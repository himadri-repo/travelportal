<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'core/TransactionResponse.php');
include_once(APPPATH.'core/Mail_Controller.php');
include_once(APPPATH.'core/Common.php');
define('PAGE_SIZE', 25);

class User_Controller extends Mail_Controller 
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Calcutta'); 		
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('security');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('excel');
		$this->load->database();
		$this->load->library('upload');
		$this->load->model('User_Model');
		$this->load->model('Search_Model');
        $this->load->model('Admin_Model');
	}
	
	
	public function index()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['ticket_added']=$this->User_Model->ticket_added();
			$result['my_booking']=$this->User_Model->my_booking();	
			$result['ticket_sold']=$this->User_Model->ticket_sold();
			$result['count_testi']=$this->User_Model->count_testi();
			$result['cancels']=$this->User_Model->cancels();
			$result['wallet']=$this->User_Model->wallet();
			$result['city']=$this->User_Model->select("city_tbl");	
			$result['airline']=$this->User_Model->select("airline_tbl");
			$result["footer"]=$this->Search_Model->get_post(5);

			$company = $this->session->userdata("company");
			$companyname = $company['display_name'];
			//$result["setting"]=$this->Search_Model->setting();
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);
			$result["payment_gateway"] = json_decode($result["setting"][0]['payment_gateway'], true);

			$result["payload"] = ($this->session->flashdata('payment_response') !== NULL) ? $this->session->flashdata('payment_response') : false;

			if(NEW_FLOW)
			{
				$current_user = $this->session->userdata("current_user");
				$companyid = $current_user["companyid"];
				$result["companyid"] = $companyid;
				$result["cname"] = $current_user["cname"];
				$result["current_user"] = $current_user;

				$result["company_setting"]=$this->Search_Model->company_setting($companyid);
			}
			//$bank_accounts = json_decode($result["company_setting"][0]['bank_accounts'], false);

			$result["target_accounts"] = json_decode($result["company_setting"][0]['bank_accounts'], true);

			$result['mywallet']= $this->getMyWallet();

			if(isset($result['mywallet']['walletid'])) {
				$result['wallet_summary']=$this->User_Model->get_wallet_summary($result['mywallet']['walletid']);
			}

		    $this->load->view('header1',$result);
			$this->load->view('user_dashboard',$result);
			$this->load->view('footer1');
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function tickets()
	{
		$result['user_details']=$this->User_Model->user_details();
		if ($this->session->userdata('user_id') && $result['user_details'][0]["is_supplier"]==1) 
		{ 
			$str="";
			$pageindex = 0;
			$pagesize = 0;
			
			if($this->input->get('pageindex') !== null) {
				$pageindex = intval($this->input->get('pageindex'));
			}

			if($this->input->get('pagesize') !== null) {
				$pagesize = intval($this->input->get('pagesize'));
			}
			
	        if($_SERVER['REQUEST_METHOD'] == 'POST' || ($pageindex>0 && $pagesize>0))
			{
				$pageindex = $this->input->get('pageindex') !== null ? intval($this->input->get('pageindex')) : 1;
				$pagesize = $this->input->get('pagesize') !== null ? intval($this->input->get('pagesize')) : PAGE_SIZE;
				$arr = null;
				if($this->session->userdata('ticket_qry') && $_SERVER['REQUEST_METHOD'] == 'GET') {
					$arr = $this->session->userdata('ticket_qry');
					if($arr === null) {
						$arr=array(
							"DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="=>'1970-01-01',
							"DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>'1970-01-01',
							"source"=>'',
							"destination"=>'',
							"pnr"=>'',
							"pageindex"=>$pageindex,
							"pagesize"=>$pagesize
						);		
					}

					$dt_from = $arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="]!=='1970-01-01'?$arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="]:'';
					$dt_to = $arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="]!=='1970-01-01'?$arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="]:'';
					$source = $arr["source"];
					$destination = $arr["destination"];
					$pnr = $arr["pnr"];
				}
				else {
					$arr=array(
						"DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="=>date('Y-m-d', strtotime($this->input->post('dt_from'))),
						"DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>date('Y-m-d', strtotime($this->input->post('dt_to'))),
						"source"=>$this->input->post('source'),
						"destination"=>$this->input->post('destination'),
						"pnr"=>$this->input->post('pnr'),
						"pageindex"=>$pageindex,
						"pagesize"=>$pagesize
					);

					$this->session->set_userdata('ticket_qry', $arr);

					$dt_from = $arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="]!=='1970-01-01'?$arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="]:'';
					$dt_to = $arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="]!=='1970-01-01'?$arr["DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="]:'';
					$source = $arr["source"];
					$destination = $arr["destination"];
					$pnr = $arr["pnr"];
				}

				$arr['pageindex'] = $pageindex;
				$arr['pagesize'] = $pagesize;

				$result['page_size'] = $pagesize;
				$result['page_index'] = $pageindex;
				$result['total_tickets'] = $this->User_Model->total_ticket($arr);
				
				$result['ticket']=$this->User_Model->search_ticket($arr);
				
				$result['dt_from']=$dt_from;
				$result['dt_to']=$dt_to;
				$result['source']=$source;
				$result['destination']=$destination;
				$result['pnr']=$pnr;
				$result['pageindex']=$pageindex;
				$result['pagesize']=$pagesize;
			}
			else
			{
				$this->session->unset_userdata('ticket_qry');

				$pageindex = $this->input->get('pageindex') !== null ? intval($this->input->get('pageindex')) : 1;
				$pagesize = $this->input->get('pagesize') !== null ? intval($this->input->get('pagesize')) : PAGE_SIZE;

				$arr=array(
					"DATE_FORMAT(departure_date_time, '%Y-%m-%d')<="=>'1970-01-01',
					"DATE_FORMAT(departure_date_time, '%Y-%m-%d')>="=>'1970-01-01',
					"source"=>'',
					"destination"=>'',
					"pnr"=>'',
					"pageindex"=>$pageindex,
					"pagesize"=>$pagesize
				);
				$result['page_size'] = $pagesize;
				$result['page_index'] = $pageindex;
				$result['total_tickets'] = $this->User_Model->total_ticket($arr);
	
				$result['ticket']=$this->User_Model->ticket($pageindex, $pagesize);
				
				$result['dt_from']="";
				$result['dt_to']="";
				$result['source']="";
				$result['destination']="";
				$result['pnr']="";
				$result['pageindex']=$pageindex;
				$result['pagesize']=$pagesize;
			}
			
			$result['city']=$this->User_Model->select("city_tbl");
			//$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);	

		    $this->load->view('header1',$result);			
			$this->load->view('user_ticket',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/user');
		}
		
	}
	
	public function testimonials()
	{
		$result['user_details']=$this->User_Model->user_details();
		if ($this->session->userdata('user_id') ) 
		{ 
	        			
			$result['testimonials']=$this->User_Model->testimonials();
	        //$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);
				
			$result['mywallet']= $this->getMyWallet();

		    $this->load->view('header1',$result);			
			$this->load->view('user_testimonials',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/user');
		}
		
	}
	
	public function mybookings()
	{
		if ($this->session->userdata('user_id')) 
		{ 
			$result['usermodel'] = $this->User_Model;
			$result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->my_booking_order();
	        //$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);
			
		    $this->load->view('header1',$result);			
			$this->load->view('user_booking',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}	
	}
	
	
	public function booking_details($id)
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->booking_details($id);
	        //$result["setting"]=$this->Search_Model->setting();
	        $result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		    $this->load->view('header1',$result);			
			$this->load->view('user_booking_details',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function bookingorders()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->booking_orders();
	        //$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		    $this->load->view('header1',$result);			
			$this->load->view('user_booking_orders',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function cancelrequest()
	{
		if ($this->session->userdata('user_id')) 
		{ 
	        $result['user_details']=$this->User_Model->user_details();
			$result['sale_order']=$this->User_Model->cancel_request();
	        //$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		    $this->load->view('header1',$result);			
			$this->load->view('user_cancel_request',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}	
	}
	
	public function edit_booking($id)
	{
		$result1["user"]=$this->User_Model->user_details();		
		//$result["flight"]=$this->Search_Model->get_booking_details($id); 
		$result["flight"]=$this->Search_Model->booking_details($id); 
		if ($this->session->userdata('user_id') && isset($id) && ($result["flight"][0]["customer_id"]==$this->session->userdata('user_id')) && $result1["user"][0]["is_customer"]==1 ) 
		{			          		   				  							  
			$result["flight"][0]["price"]=$result["flight"][0]["rate"];	
			$result["flight"][0]["service_charge"]=$result["flight"][0]["service_charge"];	
			$result["flight"][0]["gst"]=($result["flight"][0]["igst"]+$result["flight"][0]["cgst"]+$result["flight"][0]["sgst"]);	
			$result["flight"][0]["total"]=$result["flight"][0]["total"];
			$result["flight"][0]["qty"]=$result["flight"][0]["qty"];
			$result["flight"][0]["id"]=$id;								  			  			 
			//$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);

			$this->load->view('header1',$result);;
			$this->load->view('edit_booking',$result);						
			$this->load->view('footer1');					 						  
		}
		else
		{
			redirect('/user/booking-orders');
		}
	}
	
	
	public function logout()
	{		
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('company');
		$this->session->sess_destroy();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		redirect('/login');				
	}
	
	public function register()
	{
		$companies = $this->Admin_Model->get_companies();
		$company = null;
		$siteUrl = siteURL();

		for($idx=0; $idx<count($companies); $idx++) {
			if(strtolower($companies[$idx]["baseurl"]) === strtolower($siteUrl)) {
				$company = $companies[$idx];
				break;
			}
		}

		//$result["setting"]=$this->Search_Model->setting();
		$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		$result["footer"]=$this->Search_Model->get_post(5);
		$result['mywallet']= $this->getMyWallet();

		$this->load->view('header1',$result);
		if($company!==null) {
			$states = $this->Admin_Model->get_metadata('state', $company["id"]);
			$country = $this->Admin_Model->get_metadata('country', $company["id"]);
			$payload = array(
				'states' => $states,
				'countries' => $country
			);

			$this->session->set_userdata('company', $company);
			$this->load->view('register', $payload);
		}
		$this->load->view('footer');
		
	}
	
	public function login()
	{
		$returnurl = $this->input->get('returnurl');
		$qty = intval($this->input->get('qty'));

		log_message('debug', "Return URL : $returnurl | Passed Qty : $qty");

		if($returnurl) {
			$this->session->set_userdata('returnurl', $returnurl);
		}
		if($qty>0) {
			$this->session->set_userdata('no_of_person', $qty);
		}

		$companies = $this->Admin_Model->get_companies();
		$company = null;
		$siteUrl = siteURL();

		for($idx=0; $idx<count($companies); $idx++) {
			if(strtolower($companies[$idx]["baseurl"]) === strtolower($siteUrl)) {
				$company = $companies[$idx];
				log_message('debug', json_encode($company, false));
				break;
			}
		}

		//$result["setting"]=$this->Search_Model->setting();
		$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		$result["footer"]=$this->Search_Model->get_post(5);
		$result["company"]=$company;
		if(isset($result["setting"]) && count($result["setting"])>0) {
			$company["setting"] = array(
				"configuration" => $result["setting"][0]["configuration"], 
				"payment_gateway" => $result["setting"][0]['payment_gateway'],
				"bank_accounts" => $result["setting"][0]['bank_accounts'],
				"api_integration" => $result["setting"][0]['api_integration']
			);
		}

		$result['mywallet']= $this->getMyWallet();

		if(!NEW_FLOW) {
			$this->load->view('header1',$result);
			$this->load->view('login');
			$this->load->view('footer');
		}
		else {
			$this->load->view('header',$result);
			if($company!==null) {
				$this->session->set_userdata('company', $company);
				$this->load->view('login');
			}
			$this->load->view('footer');
		}
	}
	
	
	public function verify()
	{
		//$result["setting"]=$this->Search_Model->setting();
		$result["footer"]=$this->Search_Model->get_post(5);
		$result['mywallet']= $this->getMyWallet();
		$company = $this->getCurrentCompany();
		$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		$this->load->view('header1',$result);
		$this->load->view('verify');
		$this->load->view('footer');
		
	}
	
	public function login_otp()
	{
		$company = $this->getCurrentCompany();
		//$result["setting"]=$this->Search_Model->setting();
		$result["footer"]=$this->Search_Model->get_post(5);
		$result['mywallet']= $this->getMyWallet();
		$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		$this->load->view('header1',$result);
		$this->load->view('login_otp');
		$this->load->view('footer');
		
	}
	
	public function do_register()
	{
		  
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
			 $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
			 $this->form_validation->set_rules('state', 'State', 'required|callback_validate_state');
			 $this->form_validation->set_rules('country', 'Country', 'required|callback_validate_country');
			 $this->form_validation->set_rules('email','Email','required|trim|xss_clean|valid_email|callback_unique_email');
			 $this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]|callback_unique_mobile');
			 $this->form_validation->set_rules('type','Register As.','required|xss_clean');
			//  $this->form_validation->set_rules('pan','PAN.','trim|xss_clean|max_length[10]|min_length[10]');
			//  $this->form_validation->set_rules('gst','GST.','trim|xss_clean|max_length[15]|min_length[15]');
			 $this->form_validation->set_rules('password','Password','required|xss_clean|min_length[6]');
			 $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array(
					'name' => form_error('name', '<div class="error">', '</div>'),
					'address' => form_error('address', '<div class="error">', '</div>'),
					'state' => form_error('state', '<div class="error">', '</div>'),
					'country' => form_error('country', '<div class="error">', '</div>'),
					'email' => form_error('email', '<div class="error">', '</div>'),                
					'mobile' => form_error('mobile', '<div class="error">', '</div>'),
					'type' => form_error('type', '<div class="error">', '</div>'),
					'password' => form_error('password', '<div class="error">', '</div>'),
					'confirm_password' => form_error('confirm_password', '<div class="error">', '</div>')								
				);
				
			 }
			 else
			 {
				$type = $this->input->post('type');
				if($type=='traveller') {
					$type = 'B2C';
				}
				else if($type=='agent') {
					$type = 'B2B';
				}
				$company = $this->session->userdata('company');
				$CI =   &get_instance();
				$query  =   $CI->db->get('user_tbl');
				$num=$query->num_rows();
				$num++;
				$user_id="USR".sprintf('%03d',$num); //prefix was OXY changed to USR just to make it company indipendent
				$arr = array(
							'user_id'=> $user_id,
							'name' => $this->input->post('name'),
							'address' => $this->input->post('address'),
							'state' => $this->input->post('state'),
							'country' => $this->input->post('country'),
							'email'=>$this->input->post('email'),
							'mobile'=>$this->input->post('mobile'),
							'password'=>$this->input->post('password'),
							'companyid'=>$company['id'],
							'type'=>$type,
							'pan'=>$this->input->post('pan'),
							'gst'=>$this->input->post('gst'),
							'doj'=>date("Y-m-d")
							);

				$otp=rand(111111,999999);
				$this->session->set_userdata('otp',$otp);
				$this->session->set_userdata('data',$arr);
				$this->session->set_userdata('email',$this->input->post('email'));
				$this->session->set_userdata('type',$type);
				$this->session->set_userdata('pan',$this->input->post('pan'));
				$this->session->set_userdata('gst',$this->input->post('gst'));
				$this->session->set_userdata('name',$this->input->post('name'));
				$this->session->set_userdata('mobile',$this->input->post('mobile'));
				$this->session->set_userdata('reg_user_id',$user_id);
				$no=$this->input->post('mobile');
				$msg='Dear '.$this->input->post('name').', OTP for mobile number verification is '.$otp.'. Thanks '.$company['display_name'];
				$this->send_message($no,$msg);
				$json["success"]=true;
				 
			 }
			 echo json_encode($json);	
		 }		 
	}
	
	
	public function confirm_otp()
	{
		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$company=$this->session->userdata('company');
			$base_url = isset($company['baseurl'])?$company['baseurl']:'';
			$company_settings=$this->Search_Model->company_setting($company["id"]);
			$otp=$this->input->post('otp');
			if ($otp==$this->session->userdata('otp')) 
			{
				$CI =   &get_instance();	

				$query  =   $CI->db->get('user_tbl');
				$num=$query->num_rows();
				$num++;
				$user_id="USR".sprintf('%03d',$num); //prefix was OXY changed to USR just to make it company indipendent

				$query = $CI->db->get_where('user_tbl', array('id' => $company["primary_user_id"]));
				$admin_user=$query->result_array();

				$data=$this->session->userdata('data');
				$data["uid"] = gen_uuid();
				$data["user_id"] = $user_id;
				$data["is_supplier"] = 0;
				$data["permission"] = $this->get_default_permission($company, $data);
				$result = $this->User_Model->save("user_tbl",$data);
				if($result==true)
				{
					$wallet_code = $this->abbreviate($data['name']);
					$companyabcode = $this->abbreviate($company['display_name']);

					$walletdata = array(
						'name' => $company['code'].'_wallet_'.$result, 
						'display_name' => 'Wallet by '.$company['display_name'], 
						'companyid' => $company['id'], 
						'userid' => $result, 
						'sponsoring_companyid' => $company['id'], 
						'wallet_account_code' => 'WL_'.$companyabcode.'_'.$wallet_code.'_'.$result,  
						'balance' => 0,
						'type' => 2,
						'created_by' => $result
					);

					$wallet_result = $this->User_Model->save("system_wallets_tbl",$walletdata);
										
					$data = array(				            
								'name' => $company['display_name'], // "OXYTRA",
								'email'=>$this->session->userdata('email'),
								'logo'=>isset($company_settings['logo'])?($base_url.'/upload/'.$company_settings['logo']):'',
								'msg'=>"Your Registration Completed Successfully",
								'msg1'=>'After Admin Approval of <span class="il">'.$company['display_name'].'</span> you can login to this site',
								'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
								);
					$this->send("Registration",$data);
					
					$data = array(				            
								'name' => $this->session->userdata('name'),
								'email'=>$this->session->userdata('email'),
								'mobile'=>$this->session->userdata('mobile'),
								'logo'=>isset($company_settings['logo'])?($base_url.'/upload/'.$company_settings['logo']):'',
								'msg'=>"A New User Registered",
								'user_id'=> $this->session->userdata('reg_user_id'),
								'msg1'=>'',
								'msg2'=>""
								
								);
			
					try
					{
						$this->adminsend("Registration",$data);
					}
					catch(Exception $ex1) {
						
					}
											
					if($admin_user!=null && count($admin_user)>0 && $admin_user[0]["mobile"]!='') {
						$no=$admin_user[0]["mobile"];
					}
					else {
						$no="9800412356";
					}
					$msg="A New User Register with ".$company['display_name'].". User ID : ".$this->session->userdata('reg_user_id')."";
					$this->send_message($no,$msg);
					$this->session->set_userdata('otp',"");
					$this->session->set_userdata('data',"");
					$this->session->set_userdata('email',"");
					$this->session->set_userdata('type',"");
					$this->session->set_userdata('name',"");
					$this->session->set_userdata('mobile',"");
					$this->session->set_userdata('reg_user_id',"");
					$json["success"]="Your Registration Completed Successfully";
			   }
			   else
			  {
				$json["error"]=false;
			  }
			}
			else
			{
				$json["error"]="You have entered wrong OTP";
			}
			echo json_encode($json);	
		}
	}

	/*This method called via ajax. When user login via OTP, after providing OTP this method being called */
	public function confirm_login_otp()
	{
		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$login_otp=$this->input->post('login_otp');
			if ($login_otp==$this->session->userdata('login_otp')) 
			{
				if($login_otp=="")
				{
					$json["error"]="You have entered wrong OTP";
				}
				else
				{
					$company = $this->session->userdata('company');

					$arr = array(
						'mobile'=>$this->session->userdata('login_mobile'),
						'password'=>$this->session->userdata('login_password'),
						'companyid'=>$company["id"],
						'login_via' => 'OTP'
					);
		
					$result = $this->User_Model->newlogin($arr);
					if($result==true)
					{	
						$this->session->set_userdata('current_user',$result);
						$this->session->set_userdata('user_id',$result['user_id']);
						$this->session->set_userdata('name',$result['name']);
						$json["success"]="Login Successfully";
						if($this->session->userdata('returnurl')) {
							$json["returnurl"] = $this->session->userdata('returnurl');
						}
						if($this->session->userdata('no_of_person')) {
							$json["no_of_person"] = $this->session->userdata('no_of_person');
						}
					}
					else {
						$json["error"]="Either wrong mobile number/email, password Or Your account is not yet approved";
					}					
					 
					//  $arr1 = array(
					// 			 'mobile'=>$this->session->userdata('login_mobile'),
					// 			 'password'=>$this->session->userdata('login_password'),
					// 			 'active'=>'1'	
					// 			 );
								 
					
					//  $result = $this->User_Model->login($arr1);
					//  if($result==true)
					//  {	
					// 	$this->session->set_userdata('user_id',$result['user_id']); 			 
					// 	$json["success"]="Login Successfully";
					//  }
					//  else
					// 	 $json["error"]="Your Account is not Approved";	 
				}			   
			}
			else
			{
				$json["error"]="You have entered wrong OTP";
			}
			echo json_encode($json);	
		}
	}
	public function update()
	{
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');			 
			 $this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]');						 
			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array(
					'name' => form_error('name', '<div class="error">', '</div>'),					              
					'mobile' => form_error('mobile', '<div class="error">', '</div>')												
				);
				
			 }
			 else
			 {
				 $arr = array('name' => $this->input->post('name'),							 
							 'mobile'=>$this->input->post('mobile')							 							
							 );
				 $result = $this->User_Model->update($arr,$this->session->userdata('user_id'));
				 if($result==true)			 
					$json["success"]="Your Profile Updated Successfully";
				 else
					 $json["error"]=$this->db->last_query();
			 }
			 echo json_encode($json);	
		 }		 
	}
	
	
	public function addmarkup()
	{
		 if ($this->session->userdata('user_id')) 
		 {
		 		 $CI =   &get_instance();	
                 $ticket = $CI->db->get_where('tickets_tbl', array('id' => $_REQUEST['hid_markup_ticket_id']));				
				 $result1=$ticket->result_array();
				 
				 $price=$result1[0]["price"];
                 $markup=$result1[0]["markup"];								 
				 $total=$result1[0]["total"];	 
				 
				 $markup+=$_REQUEST['markup_value'];
				 $total+=$_REQUEST['markup_value'];	
				 
				 $where=array("id"=>$_REQUEST['hid_markup_ticket_id']);					 
				 $data=array("markup"=>$markup,"total"=>$total);			 
				 $result = $this->Search_Model->update("tickets_tbl",$data,$where);
				 if($result==true)			 
					$json["success"]="Markup Added Successfully";
				 else
					 $json["error"]=false;
			 
			 echo json_encode($json);	
		 }		 
	}
	
	public function makepayment()
	{
		$company = $this->get_companyinfo();
		$companyname = $company['display_name'];
		if ($this->session->userdata('user_id')) 
		{
			$payment_type=isset($_POST["payment_type"])?$_POST["payment_type"]:"";
			$refrence_id=isset($_POST["refrence_id"])?$_POST["refrence_id"]:"";
			$cheque_no=isset($_POST["cheque_no"])?$_POST["cheque_no"]:"";
			$bank=isset($_POST["bank"])?$_POST["bank"]:"";
			$amount=isset($_POST["amount"])?$_POST["amount"]:"";
			
						
			$data=array(
			"request_date"=>date("Y-m-d h:i:s"),
			"user_id"=>$this->session->userdata('user_id'),
			"payment_type"=>$payment_type,
			"refrence_id"=>$refrence_id,
			"cheque_no"=>$cheque_no,
			"bank"=>$bank,
			"amount"=>$amount
			);	
			
			$result = $this->User_Model->save("payment_request_tbl",$data);
			if($result==true)
			{	
			$user['user_details']=$this->User_Model->user_details();
			
			$data = array(				            
						'name' => $companyname,
						'email'=>$user['user_details'][0]["email"],
						'msg'=>"Your Payment Request Sent Successfully",
						'msg1'=>"After Admin Approval of <span class='il'>$companyname</span> You will get amount on your wallet",
						'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
						);
			$this->send("Payment Request",$data);
			
			
			$data1 = array(				            
						'name' => $user['user_details'][0]["name"],
						'email'=>$user['user_details'][0]["email"],
						'mobile'=>$user['user_details'][0]["mobile"],
						'msg'=>"A Payment Request is done for ".$amount."",
						'user_id'=> $user['user_details'][0]["user_id"],
						'msg1'=>'',
						'msg2'=>""							 
						);
						
			$this->adminsend("Payment Request",$data1);	
			
			$no=$user['user_details'][0]["mobile"];
			$msg="Your Payment Request Sent Successfully. Thanks, $companyname";
			$this->send_message($no,$msg);
			
		
			$no="9800412356";
			$msg="A Payment Request Sent by User ID : ".$user['user_details'][0]["user_id"]."";
			$this->send_message($no,$msg);
			$json["success"]="Payment Request Sent Successfully";
			}
			else
				$json["error"]=false;
			
			echo json_encode($json);	
		}		 
	}
	
	public function validate_state($state) {
		$flag = intval($state)>0;

		if(!$flag)
			$this->form_validation->set_message('validate_state', 'Please select state you stay');

		return intval($state)>0;
	}

	public function validate_country($country) {
		$flag = intval($country)>0;

		if(!$flag)
			$this->form_validation->set_message('validate_country', 'Please select country you stay');

		return intval($country)>0;
	}

	public function unique_email($email)
    {
		$company = $this->session->userdata('company');

		$CI =   &get_instance();        
		//$check = $CI->db->get_where('user_tbl', array('email' => $email));
        $check = $CI->db->get_where('user_tbl', array('email' => $email, 'companyid' => $company['id']));
        
        if ($check->num_rows() > 0) 
		{
           
            $this->form_validation->set_message('unique_email', 'This Email already exists in our database');
            return false;
           
        }
        return TRUE;
    }
	
	public function unique_mobile($mobile)
    {
		$company = $this->session->userdata('company');

		$CI =   &get_instance();        
		//$check = $CI->db->get_where('user_tbl', array('mobile' => $mobile));
		$check = $CI->db->get_where('user_tbl', array('mobile' => $mobile, 'companyid' => $company['id']));
        
        if ($check->num_rows() > 0) 
		{
           
            $this->form_validation->set_message('unique_mobile', 'This Mobile No. already exists in our database');
            return false;
           
        }

        return TRUE;
	}
	
	public function validate_paymenttype($paymenttype) {
		$flag = intval($paymenttype)>0;

		if(!$flag)
			$this->form_validation->set_message('validate_paymenttype', 'Please select country you stay');

		return $flag;
	}

	public function validate_cheque($chequeno) {
		$payment_type = intval($this->input->post('payment_type'));
		
		$flag = $payment_type===1 ? intval($chequeno)>0 : true;

		if(!$flag)
			$this->form_validation->set_message('validate_cheque', 'Cheque number is mandatory for Cheque payment mode.');

		return $flag;
	}

	public function validate_draft($draftno) {
		$payment_type = intval($this->input->post('payment_type'));
		
		$flag = $payment_type===2 ? intval($draftno)>0 : true;

		if(!$flag)
			$this->form_validation->set_message('validate_draft', 'Draft number is mandatory for Draft payment mode.');

		return $flag;
	}

	public function validate_referenceid($referenceid) {
		$payment_type = intval($this->input->post('payment_type'));
		
		$flag = ($payment_type===3 || $payment_type===4 || $payment_type===8 || $payment_type===9 || $payment_type===10) ? ($referenceid!==NULL && $referenceid!=='') : true;

		if(!$flag)
			$this->form_validation->set_message('validate_referenceid', 'Reference id is mandatory field.');

		return $flag;
	}

	public function validate_target_accountid($target_accountid) {
		$payment_type = intval($this->input->post('payment_type'));

		$flag = ($payment_type!==5 && $payment_type!==6 && $payment_type!==7) ? intval($target_accountid)>0 : true;

		if(!$flag)
			$this->form_validation->set_message('validate_target_accountid', 'Depositing account number is mandatory.');

		return $flag;
	}

	public function validate_accountno($accountno) {
		$payment_type = intval($this->input->post('payment_type'));
		
		$flag = ($payment_type===1) ? (intval($accountno)>0) : true;

		if(!$flag)
			$this->form_validation->set_message('validate_accountno', 'Account No is mandatory for Cheque & Draft payment mode.');

		return $flag;
	}

	public function validate_bank($bank) {
		$payment_type = intval($this->input->post('payment_type'));
		$flag = ($payment_type===1 || $payment_type===2 || $payment_type===3 || 
			$payment_type===4 || $payment_type===8 || $payment_type===9 || $payment_type===10) ? ($bank!=='' && $bank!==NULL) : true;

		if(!$flag)
			$this->form_validation->set_message('validate_bank', 'Bank is mandatory field.');

		return $flag;
	}

	public function validate_branch($branch) {
		$payment_type = intval($this->input->post('payment_type'));
		$flag = ($payment_type===1 || $payment_type===2 || $payment_type===3 || 
			$payment_type===4 || $payment_type===8 || $payment_type===9 || $payment_type===10) ? ($branch!=='' && $branch!==NULL) : true;

		if(!$flag)
			$this->form_validation->set_message('validate_branch', 'Branch is mandatory field.');

		return $flag;
	}
	
	public function do_login()
	{
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {
			 $this->form_validation->set_rules('mobile','Mobile','required|numeric|xss_clean|max_length[10]|min_length[10]');
			 $this->form_validation->set_rules('password','Password','required|xss_clean');
			 
			 
			 if(!$this->form_validation->run()) 
			 {
				$json = array(                
					'mobile' => form_error('mobile', '<div class="error">', '</div>'),                               
					'password' => form_error('password', '<div class="error">', '</div>')											
				);
			 }
			 else
			 {
				if(!NEW_FLOW) {
					$json = $this->do_checkLogin();
				}
				else {
					$json = $this->do_checkNewLogin();
				}
			 }
			 echo json_encode($json);	 
		 }
	}

	private function do_checkNewLogin() {
		try
		{
			$company = $this->session->userdata('company');

			$arr = array(
				'mobile'=>$this->input->post('mobile'),						 
				'password'=>$this->input->post('password'),
				'companyid'=>$company["id"]
			);

			$result = $this->User_Model->newlogin($arr);
			if($result==true)
			{	
				$this->session->set_userdata('current_user',$result);
				$this->session->set_userdata('user_id',$result['user_id']);
				$this->session->set_userdata('name',$result['name']);
				$json["success"]="Login Successfully";
				if($this->session->userdata('returnurl')) {
					$json["returnurl"] = $this->session->userdata('returnurl');
					$this->session->unset_userdata('returnurl');
				}
				if($this->session->userdata('no_of_person')) {
					$json["no_of_person"] = $this->session->userdata('no_of_person');
				}
			}
			else {
				$json["error"]="Either wrong mobile number/email, password Or Your account is not yet approved";
			}
		}
		catch(Exception $ex) {
			var_dump($ex->getMessage());
			$json["error"] = $ex->getMessage();
		}

		return $json;
	}

	private function do_checkLogin() {
		try
		{
			$arr = array(
				'mobile'=>$this->input->post('mobile'),						 
				'password'=>$this->input->post('password')						 
				);
			//$result = $this->User_Model->login($arr);

			$arr1 = array(
						'mobile'=>$this->input->post('mobile'),						 
						'password'=>$this->input->post('password'),
						'active'=>'1'	
						);
						
			$result = $this->User_Model->login($arr);
			$result1 = $this->User_Model->login($arr1);
			if($result==true && $result1==true)
			{	
				$this->session->set_userdata('user_id',$result['user_id']);
				$this->session->set_userdata('name',$result['name']);
				$json["success"]="Login Successfully";
				if($this->session->userdata('returnurl')) {
					$json["returnurl"] = $this->session->userdata('returnurl');
				}
				if($this->session->userdata('no_of_person')) {
					$json["no_of_person"] = $this->session->userdata('no_of_person');
				}
			}
			else if($result==true && $result1==false)
				$json["error"]="Your Account is not Approved";
			else
				$json["error"]="Wrong Mobile No. / Password";
		}
		catch(Exception $ex) {
			var_dump($ex->getMessage());
			$json["error"] = $ex->getMessage();
		}

		return $json;
	}
	
	public function uploadimg()
	{
		
		 if ($this->session->userdata('user_id')) 
		{
			$validextensions = array("png","jpeg","bmp","jpg","gif","JPG","PNG","JPEG","BMP","GIF");
			$temporary = explode(".", $_FILES["profile_image"]["name"]); 
			$file_extension = end($temporary);
			if ($_FILES["profile_image"]["size"] <2097152) 
			{

				if(in_array($file_extension,$validextensions))
				{
					$filename ='user_'.rand(10000, 990000) . '_' . time() . '.' . $file_extension;				
					$sourcePath = $_FILES['profile_image']['tmp_name'];   
					$targetPath = 'upload/'.$filename ;  
					$thumbPath = 'upload/thumb/'.$filename ; 
					if(move_uploaded_file($sourcePath,$targetPath))
					{
					  
					  
					  $this->load->library('image_lib');
					  $config['image_library'] = 'gd2';
					  $config['source_image'] = $targetPath;    
					  $config['maintain_ratio'] = true;
					  $config['width'] =250;
					  $config['height'] = 300;
					  $config['new_image'] = $thumbPath;               
					  $this->image_lib->initialize($config);
					  if(!$this->image_lib->resize())
					  { 
						echo $this->image_lib->display_errors();
					  }

					     $arr = array('profile_image' => $filename);							 							 					 														 
						 $result = $this->User_Model->update($arr,$this->session->userdata('user_id'));
						 if($result)	
						 {							 
							$data["success"]["msg"]="Image Uploaded Successfully";
					        $data["success"]["file_name"]=$filename;
						 }
						 else
							 $json["error"]=false;
					}  
					else 
					  $data["error"]="file not uploaded";	 
				} 
				else
				{
				  $data["error"]="Unsupported File Type !!!";
				}



			}
			else
			{
				$data["error"]="File is too large,upload up to 2MB file";
			}
				
			echo json_encode($data);
	    }		  
	    else
		{
			 redirect('admin');
		}
	  
	
	}
	
	
	/*public function addticket()
	{
		
		 if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		 {
			 if(isset($_POST["departure_date_time1"]) && !empty($_POST["departure_date_time1"]) && isset($_POST["arrival_date_time1"]) && !empty($_POST["arrival_date_time1"]))
			 {
				 $this->form_validation->set_rules('ticket_no', 'Ticket No.', 'required|trim|xss_clean|callback_unique_ticket_no');			 
				 $this->form_validation->set_rules('pnr','PNR','required|trim|xss_clean|callback_unique_pnr');
				 $this->form_validation->set_rules('trip_type','Trip Type','required|trim|xss_clean');
				 $this->form_validation->set_rules('departure_date_time','Departure Date Time','required|trim|xss_clean');			 
				 $this->form_validation->set_rules('arrival_date_time', 'Arrival Date Time', 'required|trim|xss_clean');			 
				 $this->form_validation->set_rules('departure_date_time1','Departure Date Time','required|trim|xss_clean');			 
				 $this->form_validation->set_rules('arrival_date_time1', 'Arrival Date Time', 'required|trim|xss_clean');				 
				 $this->form_validation->set_rules('price','Price','required|trim|xss_clean');								
				 $this->form_validation->set_rules('total','Total','required|trim|xss_clean');
				 $this->form_validation->set_rules('no_of_person','No. of Person','required|trim|xss_clean');
				 $this->form_validation->set_rules('class','Class','required|trim|xss_clean');
			 }
			 else
			 {
				 $this->form_validation->set_rules('ticket_no', 'Ticket No.', 'required|trim|xss_clean|callback_unique_ticket_no');			 
				 $this->form_validation->set_rules('pnr','PNR','required|trim|xss_clean|callback_unique_pnr');
				 $this->form_validation->set_rules('trip_type','Trip Type','required|trim|xss_clean');
				 $this->form_validation->set_rules('departure_date_time','Departure Date Time','required|trim|xss_clean');			 
				 $this->form_validation->set_rules('arrival_date_time', 'Arrival Date Time', 'required|trim|xss_clean');			 						 
				 $this->form_validation->set_rules('price','Price','required|trim|xss_clean');
				
				 $this->form_validation->set_rules('total','Total','required|trim|xss_clean');
				 $this->form_validation->set_rules('no_of_person','No. of Person','required|trim|xss_clean');
				 $this->form_validation->set_rules('class','Class','required|trim|xss_clean');
			 }
			 if(!$this->form_validation->run()) 
			 {
				    if(isset($_POST["departure_date_time1"]) && !empty($_POST["departure_date_time1"]) && isset($_POST["arrival_date_time1"]) && !empty($_POST["arrival_date_time1"]))
			        {
						$json = array(
							'ticket_no' => form_error('ticket_no', '<div class="error">', '</div>'),					              
							'pnr' => form_error('pnr', '<div class="error">', '</div>'),
							'trip_type' => form_error('trip_type', '<div class="error">', '</div>'),
							'departure_date_time' => form_error('departure_date_time', '<div class="error">', '</div>'),					              
							'arrival_date_time' => form_error('arrival_date_time', '<div class="error">', '</div>'),
							'departure_date_time1' => form_error('departure_date_time1', '<div class="error">', '</div>'),					              
							'arrival_date_time1' => form_error('arrival_date_time1', '<div class="error">', '</div>'),
							'price' => form_error('price', '<div class="error">', '</div>'),					              						
							'total' => form_error('total', '<div class="error">', '</div>'),
							'no_of_person' => form_error('no_of_person', '<div class="error">', '</div>'),
							'class' => form_error('class', '<div class="error">', '</div>')
						);
				    }
					else
					{
						$json = array(
							'ticket_no' => form_error('ticket_no', '<div class="error">', '</div>'),					              
							'pnr' => form_error('pnr', '<div class="error">', '</div>'),
							'trip_type' => form_error('trip_type', '<div class="error">', '</div>'),
							'departure_date_time' => form_error('departure_date_time', '<div class="error">', '</div>'),					              
							'arrival_date_time' => form_error('arrival_date_time', '<div class="error">', '</div>'),							
							'price' => form_error('price', '<div class="error">', '</div>'),					              							
							'total' => form_error('total', '<div class="error">', '</div>'),
							'no_of_person' => form_error('no_of_person', '<div class="error">', '</div>'),
							'class' => form_error('class', '<div class="error">', '</div>')
						);
					}
			 }
			 else
			 {
				  if(isset($_POST["refundable"]))
                    $refundable="Y";
                  else
                   	$refundable="N";
				  if(isset($_POST["departure_date_time1"]) && !empty($_POST["departure_date_time1"]) && isset($_POST["arrival_date_time1"]) && !empty($_POST["arrival_date_time1"]))
			      {
				        $arr = array('ticket_no' => $this->input->post('ticket_no'),							 
							 'pnr'=>$this->input->post('pnr'),
							 'source'=>$this->input->post('source'),
							 'destination'=>$this->input->post('destination'),
							 'source1'=>$this->input->post('source1'),
							 'destination1'=>$this->input->post('destination1'),
							 'trip_type'=>$this->input->post('trip_type'),							
							 'sale_type'=>$this->input->post('sale_type'),							 						
							 'airline' => $this->input->post('airline'),
							 'departure_date_time' => $this->input->post('departure_date_time'),	
							 'arrival_date_time'=>$this->input->post('arrival_date_time'),
							 'flight_no'=>$this->input->post('flight_no'),
							 'terminal'=>$this->input->post('terminal'),
							 'departure_date_time1' => $this->input->post('departure_date_time1'),
							 'arrival_date_time1'=>$this->input->post('arrival_date_time1'),
							 'flight_no1'=>$this->input->post('flight_no1'),
							 'terminal1'=>$this->input->post('terminal1'),
							 'price' => $this->input->post('price'),
							
							 'markup'=>$this->input->post('markup'),
							 'total' => $this->input->post('total'),
							 'user_id'=>$this->session->userdata('user_id'),
							 'refundable'=>$refundable,
							 'no_of_person'=>$this->input->post('no_of_person'),
							 'max_no_of_person'=>$this->input->post('no_of_person'),
							 'class'=>$this->input->post('class')
							 );
				  }
                  else
				  {
					  $arr = array('ticket_no' => $this->input->post('ticket_no'),							 
							 'pnr'=>$this->input->post('pnr'),
							 'source'=>$this->input->post('source'),
							 'destination'=>$this->input->post('destination'),							 
							 'trip_type'=>$this->input->post('trip_type'),							
							 'sale_type'=>$this->input->post('sale_type'),							 							
							 'airline' => $this->input->post('airline'),
							 'arrival_date_time'=>$this->input->post('arrival_date_time'),
							 'departure_date_time' => $this->input->post('departure_date_time'),
							 'flight_no'=>$this->input->post('flight_no'),
							 'terminal'=>$this->input->post('terminal'),
							 'price' => $this->input->post('price'),	
							
							 'markup'=>$this->input->post('markup'),
							 'total' => $this->input->post('total'),
							 'user_id'=>$this->session->userdata('user_id'),
							 'refundable'=>$refundable,
							 'no_of_person'=>$this->input->post('no_of_person'),
							 'max_no_of_person'=>$this->input->post('no_of_person'),
							 'class'=>$this->input->post('class')
							 );
				  }					  
						 
				 $result = $this->User_Model->save("tickets_tbl",$arr);
				 if($result==true)			 
					$json["success"]="Ticket Added Successfully";
				 else
					 $json["error"]=false;
			 }
			 echo json_encode($json);	
		}
		 		 
	}*/
	
	
	public function submit_testimonial()
	{
	    if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		 {
		         $arr = array(						 
    						 'date'=>date("Y-m-d h:i:s"),
    						 'user_id'=>$this->session->userdata('user_id'),
    						 'description'=>$this->input->post('description')					
    						 );	
    						 $result = $this->User_Model->save("testimonials_tbl",$arr);
    						 if($result)
    						 {
    						    $this->session->set_flashdata('msg', 'Testimonial Added Successfully');							
							    redirect("/user");							
							    $this->session->flashdata('msg');
    						 }
    						 else
    						 {
    							 echo $this->db->_error_message();die();
    						 }
		 }
	}
	
	
	public function submit_ticket()
	{
		$company = $this->session->userdata('company');
		$companyid = $company["id"];
	
		if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		 {
			if(isset($_POST["refundable"]))
				$refundable="Y";
			else
				$refundable="N";
			
			
			$dateDiff = strtotime($this->input->post('dt_to'))-strtotime($this->input->post('dt_from'));
			$years = floor($dateDiff / (365*60*60*24));
			$months = floor(($dateDiff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($dateDiff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
		    $dtfrom=date('d', strtotime($this->input->post('dt_from')));
		    $dtto=date('d', strtotime($this->input->post('dt_to')));
			$sale_type=$this->input->post('sale_type');
			$diff=$dtto-$dtfrom;
			$diff++;
			if($this->input->post('trip_type')=="ONE" )
			{
				        $stops_name=""; 
				        if($this->input->post('no_of_stops')>0)
						{
							foreach($this->input->post('stop_name') as $key=>$value)
							{
								if(empty($stops_name))
									$stops_name=$value;
								else
									$stops_name.=",".$value;
							}
						}
						else
						{
							$stops_name="";
						}
						
				        $j=0;
						for($i=$dtfrom;$i<=$dtto;$i++,$j++)
						{
    						 $dt=date('Y-m-d', strtotime($this->input->post('dt_from').' +'.$j.' day'));
    						 $departure_date_time=$dt." ".$this->input->post('hh').":".$this->input->post('mm').":00";
    						 $arrival_date_time=$dt." ".$this->input->post('hh1').":".$this->input->post('mm1').":00";
    						 
							 $class=isset($_POST["class"])?$_POST["class"]:"";
							 //$availibility=isset($_POST["availibility"])?$_POST["availibility"]:"";
    					     
							 $arr = array(						 
    						 'pnr'=>$_POST["pnr"][$j],
    						 'source'=>$this->input->post('source'),
    						 'destination'=>$this->input->post('destination'),					
    						 'trip_type'=>$this->input->post('trip_type'),												 					 					
    						 'airline' => $this->input->post('airline'),
    						 'aircode' => $this->input->post('aircode'),
    						 'class'=>$class,
    						 'departure_date_time' => $departure_date_time,
    						 'arrival_date_time'=>$arrival_date_time,
    						 'flight_no'=>$this->input->post('flight_no'),
    						 'terminal'=>$this->input->post('terminal'),					 					 
    						 'terminal1'=>$this->input->post('terminal1'),
    						 'price' => $_POST["price"][$j],
							 'discount' => 0,
    						 'markup'=>0,
    						 'total' => $_POST["total"][$j],
    						 'user_id'=>$this->session->userdata('user_id'),
    						 'refundable'=>$refundable,
    						 'no_of_person'=>$_POST["no_of_person"][$j],
    						 'max_no_of_person'=>$_POST["no_of_person"][$j],					 
    						 'remarks'=>$this->input->post('remarks'),
    						 'no_of_stops'=>$this->input->post('no_of_stops'),
							 'stops_name'=>$stops_name,
							  'sale_type'=>$sale_type
							 //'availibility'=>$availibility
    						 );	
    						 $ticket_id = $this->User_Model->save("tickets_tbl",$arr);
							 
						  
						}
						 if($ticket_id)
						 {
							    $user['user_details']=$this->User_Model->user_details();
					            $result["flight"]=$this->Search_Model->flight_details($ticket_id, $companyid);  
								$data = array(				            
										 'name' => "OXYTRA",
										 'email'=>$user['user_details'][0]["email"],
										 'msg'=>"You have added ".$diff." (ONE WAY) ticket for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR  Ticket No.(".$ticket_id.")",
										 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
										 'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
										 );
								$this->send("Ticket Added",$data);
								
								
								$data1 = array(				            
										 'name' => $user['user_details'][0]["name"],
										 'email'=>$user['user_details'][0]["email"],
										 'mobile'=>$user['user_details'][0]["mobile"],
										 'msg'=>$diff." New (ONE WAY) ticket for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR Ticket No.(".$ticket_id.")",
										 'user_id'=> $user['user_details'][0]["user_id"],
										 'msg1'=>'',
										 'msg2'=>""							 
										 );
										 
							 $this->adminsend("Ticket Added",$data1);	
							 
							$no=$user['user_details'][0]["mobile"];
							$msg=$diff." New (ONE WAY) Ticket Added";
							$this->send_message($no,$msg);


							$no="9800412356";
							$msg=$diff." New (ONE WAY) ticket Added by User ID : ".$user['user_details'][0]["user_id"]."";
							$this->send_message($no,$msg);
							
							
							 $this->session->set_flashdata('msg', 'Ticket Added Successfully');							
							 redirect("/user");							
							 $this->session->flashdata('msg');
						 }
						 else
						 {
							 echo $this->db->_error_message();die();
						 }
											
													
			}
			else
			{
			           
				$stops_name1=""; 
				if($this->input->post('no_of_stops1')>0)
				{
					foreach($this->input->post('stop_name1') as $key=>$value)
					{
						if(empty($stops_name1))
							$stops_name1=$value;
						else
							$stops_name1.=",".$value;
					}
				}
				else
				{
					$stops_name1="";
				}
				
				$stops_name=""; 
				if($this->input->post('no_of_stops')>0)
				{
					foreach($this->input->post('stop_name') as $key=>$value)
					{
						if(empty($stops_name))
							$stops_name=$value;
						else
							$stops_name.=",".$value;
					}
				}
				else
				{
					$stops_name="";
				}
				 $class=isset($_POST["class"])?$_POST["class"]:"";
				 $class1=isset($_POST["class1"])?$_POST["class1"]:"";
				 //$availibility=isset($_POST["availibility"])?$_POST["availibility"]:"";
							 
				 $arr = array(						 
				 'pnr'=>$this->input->post('pnr'),
				 'source'=>$this->input->post('source'),
				 'destination'=>$this->input->post('destination'),
				 'source1'=>$this->input->post('destination'),
				 'destination1'=>$this->input->post('source'),		
				 'trip_type'=>$this->input->post('trip_type'),												 					 					
				 'airline' => $this->input->post('airline'),
				 'airline1' => $this->input->post('airline1'),
				 'aircode' => $this->input->post('aircode'),
				 'aircode1' => $this->input->post('aircode1'),
				 'class'=>$class,
				 'class1'=>$class1,
				 'departure_date_time' => $this->input->post('departure_date_time'),
				 'arrival_date_time'=>$this->input->post('arrival_date_time'),
				 'departure_date_time1' => $this->input->post('departure_date_time1'),
				 'arrival_date_time1'=>$this->input->post('arrival_date_time1'),
				 'flight_no'=>$this->input->post('flight_no'),
				 'flight_no1'=>$this->input->post('flight_no1'),
				 'terminal'=>$this->input->post('terminal'),					 					 
				 'terminal1'=>$this->input->post('terminal1'),
				 'terminal2'=>$this->input->post('terminal2'),					 					 
				 'terminal3'=>$this->input->post('terminal3'),
				 'price' => $this->input->post('price'),					 
				 'markup'=>0,
				 'discount' => 0,
				 'total' => $this->input->post('total'),
				 'user_id'=>$this->session->userdata('user_id'),
				 'refundable'=>$refundable,
				 'no_of_person'=>$this->input->post('no_of_person'),
				 'max_no_of_person'=>$this->input->post('no_of_person'),					 
				 'remarks'=>$this->input->post('remarks'),
				 'no_of_stops'=>$this->input->post('no_of_stops'),
				 'no_of_stops1'=>$this->input->post('no_of_stops1'),
				 'stops_name'=>$stops_name,
				 'stops_name1'=>$stops_name1,
				 'sale_type'=>$sale_type
				 //'availibility'=>$availibility
				 );	
				 
				 $ticket_id = $this->User_Model->save("tickets_tbl",$arr);
				 if($ticket_id)
				 {
					            $user['user_details']=$this->User_Model->user_details();
					            $result["flight"]=$this->Search_Model->flight_details($ticket_id, $companyid);  
								$data = array(				            
										 'name' => "OXYTRA",
										 'email'=>$user['user_details'][0]["email"],
										 'msg'=>"You have added ".$diff." (RETURN) ticket for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR for ".$this->input->post('no_of_person')." person Ticket No.(".$ticket_id.")",
										 'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
										 'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
										 );
								$this->send("Ticket Added",$data);
								
								
								$data1 = array(				            
										 'name' => $user['user_details'][0]["name"],
										 'email'=>$user['user_details'][0]["email"],
										 'mobile'=>$user['user_details'][0]["mobile"],
										 'msg'=>$diff." New (RETURN) ticket added for  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$this->input->post('total')." INR for ".$this->input->post('no_of_person')." person Ticket No.(".$ticket_id.")",
										 'user_id'=> $user['user_details'][0]["user_id"],
										 'msg1'=>'',
										 'msg2'=>""							 
										 );
										 
					 $this->adminsend("Ticket Added",$data1); 
					 
					$no=$user['user_details'][0]["mobile"];
					$msg=$diff." New (RETURN) Ticket Added";
					$this->send_message($no,$msg);


					$no="9800412356";
					$msg=$diff." New (RETURN) ticket Added by User ID : ".$user['user_details'][0]["user_id"]."";
					$this->send_message($no,$msg);
					 $this->session->set_flashdata('msg', 'Ticket Added Successfully');							
					 redirect("/user");							
					 $this->session->flashdata('msg');
				 }
				 else
				 {
					 echo $this->db->_error_message();die();
				 }
    						 
						  
						
			}
		
		}
        else
		{
			redirect('/user');
		}			
	}
	public function import_request_ticket()
	{
		$company = $this->session->userdata('company');
		$companyid = $company["id"];
	
		if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		{									
			$sale_type="quote";			
			if($this->input->post('request_trip_type')=="ONE" )
			{
				if(isset($_FILES["request_file"]["name"]))
				{
					$ticket_id = 0;
					$path = $_FILES["request_file"]["tmp_name"];
					$object = PHPExcel_IOFactory::load($path);
					$diff=0;
					foreach($object->getWorksheetIterator() as $worksheet)
					{
						
						$highestRow = $worksheet->getHighestRow();
						$highestColumn = $worksheet->getHighestColumn();
						for($row=2; $row<=$highestRow; $row++)
						{
							$source = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$destination = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
							$date = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$available = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
							
							$CI =   &get_instance();	
							$query = $CI->db->get_where('city_tbl', array('code' => $source));				
							$source_arr=$query->result_array();
							$query = $CI->db->get_where('city_tbl', array('code' => $destination));				
							$destination_arr=$query->result_array();
							if(!empty($source) && !empty($destination) && !empty($date) && !empty($available))
							{
								$data = 
								array
								(
									'source'		=>	$source_arr[0]["id"],
									'destination'	=>  $destination_arr[0]["id"],
									'departure_date_time'			=>	date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date)),
									'available'		=>	$available,
									'no_of_person'=>  "10000",
									'max_no_of_person'=> "10000",
									'trip_type'=>$this->input->post("request_trip_type"),
									'sale_type'=>$sale_type,
									'user_id'=>$this->session->userdata('user_id'),
									'approved'=>0
								);
								
								$departure_date_time=date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date));
								$departure_date_time=$departure_date_time." 00:00:00";
								$CI =   &get_instance();        
								$query  =   $CI->db->get_where('tickets_tbl',array('source =' =>$source_arr[0]["id"],'destination'=>$destination_arr[0]["id"],'departure_date_time'=>$departure_date_time,"trip_type"=>"ONE",'user_id'=>$this->session->userdata('user_id')));
								$num=$query->num_rows();
								if($num>0)
								{
									$ticket_arr=$query->result_array();
									$id=$ticket_arr[0]["id"];
									$ticket_id = $ticket_arr[0]["id"];
									$this->User_Model->update_table("tickets_tbl",$data,"id",$id);
								}
								else
									$ticket_id = $this->User_Model->save("tickets_tbl",$data);
								$diff++;
							}
						}
						
					}
					
					$user['user_details']=$this->User_Model->user_details();
					$result["flight"]=$this->Search_Model->flight_details($ticket_id, $companyid);  
					$data = array(				            
								'name' => "OXYTRA",
								'email'=>$user['user_details'][0]["email"],
								'msg'=>"You have added ".$diff." Request Mode (ONE WAY) ticket for  ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."",
								'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
								'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
								);
					$this->send("Ticket Added",$data);


					$data1 = array(				            
								'name' => $user['user_details'][0]["name"],
								'email'=>$user['user_details'][0]["email"],
								'mobile'=>$user['user_details'][0]["mobile"],
								'msg'=>$diff." New Request Mode (ONE WAY) ticket for  Added ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]." ",
								'user_id'=> $user['user_details'][0]["user_id"],
								'msg1'=>'',
								'msg2'=>""							 
								);
								
					$this->adminsend("Ticket Added",$data1);	

					$no=$user['user_details'][0]["mobile"];
					$msg=$diff." New (ONE WAY) Ticket Added";
					$this->send_message($no,$msg);


					$no="9800412356";
					$msg=$diff." Request (ONE WAY) ticket Added by:".$user['user_details'][0]["user_id"]." for ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."";
					$this->send_message($no,$msg);


					$this->session->set_flashdata('msg', 'Ticket Added Successfully');							
					redirect("/user");							
					$this->session->flashdata('msg');
					
					
				}
			}
			else
			{
				if(isset($_FILES["request_file"]["name"]))
				{
					$path = $_FILES["request_file"]["tmp_name"];
					$object = PHPExcel_IOFactory::load($path);
					$diff=0;
					foreach($object->getWorksheetIterator() as $worksheet)
					{
						
						$highestRow = $worksheet->getHighestRow();
						$highestColumn = $worksheet->getHighestColumn();
						for($row=2; $row<=$highestRow; $row++)
						{
							$source = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$destination = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
							$date = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$date1 = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
							$available = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							
							$CI =   &get_instance();	
							$query = $CI->db->get_where('city_tbl', array('code' => $source));				
							$source_arr=$query->result_array();
							$query = $CI->db->get_where('city_tbl', array('code' => $destination));				
							$destination_arr=$query->result_array();
							if(!empty($source) && !empty($destination) && !empty($date) && !empty($available))
							{
								$data = 
								array
								(
									'source'		=>	$source_arr[0]["id"],
									'destination'	=>  $destination_arr[0]["id"],
									'departure_date_time'			=>	date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date)),
									'departure_date_time1'			=>	date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date1)),
									'available'		=>	$available,
									'no_of_person'=>    "10000",
									'max_no_of_person'=> "10000",
									'trip_type'=>$this->input->post("request_trip_type"),
									'sale_type'=>$sale_type,
									'user_id'=>$this->session->userdata('user_id'),
									'approved'=>0
								);
								
								$departure_date_time=date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date));
								$departure_date_time=$departure_date_time." 00:00:00";
								
								$departure_date_time1=date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date1));
								$departure_date_time1=$departure_date_time1." 00:00:00";
								$CI =   &get_instance();        
								$query  =   $CI->db->get_where('tickets_tbl',array('source =' =>$source_arr[0]["id"],'destination'=>$destination_arr[0]["id"],'departure_date_time'=>$departure_date_time,'departure_date_time1'=>$departure_date_time1,"trip_type"=>"ROUND",'user_id'=>$this->session->userdata('user_id')));
								$num=$query->num_rows();
								if($num>0)
								{
									$ticket_arr=$query->result_array();													
									$id=$ticket_arr[0]["id"];																							
									$this->User_Model->update_table("tickets_tbl",$data,"id",$id);
								}
								else
									$this->User_Model->save("tickets_tbl",$data);
								$diff++;
							}
						}
						
					}
					
					
					$user['user_details']=$this->User_Model->user_details();
					$result["flight"]=$this->Search_Model->flight_details($ticket_id, $companyid);  
					$data = array(				            
								'name' => "OXYTRA",
								'email'=>$user['user_details'][0]["email"],
								'msg'=>"You have added ".$diff." Request Mode (RETURN) ticket for  ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]." ",
								'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> Your Tickets will available for sale',
								'msg2'=>"Enjoy! You are connected to no.1 Air Ticket booking site"
								);
					$this->send("Ticket Added",$data);


					$data1 = array(				            
								'name' => $user['user_details'][0]["name"],
								'email'=>$user['user_details'][0]["email"],
								'mobile'=>$user['user_details'][0]["mobile"],
								'msg'=>$diff." New Request Mode (RETURN) ticket for  Added ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."",
								'user_id'=> $user['user_details'][0]["user_id"],
								'msg1'=>'',
								'msg2'=>""							 
								);
								
					$this->adminsend("Ticket Added",$data1);	

					$no=$user['user_details'][0]["mobile"];
					$msg=$diff." New (RETURN) Ticket Added";
					$this->send_message($no,$msg);


					$no="9800412356";
					$msg=$diff." Request (RETURN) ticket Added by:".$user['user_details'][0]["user_id"]." for ".$source_arr[0]["city"]." to ".$destination_arr[0]["city"]."";
					$this->send_message($no,$msg);


					$this->session->set_flashdata('msg', 'Ticket Added Successfully');							
					redirect("/user");							
					$this->session->flashdata('msg');		
				}
			}
		}
        else
		{
			redirect('/user');
		}			
	}
	
	public function update_ticket()
	{
		if(($_SERVER['REQUEST_METHOD'] == 'POST') && $this->session->userdata('user_id'))
		{
			
			
			if(isset($_POST["refundable"]))
				$refundable="Y";
			else
				$refundable="N";	
			if($this->input->post('trip_type')=="ONE" )
			{
				            $stops_name=""; 
							if($this->input->post('no_of_stops')>0)
							{
								foreach($this->input->post('stop_name') as $key=>$value)
								{
									if(empty($stops_name))
										$stops_name=$value;
									else
										$stops_name.=",".$value;
								}
							}
							else
							{
								$stops_name="";
							}
    						 $departure_date_time=date("Y-m-d",strtotime($this->input->post('dt')))." ".$this->input->post('hh').":".$this->input->post('mm').":00";
    						 $arrival_date_time=date("Y-m-d",strtotime($this->input->post('dt')))." ".$this->input->post('hh1').":".$this->input->post('mm1').":00";
    						
    					     $arr = array(						 
    						 'pnr'=>$this->input->post('pnr'),
    						 'source'=>$this->input->post('source'),
    						 'destination'=>$this->input->post('destination'),					
    						 'trip_type'=>$this->input->post('trip_type'),												 					 					
    						 'airline' => $this->input->post('airline'),
    						 'aircode' => $this->input->post('aircode'),
    						 'class'=>$this->input->post('class'),
    						 'departure_date_time' => $departure_date_time,
    						 'arrival_date_time'=>$arrival_date_time,
    						 'flight_no'=>$this->input->post('flight_no'),
    						 'terminal'=>$this->input->post('terminal'),					 					 
    						 'terminal1'=>$this->input->post('terminal1'),
    						 'price' => $this->input->post('price'),					 
    						 'markup'=>0,
    						 'total' => $this->input->post('total'),
    						 'user_id'=>$this->session->userdata('user_id'),
    						 'refundable'=>$refundable,
    						 'no_of_person'=>$this->input->post('no_of_person'),
    						 'max_no_of_person'=>$this->input->post('no_of_person'),					 
    						 'remarks'=>$this->input->post('remarks'),
    						 'no_of_stops'=>$this->input->post('no_of_stops'),
							 "stops_name"=>$stops_name,
							 'availibility'=>$this->input->post('availibility'),
							 'available'=>$this->input->post('available')
    						 );	
    						 $result = $this->User_Model->update_table("tickets_tbl",$arr,"id",$this->input->post('ticket_id'));
    						 if($result)
							 {
								 
								
								 $this->session->set_flashdata('msg', 'Ticket Updated Successfully');							
								 redirect("/user");							
								 $this->session->flashdata('msg');
							 }
							 else
							 {
								 echo $this->db->_error_message();die();
							 }	
            }
            else
			{
				            $stops_name=""; 
							if($this->input->post('no_of_stops')>0)
							{
								foreach($this->input->post('stop_name') as $key=>$value)
								{
									if(empty($stops_name))
										$stops_name=$value;
									else
										$stops_name.=",".$value;
								}
							}
							else
							{
								$stops_name="";
							}
							
							$stops_name1=""; 
							if($this->input->post('no_of_stops1')>0)
							{
								foreach($this->input->post('stop_name1') as $key=>$value)
								{
									if(empty($stops_name1))
										$stops_name1=$value;
									else
										$stops_name1.=",".$value;
								}
							}
							else
							{
								$stops_name1="";
							}
				$arr = array(						 
				 'pnr'=>$this->input->post('pnr'),
				 'source'=>$this->input->post('source'),
				 'destination'=>$this->input->post('destination'),
				 'source1'=>$this->input->post('destination'),
				 'destination1'=>$this->input->post('source'),		
				 'trip_type'=>$this->input->post('trip_type'),												 					 					
				 'airline' => $this->input->post('airline'),
				 'airline1' => $this->input->post('airline1'),
				 'aircode' => $this->input->post('aircode'),
				 'aircode1' => $this->input->post('aircode1'),
				 'class'=>$this->input->post('class'),
				 'class1'=>$this->input->post('class1'),
				 'departure_date_time' => $this->input->post('departure_date_time'),
				 'arrival_date_time'=>$this->input->post('arrival_date_time'),
				 'departure_date_time1' => $this->input->post('departure_date_time1'),
				 'arrival_date_time1'=>$this->input->post('arrival_date_time1'),
				 'flight_no'=>$this->input->post('flight_no'),
				 'flight_no1'=>$this->input->post('flight_no1'),
				 'terminal'=>$this->input->post('terminal'),					 					 
				 'terminal1'=>$this->input->post('terminal1'),
				 'terminal2'=>$this->input->post('terminal2'),					 					 
				 'terminal3'=>$this->input->post('terminal3'),
				 'price' => $this->input->post('price'),					 
				 'markup'=>0,
				 'total' => $this->input->post('total'),
				 'user_id'=>$this->session->userdata('user_id'),
				 'refundable'=>$refundable,
				 'no_of_person'=>$this->input->post('no_of_person'),
				 'max_no_of_person'=>$this->input->post('no_of_person'),					 
				 'remarks'=>$this->input->post('remarks'),
				 'no_of_stops'=>$this->input->post('no_of_stops'),
				 'no_of_stops1'=>$this->input->post('no_of_stops1'),
				 'stops_name'=>$stops_name,
				 'stops_name1'=>$stops_name1,
				 'availibility'=>$this->input->post('availibility'),
				  'available'=>$this->input->post('available')
				 );	
				 $result = $this->User_Model->update_table("tickets_tbl",$arr,"id",$this->input->post('ticket_id'));
				 if($result)
				 {
					 $this->session->set_flashdata('msg', 'Ticket Updated Successfully');							
					 redirect("/user");							
					 $this->session->flashdata('msg');
				 }
				 else
				 {
					 echo $this->db->_error_message();die();
				 }	
				 
			}				
		}
        else
		{
			redirect('/user');
		}			
	}
	
	
	public function add_one_way()
	{
		if ($this->session->userdata('user_id')) 
		{
			//$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['city']=$this->User_Model->select("city_tbl");	
			$result['airline']=$this->User_Model->select("airline_tbl");
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);


		    $this->load->view('header1',$result);
			$this->load->view('ticket-form');
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function add_testimonial()
	{
		if ($this->session->userdata('user_id')) 
		{
			//$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		    $this->load->view('header1',$result);
			$this->load->view('testimonial-form');
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function add_return_ticket()
	{
		if ($this->session->userdata('user_id')) 
		{
			//$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['city']=$this->User_Model->select("city_tbl");	
			$result['airline']=$this->User_Model->select("airline_tbl");
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$result["setting"]=$this->Search_Model->company_setting($company["id"]);

		    $this->load->view('header1',$result);
			$this->load->view('return-ticket-form');
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function edit_ticket($id)
	{
		$company = $this->session->userdata('company');
		$companyid = $company["id"];

		if ($this->session->userdata('user_id')) 
		{
			$CI =   &get_instance();        
			$check = $CI->db->get_where('tickets_tbl', array('id' => $id));			
			if ($check->num_rows() > 0) 
			{
			   
				//$result["setting"]=$this->Search_Model->setting();
				$result["footer"]=$this->Search_Model->get_post(5);
				$result['city']=$this->User_Model->select("city_tbl");	
				$result['airline']=$this->User_Model->select("airline_tbl");
				$result["flight"]=$this->Search_Model->flight_details($id, $companyid); 
				$result['mywallet']= $this->getMyWallet();
				$company = $this->session->userdata("company");
				$result["setting"]=$this->Search_Model->company_setting($company["id"]);
	

				if($result["flight"][0]["trip_type"]=="ONE")
				{
					$this->load->view('header1',$result);
					$this->load->view('edit-ticket-form');
					$this->load->view('footer');
				}
				else
				{
					$this->load->view('header1',$result);
					$this->load->view('edit-return-ticket-form');
					$this->load->view('footer');
				}
			   
			}
			else
				redirect('/user');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function edit_testimonial($id)
	{
		if ($this->session->userdata('user_id')) 
		{
			$CI =   &get_instance();        
			$check = $CI->db->get_where('testimonials_tbl', array('id' => $id));			
			if ($check->num_rows() > 0) 
			{
			   
				//$result["setting"]=$this->Search_Model->setting();
				$result['mywallet']= $this->getMyWallet();
				$company = $this->session->userdata("company");
				$result["setting"]=$this->Search_Model->company_setting($company["id"]);
	
				$this->load->view('header1',$result);
				$this->load->view('ticket-form');
				$this->load->view('footer');
			}
			else
				redirect('/user');
		}
		else
		{
			redirect('/login');
			
		}
	}
	
	
	public function unique_ticket_no($ticket_no)
    {
        $CI =   &get_instance();        
        $check = $CI->db->get_where('tickets_tbl', array('ticket_no' => $ticket_no));
        
        if ($check->num_rows() > 0) 
		{
           
            $this->form_validation->set_message('unique_ticket_no', 'This Ticket No. already exists in our database');
            return false;
           
        }

        return TRUE;
    }
	
	public function unique_pnr($pnr)
    {
        $CI =   &get_instance();        
        $check = $CI->db->get_where('tickets_tbl', array('pnr' => $pnr));        
        if ($check->num_rows() > 0) 
		{           
            $this->form_validation->set_message('unique_pnr', 'This PNR already exists in our database');
            return false;           
        }
        return TRUE;
    }
	
	
	public function transaction()
	{
		$company = $this->session->userdata('company');
		$companyid = intval($company["id"]);
		$userid=$this->input->post('user');
		$partner_type = '';
		$account_type = 'wallet';
		$dt_from=$this->input->post('dt_from');
		if ($dt_from && $dt_from!== '') {
			$dt_from = date_create($dt_from." 00:00:00");
		}

		$dt_to=$this->input->post('dt_to');
		if ($dt_to && $dt_to!== '') {
			$dt_to = date_create($dt_to." 23:59:59");
		}

		$account_type = $this->input->post('account_type');
		if(!$account_type) {
			$account_type = "wallet";
		}

		$target_usertype = "";
		if($userid && !is_int($userid) && (startsWith($userid, 'WHL') || startsWith($userid, 'SPL'))) {
			if(startsWith($userid, 'WHL')) {
				$partner_type = 'WHL';
				$userid = str_replace('WHL', '', $userid);
				$target_usertype = 'WHL';
			}
			else if(startsWith($userid, 'SPL')) {
				$partner_type = 'SPL';
				$userid = str_replace('SPL', '', $userid);
				$target_usertype = 'SPL';
			}
		}

		if($userid && intval($userid) > 0) {
			$userid = intval($userid);
		}
		else {
			$userid = intval($this->session->userdata('user_id'));
		}

		if ($userid>0) 
		{ 
			if($target_usertype === '') {
				$target_user = $this->User_Model->get_userbyid($userid);
			}
			else {
				$partner_company = $this->User_Model->get('company_tbl', array('id' => $userid));
				if($partner_company && is_array($partner_company) && count($partner_company)>0) {
					$partner_company = $partner_company[0];

					//$primary_userid = $partner_company['primary_user_id'];
				}
				$target_user = $partner_company;
			}
			$result['user_details']=$this->User_Model->user_details();
			
			if($account_type === 'wallet') {
				$result['wallet_transaction']=$this->User_Model->wallet_transaction($userid, $dt_from, $dt_to);
				$result['account_transaction'] = [];
			}
			else if($account_type === 'account') {
				$result['wallet_transaction'] = [];
				if($target_usertype === '') {
					if(isset($result['user_details']) && count($result['user_details'])>0) {
						if(isset($target_user['is_admin'])) {
							$userdetails = $target_user; //$result['user_details'][0];
						}
						else {
							$userdetails = $result['user_details'][0];
						}
						if(intval($userdetails['is_admin']) === 1) {
							$result['account_transaction'] = $this->User_Model->get_account_transaction($companyid, (intval($userdetails['companyid'])==$companyid?-1:intval($userdetails['companyid'])), 0, $dt_from, $dt_to);
						}
						else {
							$result['account_transaction'] = $this->User_Model->get_account_transaction($companyid, $companyid, $userid, $dt_from, $dt_to);	
						}
					}
					else {
						$result['account_transaction'] = $this->User_Model->get_account_transaction($companyid, $userid, 0, $dt_from, $dt_to);
					}
				}
				else {
					$result['account_transaction'] = $this->User_Model->get_account_transaction($companyid, $userid, 0, $dt_from, $dt_to);
				}
			}
	        //$result["setting"]=$this->Search_Model->setting();
			$result["footer"]=$this->Search_Model->get_post(5);
			$result['mywallet']= $this->getMyWallet();
			$company = $this->session->userdata("company");
			$companyid = isset($company["id"])?intval($company["id"]):-1;
			$result["setting"]=$this->Search_Model->company_setting($companyid);
			
			$customers = $this->Search_Model->get_customers($companyid, 1, 'B2B');
			$result["agents"]=$customers;
			$customers = $this->Search_Model->get_customers($companyid, 1, 'B2C');
			$result["retail"]=$customers;

			$wholesalers = $this->Admin_Model->get_wholesalers($companyid);
			$suppliers = $this->Admin_Model->get_suppliers($companyid);

			$result["wholesalers"]=$wholesalers;
			$result["suppliers"]=$suppliers;

			$result["company"]=$company;
			$result["userid"]=$userid;
			$result["target_usertype"] = $target_usertype;
			$result["account_type"] = $account_type;
			$result["target_user"] = $target_user;

		    $this->load->view('header1',$result);
			$this->load->view('user_transaction',$result);
			$this->load->view('footer');
		}
		else
		{
			redirect('/login');
		}
	}
	
	public function updatepnr()
	{
		if ($this->session->userdata('user_id')) 
		{ 
			$id=$this->input->post('hid_refrence_booking_id');
			$pnr=$this->input->post('pnr');
			$arr = array('pnr' => $pnr);					 							
            $where=array("refrence_id"=>$id);
			$result = $this->Search_Model->update("customer_information_tbl",$arr,$where);
			
            $where=array("id"=>$id);
			$result = $this->Search_Model->update("refrence_booking_tbl",$arr,$where);
		    //echo $this->db->last_query();die();
			if($result==true)			 
			{
				$this->session->set_flashdata('msg', 'PNR Updated Successfully');
				redirect("user/booking-orders");
				$this->session->flashdata('msg');
			}
		}
		else
		{
			redirect('/login');
		}
		
	}
	
	public function forgot_password()
	{
		$companyinfo = $this->get_companyinfo();

		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$mobile = $this->input->post('mobile');
			$companyname = $companyinfo['display_name'];
			$companyid = intval($companyinfo['id']);
			$this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]');				 			 
			if(!$this->form_validation->run()) 
			{
				$json = array('forgot_mobile' => form_error('mobile', '<div class="error">', '</div>') );       				        																			
			}
			else
			{
				log_message('info', "Forget password triggered with number: $mobile | Company: $companyname");
				try
				{
					$CI =   &get_instance();        
					$check = $CI->db->get_where('user_tbl', array('mobile' => $mobile, 'companyid' => $companyid));
					if ($check->num_rows() == 0) 
					{
							
						$json = array('forgot_mobile' =>  '<div class="error">This Mobile No. does not exist in our database</div>');       
					}
					else
					{
						$result=$check->result();
						$password=$result[0]->password;
						$name=$result[0]->name;
						$json["success"] = "Your Password is sent to your registered Mobile No.";
						log_message('info', "Password found and sent to : $mobile | Company: $companyname");
						
						$msg='Dear '.$name.' '.$password." is your password. Thanks, $companyname";
						$this->send_message($mobile,$msg);
					}
				}
				catch(Exception $ex) {
					log_message('error', "Forget Password | Error => $ex");
				}
			}
			echo json_encode($json);
		}
	}
	
	public function send_login_otp()
	{
		$companyinfo = $this->get_companyinfo();
		$companyname = $companyinfo['display_name'];
		$companyid = intval($companyinfo['id']);

		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$this->form_validation->set_rules('mobile','Mobile No.','required|numeric|xss_clean|max_length[10]|min_length[10]');				 			 
			if(!$this->form_validation->run()) 
			{
				$json = array('otp_mobile' => form_error('mobile', '<div class="error">', '</div>') );       				        																			
			}
			else
			{
				$CI =   &get_instance();        
				$check = $CI->db->get_where('user_tbl', array('mobile' => $this->input->post('mobile')));				
				if ($check->num_rows() == 0) 
				{
						
					$json = array('otp_mobile' =>  '<div class="error">This Mobile No. does not exist in our database</div>');       
					
				}
				else
				{
					$result=$check->result();
					$login_mobile=$result[0]->mobile;
					$login_password=$result[0]->password;
					$name=$result[0]->name;
					
					$json["success"] =  "Your Login OTP is sent to your Mobile No.";
					
					$no=$this->input->post('mobile');
					$login_otp=rand(111111,999999);
					
					$this->session->set_userdata('login_otp',$login_otp);
					$this->session->set_userdata('login_mobile',$login_mobile);
					$this->session->set_userdata('login_password',$login_password);
					
					$msg='Dear '.$name.' '.$login_otp." is your Login OTP. Thanks, $companyname";
					$this->send_message($no,$msg);
				}
			}
			echo json_encode($json);
		}		 		 		
	}

	public function pg_response_atom() {
		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$transactionResponse = new TransactionResponse();
			$transactionResponse->setRespHashKey("KEYRESP123657234");
			
			$message = '';
			$error = '';
			$status = 'failure';
			if($transactionResponse->validateResponse($_POST)){
				// echo "Transaction Processed <br/>";
				// print_r($_POST);
				$message = 'Transaction successfully made';
				$status = 'success';
			} else {
				// echo "Invalid Signature";
				$message = 'Transaction failed';
				$error = $this->input->post('desc');
			}

			$pg_transid = $this->input->post('mmp_txn');
			$mode = 'test';
			//$status = $this->input->post('f_code');
			$txnid = $this->input->post('mer_txn');
			$amount = floatval($this->input->post('amt'));
			$card_category = $this->input->post('discriminator');
			$trans_date = date('Y-m-d H:i:s', strtotime($this->input->post('date')));
			$response_json = json_encode($_REQUEST, JSON_HEX_QUOT);		
			
			$payload = $this->process_pg_response(array(
				'mihpayid' => $pg_transid, 
				'mode' => $mode, 
				'status' => $status, 
				'txnid' => $txnid, 
				'amount' => $amount, 
				'card_category' => $card_category, 
				'trans_date' => $trans_date, 
				'error' => $error, 
				'message' => $message, 
				'response_json' => $response_json
			));

			log_message('debug', 'Response from payment gateway => '.json_encode($payload));

			$this->session->set_flashdata('payment_response', $payload);
			redirect('/user');
		}
		else {
			redirect('/user');
		}
	} 

	public function pg_response() {
		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$pg_transid = $this->input->post('mihpayid');
			$mode = $this->input->post('mode');
			$status = $this->input->post('status');
			$txnid = $this->input->post('txnid');
			$amount = $this->input->post('net_amount_debit');
			$card_category = $this->input->post('cardCategory');
			$trans_date = date('Y-m-d H:i:s', strtotime($this->input->post('addedon')));
			$error = $this->input->post('error');
			$message = $this->input->post('field9');
			$response_json = json_encode($_REQUEST, JSON_HEX_QUOT);

			$this->process_pg_response(array(
				'mihpayid' => $pg_transid, 
				'mode' => $mode, 
				'status' => $status, 
				'txnid' => $txnid, 
				'amount' => $amount, 
				'card_category' => $card_category, 
				'trans_date' => $trans_date, 
				'error' => $error, 
				'message' => $message, 
				'response_json' => $response_json
			));
		}
	}

	public function addtowallet() {
		if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$this->form_validation->set_rules('payment_type', 'Payment Type', 'required|callback_validate_paymenttype');
			$this->form_validation->set_rules('cheque_no', 'Cheque No', 'callback_validate_cheque');
			$this->form_validation->set_rules('draft_no', 'Draft No', 'callback_validate_draft');
			$this->form_validation->set_rules('bank', 'Bank', 'callback_validate_bank');
			$this->form_validation->set_rules('branch', 'Branch', 'callback_validate_branch');
			$this->form_validation->set_rules('account_no', 'Account No', 'callback_validate_accountno');
			$this->form_validation->set_rules('amount','Amount','required|trim|xss_clean|max_length[8]|min_length[3]');
			$this->form_validation->set_rules('reference_id','Reference No','callback_validate_referenceid');
			$this->form_validation->set_rules('target_accountid','Reference No','callback_validate_target_accountid');
			
			if(!$this->form_validation->run()) 
			{
			   $json = array(
				   'payment_type' => form_error('payment_type', '<div class="error">', '</div>'),
				   'cheque_no' => form_error('cheque_no', '<div class="error">', '</div>'),
				   'draft_no' => form_error('draft_no', '<div class="error">', '</div>'),
				   'amount' => form_error('amount', '<div class="error">', '</div>'),
				   'reference_id' => form_error('reference_id', '<div class="error">', '</div>'),
				   'bank' => form_error('bank', '<div class="error">', '</div>'),
				   'branch' => form_error('branch', '<div class="error">', '</div>'),
				   'account_no' => form_error('account_no', '<div class="error">', '</div>'),
				   'target_accountid' => form_error('target_accountid', '<div class="error">', '</div>')
			   );

			   redirect('/user');
			}
			else
			{
				$company = $this->session->userdata('company');
				$user_id = $this->session->userdata('user_id');
				$current_user = $this->session->userdata('current_user');
				$companyid = $current_user["companyid"];
				$companyname = $company['display_name'];
				$payment_gws = $this->Search_Model->get('thirdparty_api_tbl', array('category' => "'PAYMENT_GATEWAY'"));
				$result["company_setting"]=$this->Search_Model->company_setting($companyid);
				$result["payment_gateway"] = json_decode($result["company_setting"][0]['payment_gateway'], true);

				$payment_type = intval($this->input->post('payment_type'));
				$amount = floatval($this->input->post('amount'));

				if($payment_type===5 || $payment_type===6 || $payment_type===7) {
					$pgid = intval($this->input->post('pw'));
					$payment_gateways = $result['payment_gateway'];
					$selected_pg = false;

					for ($i=0; $payment_gateways && $i < count($payment_gateways); $i++) { 
						$pw = $payment_gateways[$i];
						// $conv_rate = json_encode($pw['conv_rate'], JSON_HEX_QUOT);
						if($pw['id']==$pgid) {
							$selected_pg = $pw;
							break;
						}
					}

					if($selected_pg['pw_name'] === 'PayU') {
						$result['pg'] = $this->getPG_payload_payu(array('payment_type' => $payment_type, 'amount' => $amount, 'selected_pg' => $selected_pg, 'company' => $company, 'current_user' => $current_user));
					}
					else if($selected_pg['pw_name'] === 'Atom') {
						$result['pg'] = $this->getPG_payload_atom(array('payment_type' => $payment_type, 'amount' => $amount, 'selected_pg' => $selected_pg, 'company' => $company, 'current_user' => $current_user));
					}
					//$this->load->view('header1',$result);
					$sponsoring_companyid = $current_user['sponsoring_companyid'];

					//Place to same transaction id into pg_transactions_tbl and same will be updated once the response received.
					$wallet_trans_id = $this->User_Model->save("pg_transactions_tbl", 
						array(
							'trans_tracking_id' => $result['pg']['txnid'], 
							'amount' => $amount,
							'final_amount' => $result['pg']['final_amount'],
							'companyid' => $companyid,
							'userid' => $user_id,
							'sponsoring_companyid' => $sponsoring_companyid,
							'payment_gateway' => $pgid,
							'pg_config' => json_encode($selected_pg, JSON_HEX_QUOT),
							'payment_mode' => $payment_type,
							'request_data' => json_encode($result['pg'], JSON_HEX_QUOT),
							'created_by' => $user_id
						)
					);

					if($selected_pg['pw_name'] === 'PayU') {
						$this->load->view('payu_pw',$result);
					}
					else if($selected_pg['pw_name'] === 'Atom') {
						$this->load->view('pw_atom',$result);
					}
					//$this->load->view('footer');
				}
				else {
					$cheque_no = intval($this->input->post('cheque_no'));
					$cheque_date = date('Y-m-d', strtotime($this->input->post('cheque_date')));
					$draft_no = intval($this->input->post('draft_no'));
					$draft_date = date('Y-m-d', strtotime($this->input->post('draft_date')));
					$amount = floatval($this->input->post('amount'));
					$reference_id = $this->input->post('reference_id');
					$reference_date = date('Y-m-d', strtotime($this->input->post('reference_date')));
					$narration = $this->input->post('narration');
					$bank = $this->input->post('bank');
					$branch = $this->input->post('branch');
					$account_no = $this->input->post('account_no');
					$target_accountid = $this->input->post('target_accountid');

					if($payment_type===1) {
						$reference_id = $cheque_no;
						$reference_date = $cheque_date;
					}
					else if($payment_type===2) {
						$reference_id = $draft_no;
						$reference_date = $draft_date;
					}

					$companyid = $current_user["companyid"];

					$walletid = $current_user['wallet_id'];
					$wallet_balance = $current_user['wallet_balance'];
					$sponsoring_companyid = $current_user['sponsoring_companyid'];

					$wallet_trans_id = $this->User_Model->save("wallet_transaction_tbl", 
						array(
							'wallet_id' => $walletid, 
							'date' => date("Y-m-d H:i:s"),
							'trans_id' => gen_uuid(),
							'companyid' => $companyid,
							'userid' => $user_id,
							'amount' => $amount,
							'bank' => $bank,
							'branch' => $branch,
							'userid' => $user_id,
							'dr_cr_type' => 'CR',
							'trans_type' => $payment_type,
							'trans_ref_id' => $reference_id,
							'trans_ref_date' => $reference_date,
							'target_accountid' => $target_accountid,
							'target_companyid' => $sponsoring_companyid,
							'trans_ref_type' => 'PAYMENT',
							'narration' => $narration,
							'sponsoring_companyid' => $sponsoring_companyid,
							'created_by' => $user_id
						)
					);
					
					redirect('/user');
				}
			}
			//echo json_encode($json);
		}
	}

	private function get_default_permission($company, $data) {
		// we need to put some logic here. If needed we can query table and get the default value set to any company
		return 255;
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

	public function change_password() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$postedvalue = json_decode($stream_clean, true);
		
		$uid=$postedvalue['id'];
		$current_pwd=trim($postedvalue['current_pwd']);
		$new_pwd=trim($postedvalue['new_pwd']);
		$confirm_pwd=trim($postedvalue['confirm_pwd']);
		$msg = '';
		$bflag = false;

		log_message('info', "[User_Control:change_password]=> user password change initiated | User Id: $uid | Current PWD: $current_pwd | New PWD: $new_pwd | Confirm PWD: $confirm_pwd");
		$user_info = $this->User_Model->get('user_tbl', array('id' => $uid, 'password' => "$current_pwd"));
		if($user_info && is_array($user_info) && count($user_info)>0) {
			$user_info = $user_info[0];
		}

		if($user_info && isset($user_info['password']) && $user_info['password'] === $current_pwd && $new_pwd === $confirm_pwd) {
			$result = $this->User_Model->update(array('password' => "$new_pwd"), $uid);
			log_message('info', "[User_Control:change_password]=> Password changed => User Id: $uid | New PWD: $new_pwd | Result : $result");

			$msg = 'Password successfully changed';
			$bflag = true;
		}
		else {
			$msg = 'No user found with the data provided';
		}

		echo json_encode(array('status' => $bflag, 'message' => $msg));
	}

	public function update_profile() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $postedvalue = json_decode($stream_clean, true);

		$uid=$postedvalue['id'];
		$name=$postedvalue['name'];
		$mobile=$postedvalue['mobile'];
		$email=$postedvalue['email'];
		$admin_markup=$postedvalue['admin_markup'];
		$company = $this->session->userdata('company');
		$companyid = intval($company['id']);
		$user_id = $this->session->userdata('user_id');
		$current_user = $this->session->userdata('current_user');
		$msg = '';
		$bflag = false;

		log_message('info', "[User_Control:update_profile]=> user profile update initiated | Name: $name | Mobile: $mobile | Admin.Markup: $admin_markup | User.ID: $user_id | Email: $email");

		$user_info = $this->User_Model->get('user_tbl', array('companyid' => $companyid, 'id !=' => $uid, 'email' => $email));
		if($user_info && count($user_info)>0) {
			$user_info = $user_info[0];
		}

		if(!$user_info) {
			$user_info = $this->User_Model->get('user_tbl', array('companyid' => $companyid, 'id !=' => $uid, 'mobile' => $mobile));
			if($user_info && count($user_info)>0) {
				$user_info = $user_info[0];
				$msg = 'Entered Mobile number already present. Please use different mobile number';				
			}
			else {
				$bflag = true;
			}
		}
		else {
			$msg = 'Entered Email address already present. Please use different email address';
		}

		if($bflag && $name!='')
		{
			$arr = array('name' => $name, 
						'mobile'=> $mobile,
						'email'=> $email							 							
					);
			$result = $this->User_Model->update($arr,$this->session->userdata('user_id'));
			log_message('info', "[User_Control:update_profile]=> user profile update response | Result : $result");

			log_message('info', "[User_Control:update_profile]=> user profile | Type : ".$current_user['type']." | Is.Admin: ".$current_user['is_admin']);
			if($result && $admin_markup>=0 && ($current_user['type']=='B2B' && $current_user['is_admin']!='1')) {
				log_message('info', "[User_Control:update_profile]=> Initiating Admin Markup | Result : $result");
				$arr = array('field_value' => $admin_markup);
				$result = $this->User_Model->update_table_data('user_config_tbl', array('user_id' => $this->session->userdata('user_id')), $arr);
			}
			if($result==true) {
				$bflg = true;
				$msg="Your Profile Updated Successfully. You need to relogin have the effect. After 5 secs it will auto logout.";
			}
			else {
				$bflg = false;
				$msg="Some error happened. Please contact administrator."; //$this->db->last_query();
			}
		}		 

		echo json_encode(array('status' => $bflag, 'message' => $msg));
	}
}
