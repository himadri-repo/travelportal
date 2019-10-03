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
		// $this->db->select("sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name as primary_user_name, u.mobile as primary_user_mobile, u.email as primary_user_email, group_concat(distinct concat(mt.datavalue,' (Markup=>',sspl.markup_rate,')') order by mt.datavalue) as services");
		// $this->db->from('supplier_tbl as spl');
		// $this->db->join('supplier_services_tbl as sspl', 'spl.id=sspl.supplier_rel_id and sspl.active=1', 'inner');
		// $this->db->join('company_tbl as sp', 'spl.supplierid=sp.id and spl.active=1 and sp.active=1', 'inner');
		// $this->db->join('user_tbl as u', 'sp.primary_user_id=u.id and u.active=1', 'inner');
		// $this->db->join('metadata_tbl mt', 'mt.id=sspl.serviceid and mt.active=1', 'inner');
		// $this->db->where('sp.type & 2 and spl.companyid='.$companyid);
		// $this->db->group_by('sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name, u.mobile, u.email');

		$this->db->select("sspl.id as relationid, sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name as primary_user_name, u.mobile as primary_user_mobile, u.email as primary_user_email, sspl.allowfeed, rp.id as rateplanid, rp.display_name as rateplan_name, sspl.transaction_type, group_concat(distinct concat(mt.datavalue) order by mt.datavalue) as services, group_concat(distinct concat(rpd.head_name, '-', rpd.amount, if(rpd.amount_type=1, '', '%'), ' ') order by rpd.head_name) as rateplandetails ", FALSE);
		$this->db->from('supplier_tbl as spl');
		$this->db->join('supplier_services_tbl as sspl', 'spl.id=sspl.supplier_rel_id and sspl.active=1', 'inner', FALSE);
		$this->db->join('company_tbl as sp', 'spl.supplierid=sp.id and spl.active=1 and sp.active=1', 'inner', FALSE);
		$this->db->join('user_tbl as u', 'sp.primary_user_id=u.id and u.active=1', 'inner', FALSE);
		$this->db->join('metadata_tbl mt', 'mt.id=sspl.serviceid and mt.active=1', 'inner', FALSE);
		$this->db->join('rateplan_tbl rp', 'sspl.rate_plan_id=rp.id and rp.active=1', 'left', FALSE);
		$this->db->join('rateplan_detail_tbl rpd', 'rpd.rateplanid=rp.id and rpd.active=1', 'left', FALSE);
		$this->db->where("sp.type & 2 and spl.companyid=$companyid", NULL, FALSE);
		$this->db->group_by('sspl.id, sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name, u.mobile, u.email, rp.id, rp.display_name, sspl.transaction_type', FALSE);
		
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

	public function get_wholesalers($companyid) {
		// $this->db->select("sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name as primary_user_name, u.mobile as primary_user_mobile, u.email as primary_user_email, group_concat(distinct concat(mt.datavalue,' (Markup=>',sspl.markup_rate,')') order by mt.datavalue) as services");
		// $this->db->from('wholesaler_tbl spl');
		// $this->db->join('wholesaler_services_tbl sspl', 'spl.id=sspl.wholesaler_rel_id and sspl.active=1', 'inner');
		// $this->db->join('company_tbl as sp', 'spl.salerid=sp.id and spl.active=1 and sp.active=1', 'inner');
		// $this->db->join('user_tbl as u', 'sp.primary_user_id=u.id and u.active=1', 'inner');
		// $this->db->join('metadata_tbl mt', 'mt.id=sspl.serviceid and mt.active=1', 'inner');
		// $this->db->where('sp.type & 4 and spl.companyid='.$companyid);
		// $this->db->group_by('sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name, u.mobile, u.email');

		$this->db->select("sspl.id as relationid, sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name as primary_user_name, u.mobile as primary_user_mobile, u.email as primary_user_email, sspl.allowfeed, rp.id as rateplanid, rp.display_name as rateplan_name, sspl.transaction_type, group_concat(distinct concat(mt.datavalue) order by mt.datavalue) as services, group_concat(distinct concat(rpd.head_name, '-', rpd.amount, if(rpd.amount_type=1, '', '%'), ' ') order by rpd.head_name) as rateplandetails ");
		$this->db->from('wholesaler_tbl spl');
		$this->db->join('wholesaler_services_tbl sspl', 'spl.id=sspl.wholesaler_rel_id and sspl.active=1', 'inner');
		$this->db->join('company_tbl sp', 'spl.salerid=sp.id and spl.active=1 and sp.active=1', 'inner');
		$this->db->join('user_tbl u', 'sp.primary_user_id=u.id and u.active=1', 'inner');
		$this->db->join('metadata_tbl mt', 'mt.id=sspl.serviceid and mt.active=1', 'inner');
		$this->db->join('rateplan_tbl rp', 'sspl.rate_plan_id=rp.id and rp.active=1', 'left');
		$this->db->join('rateplan_detail_tbl rpd', 'rpd.rateplanid=rp.id and rpd.active=1', 'left');
		$this->db->where("sp.type & 4 and spl.companyid = $companyid");
		$this->db->group_by('sspl.id, sp.id, sp.name, sp.display_name, sp.tenent_code, sp.primary_user_id, sp.type, sp.baseurl, u.name, u.mobile, u.email, rp.id, rp.display_name, sspl.transaction_type');

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

	public function get_customers($companyid) {
		$this->db->select("swl.balance, swl.allowed_transactions, 
		ifnull((select sum(credit-debit) as balance from account_transactions_tbl acc where acc.transacting_userid=usr.id and acc.transacting_companyid=usr.companyid), 0) as accounts_balance, 
		usr.*, cm.primary_user_id as primary_user, cm.type as company_type, rp.display_name as rateplan_name, rp.assigned_to, rp.default ", FALSE);
		$this->db->from('user_tbl usr');
		$this->db->join('system_wallets_tbl swl', 'swl.userid=usr.id and swl.type=2', 'inner', FALSE);
		$this->db->join('company_tbl cm', 'usr.companyid=cm.id and cm.active=1', 'inner', FALSE);
		$this->db->join('rateplan_tbl rp', 'usr.rateplanid=rp.id and rp.active=1', 'left', FALSE);
		$this->db->where('usr.type in (\'B2C\', \'B2B\') and is_admin=0 and usr.companyid='.$companyid, NULL, FALSE);
		$this->db->order_by('usr.name asc');

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

	public function get_companies() {
		$this->db->select("cm.* ");
		$this->db->from('company_tbl cm');
		$this->db->where('cm.active=1');

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

		if($customer['rateplanid']=='-1') {
			unset($customer['rateplanid']);
		}

		$this->db->where('id', $customer['companyid']);
		$query = $this->db->get('company_tbl');
		$company = NULL;
		$wallet_code = '';
		$companyabcode = '';
		
		if($query->num_rows() > 0) {
			$company = $query->result_array();
			if($company && count($company)>0) {
				$company = $company[0];
			}
			$wallet_code = $this->abbreviate($customer['name']);
			$companyabcode = $this->abbreviate($company['display_name']);
		}

		$result = '';
		if($company === NULL) {
			$result = 'Invalid company id';
			return [$result];
		}

		if($data==null && $customer['name']!='' && $customer['email']!='' && $customer['mobile']!='' && $customer['type']!='') {
			$trans_started = false;
			try
			{
				// $this->db->where('id', $customer['companyid']);
				// $query = $this->db->get('company_tbl');

				if($query->num_rows() > 0) {
					$company = $query->result_array();
					$wallet_code = $this->abbreviate($customer['name']);
					$companyabcode = $this->abbreviate($company['display_name']);
					$this->db->trans_begin();
					$trans_started = true;
					//insert
					$customer["id"] = null;
					$this->db->insert('user_tbl', $customer);
					$result = 'Item created successfully.';
					$insert_id = $this->db->insert_id();

					// Sponsored Wallet
					$customer_wallet = array('name' => $company['code'].'_wallet_'.$insert_id, 'display_name' => 'Wallet by '.$company['display_name'], 
						'companyid' => $customer['companyid'], 'userid' => $insert_id, 'sponsoring_companyid' => $customer['companyid'], 'wallet_account_code' => 'WL_'.$companyabcode.'_'.$wallet_code.'_'.$insert_id, 
						'balance' => 0, 'type' => 2, 'created_by' => $insert_id);
					$this->db->insert('system_wallets_tbl', $customer_wallet);

					// System sponsored Wallet
					// Disable System Wallet for Travel Agent and Customer
					// $customer_wallet = array('name' => $company['code'].'_wallet_sys_'.$insert_id, 'display_name' => 'Wallet by System', 
					// 	'companyid' => $customer['companyid'], 'userid' => $insert_id, 'sponsoring_companyid' => -1, 'WL_'.$companyabcode.'_'.$insert_id, 
					// 	'balance' => 0, 'type' => 1, 'created_by' => $insert_id);
					// $this->db->insert('system_wallets_tbl', $customer_wallet);

					if($customer['type'] === 'B2B') {
						//This is a travel agent type customer. So lets add default markup value
						//Default markup value
						$user_config = array('user_id' => $insert_id, 'field_name' => 'markup', 'field_display_name' => 'Markup', 'field_value' => 200, 'field_value_type' => 2, 'status' => 1, 'companyid' => $customer['companyid']);
						$this->db->insert('user_config_tbl', $user_config);
					}
				}
			}
			catch(Exception $ex) {

			}

			if ($trans_started) {
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}
				else
				{
					$this->db->trans_commit();
				}
			}
		}
		else {
			//update
			try
			{
				$result = $this->db->update('user_tbl', $customer, array("id" => intval($customer["id"],10)));
				$result = 'Item updated successfully.';

				// Sponsored Wallet
				$wallet_data = $this->db->get_where("system_wallets_tbl", ['userid' => $customer["id"], 'type' => 2])->row_array();
				if($wallet_data==null) {
					//wallet was not created as this user registered himself via registration flow

					$customer_wallet = array('name' => $company['code'].'_wallet_'.$customer["id"], 'display_name' => 'Wallet by '.$company['display_name'], 
					'companyid' => $customer['companyid'], 'userid' => $customer["id"], 'sponsoring_companyid' => $customer['companyid'], 'wallet_account_code' => 'WL_'.$companyabcode.'_'.$wallet_code.'_'.$customer["id"], 
					'balance' => 0, 'type' => 2, 'created_by' => $customer["id"]);
					$this->db->insert('system_wallets_tbl', $customer_wallet);
				}

				// User config
				$userconfig_data = $this->db->get_where("user_config_tbl", ['user_id' => $customer["id"], 'companyid' => $customer["companyid"]])->row_array();
				if($userconfig_data == null && $customer['type'] === 'B2B') {
					//This is a travel agent type customer. So lets add default markup value
					//Default markup value
					$user_config = array('user_id' => $customer["id"], 'field_name' => 'markup', 'field_display_name' => 'Markup', 'field_value' => 200, 'field_value_type' => 2, 'status' => 1, 'companyid' => $customer['companyid']);
					$this->db->insert('user_config_tbl', $user_config);
				} else if($userconfig_data != null) {
					$defaultB2BMarkup = floatval($userconfig_data['field_value']);
					$defaultB2BMarkup = $defaultB2BMarkup>0?$defaultB2BMarkup:200;
					$markupvalue = ($customer['type'] === 'B2B') ? $defaultB2BMarkup : 0;
					
					$user_config = array('field_value' => $markupvalue);
					$this->db->update('user_config_tbl', $user_config, array('id' => $userconfig_data['id']));
				}
			}
			catch(Exception $ex) {
				throw $ex;
			}
		}

		return [$result];
	}

	public function get_customer($company, $customerid) {
		$this->db->select("usr.*, rp.display_name as rateplan_name, rp.assigned_to, rp.default ");
		$this->db->from('user_tbl usr');
		$this->db->join('rateplan_tbl rp', 'usr.rateplanid=rp.id and rp.active=1', 'left', FALSE);
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

	public function get_customer_wallets($companyid, $customerid=-1) {
		$this->db->select("cm1.display_name as company_name, wl.name, wl.display_name, wl.companyid, wl.userid, wl.sponsoring_companyid, 
			cm2.display_name as sponsoring_company_name, wl.allowed_transactions, wl.wallet_account_code, wl.balance, wl.type, wl.status, 
			wl.created_by, wl.created_on, wl.updated_by, wl.updated_on ");
		$this->db->from('system_wallets_tbl wl');
		$this->db->join('company_tbl cm1', 'wl.companyid=cm1.id', 'left', FALSE);
		$this->db->join('company_tbl cm2', 'wl.sponsoring_companyid=cm2.id', 'left', FALSE);
		$this->db->where('wl.companyid = '.$company);
		if($customerid > 0) {
			$this->db->where('wl.userid = '.$customerid);
		}

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
		$this->db->select("usr.*, rp.display_name as rateplan_name, rp.assigned_to, rp.default ", FALSE);
		$this->db->from('user_tbl usr');
		$this->db->join('rateplan_tbl rp', 'usr.rateplanid=rp.id and rp.active=1', 'left', FALSE);
		$this->db->where('(usr.email=\''.$email.'\' or usr.mobile=\''.$mobile.'\') and usr.companyid='.$companyid.' and usr.id<>'.$id, NULL, FALSE);

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
		$this->db->select("c.id, c.code, c.name, c.address, c.display_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type, c.baseurl, usr.email, group_concat(md.datavalue) as services, ifnull(att.datatype, '') as configtype, ifnull(att.datavalue, '') as configuration ");
		$this->db->from('company_tbl c');
		$this->db->join('company_services_tbl csrv', 'csrv.companyid=c.id and csrv.active=1 and c.active=1', 'inner');
		$this->db->join('metadata_tbl md', 'md.associated_object_type=\'services\' and csrv.serviceid=md.id and md.active=1', 'inner');
		$this->db->join('user_tbl usr', 'usr.id=c.primary_user_id', 'inner');
		$this->db->join('attributes_tbl att', 'att.companyid=c.id and att.target_object_type=\'company\' and att.code=\'configuration\'', 'left');
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
		$this->db->select("c.id, c.code, c.name, c.address, c.display_name, c.tenent_code, c.primary_user_id, c.gst_no, c.pan, c.type, c.baseurl, usr.email, group_concat(md.datavalue) as services, ifnull(att.datatype, '') as configtype, ifnull(att.datavalue, '') as configuration ");
		$this->db->from('company_tbl c');
		$this->db->join('company_services_tbl csrv', 'csrv.companyid=c.id and csrv.active=1 and c.active=1', 'inner');
		$this->db->join('metadata_tbl md', 'md.associated_object_type=\'services\' and csrv.serviceid=md.id and md.active=1', 'inner');
		$this->db->join('user_tbl usr', 'usr.id=c.primary_user_id', 'inner');
		$this->db->join('attributes_tbl att', 'att.companyid=c.id and att.target_object_type=\'company\' and att.code=\'configuration\'', 'left');
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
		$this->db->select("cm.id, cm.title, cm.active, cm.companyid, cm.created_by, cm.created_on, cm.updated_by, cm.updated_on, (select display_name from company_tbl where id=$inviteeid) as invitee, (select display_name from company_tbl where id=$invitorid) as invitor, count(cmd.id) as msgcount, max(cmd.read) as isread, max(cmd.type) as type ");
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
		$this->db->select("cmd.id, cmd.pid, cmd.message, cmd.from_companyid, c1.display_name as fromcompany, cmd.to_companyid, c2.display_name as tocompany, cmd.ref_no, cmd.type, cmd.active, cmd.created_by, cmd.created_on, cmd.updated_by, cmd.updated_on, cmd.read, cmd.last_read_on, cmd.invitation_type, cmd.serviceid ");
		$this->db->from('communication_detail_tbl cmd');
		$this->db->join('company_tbl c1', 'cmd.from_companyid=c1.id', 'inner');
		$this->db->join('company_tbl c2', 'cmd.to_companyid=c2.id', 'inner');
		$this->db->where("cmd.active=1 and cmd.pid=$communicationid");

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

	public function search_communication_detail($msgdetailid) {
		$this->db->select("cmd.id, cmd.pid, cmd.message, cmd.from_companyid, c1.display_name as fromcompany, cmd.to_companyid, c2.display_name as tocompany, cmd.ref_no, cmd.type, cmd.active, cmd.created_by, cmd.created_on, cmd.updated_by, cmd.updated_on, cmd.read, cmd.last_read_on, cmd.invitation_type, cmd.serviceid ");
		$this->db->from('communication_detail_tbl cmd');
		$this->db->join('company_tbl c1', 'cmd.from_companyid=c1.id', 'inner');
		$this->db->join('company_tbl c2', 'cmd.to_companyid=c2.id', 'inner');
		$this->db->where("cmd.active=1 and cmd.id=$msgdetailid");

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
			$this->db->select("cmd.id, cm.title, cm.active, cm.companyid, cmd.from_companyid, c1.display_name as from_company_name, cmd.to_companyid, c2.display_name as to_company_name, cmd.ref_no, cmd.message, cmd.type, cmd.created_on, cmd.created_by, usr.name, cmd.read, cmd.last_read_on, cmd.invitation_type, cmd.serviceid, (select max(type) as type1 from communication_detail_tbl cmd1 where cmd1.pid=cm.id) as finaltype ", FALSE);
			$this->db->from('communication_tbl cm');
			$this->db->join('communication_detail_tbl cmd', 'cm.id=cmd.pid and cmd.active=1', 'inner', FALSE);
			$this->db->join('company_tbl c1', 'c1.id=cmd.from_companyid', 'inner', FALSE);
			$this->db->join('company_tbl c2', 'c2.id=cmd.to_companyid', 'inner', FALSE);
			$this->db->join('user_tbl usr', 'cmd.created_by=usr.id', 'inner', FALSE);
			$this->db->where("cmd.from_companyid=$companyid", NULL, FALSE);
			$this->db->order_by("cmd.created_on desc", NULL, FALSE);
	
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
		else if($boxtype === 'inbox') {
			// this is for inbox
			$this->db->select("cmd.id, cm.title, cm.active, cm.companyid, cmd.from_companyid, c1.display_name as from_company_name, cmd.to_companyid, c2.display_name as to_company_name, cmd.ref_no, cmd.message, cmd.type, cmd.created_on, cmd.created_by, usr.name, cmd.read, cmd.last_read_on, cmd.invitation_type, cmd.serviceid, (select max(type) as type1 from communication_detail_tbl cmd1 where cmd1.pid=cm.id) as finaltype ", FALSE);
			$this->db->from('communication_tbl cm');
			$this->db->join('communication_detail_tbl cmd', 'cm.id=cmd.pid and cmd.active=1', 'inner', FALSE);
			$this->db->join('company_tbl c1', 'c1.id=cmd.from_companyid', 'inner', FALSE);
			$this->db->join('company_tbl c2', 'c2.id=cmd.to_companyid', 'inner', FALSE);
			$this->db->join('user_tbl usr', 'cmd.created_by=usr.id', 'inner', FALSE);
			$this->db->where("cmd.to_companyid=$companyid", NULL, FALSE);
			$this->db->order_by("cmd.created_on desc", NULL, FALSE);
	
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

	public function message_read($msgid, $userid) {
        if(empty($msgid) || empty($userid)){
			return array("message" => "Invalid msgid or userid passed", "status" => false);
		}

		$result = '';
		//update
		try
		{
			$where = array("id" => intval($msgid, 10));
			// $fields = array("read" => 1, "last_read_on" => "$date()");
			$this->db->set("read", 1);
			$this->db->set("last_read_on", 'NOW()', FALSE);
			$this->db->where($where);
			$result = $this->db->update('communication_detail_tbl');
			$result = array("message" => "Item updated successfully.", "id" => intval($msgid,10));
		}
		catch(Exception $ex) {
			throw $ex;
		}

		return [$result];
	}

	public function rateplanByCompanyid($companyid, $arv=NULL) {
        if(empty($companyid)){
			return array("message" => "Invalid company id passed", "status" => false);
		}

		$result = '';
		//update
		try
		{
			$this->db->select("distinct rp.default, rp.id, rp.display_name as planname, rp.assigned_to, rp.companyid, cmp.display_name, rp.active, rp.created_by, rp.created_on, rp.updated_by, rp.updated_on, usr.name as created_by_name ", FALSE);
			$this->db->from('rateplan_tbl rp');
			$this->db->join('rateplan_detail_tbl rpd', 'rp.id=rpd.rateplanid and rp.active=1', 'inner', FALSE);
			$this->db->join('company_tbl cmp', 'cmp.id=rp.companyid and cmp.active=1', 'inner', FALSE);
			$this->db->join('user_tbl usr', 'rp.created_by=usr.id', 'inner', FALSE);
			if($companyid!==-1) {
				$this->db->where("rp.companyid=$companyid", NULL, FALSE);
			}
			if($arv!=NULL) {
				$this->db->where($arv, NULL, FALSE);
			}
			//$this->db->order_by("rp.display_name", NULL, FALSE);
			$this->db->order_by("rp.created_on", NULL, FALSE);
	
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
		catch(Exception $ex) {
			throw $ex;
		}

		return [$result];
	}

	public function rateplandetails($rateplanid=0) {
        if(empty($rateplanid)){
			return array("message" => "Invalid rate plan id passed", "status" => false);
		}

		$result = '';
		//update
		try
		{
			$this->db->select("rpd.id, rp.display_name as planname, rp.assigned_to, rp.companyid, rpd.rateplanid, rpd.serialno, rpd.head_name, rpd.head_code, rpd.amount, rpd.amount_type, rpd.operation, rpd.calculation, rpd.active, rpd.created_by, rpd.created_on, usr.name as created_by_name ", FALSE);
			$this->db->from('rateplan_tbl rp');
			$this->db->join('rateplan_detail_tbl rpd', 'rp.id=rpd.rateplanid and rp.active=1', 'inner', FALSE);
			$this->db->join('company_tbl cmp', 'cmp.id=rp.companyid and cmp.active=1', 'inner', FALSE);
			$this->db->join('user_tbl usr', 'rpd.created_by=usr.id', 'inner', FALSE);
			if($rateplanid>0) {
				$this->db->where("rp.id=$rateplanid", NULL, FALSE);
			}
			//$this->db->order_by("rp.display_name, rpd.serialno", NULL, FALSE);
			$this->db->order_by("rpd.serialno", NULL, FALSE);
	
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
		catch(Exception $ex) {
			throw $ex;
		}

		return [$result];
	}

	public function get_services($companyid) {
        if(empty($companyid)){
			return array("message" => "Invalid company id passed", "status" => false);
		}

		$result = '';
		//update
		try
		{
			$this->db->select("cmsrv.id, cmsrv.companyid, cmsrv.active, cmsrv.serviceid, md.datavalue as service_name, cm.display_name as company_name ", FALSE);
			$this->db->from("company_services_tbl cmsrv");
			$this->db->join("metadata_tbl md", "md.id=cmsrv.serviceid and md.associated_object_type='services' and name='name'", 'inner', FALSE);
			$this->db->join("company_tbl cm", "cmsrv.companyid=cm.id", "inner", FALSE);
			$this->db->where("cmsrv.companyid = $companyid", NULL, FALSE);
			$this->db->order_by("md.datavalue", NULL, FALSE);
	
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
		catch(Exception $ex) {
			throw $ex;
		}

		return [$result];
	}

	public function wholesaler_save($wholesaler) {
        if(!empty($wholesaler["id"])) {
            $data = $this->db->get_where("wholesaler_tbl", ['id' => $wholesaler["id"]])->row_array();
        } else if(!empty($wholesaler["salerid"]) && !empty($wholesaler["companyid"])){
            $data = $this->db->get_where("wholesaler_tbl", ['salerid' => $wholesaler["salerid"], 'companyid' => $wholesaler["companyid"]])->row_array();
		}
		else {
			$data = null;
		}

		$result = '';
		if($data==null) {
			//insert
			$wholesaler["id"] = null;
			unset($wholesaler["details"]);
			$this->db->insert('wholesaler_tbl', $wholesaler);
			$result = array("message" => "Wholesaler registered successfully.", "id" => $this->db->insert_id());
		}
		else {
			//update
			if(!empty($data["id"])) {
				try
				{
					$wholesalerDetails = $wholesaler["details"];
					unset($wholesaler["details"]);
					$result = $this->db->update('wholesaler_tbl', $wholesaler, array("id" => intval($data["id"],10)));
					$result = array("message" => "Wholesaler updated successfully.", "id" => intval($data["id"],10));
				}
				catch(Exception $ex) {
					throw $ex;
				}
			}
		}

		return [$result];
	}

	public function wholesaler_detail_save($wholesalerDetail) {
        if(!empty($wholesalerDetail["id"])) {
            $data = $this->db->get_where("wholesaler_services_tbl", ['id' => $wholesalerDetail["id"]])->row_array();
        } else if(!empty($wholesalerDetail["wholesaler_rel_id"])) {
            $data = $this->db->get_where("wholesaler_services_tbl", ['wholesaler_rel_id' => $wholesalerDetail["wholesaler_rel_id"]])->row_array();
		} else {
			$data = null;
		}

		$result = '';
		if($data==null) {
			//insert
			$wholesalerDetail["id"] = null;
			$this->db->insert('wholesaler_services_tbl', $wholesalerDetail);
			$result = array("message" => "Wholesaler services registered successfully.", "id" => $this->db->insert_id());
		}
		else {
			//update
			if(!empty($data["id"])) {
				try
				{
					$result = $this->db->update('wholesaler_services_tbl', $wholesalerDetail, array("id" => intval($data["id"],10)));
					$result = array("message" => "Wholesaler services updated successfully.", "id" => intval($data["id"],10));
				}
				catch(Exception $ex) {
					throw $ex;
				}
			}
		}

		return [$result];
	}

	public function supplier_save($supplier) {
        if(!empty($supplier["id"])) {
            $data = $this->db->get_where("supplier_tbl", ['id' => $supplier["id"]])->row_array();
        } else if(!empty($supplier["supplierid"]) && !empty($supplier["companyid"])){
            $data = $this->db->get_where("supplier_tbl", ['supplierid' => $supplier["supplierid"], 'companyid' => $supplier["companyid"]])->row_array();
		}
		else {
			$data = null;
		}

		$result = '';
		if($data==null) {
			//insert
			$supplier["id"] = null;
			unset($supplier["details"]);
			$this->db->insert('supplier_tbl', $supplier);
			$result = array("message" => "Supplier registered successfully.", "id" => $this->db->insert_id());
		}
		else {
			//update
			if(!empty($data["id"])) {
				try
				{
					$supplierDetails = $supplier["details"];
					unset($supplier["details"]);
					$result = $this->db->update('supplier_tbl', $supplier, array("id" => intval($data["id"],10)));
					$result = array("message" => "Supplier updated successfully.", "id" => intval($data["id"],10));
				}
				catch(Exception $ex) {
					throw $ex;
				}
			}
		}

		return [$result];
	}

	public function supplier_detail_save($supplierDetail) {
        if(!empty($supplierDetail["id"])) {
            $data = $this->db->get_where("supplier_services_tbl", ['id' => $supplierDetail["id"]])->row_array();
        } else if(!empty($supplierDetail["supplier_rel_id"])) {
            $data = $this->db->get_where("supplier_services_tbl", ['supplier_rel_id' => $supplierDetail["supplier_rel_id"]])->row_array();
		} else {
			$data = null;
		}

		$result = '';
		if($data==null) {
			//insert
			$supplierDetail["id"] = null;
			$this->db->insert('supplier_services_tbl', $supplierDetail);
			$result = array("message" => "Supplier services registered successfully.", "id" => $this->db->insert_id());
		}
		else {
			//update
			if(!empty($data["id"])) {
				try
				{
					$result = $this->db->update('supplier_services_tbl', $supplierDetail, array("id" => intval($data["id"],10)));
					$result = array("message" => "Supplier services updated successfully.", "id" => intval($data["id"],10));
				}
				catch(Exception $ex) {
					throw $ex;
				}
			}
		}

		return [$result];
	}

	public function save_rateplan($rateplan) {
		$result = array();
		if(empty($rateplan)) {
			$result["message"] = "Invalid rateplan passed";
			$result["status"] = -1;

			//return [$result];
			throw new Exception('Invalid rateplan passed');
		}

		try
		{
			if(!empty($rateplan["id"])) {
				//update
				$this->db->update('rateplan_tbl', $rateplan, array("id" => intval($rateplan["id"], 10)));
				$result = array("message" => "Rateplan updated successfully.", "id" => intval($rateplan["id"],10));
			}
			else {
				$rateplan["id"] = NULL;
				$this->db->insert('rateplan_tbl', $rateplan);
				$rateplan["id"] = $this->db->insert_id();
				$result = array("message" => "Rateplan updated successfully.", "id" => $this->db->insert_id());
			}
		}
		catch(Exception $ex) {
			throw $ex;
		}

		return [$result];
	}

	public function save_rateplan_details($rateplandetails) {
		$result = array();
		if(empty($rateplandetails) || count($rateplandetails)==0) {
			$result["message"] = "Invalid rateplan details passed";
			$result["status"] = -1;

			// return [$result];
			throw new Exception('Invalid rateplan details passed');
		}

		try
		{
			for ($i=0; $i<count($rateplandetails); $i++) { 
				$rateplandetail = $rateplandetails[$i];
				if(!empty($rateplandetail["id"])) {
					//update
					$this->db->update('rateplan_detail_tbl', $rateplandetail, array("id" => intval($rateplandetail["id"], 10)));
					array_push($result, array("message-$i" => "Rateplan detaiils updated successfully.", "id" => intval($rateplandetail["id"],10)));
				}
				else {
					//insert
					$rateplandetail["id"] = NULL;
					$this->db->insert('rateplan_detail_tbl', $rateplandetail);
					$rateplandetail["id"] = $this->db->insert_id();
					array_push($result, array("message-$i" => "Rateplan details updated successfully.", "id" => $this->db->insert_id()));
				}
			}
		}
		catch(Exception $ex) {
			throw $ex;
		}

		return [$result];
	}

	public function get_supplier($supplierid, $wholesalerid) {
		$this->db->select("spd.* ", FALSE);
		$this->db->from('supplier_services_tbl spd');
		$this->db->join('supplier_tbl sp', 'spd.supplier_rel_id=sp.id', 'inner', FALSE);
		$this->db->where("sp.supplierid=$supplierid and sp.companyid=$wholesalerid", NULL, FALSE);
		
		$query = $this->db->get();
		// $qry = $this->db->last_query();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
            return false;
		}
	}

	public function get_wholesaler($supplierid, $wholesalerid) {
		$this->db->select("wsd.* ", FALSE);
		$this->db->from('wholesaler_services_tbl wsd');
		$this->db->join('wholesaler_tbl ws', 'wsd.wholesaler_rel_id=ws.id', 'inner', FALSE);
		$this->db->where("ws.salerid=$supplierid and ws.companyid=$wholesalerid", NULL, FALSE);
		
		$query = $this->db->get();
		// $qry = $this->db->last_query();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
            return false;
		}
	}

	public function get_metadata($metadatacode, $companyid) {
		$this->db->select("mt.* ", FALSE);
		$this->db->from('metadata_tbl mt');
		$this->db->where("mt.active=1 and mt.associated_object_type = '$metadatacode' and (mt.companyid = 0 or mt.companyid = $companyid)", NULL, FALSE);

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

	public function get_cities() {
		$this->db->select("ct.* ", FALSE);
		$this->db->from('city_tbl ct');

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

	public function get_airlines() {
		$this->db->select("al.* ", FALSE);
		$this->db->from('airline_tbl al');

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

	function abbreviate($string){
		$abbreviation = "";
		$string = ucwords($string);
		$words = explode(" ", "$string");
		  foreach($words as $word){
			  $abbreviation .= $word[0];
		  }
	   return $abbreviation; 
	}	

	function save_pnr($pnrdetails) {
		$result = false;
		log_message('info', json_encode($pnrdetails));

		if($pnrdetails !== NULL && intval($pnrdetails['id'], 10)>0) {
			$result = $this->db->update('customer_information_tbl', array(
				'prefix' => $pnrdetails['prefix'], 
				'first_name' => strtoupper($pnrdetails['first_name']), 
				'last_name' => strtoupper($pnrdetails['last_name']), 
			), array("id" => intval($pnrdetails["id"], 10)));
		}

		return $result;
	}
}	
?>