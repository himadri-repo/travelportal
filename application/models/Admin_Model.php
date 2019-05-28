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

	public function search_wholesalers() {
		$this->db->select("c.id, c.code, c.name, c.address, c.display_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type, c.baseurl, usr.email, group_concat(md.datavalue) as services ");
		$this->db->from('company_tbl c');
		$this->db->join('company_services_tbl csrv', 'csrv.companyid=c.id and csrv.active=1 and c.active=1', 'inner');
		$this->db->join('metadata_tbl md', 'md.associated_object_type=\'services\' and csrv.serviceid=md.id and md.active=1', 'inner');
		$this->db->join('user_tbl usr', 'usr.id=c.primary_user_id', 'inner');
		$this->db->where('(c.type & 4)=4');
		$this->db->group_by('c.id, c.code, c.name, c.address, c.display_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type, c.baseurl, usr.email');
		$this->db->order_by('c.display_name');

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

	public function search_suppliers() {
		$this->db->select("c.id, c.code, c.name, c.address, c.display_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type, c.baseurl, usr.email, group_concat(md.datavalue) as services ");
		$this->db->from('company_tbl c');
		$this->db->join('company_services_tbl csrv', 'csrv.companyid=c.id and csrv.active=1 and c.active=1', 'inner');
		$this->db->join('metadata_tbl md', 'md.associated_object_type=\'services\' and csrv.serviceid=md.id and md.active=1', 'inner');
		$this->db->join('user_tbl usr', 'usr.id=c.primary_user_id', 'inner');
		$this->db->where('(c.type & 2)=2');
		$this->db->group_by('c.id, c.code, c.name, c.address, c.display_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type, c.baseurl, usr.email');
		$this->db->order_by('c.display_name');

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

	public function search_communications($inviteeid, $invitorid) {
		$this->db->select("cm.id, cm.title, cm.active, cm.companyid, cm.created_by, cm.created_on, cm.updated_by, cm.updated_on, (select display_name from company_tbl where id=$inviteeid) as invitee, (select display_name from company_tbl where id=$invitorid) as invitor, count(cmd.id) as msgcount ");
		$this->db->from('communication_tbl cm');
		$this->db->join('communication_detail_tbl cmd', 'cm.id=cmd.pid and cm.active=1', 'inner');
		$this->db->where("cmd.active=1 and (from_companyid=$inviteeid or to_companyid=$inviteeid) and (from_companyid=$invitorid or to_companyid=$invitorid)");
		$this->db->group_by("cm.id, cm.title, cm.created_on");
		$this->db->order_by("cm.created_on desc");

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

	public function search_communication_details($communicationid) {
		$this->db->select("cmd.id, cmd.pid, cmd.message, cmd.from_companyid, c1.display_name as fromcompany, cmd.to_companyid, c2.display_name as tocompany, cmd.ref_no, cmd.type, cmd.active, cmd.created_by, cmd.created_on, cmd.updated_by, cmd.updated_on ");
		$this->db->from('communication_detail_tbl cmd');
		$this->db->join('company_tbl c1', 'cmd.from_companyid=c1.id', 'inner');
		$this->db->join('company_tbl c2', 'cmd.to_companyid=c2.id', 'inner');
		$this->db->where("cmd.active=1 and cmd.pid=1");

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

	public function messagesByCompanyid($boxtype, $companyid)
	{
		if($boxtype === 'outbox') {
			// this is for outbox
			$this->db->select("cm.id, cm.title, cm.active, cm.companyid, cmd.from_companyid, c1.display_name as from_company_name, cmd.to_companyid, c2.display_name as to_company_name, cmd.ref_no, cmd.message, cmd.type, cmd.created_on, cmd.created_by, usr.name ");
			$this->db->from('communication_tbl cm');
			$this->db->join('communication_detail_tbl cmd', 'cm.id=cmd.pid and cmd.active=1', 'inner');
			$this->db->join('company_tbl c1', 'c1.id=cmd.from_companyid', 'inner');
			$this->db->join('company_tbl c2', 'c2.id=cmd.to_companyid', 'inner');
			$this->db->join('user_tbl usr', 'cmd.created_by=usr.id', 'inner');
			$this->db->where("cmd.from_companyid=$companyid");
			$this->db->order_by("cmd.created_on desc");
	
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
		else if($boxtype === 'inbox') {
			// this is for inbox
			$this->db->select("cm.id, cm.title, cm.active, cm.companyid, cmd.from_companyid, c1.display_name as from_company_name, cmd.to_companyid, c2.display_name as to_company_name, cmd.ref_no, cmd.message, cmd.type, cmd.created_on, cmd.created_by, usr.name ");
			$this->db->from('communication_tbl cm');
			$this->db->join('communication_detail_tbl cmd', 'cm.id=cmd.pid and cmd.active=1', 'inner');
			$this->db->join('company_tbl c1', 'c1.id=cmd.from_companyid', 'inner');
			$this->db->join('company_tbl c2', 'c2.id=cmd.to_companyid', 'inner');
			$this->db->join('user_tbl usr', 'cmd.created_by=usr.id', 'inner');
			$this->db->where("cmd.to_companyid=$companyid");
			$this->db->order_by("cmd.created_on desc");
	
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

	public function message_add($message) {
        if(!empty($message["id"])){
            $data = $this->db->get_where("communication_tbl", ['id' => $message["id"]])->row_array();
        }else{
            $data = null;
		}

		$result = '';
		if($data==null) {
			//insert
			$message["id"] = null;
			$this->db->insert('communication_tbl', $message);
			$result = array("message" => "Item created successfully.", "id" => $this->db->insert_id());
		}
		else {
			//update
			try
			{
				$result = $this->db->update('communication_tbl', $message, array("id" => intval($message["id"],10)));
				$result = array("message" => "Item updated successfully.", "id" => intval($message["id"],10));
			}
			catch(Exception $ex) {
				throw $ex;
			}
		}

		return [$result];
	}

	public function message_detail_add($messageDetail) {
        if(!empty($messageDetail["id"])){
            $data = $this->db->get_where("communication_detail_tbl", ['id' => $messageDetail["id"]])->row_array();
        }else{
            $data = null;
		}

		$result = '';
		if($data==null) {
			//insert
			$messageDetail["id"] = null;
			$this->db->insert('communication_detail_tbl', $messageDetail);
			$result = array("message" => "Item created successfully.", "id" => $this->db->insert_id());
		}
		else {
			//update
			try
			{
				$result = $this->db->update('communication_detail_tbl', $messageDetail, array("id" => intval($messageDetail["id"],10)));
				$result = array("message" => "Item updated successfully.", "id" => intval($messageDetail["id"],10));
			}
			catch(Exception $ex) {
				throw $ex;
			}
		}

		return [$result];
	}
}	
?>