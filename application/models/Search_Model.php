<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_Direction {
	const Wholesaler_To_Supplier = 1;
	const Supplier_To_Wholesaler = 2;
}

Class BaseModel extends CI_Model {
	public $processnotification = true;
	public function _constructor() {

	}

	public function query($sql) {
		log_message('debug', "Query => $sql");

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{
			$result = $query->result_array();
            return $result;
		}
		else
		{
			//return $sql;
			return false;
		}
	}

	//Common methods
    public function save($tbl,$data) 
	{    
		try {
			if($this->db->insert($tbl,$data))
			{
				
				return $this->db->insert_id();
			}
			else
			{
				echo $this->db->last_query();die();
				return false;
			}
		}	
		catch(Exception $ex) {
			log_message('error', $ex);
		}
	}
	//end of Common methods

	public function getNotifyPayload($ntfyfeed) {
		log_message('debug', "BaseModel::getNotifyPayload - Payload: ".json_encode($ntfyfeed));

		$doctype = strtolower($ntfyfeed['doctype']);
		$docno = intval($ntfyfeed['docno']);
		$template = strtolower($ntfyfeed['template']);

		$ntfypayload = [];

		switch($doctype) {
			case 'booking':
				$ntfypayload['template'] = $template;
				$doc = $this->getBookingInfo4Notification($docno);
				$ntfypayload['document'] = $doc;
				$ntfypayload['payload'] = false;
				if($doc) {	
					$ntfypayload['payload'] = array(
						'fname' => $doc['customer_username'],
						'lname' => '',
						'email' => $doc['customer_username'].' <'.$doc['customer_email'].'>',
						'reply_email' => $doc['seller_username'].' <'.$doc['seller_email'].'>',
						'data' => '',
						'order' => array(
							'orderid' => 'BK-'.$doc['bookingid'],
							'orddate' => date("Y-m-d H:i:s", strtotime($doc['booking_date'].'+05:30')),
							'value' => floatval($doc['price']),
							'status' => $doc['status'],
							'sector' => $doc['source'].' - '.$doc['destination'],
							'departure_date' => date("Y-m-d H:i:s", strtotime($doc['departure_date_time'].'+05:30')),
							'arrival_date' => date("Y-m-d H:i:s", strtotime($doc['arrival_date_time'].'+05:30')),
							'pax' => intval($doc['qty']).' + '.intval($doc['infant']).'(Infant)',
							'trip_type' => $doc['trip_type'],
							'ticket_no' => $doc['ticket_no'],
						),
						'sender' => array(
							'name' => $doc['seller_companyname'],
							'email' => $doc['seller_companyname'].' <'.$doc['seller_email'].'>',
							'siteurl' => $doc['seller_url'],
							'undersigned' => 'Operation - '.$doc['seller_companyname'],
							'mobile' => $doc['seller_mobile']
						)
					);
				}

				break;	
			default:
				log_message('debug', "BaseModel::getNotifyPayload - DocType invalid");
		}

		return $ntfypayload;
	}

	public function getBookingInfo4Notification($bookingid) {
		$sql = "select 	sl_cmp.id as seller_companyid, sl_cmp.display_name as seller_companyname, sl_cmp.baseurl as seller_url, cus_cmp.id as customer_companyid, cus_cmp.display_name as customer_companyname, 
						sl_usr.id as seller_userid, sl_usr.name as seller_username, sl_usr.email as seller_email, sl_usr.mobile as seller_mobile, sl_usr.type as seller_type,
						cus_usr.id as customer_userid, cus_usr.name as customer_username, cus_usr.email as customer_email, cus_usr.mobile as customer_mobile, cus_usr.type as customer_type
						,bk.id as bookingid, bk.booking_date, case when bk.status=0 then 'Pending' else (case when bk.status=2 then 'Processed' else (case when bk.status=8 then 'Rejected' else 'In-Process' end) end) end as status
						,tk.id as ticketid, tk.ticket_no, src.city as source, dst.city as destination, tk.departure_date_time, tk.arrival_date_time, tk.flight_no, tk.terminal, 
						air.airline, air.aircode,
						bk.total as price, bk.qty, bk.adult, bk.child, bk.infant, case when bk.trip_type=1 then 'ONE_WAY' else 'ROUND_TRIP' end as trip_type
				from bookings_tbl bk
				inner join company_tbl sl_cmp on bk.seller_companyid=sl_cmp.id and sl_cmp.active=1
				inner join user_tbl sl_usr on bk.seller_userid=sl_usr.id and sl_usr.active=1
				inner join company_tbl cus_cmp on bk.customer_companyid=cus_cmp.id and sl_cmp.active=1
				inner join user_tbl cus_usr on bk.customer_userid=cus_usr.id and sl_usr.active=1
				inner join tickets_tbl tk on tk.id=bk.ticket_id
				inner join city_tbl src on src.id=tk.source
				inner join city_tbl dst on dst.id=tk.destination
				inner join airline_tbl air on air.id=tk.airline
				where bk.id=$bookingid";

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{
			$booking = $query->result_array()[0];
            return $booking;
		}
		else
		{
			//return $sql;
			return false;
		}
	}

	public function notify($ntfyfeed) {
		$ntfyinfo = $this->getNotifyPayload($ntfyfeed);
		log_message('debug', "BaseModel::notify - Notification feed provided : ".json_encode($ntfyfeed));
		//Notify to user
		if($this->processnotification && $ntfyinfo && isset($ntfyinfo['document']) && isset($ntfyinfo['payload']) && $ntfyinfo['payload']) {
			$tbl = 'notification_tbl';
			$document = $ntfyinfo['document'];
			$payload = $ntfyinfo['payload'];
			$payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
			$arr = array(
				'date' => date("Y-m-d H:i:s"),
				'mode' => 'EMAIL',
				'companyid' => $document['seller_companyid'],
				'payloaddata' => $payload,
				'templatename' => $ntfyfeed['template'],
				'module' => $ntfyfeed['template'],
				'receipients' => $document['customer_username'].' <'.$document['customer_email'].'>',
				'status' => 0,
				'isread' => 0,
				'created_on' => date("Y-m-d H:i:s"),
				'created_by' => $document['customer_userid']
			);

			log_message('debug', "BaseModel::notify - Notification table data : ".json_encode($arr));
			$notifyid = $this->save($tbl, $arr);

			log_message('debug', "BaseModel::notify - Notifier Id : $notifyid | Payload: $payload");
		}		
	}
}

Class Search_Model extends BaseModel
{
	protected $default_infant_price = 1500;

    public function __construct()
	{ 
	    date_default_timezone_set("Asia/Calcutta");   
		
	}

	public function search_one_wayV2($arr) {
		// $dt_from = date("Y-m-d H:i:s");
		$companyid = $arr["companyid"];
		$source = $arr["source"];
		$destination = $arr["destination"];
		$from_date = $arr["from_date"];
		$to_date = $arr["to_date"];
		$triptype = $arr["trip_type"];
		$approved = $arr["approved"];
		$available = $arr["available"];
		$no_of_person = $arr["no_of_person"];
		$infant_price = $this->default_infant_price;

		$sql = "select 	tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city as source_city, ct1.code as source_city_code, ct2.city as destination_city, ct2.code as destination_city_code, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person, tkt.class, 
						tkt.no_of_stops, tkt.data_collected_from, tkt.sale_type, tkt.refundable, tkt.total, al.display_name as airline, al.id as airlineid, al.image, al.aircode as aircode, tkt.ticket_no, tkt.price, cm.id as companyid, cm.display_name as companyname, tkt.user_id ,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, tkt.tag, 
						tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id, if(tkt.price_infant<=0, $infant_price, tkt.price_infant) as infant_price, tkt.fare_rule, tkt.adult_count as adult, tkt.child_count as child, tkt.infant_count as infant, tkt.remarks, 
						max(ltkt.departure_date_time) as dept_date_time, max(ltkt.arrival_date_time) as arrv_date_time, max(ltkt.airline) as airlinecode, max(ltkt.adultbasefare) as adultbasefare, max(ltkt.adult_tax_fees) as adult_tax_fees, 
						max(TIMESTAMPDIFF(MINUTE, ltkt.departure_date_time, ltkt.arrival_date_time)) as timediff, max(ltkt.departure_terminal) as departure_terminal, max(ltkt.arrival_terminal) as arrival_terminal, max(ltkt.adultbasefare+ltkt.adult_tax_fees+200) as adult_total
				from tickets_tbl tkt 
				inner join city_tbl ct1 on tkt.source=ct1.id 
				inner join city_tbl ct2 on tkt.destination=ct2.id 
				inner join airline_tbl al on al.id=tkt.airline 
				inner join company_tbl cm on tkt.companyid=cm.id 
				inner join 
				(  
					(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id 
					from wholesaler_tbl spl  
					inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1 
					inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services' 
					inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
					inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1 
					where spl.salerid=$companyid) 
					union all 
					(select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id 
					from company_tbl cm 
					inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1 
					where cm.id=$companyid 
					limit 1) 
				) as rpt on tkt.companyid = rpt.supplierid 
				left outer join live_tickets_tbl ltkt on ltkt.source=tkt.source and tkt.destination=ltkt.destination and al.aircode=ltkt.carrierid and ltkt.active=1  
						and ltkt.departure_date_time>=DATE_SUB(tkt.departure_date_time, INTERVAL 15 MINUTE)  
						and ltkt.departure_date_time<=DATE_ADD(tkt.departure_date_time, INTERVAL 15 MINUTE) 
						and ltkt.airline is not null 
				where tkt.source=$source and tkt.destination=$destination and tkt.trip_type='$triptype' and tkt.available='$available' and tkt.approved=$approved  
				and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')='$from_date' and tkt.no_of_person>=$no_of_person 
				group by tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city, ct2.city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person  
						, tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.id, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id, cm.display_name, tkt.user_id ,tkt.data_collected_from,  
						tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id,  
						tkt.price_infant, tkt.fare_rule, tkt.adult_count, tkt.child_count, tkt.infant_count, tkt.remarks
				order by (price + admin_markup + markup)";

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			//return $sql;
			return false;
		}         	
	}

	public function search_round_wayv2($arr) {
		// $dt_from = date("Y-m-d H:i:s");
		$companyid = $arr["companyid"];
		$source = $arr["source"];
		$destination = $arr["destination"];
		$from_date = $arr["from_date"];
		$to_date = $arr["to_date"];
		$triptype = $arr["trip_type"];
		$approved = $arr["approved"];
		$available = $arr["available"];
		$no_of_person = $arr["no_of_person"];
		$infant_price = $this->default_infant_price;

		$sql = "select 	tkt.direction, tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city as source_city, ct1.code as source_city_code, ct2.city as destination_city, ct2.code as destination_city_code, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person, tkt.class, 
			tkt.no_of_stops, tkt.data_collected_from, tkt.sale_type, tkt.refundable, tkt.total, al.display_name as airline, al.id as airlineid, al.image, al.aircode as aircode, tkt.ticket_no, tkt.price, cm.id as companyid, cm.display_name as companyname, tkt.user_id ,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, tkt.tag, 
			tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id, if(tkt.price_infant<=0, $infant_price, tkt.price_infant) as infant_price, tkt.fare_rule, tkt.adult_count as adult, tkt.child_count as child, tkt.infant_count as infant, tkt.remarks, 
			max(ltkt.departure_date_time) as dept_date_time, max(ltkt.arrival_date_time) as arrv_date_time, max(ltkt.airline) as airlinecode, max(ltkt.adultbasefare) as adultbasefare, max(ltkt.adult_tax_fees) as adult_tax_fees, 
			max(TIMESTAMPDIFF(MINUTE, ltkt.departure_date_time, ltkt.arrival_date_time)) as timediff, max(ltkt.departure_terminal) as departure_terminal, max(ltkt.arrival_terminal) as arrival_terminal, max(ltkt.adultbasefare+ltkt.adult_tax_fees+200) as adult_total
			from 
			(
				select *, 'OB' as direction
				from tickets_tbl tkt1
				where 	(tkt1.source=$source and tkt1.destination=$destination) and tkt1.trip_type='ONE' and tkt1.available='YES' and tkt1.approved=$approved  
						and (DATE_FORMAT(tkt1.departure_date_time,'%Y-%m-%d')='$from_date') and tkt1.no_of_person>=$no_of_person
				union
				select *, 'IB' as direction 
				from tickets_tbl tkt2
				where 	(tkt2.source=$destination and tkt2.destination=$source) and tkt2.trip_type='ONE' and tkt2.available='YES' and tkt2.approved=$approved  
						and (DATE_FORMAT(tkt2.departure_date_time,'%Y-%m-%d')='$to_date') and tkt2.no_of_person>=$no_of_person
				union
				select *, 'OI' as direction 
				from tickets_tbl tkt3
				where 	(tkt3.source=$source and tkt3.destination=$destination and tkt3.source1=$destination and tkt3.destination1=$source) and tkt3.trip_type='ROUND' and tkt3.available='YES' and tkt3.approved=$approved  
						and (DATE_FORMAT(tkt3.departure_date_time,'%Y-%m-%d')='$from_date' and DATE_FORMAT(tkt3.departure_date_time1,'%Y-%m-%d')='$to_date') and tkt3.no_of_person>=$no_of_person
			) tkt 
			inner join city_tbl ct1 on tkt.source=ct1.id 
			inner join city_tbl ct2 on tkt.destination=ct2.id 
			inner join airline_tbl al on al.id=tkt.airline 
			inner join company_tbl cm on tkt.companyid=cm.id 
			inner join 
			(  
				(
					select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id 
					from wholesaler_tbl spl  
					inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1 
					inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services' 
					inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
					inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1 
					where spl.salerid=$companyid
				) 
				union all 
				(
					select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id 
					from company_tbl cm 
					inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1 
					where cm.id=$companyid 
					limit 1
				) 
			) as rpt on tkt.companyid = rpt.supplierid 
			left outer join live_tickets_tbl ltkt on ltkt.source=tkt.source and tkt.destination=ltkt.destination and al.aircode=ltkt.carrierid and ltkt.active=1  
					and ltkt.departure_date_time>=DATE_SUB(tkt.departure_date_time, INTERVAL 15 MINUTE)  
					and ltkt.departure_date_time<=DATE_ADD(tkt.departure_date_time, INTERVAL 15 MINUTE) 
					and ltkt.airline is not null 
			group by tkt.direction, tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city, ct2.city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person  
					, tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.id, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id, cm.display_name, tkt.user_id ,tkt.data_collected_from,  
					tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id,  
					tkt.price_infant, tkt.fare_rule, tkt.adult_count, tkt.child_count, tkt.infant_count, tkt.remarks, tkt.total, tkt.tag, tkt.markup
			order by (price + admin_markup + markup)";

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			//return $sql;
			return false;
		}
	}
	
	 public function search_one_way($arr) 
	{
		//$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops, t.data_collected_from, t.aircode,t.flight_no');
		$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops, t.data_collected_from, t.aircode,t.flight_no, t.tag, 
		max(ltkt.departure_date_time) as dept_date_time, max(ltkt.arrival_date_time) as arrv_date_time, max(ltkt.airline) as airline, max(ltkt.adultbasefare) as adultbasefare, max(ltkt.adult_tax_fees) as adult_tax_fees, 
		max(TIMESTAMPDIFF(MINUTE, ltkt.departure_date_time, ltkt.arrival_date_time)) as timediff, max(ltkt.departure_terminal) as departure_terminal, max(ltkt.arrival_terminal) as arrival_terminal, max(ltkt.adultbasefare+ltkt.adult_tax_fees+200) as adult_total');
		$this->db->from('tickets_tbl t');
		$this->db->join('airline_tbl a', 'a.id = t.airline','inner');
		$this->db->join('city_tbl c', 'c.id = t.source', 'inner');
		$this->db->join('city_tbl ct', 'ct.id = t.destination', 'inner');
		$this->db->join('live_tickets_tbl ltkt', 'ltkt.source=t.source and t.destination=ltkt.destination and a.aircode=ltkt.carrierid and ltkt.active=1 
		and ltkt.departure_date_time>=DATE_SUB(t.departure_date_time, INTERVAL 15 MINUTE) 
		and ltkt.departure_date_time<=DATE_ADD(t.departure_date_time, INTERVAL 15 MINUTE)
		and ltkt.airline is not null', 'left');
		$this->db->where($arr);
		$this->db->order_by("(price + admin_markup + markup)", "asc"); //for ordering data in the list while searching one way
		$this->db->group_by("t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,
		t.sale_type,t.refundable,c.city,ct.city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops, t.data_collected_from, 
		t.aircode, t.flight_no");
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		
		if($query->num_rows() > 0) 
		{
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
         	
	}
	

	public function best_offer($arr) 
	{            		  
		$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops,t.trip_type, t.tag');
		$this->db->from('tickets_tbl t');
		$this->db->join('airline_tbl a', 'a.id = t.airline');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->where($arr);
		$this->db->order_by("t.total","ASC");
		$this->db->limit(6,0);
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		
		if($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
         	
    }
    
    public function best_offer_num($arr) 
	{            		  
		$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops,t.trip_type, t.tag');
		$this->db->from('tickets_tbl t');
		$this->db->join('airline_tbl a', 'a.id = t.airline');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->where($arr);
		$this->db->order_by("t.total","ASC");
		$this->db->limit(6,0);
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		
							
        return $query->num_rows();		
		       	
         	
    }
    
	public function search_round_trip($arr) 
	{            		  
		$this->db->select('t.user_id,t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.price,t.total,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,c1.city as source_city1,ct1.city as destination_city1,t.no_of_person,t.class,a.image,t.no_of_stops,t.no_of_stops1, t.companyid, t.tag');
		$this->db->from('tickets_tbl t');
		$this->db->join('airline_tbl a', 'a.id = t.airline','left');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->where($arr);
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		
		if($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
         	
    }
	public function flight_details($id, $companyid=-1)
	{
		$arr=array("t.id"=>$id);
		$this->db->select('u.id as user_id,t.user_id as uid,t.id,t.source,t.destination,t.source1,t.destination1,t.ticket_no,t.pnr,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.flight_no,t.flight_no1,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.departure_date_time1,t.arrival_date_time1,t.flight_no1,t.total,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,c1.city as source_city1,ct1.city as destination_city1,t.class,t.class1,t.no_of_person,a.image,t.airline,t.airline1,t.trip_type,t.price,t.baggage,t.meal,t.markup,t.discount,t.availibility,t.aircode,t.aircode1,t.no_of_stops,t.no_of_stops1,t.remarks,t.approved,t.remarks,t.stops_name,t.stops_name1,t.available, t.tag, t.companyid, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id', FALSE);
		$this->db->from('tickets_tbl t');
		$this->db->join("(
				(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id
				from wholesaler_tbl spl 
				inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1
				inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services'
				inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
				inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1
				where spl.salerid=$companyid)
				union all
				(select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id 
				from company_tbl cm 
				inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1 
				where cm.id=$companyid 
				limit 1) 
			) as rpt", 'companyid = rpt.supplierid', 'inner', FALSE);
		$this->db->join('airline_tbl a', 'a.id = t.airline','left', FALSE);
		$this->db->join('city_tbl c', 'c.id = t.source', 'inner', FALSE);
		$this->db->join('city_tbl ct', 'ct.id = t.destination', 'inner', FALSE);
		$this->db->join('user_tbl u', 't.user_id = u.id', 'inner', FALSE);
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left', FALSE);
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left', FALSE);
		$this->db->where($arr, NULL, FALSE);
		$query = $this->db->get();					
		$qry = $this->db->last_query();
		
		if($query->num_rows() > 0) 
		{					
           return $query->result_array();		
		  // print_r($query->result_array());die();
		}
		else
		{
			  return false;
		}   
	}
	
	public function company_setting($companyid, $asobject=false) {
		if(!isset($asobject)) {
			$asobject = false;
		}

        $arr=array("target_object_type"=>"company", "target_object_id"=>$companyid);
		$this->db->select('*');
		$this->db->from('attributes_tbl');
		$this->db->order_by('category asc, display_name asc');
		
		$this->db->where($arr);
		$query = $this->db->get();					
		$data = array(
			'setting_id' => $companyid,
			'site_title' => '',
			'phone_no' => '',
			'fax' => '',
			'email' => '',
			'address' => '',
			'logo' => '',
			'site_banner' => '',
			'facebook_link' => '',
			'twitter_link' => '',
			'youtube_link' => '',
			'pinterest_link' => '',
			'instagram_link' => '',
			'google_link' => '',
			'bank_name' => '',
			'branch' => '',
			'acc_no' => '',
			'acc_name' => '',
			'ifsc' => '',
			'map' => '',
			'bank_accounts' => '',
			'payment_gateway' => '',
			'configuration' => '',
			'api_integration' => '',
			'service_charge' => 0.00,
			'cgst' => 0.00,
			'igst' => 0.00,
			'infant_price' => $this->default_infant_price /* This is the detault infant price, which will be overwritten by the company specific infant_price */
		);
		
		if ($query->num_rows() > 0) 
		{	
			foreach ($query->result_array() as $row) {
				if($asobject && isset($row['datatype']) && $row['datatype']=='object') {
					$data[$row['code']] = json_decode($row['datavalue'], TRUE);
				}
				else {
					$data[$row['code']] = $row['datavalue'];
				}
			}
			return array($data);
		}
		else
		{
			  return false;
		}         	
	}

	public function setting() 
	{    
        $arr=array("setting_id"=>"2");  	
		$this->db->select('*');
		$this->db->from('setting_tbl');
		
		$this->db->where($arr);
		$query = $this->db->get();					
		
		if ($query->num_rows() > 0) 
		{					
			$result = $query->result_array();
            return $result;
		}
		else
		{
			  return false;
		}         	
	}	
	
    // public function save($tbl,$data) 
	// {    
	// 	try {
	// 		if($this->db->insert($tbl,$data))
	// 		{
				
	// 			return $this->db->insert_id();
	// 		}
	// 		else
	// 		{
	// 			echo $this->db->last_query();die();
	// 			return false;
	// 		}
	// 	}	
	// 	catch(Exception $ex) {
	// 		log_message('error', $ex);
	// 	}
	// }
	
	// public function update($tbl, $data, $filter) {
	// 	// $this->db->update('employee_master',$data,array('emp_ID' => 1));
	// 	if($this->db->update($tbl, $data, $filter)) {
	// 		return array('effected_rows' => $this->db->affected_rows(), 'id' => -1);
	// 	}
	// 	else
	// 	{
	// 		echo $this->db->last_query();die();
	// 		return false;
	// 	}
	// }

    public function get_bookingby_correlationid($correlationid) 
	{    
		$qry = "select 	u.name,u.email,u.mobile,b.id as booking_id, b.ticket_id,t.ticket_no,c.city as source,ct.city as destination,a.display_name as airline,
						case when t.direction='OB' then 'Outbound' else 'Inbound' end as direction, 
						t.class,t.departure_date_time,t.arrival_date_time,t.trip_type,t.departure_terminal,t.arrival_terminal,t.flight_no,
						case when u.is_admin=1 then 'EMP' else u.type end as type, u.address, 
						b.admin_markup, b.booking_date as date, b.srvchg as service_charge,b.sgst,b.cgst,b.igst,(b.price) as rate,b.qty, b.adult, b.child, b.infant, b.infant_price, 
						((b.price * b.qty) + (b.infant_price * b.infant)) as amount, (b.total) as total, b.costprice, b.rateplanid, 
						case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' end as status, 
						b.customer_userid, b.customer_companyid, b.seller_userid, b.seller_companyid, a.image,b.booking_confirm_date, 
						ifnull((select status from booking_activity_tbl where booking_id=b.id and (requesting_by & 4)=4 order by activity_date limit 1),0) as seller_status, 
						a.aircode, a.image
				from bookings_flights_tbl t
				inner join bookings_tbl b on b.ticket_id = t.ticket_id
				inner join airline_tbl a on a.id = t.airline
				inner join city_tbl c on c.id = t.source
				inner join city_tbl ct on ct.id = t.destination
				inner join user_tbl u on b.customer_userid =u.id
				where b.correlationid = '$correlationid' 
				order by b.id";
		//$query = $this->db->get();
		$query = $this->db->query($qry);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }	

    public function booking_details($id) 
	{    
		$qry = "select 	cus.prefix,cus.email as cemail, cus.type as passenger_type, cus.first_name,cus.last_name,cus.age,cus.dob,cus.mobile_no,cus.pnr, cus.airline_ticket_no, u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,c.city as source,c1.city as source1,
						ct.city as destination,ct1.city as destination1,a.display_name as airline,a1.display_name as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,
						t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1, case when u.is_admin=1 then 'EMP' else u.type end as type, u.address, 
						case when cus.type=1 then 'Adult' when cus.type=2 then 'Child' else 'Infant' end as passenger_type_code, b.admin_markup, b.correlationid, 
						b.booking_date as date, b.srvchg as service_charge,b.sgst,b.cgst,b.igst,(b.price) as rate,b.qty, b.adult, b.child, b.infant, b.infant_price, ((b.price * b.qty) + (b.infant_price * b.infant)) as amount, (b.total) as total, b.costprice, b.rateplanid, 
						case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' end as status, 
						b.customer_userid, b.customer_companyid, b.seller_userid, b.seller_companyid,
						a.image,b.booking_confirm_date, ifnull((select status from booking_activity_tbl where booking_id=$id and (requesting_by & 4)=4 order by activity_date limit 1),0) as seller_status, 
						a.aircode, a.image, a1.aircode as aircode1, a1.image as image1,  
						case when cus.status=2 then 'APPROVED' when cus.status=127 then 'REMOVED' else 'PENDING' end as customer_status 
				from tickets_tbl as t 
				inner join bookings_tbl b on b.ticket_id = t.id
				inner join airline_tbl a on a.id = t.airline
				inner join city_tbl c on c.id = t.source
				inner join city_tbl ct on ct.id = t.destination
				inner join user_tbl u on b.customer_userid =u.id
				left outer join city_tbl c1 on c1.id = t.source1 
				left outer join city_tbl ct1 on ct1.id = t.destination1
				left outer join airline_tbl a1 on a1.id = t.airline1 
				left outer join customer_information_tbl cus on b.id =cus.booking_id
				where b.id = $id";
		//$query = $this->db->get();
		$query = $this->db->query($qry);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }
	
	 public function refrence_booking_details($id) 
	{    
        $arr=array("b.booking_id"=>$id);  	
		$this->db->select('cus.prefix,cus.email as cemail, cus.type as passenger_type, cus.first_name,cus.last_name,cus.age,cus.dob,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status');
		$this->db->from('tickets_tbl as t');
		$this->db->join('refrence_booking_tbl b', 'b.ticket_id = t.id');
		$this->db->join('airline_tbl a', 'a.id = t.airline');
		$this->db->join('airline_tbl a1', 'a1.id = t.airline1','left');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->join('user_tbl u', 'b.customer_id =u.id');
		$this->db->join('customer_information_tbl cus', 'b.id =cus.refrence_id',"left");
		
		$this->db->where($arr);
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }
	
	 public function get_booking_details($id) 
	{    
	    
        $arr=array("b.id"=>$id);  	
		$this->db->select('cus.prefix,cus.email as cemail,cus.first_name, cus.type as passenger_type, cus.last_name,cus.age,cus.dob,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status');
		$this->db->from('tickets_tbl as t');
		$this->db->join('refrence_booking_tbl b', 'b.ticket_id = t.id');
		$this->db->join('airline_tbl a', 'a.id = t.airline');
		$this->db->join('airline_tbl a1', 'a1.id = t.airline1','left');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->join('user_tbl u', 'b.customer_id =u.id');
		$this->db->join('customer_information_tbl cus', 'b.booking_id =cus.booking_id',"left");
		
		$this->db->where($arr);
		$this->db->order_by("cus.id","ASC");
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }
	public function supplier_booking_details($id) 
	{    
	    
        $arr=array("b.id"=>$id);  	
		$this->db->select('cus.prefix,cus.email as cemail,cus.first_name, cus.type as passenger_type, cus.last_name,cus.age,cus.dob,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status,t.price,t.markup');
		$this->db->from('tickets_tbl as t');
		$this->db->join('refrence_booking_tbl b', 'b.ticket_id = t.id');
		$this->db->join('airline_tbl a', 'a.id = t.airline');
		$this->db->join('airline_tbl a1', 'a1.id = t.airline1','left');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->join('user_tbl u', 'b.seller_id =u.id');
		$this->db->join('customer_information_tbl cus', 'b.booking_id =cus.booking_id',"left");
		
		$this->db->where($arr);
		$this->db->order_by("cus.id","ASC");
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }
	public function filter_city($source,$trip_type) 
	{		
	    $today=date("Y-m-d");
		
        $arr=array(
		"t.source"=>$source,
		"t.trip_type"=>$trip_type,
		"DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')>="=>$today,
		"t.approved"=>1,
		"t.available"=>"YES");  
		
		$this->db->select('c.*');
		$this->db->from('tickets_tbl as t');
		$this->db->join('city_tbl c', 'c.id = t.destination');				
		$this->db->where($arr);
		$this->db->group_by('c.id');
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }
	
	public function search_available_date($source,$destination,$trip_type, $companyid=1) 
	{		
	    $today=date("Y-m-d");
		
        // $arr=array(
		// "source"=>$source,
		// "destination"=>$destination,
		// "trip_type"=>$trip_type,
		// "DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>$today,
		// "approved"=>1,
		// "available"=>"YES");  

		$arr=array(
			"source"=>$source,
			"destination"=>$destination,
			"trip_type"=>$trip_type,
			"DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>$today,
			"approved"=>1,
			"no_of_person>"=>0);  
			
		//$this->db->select("DATE_FORMAT(departure_date_time, '%d-%m-%Y') as departure_date_time, (price + admin_markup + markup) as price, rpt.rate_plan_id");
		$this->db->select("DATE_FORMAT(departure_date_time, '%d-%m-%Y') as departure_date_time, (price) as price, admin_markup, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id");
		$this->db->from('tickets_tbl');					
		$this->db->join("( 
			(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id 
			from wholesaler_tbl spl 
			inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1 
			inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services' 
			inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
			inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1 
			where spl.salerid=$companyid)     
			union all 
			(select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id 
			from company_tbl cm 
			inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1 
			where cm.id=$companyid 
			limit 1)		
		) as rpt", 'companyid = rpt.supplierid', 'inner');
		$this->db->where($arr);
		$this->db->where("(companyid in ( 
			select spl.supplierid 
			from supplier_tbl spl 
			inner join supplier_services_tbl spd on spl.id=spd.supplier_rel_id and spd.active=1 and spd.allowfeed=1 
			inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active=1 and mtd.associated_object_type='services' 
			where spl.companyid=$companyid 
		) or companyid=$companyid)");
		$this->db->order_by("(price + admin_markup + markup)", "asc"); //for ordering data in the list while searching one way
		
		$query = $this->db->get();
		$qry = $this->db->last_query();
		//return $this->db->last_query();

		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }
    public function search_available_date1($source,$destination,$trip_type) 
	{		
	    $today=date("Y-m-d");
		
        $arr=array(
		"source"=>$source,
		"destination"=>$destination,
		"trip_type"=>$trip_type,
		"DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>$today,
		"approved"=>1,
		"available"=>"YES");  
		
		$this->db->select("DATE_FORMAT(departure_date_time, '%d-%m-%Y') as departure_date_time1, (price + admin_markup + markup) as price1");
		$this->db->from('tickets_tbl');					
		$this->db->where($arr);
		$this->db->order_by("(price + admin_markup + markup)", "asc"); //for ordering data in the list while searching one way
		
		$query = $this->db->get();							
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			return false;
		}         	
	}
	
    public function update($tbl,$data,$arr) 
	{    
        $this->db->where($arr);
		if ($this->db->update($tbl,$data)) 
		{					
            return true;
		}
		else
		{
			return false;
		}
    }

     public function get_post($id)
	 {
		$arr=array("id"=>$id); 
		$this->db->select("*");
		$this->db->from('post_tbl');					
		$this->db->where($arr);
		
		$query = $this->db->get();							
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
	 }

	public function getTableData($table, $arr) {
		$this->db->select("*");
		$this->db->from($table);

		foreach ($arr as $key => $value) {
			if(is_numeric($key)) {
				$this->db->where($value, null, false);
			}
			else {
				$this->db->where($key, $value, false);
			}
		}
		
		$query = $this->db->get();							
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
	}

	public function get($table, $arr) {
		// $arr=array("id"=>$id); 
		$this->db->select("*");
		$this->db->from($table);
		if($arr && is_array($arr) && count($arr)>0) {
			$this->db->where($arr, null, false);
		}
		
		$query = $this->db->get();
		$qry = $this->db->last_query();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
	 }

     public function terms($companyid = 1, $mode = 'GENERAL')
	 {
		 
		$this->db->select("*");
		$this->db->from('term_tbl');					
		if($companyid && intval($companyid)>0) {
			$this->db->where(array('companyid' => $companyid), null, false);
		}
		if($mode && $mode !== '') {
			$this->db->where(array('mode' => "'$mode'"), null, false);
		}
		
		$query = $this->db->get();							
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();
		}
		else
		{
			  return false;
		}         	
	 }

      public function testimonial()
	 {
		 
		$this->db->select("t.*,u.name");
		$this->db->from('testimonials_tbl as t');					
		
		$this->db->join('user_tbl u', 'u.id = t.user_id');				
	
		
		$query = $this->db->get();							
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
	 }	

      public function faq()
	 {
		 
		$this->db->select("*");
		$this->db->from('faq_tbl');					
		
		
		$query = $this->db->get();							
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
	 }

	 public function get_tickets($companyid, $dt_from=NULL) {
		if($dt_from==NULL || $dt_from=='') {
			$dt_from = date("Y-m-d H:i:s");
		}

		$sql = "select tkt.id, tkt.source sourceid, ct1.city source, tkt.destination destinationid, ct2.city destination, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no   
					,tkt.terminal, tkt.no_of_person, tkt.class, tkt.airline airlineid, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, tkt.price_child, tkt.price_infant, tkt.cancel_rate, tkt.booking_freeze_by, sl1.wsl_markup_rate as admin_markup, cm.id as companyid, tkt.user_id   
					,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, tkt.last_sync_key, tkt.approved, sl1.display_name as supplier, tkt.sale_type, tkt.refundable, tkt.pnr,  tkt.baggage, tkt.meal,  
					sl1.slr_markup_rate as markup_rate, 0 as markup_type, sl1.slr_srvchg as srvchg_rate, sl1.slr_cgst as cgst_rate, sl1.slr_sgst as sgst_rate, sl1.slr_igst as igst_rate, sl1.slr_allowfeed as allowfeed, sl1.service, sl1.owner_companyid,  
					sl1.wsl_markup_rate as wsl_markup_rate, sl1.wsl_srvchg as wsl_srvchg_rate, sl1.wsl_cgst as wsl_cgst_rate, sl1.wsl_sgst as wsl_sgst_rate, sl1.wsl_igst as wsl_igst_rate, sl1.wsl_allowfeed as wsl_allowfeed,  
					case when sl1.trans_type=1 then 'ON REQUEST' when sl1.trans_type=0  then 'LIVE' when sl1.trans_type=-1 then 'ON REQUEST' end trans_type   
				from tickets_tbl tkt 
				inner join city_tbl ct1 on tkt.source=ct1.id
				inner join city_tbl ct2 on tkt.destination=ct2.id
				inner join airline_tbl al on al.id=tkt.airline
				inner join company_tbl cm on tkt.companyid=cm.id
				inner join
				( 
					(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id, slr_rp.markup as slr_markup_rate, wsl_rp.markup as wsl_markup_rate, 
					slr_rp.srvchg as slr_srvchg, wsl_rp.srvchg as wsl_srvchg, slr_rp.cgst as slr_cgst, wsl_rp.cgst as wsl_cgst, 
					slr_rp.sgst as slr_sgst, wsl_rp.sgst as wsl_sgst, slr_rp.igst as slr_igst, wsl_rp.igst as wsl_igst, slr_rp.disc as slr_disc, wsl_rp.disc as wsl_disc, cmm1.display_name, 
					spd.allowfeed as slr_allowfeed, whd.allowfeed as wsl_allowfeed, mtd.datavalue as service, spl.salerid as owner_companyid, spd.transaction_type as trans_type
					from wholesaler_tbl spl 
					inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1
					inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services'
					inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
					inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1
					inner join company_tbl cmm1 on cmm1.id=spl.companyid
					left join rateplans_vw slr_rp on spd.rate_plan_id=slr_rp.rateplanid
					left join rateplans_vw wsl_rp on whd.rate_plan_id=wsl_rp.rateplanid
					where spl.salerid=$companyid)
					union all
					(select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id, slr_rp.markup as slr_markup_rate, 0 as wsl_markup_rate, 
					slr_rp.srvchg as slr_srvchg, 0 as wsl_srvchg, slr_rp.cgst as slr_cgst, 0 as wsl_cgst, 
					slr_rp.sgst as slr_sgst, 0 as wsl_sgst, slr_rp.igst as slr_igst, 0 as wsl_igst, slr_rp.disc as slr_disc, 0 as wsl_disc, cm.display_name, 
					1 as slr_allowfeed, 0 as wsl_allowfeed, 'Coupon Flight Tickets' as service, cm.id as owner_companyid, -1 as trans_type 
					from company_tbl cm
					inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1
					left join rateplans_vw slr_rp on rp.id=slr_rp.rateplanid
					where cm.id=$companyid
					limit 1)
				) as sl1 on tkt.companyid = sl1.supplierid
				where DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d %H:%i:%s')>='$dt_from' and sl1.owner_companyid=$companyid
				order by sl1.display_name asc, ct1.city asc, ct2.city asc, tkt.departure_date_time asc";

		//tkt.no_of_person>0 - this condition was there which has been removed. So that even ticket, no_of_passengers becode zero it should be visible.
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
	 }

	public function get_bookings($companyid=-1, $userid=-1, $fromdate=NULL, $todate=NULL) {
		// (((b.customer_userid=$userid or $userid=-1) and ($companyid=-1 or b.customer_companyid=$companyid)) or  
		// $sql = "SELECT 	t.departure_date_time, t.arrival_date_time, b.id,b.booking_date as date, b.booking_confirm_date as process_date,b.pnr,(b.price+b.markup) as rate,b.qty,((b.price+b.markup) * b.qty) as amount,(b.cgst+b.sgst) as igst,b.srvchg as service_charge,

		$fromdate = $fromdate === NULL ? '' : $fromdate;
		$todate = $todate === NULL ? '' : $todate;

		$sql = "SELECT 	t.departure_date_time, t.arrival_date_time, b.id,b.booking_date as date, b.booking_confirm_date as process_date,b.pnr,(b.price) as rate,b.infant_price, b.infant, (b.qty) as qty,((b.price) * (b.qty)) as amount,((b.cgst+b.sgst) * (b.qty)) as igst,(b.srvchg * (b.qty-b.child)) as service_charge, (b.markup) as markup,
						b.total,t.trip_type,u.user_id,u.name, us.name as seller,us.user_id as seller_id, source.city as source_city,destination.city as destination_city, t.flight_no, t.aircode, t.ticket_no, t.class,
						cc.id as customer_companyid, cc.display_name as customer_companyname, sc.id as seller_companyid, sc.display_name as seller_companyname, t.id as ticket_id,
						case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' when b.status=32 then 'REQUEST FOR CANCEL' when b.status=64 then 'REQUEST FOR HOLD' end as status,   
						ifnull(b.pbooking_id,0) as parent_booking_id, ifnull(b.message, '') as notes, ifnull(b.rateplanid,0) as rateplanid, 
						rpvw.display_name as rateplan_name, rpvw.default as isdefaultrateplan, rpvw.assigned_to as rateplan_assignedto, rpvw.markup as rateplan_markup, rpvw.srvchg as rateplan_srvchg, rpvw.cgst as rateplan_cgst, 
						rpvw.sgst as rateplan_sgst, rpvw.igst as rateplan_igst, rpvw.disc as rateplan_disc, ifnull(ucfg.field_display_name,'') as field_name, ifnull(ucfg.field_value, 0) as field_value, ifnull(ucfg.field_value_type, 0) as field_value_type, 
						ifnull(ucfg.status,0) as ucfg_status, t.price as ticket_price, t.total as ticket_total, u.type as customer_type, u.is_admin
				FROM bookings_tbl b 
				INNER JOIN tickets_tbl t ON b.ticket_id = t.id 
				INNER JOIN user_tbl u ON b.customer_userid = u.id
				INNER JOIN user_tbl us ON b.seller_userid = us.id 
				INNER JOIN company_tbl cc ON b.customer_companyid = cc.id 
				INNER JOIN company_tbl sc ON b.seller_companyid = sc.id 
				INNER JOIN city_tbl source ON source.id = t.source 
				INNER JOIN city_tbl destination ON destination.id = t.destination 
				LEFT OUTER JOIN rateplans_vw rpvw on b.rateplanid=rpvw.rateplanid
				LEFT OUTER JOIN user_config_tbl ucfg on ucfg.user_id=u.id
				WHERE ((b.seller_userid=$userid or $userid=-1) and ($companyid=-1 or b.seller_companyid=$companyid))
					  and (('$fromdate'='' or DATE_FORMAT(b.booking_date,'%Y-%m-%d %H:%i:%s')>='$fromdate') and ('$todate'='' or DATE_FORMAT(b.booking_date,'%Y-%m-%d %H:%i:%s')<='$todate')) 
				ORDER BY b.id DESC";
				// (t.sale_type!='live') and 
				// We are allowing all booking to be visible, be it live or request

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();
		}
		else
		{
			return false;
		}         	
	}

	public function get_bookings_by_query($argv) {
		$this->db->select("t.departure_date_time, t.arrival_date_time, b.id,b.booking_date as date, b.booking_confirm_date as process_date,b.pnr,(b.price+b.markup) as rate,b.qty,((b.price+b.markup) * b.qty) as amount,(b.cgst+b.sgst) as igst,b.srvchg as service_charge,
			b.total,t.trip_type,u.user_id,u.name, us.name as seller,us.user_id as seller_id, source.city as source_city,destination.city as destination_city, t.flight_no, t.aircode, t.ticket_no, t.class,
			cc.id as customer_companyid, cc.display_name as customer_companyname, sc.id as seller_companyid, sc.display_name as seller_companyname, t.id as ticket_id,
			case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' when b.status=32 then 'REQUEST FOR CANCEL' when b.status=64 then 'REQUEST FOR HOLD' end as status,  
			ifnull(b.pbooking_id,0) as parent_booking_id, ifnull(b.message, '') as notes, ifnull(b.rateplanid,0) as rateplanid");
		$this->db->from("bookings_tbl b");
		$this->db->join("tickets_tbl t", "b.ticket_id = t.id", FALSE);
		$this->db->join("user_tbl u", "b.customer_userid = u.id", FALSE);
		$this->db->join("user_tbl us", "b.seller_userid = us.id", FALSE);
		$this->db->join("company_tbl cc", "b.customer_companyid = cc.id", FALSE);
		$this->db->join("company_tbl sc", "b.seller_companyid = sc.id", FALSE);
		$this->db->join("city_tbl source", "source.id = t.source", FALSE);
		$this->db->join("city_tbl destination", "destination.id = t.destination", FALSE);
		//$this->db->where("(t.sale_type!='live')"); 
		// Commenting above one to allow API level booking as api booking is always 'live'
		$this->db->where($argv);

		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{					
            return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	public function get_booking_payment_by_query($argv) {
		$this->db->select("bk.*, act.credit, act.documentid as payment_id, sw.balance, sw.allowed_transactions");
		$this->db->from("bookings_tbl bk");
		$this->db->join("wallet_transaction_tbl wt", "bk.id=wt.trans_documentid and wt.trans_ref_type='PURCHASE'", "left", FALSE);
		$this->db->join("account_transactions_tbl act", "act.documentid=wt.id and act.document_type=2", "left", FALSE);
		$this->db->join("system_wallets_tbl sw", "sw.userid = bk.customer_userid and sw.type=2", "left", FALSE);
		$this->db->where($argv);

		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{					
            return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	public function get_booking_activity($bookingid=-1) {
		$sql = "select 	ba.activity_id, ba.pactivity_id, ba.booking_id, ba.activity_date, ba.source_userid, srcusr.name as source_username, ba.target_userid, tgtusr.name as target_username, ba.source_companyid, 
						src.display_name as source_companyname, ba.target_companyid, tgt.display_name as target_companyname, srcusr.name as activity_done_by, tgtusr.name as activity_done_to, ba.created_by, ba.created_on, 
						ba.updated_by, ba.updated_on, ba.charge_amount, ba.charge_desc,
						case when (ba.requesting_by & 1)=1 then 'Customer' when (ba.requesting_by & 2)=2 then 'Travel Agent' when (ba.requesting_by & 4)=4 then 'Wholesaler' when (ba.requesting_by & 8)=8 then 'Supplier' end as requesting_by,
						case when (ba.requesting_to & 1)=1 then 'Customer' when (ba.requesting_to & 2)=2 then 'Travel Agent' when (ba.requesting_to & 4)=4 then 'Wholesaler' when (ba.requesting_to & 8)=8 then 'Supplier' end as requesting_to,
						case when ba.status=0 then 'Pending' when ba.status=1 then 'Hold' when ba.status=2 then 'Rejected' when ba.status=4 then 'Requesy for Cancel' when ba.status=8 then 'Cancelled' when ba.status=16 then 'Revised' when ba.status=32 then 'Processed' when ba.status=64 then 'Processing' when ba.status=128 then 'Request 4 Hold' end as status    
				from booking_activity_tbl ba
				inner join bookings_tbl b on ba.booking_id=b.id
				inner join company_tbl src on src.id=ba.source_companyid and src.active=1
				inner join company_tbl tgt on tgt.id=ba.target_companyid and tgt.active=1
				inner join user_tbl srcusr on srcusr.id=ba.source_userid and srcusr.active=1
				inner join user_tbl tgtusr on tgtusr.id=ba.target_userid and tgtusr.active=1
				where b.id = $bookingid
				order by ba.activity_date DESC";
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}         	
	}

	public function get_booking_customers($bookingid=-1, $companyid=-1, $userid=-1, $fromdate=NULL, $todate=NULL) {
		$fromdate = $fromdate === NULL ? '' : $fromdate;
		$todate = $todate === NULL ? '' : $todate;

		$sql = "select cus.id, cus.prefix, cus.first_name, cus.last_name, cus.mobile_no, cus.type as passenger_type, cus.age, cus.dob, cus.email, cus.airline_ticket_no, cus.pnr, cus.ticket_fare, cus.ticket_fare, cus.costprice, cus.booking_id as cus_booking_id, cus.refrence_id, cus.companyid, cus.status,  
				b.id as booking_id, b.booking_date, b.booking_confirm_date, b.pbooking_id, b.ticket_id, b.customer_userid, b.customer_companyid, b.seller_userid, b.seller_companyid,  
				case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' when b.status=32 then 'REQUEST FOR CANCEL' when b.status=64 then 'REQUEST FOR HOLD' end as booking_status,  
				b.total, b.costprice  
			from customer_information_tbl cus  
			inner join bookings_tbl b on (b.id=cus.booking_id or b.id=cus.refrence_id)  
			inner join tickets_tbl t on t.id = b.ticket_id  
			where (cus.booking_id=$bookingid or $bookingid=-1) and  
				(('$fromdate'='' or DATE_FORMAT(b.booking_date,'%Y-%m-%d %H:%i:%s')>='$fromdate') and ('$todate'='' or DATE_FORMAT(b.booking_date,'%Y-%m-%d %H:%i:%s')<='$todate')) and   
				(((b.customer_userid=$userid or $userid=-1) and ($companyid=-1 or b.customer_companyid=$companyid)) or   
				((b.seller_userid=$userid or $userid=-1) and ($companyid=-1 or b.seller_companyid=$companyid)))";
		
		// commenting "(t.sale_type!='live') and" due to live API booking

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}         	
	}

	public function get_ticket($ticketid = -1) {
		$sql = "select t.*, u.name, c.display_name supplier, c1.city source_city, c2.city destination_city, al.airline airline_name, al.image airline_image, al.aircode  
				from tickets_tbl t 
				inner join company_tbl c on c.id = t.companyid 
				inner join user_tbl u on t.user_id=u.id 
				inner join city_tbl c1 on t.source=c1.id 
				inner join city_tbl c2 on t.destination=c2.id 
				inner join airline_tbl al on t.airline=al.id 
				where t.id=$ticketid
				order by t.departure_date_time ASC, t.price ASC";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function get_booking($id=-1) {
		$sql = "select 	b.*, 
			rpvw.display_name as rateplan_name, rpvw.default as isdefaultrateplan, rpvw.assigned_to as rateplan_assignedto, rpvw.markup as rateplan_markup, rpvw.srvchg as rateplan_srvchg, rpvw.cgst as rateplan_cgst, 
			rpvw.sgst as rateplan_sgst, rpvw.igst as rateplan_igst, rpvw.disc as rateplan_disc, ifnull(ucfg.field_display_name,'') as field_name, ifnull(ucfg.field_value, 0) as field_value, ifnull(ucfg.field_value_type, 0) as field_value_type, 
			ifnull(ucfg.status,0) as ucfg_status 
		from bookings_tbl b 
		inner join user_tbl u on b.customer_userid =u.id
		LEFT OUTER JOIN rateplans_vw rpvw on b.rateplanid=rpvw.rateplanid
		LEFT OUTER JOIN user_config_tbl ucfg on ucfg.user_id=u.id
		where b.id = $id";

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	protected function getPartnerRelationship($direction, $wholesalerid=-1, $supplierid=-1, $default_sale_type='request') {
		$sale_type = $default_sale_type;
		if($wholesalerid===-1 || $supplierid===-1) {
			return $sale_type;
		}

		if($direction === Sale_Direction::Wholesaler_To_Supplier) {
			$saller_contract = $this->get_wholesaler_contract($wholesalerid, $supplierid);
		} else if($direction === Sale_Direction::Supplier_To_Wholesaler) {
			$saller_contract = $this->get_suppliers_contract($wholesalerid, $supplierid);
		}

		if($saller_contract && is_array($saller_contract) && count($saller_contract)>0) {
			$saller_contract = $saller_contract[0];
			log_message('debug', "Contract => $direction | ".json_encode($saller_contract, TRUE));

			$sale_type = $saller_contract['sale_type'];
		}

		log_message('debug', "Identified sale_type: $sale_type");

		return $sale_type;
	}

	public function upsert_booking($booking, $selected_ticket, $original_booking, $pricediffaction) {
		if($booking === NULL) return;
		$returnedValue = NULL;
		$tbl = 'bookings_tbl';
		$process_db_interaction = true;
		$booking_id = isset($booking['id'])?intval($booking['id']):-1;
		$parent_booking_id = isset($booking['parent_booking_id'])?intval($booking['parent_booking_id']):0;
		$customer_companyid = isset($booking['customer_companyid'])?intval($booking['customer_companyid']):-1;
		$seller_companyid = isset($booking['seller_companyid'])?intval($booking['seller_companyid']):-1;
		$sale_type = (isset($booking['ticket']) && isset($booking['ticket']['sale_type']))?$booking['ticket']['sale_type']:'request';
		$pnr = ($selected_ticket && isset($selected_ticket['pnr']))?$selected_ticket['pnr']:'';

		// $customer_company = $this->get('company_tbl', array("id" => $customer_companyid));
		// $customer_company_user = $this->get('user_tbl', array("id" => $customer_company["primary_user_id"]));
		// $seller_company = $this->get('company_tbl', array("id" => $seller_companyid));
		// $seller_company_user = $this->get('user_tbl', array("id" => $seller_company["primary_user_id"]));

		$ticketid = -1;
		$bookingupdate = -1;

		$direction = Sale_Direction::Wholesaler_To_Supplier;

		//First time wholesaler's booking id set as parent_booking_id and passed to create new booking id for supplier
		//That's why if $booking_id is greater than 0 means its Supplier to Wholesaler call
		if($booking_id>0) {
			$direction = Sale_Direction::Supplier_To_Wholesaler;
		} else {
			$direction = Sale_Direction::Wholesaler_To_Supplier;
		}

		if($customer_companyid>-1 && $seller_companyid>-1 && $customer_companyid!==$seller_companyid) {
			$sale_type = $this->getPartnerRelationship($direction, $customer_companyid, $seller_companyid);
		}
		if($pnr === '') {
			$sale_type = 'request';
		}

		log_message('debug', 'Search_Model::upsert_booking - '.json_encode($booking));
		$status = intval($booking['status']);

		if($booking_id>0) {
			try
			{
				$this->db->trans_begin();

				log_message('debug', 'Search_Model::upsert_booking - Updating existing booking information | Booking id: '.$booking_id);
				$bookingStatus = $booking['status'];

				if(isset($booking['ticket'])) {
					$ticketid = intval($booking['ticket']['id']);
					$ticket = $this->get_ticket($ticketid);
					if($ticket && is_array($ticket) && count($ticket)) {
						$ticket = $ticket[0];
					}
				}
				log_message('debug', 'Search_Model::upsert_booking - Booking Status : '.($bookingStatus==2?'Processed':($bookingStatus==1?'Hold':'')));

				//First update customers and see enough tickets available to fulfil current requirement
				$customers = null; 
				$no_of_tickets = 0;
				$inv_mode = '';
				if(isset($booking['customers']) && count($booking['customers'])>0 && $parent_booking_id>0) {
					$customers = $booking['customers'];
					unset($booking['customers']);
					$tbl = 'customer_information_tbl';

					for ($i=0; $i < count($customers); $i++) { 
						$returnedValue = -1;
						$ticket = $this->get_ticket($ticketid);
						if($ticket && is_array($ticket) && count($ticket)) {
							$ticket = $ticket[0];
						}
	
						$customer_info = $this->get($tbl, array('id' => $customers[$i]['id']));

						if(($customers[$i]['status'] == 2 || $customers[$i]['status'] == 8) && $customer_info && intval($customer_info[0]['status']) !==8)
						{
							// Approved or Hold
							$no_of_tickets++;
						} else if(($customers[$i]['status'] == 3) && $customer_info && intval($customer_info[0]['status']) === 8) {
							//Previously kept on Hold. Now rejecting it.
							$no_of_tickets++;
							$inv_mode = 'return_stock';
						} else if(($customers[$i]['status'] == 2) && $customer_info && intval($customer_info[0]['status']) === 8) {
							$inv_mode = 'no_update_stock';
						}
						log_message('debug', "Search_Model::upsert_booking - Current customer record => ".json_encode($customer_info[0], TRUE));
						log_message('debug', "Search_Model::upsert_booking - To be changed Current record => ".json_encode($customers[$i], TRUE));
						log_message('debug', "Search_Model::upsert_booking - customer updated $i - inv_mode: $inv_mode | customers: $no_of_tickets | ticket inventory count: ".intval($ticket['no_of_person']));

						if((intval($ticket['no_of_person'])>=$no_of_tickets || $inv_mode == 'return_stock' || $inv_mode == 'no_update_stock') && $process_db_interaction) {
							$returnedValue = $this->update($tbl, array('pnr'=> $customers[$i]['pnr'], 'airline_ticket_no'=> $customers[$i]['airline_ticket_no'], 'status'=> $customers[$i]['status']), array('id' => $customers[$i]['id']));
							log_message('debug', "Search_Model::upsert_booking - Booking customer updated : $returnedValue | Inventory Node: $inv_mode | No Of Tickets : $no_of_tickets");
						}
						else {
							$returnedValue = $this->update($tbl, array('pnr'=> '', 'airline_ticket_no'=> '', 'status'=> 1), array('booking_id' => $parent_booking_id, 'status' => 2));
							$status = 0;
							$process_db_interaction = false;
							break;
						}
					}
				}
				
				// this is old booking. so needs to be updated
				$tbl = 'bookings_tbl';
				if($process_db_interaction) {
					if(intval($booking['status']) === 8) {
						$bookingupdate = $this->update($tbl, array('message'=> $booking['notes'], 'status' => $booking['status'], 'pnr' => ''), array('id' => $booking_id));
					}
					else {
						$bookingupdate = $this->update($tbl, array('message'=> $booking['notes'], 'status' => $booking['status'], 'pnr' => $booking['pnr']), array('id' => $booking_id));
					}
				}

				log_message('debug', "Search_Model::upsert_booking - Booking updated : $bookingupdate");
				$bookingactivity = null; 
				if(isset($booking['activity']) && count($booking['activity'])>0) {
					$bookingactivity = $booking['activity'][0];
					unset($booking['activity']);
					$tbl = 'booking_activity_tbl';

					if($process_db_interaction) {
						$returnedValue = $this->update($tbl, array('notes'=> $bookingactivity['notes'], 'status' => $bookingactivity['status']), array('activity_id' => $bookingactivity['activity_id']));
					}
					log_message('debug', "Search_Model::upsert_booking - Booking activity updated : $returnedValue");
				}

				//Update user accounts as purchased ticket as collection was received before
				$ordered_booking = $this->get('bookings_tbl', array('id' => $booking_id));
				if($ordered_booking && is_array($ordered_booking) && count($ordered_booking)>0) {
					$ordered_booking = $ordered_booking[0];
				}

				$customer_user = $this->get('user_tbl', array('id' => intval($ordered_booking['customer_userid'])));
				if($customer_user && is_array($customer_user) && count($customer_user)>0) {
					$customer_user = $customer_user[0];
				}

				if($customer_user && intval($bookingStatus)===2 && intval($ordered_booking['pbooking_id'])===0 && isset($customer_user['is_admin']) && intval($customer_user['is_admin']) === 0) {
					log_message('debug', "Ticket fullfilled so lets give customer user`s accounts entry");
					log_message('debug', "Booking Id: $booking_id | Booking Status: $bookingStatus | Amount: ".$ordered_booking['total']);
					$customer_company = $this->get('company_tbl', array('id' => $customer_companyid));
					if($customer_company && is_array($customer_company) && count($customer_company)>0) { 
						$customer_company = $customer_company[0];
					}
					//This is the place where we should add customer user's account transaction as purchased ticket

					//Commenting this as before final Sales entry agaisnt customer user, we are creating purchase order against customer user.

					// $vc_no = $this->Search_Model->get_next_voucherno($customer_company);
					// $whl_voucher_no = $this->save("account_transactions_tbl", array(
					// 	"voucher_no" => $vc_no, 
					// 	"transacting_companyid" => $customer_companyid, 
					// 	"transacting_userid" => intval($ordered_booking['customer_userid']),
					// 	"documentid" => $booking_id, 
					// 	"document_date" => $ordered_booking['booking_date'], 
					// 	"document_type" => 1,
					// 	"transaction_type" => "SALES",
					// 	"credit" => $booking['total'],  
					// 	"companyid" => $customer_companyid,  
					// 	"credited_accountid" => 7,  
					// 	"created_by"=>$ordered_booking['customer_userid'],
					// 	"narration" => "Sales booking (Booking id: $booking_id dated: ".$ordered_booking['booking_date']
					// ));
					// log_message('debug', "[SAVED] Booking Id: $booking_id | Booking Status: $bookingStatus | Amount: ".$ordered_booking['total']." | Voucher No: $vc_no");
				}

				if(($booking['status'] == 2 || $booking['status'] == 1 || $inv_mode == 'return_stock') && $no_of_tickets>0 && intval($booking['parent_booking_id'])>0) {
					// deduct stock if 'Approved' or 'Hold'
					// Means if the booking is approved then reduce the available ticekt count and dr./cr. wallet balance.

					// reduce available tickets count.
					$tbl = 'tickets_tbl';
					$no_of_person = intval($ticket['no_of_person']);

					if(intval($ticket['no_of_person'])>=$no_of_tickets || $inv_mode === 'return_stock') {
						if($inv_mode === 'return_stock') {
							$no_of_tickets = -1 * $no_of_tickets;
						}
						$available = (intval($ticket['no_of_person']) - $no_of_tickets)>0 ? 'YES' : 'NO';
						if($process_db_interaction) {
							$returnedValue = $this->update($tbl, array('no_of_person'=> (intval($ticket['no_of_person']) - $no_of_tickets), 'availibility'=> (intval($ticket['no_of_person']) - $no_of_tickets), 'available'=> "$available"), array('id' => $ticket['id']));
						}
					}

					// new dr./cr. accounts.
					//supplier fulfilled the ticket. So lets enter the sales voucher at supplier side and purchase voucher at seller side

					if($customer_companyid != $seller_companyid) {
						$customercompany = $this->get('company_tbl', array('id' => $customer_companyid));
						if($customercompany && is_array($customercompany) && count($customercompany)>0) {
							$customercompany = $customercompany[0];
						}

						$sellercompany = $this->get('company_tbl', array('id' => $seller_companyid));
						if($sellercompany && is_array($sellercompany) && count($sellercompany)>0) {
							$sellercompany = $sellercompany[0];
						}

						$ordered_booking = $this->get('bookings_tbl', array('id' => $booking_id));
						if($ordered_booking && is_array($ordered_booking) && count($ordered_booking)>0) {
							$ordered_booking = $ordered_booking[0];
						}

						$vc_no = $this->Search_Model->get_next_voucherno($customercompany);
						$whl_voucher_no = $this->save("account_transactions_tbl", array(
							"voucher_no" => $vc_no, 
							"transacting_companyid" => $seller_companyid, 
							"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
							"documentid" => $booking_id, 
							"document_date" => $ordered_booking['booking_date'], 
							"document_type" => 1,
							"transaction_type" => "PURCHASE",
							//"debit" => $ordered_booking['total'],  
							"credit" => $ordered_booking['total'],  /* Amount is credited to Seller from supplier as ticket needs to be issued later */
							"companyid" => $customer_companyid,  
							"credited_accountid" => 7,  
							"created_by"=>$ordered_booking['customer_userid'],
							"narration" => "Purchase booking (Booking id: $booking_id dated: ".date("Y-m-d H:i:s", strtotime($ordered_booking['booking_date'].'+00:00'))
						));

						log_message('debug', "Purchase voucher raised => $whl_voucher_no | VC => $vc_no");

						$vc_no = $this->Search_Model->get_next_voucherno($sellercompany);
						$whl_voucher_no = $this->save("account_transactions_tbl", array(
							"voucher_no" => $vc_no, 
							"transacting_companyid" => $customer_companyid, 
							"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
							"documentid" => $booking_id, 
							"document_date" => $ordered_booking['booking_date'], 
							"document_type" => 1,
							"transaction_type" => "SALES",
							//"credit" => $ordered_booking['total'],  
							"debit" => $ordered_booking['total'],   /* Ticket issued to wholesaler from supplier */
							"companyid" => $seller_companyid,  
							"credited_accountid" => 7,  
							"created_by"=>$ordered_booking['customer_userid'],
							"narration" => "Sales booking (Booking id: $booking_id dated: ".date("Y-m-d H:i:s", strtotime($ordered_booking['booking_date'].'+00:00'))
						));

						log_message('debug', "Sales voucher raised => $whl_voucher_no | VC => $vc_no");
					}

				}

				//Notify to user
				#region Notify user via Email
				if($process_db_interaction) {
					log_message('debug', "Search_Model::upsert_booking - Notifying customer/wholesaler/supplier");

					$this->notify(array(
						'doctype' => 'booking',
						'docno' => $booking_id,
						'template' => 'booking'
					));
				}
				#endregion

				//if order is cancelled then we need to reverse the payment deducted and also adjust accounts.
				if($booking['status'] == 8) {
					$result = $this->cancel_booking($booking_id);
					if($result) {
						log_message('debug', "Search_Model::upsert_booking - Notifying customer/wholesaler/supplier as the previous booking got cancelled, wallet deduction & accounts transactions are reversed");

						//Notification to be sent to both customer and vendor
						// $this->notify(array(
						// 	'doctype' => 'booking',
						// 	'docno' => $booking_id,
						// 	'template' => 'wallet_update'
						// ));
					}
				}

				if($bookingupdate>0) {
					$this->db->trans_complete();
				}
				else {
					$this->db->trans_rollback();
				}
			}
			catch(Exception $ex) {
				log_message('error', 'Search_Model::upsert_booking - Error: '.$ex);
				$this->db->trans_rollback();
			}
		}
		else {

			try
			{
				$this->db->trans_begin();
				log_message('debug', 'Search_Model::upsert_booking - Inserting new booking information');

				// this is new booking. so needs to be inserted
				$bookingactivity = null; 
				$pbooking_id = intval($booking['pbooking_id']);
				$customer_userid = intval($booking['customer_userid']);
				$customer_companyid = intval($booking['customer_companyid']);
				$seller_userid = intval($booking['seller_userid']);
				$seller_companyid = intval($booking['seller_companyid']);
				$customeruserinfo = $this->get('user_tbl', array('id' => $customer_userid));
				$customercompanyinfo = $this->get('company_tbl', array('id' => $customer_companyid));

				$selleruserinfo = $this->get('user_tbl', array('id' => $seller_userid));
				$sellercompanyinfo = $this->get('company_tbl', array('id' => $seller_companyid));

				if($sale_type === 'live') {
					$booking['status'] = 2; //processed
				}

				if($customeruserinfo!=NULL && count($customeruserinfo)>0) {
					$customeruserinfo = $customeruserinfo[0];
				}

				if($customercompanyinfo!=NULL && count($customercompanyinfo)>0) {
					$customercompanyinfo = $customercompanyinfo[0];
				}

				//$ordered_booking = $this->get('bookings_tbl', array('id' => $pbooking_id));
				$ordered_booking = $this->get_booking($pbooking_id);
				if($ordered_booking!=NULL && count($ordered_booking)>0) {
					$ordered_booking = $ordered_booking[0];
				}

				$customeruser = $this->get('user_tbl', array('id' => $ordered_booking['customer_userid'], 'active' => 1));
				if($customeruser!=NULL && count($customeruser)>0) {
					$customeruser = $customeruser[0];
				}

				$customeruserwallet = $this->get('system_wallets_tbl', array('companyid' => $customer_companyid, 'sponsoring_companyid' => $ordered_booking['customer_companyid'], 'userid' => $ordered_booking['customer_userid'], 'type' => 2));
				if($customeruserwallet!=NULL && count($customeruserwallet)>0) {
					$customeruserwallet = $customeruserwallet[0];
				}

				$customerwallet = $this->get('system_wallets_tbl', array('companyid' => $customer_companyid, 'sponsoring_companyid' => -1, 'userid' => 0, 'type' => 1));
				if($customerwallet!=NULL && count($customerwallet)>0) {
					$customerwallet = $customerwallet[0];
				}

				$sellerwallet = $this->get('system_wallets_tbl', array('companyid' => $seller_companyid, 'sponsoring_companyid' => -1, 'userid' => 0, 'type' => 1));
				if($sellerwallet!==NULL && count($sellerwallet)>0) {
					$sellerwallet = $sellerwallet[0];
				}

				$current_ticket = $this->get('tickets_tbl', array('id' => intval($selected_ticket['id'])));
				if($current_ticket && is_array($current_ticket) && count($current_ticket)>0) {
					$current_ticket = $current_ticket[0];
				}

				$markup = floatval($ordered_booking['markup']);
				$srvchg = floatval($ordered_booking['srvchg']);
				$cgst = floatval($ordered_booking['cgst']);
				$sgst = floatval($ordered_booking['sgst']);
				$igst = floatval($ordered_booking['igst']);
				$qty = floatval($ordered_booking['qty']);

				$is_different_tkt = (intval($selected_ticket['id']) !== intval($ordered_booking['ticket_id']));

				log_message('debug', 'Search_Model::upsert_booking - Selected Ticket - '.json_encode($selected_ticket));
				log_message('debug', "Search_Model::upsert_booking - Customer UserId: $customer_userid | Customer CompanyId: $customer_companyid | Seller UserId: $seller_userid | Seller CompanyId: $seller_companyid");
				log_message('debug', 'Search_Model::upsert_booking - Customer User Info - '.json_encode($customeruserinfo));
				log_message('debug', 'Search_Model::upsert_booking - Customer Company Info - '.json_encode($customercompanyinfo));
				log_message('debug', 'Search_Model::upsert_booking - Customer Wallet Info - '.json_encode($customerwallet));
				log_message('debug', 'Search_Model::upsert_booking - Seller Wallet Info - '.json_encode($sellerwallet));
				log_message('debug', 'Search_Model::upsert_booking - Customer Booking Info - '.json_encode($ordered_booking));
				log_message('debug', 'Search_Model::upsert_booking - Customer User Wallet Info - '.json_encode($customeruserwallet));
				log_message('debug', 'Search_Model::upsert_booking - Customer Original Booking - '.json_encode($original_booking));
				log_message('debug', 'Search_Model::upsert_booking - Original Customer Info - '.json_encode($customeruser));

				if(isset($booking['activity']) && count($booking['activity'])>0) {
					$bookingactivity = $booking['activity'][0];
					unset($booking['activity']);
				}

				$customers = null; 
				if(isset($booking['customers']) && count($booking['customers'])>0) {
					$customers = $booking['customers'];
					unset($booking['customers']);
				}

				$booking_id = -1;
				//$selected_ticket_price = floatval($selected_ticket['cost_price']) + floatval($selected_ticket['spl_markup']) + floatval($selected_ticket['whl_markup']);
				$selected_ticket_price = floatval($selected_ticket['total']) + floatval($selected_ticket['spl_markup']) + floatval($selected_ticket['whl_markup']);
				log_message('debug', "Search_Model::upsert_booking - Selected Ticket Price 1 - Selected Ticket Price: $selected_ticket_price | Cost : ".floatval($selected_ticket['cost_price']));
				log_message('debug', "Search_Model::upsert_booking - Selected Ticket Price 2 - Supl.Markup: ".($selected_ticket['spl_markup'])." | Whl.Markup: ".($selected_ticket['whl_markup']));

				$pricediff = ($selected_ticket_price - floatval($original_booking['rate']))*$qty;
				if(intval($customeruser['is_admin'])===1) {
					$pricediff = 0;
				}

				$customeruser_wallet_balance = floatval($customeruserwallet['balance']);

				log_message('debug', "Search_Model::upsert_booking - Price diff: $pricediff | Target Booking.Cost: ".floatval($booking['costprice']).' | Original Order.Cost: '.$ordered_booking['costprice']." | customer wallet balance: $customeruser_wallet_balance");
				log_message('debug', "Search_Model::upsert_booking - Price diff: $pricediff | Target Booking.Portal.Price: ".$selected_ticket_price.' | Original Order.Portal.Price: '.floatval($original_booking['rate'])." | customer wallet balance: $customeruser_wallet_balance");
				unset($booking['id']);
				unset($bookingactivity['activity_id']);

				if($process_db_interaction) {
					$booking_id = $this->save($tbl, $booking);
				}

				if($booking_id!==null && intval($booking_id)>0) {
					log_message('debug', "Search_Model::upsert_booking - New booking id: $booking_id");
					$tbl = 'booking_activity_tbl';
					$bookingid = intval($booking_id);
					$bookingactivity['booking_id'] = $booking_id;
					
					if($sale_type === 'live') {
						$bookingactivity['status'] = 32; //processed
					}


					//Updating customer information into table
					log_message('debug', 'Search_Model::upsert_booking - Customer List'.json_encode($customers));
					for ($i=0; $i < count($customers); $i++) { 
						$customer = &$customers[$i];
						if(intval($customer['refrence_id']) === -1) {
							$customer['refrence_id'] = $booking_id;

							if($sale_type==='live' && $pnr!=='') {
								$customer['airline_ticket_no'] = $pnr;
								$customer['pnr'] = $pnr;
								$customer['status'] = 2;
							}

							if($process_db_interaction) {
								$return = $this->update('customer_information_tbl', $customer, array('id' => $customer['id']));
							}
						}
					}

					if($process_db_interaction) {
						$returnedValue = $this->save($tbl, $bookingactivity);
					}

					log_message('debug', "Search_Model::upsert_booking - Booking activity : $returnedValue");

					//Notify to user
					#region Notify user via Email
					if($process_db_interaction) {
						log_message('debug', "Search_Model::upsert_booking - Notifying customer");

						$this->notify(array(
							'doctype' => 'booking',
							'docno' => $booking_id,
							'template' => 'booking'
						));
					}
					#endregion

					//Check if there is a price differance or not.
					//if not then no need to update ticket information in old booking
					//if there is price differance then update old booking details
					if(($is_different_tkt || $pricediff!=0) && $process_db_interaction) {
						//$price = floatval($selected_ticket['price']) + floatval($selected_ticket['admin_markup']) + floatval($ordered_booking['field_value']);
						$price = $selected_ticket_price + floatval($selected_ticket['admin_markup']) + floatval($ordered_booking['field_value']);

						log_message('debug', "Search_Model::upsert_booking - Updating price difference into ticket : $price | price differance action: $pricediffaction");
						
						if($pricediffaction==='pass') {
							$return = $this->update('bookings_tbl', array(
								'ticket_id' => intval($selected_ticket['id']), 
								'price' => $price, 
								//'costprice' => $selected_ticket['price'],
								'costprice' => $selected_ticket_price,
								'total' => ($price+$srvchg+$cgst+$sgst)*$qty
							), array('id' => $original_booking['id']));
							log_message('debug', "Search_Model::upsert_booking - Updating price difference into ticket updated : $return | Ticket: ".intval($selected_ticket['id'])." | Price : $price | Cost price : $selected_ticket_price | Total : ".(($price+$srvchg+$cgst+$sgst)*$qty));
						}
						else {
							$return = $this->update('bookings_tbl', array(
								'ticket_id' => intval($selected_ticket['id']), 
							), array('id' => $original_booking['id']));
							log_message('debug', "Search_Model::upsert_booking - Updating price difference into ticket updated (Since absorbe) : $return | Ticket: ".intval($selected_ticket['id']));
						}
					}

					$company = $this->get('company_tbl', array('id' => $customer_companyid));
					if($company!=NULL && is_array($company) && count($company)>0) {
						$company = $company[0];
					}

					$seller_company = $this->get('company_tbl', array('id' => $seller_companyid));
					if($seller_company!=NULL && is_array($seller_company) && count($seller_company)>0) {
						$seller_company = $seller_company[0];
					}

					//Perform wallet transaction if any recidue present
					//Pass the price differance to customer if wholesaler accept it
					if($pricediff!=0 && $customeruserwallet!=NULL && intval($customeruserwallet['id'])>0 && intval($customeruser['is_admin'])!=1 && $pricediffaction==='pass') {
						$tbl = 'wallet_transaction_tbl';
						$wallet_trans_date = date("Y-m-d H:i:s");
						$transaction_id = -1;

						if($process_db_interaction && intval($customeruser['is_admin'])!=1) {
							$transaction_id = $this->save("wallet_transaction_tbl", array(
								"wallet_id" => $customeruserwallet['id'], 
								"date" => $wallet_trans_date, 
								"trans_id" => uniqid(), 
								"companyid" => $customer_companyid, 
								"userid" => $ordered_booking['customer_userid'],
								"amount" => abs($pricediff), 
								'dr_cr_type'=> $pricediff>0 ?'DR':'CR',
								'trans_type'=>$pricediff>0?12:11, /*20 is for Ticket Booking | 11 is Credit Note | 12 is Debit Note*/
								"trans_ref_id" => $ordered_booking['id'],
								"trans_ref_date" => $ordered_booking['booking_date'],
								'trans_ref_type'=>$pricediff>0 ?'DEBIT NOTE':'CREDIT NOTE',
								"trans_documentid" => $bookingid,
								"narration" => "Customer booking ".$ordered_booking['id']." changed to new booking id: $bookingid. Difference money: ".abs($pricediff)." ".($pricediff>0?'DR':'CR'),
								"sponsoring_companyid" => $customer_companyid,
								"status" => 1,
								"approved_by" => $customer_userid,
								"approved_on" => $wallet_trans_date,
								"target_companyid" => $customer_companyid, 
								"created_by" => $customer_userid,
								"created_on" => $wallet_trans_date
							));

							log_message('debug', "Search_Model::upsert_booking - Updating price difference into ticket updated (Since absorbe) : $return | Ticket: ".intval($selected_ticket['id']));
						}

						if($transaction_id>-1) {
							log_message('debug', "Search_Model::upsert_booking - Wallet transaction id : $transaction_id | wallet id: ".$customerwallet['id']." | Wallet Balance : ".floatval($booking['costprice']));
							$custuserid = $ordered_booking['customer_userid'];
							$custuserwalletid = $customeruserwallet['id'];						

							if($process_db_interaction) {
								$returnvalue = $this->update("system_wallets_tbl", array('balance' => $customeruser_wallet_balance-$pricediff), array(
									"id" => $customeruserwallet['id']
								));
							}

							log_message('debug', "Search_Model::upsert_booking - Wallet balance : ".($customeruser_wallet_balance-$pricediff).' | Transaction Type : '.($pricediff>0 ?'DEBIT NOTE':'CREDIT NOTE'));
						}

						if($pricediff!=0 && intval($customeruser['is_admin'])!=1) {
							log_message('debug', "[Search:upsert_booking] Transacting Accounts | User Id: $custuserid | Wallet Id: $custuserwalletid | Previous Wallet Balance: $customeruser_wallet_balance | Transaction amount: $pricediff");
				
							$arr=array(
								"voucher_no" => $this->Search_Model->get_next_voucherno($company),
								"transacting_companyid" => $customer_companyid,
								"transacting_userid" => $custuserid,
								"documentid" => $transaction_id,
								"document_date" => $wallet_trans_date,
								"document_type" => $pricediff>0?4:3, /* 1 = Booking | 2 = Payment | 3 = Credit Note | 4 = Debit Note | 5 = Refund | 6 = Withdrawl */
								"transaction_type" => $pricediff>0?"DEBIT NOTE":"CREDIT NOTE",
								"debit" => $pricediff > 0 ? abs($pricediff) : 0,
								"credit" => $pricediff < 0 ? abs($pricediff) : 0,
								"companyid" => $customer_companyid,
								//"debited_accountid" => ($ticket_account==null? -1: $ticket_account['accountid']),
								"created_by" => $custuserid,
								"created_on" => date("Y-m-d H:i:s")
							);

							if($process_db_interaction) {
								$voucher_no = $this->Search_Model->save("account_transactions_tbl",$arr);
							}
						}
					}

					//perform wallet transaction
					if($seller_companyid !== $customer_companyid) {
						//First debit customer wallet account
						if($process_db_interaction) {
							$transaction_id = $this->save("wallet_transaction_tbl", array(
								"wallet_id" => $customerwallet['id'], 
								"date" => date("Y-m-d H:i:s"), 
								"trans_id" => uniqid(), 
								"companyid" => $customer_companyid, 
								//"amount" => floatval($booking['costprice']), 
								"amount" => floatval($booking['total']), 
								"dr_cr_type" => 'DR', 
								"trans_type" => 20, /* 20 is for booking type transaction */
								"trans_ref_id" => $bookingid,
								"trans_ref_date" => $booking['booking_date'],
								"trans_ref_type" => 'PURCHASE',
								"trans_documentid" => $bookingid,
								"narration" => "New ticket booking raised (id: $bookingid)",
								"sponsoring_companyid" => $customer_companyid,
								"status" => 1,
								"approved_by" => $customer_userid,
								"approved_on" => date("Y-m-d H:i:s"),
								"target_companyid" => $customer_companyid, 
								"created_by" => $customer_userid,
								"created_on" => date("Y-m-d H:i:s")
							));

							//This is the place to add account transaction
							$whl_voucher_no = $this->save("account_transactions_tbl", array(
								"voucher_no" => $this->Search_Model->get_next_voucherno($seller_company), 
								"transacting_companyid" => $customer_companyid, 
								"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
								"documentid" => $bookingid, 
								"document_date" => $booking['booking_date'], 
								"document_type" => 1,
								"transaction_type" => "COLLECTION",
								//"debit" => floatval($booking['total']),  
								"credit" => floatval($booking['total']),   /* Collection received from Customer so posting it to his credit account */
								"companyid" => $seller_companyid,  
								"credited_accountid" => 7,  //some dummy value
								"created_by" => $customer_userid,
								"narration" => "Collection received towards (Booking id: $bookingid dated: ".date("Y-m-d H:i:s", strtotime($booking["booking_date"].'+00:00'))
							));	
						}

						log_message('debug', "Search_Model::upsert_booking - Wallet transaction id : $transaction_id | wallet id: ".$customerwallet['id']." | Wallet Balance : ".floatval($booking['total'])." | Whl.Voucher #: $whl_voucher_no");
						if(intval($transaction_id)>0 && $process_db_interaction) {
							$returnvalue = $this->update("system_wallets_tbl", array('balance' => (floatval($customerwallet['balance'])-floatval($booking['total']))), 
							array(
								"id" => $customerwallet['id']
							));

							log_message('debug', "Search_Model::upsert_booking - Wallet balance : ".(floatval($customerwallet['balance'])-floatval($booking['total'])));
						}

						if($process_db_interaction) {
							//Second credit seller wallet account
							$transaction_id = $this->save("wallet_transaction_tbl", array(
								"wallet_id" => $sellerwallet['id'], 
								"date" => date("Y-m-d H:i:s"), 
								"trans_id" => uniqid(), 
								"companyid" => $seller_companyid, 
								//"amount" => floatval($booking['costprice']), 
								"amount" => floatval($booking['total']), 
								"dr_cr_type" => 'CR', 
								"trans_type" => 9, /* 9 is for transfer */
								"trans_ref_id" => $bookingid,
								"trans_ref_date" => $booking['booking_date'],
								"trans_ref_type" => 'PAYMENT',
								"trans_documentid" => $bookingid,
								"narration" => "New ticket booking raised (id: $bookingid)",
								"sponsoring_companyid" => $seller_companyid,
								"status" => 1,
								"approved_by" => $seller_userid,
								"approved_on" => date("Y-m-d H:i:s"),
								"target_companyid" => $seller_companyid, 
								"created_by" => $customer_userid,
								"created_on" => date("Y-m-d H:i:s")
							));

							//This is the place to add account transaction
							$spl_voucher_no = $this->save("account_transactions_tbl", array(
								"voucher_no" => $this->Search_Model->get_next_voucherno($company), 
								"transacting_companyid" => $seller_companyid, 
								"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
								"documentid" => $bookingid, 
								"document_date" => $booking['booking_date'], 
								"document_type" => 1,
								"transaction_type" => "PAYMENT",
								//"credit" => floatval($booking['total']),  
								"debit" => floatval($booking['total']),  /* Payment made by customer to supplier. So posting it to supplier's debit and opposit credit posting being done by supplier */
								"companyid" => $customer_companyid,  
								"credited_accountid" => 7,  //some dummy value
								"created_by" => $customer_userid,
								"narration" => "Payment made towards (Booking id: $bookingid dated: ".date("Y-m-d H:i:s", strtotime($booking["booking_date"].'+00:00'))
							));	
						}

						log_message('debug', "Search_Model::upsert_booking - Wallet transaction id : $transaction_id | wallet id: ".$sellerwallet['id']." | Wallet Balance : ".floatval($booking['total'])." | Spl.Voucher #: $spl_voucher_no");
						if(intval($transaction_id)>0 && $process_db_interaction) {
							$returnvalue = $this->update("system_wallets_tbl", array('balance' => (floatval($sellerwallet['balance'])+floatval($booking['total']))), 
							array(
								"id" => $sellerwallet['id']
							));

							log_message('debug', "Search_Model::upsert_booking - Wallet balance : ".(floatval($sellerwallet['balance'])+floatval($booking['total'])));
						}
					}

					if($sale_type==='live' && $current_ticket && is_array($current_ticket) && count($current_ticket)>0) {
						//This is live booking so ticket count should be reduced
						if($process_db_interaction) {
							$no_of_person = intval($current_ticket['no_of_person']) - $qty;
							$ticket_return = $this->update('tickets_tbl', array(
								'no_of_person' => $no_of_person, // intval($selected_ticket['id']), 
								'max_no_of_person' => $no_of_person, // intval($selected_ticket['id']), 
								'availibility' => $no_of_person, // intval($selected_ticket['id']), 
								'available' => $no_of_person>0?'YES':'NO'
							), array('id' => intval($selected_ticket['id'])));

							log_message('debug', "Search_Model::upsert_booking - Ticket count reduced by $qty as transaction is $sale_type | Result =>  $ticket_return | Ticket: ".intval($selected_ticket['id']));
						}

						//This is live booking between wholesaler and supplier and inventory also consumed. So lets perform account transaction
						if($seller_companyid !== $customer_companyid) {
							//Raise sale voucher
							$return_value = $this->raiseVoucher(array(
								'vch_type'=> 'SALE', 
								'debit' => floatval($booking['total']), 
								'customer_companyid' => $customer_companyid, 
								'customer_userid' => 0, //$customer_userid, 
								'source_companyid' => $seller_companyid, 
								'source_userid' => $seller_userid, 
								'document_id' => $bookingid,
								'document_type' => 1,
								"document_date" => $booking['booking_date'], 
								"narration" => "Ticket sold (Booking id: $bookingid dated: ".date("Y-m-d H:i:s", strtotime($booking["booking_date"].'+00:00'))
							));

							log_message('debug', "Sale voucher raised ".json_encode($return_value, TRUE));

							//Raise purchase voucher
							$return_value = $this->raiseVoucher(array(
								'vch_type'=> 'PURCHASE', 
								'credit' => floatval($booking['total']), 
								'customer_companyid' => $seller_companyid, 
								'customer_userid' => 0, // $seller_userid, 
								'source_companyid' => $customer_companyid, 
								'source_userid' => $customer_userid, 
								'document_id' => $bookingid,
								'document_type' => 1,
								"document_date" => $booking['booking_date'], 
								"narration" => "Ticket purchased (Booking id: $bookingid dated: ".date("Y-m-d H:i:s", strtotime($booking["booking_date"].'+00:00'))
							));

							log_message('debug', "Purchase voucher raised ".json_encode($return_value, TRUE));
						}
					}

					$this->db->trans_complete();
				}
				else {
					$this->db->trans_rollback();
				}
			}
			catch(Exception $ex1) {
				log_message('error', 'Search_Model::upsert_booking - Error: '.$ex1);
				$this->db->trans_rollback();
			}
		}

		if($status === 0) {
			return array('status' => false, 'booking_id' => -1, 'sale_type' => $sale_type, 'message' => 'This ticket does`t have enough PAX left. Please add some PAX into this ticket.');
		}
		else {
			return array('status' => true, 'booking_id' => $returnedValue, 'sale_type' => $sale_type, 'message' => 'Successfully saved');
		}
	}

	//wallet reverse due to cancel booking
	public function cancel_booking($bookingid = -1) {
		if($bookingid<=0) return false;

		$flag = false;

		//Get the booking details by booking id
		$booking = $this->get('bookings_tbl', array('id' => $bookingid));
		if($booking && count($booking) > 0) {
			$booking = $booking[0];
		}
		//get the booking_details related to wallet reverse
		if($booking && intval($booking['status']) === 8) {
			if (intval($booking['id']) > 0 && intval($booking['pbooking_id']) > 0) {
				//Booking cancelled between supplier and wholesaler
				$parent_bookingid = intval($booking['pbooking_id']);
				$transid = uniqid();
				$wallet_trans_date = date('Y-m-d H:i:s');
				$wallettrans = $this->get('wallet_transaction_tbl', array('trans_ref_id' => $parent_bookingid, 'trans_type' => 20, 'userid' => 0, 'sponsoring_companyid' => -1));
			} elseif (intval($booking['id']) > 0 && intval($booking['pbooking_id']) === 0) {
				$parent_bookingid = intval($booking['pbooking_id']);
				$transid = uniqid();
				$wallet_trans_date = date('Y-m-d H:i:s');
				$wallettrans = $this->query("select * from wallet_transaction_tbl where trans_ref_id = $bookingid and trans_type=20 and userid>0"); // $this->get('wallet_transaction_tbl', array('trans_ref_id' => $parent_bookingid, 'trans_type' => 20, 'userid' => 0, 'sponsoring_companyid' => -1));
			}

			for($i=0; $i<count($wallettrans); $i++) {
				$wallettran = $wallettrans[$i];
				$walletid = $wallettran['wallet_id'];

				$doc_ref_type = $wallettran['trans_ref_type']==='PURCHASE' ? 'CREDIT NOTE' : 'DEBIT NOTE';
				$crdrtype = $wallettran['dr_cr_type']==='CR' ?'DR':'CR';
				$amount = abs($wallettran['amount']);
				$transactinguserid = intval($wallettran['userid']);
				
				$transaction_id = $this->save("wallet_transaction_tbl", array(
					"wallet_id" => $walletid, 
					"date" => $wallet_trans_date, 
					"trans_id" => $transid, 
					"trans_tracking_id" => $wallettran['trans_id'], 
					"companyid" => $wallettran['companyid'], 
					"userid" => $transactinguserid, 
					"amount" => $amount, 
					'dr_cr_type'=> $crdrtype,
					'trans_type'=> $wallettran['trans_type'], /*20 is for Ticket Booking | 11 is Credit Note | 12 is Debit Note*/
					"trans_ref_id" => $wallettran['trans_ref_id'],
					"trans_ref_date" => $wallettran['trans_ref_date'],
					'trans_ref_type'=> $doc_ref_type,
					"trans_documentid" => $bookingid,
					"narration" => "Booking # BK-$bookingid is cancelled. So reversing wallet transactions. $doc_ref_type (ref: $transid) issued.",
					"sponsoring_companyid" => $wallettran['sponsoring_companyid'],
					"status" => 1,
					"approved_by" => $booking['seller_userid'],
					"approved_on" => $wallet_trans_date,
					"target_companyid" => $wallettran['target_companyid'], 
					"created_by" => $booking['seller_userid'],
					"created_on" => $wallet_trans_date
				));

				$wallet = $this->get("system_wallets_tbl", array('id' => $walletid));
				if($wallet && count($wallet)>0) {
					$wallet = $wallet[0];
				}

				if($wallet) {
					$walletsummary = $this->update("system_wallets_tbl", array("balance" => floatval($wallet['balance']) + ($crdrtype === 'CR'? $amount : -$amount)), array("id" => $walletid));
					$walletoldbalance = floatval($wallet['balance']);
					$wallet = $this->get("system_wallets_tbl", array('id' => $walletid));
					if($wallet && count($wallet)>0) {
						$wallet = $wallet[0];
					}

					log_message('debug', "Wallet summary of $walletid updated. Old value : $walletoldbalance | New value : ".floatval($wallet['balance']));
				}

				//This is the place to add account transaction
				$seller_companyid = $wallettran['companyid'];
				$customer_companyid = $wallettran['target_companyid'];
				$seller_company = $this->get('company_tbl', array('id' => $seller_companyid));
				if($seller_company && count($seller_company) > 0) {
					$seller_company = $seller_company[0];
				}
		
				$whl_voucher_no = $this->save("account_transactions_tbl", array(
					"voucher_no" => $this->Search_Model->get_next_voucherno($seller_company), 
					"transacting_companyid" => $customer_companyid, 
					"transacting_userid" => $transactinguserid > 0 ? $transactinguserid : 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
					"documentid" => $bookingid, 
					"document_date" => $booking['booking_date'], 
					"document_type" => $doc_ref_type==='DEBIT NOTE'?4:3,
					"transaction_type" => $doc_ref_type,
					"debit" => $crdrtype==='DR' ? $amount : 0,  
					"credit" => $crdrtype==='CR' ? $amount : 0,   /* Collection received from Customer so posting it to his credit account */
					"companyid" => $seller_companyid,  
					"credited_accountid" => null,  //some dummy value
					"created_by" => $booking['seller_userid'],
					"narration" => "Transaction reversed due to cancellation of (Booking id: $bookingid dated: ".date("Y-m-d H:i:s", strtotime($booking["booking_date"].'+00:00'))
				));

				$flag = $transaction_id > -1 && $whl_voucher_no > -1;
			}
		}

		return $flag;
	}

	private function raiseVoucher($payload) {
		$flag = true;
		if(!$payload) return array('status' => 1, 'message' => 'Insufficient data passed to create voucher', 'vch_no' => '');
		$voucher_no = '';

		try
		{
			$vch_type = isset($payload['vch_type']) ? $payload['vch_type'] : '';
			$credit = isset($payload['credit']) ? floatval($payload['credit']) : 0;
			$debit = isset($payload['debit']) ? floatval($payload['debit']) : 0;
			$customer_companyid = isset($payload['customer_companyid']) ? $payload['customer_companyid'] : 0;
			$customer_userid = isset($payload['customer_userid']) ? $payload['customer_userid'] : 0;
			$source_companyid = isset($payload['source_companyid']) ? $payload['source_companyid'] : 0;
			$source_userid = isset($payload['source_userid']) ? $payload['source_userid'] : 0;
			$document_id = isset($payload['document_id']) ? $payload['document_id'] : 0;
			$document_type = isset($payload['document_type']) ? $payload['document_type'] : 0;
			$document_date = isset($payload['document_date']) ? date("Y-m-d H:i:s", strtotime($payload['document_date'].'+00:00')) : date('Y-m-d H:i:s');
			$narration = isset($payload['narration']) ? $payload['narration'] : '';

			if($vch_type === '' || ($credit+$debit) === 0 || $customer_companyid === 0 || $source_companyid === 0) {
				return array('status' => 1, 'message' => 'Invalid data passed for voucher creation', 'vch_no' => '');
			}

			$company = $this->get('company_tbl', array('id' => $source_companyid));
			if($company!=NULL && is_array($company) && count($company)>0) {
				$company = $company[0];
			}

			log_message('debug', "About to create voucher - $vch_type - ".json_encode($payload));

			$voucher_no = $this->Search_Model->get_next_voucherno($company);
			$this->save("account_transactions_tbl", array(
				"voucher_no" => $voucher_no, 
				"transacting_companyid" => $customer_companyid, 
				"transacting_userid" => $customer_userid,
				"documentid" => $document_id, 
				"document_date" => $document_date, 
				"document_type" => $document_type, 
				"transaction_type" => $vch_type,
				"credit" => $credit,
				"debit" => $debit,
				"companyid" => $source_companyid,  
				"credited_accountid" => 7,  //some dummy value
				"created_by" => $source_userid,
				"narration" => $narration
			));	
		}
		catch(Exception $ex) {
			log_message('error', 'Search_Model::raiseVoucher - Error: '.$ex);
			return array('status' => 1, 'message' => 'Invalid data passed for voucher creation', 'vch_no' => '');
		}

		return array('status' => 0, 'message' => "Voucher created successfully - $voucher_no", 'vch_no' => $voucher_no);
	}

	public function get_wallet($userid=-1, $companyid=-1) {
		$sql = "select 	usr.type as user_type, usr.name, usr.rateplanid, usr.is_admin, wl.id as walletid, wl.name wallet_name, wl.display_name as wallet_display_name, wl.userid, wl.companyid, wl.sponsoring_companyid, 
						wl.allowed_transactions, wl.wallet_account_code, wl.balance, wl.type as wallet_type, wl.status
				from 	system_wallets_tbl wl
						left outer join user_tbl usr on wl.userid=usr.id and usr.active=1";
		
		if($companyid!==NULL && $companyid!==-1) {
			$sql = $sql." where ((wl.companyid=$companyid or -1=$companyid) and wl.userid=0) and wl.status=1 and wl.type=1";
		} 
		else if($userid!==NULL && $userid!==-1) {
			$sql = $sql." where (wl.userid=$userid or -1=$userid) and wl.status=1 and wl.type=2";
		}

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function get_wallet_transaction($walletid=-1) {
		if($walletid==-1 || $walletid==NULL) return false;

		$sql = "select 	wlt.id as wallet_trans_id, wlt.wallet_id, wlt.date, wlt.trans_id, wlt.companyid, wlt.userid, wlt.amount, wlt.dr_cr_type, wlt.trans_type, wlt.trans_ref_id, wlt.trans_tracking_id, wlt.narration, wlt.sponsoring_companyid, 
						wlt.status, wlt.approved_by, wlt.approved_on, c.display_name as sponsoring_company_name
				from wallet_transaction_tbl wlt
				inner join wallet_tbl wl on wlt.wallet_id=wl.id
				inner join company_tbl c on wlt.sponsoring_companyid=c.id
				left outer join user_tbl usr on wlt.approved_by=usr.id
				where wl.id=$walletid";

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function getMyWallet($userid=-1, $companyid=-1) {
		$wallet = $this->get_wallet($userid, $companyid);

		$mywallet = &$wallet;

		if($mywallet && count($mywallet)>0) {
			$walletid = $mywallet[0]['walletid'];
			$mywallet[0]['transactions'] = $this->get_wallet_transaction($walletid);
		}

		return $mywallet;
	}

	public function get_next_voucherno($company) {
		// 'VCH-RR-000001/19-20'
		$companyid=intval($company['id']);
		$company_name = $company['display_name'];
		$company_abb = $this->abbreviate($company_name);

		$vchno = null;
		
		$this->db->where(array("companyid" => $companyid));
		$num = $this->db->count_all_results("account_transactions_tbl");
		$num++;

		$vchno = "VCH-$company_abb-".str_pad($num,8,"0",STR_PAD_LEFT);
		return $vchno;
	}

	public function book_ticket($parameters, $company, $current_user, $ticket, $wallet, $posteddata, $customers) {
		$booking_id = -1;
		$booking_activity_id = -1;
		$voucher_no = -1;
		$booking_type = isset($posteddata['booking_type']) ? $posteddata['booking_type'] : '';
		$correlationid = isset($posteddata['correlationid']) ? $posteddata['correlationid'] : uniqid();
		$amount = floatval($parameters["total"]);
		$isownticket = boolval($parameters["isownticket"]);
		$parentbooking_id = isset($parameters["pbooking_id"])?intval($parameters["pbooking_id"]):0;
		$seller_company = isset($parameters["seller_company"]) ? $parameters["seller_company"] : [];
		$sale_type = isset($parameters["sale_type"]) ? $parameters["sale_type"] : 'request';
		$pnr = isset($parameters["pnr"]) ? $parameters["pnr"] : '';
		$status = intval($parameters["status"]);
		
		if($pnr==='' && $parentbooking_id>0) {
			$status = 0;
			$parameters["status"] = $status;
		}

		if($amount>0) {
			try {
				$this->db->trans_begin();
				log_message("debug", "SearchModel:book_ticket-BeforeSave-{'bookingid': $booking_id, 'booking_activity_id': $booking_activity_id, 'parameters': ".json_encode($parameters)."}");

				#region Wholesaler side booking
				//Save booking from end users perspective
				$booking_id = $this->save("bookings_tbl", array(
					"booking_date"=>$parameters["booking_date"], 
					"ticket_id"=>$parameters["ticket_id"], 
					"pbooking_id" => $parentbooking_id, 
					"pnr"=>$pnr, 
					"customer_userid"=>$parameters["customer_userid"], 
					"customer_companyid"=>$parameters["customer_companyid"], 
					"seller_userid"=>$parameters["seller_userid"], 
					"seller_companyid"=>$parameters["seller_companyid"], 
					"status"=>$status, 
					"price"=>$parameters["price"], 
					"admin_markup"=>$parameters["admin_markup"], 
					"markup"=>$parameters["markup"], 
					"srvchg"=>$parameters["srvchg"], 
					"cgst"=>$parameters["cgst"], 
					"sgst"=>$parameters["sgst"], 
					"igst"=>$parameters["igst"], 
					"total"=>$parameters["total"], 
					"costprice"=>floatval($parameters["costprice"]), 
					"rateplanid"=>$parameters["rateplanid"], 
					"qty"=>$parameters["qty"], 
					"adult"=>intval($parameters["adult"]), 
					"child"=>intval($parameters["child"]), 
					"infant"=>intval($parameters["infant"]), 
					"infant_price"=>floatval($parameters["infant_price"]), 
					"created_by"=>$parameters["created_by"], 
					"created_on"=>date("Y-m-d H:i:s"),
					"correlationid" => $correlationid
				));

				$adult = isset($ticket['adult']) ? intval($ticket['adult']) : 0;
				$child = isset($ticket['child']) ? intval($ticket['child']) : 0;
				$infant = isset($ticket['infant']) ? intval($ticket['infant']) : 0;
				$no_of_person = $adult + $child + $infant;

				$flight = $this->get('airline_tbl', array('airline' => "'".$ticket['airline']."'"));
				if($flight && count($flight)>0) {
					$ticket['airlineid'] = $flight[0]['id'];
				}
				//insert record into booking_flight_tbl;
				$booking_ticket_id = $this->save("bookings_flights_tbl", array(
					"booking_id" => $booking_id,
					"ticket_id" => $ticket['id'],
					"ticket_key" => isset($ticket['aid']) ? $ticket['aid'] : '',
					"direction" => isset($ticket['direction']) ? $ticket['direction'] : 'OB',
					"source" => isset($ticket['source']) ? $ticket['source'] : -1,
					"destination" => isset($ticket['destination']) ? $ticket['destination'] : -1,
					"pnr" => isset($ticket['pnr']) ? $ticket['pnr'] : '',
					"trip_type" => isset($ticket['trip_type']) ? $ticket['trip_type'] : 'ONE',
					"departure_date_time" => isset($ticket['departure_date_time']) ? $ticket['departure_date_time'] : '',
					"arrival_date_time" => isset($ticket['arrival_date_time']) ? $ticket['arrival_date_time'] : '',
					"flight_no" => isset($ticket['flight_no']) ? $ticket['flight_no'] : '',
					"departure_terminal" => isset($ticket['terminal']) ? $ticket['terminal'] : '',
					"arrival_terminal" => isset($ticket['terminal1']) ? $ticket['terminal1'] : '',
					"no_of_person" => $no_of_person,
					"adult" => $adult,
					"child" => $child,
					"infant" => $infant,
					"class" => isset($ticket['class']) ? $ticket['class'] : 'ECONOMY',
					"no_of_stops" => isset($ticket['no_of_stops']) ? $ticket['no_of_stops'] : 0,
					"data_collected_from" => isset($ticket['data_collected_from']) ? $ticket['data_collected_from'] : '',
					"sale_type" => isset($ticket['sale_type']) ? $ticket['sale_type'] : 'request',
					"refundable" => isset($ticket['refundable']) ? $ticket['refundable'] : 'Y',
					"airline" => isset($ticket['airlineid']) ? $ticket['airlineid'] : (isset($ticket['airline']) ? $ticket['airline'] : -1),
					"aircode" => isset($ticket['aircode']) ? $ticket['aircode'] : '',
					"ticket_no" => isset($ticket['ticket_no']) ? $ticket['ticket_no'] : '',
					"companyid" => isset($ticket['companyid']) ? $ticket['companyid'] : -1,
					"user_id" => isset($ticket['uid']) ? $ticket['uid'] : -1,
					"supplier_rate_plan_id" => isset($ticket['rate_plan_id']) ? $ticket['rate_plan_id'] : -1,
					"seller_rate_plan_id" => isset($ticket['seller_rateplan_id']) ? $ticket['seller_rateplan_id'] : -1,
					"supplier_companyid" => isset($ticket['supplierid']) ? $ticket['supplierid'] : -1,
					"seller_companyid" => isset($ticket['sellerid']) ? $ticket['sellerid'] : -1,
					"result_index" => isset($ticket['ResultIndex']) ? $ticket['ResultIndex'] : '',
					"cost_price"=>floatval($parameters["costprice"]), 
					"price"=>$parameters["price"], 
					"admin_markup"=>$parameters["admin_markup"], 
					"markup"=>$parameters["markup"], 
					"srvchg"=>$parameters["srvchg"], 
					"cgst"=>$parameters["cgst"], 
					"sgst"=>$parameters["sgst"], 
					"igst"=>$parameters["igst"], 
					"total"=>$parameters["total"], 
					"discount"=>0.00,
					// "cost_price" => isset($ticket['cost_price']) ? $ticket['cost_price'] : 0.00,
					// "price" => isset($ticket['price']) ? $ticket['price'] : 0.00,
					// "admin_markup" => isset($ticket['admin_markup']) ? $ticket['admin_markup'] : 0.00,
					// "markup" => (isset($ticket['whl_markup']) ? floatval($ticket['whl_markup']) : 0) + (isset($ticket['spl_markup']) ? floatval($ticket['spl_markup']) : 0),
					// "srvchg" => (isset($ticket['whl_srvchg']) ? floatval($ticket['whl_srvchg']) : 0) + (isset($ticket['spl_srvchg']) ? floatval($ticket['spl_srvchg']) : 0),
					// "cgst" => (isset($ticket['whl_cgst']) ? floatval($ticket['whl_cgst']) : 0) + (isset($ticket['spl_cgst']) ? floatval($ticket['spl_cgst']) : 0),
					// "sgst" => (isset($ticket['whl_sgst']) ? floatval($ticket['whl_sgst']) : 0) + (isset($ticket['spl_sgst']) ? floatval($ticket['spl_sgst']) : 0),
					// "igst" => (isset($ticket['whl_igst']) ? floatval($ticket['whl_igst']) : 0) + (isset($ticket['spl_igst']) ? floatval($ticket['spl_igst']) : 0),
					// "discount" => (isset($ticket['whl_disc']) ? floatval($ticket['whl_disc']) : 0) + (isset($ticket['spl_disc']) ? floatval($ticket['spl_disc']) : 0),
					// "total" => isset($ticket['total']) ? $ticket['total'] : 0.00,
					"islcc" => isset($ticket['IsLCC']) ? $ticket['IsLCC'] : false,
					"gstallowed" => isset($ticket['GSTAllowed']) ? $ticket['GSTAllowed'] : false,
					"isholdallowedwithssr" => isset($ticket['IsHoldAllowedWithSSR']) ? $ticket['IsHoldAllowedWithSSR'] : false,
					"ispassportrequiredatticket" => isset($ticket['IsPassportRequiredAtTicket']) ? $ticket['IsPassportRequiredAtTicket'] : false,
					"ispassportrequiredatbook" => isset($ticket['IsPassportRequiredAtBook']) ? $ticket['IsPassportRequiredAtBook'] : false,
					"ispanrequiredatticket" => isset($ticket['IsPanRequiredAtTicket']) ? $ticket['IsPanRequiredAtTicket'] : false,
					"iscouponappilcable" => isset($ticket['IsCouponAppilcable']) ? $ticket['IsCouponAppilcable'] : false,
					"isgstmandatory" => isset($ticket['IsGSTMandatory']) ? $ticket['IsGSTMandatory'] : false,
					"ticket_type" => isset($ticket['ticket_type']) ? $ticket['ticket_type'] : '',
					"segments" => isset($ticket['segments']) ? json_encode($ticket['segments']) : json_encode('{}'),
					"adult_rate" => 0.00,
					"infant_rate" => 0.00,
					"cgst_rate" => 0.00,
					"sgst_rate" => 0.00,
					"igst_rate" => 0.00,
					"discount_rate" => 0.00,
					"api_bookingid" => isset($ticket['bookingid']) ? $ticket['bookingid'] : ''
				));

				log_message('debug', 'Booking Ticket id => '.$booking_ticket_id);
				//Save booking activity from end users perspective
				$booking_activity_id = $this->save("booking_activity_tbl", array(
					"booking_id"=>$booking_id,
					"activity_date"=>$parameters["booking_date"],
					"source_userid"=>$parameters["customer_userid"], 
					"source_companyid"=>$parameters["customer_companyid"], 
					"requesting_by"=>$parameters["requesting_by"], 
					"target_userid"=>$parameters["seller_userid"], 
					"target_companyid"=>$parameters["seller_companyid"], 
					"requesting_to"=>$parameters["requesting_to"], 
					"status" => (intval($parameters["status"])===2 ? 32 : $parameters["status"]), 
					"notes"=>'',
					"created_by"=>$parameters["created_by"], 
					"created_on"=>date("Y-m-d H:i:s")
				));

				//Perform account transaction if booking is saved
				$voucher_no = 0;
				$whl_voucher_no = 0;
				$spl_voucher_no = 0;
				if(intval($booking_id)>0) {
					$walletbalance = isset($parameters['wallet_balance'])?floatval($parameters['wallet_balance']):0;
					//if($booking_type==='' && $current_user && isset($current_user['is_admin']) && intval($current_user['is_admin'])===0 && $walletbalance>=floatval($parameters["debit"])) {
					if($booking_type==='' && $current_user && isset($current_user['is_admin']) && intval($current_user['is_admin'])===0) {
						$voucher_no = $this->save("account_transactions_tbl", array(
							"voucher_no" => $this->Search_Model->get_next_voucherno($company), 
							"transacting_companyid" => $parameters["customer_companyid"], 
							"transacting_userid" => $parameters["customer_userid"], 
							"documentid" => $booking_id, 
							"document_date" => $parameters["booking_date"], 
							"document_type" => 1,
							"transaction_type" => "PURCHASE", /* It was COLLECTION. Changing it to PURCHASE as it is PURCHASE for B2B & B2C */
							"debit" => $parameters["debit"],  /*Payment made by B2B/B2C towards wholesaler company. But ticket is not being issued. So amount is being posed towards B2B/B2C's accounts */
							//"credit" => $parameters["debit"],  
							"companyid" => $parameters["customer_companyid"],  
							"credited_accountid" => $parameters["ticket_account"],  
							"created_by"=>$parameters["created_by"],
							"narration"=>"Purchase booking (Booking id: $booking_id | booking date: ".date("Y-m-d H:i:s", strtotime($parameters["booking_date"].'+00:00')).")"
						));
					}
					else if($booking_type==='WHL-SPL' && $status!==0) {
						log_message("debug", "SearchModel:book_ticket-WHL-SPL-Making Account transaction: $booking_id, 'booking_activity_id': $booking_activity_id, 'parameters': ".json_encode($parameters)."}");
						$whl_voucher_no = $this->save("account_transactions_tbl", array(
							"voucher_no" => $this->Search_Model->get_next_voucherno($company), 
							"transacting_companyid" => $parameters["seller_companyid"], 
							"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
							"documentid" => $booking_id, 
							"document_date" => $parameters["booking_date"], 
							"document_type" => 1,
							"transaction_type" => "PURCHASE",
							//"debit" => $parameters["debit"],  
							"credit" => $parameters["debit"],  /*Payment was made before and that time amount posted as Debit and since ticket purchased from supplier so same amount should be credited to supplier's ladger also */
							"companyid" => $parameters["customer_companyid"],  
							"credited_accountid" => $parameters["ticket_account"],  
							"created_by"=>$parameters["created_by"],
							"narration" => "Purchase booking - (Booking id: $booking_id dated: ".date("Y-m-d H:i:s", strtotime($parameters["booking_date"].'+00:00')).')'
						));

						$whl_voucher_no = $this->save("account_transactions_tbl", array(
							"voucher_no" => $this->Search_Model->get_next_voucherno($company), 
							"transacting_companyid" => $parameters["customer_companyid"], 
							"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
							"documentid" => $booking_id, 
							"document_date" => $parameters["booking_date"], 
							"document_type" => 1,
							"transaction_type" => "SALES",
							//"credit" => $parameters["debit"],  
							"debit" => $parameters["debit"],  /*Sales made by Supplier towards wholesaler. When wholesaler paid money it was posted in Wholesaler's account as credit. Means amount needs to be paid back. Now ticket is being made so equal amount should be debited. */
							"companyid" => $parameters["seller_companyid"],  
							"credited_accountid" => $parameters["ticket_account"],  
							"created_by"=>$parameters["created_by"],
							"narration" => "Sales booking (Booking id: $booking_id dated: ".date("Y-m-d H:i:s", strtotime($parameters["booking_date"].'+00:00')).')'
						));
					}
				}

				//Add customer informations for the booking
				if($customers && is_array($customers) && count($customers)>0 && $booking_id>0 && $parentbooking_id === 0) {
					foreach($customers as $customer)
					{
						try
						{
							$dob = date("Y-m-d", strtotime(intval($customer["passenger_dob_year"])."-".intval($customer["passenger_dob_month"])."-".intval($customer["passenger_dob_day"])));

							$arr=array("prefix"=>$customer["passenger_title"],
										"first_name"=>$customer["passenger_first_name"],
										"last_name"=>$customer["passenger_last_name"],
										"mobile_no"=>$customer["passenger_mobile"],
										"age"=>$customer["passenger_age"], 
										"dob"=>($customer["passenger_type_code"]==="3"?$dob:null),
										"type"=>intval($customer["passenger_type_code"]), 
										"ticket_fare"=>round($amount/intval($parameters["qty"]), 0),
										"costprice"=>round(floatval($parameters["costprice"]), 0),
										"email"=>$customer["passenger_email"], 
										"companyid"=> intval($parameters["customer_companyid"]),
										"booking_id"=>$booking_id,
										"airline_ticket_no"=>$parameters["pnr"],
										"pnr"=>$parameters["pnr"],
										"status" => (intval($parameters["status"])===2 ? 2 : 1), 
										"created_by"=>$parameters["created_by"],
										"created_on"=>date("Y-m-d H:i:s")
									);
							$custinfo = $this->save("customer_information_tbl",$arr);
							log_message("debug", "SearchModel:book_ticket-AfterSave: Customer Id: $custinfo | customer-".json_encode($arr));
						}
						catch(Exception $ex1) {
							log_message("error", $ex1);
						}
					}
				}
				else if($parentbooking_id>0 && $booking_id>0) {
					$flag = $this->update('customer_information_tbl', 
							array('refrence_id' => $booking_id, 'status' => (intval($parameters["status"])===2 ? 2 : 1), 'updated_by' => $parameters["created_by"], 'updated_on' => date("Y-m-d H:i:s")),
							array('booking_id' => $parentbooking_id, 'status !=' => 127));
					$no_of_rows = $this->db->affected_rows();
					if ($flag && $no_of_rows>0) {
						log_message('debug', "customer_information_tbl : $no_of_rows number of records updated with refrence booking id from supplier side");
					}
				}
				#endregion

				log_message("debug", "SearchModel:book_ticket-AfterSave-{'bookingid': $booking_id, 'booking_activity_id': $booking_activity_id, 'voucher_no': $voucher_no, 'spl_voucher_no': $spl_voucher_no, 'whl_voucher_no': $whl_voucher_no, 'parameters': ".json_encode($parameters)."}");

				if($booking_id>0 && $booking_activity_id>0) {
					$this->db->trans_complete();

					//notify all the user
					$this->notify(array(
						'doctype' => 'booking',
						'docno' => $booking_id,
						'template' => 'booking'
					));
				}
				else {
					$this->db->trans_rollback();
				}
			}
			catch(Exception $ex) {
				log_message("error", $ex);
				$this->db->trans_rollback();
			}
		}

		return array('booking_id'=> $booking_id, 'booking_activity_id' => $booking_activity_id, 'voucher_no' => $voucher_no, 'spl_voucher_no' => $spl_voucher_no, 'whl_voucher_no' => $whl_voucher_no);
	}

	public function get_suppliers_contract($wholesalerid, $supplierid=-1) {
		if(intval($wholesalerid) === intval($supplierid)) return false;

		$sql = "select 	spl.code, spl.primary_user_id, spl.supplierid, spl.companyid, spls.serviceid, spls.allowfeed, spls.markup_rate, spls.markup_type, spls.rate_plan_id, spls.status, 
						spls.communicationid, spls.tracking_id, case when spls.transaction_type=1 then 'request' else 'live' end as sale_type
				from supplier_tbl spl
				inner join supplier_services_tbl spls on spl.id=spls.supplier_rel_id and spl.active=1
				where spl.companyid=$wholesalerid and (spl.supplierid=$supplierid or $supplierid=-1)";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function get_wholesaler_contract($wholesalerid, $supplierid=-1) {
		if(intval($wholesalerid) === intval($supplierid)) return false;

		$sql = "select 	whl.code, whl.primary_user_id, whl.salerid, whl.companyid, whls.serviceid, whls.allowfeed, whls.markup_rate, whls.markup_type, whls.rate_plan_id, whls.status, 
						whls.communicationid, whls.tracking_id, case when whls.transaction_type=1 then 'request' else 'live' end as sale_type
				from wholesaler_tbl whl
				inner join wholesaler_services_tbl whls on whl.id=whls.wholesaler_rel_id and whl.active=1
				where whl.companyid=$supplierid and (whl.salerid=$wholesalerid or $wholesalerid=-1)";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function statistics($payload) {
		$companyid = $payload['filter']['companyid'];
		$pastdays = isset($payload['filter']['pastdays']) ? intval($payload['filter']['pastdays']) : 7;
		$sql = "select * from stats_vw where companyid=$companyid";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function historical_sales($payload) {
		$companyid = $payload['filter']['companyid'];
		$pastdays = isset($payload['filter']['pastdays']) ? intval($payload['filter']['pastdays']) : 7;
		$sql = "select bk.seller_companyid, DATE_FORMAT(bk.booking_date, '%m-%d') as `day`, sum(bk.costprice+bk.markup+bk.srvchg+bk.cgst+bk.sgst) as total
			from bookings_tbl bk
			where bk.status=2 and (bk.booking_date>=(DATE_SUB(DATE_FORMAT(now(), '%Y-%m-%d 00:00:00'), INTERVAL $pastdays DAY))) 
			and (bk.customer_userid!=bk.seller_userid) and bk.seller_companyid=$companyid
			group by bk.seller_companyid, DATE_FORMAT(bk.booking_date, '%m-%d')";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function inventory_circle($payload) {
		$companyid = $payload['filter']['companyid'];
		$pastdays = isset($payload['filter']['pastdays']) ? intval($payload['filter']['pastdays']) : 7;
		$sql = "select tkt.companyid, concat(c1.code, '-', c2.code) as circle, count(tkt.no_of_person) as inventory
			from tickets_tbl tkt
			inner join city_tbl c1 on tkt.source = c1.id
			inner join city_tbl c2 on tkt.destination = c2.id
			where tkt.no_of_person>0 and tkt.approved=1 
			and tkt.available='YES' and trip_type='ONE'
			and tkt.departure_date_time>=DATE_FORMAT(now(), '%Y-%m-%d') and tkt.companyid=$companyid
			group by tkt.companyid, concat(c1.code, '-', c2.code)
			order by count(tkt.no_of_person) DESC
			limit 12";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function inventory_search($payload) {
		$companyid = $payload['filter']['companyid'];
		$pastdays = isset($payload['filter']['pastdays']) ? intval($payload['filter']['pastdays']) : 7;
		$sql = "select usr.companyid, DATE_FORMAT(usra.requested_on, '%d-%b') as req_date, count(usra.userid) as enquiry
			from user_activities_tbl usra
			inner join user_tbl usr on usr.id=usra.userid and usr.active=1
			where usra.controller='search' and usra.method='search_one_way' 
			and (usra.requested_on>=(DATE_SUB(DATE_FORMAT(now(), '%Y-%m-%d 00:00:00'), INTERVAL 10 DAY))) 
			and usr.companyid=$companyid 
			group by usr.companyid, DATE_FORMAT(usra.requested_on, '%d-%b')
			limit 12";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function getTemplates() {
		$sql = "select * from template_tbl where active=1";

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();		
		}
		else
		{
			return false;
		}
	}

	public function pnr_search($payload) {
		$companyid = isset($payload['filter']['companyid']) ? $payload['filter']['companyid'] : -1;
		$pnr = isset($payload['filter']['pnr']) ? $payload['filter']['pnr'] : '';
		if($pnr!='') {
			$sql = "select 	c1.city as source, c2.city as destination, tkt.departure_date_time, tkt.arrival_date_time, bk.status, tkt.max_no_of_person, tkt.no_of_person, bk.ticket_id, cus.id, cus.prefix, 
							cus.first_name, cus.last_name, cus.type as passenger_type, cus.age, cus.dob, cus.airline_ticket_no, cus.pnr, cus.booking_id, cus.refrence_id, cus.status, tkt.companyid
					from customer_information_tbl cus
					inner join bookings_tbl bk on bk.id=cus.booking_id and bk.status=2
					inner join tickets_tbl tkt on tkt.id=bk.ticket_id
					inner join city_tbl c1 on tkt.source=c1.id
					inner join city_tbl c2 on tkt.destination=c2.id
					where cus.pnr like '$pnr%' and (bk.seller_companyid=$companyid)
					union all
					select 	c1.city as source, c2.city as destination, tkt.departure_date_time, tkt.arrival_date_time, bk.status, tkt.max_no_of_person, tkt.no_of_person, bk.ticket_id, cus.id, cus.prefix, 
							cus.first_name, cus.last_name, cus.type as passenger_type, cus.age, cus.dob, cus.airline_ticket_no, cus.pnr, cus.booking_id, cus.refrence_id, cus.status, tkt.companyid
					from customer_information_tbl cus
					inner join bookings_tbl bk on bk.id=cus.refrence_id and bk.status=2
					inner join tickets_tbl tkt on tkt.id=bk.ticket_id
					inner join city_tbl c1 on tkt.source=c1.id
					inner join city_tbl c2 on tkt.destination=c2.id
					where cus.pnr like '$pnr%' and (bk.seller_companyid=$companyid and bk.seller_companyid!=bk.customer_companyid)";
			
			$query = $this->db->query($sql);
			//echo $this->db->last_query();die();
			if ($query->num_rows() > 0) 
			{					
				return $query->result_array();		
			}
			else
			{
				return false;
			}
		}
		else {
			return false;
		}
	}

	private function abbreviate($string) {
		$abbreviation = "";
		$string = ucwords($string);
		$words = explode(" ", "$string");
		  foreach($words as $word){
			  $abbreviation .= $word[0];
		  }
	   return $abbreviation; 
	}

	public function save_attribute($tblname, $update_data, $insert_data, $filter) {
		$this->db->where($filter);
		$flag = $this->db->update($tblname,$update_data);
		$no_of_rows = $this->db->affected_rows();
		if ($flag && $no_of_rows>0) 
		{					
            return true;
		}
		else
		{
			$flag = $this->save($tblname, $insert_data);
			return $flag;
		}
	}

	public function get_customers($companyid, $only_active=1, $type='B2B') {
		$this->db->select('usr.* ');
		$this->db->from('user_tbl usr ');
		$this->db->where("usr.companyid=$companyid and usr.type='$type' ");
		$this->db->order_by('usr.is_admin desc, usr.name asc');
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		
		if($query->num_rows() > 0) 
		{
            return $query->result_array();		
		}
		else
		{
			return false;
		}         	
	}

	public function get_wallet_balance($companyid, $userid=-1) {
		if(intval($userid)>0 && intval($companyid)>0) {
			$sql = "select id, name, display_name, companyid, userid, sponsoring_companyid, allowed_transactions, wallet_account_code, balance, type, status, created_by, created_on, updated_by, updated_on 
				from system_wallets_tbl 
				where sponsoring_companyid=$companyid and companyid=$companyid and userid=$userid";
		}
		else if(intval($companyid)>0) {
			$sql = "select id, name, display_name, companyid, userid, sponsoring_companyid, allowed_transactions, wallet_account_code, balance, type, status, created_by, created_on, updated_by, updated_on 
				from system_wallets_tbl 
				where sponsoring_companyid=-1 and companyid=$companyid and userid=0";
		}

		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			$wallet_entry = $query->result_array();
			if($wallet_entry && is_array($wallet_entry) && count($wallet_entry)>0) {
				return $wallet_entry[0];
			}
			else {
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function do_reducee_inventory($ticketid=0, $qty=0) {
		if (intval($qty)<=0 || intval($ticketid)<=0) {
			return null;
		}

		$flag = false;
		$current_ticket = $this->get('tickets_tbl', array('id' => $ticketid));
		if($current_ticket && is_array($current_ticket) && count($current_ticket)>0) {
			$current_ticket = $current_ticket[0];

			$current_qty = intval($current_ticket['no_of_person']);
			$available = ($current_qty - intval($qty));
		}

		$sql = "update tickets_tbl set no_of_person = no_of_person-$qty, max_no_of_person = max_no_of_person-$qty, availibility = availibility-$qty, available = '".($available==0?'NO':'YES')."' where id=$ticketid";
		$query = $this->db->query($sql);
		$no_of_rows = $this->db->affected_rows();
		if ($query && $no_of_rows>0) {
			log_message('debug', "Ticket qty reduced");
			$flag = true;
		}

		return $flag;
	}

	public function transact_wallet($payload) {
		if($payload==null) return false;

		$result = [];
		$wallet_id = intval($payload['wallet_id']);
		$bookingid = intval($payload['trans_ref_id']);
		$updated_by = intval($payload['approved_by']);
		$updated_on = date("Y-m-d H:i:s");
		if($bookingid>0) {
			try
			{
				$wallet = $this->get_wallet_balance(intval($payload['companyid']), -1);
				$total_cost = $payload['amount'];

				if($payload['dr_cr_type'] == 'DR') {
					$final_balance = floatval($wallet['balance'])-$total_cost;
				}
				else if($payload['dr_cr_type'] == 'CR') {
					$final_balance = floatval($wallet['balance'])+$total_cost;
				}
				$this->db->trans_begin();

				$wallet_transid = $this->save("wallet_transaction_tbl",$payload);
				$result['trans_id'] = $wallet_transid;
				if($wallet_transid>0) {
					//update($tbl,$data,$arr) 
					$wallet_update = $this->db->update("system_wallets_tbl", 
						array('balance' => $final_balance, 'updated_by' => $updated_by, 'updated_on' => $updated_on), 
						array('id' => $wallet_id, 'status' => 1));
					$no_of_rows = $this->db->affected_rows();
					$result['summary_updated'] = $wallet_update && $no_of_rows>0;
					$result['wallet_balance'] = $final_balance;

					if($wallet_update && $no_of_rows>0) {
						$result['status'] = true;
						log_message('debug', 'Search_Model::transact_wallet - Wallet transaction inserted '.json_encode($payload));
						log_message('debug', "Search_Model::transact_wallet - Wallet summary updated with final value | Final value: $final_balance");
						$this->db->trans_complete();
					}
					else {
						$result['status'] = false;
						$result['error'] = "Error: Wallet summery could not updated with final value | Final value: $final_balance";
						log_message('error', "Search_Model::transact_wallet - Error: Wallet summery could not updated with final value | Final value: $final_balance");
						$this->db->trans_rollback();
					}
				}
				else {
					$result['status'] = false;
					$result['error'] = "Error: Wallet transaction failed";
					log_message('error', 'Search_Model::transact_wallet - Error: Wallet transaction failed'.json_encode($payload));
					$this->db->trans_rollback();
				}
			}
			catch(Exception $ex) {
				$result['trans_id'] = 0;
				$result['summary_updated'] = false;
				$result['wallet_balance'] = 0;
				$result['status'] = false;
				$result['error'] = "Error: Wallet transaction failed";
				log_message('error', 'Search_Model::transact_wallet - Error: '.$ex);
				$this->db->trans_rollback();
			}
		}
		else {
			$result['trans_id'] = 0;
			$result['summary_updated'] = false;
			$result['wallet_balance'] = 0;
			$result['status'] = false;
			$result['error'] = "Error: Booking id can't be invalid";
			log_message('error', "Search_Model::transact_wallet - Booking id can't be invalid ".json_encode($payload));
		}

		return $result;
	}

	public function clone_ticket($ticketid, $companyid, $metadata) {
		$result = [];

		if(!$ticketid || intval($ticketid, 10)<=0) {
			$result = array('status' => "This ticket belongs to you only, can't clone it. You can modify this ticket if needed.", 'code' => 501, 'data' => []);

			return $result;
		}

		$ticketid = intval($ticketid, 10);

		//This ticket will be cloned and create a new ticket under current companyid. But if the same ticket is present 
		$targetticket = $this->Search_Model->get('tickets_tbl', array('id' => $ticketid));
		if($targetticket && count($targetticket)>0) {
			$targetticket = &$targetticket[0];
		}
		log_message('debug', "Cloning Taking - ".$ticketid." => ".json_encode($targetticket));
		if(intval($targetticket['companyid']) !== $companyid) {
			unset($targetticket['id']);
			unset($targetticket['pnr']);
			unset($targetticket['created_date']);
			unset($targetticket['created_on']);
			unset($targetticket['data_collected_from']);
			unset($targetticket['last_sync_key']);
			unset($targetticket['updated_by']);
			unset($targetticket['updated_on']);

			try
			{
				$targetticket['companyid'] = $companyid;
				$targetticket['cloned_from'] = $ticketid;
				$targetticket['flight_no'] = isset($metadata['flight_no'])?$metadata['flight_no']:'';
				$targetticket['no_of_person'] = isset($metadata['no_of_person'])?$metadata['no_of_person']:1;
				$targetticket['max_no_of_person'] = isset($metadata['no_of_person'])?$metadata['no_of_person']:1;
				$targetticket['availibility'] = isset($metadata['no_of_person'])?$metadata['no_of_person']:1;
				$targetticket['available'] = (isset($metadata['no_of_person']) && intval($metadata['no_of_person'])) > 0 ? 'YES' : 'NO';
				$targetticket['created_by'] = isset($metadata['current_userid'])?$metadata['current_userid']:0;
				$targetticket['price'] = isset($metadata['price'])?floatval($metadata['price']):0;
				$targetticket['total'] = isset($metadata['price'])?floatval($metadata['price']):0;
				$targetticket['tag'] = isset($metadata['tag'])?$metadata['tag']:'';
				$targetticket['departure_date_time'] = isset($metadata['departure_date_time']) ? $metadata['departure_date_time'] : '1970-01-01 00:00:00';
				$targetticket['arrival_date_time'] = isset($metadata['arrival_date_time']) ? $metadata['arrival_date_time'] : '1970-01-01 00:00:00';
				$targetticket['booking_freeze_by'] = isset($metadata['booking_freeze_by']) ? $metadata['booking_freeze_by'] : '1970-01-01 00:00:00';
				$targetticket['user_id'] = isset($metadata['current_userid'])?$metadata['current_userid']:0;
				$targetticket['ticket_no'] = "CLONED-".$targetticket['ticket_no'];

				$tktid = $this->Search_Model->save('tickets_tbl', $targetticket);

				if($tktid>0) {
					$targetticket = $this->Search_Model->get('tickets_tbl', array('id' => intval($tktid)));
					if($targetticket && count($targetticket)>0) {
						$targetticket = $targetticket[0];
					}
					$result['status'] ='Ticket successfully cloned';
					$result['code'] =200;
					$result['data'] =$targetticket;

					log_message('debug', "Taking cloned - ".$tktid." => ".json_encode($targetticket));
				}
				else {
					$result['status'] ='Ticket clonning failed. Check with admin.';
					$result['code'] =501;
					$result['data'] =[];
				}
			}
			catch(Exception $ex) {
				log_message('error', "Clone Error => $ex");
			}
		}
		else {
			$result['status'] ="This ticket belongs to you only, can't clone it. You can modify this ticket if needed.";
			$result['code'] =501;
			$result['data'] =[];
		}

		return $result;
	}

	public function get_my_inventory($companyid=-1, $source=-1, $destination=-1, $days=30) {
		if($companyid > 0) {
			$sql = "select 	tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city as source_city, ct1.code as source_city_code, ct2.city as destination_city, ct2.code as destination_city_code, tkt.trip_type, 
				tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person, tkt.class, tkt.no_of_stops, tkt.data_collected_from, tkt.sale_type, tkt.refundable, 
				tkt.total, al.airline, al.image, al.aircode as aircode, tkt.ticket_no, tkt.price, cm.id as companyid, cm.display_name as companyname, tkt.user_id ,tkt.data_collected_from, tkt.updated_on, 
				tkt.updated_by, tkt.tag, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id, rpv.markup as supl_markup, rpv.srvchg as supl_srvchg, 
				rpv.cgst as supl_cgst, rpv.sgst as supl_sgst, rpv.igst as supl_igst, rpv.disc as supl_disc, ((tkt.price+rpv.markup+ifnull(rpv_whl.markup, 0)+rpv.srvchg+ifnull(rpv_whl.srvchg,0))+((rpv.srvchg+ifnull(rpv_whl.srvchg,0))*rpv.igst)) as ticket_price, 
				max(ltkt.departure_date_time) as dept_date_time, max(ltkt.arrival_date_time) as arrv_date_time, max(ltkt.airline) as airline, max(ltkt.adultbasefare) as adultbasefare, 
				max(ltkt.adult_tax_fees) as adult_tax_fees, 
				max(TIMESTAMPDIFF(MINUTE, ltkt.departure_date_time, ltkt.arrival_date_time)) as timediff, max(ltkt.departure_terminal) as departure_terminal, max(ltkt.arrival_terminal) as arrival_terminal, 
				max(ltkt.adultbasefare+ltkt.adult_tax_fees+200) as adult_total	
			from tickets_tbl tkt 
			inner join city_tbl ct1 on tkt.source=ct1.id 
			inner join city_tbl ct2 on tkt.destination=ct2.id 
			inner join airline_tbl al on al.id=tkt.airline 
			inner join company_tbl cm on tkt.companyid=cm.id 
			inner join 
			(  
				(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id 
				from wholesaler_tbl spl  
				inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1 
				inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services' 
				inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
				inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1 
				where spl.salerid=$companyid) 
				union all 
				(select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id 
				from company_tbl cm 
				inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1 
				where cm.id=$companyid 
				limit 1) 
			) as rpt on tkt.companyid = rpt.supplierid 
			inner join rateplans_vw rpv on rpv.rateplanid=rpt.rate_plan_id   
			left outer join rateplans_vw rpv_whl on rpv_whl.rateplanid=rpt.seller_rateplan_id			
			left outer join live_tickets_tbl ltkt on ltkt.source=tkt.source and tkt.destination=ltkt.destination and al.aircode=ltkt.carrierid and ltkt.active=1  
					and ltkt.departure_date_time>=DATE_SUB(tkt.departure_date_time, INTERVAL 15 MINUTE)  
					and ltkt.departure_date_time<=DATE_ADD(tkt.departure_date_time, INTERVAL 15 MINUTE) 
					and ltkt.airline is not null 
			where tkt.source=$source and tkt.destination=$destination and tkt.trip_type='ONE' and tkt.available='YES' and tkt.approved=1 and tkt.no_of_person>=1 
				and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')>=now() and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')<=DATE_ADD(now(), INTERVAL $days DAY)
			group by tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city, ct2.city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person  
					, tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id, cm.display_name, tkt.user_id ,tkt.data_collected_from,  
					tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id, 
					rpv.markup, rpv.srvchg, rpv.cgst, rpv.sgst, rpv.igst, rpv.disc, ct1.city, ct2.city
			order by tkt.source, tkt.destination, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time";
			
			$query = $this->db->query($sql);
			//echo $this->db->last_query();die();
			if ($query->num_rows() > 0) 
			{					
				//return $query->result_array();
				$result = $query->result_array();
				if($result && is_array($result) && count($result)>0) {
					return $result;
				}
				else
				{
					return false;
				}
			}	
		}

		return false;
	}

	public function get_inventory_circles($companyid, $days=30, $triptype='ONE') {
		$circles = array();
		$source_city = array();
		$destination_city = array();

		if($companyid > 0) {
			$sql = "select distinct ct1.city as source_city, ct2.city as destination_city, ct1.code as source_city_code, ct1.id as src_id, ct2.code as destination_city_code, ct2.id as dst_id
			from tickets_tbl tkt 
			inner join city_tbl ct1 on tkt.source=ct1.id 
			inner join city_tbl ct2 on tkt.destination=ct2.id 
			inner join airline_tbl al on al.id=tkt.airline 
			inner join company_tbl cm on tkt.companyid=cm.id 
			inner join 
			(  
				(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id 
				from wholesaler_tbl spl  
				inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1 
				inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services' 
				inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
				inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1 
				where spl.salerid=$companyid) 
				union all 
				(select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id 
				from company_tbl cm 
				inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1 
				where cm.id=$companyid 
				limit 1) 
			) as rpt on tkt.companyid = rpt.supplierid 
			inner join rateplans_vw rpv on rpv.rateplanid=rpt.rate_plan_id     
			left outer join rateplans_vw rpv_whl on rpv_whl.rateplanid=rpt.seller_rateplan_id			
			left outer join live_tickets_tbl ltkt on ltkt.source=tkt.source and tkt.destination=ltkt.destination and al.aircode=ltkt.carrierid and ltkt.active=1  
					and ltkt.departure_date_time>=DATE_SUB(tkt.departure_date_time, INTERVAL 15 MINUTE)  
					and ltkt.departure_date_time<=DATE_ADD(tkt.departure_date_time, INTERVAL 15 MINUTE) 
					and ltkt.airline is not null 
			where tkt.trip_type='$triptype' and tkt.available='YES' and tkt.approved=1 and tkt.no_of_person>=1 
				and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')>=now() and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')<=DATE_ADD(now(), INTERVAL $days DAY)
			group by tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city, ct2.city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person  
					, tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id, cm.display_name, tkt.user_id ,tkt.data_collected_from,  
					tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id, 
					rpv.markup, rpv.srvchg, rpv.cgst, rpv.sgst, rpv.igst, rpv.disc, ct1.city, ct2.city
			order by ct1.city, ct2.city";
			
			$query = $this->db->query($sql);
			//echo $this->db->last_query();die();
			if ($query->num_rows() > 0) 
			{					
				//return $query->result_array();
				$result = $query->result_array();
				if($result && is_array($result) && count($result)>0) {
					$src_city = array();
					$dst_city = array();
					foreach ($result as $row) {
						$circles[] = array(
							'source_city' => $row['source_city'], 
							'source_id' => $row['src_id'], 
							'source_code' => $row['source_city_code'],
							'destination_city' => $row['destination_city'], 
							'destination_id' => $row['dst_id'], 
							'destination_code' => $row['destination_city_code'],
						);
					}
					
					return $circles;
				}
				else {
					return false;
				}
			}
			else
			{
				return false;
			}

		}

		return $circles;
	}

	public function get_inventory_calender($companyid, $days = 7) {
		$circles = array();
		$source_city = array();
		$destination_city = array();

		if($companyid > 0) {
			$sql = "select b.circle, sum(b.price_1) as price_1, sum(b.price_2) as price_2, sum(b.price_3) as price_3, sum(b.price_4) as price_4, sum(b.price_5) as price_5, sum(b.price_6) as price_6, sum(b.price_7) as price_7, sum(b.price_8) as price_8
			from
			(
				select a.circle, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 0 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_1, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 1 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_2, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 2 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_3, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 3 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_4, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 4 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_5, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 5 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_6, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 6 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_7, 
				if(DATE_FORMAT(DATE_ADD(now(), INTERVAL 7 DAY),'%e-%b')=a.dow, min(a.ticket_price), 0) as price_8 
				from
				(
					select concat(ct1.code, '-', ct2.code) as circle, DATE_FORMAT(tkt.departure_date_time,'%e-%b') as dow, ((tkt.price+rpv.markup+ifnull(rpv_whl.markup, 0)+rpv.srvchg+ifnull(rpv_whl.srvchg,0))+((rpv.srvchg+ifnull(rpv_whl.srvchg,0))*rpv.igst)) as ticket_price  
					from tickets_tbl tkt 
					inner join city_tbl ct1 on tkt.source=ct1.id 
					inner join city_tbl ct2 on tkt.destination=ct2.id 
					inner join airline_tbl al on al.id=tkt.airline 
					inner join company_tbl cm on tkt.companyid=cm.id 
					inner join 
					(  
						(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id 
						from wholesaler_tbl spl  
						inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1 
						inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services' 
						inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.companyid=spl.salerid and whl.active=1 
						inner join supplier_services_tbl whd on whl.id=whd.supplier_rel_id and whd.active=1 and whd.allowfeed=1 
						where spl.salerid=$companyid) 
						union all 
						(select cm.id as supplierid, rp.id as rate_plan_id, 0 as sellerid, 0 as seller_rateplan_id 
						from company_tbl cm 
						inner join rateplan_tbl rp on cm.id=rp.companyid and rp.active=1 and rp.default=1 
						where cm.id=$companyid 
						limit 1) 
					) as rpt on tkt.companyid = rpt.supplierid 
					inner join rateplans_vw rpv on rpv.rateplanid=rpt.rate_plan_id      
					left outer join rateplans_vw rpv_whl on rpv_whl.rateplanid=rpt.seller_rateplan_id					 
					left outer join live_tickets_tbl ltkt on ltkt.source=tkt.source and tkt.destination=ltkt.destination and al.aircode=ltkt.carrierid and ltkt.active=1  
							and ltkt.departure_date_time>=DATE_SUB(tkt.departure_date_time, INTERVAL 15 MINUTE)  
							and ltkt.departure_date_time<=DATE_ADD(tkt.departure_date_time, INTERVAL 15 MINUTE) 
							and ltkt.airline is not null 
					where -- tkt.source=4 and tkt.destination=5 and 
					tkt.trip_type='ONE' and tkt.available='YES' and tkt.approved=1 and tkt.no_of_person>=1 
					and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')>=now() and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')<=DATE_ADD(now(), INTERVAL 8 DAY)
					group by tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city, ct2.city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person  
							, tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id, cm.display_name, tkt.user_id ,tkt.data_collected_from,  
							tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id, 
							rpv.markup, rpv.srvchg, rpv.cgst, rpv.sgst, rpv.igst, rpv.disc, ct1.city, ct2.city
					order by tkt.source, tkt.destination, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time
				) a
				group by a.circle, a.dow
			) b
			group by b.circle";
			
			$query = $this->db->query($sql);
			//echo $this->db->last_query();die();
			if ($query->num_rows() > 0) 
			{					
				//return $query->result_array();
				$result = $query->result_array();
				if($result && is_array($result) && count($result)>0) {
					$col_name_1 = $this->get_column_name('price_1', 0);
					$col_name_2 = $this->get_column_name('price_2', 1);
					$col_name_3 = $this->get_column_name('price_3', 2);
					$col_name_4 = $this->get_column_name('price_4', 3);
					$col_name_5 = $this->get_column_name('price_5', 4);
					$col_name_6 = $this->get_column_name('price_6', 5);
					$col_name_7 = $this->get_column_name('price_7', 6);
					$col_name_8 = $this->get_column_name('price_8', 7);

					foreach ($result as $row) {
						
						$circles[] = array(
							'Circle' => $row['circle'],
							"$col_name_1" => round(floatval($row['price_1']),0) > 0 ? '₹ '.round(floatval($row['price_1']),0) : '-', 
							"$col_name_2" => round(floatval($row['price_2']),0) > 0 ? '₹ '.round(floatval($row['price_2']),0) : '-', 
							"$col_name_3" => round(floatval($row['price_3']),0) > 0 ? '₹ '.round(floatval($row['price_3']),0) : '-', 
							"$col_name_4" => round(floatval($row['price_4']),0) > 0 ? '₹ '.round(floatval($row['price_4']),0) : '-', 
							"$col_name_5" => round(floatval($row['price_5']),0) > 0 ? '₹ '.round(floatval($row['price_5']),0) : '-', 
							"$col_name_6" => round(floatval($row['price_6']),0) > 0 ? '₹ '.round(floatval($row['price_6']),0) : '-', 
							"$col_name_7" => round(floatval($row['price_7']),0) > 0 ? '₹ '.round(floatval($row['price_7']),0) : '-', 
							"$col_name_8" => round(floatval($row['price_8']),0) > 0 ? '₹ '.round(floatval($row['price_8']),0) : '-', 
						);
					}
					
					return $circles;
				}
				else {
					return false;
				}
			}
			else
			{
				return false;
			}

		}

		return $circles;
	}

	public function get_column_name($col_name, $day_index) {
		$date=date_create(date("Y-m-d H:i:s"));
		//echo date_format($date,"Y-m-d")."</br>";
		date_add($date,date_interval_create_from_date_string("$day_index days"));
		return date_format($date,"d-M");
	}

	public function getUserQuery($companyid = -1) {
		$sql = "select usr.name, c1.city as source, c2.city as destination, qry.id, qry.reqid, qry.source_city, qry.destination_city, qry.departure_date, qry.no_of_person, qry.start_price, qry.end_price, qry.time_range, qry.mobile, qry.email, qry.remarks, qry.userid, qry.companyid, qry.created_by, qry.created_on, qry.is_flexible, qry.status      
			from user_query_tbl qry 
			inner join city_tbl c1 on qry.source_city=c1.id 
			inner join city_tbl c2 on qry.destination_city=c2.id 
			left outer join user_tbl usr on usr.id=qry.userid 
			where qry.companyid = $companyid and qry.status=1 
			order by created_on desc";
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();
		}
		else {
			return false;
		}

		log_message('debug', "getUserQuery => Companyid : $companyid");
	}

	public function get_cities() {
		$sql = "select c.city as source_city, c.id as source_id, c.code as source_code   
				from city_tbl c
				where c.city<>''
				order by c.city";
		$query = $this->db->query($sql);
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
			return $query->result_array();
		}
		else {
			return false;
		}
	}
}
?>
