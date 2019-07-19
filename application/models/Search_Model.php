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

		$sql = "select 	tkt.id, tkt.source, tkt.destination, tkt.pnr, ct1.city as source_city, ct2.city as destination_city, tkt.trip_type, tkt.departure_date_time, tkt.arrival_date_time, tkt.flight_no ,tkt.terminal, tkt.no_of_person, tkt.class, 
						tkt.no_of_stops, tkt.data_collected_from, tkt.sale_type, tkt.refundable, tkt.total, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id as companyid, cm.display_name as companyname, tkt.user_id ,tkt.data_collected_from, tkt.updated_on, tkt.updated_by, 
						tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id, 
						max(ltkt.departure_date_time) as dept_date_time, max(ltkt.arrival_date_time) as arrv_date_time, max(ltkt.airline) as airline, max(ltkt.adultbasefare) as adultbasefare, max(ltkt.adult_tax_fees) as adult_tax_fees, 
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
					inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.active=1 
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
						, tkt.class, tkt.no_of_stops, tkt.data_collected_from, al.airline, al.image, tkt.aircode, tkt.ticket_no, tkt.price, cm.id, cm.display_name, tkt.user_id ,tkt.data_collected_from,  
						tkt.refundable, tkt.sale_type, tkt.updated_on, tkt.updated_by, tkt.admin_markup, tkt.last_sync_key, tkt.approved, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id  
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
		$this->db->select('u.id as user_id,t.user_id as uid,t.id,t.source,t.destination,t.source1,t.destination1,t.ticket_no,t.pnr,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.flight_no,t.flight_no1,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.departure_date_time1,t.arrival_date_time1,t.flight_no1,t.total,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,c1.city as source_city1,ct1.city as destination_city1,t.class,t.class1,t.no_of_person,a.image,t.airline,t.airline1,t.trip_type,t.price,t.baggage,t.meal,t.markup,t.discount,t.availibility,t.aircode,t.aircode1,t.no_of_stops,t.no_of_stops1,t.remarks,t.approved,t.remarks,t.stops_name,t.stops_name1,t.available, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id', FALSE);
		$this->db->from('tickets_tbl t');
		$this->db->join("(
				(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id
				from wholesaler_tbl spl 
				inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1
				inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services'
				inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.active=1
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

    public function booking_details($id) 
	{    
	    
        // $arr=array("b.id"=>$id);  	
		// $this->db->select('cus.prefix,cus.email as cemail,cus.first_name,cus.last_name,cus.age,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,b.date,c.city as source,c1.city as source1,ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,b.service_charge,b.sgst,b.cgst,b.igst,b.rate,b.qty,b.amount,b.total,b.type,b.status,b.customer_id,b.seller_id,a.image,b.booking_confirm_date,b.seller_status');
		// $this->db->from('tickets_tbl as t');
		// $this->db->join('booking_tbl b', 'b.ticket_id = t.id');
		// $this->db->join('airline_tbl a', 'a.id = t.airline');
		// $this->db->join('airline_tbl a1', 'a1.id = t.airline1','left');
		// $this->db->join('city_tbl c', 'c.id = t.source');
		// $this->db->join('city_tbl ct', 'ct.id = t.destination');
		// $this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		// $this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		// $this->db->join('user_tbl u', 'b.customer_id =u.id');
		// $this->db->join('customer_information_tbl cus', 'b.id =cus.booking_id',"left");
		
		// $this->db->where($arr);
		// $this->db->order_by("cus.id","ASC");

		$qry = "select 	cus.prefix,cus.email as cemail,cus.first_name,cus.last_name,cus.age,cus.mobile_no,cus.pnr,u.name,u.email,u.mobile,b.id,b.ticket_id,t.ticket_no,c.city as source,c1.city as source1,
						ct.city as destination,ct1.city as destination1,a.airline,a1.airline as airline1,t.class,t.class1,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.trip_type,t.terminal,
						t.terminal1,t.terminal2,t.terminal3,t.flight_no,t.flight_no1,u.type,
						b.booking_date as date, b.srvchg as service_charge,b.sgst,b.cgst,b.igst,b.price as rate,b.qty,(b.price * b.qty) as amount,b.total, b.costprice, b.rateplanid, b.status,b.customer_userid, b.customer_companyid, b.seller_userid, b.seller_companyid,
						a.image,b.booking_confirm_date, ifnull((select status from booking_activity_tbl where booking_id=$id and (requesting_by & 4)=4 order by activity_date limit 1),0) as seller_status
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
		$this->db->select("DATE_FORMAT(departure_date_time, '%d-%m-%Y') as departure_date_time, (price) as price, admin_markup, rpt.rate_plan_id, rpt.supplierid, rpt.sellerid, rpt.seller_rateplan_id");
		$this->db->from('tickets_tbl');					
		$this->db->join("( 
			(select spl.companyid as supplierid, spd.rate_plan_id, whl.companyid as sellerid, whd.rate_plan_id as seller_rateplan_id 
			from wholesaler_tbl spl 
			inner join wholesaler_services_tbl spd on spl.id=spd.wholesaler_rel_id and spd.active = 1 and spd.allowfeed=1 
			inner join metadata_tbl mtd on mtd.id=spd.serviceid and mtd.active = 1 and mtd.associated_object_type='services' 
			inner join supplier_tbl whl on spl.companyid=whl.supplierid and whl.active=1 
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

	public function get_bookings($companyid=-1, $userid=-1) {
		// (((b.customer_userid=$userid or $userid=-1) and ($companyid=-1 or b.customer_companyid=$companyid)) or  
		$sql = "SELECT 	t.departure_date_time, t.arrival_date_time, b.id,b.booking_date as date, b.booking_confirm_date as process_date,b.pnr,(b.price+b.markup) as rate,b.qty,((b.price+b.markup) * b.qty) as amount,(b.cgst+b.sgst) as igst,b.srvchg as service_charge,
						b.total,t.trip_type,u.user_id,u.name, us.name as seller,us.user_id as seller_id, source.city as source_city,destination.city as destination_city, t.flight_no, t.aircode, t.ticket_no, t.class,
						cc.id as customer_companyid, cc.display_name as customer_companyname, sc.id as seller_companyid, sc.display_name as seller_companyname, t.id as ticket_id,
						case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' end as status, 
						ifnull(b.pbooking_id,0) as parent_booking_id, ifnull(b.message, '') as notes, ifnull(b.rateplanid,0) as rateplanid
				FROM bookings_tbl b 
				INNER JOIN tickets_tbl t ON b.ticket_id = t.id 
				INNER JOIN user_tbl u ON b.customer_userid = u.id
				INNER JOIN user_tbl us ON b.seller_userid = us.id 
				INNER JOIN company_tbl cc ON b.customer_companyid = cc.id 
				INNER JOIN company_tbl sc ON b.seller_companyid = sc.id 
				INNER JOIN city_tbl source ON source.id = t.source 
				INNER JOIN city_tbl destination ON destination.id = t.destination 
				WHERE (t.sale_type!='live') and 				
					((b.seller_userid=$userid or $userid=-1) and ($companyid=-1 or b.seller_companyid=$companyid))
				ORDER BY b.id DESC";
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
			case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' end as status, 
			ifnull(b.pbooking_id,0) as parent_booking_id, ifnull(b.message, '') as notes, ifnull(b.rateplanid,0) as rateplanid");
		$this->db->from("bookings_tbl b");
		$this->db->join("tickets_tbl t", "b.ticket_id = t.id", FALSE);
		$this->db->join("user_tbl u", "b.customer_userid = u.id", FALSE);
		$this->db->join("user_tbl us", "b.seller_userid = us.id", FALSE);
		$this->db->join("company_tbl cc", "b.customer_companyid = cc.id", FALSE);
		$this->db->join("company_tbl sc", "b.seller_companyid = sc.id", FALSE);
		$this->db->join("city_tbl source", "source.id = t.source", FALSE);
		$this->db->join("city_tbl destination", "destination.id = t.destination", FALSE);
		$this->db->where("(t.sale_type!='live')");
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
						case when ba.status=0 then 'Pending' when ba.status=1 then 'Hold' when ba.status=2 then 'Rejected' when ba.status=4 then 'Requesy for Cancel' when ba.status=8 then 'Cancelled' when ba.status=16 then 'Revised' when ba.status=32 then 'Processed' when ba.status=64 then 'Processing' end as status
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

	public function get_booking_customers($bookingid=-1, $companyid=-1, $userid=-1) {
		$sql = "select cus.id, cus.prefix, cus.first_name, cus.last_name, cus.mobile_no, cus.age, cus.email, cus.airline_ticket_no, cus.pnr, cus.ticket_fare, cus.ticket_fare, cus.costprice, cus.booking_id as cus_booking_id, cus.refrence_id, cus.companyid, cus.status, 
				b.id as booking_id, b.booking_date, b.booking_confirm_date, b.pbooking_id, b.ticket_id, b.customer_userid, b.customer_companyid, b.seller_userid, b.seller_companyid, 
				case when b.status=0 then 'PENDING' when b.status=1 then 'HOLD' when b.status=2 then 'APPROVED' when b.status=4 then 'PROCESSING' when b.status=8 then 'REJECTED' when b.status=16 then 'CANCELLED' end as booking_status, 
				b.total, b.costprice
			from customer_information_tbl cus
			inner join bookings_tbl b on (b.id=cus.booking_id or b.id=cus.refrence_id)
			inner join tickets_tbl t on t.id = b.ticket_id
			where (cus.booking_id=$bookingid or $bookingid=-1) and (t.sale_type!='live') and 
				(((b.customer_userid=$userid or $userid=-1) and ($companyid=-1 or b.customer_companyid=$companyid)) or  
				((b.seller_userid=$userid or $userid=-1) and ($companyid=-1 or b.seller_companyid=$companyid)))";
		
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
		$sql = "select t.*
			from tickets_tbl t
			where t.id=$ticketid";
		
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

	public function upsert_booking($booking) {
		if($booking === NULL) return;
		$returnedValue = NULL;
		$tbl = 'bookings_tbl';

		if($booking['id']>0) {
			// this is old booking. so needs to be updated
			$returnedValue = $this->update($tbl, $booking, array('id' => $booking['id']));
		}
		else {
			// this is new booking. so needs to be inserted
			$bookingactivity = null; 
			if(isset($booking['activity']) && count($booking['activity'])>0) {
				$bookingactivity = $booking['activity'][0];
				unset($booking['activity']);
			}

			$customers = null; 
			if(isset($booking['customers']) && count($booking['customers'])>0) {
				$customers = $booking['customers'];
				unset($booking['customers']);
			}

			unset($booking['id']);
			unset($bookingactivity['activity_id']);
			$returnedValue = $this->save($tbl, $booking);
			if($returnedValue!==null) {
				$tbl = 'booking_activity_tbl';
				$bookingactivity['booking_id'] = $returnedValue;

				for ($i=0; $i < count($customers); $i++) { 
					$customer = &$customers[$i];
					$customer['refrence_id'] = $returnedValue;

					$return = $this->update('customer_information_tbl', $customer, array('id' => $customer['id']));
				}
				$returnedValue = $this->save($tbl, $bookingactivity);
			}
		}

		return $returnedValue;
	}
}
?>
