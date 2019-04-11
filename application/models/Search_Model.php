<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Search_Model extends CI_Model
{
    public function __construct()
	{ 
	    date_default_timezone_set("Asia/Calcutta");   
		
	}
	
	public function search_one_way($arr) 
	{            		  
		$this->db->select('t.id,t.source,t.destination,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.total,t.price,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,t.no_of_person,t.class,a.image,t.user_id,t.no_of_stops, t.data_collected_from, t.aircode, t.flight_no');
		$this->db->from('tickets_tbl t');
		$this->db->join('airline_tbl a', 'a.id = t.airline','left');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->where($arr);
		$this->db->order_by("(price + admin_markup + markup)", "asc"); //for ordering data in the list while searching one way
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
	public function flight_details($id) 
	{    
        
		$arr=array("t.id"=>$id);  	
		$this->db->select('u.id as user_id,t.user_id as uid,t.id,t.source,t.destination,t.source1,t.destination1,t.ticket_no,t.pnr,t.departure_date_time,t.departure_date_time1,t.arrival_date_time,t.arrival_date_time1,t.flight_no,t.flight_no1,t.terminal,t.terminal1,t.terminal2,t.terminal3,t.departure_date_time1,t.arrival_date_time1,t.flight_no1,t.total,t.admin_markup,t.markup,t.sale_type,t.refundable,c.city as source_city,ct.city as destination_city,c1.city as source_city1,ct1.city as destination_city1,t.class,t.class1,t.no_of_person,a.image,t.airline,t.airline1,t.trip_type,t.price,t.baggage,t.meal,t.markup,t.discount,t.availibility,t.aircode,t.aircode1,t.no_of_stops,t.no_of_stops1,t.remarks,t.approved,t.remarks,t.stops_name,t.stops_name1,t.available');
		$this->db->from('tickets_tbl t');
		$this->db->join('airline_tbl a', 'a.id = t.airline','left');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('user_tbl u', 't.user_id = u.id');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->where($arr);
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		
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
     public function setting() 
	{    
        $arr=array("setting_id"=>"2");  	
		$this->db->select('*');
		$this->db->from('setting_tbl');
		
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
	
	public function search_available_date($source,$destination,$trip_type) 
	{		
	    $today=date("Y-m-d");
		
        $arr=array(
		"source"=>$source,
		"destination"=>$destination,
		"trip_type"=>$trip_type,
		"DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>$today,
		"approved"=>1,
		"available"=>"YES");  
		
		$this->db->select("DATE_FORMAT(departure_date_time, '%d-%m-%Y') as departure_date_time, (price + admin_markup + markup) as price");
		$this->db->from('tickets_tbl');					
		$this->db->where($arr);
		$this->db->order_by("(price + admin_markup + markup)", "asc"); //for ordering data in the list while searching one way
		
		$query = $this->db->get();							
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
}	
?>