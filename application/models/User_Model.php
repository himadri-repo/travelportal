<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class User_Model extends CI_Model
{
    public function __construct()
	{ 
	    date_default_timezone_set("Asia/Calcutta");   
		
	}

	public function get($table, $arr) {
		// $arr=array("id"=>$id); 
		$this->db->select("*");
		$this->db->from($table);
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
	
	public function del($tbl,$field,$value) 
	{                      
		    $this->db->where($field, $value);
            if($this->db->delete($tbl))
			{
				return true;
			}				
            else
			{
				echo $this->db->last_query();die();
			    return false;
			}         
	}
	
	public function get_where($table, $where) {
		$query = $this->db->get_where($table, $where);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}				
		else
		{
			echo $this->db->last_query();
			return false;
		}         
	}
	
	
	public function login($data) 
	{      
        $this->db->where($data);
		$query = $this->db->get('user_tbl');
		if ($query->num_rows() > 0) 
		{
			foreach ($query->result_array() as $row) 
			{
			  $data['user_id'] = $row['id'];
			  $data['name'] = $row['name'];
            }			
            return $data;
			
		}
		else
		{
			  return false;
		}
	}

	public function get_userbyid($uid) {
		$this->db->select('u.*, c.code as ccode, c.name as cname, c.display_name as cdisplay_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type as ctype');
		$this->db->from('user_tbl u');
		$this->db->join('company_tbl c', 'u.companyid=c.id', 'inner');
		$this->db->where('u.id=', $uid);
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			$user = $query->result_array()[0];

			return $user;
		}
		else {
			return false;
		}
	}

	public function getUserByUUID($uuid) {
		$this->db->select('u.*, c.code as ccode, c.name as cname, c.display_name as cdisplay_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type as ctype');
		$this->db->from('user_tbl u');
		$this->db->join('company_tbl c', 'u.companyid=c.id', 'inner');
		$this->db->where('u.uid=', $uuid);
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			$user = $query->result_array()[0];

			return $user;
		}
		else {
			return false;
		}
	}
	
	public function newlogin($data) {
		$mobile = $data['mobile'];
		$email = $data['mobile'];
		$pwd = $data['password'];
		$companyid = $data['companyid'];

		$this->db->select('u.*, c.code as ccode, c.name as cname, c.display_name as cdisplay_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, 
			c.type as ctype, wl.id as wallet_id, wl.display_name as wallet_name, wl.sponsoring_companyid, wl.allowed_transactions, wl.balance as wallet_balance, 
			wl.type as wallet_type, wl.status as wallet_status');
		$this->db->from('user_tbl u');
		$this->db->join('company_tbl c', 'u.companyid=c.id', 'inner');
		$this->db->join('system_wallets_tbl wl', 'u.companyid=wl.companyid and u.id=wl.userid', 'left');
		$this->db->where('u.companyid=', $companyid);
		$this->db->where('u.active=', 1);
		$this->db->where('u.mobile=', $mobile);
		$this->db->or_where('u.email=', $email);
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			$data = $query->result_array()[0];
			if(sha1($data['password'])==$pwd || $data['password']==$pwd) {
				unset($data['password']);
				$data['user_id'] = $data['id'];

				return $data;
			}
			else {
				return false;
			}
			
			// foreach ($query->result_array() as $row) 
			// {
			//   $data['user_id'] = $row['id'];
			//   $data['name'] = $row['name'];
            // }			
            //return $data;
			
		}
		else
		{
			  return false;
		}
	}

	public function user_details() 
	{    
		$userid = $this->session->userdata('user_id');
		$sql = "select 	u.id, u.user_id, u.name, u.profile_image, u.email, u.mobile, u.address, u.state, u.country, u.password, u.is_supplier, 
						u.is_customer, u.active, u.type, u.credit_ac, u.doj, u.companyid, u.created_by, u.created_on, u.updated_by, u.updated_on, u.permission, u.is_admin, u.uid, u.pan, u.gst, u.rateplanid,
        				(select count(t.id) from tickets_tbl t where t.user_id=$userid) as total_ticket, 
        				(select count(seller.id) from bookings_tbl seller where seller.seller_userid=$userid) as sold,
        				(select count(customer.id) from bookings_tbl customer where customer.customer_userid=$userid) as purchased
				FROM user_tbl as u
				WHERE u.id = $userid";

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
	
	public function user_settings($userid = -1, $fields = []) {
		if($userid < 0) {
			$userid = $this->session->userdata('user_id');
		}

		$this->db->select('ucnf.id, ucnf.user_id, ucnf.field_name, ucnf.field_display_name, ucnf.field_value, ucnf.field_value_type, ucnf.status, ucnf.companyid, ucnf.dependent_field_id, usr.name, usr.email, usr.mobile, usr.address, usr.type, usr.credit_ac');
		$this->db->from('user_config_tbl ucnf');
		$this->db->join('user_tbl usr', 'ucnf.user_id = usr.id and ucnf.status=1', 'inner');
		$this->db->where('ucnf.user_id=', $userid);
		if($fields!==NULL && count($fields)>0) {
			$this->db->where_in('ucnf.field_name', $fields);
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) 
		{
			return $query->result_array()[0];
		}
		else
		{
			return false;
		}
	}


	public function filter_city($trip_type) 
	{       
        $today=date("Y-m-d");
		$arr=array("t.trip_type"=>$trip_type,"DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')>="=>$today,"t.approved"=>1,"available"=>"YES");
		$this->db->select('DISTINCT(c.city),c.id');
		$this->db->from('tickets_tbl as t');
		$this->db->join('city_tbl as c', 't.source=c.id');
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
	
	public function wallet() 
	{            
		$arr=array("userid"=>$this->session->userdata('user_id'), 'type' => 2);
		$this->db->select('balance as wallet');
		$this->db->from('system_wallets_tbl');		
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
    public function count_testi() 
	{            
		$arr=array("user_id"=>$this->session->userdata('user_id'));  	
		$this->db->select('count(id) as no');
		$this->db->from('testimonials_tbl');		
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
	public function testimonials() 
	{            
		$arr=array("user_id"=>$this->session->userdata('user_id'));  	
		$this->db->select('*');
		$this->db->from('testimonials_tbl');		
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
	public function ticket_added() 
	{            
		$arr=array("user_id"=>$this->session->userdata('user_id'));  	
		$this->db->select('count(id) as no');
		$this->db->from('tickets_tbl');		
		$this->db->where($arr);		
		$this->db->order_by("id", "DESC");
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
	
	public function my_booking() 
	{            
		$arr=array("customer_userid"=>$this->session->userdata('user_id'));  	
		$this->db->select('count(id) as no');
		$this->db->from('bookings_tbl');		
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
	
	
	/*public function ticket_sold() 
	{           
		$arr=array("seller_id"=>$this->session->userdata('user_id'),"customer_id<>"=>$this->session->userdata('user_id'));    	
		$this->db->select('count(id) as no');
		$this->db->from('booking_tbl');		
		$this->db->where($arr);		
		$this->db->get();					
		$query1 = $this->db->last_query();
		
		$arr=array("seller_id"=>$this->session->userdata('user_id'),"customer_id<>"=>$this->session->userdata('user_id'));    	
		$this->db->select('count(id) as no');
		$this->db->from('refrence_booking_tbl');		
		$this->db->where($arr);		
		$this->db->get();
		$query2 = $this->db->last_query();
		
		$query = $this->db->query($query1." UNION ".$query2);
		
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	         
    }*/
	public function ticket_sold() 
	{           
		$arr=array("seller_id"=>$this->session->userdata('user_id'),"customer_id<>"=>$this->session->userdata('user_id'));    	
		$this->db->select('count(id) as no');
		$this->db->from('refrence_booking_tbl');		
		$this->db->where($arr);		
		$query=$this->db->get();					
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	         
    }
	public function cancels() 
	{           
		$arr=array("seller_id"=>$this->session->userdata('user_id'),"customer_cancel_request"=>1);    	
		$this->db->select('count(id) as no');
		$this->db->from('booking_tbl');		
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

	public function my_booking_order() 
	{   
		$current_user = $this->session->userdata('current_user');
		
		if($current_user['is_admin']=='1' && ($current_user['type']=='B2B' || $current_user['type']=='EMP')) {
			$arr=array("customer_companyid"=> $current_user['companyid'], 'pbooking_id' => NULL); 
		}
		else {
			$arr=array("customer_userid"=>$this->session->userdata('user_id'), 'pbooking_id' => NULL); 
		}

		// $this->db->select('b.pnr,b.status,u.user_id,u.name,b.id,b.date,b.qty,b.rate,b.amount,b.total,c.city as source_city,ct.city as destination_city,t.trip_type,b.customer_id, 
		// 		b.seller_id,b.customer_cancel_request,b.supplier_cancel_request,b.reason_for_cancellation,b.cancel_request_date,b.cancel_date,b.booking_confirm_date');
		// $this->db->from('booking_tbl as b');	
		// $this->db->join('tickets_tbl as t', 't.id = b.ticket_id');		
	    // $this->db->join('city_tbl c', 'c.id = t.source');
		// $this->db->join('city_tbl ct', 'ct.id = t.destination');
		// $this->db->join('user_tbl u', 'u.id = b.customer_id');
		// $this->db->where($arr);	
		// $this->db->order_by("b.id", "DESC");


		$this->db->select('b.pnr,b.status,u.user_id,u.name,b.id,b.booking_date as date,b.qty, (b.total/b.qty) as rate, b.price, b.total,c.city as source_city,ct.city as destination_city,t.trip_type,b.customer_userid as customer_id,   
				b.seller_userid as seller_id, 0 as customer_cancel_request, 0 as supplier_cancel_request, "" as reason_for_cancellation,   
				"1990-01-01 00:00:00" as cancel_request_date, "1990-01-01 00:00:00" as cancel_date, b.booking_confirm_date');
		$this->db->from('bookings_tbl as b');
		$this->db->join('tickets_tbl as t', 't.id = b.ticket_id');		
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('user_tbl u', 'u.id = b.customer_userid');
		$this->db->where($arr);	
		$this->db->order_by("b.id", "DESC");
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
	
	/*public function booking_orders() 
	{
        		
		$arr=array("b.seller_id"=>$this->session->userdata('user_id'),"b.customer_id<>"=>$this->session->userdata('user_id'));  	
		$this->db->select('b.booking_id,b.pnr,b.status,b.seller_status,u.user_id,u.name,b.id,b.date,b.qty,b.rate,b.amount,b.total,c.city as source_city,ct.city as destination_city,t.trip_type,b.customer_id,b.seller_id,b.customer_cancel_request,b.supplier_cancel_request,b.reason_for_cancellation,b.cancel_request_date,b.cancel_date,b.booking_confirm_date');
		$this->db->from('booking_tbl as b');	
		$this->db->join('tickets_tbl as t', 't.id = b.ticket_id');		
	    $this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('user_tbl as u', 'u.id = b.seller_id');
		$this->db->where($arr);	
							
		$this->db->get();	
		$query1 = $this->db->last_query();
		
		$arr=array("b.seller_id"=>$this->session->userdata('user_id'),"b.customer_id<>"=>$this->session->userdata('user_id'));  	
		$this->db->select('b.booking_id,b.pnr,b.status,b.seller_status,u.user_id,u.name,b.id,b.date,b.qty,b.rate,b.amount,b.total,c.city as source_city,ct.city as destination_city,t.trip_type,b.customer_id,b.seller_id,b.customer_cancel_request,b.supplier_cancel_request,b.reason_for_cancellation,b.cancel_request_date,b.cancel_date,b.booking_confirm_date');
		$this->db->from('refrence_booking_tbl as b');	
		$this->db->join('tickets_tbl as t', 't.id = b.ticket_id');		
	    $this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('user_tbl as u', 'u.id = b.seller_id');
		$this->db->where($arr);	
							
		$this->db->get();	
		$query2 = $this->db->last_query();
		
		$query = $this->db->query($query1." UNION ".$query2);
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	         
    }*/
	
	public function booking_orders() 
	{
        		
		$arr=array("b.seller_id"=>$this->session->userdata('user_id'),"b.customer_id<>"=>$this->session->userdata('user_id'));  	
		$this->db->select('b.id,b.pnr,b.status,b.seller_status,u.user_id,u.name,b.id,b.date,b.qty,b.rate,b.amount,b.total,c.city as source_city,ct.city as destination_city,t.trip_type,b.customer_id,b.seller_id,b.customer_cancel_request,b.supplier_cancel_request,b.reason_for_cancellation,b.cancel_request_date,b.cancel_date,b.booking_confirm_date,b.booking_id,t.price,t.markup');
		$this->db->from('refrence_booking_tbl as b');	
		$this->db->join('tickets_tbl as t', 't.id = b.ticket_id');		
	    $this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('user_tbl as u', 'u.id = b.seller_id');
		$this->db->where($arr);	
		$this->db->order_by("b.id", "DESC");			
		$query=$this->db->get();	
		
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
        		
		$arr=array("b.seller_id"=>$this->session->userdata('user_id'),"b.ticket_id"=>$id);  	
		$this->db->select('b.id,b.pnr,b.status,b.seller_status,u.user_id,u.name,b.id,b.date,b.qty,b.rate,b.amount,b.total,c.city as source_city,ct.city as destination_city,t.trip_type,b.customer_id,b.seller_id,b.customer_cancel_request,b.supplier_cancel_request,b.reason_for_cancellation,b.cancel_request_date,b.cancel_date,b.booking_confirm_date,b.booking_id,t.sale_type');
		$this->db->from('refrence_booking_tbl as b');	
		$this->db->join('tickets_tbl as t', 't.id = b.ticket_id');		
	    $this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('user_tbl as u', 'u.id = b.seller_id');
		$this->db->where($arr);	
		$this->db->order_by("b.id", "DESC");			
		$query=$this->db->get();	
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	         
    }
	public function cancel_request() 
	{           
		$arr=array("b.seller_id"=>$this->session->userdata('user_id'),"b.customer_cancel_request"=>1);    	
		$this->db->select('b.pnr,b.status,u.user_id,u.name,b.id,b.date,b.qty,b.rate,b.amount,b.total,c.city as source_city,ct.city as destination_city,t.trip_type,b.customer_id,b.seller_id,b.supplier_cancel_request,b.reason_for_cancellation,b.cancel_request_date,b.cancel_date');
		$this->db->from('booking_tbl as b');	
		$this->db->join('tickets_tbl as t', 't.id = b.ticket_id');		
	    $this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('user_tbl as u', 'u.id = b.seller_id');
		$this->db->where($arr);	
		$this->db->order_by("b.id", "DESC");
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
	public function expired() 
	{    
        
		$arr=array("user_id"=>$this->session->userdata('user_id'),"DATE_FORMAT(departure_date_time, '%Y-%m-%d h:i:s')<"=>date("Y-m-d h:i:s"));  	
		$this->db->select('count(id) as no');
		$this->db->from('tickets_tbl');	
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
	public function available() 
	{            
		$arr=array("user_id"=>$this->session->userdata('user_id'),"DATE_FORMAT(departure_date_time, '%Y-%m-%d h:i:s')>="=>date("Y-m-d h:i:s"));  	
		$this->db->select('count(id) as no');
		$this->db->from('tickets_tbl');	
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
	public function select($tbl) 
	{    
       
		$query = $this->db->get($tbl);
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();
			
		}
		else
		{
			  return false;
		}
         	
    }
	
	public function checknum($tbl,$arr) 
	{    
        $this->db->where($arr);	
		$query = $this->db->get($tbl);						
        return $query->num_rows();		        
    }
	
	public function total_ticket($data) {
		$arr=array("user_id"=>$this->session->userdata('user_id'));
		if($data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="]!="1970-01-01")
		{
			$value=$data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="];
			$arr["DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')>="]=$value;
			//$this->db->where($arr);
		}
		if($data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="]!="1970-01-01")
		{
			 $value=$data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="];
			 $arr["DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')<="]=$value;
		}
        if(!empty($data["source"]))
		{
			$arr["t.source"]=$data["source"];
		}
		if(!empty($data["destination"]))
		{
			$arr["t.destination"]=$data["destination"];
		}
		
		if(!empty($data["pnr"]))
		{
			$arr["t.pnr"]=$data["pnr"];
		}			

		$query = $this->db->where($arr)->get('tickets_tbl t');
		$total_count = $query->num_rows();
		// $total_count = $this->db->count_all_results('tickets_tbl', $arr);

		return $total_count;
	}

	public function ticket($pageindex=0, $pagesize=0) 
	{    
		$arr=array("t.user_id"=>$this->session->userdata('user_id'));
		
		$this->db->select('t.id,t.trip_type,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.departure_date_time1,t.arrival_date_time1,t.total,t.sale_type,t.refundable,t.created_date,c.city as source,ct.city as destination,c1.city as source1,ct1.city as destination1,t.class,t.no_of_person,t.max_no_of_person,a.image,t.approved,t.price,t.markup,t.available');
		$this->db->from('tickets_tbl t');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('airline_tbl a', 'a.id = t.airline','left');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->where($arr);
		$this->db->order_by("t.id","desc");
		if($pageindex>0 && $pagesize>0) {
			$this->db->limit($pagesize, $pageindex-1);
		}
		$query = $this->db->get();
		$sql = $this->db->last_query();

		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
		}         	
    }
	
	public function search_ticket($data) 
	{    
        $arr=array("t.user_id"=>$this->session->userdata('user_id'));  	
		$this->db->select('t.id,t.trip_type,t.ticket_no,t.pnr,t.departure_date_time,t.arrival_date_time,t.departure_date_time1,t.arrival_date_time1,t.total,t.sale_type,t.refundable,t.created_date,c.city as source,ct.city as destination,c1.city as source1,ct1.city as destination1,t.class,t.no_of_person,t.max_no_of_person,a.image,t.approved,t.price,t.markup,t.available');
		$this->db->from('tickets_tbl as t');
		

		$this->db->join('airline_tbl a', 'a.id = t.airline');
		$this->db->join('city_tbl c', 'c.id = t.source');
		$this->db->join('city_tbl ct', 'ct.id = t.destination');
		$this->db->join('city_tbl c1', 'c1.id = t.source1','left');
		$this->db->join('city_tbl ct1', 'ct1.id = t.destination1','left');
		$this->db->where($arr);
		
		
		if($data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="]!="1970-01-01")
		{
			$value=$data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="];
			$arr=array("DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')>="=>$value);
			$this->db->where($arr);
		}
		if($data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="]!="1970-01-01")
		{
			 $value=$data["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="];
			 $arr=array("DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')<="=>$value);
		     $this->db->where($arr);
		}
        if(!empty($data["source"]))
		{
			 $arr=array("t.source"=>$data["source"]);
			 $this->db->where($arr);
		}
		if(!empty($data["destination"]))
		{
			$arr=array("t.destination"=>$data["destination"]);
			$this->db->where($arr);
		}
		
		if(!empty($data["pnr"]))
		{
			$arr=array("t.pnr"=>$data["pnr"]);
			$this->db->where($arr);
		}			
						
		$this->db->order_by("t.id","desc");
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
	
	public function update($data,$id) 
	{    
        $arr=array("id"=>$id);	
        $this->db->where($arr);		
		if ($this->db->update('user_tbl',$data)) 
		{					
            return true;
		}
		else
		{
			return false;
		}
    }

	public function update_table_data($table, $where, $data)
	{    
        //$arr=array("id"=>$id);	
        $this->db->where($where);
		if ($this->db->update($table,$data)) 
		{					
            return true;
		}
		else
		{
			return false;
		}
    }
	public function update_table($tbl,$data,$field,$value) 
	{    
        $arr=array($field=>$value);	
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
	public function wallet_transaction() 
	{    
        $arr=array("w.user_id"=>$this->session->userdata('user_id'));  	
		$this->db->select('w.date,w.amount,w.booking_id,w.type,w.narration,b.id as booking_no,t.ticket_no,b.pnr,c.city as source,ct.city as destination,u.name,b.status');
		$this->db->from('wallet_tbl as w');
		$this->db->join('booking_tbl as b', 'w.booking_id = b.id','left');
		$this->db->join('tickets_tbl as t', 't.id = b.ticket_id','left');
		$this->db->join('city_tbl as c', 'c.id = t.source','left');
		$this->db->join('city_tbl as ct', 'ct.id = t.destination','left');		
		$this->db->join('user_tbl as u', 'u.id = b.customer_id','left');
        $this->db->where($arr);
		$this->db->order_by("w.id", "DESC");
		$query = $this->db->get();	
		
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			  return false;
			  echo $this->db->last_query();die();
		}
	}
	
	public function get_users($companyid) {
		$arr = array('(u.type in (\'EMP\') or u.is_admin=1) and u.companyid='=>$companyid);

		$this->db->select('u.id,u.user_id,u.name, u.profile_image, u.email, u.mobile, u.password, u.is_supplier, u.is_customer, u.active, u.type, u.credit_ac, u.doj, u.companyid, u.permission, u.is_admin, u.uid');
		$this->db->from('user_tbl as u');
        $this->db->where($arr);
		$this->db->order_by("u.name", "ASC");
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			return false;
			echo $this->db->last_query();
			die();
		}
	}

	public function get_wallet_summary($walletid) {
		$arr = array('wl.id'=>$walletid);

		$this->db->select("wl.id as wallet_id, wl.display_name as wallet_name, wl.companyid, wl.userid, wl.sponsoring_companyid, wl.balance as wallet_balance, wl.type as wallet_type, wl.status as wallet_status, 
				(select count(*) from wallet_transaction_tbl wlt where wlt.wallet_id=wl.id) as no_of_transactions, 
				(select count(*) from wallet_transaction_tbl wlt where wlt.wallet_id=wl.id and wlt.status=0) as no_of_unapproved_transactions, 
				(select sum(case when wlt.dr_cr_type='CR' then wlt.amount else -wlt.amount end) from wallet_transaction_tbl wlt where wlt.wallet_id=wl.id and wlt.status=0) as unapproved_amount");
		$this->db->from('system_wallets_tbl wl');
        $this->db->where($arr);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			return false;
			echo $this->db->last_query();
			die();
		}
	}	

	public function get_wallet_transactions($payload) {

		$qry = [];

		$this->db->select("wlt.*, wl.name, wl.display_name wallet_name, wl.allowed_transactions, wl.wallet_account_code, wl.balance, wl.type as wallet_type, wl.status as wallet_status, c.display_name as companyname, 
			usr.name as username, usr.email, usr.type ");
		$this->db->from("wallet_transaction_tbl wlt ", FALSE);
		$this->db->join("system_wallets_tbl wl ", "wl.id=wlt.wallet_id", NULL, FALSE);
		$this->db->join("company_tbl c ", "wl.companyid=c.id",NULL, FALSE);
		$this->db->join("user_tbl usr ", "usr.id=wlt.userid","left", FALSE);
		
		// allTransactions
		if(isset($payload['allTransactions'])) {
			if($payload['allTransactions'] === false) {
				$this->db->where(array('trans_ref_type' => 'PAYMENT'), FALSE);
			}
		}

		if(isset($payload['filter'])) {
			$qry = array('wlt.status' => $payload['filter']['status'], 'wlt.target_companyid' => $payload['filter']['target_companyid']);
			$this->db->where($qry, FALSE);
		}
		$pagesize = isset($payload['pagesize']) ? intval($payload['pagesize']) : -1;
		$pageindex = isset($payload['pageindex']) ? intval($payload['pageindex']) : -1;
		$start = intval(($pageindex - 1) * $pagesize);

		if($pagesize>-1)
			$this->db->limit($pagesize, $start);

		$query = $this->db->get();
		
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
			return false;
			echo $this->db->last_query();
			die();
		}
	}

	public function get_account_balance($companyid, $userid) {
		$account_balance = 0;
		$arr = array('act.transacting_companyid'=>$companyid, 'act.transacting_userid'=>$userid);

		$this->db->select("ifnull(sum(credit-debit), 0) balance");
		$this->db->from('account_transactions_tbl act');
        $this->db->where($arr);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) 
		{	
			$result = $query->result_array();
			$account_balance = intval($result[0]['balance']);
            return $account_balance;
		}
		else
		{
			return $account_balance;
			// echo $this->db->last_query();
			// die();
		}
	}
	
	public function settle_wallet_transaction($payload) {
		$id = $payload['id'];
		$status = $payload['status'];
		
		if($id != '' && $status != '') {
			$wallet_trans = $this->get('wallet_transaction_tbl', array('id' => $id));
			if($wallet_trans && count($wallet_trans)>0) {
				$wallet_trans = $wallet_trans[0];
			}

			$amount_balance = $this->get_account_balance($wallet_trans['companyid'], $wallet_trans['userid']);
			$wallet = $this->get('system_wallets_tbl', array('id' => $wallet_trans['wallet_id']));

			if($wallet && $wallet_trans) {
				$wallet = $wallet[0];
				$trans_amount = floatval($wallet_trans['amount']);
				$balance_before = floatval($wallet['balance']);
				$balance = intval($wallet['balance']) + intval($wallet_trans['amount']);

				$flag = $this->update_table_data('wallet_transaction_tbl', array('id' => $id), array('status' => $status, 'approved_on' => date("Y-m-d h:i:s")));

				if($trans_amount>0 && $flag) {
					$companyid = $wallet_trans['companyid'];
					$userid = $wallet_trans['userid'];
					$setting=$this->get('attributes_tbl', array('target_object_id' => 'company', 'target_object_id' => 1, 'active' => 1, 'companyid' => 1, 'code' => 'configuration'));
					if($setting && count($setting)>0) {
						$setting = json_decode($setting[0]['datavalue'], true);
						if(isset($setting['account_settings'])) {
							$setting = $setting['account_settings'];
							for ($i=0; $i<count($setting) ; $i++) { 
								if($setting[$i]['module']=='ticket_sale') {
									$setting = $setting[$i];
									break;
								}
							}
						}
						else {
							$setting = null;
						}
					}
					else {
						$setting = null;
					}

					// $cost_amount = (0 - $amount_balance);

					// if($trans_amount<$cost_amount) {
					// 	$cost_amount = $trans_amount;
					// }
					//save data to account_transactions_tbl;
					//If wallet balance is there and amount realized from wallet then add that to accounts
					if($amount_balance<0) {
						$arr=array(
							"voucher_no" => $this->Search_Model->get_next_voucherno($company),
							"transacting_companyid" => $companyid,
							"transacting_userid" => $userid,
							"documentid" => $id,
							"document_date" => $wallet_trans['date'],
							"document_type" => 2, /* Payment receive */
							"credit" => $trans_amount,
							"companyid" => $companyid,
							"debited_accountid" => (($setting==null || !isset($setting['accountid']))? -1: $setting['accountid']),
							"created_by" => $userid
						);
						$voucher_no = $this->Search_Model->save("account_transactions_tbl",$arr);
					}
				}

				if($flag) {
					return $this->update_table_data('system_wallets_tbl', array('id' => $wallet_trans['wallet_id']), array('balance' => $balance));
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	public function booking_status($status) {
		$status = intval($status);
		$statusValue = 'PENDING';

		switch ($status) {
			case 0:
				$statusValue = 'PENDING';
				break;
			case 1:
				$statusValue = 'HOLD';
				break;
			case 2:
				$statusValue = 'APPROVED';
				break;
			case 4:
				$statusValue = 'PROCESSING';
				break;
			case 8:
				$statusValue = 'REJECTED';
				break;
			case 16:
				$statusValue = 'CANCELLED';
				break;
			default:
				$statusValue = 'PENDING';
				break;
		}

		return $statusValue;
	}
}	
?>