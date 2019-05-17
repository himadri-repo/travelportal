<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Admin_Model extends CI_Model
{
	public function __construct()
	{ 
		date_default_timezone_set("Asia/Calcutta");   
	}

    	public function get_modules($permision)
	{
        //$arr=array("m.code"=>$id);
		$this->db->select('m.*');
		$this->db->from('modules_tbl as m');
		$this->db->where('m.code & '.$permision);
        	$this->db->order_by("m.category asc, m.display_order asc");
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
	
	public function get_suppliers($companyid) {
		$this->db->select("sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name as primary_user_name, u.mobile as primary_user_mobile, u.email as primary_user_email, group_concat(distinct concat(mt.datavalue,' (Markup=>',sspl.markup_rate,')') order by mt.datavalue) as services");
		$this->db->from('supplier_tbl as spl');
		$this->db->join('supplier_services_tbl as sspl', 'spl.id=sspl.supplier_rel_id and sspl.active=1', 'inner');
		$this->db->join('company_tbl as sp', 'spl.supplierid=sp.id and spl.active=1 and sp.active=1', 'inner');
		$this->db->join('user_tbl as u', 'sp.primary_user_id=u.id and u.active=1', 'inner');
		$this->db->join('metadata_tbl mt', 'mt.id=sspl.serviceid and mt.active=1', 'inner');
		$this->db->where('sp.type & 2 and spl.companyid='.$companyid);
		$this->db->group_by('sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name, u.mobile, u.email');

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

	public function get_wholesalers($companyid) {
		$this->db->select("sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name as primary_user_name, u.mobile as primary_user_mobile, u.email as primary_user_email, group_concat(distinct concat(mt.datavalue,' (Markup=>',sspl.markup_rate,')') order by mt.datavalue) as services");
		$this->db->from('wholesaler_tbl spl');
		$this->db->join('wholesaler_services_tbl sspl', 'spl.id=sspl.wholesaler_rel_id and sspl.active=1', 'inner');
		$this->db->join('company_tbl as sp', 'spl.salerid=sp.id and spl.active=1 and sp.active=1', 'inner');
		$this->db->join('user_tbl as u', 'sp.primary_user_id=u.id and u.active=1', 'inner');
		$this->db->join('metadata_tbl mt', 'mt.id=sspl.serviceid and mt.active=1', 'inner');
		$this->db->where('sp.type & 2 and spl.companyid='.$companyid);
		$this->db->group_by('sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name, u.mobile, u.email');

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

	public function get_customers($companyid) {
		$this->db->select("usr.*, cm.primary_user_id as primary_user, cm.type as company_type ");
		$this->db->from('user_tbl usr');
		$this->db->join('company_tbl cm', 'usr.companyid=cm.id and usr.active=1 and cm.active=1', 'inner');
		$this->db->where('usr.type in (\'B2C\', \'B2B\') and is_admin=0 and usr.companyid='.$companyid);
		$this->db->order_by('name asc');

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

	public function get_company($companyid) {
		$this->db->select("cm.* ");
		$this->db->from('company_tbl cm');
		$this->db->where('cm.id='.$companyid);

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

	public function set_customer($customer) {
        if(!empty($customer["id"])){
            $data = $this->db->get_where("user_tbl", ['id' => $customer["id"]])->row_array();
        }else{
            $data = null;
		}

		$result = '';
		if($data==null) {
			//insert
			$customer["id"] = null;
			$this->db->insert('user_tbl', $customer);
			$result = 'Item created successfully.';
		}
		else {
			//update
			try
			{
				$result = $this->db->update('user_tbl', $customer, array("id" => intval($customer["id"],10)));
				$result = 'Item updated successfully.';
			}
			catch(Exception $ex) {
				throw $ex;
			}
		}

		return [$result];
	}

	public function get_customer($company, $customerid) {
		$this->db->select("usr.* ");
		$this->db->from('user_tbl usr');
		$this->db->where('usr.id='.$customerid.' and usr.companyid='.$company);

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

	public function get_customersByEmailOrMobile($email, $mobile, $companyid, $id) {
		$this->db->select("usr.* ");
		$this->db->from('user_tbl usr');
		$this->db->where('(usr.email=\''.$email.'\' or usr.mobile=\''.$mobile.'\') and usr.companyid='.$companyid.' and usr.id<>'.$id);

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
}	
?>