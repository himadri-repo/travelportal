<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Search_Model extends CI_Model
{
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

		#region commented
		// $sql = "select tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city as source_city, ct2.city as destination_city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person, tkt.class, 
			// 	tkt.no_of_stops, tkt.data_collected_from, tkt.sale_type, tkt.refundable, tkt.total, 
			// 	al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, sl1.wsl_markup_rate as admin_markup, sl1.wsl_srvchg_rate as admin_crvchg, cm.id as companyid, tkt.user_id ,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, 
			// 	tkt.last_sync_key, tkt.approved, sl1.display_name as supplier, sl1.slr_markup_rate as markup_rate, sl1.slr_srvchg_rate as srvchg_rate, sl1.markup_type, sl1.cgst_rate, sl1.sgst_rate, sl1.igst_rate, sl1.allowfeed, 
			// 	sl1.service, sl1.owner_companyid,
			// 	max(ltkt.departure_date_time) as dept_date_time, max(ltkt.arrival_date_time) as arrv_date_time, max(ltkt.airline) as airline, max(ltkt.adultbasefare) as adultbasefare, max(ltkt.adult_tax_fees) as adult_tax_fees, 
			// 	max(TIMESTAMPDIFF(MINUTE, ltkt.departure_date_time, ltkt.arrival_date_time)) as timediff, max(ltkt.departure_terminal) as departure_terminal, max(ltkt.arrival_terminal) as arrival_terminal, max(ltkt.adultbasefare+ltkt.adult_tax_fees+200) as adult_total
			// from tickets_tbl tkt 
			// inner join city_tbl ct1 on tkt.source=ct1.id
			// inner join city_tbl ct2 on tkt.destination=ct2.id
			// inner join airline_tbl al on al.id=tkt.airline
			// inner join company_tbl cm on tkt.companyid=cm.id
			// inner join
			// (
			// 	select sl.code, sl.primary_user_id, supplierid, cm.display_name, slr.markup_type
			// 	,ifnull((select sum(if(rpd.amount_type=1, rpd.amount, rpd.amount/100)) from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='markup'), 0) as wsl_markup_rate
			// 	,ifnull((select sum(if(rpd.amount_type=1, rpd.amount, rpd.amount/100)) from rateplan_detail_tbl rpd where rpd.rateplanid=slr.rate_plan_id and rpd.head_code='markup'), 0) as slr_markup_rate
			// 	,ifnull((select sum(if(rpd.amount_type=1, rpd.amount, rpd.amount/100)) from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='srvchg'), 0) as wsl_srvchg_rate
			// 	,ifnull((select sum(if(rpd.amount_type=1, rpd.amount, rpd.amount/100)) from rateplan_detail_tbl rpd where rpd.rateplanid=slr.rate_plan_id and rpd.head_code='srvchg'), 0) as slr_srvchg_rate
			// 	, (select rpd.amount from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='cgst') as cgst_rate
			// 	, (select rpd.amount from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='sgst') as sgst_rate
			// 	, (select rpd.amount from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='igst') as igst_rate
			// 	,slr.allowfeed,  srv.datavalue as service, sl.companyid as owner_companyid
			// 	, slr.rate_plan_id as wholesaler_rateplan, wsl.rate_plan_id as supplier_rateplan
			// 	from supplier_tbl sl 
			// 	inner join company_tbl cm on sl.supplierid=cm.id and cm.active=1
			// 	inner join supplier_services_tbl slr on sl.id=slr.supplier_rel_id and slr.active=1
			// 	inner join metadata_tbl srv on srv.id=slr.serviceid and srv.active=1 and srv.code='FSRV0001'
			// 	inner join wholesaler_services_tbl wsl on wsl.id=slr.tracking_id and wsl.tracking_id=slr.id
			// 	where sl.companyid=$companyid and slr.allowfeed=1
			// union all
			// 	select cm1.code, cm1.primary_user_id, cm1.id as supplierid, cm1.display_name, 1 as markup_type, 0 as wsl_markup_rate, 0 as slr_markup_rate, 0 as wsl_srvchg_rate, 0 as slr_srvchg_rate, 
			// 	0 as cgst_rate, 0 as sgst_rate, 0 as igst_rate, 1 as allowfeed, 'Coupon Flight Tickets' as service, cm1.id as owner_companyid, 1 as supplier_rateplan, 1 as wholesaler_rateplan
			// 	from company_tbl cm1
			// 	where cm1.id=$companyid and active=1
			// ) as sl1 on sl1.supplierid=cm.id
			// left outer join live_tickets_tbl ltkt on ltkt.source=tkt.source and tkt.destination=ltkt.destination and al.aircode=ltkt.carrierid and ltkt.active=1 
			// 	and ltkt.departure_date_time>=DATE_SUB(tkt.departure_date_time, INTERVAL 15 MINUTE) 
			// 	and ltkt.departure_date_time<=DATE_ADD(tkt.departure_date_time, INTERVAL 15 MINUTE)
			// 	and ltkt.airline is not null 
			// where tkt.source=$source and tkt.destination=$destination and tkt.trip_type='$triptype' and tkt.available='$available' and tkt.approved=$approved and DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d')='$from_date' and tkt.no_of_person>=$no_of_person and sl1.owner_companyid=$companyid
			// group by tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city, ct2.city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person, 
			// 	tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, sl1.wsl_markup_rate, cm.id, tkt.user_id ,tkt.data_collected_from, 
			// 	tkt.total, tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.last_sync_key, tkt.approved, sl1.display_name, sl1.slr_markup_rate, sl1.markup_type, sl1.cgst_rate, sl1.sgst_rate, sl1.igst_rate, sl1.allowfeed, 
			// 	sl1.service, sl1.owner_companyid, sl1.wsl_srvchg_rate, sl1.slr_srvchg_rate
			// order by (price + admin_markup + markup) asc";

		#endregion
		
		// order by sl1.display_name asc, ct1.city asc, ct2.city asc, tkt.departure_date_time asc";
		// where DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d %H:%i:%s')>='$dt_from' and tkt.no_of_person>0 and sl1.owner_companyid=$companyid

		$sql = "select 	tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city as source_city, ct2.city as destination_city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person, tkt.class, 
						tkt.no_of_stops, tkt.data_collected_from, tkt.sale_type, tkt.refundable, tkt.total, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id as companyid, tkt.user_id ,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, 
						tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, 
						max(ltkt.departure_date_time) as dept_date_time, max(ltkt.arrival_date_time) as arrv_date_time, max(ltkt.airline) as airline, max(ltkt.adultbasefare) as adultbasefare, max(ltkt.adult_tax_fees) as adult_tax_fees, 
						max(TIMESTAMPDIFF(MINUTE, ltkt.departure_date_time, ltkt.arrival_date_time)) as timediff, max(ltkt.departure_terminal) as departure_terminal, max(ltkt.arrival_terminal) as arrival_terminal, max(ltkt.adultbasefare+ltkt.adult_tax_fees+200) as adult_total
				from tickets_tbl tkt 
				inner join city_tbl ct1 on tkt.source=ct1.id 
				inner join city_tbl ct2 on tkt.destination=ct2.id 
				inner join airline_tbl al on al.id=tkt.airline 
				inner join company_tbl cm on tkt.companyid=cm.id 
				inner join 
					(  
					(select spl.companyid as supplierid, spd.rate_plan_id 
					from wholesaler_tbl spl  
					inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and `spd`.`active` = 1 and spd.allowfeed=1 
					inner join metadata_tbl mtd on mtd.id=spd.serviceid and `mtd`.`active` = 1 and mtd.associated_object_type='services' 
					where spl.salerid=$companyid) 
					union all 
					(select cm.id as supplierid, rp.id as rate_plan_id 
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
						, tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id, tkt.user_id ,tkt.data_collected_from,  
						tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid 
				order by (price + admin_markup + markup)";

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
	
	 public function search_one_way($arr) 
	{
		//$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops, t.data_collected_from, t.aircode,t.flight_no');
		$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops, t.data_collected_from, t.aircode,t.flight_no, 
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
		$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops,t.trip_type');
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
		$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops,t.trip_type');
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
		$this->db->select('t.user_id,t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.price,t.total,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,c1.city as source_city1,ct1.city as destination_city1,t.no_of_person,t.class,a.image,t.no_of_stops,t.no_of_stops1');
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
		$this->db->select('u.id as user_id,t.user_id as uid,t.id,t.source,t.destination,t.source1,t.destination1,t.ticket_no,t.pnr,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.flight_no,t.flight_no1,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.departure_date_time1,t.arrival_date_time1,t.flight_no1,t.total,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,c1.city as source_city1,ct1.city as destination_city1,t.class,t.class1,t.no_of_person,a.image,t.airline,t.airline1,t.trip_type,t.price,t.baggage,t.meal,t.markup,t.discount,t.availibility,t.aircode,t.aircode1,t.no_of_stops,t.no_of_stops1,t.remarks,t.approved,t.remarks,t.stops_name,t.stops_name1,t.available, rpt.rate_plan_id, rpt.supplierid', FALSE);
		$this->db->from('tickets_tbl t');
		$this->db->join("(
				(select spl.companyid as supplierid, spd.rate_plan_id
				from wholesaler_tbl spl 
				inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and `spd`.`active` = 1 and spd.allowfeed=1
				inner join metadata_tbl mtd on mtd.id=spd.serviceid and `mtd`.`active` = 1 and mtd.associated_object_type='services'
				where spl.salerid=$companyid)
				union all
				(select cm.id as supplierid, rp.id as rate_plan_id
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
	
	public function company_setting($companyid) {
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
			'service_charge' => 0.00,
			'cgst' => 0.00,
			'igst' => 0.00
		);
		
		if ($query->num_rows() > 0) 
		{	
			foreach ($query->result_array() as $row) {
				$data[$row['code']] = $row['datavalue'];
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
	
    public function save($tbl,$data) 
	{                      
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
    public function booking_details($id) 
	{    
	    
        $arr=array("b.id"=>$id);  	
		$this->db->select('cus.prefix,cus.email as cemail,cus.first_name,cus.last_name,cus.age,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status');
		$this->db->from('tickets_tbl as t');
		$this->db->join('booking_tbl b', 'b.ticket_id = t.id');
		$this->db->join('airline_tbl a', 'a.id = t.airline');
		$this->db->join('airline_tbl a1', 'a1.id = t.airline1','left');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->join('user_tbl u', 'b.customer_id =u.id');
		$this->db->join('customer_information_tbl cus', 'b.id =cus.booking_id',"left");
		
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
	
	 public function refrence_booking_details($id) 
	{    
	    
        $arr=array("b.booking_id"=>$id);  	
		$this->db->select('cus.prefix,cus.email as cemail,cus.first_name,cus.last_name,cus.age,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status');
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
		$this->db->select('cus.prefix,cus.email as cemail,cus.first_name,cus.last_name,cus.age,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status');
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
		$this->db->select('cus.prefix,cus.email as cemail,cus.first_name,cus.last_name,cus.age,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status,t.price,t.markup');
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
		$this->db->select("DATE_FORMAT(departure_date_time, '%d-%m-%Y') as departure_date_time, (price) as price, admin_markup, rpt.rate_plan_id, rpt.supplierid");
		$this->db->from('tickets_tbl');					
		$this->db->join("( 
			(select spl.companyid as supplierid, spd.rate_plan_id  
			from wholesaler_tbl spl 
			inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and `spd`.`active` = 1 and spd.allowfeed=1 
			inner join metadata_tbl mtd on mtd.id=spd.serviceid and `mtd`.`active` = 1 and mtd.associated_object_type='services' 
			where spl.salerid=$companyid)     
			union all 
			(select cm.id as supplierid, rp.id as rate_plan_id 
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

     public function terms()
	 {
		 
		$this->db->select("*");
		$this->db->from('term_tbl');					
		
		
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

	 public function get_tickets($companyid) {
		$dt_from = date("Y-m-d H:i:s");
		// inner join user_tbl usr on usr.id=tkt.user_id

		// $sql = "select tkt.id, ct1.city source, ct2.city destination, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no
		// 			,tkt.terminal, tkt.no_of_person, tkt.class, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, tkt.admin_markup, cm.id, tkt.user_id
		// 			,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, tkt.last_sync_key, tkt.approved, sl1.display_name as supplier, sl1.markup_rate, sl1.markup_type, sl1.allowfeed, sl1.service, sl1.owner_companyid
		// 		from tickets_tbl tkt 
		// 		inner join city_tbl ct1 on tkt.source=ct1.id
		// 		inner join city_tbl ct2 on tkt.destination=ct2.id
		// 		inner join airline_tbl al on al.id=tkt.airline
		// 		inner join company_tbl cm on tkt.companyid=cm.id
		// 		inner join
		// 		(
		// 			select sl.code, sl.primary_user_id, supplierid, cm.display_name, slr.markup_rate, slr.markup_type, slr.allowfeed,  srv.datavalue as service, sl.companyid as owner_companyid
		// 			from supplier_tbl sl 
		// 			inner join company_tbl cm on sl.supplierid=cm.id and cm.active=1
		// 			inner join supplier_services_tbl slr on sl.id=slr.supplier_rel_id and slr.active=1
		// 			inner join metadata_tbl srv on srv.id=slr.serviceid and srv.active=1 and srv.code='FSRV0001'
		// 			where sl.companyid=$companyid and slr.allowfeed=1
		// 			union all
		// 			select cm1.code, cm1.primary_user_id, cm1.id as supplierid, cm1.display_name, 0 as markup_rate, 1 as markup_type, 1 as allowfeed, 'Coupon Flight Tickets' as service, cm1.id as owner_companyid
		// 			from company_tbl cm1
		// 			where cm1.id=$companyid and active=1
		// 		) as sl1 on sl1.supplierid=cm.id
		// 		where DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d %H:%i:%s')>='$dt_from' and tkt.no_of_person>0 and sl1.owner_companyid=$companyid
		// 		order by sl1.display_name asc, ct1.city asc, ct2.city asc, tkt.departure_date_time asc";

		$sql = "select tkt.id, ct1.city source, ct2.city destination, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no
				,tkt.terminal, tkt.no_of_person, tkt.class, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, sl1.wsl_markup_rate as admin_markup, cm.id, tkt.user_id
				,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, tkt.last_sync_key, tkt.approved, sl1.display_name as supplier, 
				sl1.slr_markup_rate as markup_rate, sl1.markup_type, sl1.cgst_rate, sl1.sgst_rate, sl1.igst_rate, sl1.allowfeed, sl1.service, sl1.owner_companyid
			from tickets_tbl tkt 
			inner join city_tbl ct1 on tkt.source=ct1.id
			inner join city_tbl ct2 on tkt.destination=ct2.id
			inner join airline_tbl al on al.id=tkt.airline
			inner join company_tbl cm on tkt.companyid=cm.id
			inner join
			(
				select sl.code, sl.primary_user_id, supplierid, cm.display_name, slr.markup_type
				,ifnull((select sum(if(rpd.amount_type=1, rpd.amount, rpd.amount)) from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='markup'), 0) as wsl_markup_rate
				,ifnull((select sum(if(rpd.amount_type=1, rpd.amount, rpd.amount)) from rateplan_detail_tbl rpd where rpd.rateplanid=slr.rate_plan_id and rpd.head_code='markup'), 0) as slr_markup_rate
				, (select rpd.amount from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='cgst') as cgst_rate
				, (select rpd.amount from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='sgst') as sgst_rate
				, (select rpd.amount from rateplan_detail_tbl rpd where rpd.rateplanid=wsl.rate_plan_id and rpd.head_code='igst') as igst_rate
				,slr.allowfeed,  srv.datavalue as service, sl.companyid as owner_companyid
				, slr.rate_plan_id as wholesaler_rateplan, wsl.rate_plan_id as supplier_rateplan
				from supplier_tbl sl 
				inner join company_tbl cm on sl.supplierid=cm.id and cm.active=1
				inner join supplier_services_tbl slr on sl.id=slr.supplier_rel_id and slr.active=1
				inner join metadata_tbl srv on srv.id=slr.serviceid and srv.active=1 and srv.code='FSRV0001'
				inner join wholesaler_services_tbl wsl on wsl.id=slr.tracking_id and wsl.tracking_id=slr.id
				where sl.companyid=$companyid and slr.allowfeed=1
				union all
				select cm1.code, cm1.primary_user_id, cm1.id as supplierid, cm1.display_name, 1 as markup_type, 0 as wsl_markup_rate, 0 as slr_markup_rate, 0 as cgst_rate, 0 as sgst_rate, 0 as igst_rate,
				1 as allowfeed, 'Coupon Flight Tickets' as service, cm1.id as owner_companyid
				,1 as supplier_rateplan, 1 as wholesaler_rateplan
				from company_tbl cm1
				where cm1.id=$companyid and active=1
			) as sl1 on sl1.supplierid=cm.id
			where DATE_FORMAT(tkt.departure_date_time,'%Y-%m-%d %H:%i:%s')>='$dt_from' and tkt.no_of_person>0 and sl1.owner_companyid=$companyid
			order by sl1.display_name asc, ct1.city asc, ct2.city asc, tkt.departure_date_time asc";

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
}	
?>