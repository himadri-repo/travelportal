<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'core/Mail_Controller.php');
include(APPPATH.'entities/company.php');
class Search extends Mail_Controller 
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
		$this->load->database();
		$this->load->library('upload');
		$this->load->library('parser');
		//$this->load->library('perser_extension');
		$this->load->model('User_Model');
		$this->load->model('Search_Model');
		$this->load->model('Admin_Model');		
	}
	
	public function index()
	{  
		if ($this->session->userdata('user_id')) 
		{ 
			if(NEW_FLOW) {
				$companyid = $this->session->userdata("current_user")["companyid"];
				$cname = $this->session->userdata("current_user")["cname"];
				$result['cname']=$cname;
			}
			else {
				$companyid = NULL;
				$cname = NULL;
				$result['cname']='';
			}
			
			$result['city']=$this->User_Model->filter_city("ONE");	
			$result['city1']=$this->User_Model->filter_city("ROUND");
			$result["flight"]="";
			$result["footer"]=$this->Search_Model->get_post(5);

			if(NEW_FLOW && $companyid!=NULL)
			{
				$result['company_setting']=$this->Search_Model->company_setting($companyid);
			}

			//$result["setting"]=$this->Search_Model->setting();
			$result['setting']=$this->Search_Model->company_setting($companyid);

			$result['mywallet']= $this->getMyWallet();
				
			$this->load->view('header1',$result);
			$this->load->view('search',$result);
			$this->load->view('footer1');
		}
		else
			redirect("/login");
	}
	
	public function search_one_way()
	{ 
		if ($this->session->userdata('user_id')) 
		{	
			$source = intval($this->input->post('source'));
			$destination = intval($this->input->post('destination'));

			if($_SERVER['REQUEST_METHOD'] == 'POST' && $source>0 && $destination>0) 
			{			  
				$source_city = $this->User_Model->get('city_tbl', array('id' => $source));
				if($source_city && count($source_city)>0) {
					$source_city = $source_city[0];
				}
				$destination_city = $this->User_Model->get('city_tbl', array('id' => $destination));
				if($destination_city && count($destination_city)>0) {
					$destination_city = $destination_city[0];
				}

				$dept_date = date_format(date_create($this->input->post('departure_date')), 'Ymd');
				$url = "http://developer.goibibo.com/api/search/?app_id=f8803086&app_key=012f84558a572cb4ccc4b4c84a15d523&format=json&source=".$source_city['code']."&destination=".$destination_city['code']."&dateofdeparture=".$dept_date."&seatingclass=E&adult=1&children=0&infant=0&counter=100";
	
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache"
					),
				));
		
				$response = curl_exec($curl);
				$err = curl_error($curl);
		
				curl_close($curl);

				$live_ticket_data = json_decode($response, true);
				if($live_ticket_data && count($live_ticket_data)>0) {
					$live_ticket_data = $live_ticket_data['data']['onwardflights'];
				}
				else {
					$live_ticket_data = NULL;
				}
		
				$company = $this->session->userdata('company');
				$currentuser = $this->session->userdata('current_user');

				//if($this->input->post('departure_date_time')==date("d-m-Y")) 
				if($this->input->post('departure_date')==date("d-m-Y")) 
				{	
					$departure_date_time=date("Y-m-d H:i:s");
					$arr=array(
					"companyid" => $company["id"],
					"source"=>$this->input->post('source'),
					"destination"=>$this->input->post('destination'),
					"from_date"=>$departure_date_time,
					"to_date"=>date('Y-m-d', strtotime(date("Y-m-d").' +1 day')),
					"trip_type"=>"ONE",
					"approved"=>"1",
					"available"=>"YES",
					"no_of_person"=>$this->input->post('no_of_person')
					//"t.availibility>="=>$days
					);
				}
				else
				{
					//$departure_date_time=date("Y-m-d",strtotime($this->input->post('departure_date_time')));		  
					$departure_date_time=date("Y-m-d",strtotime($this->input->post('departure_date')));
					$arr=array(
					"companyid" => $company["id"],
					"source"=>$this->input->post('source'),
					"destination"=>$this->input->post('destination'),
					"from_date"=>$departure_date_time,
					"to_date"=>strtotime($departure_date_time.' +1 day'),
					"trip_type"=>"ONE",
					"approved"=>"1",
					"available"=>"YES",
					"no_of_person"=>$this->input->post('no_of_person')
					//"t.availibility>="=>$days
				
					);
				}
				$result['company']=$company;
				$result['city']=$this->User_Model->filter_city("ONE");
				$result['city1']=$this->Search_Model->filter_city($this->input->post('source'),"ONE"); 
				$result['city2']=$this->User_Model->filter_city("ROUND");
				$result['availalble']=$this->Search_Model->search_available_date($this->input->post('source'),$this->input->post('destination'),"ONE", $company["id"]);
				//$result["flight"]=$this->Search_Model->search_one_way($arr);

				$company = $this->session->userdata('company');
				$companyid = $company["id"];
				$usertype = $currentuser["type"];
				$is_admin = $currentuser["is_admin"];

				if($currentuser['type'] === 'B2B') {
					$user['user_markup']=$this->User_Model->user_settings($currentuser['id'], array('markup'));
				}
				else {
					$user['user_markup']= NULL;
				}	

				$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
				$defaultRP = NULL;
				$defaultRPD = NULL;
				if(count($rateplans)>0) {
					$defaultRP = $rateplans[0];
					$rateplanid = $defaultRP['id'];

					if($currentuser["type"]==='B2B' && $currentuser["is_admin"]!=='1' && $currentuser["rateplanid"]!==null) {
						$rateplanid = intval($currentuser["rateplanid"]);
					}

					$defaultRPD = $this->Admin_Model->rateplandetails($rateplanid);
				}
				$rateplan_details = $this->Admin_Model->rateplandetails(-1);

				$tickets = $this->Search_Model->search_one_wayV2($arr);

				for ($i=0; $tickets && $i < count($tickets); $i++) { 
					$ticket = &$tickets[$i];
					$live_ticket = NULL;
					if($live_ticket_data && count($live_ticket_data)>0) {
						for ($tk=0; $tk < count($live_ticket_data); $tk++) {
							$live_ticket = $live_ticket_data[$tk];
							$cachekey = $live_ticket['carrierid'].' '.$live_ticket['flightno'];
							$flight_no = str_replace('_', '', str_replace('-', '', $ticket['flight_no']));
							if($live_ticket && intval($live_ticket['stops'])===0 && $flight_no===$cachekey) {
								break;
							}
							else {
								$live_ticket = NULL;
							}
						}
					}

					if($live_ticket) {
						$ticket['live_fare'] = floatval($live_ticket['fare']['adulttotalfare']);
						$ticket['seatsavailable'] = intval($live_ticket['seatsavailable']);
					}
					else {
						$ticket['live_fare'] = -1;
						$ticket['seatsavailable'] = -1;
					}
					$suprpd = [];
					$sellrpd = [];
					$suprpid = intval($ticket["rate_plan_id"]);
					$sellrpid = intval($ticket["seller_rateplan_id"]);
					
					if($rateplan_details && count($rateplan_details)>0) {
						foreach ($rateplan_details as $rateplan_detail) {
							$rpid = intval($rateplan_detail["rateplanid"]);
		
							if($rpid === $suprpid) {
								$suprpd[] = $rateplan_detail;
							}
							if($rpid === $sellrpid) {
								$sellrpd[] = $rateplan_detail;
							}
						}

						//If user is a Travel Agent (B2B) then check is there any Rateplan assigned against him or not.
						//If assigned then ignore wholesaler's rateplan and take the rateplan which is assigned to this customer.
						//$adminmarkup is his own margin to his customers.
						$adminmarkup = 0;
						$usertype = '';

						if($currentuser['type']==='B2B') {
							if($currentuser['is_admin']==='1') {
								$usertype = 'EMP';	
							}
							else {
								$usertype = 'B2B';
							}
						} else if($currentuser['type']==='B2B') {
							$usertype = 'B2C';
						} else if($currentuser['type']==='EMP') {
							$usertype = 'EMP';
						}

						if($currentuser["type"]==='B2B' && $currentuser["is_admin"]!=='1' && $defaultRPD!==NULL) {
							$sellrpd = $defaultRPD;
							if($user['user_markup']!==NULL) {
								if($user['user_markup']['field_value_type'] === '2') {
									$adminmarkup = floatval($user['user_markup']['field_value']);
								}
							}	
						}
		
						if(count($suprpd)>0 || count($sellrpd)>0) {
							$ticketupdated = $this->calculationTicketValue($ticket, $suprpd, $sellrpd, $companyid, $adminmarkup, $usertype);
						}
					}
					$total = $ticket['total'];
					$ticket["new"] = 1;

					log_message('info', "Ticket -> ".json_encode($ticket));
				}	
				
				$result["flight"]=$tickets;
				$result["rateplan"]=$rateplans; // $default_rp;
				$result["currentuser"]=$currentuser;
				
				$this->session->set_userdata('no_of_person',$this->input->post('no_of_person'));
				
				$result["post"][0]["source"]=$this->input->post('source');
				$result["post"][0]["destination"]=$this->input->post('destination');
				$result["post"][0]["departure_date_time"]=$this->input->post('departure_date_time');
				$result["post"][0]["departure_date"]=$this->input->post('departure_date');
				$result["post"][0]["no_of_person"]=$this->input->post('no_of_person');
				$result["post"][0]["hid_trip_type"]="ONE";
				//echo $this->db->last_query();die(); 
				$result["post"][0]["qty"]=$this->input->post('no_of_person');
				//$result["setting"]=$this->Search_Model->setting();
				$result['setting']=$this->Search_Model->company_setting($companyid);
				$result["footer"]=$this->Search_Model->get_post(5);
				$current_user = $this->session->userdata("current_user");

				$result['mywallet']= $this->getMyWallet();
							
				$this->load->view('header1',$result);
				$this->load->view('search_one_way',$result);
				$this->load->view('footer1');
			}
			else
				redirect("/search");  
		}
		else
			redirect("/login");  
	}

	private function get_rateplan_detail_by_head($headname, $rateplandetails) {
		if($rateplandetails==null) return NULL;
		if($headname==null || $headname=='') return NULL;
		$rateplandetail = NULL;

		try
		{
			for ($i=0; $rateplandetails && $i < count($rateplandetails); $i++) { 
				if($rateplandetails[$i]["head_code"]==$headname) {
					$rateplandetail = $rateplandetails[$i];
					break;
				}
			}
		}
		catch(Exception $ex) {

		}

		return $rateplandetail;
	}
	
	public function search_one_wayV1()
	{ 
		//$diff = intval((strtotime($this->input->post('departure_date_time'))-strtotime(date("d-m-Y")))/60);
		$diff = intval((strtotime($this->input->post('departure_date'))-strtotime(date("d-m-Y")))/60);
		$diff=intval($diff/60);
		$days=$diff/24;
		
		if ($this->session->userdata('user_id')) 
		{	
			if($_SERVER['REQUEST_METHOD'] == 'POST') 
			{	
				$company = $this->session->userdata('company');
		  
				//if($this->input->post('departure_date_time')==date("d-m-Y")) 
				if($this->input->post('departure_date')==date("d-m-Y")) 
				{	
					$departure_date_time=date("Y-m-d H:i:s");			  
					$arr=array(
					"t.source"=>$this->input->post('source'),
					"t.destination"=>$this->input->post('destination'),
					"t.departure_date_time > "=>$departure_date_time,
					"DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')<"=>date('Y-m-d', strtotime(date("Y-m-d").' +1 day')),
					"t.trip_type"=>"ONE",
					"t.approved"=>"1",
					"t.available"=>"YES",
					"t.no_of_person>="=>$this->input->post('no_of_person')
					//"t.availibility>="=>$days
					);
				}
				else
				{
					//$departure_date_time=date("Y-m-d",strtotime($this->input->post('departure_date_time')));		  
					$departure_date_time=date("Y-m-d",strtotime($this->input->post('departure_date')));
					$arr=array(
					"t.source"=>$this->input->post('source'),
					"t.destination"=>$this->input->post('destination'),
					"DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')="=>$departure_date_time,
					"t.trip_type"=>"ONE",
					"t.approved"=>"1",
					"t.available"=>"YES",
					"t.no_of_person>="=>$this->input->post('no_of_person')
					//"t.availibility>="=>$days
				
					);
				}
				$result['city']=$this->User_Model->filter_city("ONE");
				$result['city1']=$this->Search_Model->filter_city($this->input->post('source'),"ONE"); 
				$result['city2']=$this->User_Model->filter_city("ROUND");
				$result['availalble']=$this->Search_Model->search_available_date($this->input->post('source'),$this->input->post('destination'),"ONE", $company["id"]);
				$result["flight"]=$this->Search_Model->search_one_way($arr);
				
				$this->session->set_userdata('no_of_person',$this->input->post('no_of_person'));
				
				$result["post"][0]["source"]=$this->input->post('source');
				$result["post"][0]["destination"]=$this->input->post('destination');
				$result["post"][0]["departure_date_time"]=$this->input->post('departure_date_time');
				$result["post"][0]["departure_date"]=$this->input->post('departure_date');
				$result["post"][0]["no_of_person"]=$this->input->post('no_of_person');
				$result["post"][0]["hid_trip_type"]="ONE";
				//echo $this->db->last_query();die(); 
				/*if($result["flight"][0]["user_id"]==$this->session->userdata('user_id'))
				{					  				 
					$result["flight"][0]["total"]=$result["flight"][0]["total"];
				}					 
				else	
				{					  
					$result["flight"][0]["total"]=$result["flight"][0]["total"]+$result["flight"][0]["admin_markup"];	
				
				}*/
				//echo $this->db->last_query();die(); 
				$result["post"][0]["qty"]=$this->input->post('no_of_person');
				$result["setting"]=$this->Search_Model->setting();
				$result["footer"]=$this->Search_Model->get_post(5);

				$current_user = $this->session->userdata("current_user");

				$result['mywallet']= $this->getMyWallet();
					
				$this->load->view('header1',$result);
				$this->load->view('search_one_way',$result);
				$this->load->view('footer1');
			}
			else
				redirect("/search");  
		}
		else
			redirect("/login");  
	}
	
	
	public function search_round_trip()
	{ 
	  $diff = intval((strtotime($this->input->post('departure_date_time'))-strtotime(date("d-m-Y")))/60);
	  $diff=intval($diff/60);
	  $days=$diff/24;
	  
      if ($this->session->userdata('user_id')) 
	  {	
          if($_SERVER['REQUEST_METHOD'] == 'POST') 
		  {			  
			  if($this->input->post('departure_date_time1')==date("d-m-Y")) 
			  {	
				 $departure_date_time=date("Y-m-d H:i:s");			  
				 $arr=array(
				 "t.source"=>$this->input->post('source1'),
				 "t.destination"=>$this->input->post('destination1'),
				 "t.departure_date_time > "=>$departure_date_time,
				 "DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')<"=>date('Y-m-d', strtotime(date("Y-m-d").' +1 day')),
				 "t.trip_type"=>"ROUND",
				 "t.approved"=>"1",
				 "t.available"=>"YES",
				 "t.no_of_person>="=>$this->input->post('no_of_person1')
				 //"t.availibility>="=>$days
				
				 );
			  }
			  else
			  {
				 $departure_date_time=date("Y-m-d",strtotime($this->input->post('departure_date_time1')));		  
				 $arr=array(
				 "t.source"=>$this->input->post('source1'),
				 "t.destination"=>$this->input->post('destination1'),
				 "DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')="=>$departure_date_time,
				 "t.trip_type"=>"ROUND",
				 "t.approved"=>"1",
				 "t.available"=>"YES",
				 "t.no_of_person>="=>$this->input->post('no_of_person1')
				 //"t.availibility>="=>$days
				 
				 );
			  }
			  $company = $this->session->userdata('company');
			  $companyid = $company["id"];

			  $result['city']=$this->User_Model->filter_city("ROUND");
			  $result['city1']=$this->Search_Model->filter_city($this->input->post('source1'),"ROUND"); 
			  $result['city2']=$this->User_Model->filter_city("ONE");
			  $result['availalble']=$this->Search_Model->search_available_date($this->input->post('source1'),$this->input->post('destination1'),"ROUND", $company["id"]);
			  $result["flight"]=$this->Search_Model->search_round_trip($arr);	
			  $this->session->set_userdata('no_of_person',$this->input->post('no_of_person1'));
			  $result["post"][0]["source"]=$this->input->post('source1');
			  $result["post"][0]["destination"]=$this->input->post('destination1');			
			  $result["post"][0]["departure_date_time"]=$this->input->post('departure_date_time1');
			  $result["post"][0]["no_of_person"]=$this->input->post('no_of_person1');
			  $result["post"][0]["hid_trip_type"]="ROUND";
			  //echo $this->db->last_query();die(); 
			  /*if($result["flight"][0]["user_id"]==$this->session->userdata('user_id'))
			  {					  				 
				 $result["flight"][0]["total"]=$result["flight"][0]["total"];
			  }					 
			  else	
			  {					  
				 $result["flight"][0]["total"]=$result["flight"][0]["total"]+$result["flight"][0]["admin_markup"];	
				
			  }*/
			  $result["post"][0]["qty"]=$this->input->post('no_of_person');
			  $result["setting"]=$this->Search_Model->setting();
			  $result["footer"]=$this->Search_Model->get_post(5);

			  $current_user = $this->session->userdata("current_user");

			  $result['mywallet']= $this->getMyWallet();
	  
		      $this->load->view('header1',$result);
			  $this->load->view('search_round_trip',$result);
			  $this->load->view('footer1');
		  }
		  else
		  redirect("/search");  
	  }
	  else
		redirect("/login");  
	   
	}
	
	
	public function flightdetails($id)
	{
		$current_user = $this->session->userdata('current_user');
		if ($this->session->userdata('user_id') && isset($id)) 
		{			  
			$company = $this->session->userdata('company');
			$companyid = $company["id"];
			$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
			$defaultRP = NULL;
			$defaultRPD = NULL;
			$suprpd = [];
			$sellrpd = [];

			if(count($rateplans)>0) {
				$defaultRP = $rateplans[0];
				$rateplanid = $defaultRP['id'];

				if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && $current_user["rateplanid"]!==null) {
					$rateplanid = intval($current_user["rateplanid"]);
				}
					
				$defaultRPD = $this->Admin_Model->rateplandetails($rateplanid);
			}
	
			$rateplan_details = $this->Admin_Model->rateplandetails(-1);
	
			$tickets = $this->Search_Model->flight_details($id, $companyid);
			
			if($tickets && count($tickets)>0) {
				$ticket = $tickets[0];

				log_message('info', "Selected Ticket => ".json_encode($ticket));

				$suprpid = intval($ticket["rate_plan_id"]);
				$sellrpid = intval($ticket["seller_rateplan_id"]);

				if($rateplan_details!==NULL && count($rateplan_details)>0) {
					foreach ($rateplan_details as $rateplan_detail) {
						$rpid = intval($rateplan_detail["rateplanid"]);

						if($rpid === $suprpid) {
							$suprpd[] = $rateplan_detail;
						}
						if($rpid === $sellrpid) {
							$sellrpd[] = $rateplan_detail;
						}
					}
				}

				//If user is a Travel Agent (B2B) then check is there any Rateplan assigned against him or not.
				//If assigned then ignore wholesaler's rateplan and take the rateplan which is assigned to this customer.
				//$adminmarkup is his own margin to his customers.
				$adminmarkup = 0;
				$user['user_markup']= NULL;
				if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && $defaultRPD!==NULL) {
					$sellrpd = $defaultRPD;
					$user['user_markup']=$this->User_Model->user_settings($current_user['id'], array('markup'));
	
					if($user['user_markup']!==NULL) {
						if($user['user_markup']['field_value_type'] === '2') {
							$adminmarkup = floatval($user['user_markup']['field_value']);
						}
					}	
				}

				$usertype = '';

				if($current_user['type']==='B2B') {
					if($current_user['is_admin']==='1') {
						$usertype = 'EMP';	
					}
					else {
						$usertype = 'B2B';
					}
				} else if($current_user['type']==='B2B') {
					$usertype = 'B2C';
				} else if($current_user['type']==='EMP') {
					$usertype = 'EMP';
				}
		
				//$ticketupdated = $this->calculationTicketValue($ticket, $defaultRPD, $rateplan_details, $companyid, $adminmarkup);
				$ticketupdated = $this->calculationTicketValue($ticket, $suprpd, $sellrpd, $companyid, $adminmarkup, $usertype);

				$result["flight"]=array($ticket); 
				$result["currentuser"]=$current_user;
				//$result["setting"]=$this->Search_Model->setting();
				$result['setting']=$this->Search_Model->company_setting($companyid);

				$result["user_type"]=strtoupper($current_user["type"]);
				
				$result['user_details']=$this->User_Model->user_details();
				if($result["flight"][0]["no_of_person"]>0 && $result["flight"][0]["approved"]==1)
				{
					if(isset($_REQUEST["back_end_qty"]))
					{					  
						$this->session->set_userdata('no_of_person',$_REQUEST["back_end_qty"]);
					}
					else
					{						
						
					}
					
					$result["flight"][0]["ticket_type"]= $result['user_details'][0]["type"];
					if($result["flight"][0]["user_id"]==$this->session->userdata('user_id'))
					{					  					 
						// $result["flight"][0]["total"]=$result["flight"][0]["total"];
						$result["flight"][0]["price"]=$result["flight"][0]["total"]+$result["flight"][0]["admin_markup"];	
						$result["flight"][0]["total"]=$result["flight"][0]["total"]+$result["flight"][0]["admin_markup"];
					}					 
					else	
					{					  
						$result["flight"][0]["price"]=$result["flight"][0]["total"]+$result["flight"][0]["admin_markup"];	
						$result["flight"][0]["total"]=$result["flight"][0]["total"]+$result["flight"][0]["admin_markup"];
					}
					
					//$result["setting"]=$this->Search_Model->setting();
					$result["footer"]=$this->Search_Model->get_post(5);
					$current_user = $this->session->userdata("current_user");

					$result['mywallet']= $this->getMyWallet();
											
					$this->load->view('header1',$result);
					$this->load->view('flight-detail',$result);
					$this->load->view('footer1');
				}
				else
				{
					redirect("/search");
				}
			}
		}
	}

	private function calculationTicketValue(&$ticket, $defaultRPD, $rateplan_details, $companyid, $adminmarkup=0, $usertype='') {
		$rateplanid = $ticket['rate_plan_id'];
		$seller_rateplanid = $ticket['seller_rateplan_id'];
		$price = $ticket['price'];
		
		$ticket['whl_markup'] = 0;
		$ticket['whl_srvchg'] = 0;
		$ticket['whl_cgst'] = 0;
		$ticket['whl_sgst'] = 0;
		$ticket['whl_igst'] = 0;
		$ticket['whl_disc'] = 0;

		$ticket['spl_markup'] = 0;
		$ticket['spl_srvchg'] = 0;
		$ticket['spl_cgst'] = 0;
		$ticket['spl_sgst'] = 0;
		$ticket['spl_igst'] = 0;
		$ticket['spl_disc'] = 0;

		$tax_others = 0;

		try
		{
			if($ticket['supplierid'] !== $companyid) {
				//sourced tickets
				for ($j=0; $j < count($rateplan_details); $j++) { 
					$rpdetail = $rateplan_details[$j];
					//if($rpdetail['rateplanid'] == $seller_rateplanid) {
						$achead = 'whl_'.$rpdetail['head_code'];
						// array_push($ticket, [$achead => '']);
						if($rpdetail['head_code'] !== 'igst') { //because igst can only be calculated for other state
							if($rpdetail['operation'] == 1) {
								$ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
								$tax_others = $tax_others + $ticket[$achead];
							}
							else {
								$ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
								$tax_others = $tax_others - $ticket[$achead];
							}
						}
					//}
				}
			}
			// $ticket['price'] += $tax_others;
			$tax_others = 0;
			
			if($defaultRPD !== NULL) {
				//add wholesaler's part
				for ($j=0; $j < count($defaultRPD); $j++) { 
					$rpdetail = $defaultRPD[$j];
					if(intval($ticket['supplierid']) === intval($companyid) && $usertype !== 'B2B') {
						$achead = 'whl_'.$rpdetail['head_code'];
					}
					else {
						$achead = 'spl_'.$rpdetail['head_code'];
					}
					// array_push($ticket, [$achead => '']);
					if($rpdetail['head_code'] !== 'igst') { //because igst can only be calculated for other state
						if($rpdetail['operation'] == 1) {
							$ticket[$achead] += $this->getProcessedValue($rpdetail, $price, $ticket);
							$tax_others += $ticket[$achead];
						}
						else {
							$ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
							$tax_others -= $ticket[$achead];
						}
					}
				}
			}
		}
		catch(Exception $ex) {

		}

		// $ticket['finalvalue'] = $tax_others;
		// $ticket['price'] += $tax_others;

		$ticket['admin_markup'] = $adminmarkup;
		$ticket['price'] += ($ticket['whl_markup'] + $ticket['spl_markup'] + $ticket['whl_srvchg'] + $ticket['spl_srvchg'] 
				+ ($ticket['whl_srvchg'] * $ticket['whl_cgst'] / 100)
				+ ($ticket['whl_srvchg'] * $ticket['whl_sgst'] / 100)
				+ ($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100)
				+ ($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100));

		$ticket['cost_price'] = (($price + $ticket['spl_markup'] + $ticket['spl_srvchg'])
			+ ($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100)
			+ ($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100));

		if(intval($ticket['cost_price'])===0) {
				$ticket['cost_price'] = intval($price);
		}
	
		if ($ticket['whl_srvchg'] === 0) {
			$ticket['whl_cgst'] = 0;
			$ticket['whl_sgst'] = 0;
			$ticket['whl_igst'] = 0;
		}
		else {
			$ticket['whl_cgst'] = ($ticket['whl_srvchg'] * $ticket['whl_cgst'] / 100);
			$ticket['whl_sgst'] = ($ticket['whl_srvchg'] * $ticket['whl_sgst'] / 100);
			$ticket['whl_igst'] = ($ticket['whl_srvchg'] * $ticket['whl_igst'] / 100);
		}

		if ($ticket['spl_srvchg'] === 0) {
			$ticket['spl_cgst'] = 0;
			$ticket['spl_sgst'] = 0;
			$ticket['spl_igst'] = 0;
		}
		else {
			$ticket['spl_cgst'] = ($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100);
			$ticket['spl_sgst'] = ($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100);
			$ticket['spl_igst'] = ($ticket['spl_srvchg'] * $ticket['spl_igst'] / 100);
		}

		return $ticket;
	}
	
	public function beforebook($id)
	{
		$current_user = $this->session->userdata('current_user');
		if ($this->session->userdata('user_id') && isset($id)) 
		{	
			$company = $this->session->userdata('company');
			$companyid = $company["id"];
			$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
			$defaultRP = NULL;
			$defaultRPD = NULL;
			$suprpd = [];
			$sellrpd = [];
			if(count($rateplans)>0) {
				$defaultRP = $rateplans[0];
				$rateplanid = $defaultRP['id'];
				
				if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && $current_user["rateplanid"]!==null) {
					$rateplanid = intval($current_user["rateplanid"]);
				}
	
				$defaultRPD = $this->Admin_Model->rateplandetails($rateplanid);
			}
	
			$rateplan_details = $this->Admin_Model->rateplandetails(-1);

			$tickets = $this->Search_Model->flight_details($id, $companyid);
			$result["flight"]=$tickets;
			if($result["flight"][0]["no_of_person"]>0 && $result["flight"][0]["approved"]==1)
			{
				$ticket = $tickets[0];

				$suprpid = intval($ticket["rate_plan_id"]);
				$sellrpid = intval($ticket["seller_rateplan_id"]);

				if($rateplan_details!==NULL && count($rateplan_details)>0) {
					foreach ($rateplan_details as $rateplan_detail) {
						$rpid = intval($rateplan_detail["rateplanid"]);

						if($rpid === $suprpid) {
							$suprpd[] = $rateplan_detail;
						}
						if($rpid === $sellrpid) {
							$sellrpd[] = $rateplan_detail;
						}
					}
				}

				//If user is a Travel Agent (B2B) then check is there any Rateplan assigned against him or not.
				//If assigned then ignore wholesaler's rateplan and take the rateplan which is assigned to this customer.
				//$adminmarkup is his own margin to his customers.
				$adminmarkup = 0;
				$user['user_markup']= NULL;
				if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && $defaultRPD!==NULL) {
					$sellrpd = $defaultRPD;
					$user['user_markup']=$this->User_Model->user_settings($current_user['id'], array('markup'));

					if($user['user_markup']!==NULL) {
						if($user['user_markup']['field_value_type'] === '2') {
							$adminmarkup = floatval($user['user_markup']['field_value']);
						}
					}	
				}

				$usertype = '';

				if($current_user['type']==='B2B') {
					if($current_user['is_admin']==='1') {
						$usertype = 'EMP';	
					}
					else {
						$usertype = 'B2B';
					}
				} else if($current_user['type']==='B2C') {
					$usertype = 'B2C';
				} else if($current_user['type']==='EMP') {
					$usertype = 'EMP';
				}

				$ticketupdated = $this->calculationTicketValue($ticket, $suprpd, $sellrpd, $companyid, $adminmarkup, $usertype);

				$service_charge = floatval($this->input->post('service_charge'));
				$result1["user"]=$this->User_Model->user_details();
				$result["currentuser"] = $current_user;
				//$result["setting"]=$this->Search_Model->setting();
				$result['setting']=$this->Search_Model->company_setting($companyid);

				$baserate = ($ticket["total"]) + $ticket["whl_markup"] + $ticket["spl_markup"];
				$srvchg = $ticket["whl_srvchg"] + $ticket["spl_srvchg"];
				$gst = $ticket["whl_cgst"] + $ticket["spl_cgst"] + $ticket["whl_sgst"] + $ticket["spl_sgst"];
				$total = $baserate + $srvchg + $gst;
				$costprice = 0;
				if($current_user["is_admin"]==='1' || $current_user["type"]==='EMP')
				{
					//this is company admin or employee
					//$flight[$key]["total"] + $flight[$key]["whl_markup"] + $flight[$key]["whl_srvchg"] + ($flight[$key]['whl_srvchg'] * $flight[$key]['whl_cgst'] / 100) + ($flight[$key]['whl_srvchg'] * $flight[$key]['whl_sgst'] / 100);
					//$costprice = $ticket["total"] + $ticket["whl_markup"] + $ticket["whl_srvchg"] + ($ticket["whl_srvchg"] * $ticket["whl_cgst"]/100) + ($ticket["whl_srvchg"] * $ticket["whl_sgst"]/100);
					$costprice = $ticket["total"] + $ticket["spl_markup"] + $ticket["spl_srvchg"] + $ticket["spl_cgst"] + $ticket["spl_sgst"];
				}
				else if($current_user["type"]==='B2B' || $current_user["type"]==='B2C') {
					$costprice = $ticket["price"];
				}

				$result["flight"][0]["admin_markup"]= $ticket['admin_markup'];

				$result["flight"][0]["price"]= $baserate;
				$result["flight"][0]["service_charge"]= $srvchg;
				$result["flight"][0]["gst"]= $gst;
				$result["flight"][0]["costprice"]= $costprice;
				$result["flight"][0]["rateplanid"]= $rateplanid;
				
					
				$result["flight"][0]["markup"]=$srvchg;
				$result["flight"][0]["total"]=$total;
				$result["flight"][0]["qty"]=$this->input->post('qty');
				// $result["flight"][0]["wallet_balance"]=$this->get_wallet_balance($this->session->userdata('user_id'));
				$result["flight"][0]["wallet_balance"]=$this->get_wallet_balance($current_user);
				
				$result["flight"][0]["id"]=$id;	
				$result["flight"][0]["user_email"]=$result1["user"][0]["email"];
				$result["flight"][0]["mobile_no"]=$result1["user"][0]["mobile"];			
				$result["flight"][0]["first_name"]=$result1["user"][0]["name"];
				$result["flight"][0]["user"]=$result1["user"][0];
			
									
				if($result["flight"][0]["no_of_person"]>0 && $result["flight"][0]["no_of_person"]>=$this->input->post('qty'))
				{					  			  			  
					//$result["setting"]=$this->Search_Model->setting();
					$result["footer"]=$this->Search_Model->get_post(5);
					$current_user = $this->session->userdata("current_user");

					$result['mywallet']= $this->getMyWallet();
									
					$this->load->view('header1',$result);
					$this->load->view('customer-information',$result);						
					$this->load->view('footer1');
				}
				else
				{
					redirect("/search");
				}
			}
		    else
		    {
			  redirect("/search");
		    }  
		}
	}

	public function book($id)
	{
		$amount=floatval($this->input->post('total'));
		$costprice=floatval($this->input->post('costprice'));
		$qty=intval($this->input->post('qty'));
		$date = $this->input->post('date');
		$price = $this->input->post('price');
		$service_charge = $this->input->post('service_charge');
		$igst = $this->input->post('igst');
		$rateplanid = $this->input->post('rateplanid');

		$posteddata = array(
			"total_amount" => $amount, 
			"costprice" => $costprice, 
			"qty" => $qty, 
			"date" => $date, 
			"price" => $price, 
			"service_charge" => $service_charge, 
			"igst" => $igst, 
			"rateplanid" => $rateplanid 
		);
		
		$company = $this->get_companyinfo();
		$current_user = $company['current_user'];
		$companyid = intval($company['id']);
		$form_valid = $this->is_booking_valid($companyid);

		$customers = $form_valid['customers'];
		
		if($form_valid['hasduplicatecustomers']!='true') {
			redirect("search/beforebook/$id");
		}
		//$result["footer"]=$this->Search_Model->get_post(5);

		$wallet=$this->get_walletbalance($current_user);
		$wallet_balance = $wallet['balance'];
		$total_costprice = ($qty * $costprice);

		if (isset($date) && isset($qty)) {

			$ticket = $this->get_ticket($id, $current_user, $company);
			$status = ($ticket['pnr']!="" && intval($ticket['user_id'])==intval($current_user["id"])) ? "CONFIRMED" : "PENDING";
			$pnr = $ticket['pnr'];
			$user_id = intval($ticket['user_id']);

			$is_booking_allowed = $this->is_booking_allowed($total_costprice, $current_user, $wallet);

			//if($total_costprice>$wallet_amount && $current_user['is_admin']!='1' && $user['user_details'][0]["credit_ac"]==0)
			if(!$is_booking_allowed)
			{
				$result["footer"]=$this->Search_Model->get_post(5);
				$result["setting"]=$this->Search_Model->setting();
				$current_user = $this->session->userdata("current_user");

				$result['mywallet']= $this->getMyWallet();
			
				$this->load->view('header1',$result);
				$this->load->view('insufficient_amount',$result);
				$this->load->view('footer1');
			}
			else
			{
				try {
					$result=$this->User_Model->user_details();
					if($pnr!="" && $this->session->userdata('user_id')==$user_id)
						$status="CONFIRM";
					else
						$status="PENDING";	
					$booking_id = -1;

					$requesting_by = $ticket['requesting_by'];
					$requesting_to = $ticket['requesting_to'];
					$seller_userid = intval($ticket['seller_userid']);
					$seller_companyid = intval($ticket['seller_companyid']);
					$adminmarkup = $ticket['adminmarkup'];

					#region flow steps
					/*
					Flow:
					1. 	Insert into bookings_tbl table
					2. 	Insert into booking_activity_tbl table
					3. 	Insert into account_transactions_tbl table
						(If the Admin/Employee is creating this ticket then booking should be raised against company rather user )
						(If the TA/Retail customer creating this ticket then booking should be raised against user (customer))
					4.  Send SMS to Wholesaler & Customer
					5.  Insert into Wallet Transaction
						(This is only for non-admin users and customers)
					6.  After receiving payment from Wallet, Insert into account_transactions_tbl table
					*/
					#endregion

					log_message('info', "Current_User : ".json_encode($current_user));
					log_message('info', "Ticket : ".json_encode($ticket));
					log_message('info', "Company : ".json_encode($company));
					log_message('info', "Posted Data : ".json_encode($posteddata));
					log_message('info', "Customers : ".json_encode($customers));

					$booking_info = $this->save_booking($current_user, $ticket, $status, $wallet, $company, $posteddata, $customers);

					$booking_id = intval($booking_info['booking_id']);
					$booking_date = $booking_info['booking_date'];
					$booking_activity_id = intval($booking_info['booking_activity_id']);
					$voucher_no = intval($booking_info['voucher_no']);
					
					$newbookinginfo=$this->Search_Model->booking_details($booking_id);
					if($newbookinginfo && count($newbookinginfo)>0) {
						$newbookinginfo = $newbookinginfo[0];
					}

					log_message('info', "Booking Info : ".json_encode($booking_info));
					log_message('info', "Booking Details : ".json_encode($newbookinginfo));
					
					$flight = $this->Search_Model->flight_details($id, $companyid);
					if($flight && count($flight)>0) {
						$flight = $flight[0];
					}
					$trip = ($flight['trip_type']=='ONE')?"ONE WAY":"RETURN";
					$is_owned_ticket = intval($ticket['companyid'])===$companyid;
					
					log_message('info', "Flight : ".json_encode($flight));

					//send email to customer here
					$this->prepare_send_email("BOOKING_CUSTOMER_EMAIL", $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo);
					//send sms to customer here
					$this->prepare_send_sms("BOOKING_CUSTOMER_SMS", $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo);
					//$this->prepare_send_sms("BOOKING_CUSTOMER_SMS", $booking_info, $company, $ticket, $customers, $posteddata);

					if($is_owned_ticket) {
						$sale_type = $ticket['sale_type'];
						log_message('info', "This is own ticket. Wholeslaer id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type");
					}
					else {
						$supplier_contract = $this->Search_Model->get_suppliers_contract($companyid, intval($ticket['companyid']));
						if($supplier_contract && count($supplier_contract)>0) {
							$supplier_contract = $supplier_contract[0];
						}

						if(isset($supplier_contract['sale_type'])) {
							$sale_type = $ticket['sale_type'];
						}
						log_message('info', "This is sourced ticket. Wholeslaer id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type");
					}

					if(isset($ticket['pnr']) && $ticket['pnr']=='') {
						log_message('info', "Original sale type: $sale_type | PNR: ".$ticket['pnr']." | Changed to REQUEST sale type");
						$sale_type = 'request';
					}

					if($current_user["is_admin"]!='1' && $current_user["type"]!='EMP') {
						$transactionresult = $this->do_wallet_transaction($current_user, $company, $ticket, array('booking_id' => $booking_id, 'booking_date' => $booking_date, 'total_costprice'=>$total_costprice));
					}
					$wallettransid = 0;
					if($transactionresult) {
						$wallettransid = intval($transactionresult['wallet_transid']);
					}

					if($booking_id>0 && $wallettransid>=0) {
						log_message('info', "Booking processed in REQUEST mode | Booking Id: $booking_id | Wallet Transaction id: $wallettransid | Accounts posting id: $voucher_no");
						redirect("/search/thankyou/".$booking_id."");
					}
					else {
						log_message('info', "Can't process booking some error | Booking Id: $booking_id | Wallet Transaction id: $wallettransid | Accounts posting id: $voucher_no");
						redirect("search/beforebook/$id");
					}
				}
				catch(Exception $ex1) {
					log_message('error', $ex1);
				}

				#region
				// $result["flight"]=$this->Search_Model->flight_details($id, $companyid); 
				// if($result["flight"][0]["trip_type"]=="ONE")
				// 	$trip="ONE WAY";
				// else
				// 	$trip="RETURN";
				// if($booking_id_new)	
				// {
				// 	if($result["flight"][0]["sale_type"]=="live")
				// 	{
				// 		$refrence_id = -1;
				// 	}
					
				// 	if($this->session->userdata('user_id')==$user_id)
				// 	{
				// 		$amount=$this->input->post('total');
				// 		$user['user_details']=$this->User_Model->user_details();	          							
				// 		$data = array(				            
				// 			'name' => $companyname,
				// 			'email'=>$user['user_details'][0]["email"],
				// 			'msg'=>"You have Booked  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person. (Booking No. : ".$booking_id_new.")",
				// 			'msg1'=>' You booking is confirm',
				// 			'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
				// 			);
				// 		$this->send("Booking",$data);
				
				
				// 		$data1 = array(				            
				// 			'name' => $user['user_details'][0]["name"],
				// 			'email'=>$user['user_details'][0]["email"],
				// 			'mobile'=>$user['user_details'][0]["mobile"],
				// 			'msg'=>"A New Booking done by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person (Booking No. : ".$booking_id_new.")",
				// 			'user_id'=> $user['user_details'][0]["user_id"],
				// 			'msg1'=>'',
				// 			'msg2'=>""							 
				// 			);									 
				// 		$this->adminsend("Booking",$data1);
				// 		$no=$user['user_details'][0]["mobile"];
				// 		$msg="You have Booked a trip Booking No. ".$booking_id_new." Thanks, $companyname";
				// 		$this->send_message($no,$msg);
						
					
				// 		$no = $supplier_mobile;
				// 		$msg="A New Booking done by User ID : ".$user['user_details'][0]["user_id"]." Booking No. ".$booking_id_new."";
				// 		$this->send_message($no,$msg);
				// 	}							 
				// 	else
				// 	{		
				// 		$amount=$total_costprice; //floatval($this->input->post('costprice'));
						
				// 		if ($current_user['is_admin']=='0') {
				// 			$wallet_trans_date = date("Y-m-d H:i:s");
				// 			$arr=array(
				// 			'wallet_id'=>$current_user['wallet_id'],
				// 			'date'=>$wallet_trans_date,
				// 			'trans_id'=>uniqid(),
				// 			'companyid'=>$companyid,
				// 			'userid'=>$this->session->userdata('user_id'),
				// 			//'amount'=>(0-$amount),
				// 			'amount'=>($total_costprice),
				// 			'dr_cr_type'=>'DR',
				// 			'trans_type'=>20, /*20 is for Ticket Booking */
				// 			'trans_ref_id'=>$booking_id_new,
				// 			'trans_ref_date'=>$booking_date,
				// 			'trans_ref_type'=>'PURCHASE',
				// 			'trans_documentid'=>$booking_id_new,
				// 			'narration'=>"New ticket booking raised (id: $booking_id_new)",
				// 			'sponsoring_companyid'=>$current_user['sponsoring_companyid'],
				// 			'created_by'=>$this->session->userdata('user_id'),
				// 			'status'=>1,
				// 			'approved_by'=>$company['primary_user_id'],
				// 			'approved_on'=>date("Y-m-d H:i:s"),
				// 			'target_companyid'=>$companyid
				// 			);					 
				// 			$wallet_transid = $this->Search_Model->save("wallet_transaction_tbl",$arr);

				// 			$mywallet = $this->Search_Model->get('system_wallets_tbl', array('id' => $current_user['wallet_id']));
				// 			$wl_balance = 0;
				// 			if($mywallet && count($mywallet)>0) {
				// 				$mywallet = $mywallet[0];

				// 				$wl_balance = floatval($mywallet['balance']);
				// 			}

				// 			$wl_balance -= $total_costprice;

				// 			$return = $this->Search_Model->update('system_wallets_tbl', array('balance' => $wl_balance), array('id' => $current_user['wallet_id']));
				// 			//save data to account_transactions_tbl;
				// 			//If wallet balance is there and amount realized from wallet then add that to accounts
				// 			if($total_costprice<=$wallet_amount) {
				// 				$arr=array(
				// 					"voucher_no" => $this->Search_Model->get_next_voucherno($company),
				// 					"transacting_companyid" => $companyid,
				// 					"transacting_userid" => $this->session->userdata('user_id'),
				// 					"documentid" => $wallet_transid,
				// 					"document_date" => $wallet_trans_date,
				// 					"document_type" => 2, /* Payment receive */
				// 					"credit" => $total_costprice,
				// 					"companyid" => $companyid,
				// 					"debited_accountid" => ($ticket_account==null? -1: $ticket_account['accountid']),
				// 					"created_by" => $this->session->userdata('user_id')
				// 				);
				// 				$voucher_no = $this->Search_Model->save("account_transactions_tbl",$arr);
				// 			}
				// 		}

				// 		$user['user_details']=$this->User_Model->user_details();	          							
				// 		$data = array(				            
				// 			'name' => $companyname,
				// 			'email'=>$user['user_details'][0]["email"],
				// 			'msg'=>"You have requested for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person. (Booking No. : ".$booking_id_new.")",
				// 			'msg1'=>"After Admin Approval of <span class='il'>$companyname</span> You booking will be confirm",
				// 			'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
				// 			);
				// 		$this->send("Booking",$data);
				
				
				// 		$data1 = array(				            
				// 			'name' => $user['user_details'][0]["name"],
				// 			'email'=>$user['user_details'][0]["email"],
				// 			'mobile'=>$user['user_details'][0]["mobile"],
				// 			'msg'=>"A New Booking Request by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person (Booking No. : ".$booking_id_new.")",
				// 			'user_id'=> $user['user_details'][0]["user_id"],
				// 			'msg1'=>'',
				// 			'msg2'=>""							 
				// 			);									 
				// 		$this->adminsend("Booking",$data1);

				// 		$no=$user['user_details'][0]["mobile"];
				// 		// $msg="You have Requested for a trip Request No. ".$booking_id." Thanks, OXYTRA";
				// 		$msg="You have Requested for a trip Request No. ".$booking_id_new." Thanks, $companyname";
						
				// 		//hiden for local testing only
				// 		if(SEND_EMAIL)
				// 			$this->send_message($no,$msg);
						
					
				// 		//$no="9800412356";
				// 		$no = $supplier_mobile;
				// 		$msg="A New Booking Requested done by User ID : ".$user['user_details'][0]["user_id"]." Request No. ".$booking_id_new."";
						
					
				// 	}
																																
				// 	$where=array("id"=>$id);
					
				// 	if($result["flight"][0]["sale_type"]=="live")
				// 	{
				// 		foreach($_REQUEST["prefix"] as $key=>$value)
				// 		{
				// 			$arr=array("prefix"=>$_REQUEST["prefix"][$key],
				// 						"first_name"=>$_REQUEST["first_name"][$key],
				// 						"last_name"=>$_REQUEST["last_name"][$key],
				// 						"mobile_no"=>$_REQUEST["mobile_no"][$key],
				// 						"age"=>$_REQUEST["age"][$key],
				// 						"email"=>$_REQUEST["email"][$key],
				// 						//"booking_id"=>$booking_id,
				// 						"companyid"=> $companyid,
				// 						"booking_id"=>$booking_id_new,
				// 						"refrence_id"=>$refrence_id,
				// 						"ticket_fare"=>($amount),
				// 						"costprice"=>($costprice),
				// 						"pnr"=>$result1[0]["pnr"],
				// 						"created_by"=>$this->session->userdata('user_id'),
				// 						"created_on"=>date("Y-m-d H:i:s")
				// 						);
				// 			$this->Search_Model->save("customer_information_tbl",$arr);
				// 		}
						
				// 		$current_no_of_person=$no_of_person-$this->input->post('qty');
				// 		$data=array("no_of_person"=>$current_no_of_person);
				// 		$this->Search_Model->update("tickets_tbl",$data,$where);
				// 	}
				// 	else
				// 	{
				// 		foreach($_REQUEST["prefix"] as $key=>$value)
				// 		{
				// 			$arr=array("prefix"=>$_REQUEST["prefix"][$key],
				// 						"first_name"=>$_REQUEST["first_name"][$key],
				// 						"last_name"=>$_REQUEST["last_name"][$key],
				// 						"mobile_no"=>$_REQUEST["mobile_no"][0], /* was $key */
				// 						"age"=>$_REQUEST["age"][$key], 
				// 						"ticket_fare"=>round($amount/$this->input->post('qty'), 0),
				// 						"costprice"=>round($costprice, 0),
				// 						"email"=>$_REQUEST["email"][0],  /* was $key */
				// 						"companyid"=> $companyid,
				// 						"booking_id"=>$booking_id_new,
				// 						"pnr"=>$result1[0]["pnr"],
				// 						"created_by"=>$this->session->userdata('user_id'),
				// 						"created_on"=>date("Y-m-d H:i:s")
				// 					);
				// 			$this->Search_Model->save("customer_information_tbl",$arr);
				// 		}
				// 	}
					
				// 	redirect("/search/thankyou/".$booking_id_new."");
				// }
				// else
				// 	echo $this->db->last_query();die();

				#endregion
			}
		}
	}

	protected function prepare_send_email($function, $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo) {
		$bookingid = $booking_info["booking_id"];
		switch ($function) {
			case 'BOOKING_CUSTOMER_EMAIL':
				$booking_data = $this->preparedata4booking($booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo);
				break;
			
			default:
				# code...
				break;
		}

		$flag = $this->send_email($function, "Booking : $bookingid", $company, $booking_data);

		return $flag;
	}

	protected function prepare_send_sms($function, $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo) {
		$bookingid = $booking_info["booking_id"];
		switch ($function) {
			case 'BOOKING_CUSTOMER_SMS':
				$booking_data = $this->preparesmsdata4booking($booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo);
				break;
			
			default:
				# code...
				break;
		}

		$flag = $this->send_sms($function, "Booking : $bookingid", $company, $booking_data);

		return $flag;
	}

	protected function preparedata4booking($booking_info, $company, $ticket, $customers, $posteddata, $flight, $booking) {
		$company = $this->get_companyinfo();
		$current_user = $company["current_user"];
		$templates = $this->getTemplates();
		$bookingid = "BK-".$booking_info["booking_id"];
		$pax = count($customers);

		$first_passenger_name = $customers[0]['prefix'].' '.$customers[0]['first_name'].' '.$customers[0]['last_name'];
		$to = $customers[0]['email'];
		$cc = ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? $current_user["email"] : $booking["email"];
		//$flight['source_city']
		
		$data = array(
            'company_name' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? $current_user["name"] : $company["display_name"],
			'phone_number' => $current_user["mobile"],
			'action_url' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? '': $company["baseurl"],
			'booking_status' => 'PENDING',
			'pnr' => 'PNR',
			'booking_number' => $bookingid,
			'booking_date' => $booking_info["booking_date"],
			'departure_city' => $flight['source_city'],
			'arrival_city' => $flight['destination_city'],
			'airline' => $ticket['aircode'],
			'flight_number' => $ticket['flight_no'],
			'departure_date' => $ticket['departure_date_time'],
			'arrival_date' => $ticket['arrival_date_time'],
			'no_of_pax' => $pax,
			'invoice_amount' => number_format($booking['amount'], 2),
			'first_passenger_name' => $first_passenger_name,
			'companing_count' => '+'.($pax-1),
			'to' => $to,
			'cc' => $cc,
        );

		return $data;
	}

	protected function preparesmsdata4booking($booking_info, $company, $ticket, $customers, $posteddata, $flight, $booking) {
		$company = $this->get_companyinfo();
		$current_user = $company["current_user"];
		$templates = $this->getTemplates();
		$bookingid = "BK-".$booking_info["booking_id"];
		$pax = count($customers);

		$first_passenger_name = $customers[0]['prefix'].' '.$customers[0]['first_name'].' '.$customers[0]['last_name'];
		$to = $customers[0]['mobile_no'];
		$cc = '';
		//$flight['source_city']
		
		$data = array(
            'company_name' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? $current_user["name"] : $company["display_name"],
			'phone_number' => $current_user["mobile"],
			'action_url' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? '': $company["baseurl"],
			'booking_status' => 'PENDING',
			'pnr' => 'PNR',
			'booking_number' => $bookingid,
			'booking_date' => $booking_info["booking_date"],
			'departure_city' => $flight['source_city'],
			'arrival_city' => $flight['destination_city'],
			'airline' => $ticket['aircode'],
			'flight_number' => $ticket['flight_no'],
			'departure_date' => $ticket['departure_date_time'],
			'arrival_date' => $ticket['arrival_date_time'],
			'no_of_pax' => $pax,
			'invoice_amount' => number_format($booking['amount'], 2),
			'first_passenger_name' => $first_passenger_name,
			'companing_count' => '+'.($pax-1),
			'to' => $to,
			'cc' => $cc,
        );

		return $data;
	}

	protected function save_booking($current_user, $ticket, $status, $wallet, $company, $posteddata, $customers) {
		$booking_date = date("Y-m-d H:i:s");
		$arr = array(
			"booking_date"=>$booking_date,
			"ticket_id"=>$ticket["id"],
			"pnr"=>$ticket["pnr"],
			"customer_userid"=>$current_user["id"],
			"customer_companyid"=>$company["id"],
			"seller_userid"=>$ticket['seller_userid'],
			"seller_companyid"=>$ticket['seller_companyid'],
			"status"=>0,
			"price"=>floatval($posteddata['price']),
			"admin_markup"=>floatval($ticket['adminmarkup']),
			"markup"=>0,
			"srvchg"=>floatval($posteddata['service_charge']),
			"cgst"=>floatval($posteddata['igst'])/2, /* This is wrong should be directly taken from cgst value */
			"sgst"=>floatval($posteddata['igst'])/2, /* This is wrong should be directly taken from sgst value */
			"igst"=>floatval($posteddata['igst']),
			"total"=>floatval($posteddata['total_amount']),
			"costprice"=>floatval($posteddata['costprice']),
			"rateplanid"=>intval($posteddata['rateplanid']),
			"qty"=>intval($posteddata['qty']),
			"adult"=>intval($posteddata['qty']),
			"created_by"=>$current_user["id"],
			"requesting_by"=>intval($ticket['requesting_by']),
			"target_userid"=>intval($ticket['seller_userid']),
			"target_companyid"=>intval($ticket['seller_companyid']),
			"requesting_to"=>intval($ticket['requesting_to']),
			"debit" => (($current_user["type"]=='B2B' && $current_user["is_admin"]!='1')? floatval($posteddata['costprice'] * intval($posteddata['qty'])) : floatval($posteddata['total_amount'])),
			"ticket_account" => $company['ticket_sale_account']==null? -1 : $company['ticket_sale_account']['accountid']
		);

		if(floatval($posteddata['total_amount'])>0) {
			//insert data into booking_activity_tbl
			$booking_result = $this->Search_Model->book_ticket($arr, $company, $current_user, $ticket, $wallet, $posteddata, $customers);
			return array("booking_id" => $booking_result['booking_id'], "booking_date" => $arr['booking_date'], "booking_activity_id" => $booking_result['booking_activity_id'], "voucher_no" => $booking_result['voucher_no']);
		}
		else {
			return array("booking_id" => -1, "booking_date" => date("Y-m-d H:i:s"), "booking_activity_id" => -1, "voucher_no" => -1);
		}
	}

	protected function do_wallet_transaction($current_user, $company, $ticket, $postedvalue) {
		$amount=floatval($postedvalue['total_costprice']); //floatval($this->input->post('costprice'));
		$booking_id = intval($postedvalue['booking_id']);
		$booking_date = intval($postedvalue['booking_date']);
		$companyid = intval($company['id']);
		$walletid = intval($current_user['wallet_id']);
		$userid = intval($current_user['id']);
		$ticket_account = $company['ticket_sale_account'];
		$wallet_transid = 0;
		$voucher_no = 0;
		
		if ($current_user['is_admin']=='0') {
			log_message('info', "[Search:do_wallet_transaction] | User Id: $userid | Wallet Id: $walletid | User is not admin - ".json_encode($current_user));
			$wallet_trans_date = date("Y-m-d H:i:s");
			//=> intval($current_user['wallet_id'])
			$arr=array(
				'wallet_id'=>$walletid,
				'date'=>$wallet_trans_date,
				'trans_id'=>uniqid(),
				'companyid'=>$companyid,
				'userid'=>intval($current_user['id']),
				'amount'=>($amount),
				'dr_cr_type'=>'DR',
				'trans_type'=>20, /*20 is for Ticket Booking */
				'trans_ref_id'=>$booking_id,
				'trans_ref_date'=>$booking_date,
				'trans_ref_type'=>'PURCHASE',
				'trans_documentid'=>$booking_id,
				'narration'=>"New ticket booking raised (id: $booking_id)",
				'sponsoring_companyid'=>$current_user['sponsoring_companyid'],
				'created_by'=>intval($current_user['id']),
				'status'=>1,
				'approved_by'=>$company['primary_user_id'],
				'approved_on'=>date("Y-m-d H:i:s"),
				'target_companyid'=>$companyid
			);					 
			$wallet_transid = $this->Search_Model->save("wallet_transaction_tbl",$arr);

			log_message('info', "[Search:do_wallet_transaction] | User Id: $userid | Wallet transaction successful - $wallet_transid | Amount : $amount");

			$mywallet = $this->Search_Model->get('system_wallets_tbl', array('id' => $current_user['wallet_id']));
			$wl_balance = 0;
			if($mywallet && count($mywallet)>0) {
				$mywallet = $mywallet[0];

				$wl_balance = floatval($mywallet['balance']);
				$wallet_amount = $wl_balance;
			}

			log_message('info', "[Search:do_wallet_transaction] | User Id: $userid | Wallet Id: $walletid | Current Balance: $wl_balance | Transaction amount: $amount | New Balance: ". ($wl_balance-$amount));
			$wl_balance -= $amount;
			$voucher_no = -1;
			$return = $this->Search_Model->update('system_wallets_tbl', array('balance' => $wl_balance), array('id' => $current_user['wallet_id']));
			log_message('info', "[Search:do_wallet_transaction] Wallet Id: $walletid | New wallet balance updated: $wl_balance | Wallet Balance: $wallet_amount");
			//save data to account_transactions_tbl;
			//If wallet balance is there and amount realized from wallet then add that to accounts
			//if($amount<=$wallet_amount) {
			if($wallet_amount>0) {
				log_message('info', "[Search:do_wallet_transaction] Transacting Accounts | User Id: $userid | Wallet Id: $walletid | Previous Wallet Balance: $wallet_amount | Transaction amount: $amount");

				$arr=array(
					"voucher_no" => $this->Search_Model->get_next_voucherno($company),
					"transacting_companyid" => $companyid,
					"transacting_userid" => intval($current_user['id']),
					"documentid" => $wallet_transid,
					"document_date" => $wallet_trans_date,
					"document_type" => 2, /* Payment receive */
					"credit" => ($amount<=$wallet_amount)?$amount:$wallet_amount,
					"companyid" => $companyid,
					"debited_accountid" => ($ticket_account==null? -1: $ticket_account['accountid']),
					"created_by" => intval($current_user['id'])
				);
				$voucher_no = $this->Search_Model->save("account_transactions_tbl",$arr);
			}
			else {
				log_message('info', "[Search:do_wallet_transaction] Enough wallet balance not present | User Id: $userid | Wallet Id: $walletid | Previous Wallet Balance: $wallet_amount | Transaction amount: $amount");
			}
		}

		return array('wallet_transid' => $wallet_transid, 'voucher_no' => $voucher_no);
	}
	
	protected function get_ticket($ticketid, $current_user, $company) {
		$CI =   &get_instance();
		$ticket = $CI->db->get_where('tickets_tbl', array('id' => $ticketid));
		$result1=$ticket->result_array();
		
		if(isset($result1) && count($result1)>0) {
			$ticket = $result1[0];
		} else {
			$ticket = NULL;

			return $ticket;
		}

		$no_of_person=$ticket["no_of_person"];
		$user_id=$ticket["user_id"];
		$pnr=$ticket["pnr"];
		$userdetails = $this->User_Model->user_details($user_id);
		if($userdetails && count($userdetails)>0) {
			$userdetails = $userdetails[0];
		}
		$ticket['supplier_user_details']=$userdetails;
		if($current_user['type'] === 'B2B' && $current_user['is_admin']!='1') {
			$ticket['user_markup']=$this->User_Model->user_settings($current_user['id'], array('markup'));
		}
		else {
			$ticket['user_markup']= 0;
		}

		$requesting_by = 1;
		$requesting_to = 4;
		$seller_userid = $ticket['supplier_user_details']['id'];
		$seller_companyid = $ticket['supplier_user_details']['companyid'];
		$adminmarkup = 0;
		if($current_user["is_admin"]!=='1' && $current_user["type"]!=='EMP' && ($current_user["type"]==='B2B' || $current_user["type"]==='B2C'))
		{
			if($current_user["type"]==='B2B') {
				if($ticket['user_markup']!==NULL) {
					if($ticket['user_markup']['field_value_type'] === '2') {
						$adminmarkup = floatval($ticket['user_markup']['field_value']);
					}
				}
				$requesting_by = 2;
				$seller_userid = $company['primary_user_id'];
				$seller_companyid = $company['id'];
			}
			else {
				$adminmarkup = 0;
				$requesting_by = 1;
				$seller_userid = $company['primary_user_id'];
				$seller_companyid = $company['id'];
			}
		}
		if($current_user["is_admin"]==='1' || $current_user["type"]==='EMP') {
			$requesting_by = 4;
			$seller_userid = $company['primary_user_id'];
			$seller_companyid = $company['id'];
		}

		$ticket['seller_userid'] = $seller_userid;
		$ticket['seller_companyid'] = $seller_companyid;
		$ticket['requesting_by'] = $requesting_by;
		$ticket['requesting_to'] = $requesting_to;
		$ticket['adminmarkup'] = $adminmarkup;

		log_message("info", "book.get_ticket-ticket - ".json_encode($ticket));
		log_message("info", "book.get_ticket-company - ".json_encode($company));
		log_message("info", "book.get_ticket-current_user - ".json_encode($current_user));

		return $ticket;
	}

	protected function is_booking_allowed($totalcostprice, $current_user, $wallet) {
		$user = $this->User_Model->user_details($current_user['id']);
		$credit_limit = 999999999; /* This should be actual credit limit */
		$wallet_balance = floatval($wallet["balance"]);

		log_message("info", "Ticket Cost: $totalcostprice | Wallet Balance: $wallet_balance | user id: ".$current_user['id']." | credit allowed: ".intval($current_user["credit_ac"])." | is_admin: ".intval($current_user['is_admin']));

		//$flag = $totalcostprice>floatval($wallet["balance"]) && intval($current_user['is_admin'])!=1 && intval($current_user["credit_ac"])==0;

		//We need to check credit limit
		$flag = (floatval($wallet["balance"])>=$totalcostprice) || intval($current_user['is_admin'])===1 || (intval($current_user["credit_ac"])===1 && $totalcostprice<=$credit_limit);

		return $flag;
	}

	// protected function get_companyinfo()
	// {
	// 	$company = $this->session->userdata('company');
	// 	$companyinfo = &$company;
	// 	$companyinfo['configuration'] = json_decode($company['setting']['configuration'], true);
	// 	$companyinfo['account_settings'] = isset($companyinfo['configuration']['account_settings'])?$companyinfo['configuration']['account_settings']:array();
	// 	$company_account_settings = $companyinfo['account_settings'];
		
	// 	$ticket_account = null;
	// 	if(count($company_account_settings)>0) {
	// 		for($idx=0; $idx<count($company_account_settings); $idx++) {
	// 			if($company_account_settings[$idx]['module']=='ticket_sale') {
	// 				$ticket_account = $company_account_settings[$idx];
	// 				break;
	// 			}
	// 		}
	// 	}
	// 	$companyinfo['ticket_sale_account'] = $ticket_account;
	// 	$companyinfo['current_user'] = $this->session->userdata('current_user');

	// 	log_message("info", "Company Info : ".json_encode($companyinfo));

	// 	return $companyinfo;
	// }

	protected function is_booking_valid($companyid) {
		$hasduplicatecustomers = false;
		$customers = array();
		$customerlist = array();
		if(isset($_REQUEST["prefix"])) {
			foreach($_REQUEST["prefix"] as $key=>$value)
			{
				$prefix = $_REQUEST["prefix"][$key];
				$first_name = $_REQUEST["first_name"][$key];
				$last_name = $_REQUEST["last_name"][$key];

				$uniquekey = $prefix.'_'.$first_name.'_'.$last_name;

				if($prefix=='' || $first_name=='' || $last_name=='') {
					$hasduplicatecustomers = true;
					//break;
				}

				if(!isset($customers[$uniquekey])) {
					$customers[$uniquekey] = true;
				}
				else {
					$hasduplicatecustomers = true;
					//break;
				}

				$customer=array("prefix"=>$_REQUEST["prefix"][$key],
					"first_name"=>$_REQUEST["first_name"][$key],
					"last_name"=>$_REQUEST["last_name"][$key],
					"mobile_no"=>$_REQUEST["mobile_no"][0], 
					"age"=>$_REQUEST["age"][$key], 
					"ticket_fare"=>0,
					"costprice"=>0,
					"email"=>$_REQUEST["email"][0],  
					"companyid"=> $companyid,
					"booking_id"=>-1,
					"pnr"=>'',
					"created_by"=>-1,
					"created_on"=>date("Y-m-d H:i:s")
				);

				array_push($customerlist, $customer);
			}
		}
		else {
			redirect("/search");
		}

		log_message("info", "book.is_booking_valid-customers - ".json_encode($customerlist));
		log_message("info", "book.is_booking_valid-hasduplicatecustomers - ".$hasduplicatecustomers);

		return array('hasduplicatecustomers'=> !$hasduplicatecustomers, 'customers'=> $customerlist);
	}

	protected function get_walletbalance($current_user) {
		$CI =   &get_instance();
			
		$userid = $current_user['id'];
		$companyid = $current_user['companyid'];
		if(($current_user['type']=='B2B' || $current_user['type']=='B2C') && $current_user['is_admin']!=1) {
			$check = $CI->db->get_where('system_wallets_tbl', array('userid' => $userid, 'type' => 2));
		} else {
			$check = $CI->db->get_where('system_wallets_tbl', array('companyid' => $companyid, 'type' => 1, 'sponsoring_companyid' => -1));
		}

		$result=$check->result_array();

		if($result && count($result)) {
			$result = $result[0];
		}
		else {
			$result = array('balance' => 0.0);
		}
		return $result;
	}
	
	public function book_old($id)
	{
		$company = $this->session->userdata('company');
		$company_configuration = json_decode($company['setting']['configuration'], true);
		$company_account_settings = isset($company_configuration['account_settings'])?$company_configuration['account_settings']:array();
		$ticket_account = null;

		if(count($company_account_settings)>0) {
			for($idx=0; $idx<count($company_account_settings); $idx++) {
				if($company_account_settings[$idx]['module']=='ticket_sale') {
					$ticket_account = $company_account_settings[$idx];
					break;
				}
			}
		}
		$companyid = $company["id"];
		$companyname = $company["display_name"];
		$current_user = $this->session->userdata('current_user');

		$result["footer"]=$this->Search_Model->get_post(5);
		$wallet_amount=0;
		$customers = array();
		$hasduplicatecustomers = false;

		if(isset($_REQUEST["prefix"])) {
			foreach($_REQUEST["prefix"] as $key=>$value)
			{
				$prefix = $_REQUEST["prefix"][$key];
				$first_name = $_REQUEST["first_name"][$key];
				$last_name = $_REQUEST["last_name"][$key];

				$key = $prefix.'_'.$first_name.'_'.$last_name;

				if($prefix=='' || $first_name=='' || $last_name=='') {
					$hasduplicatecustomers = true;
					break;
				}

				if(!isset($customers[$key])) {
					$customers[$key] = true;
				}
				else {
					$hasduplicatecustomers = true;
					break;
				}
			}
		}
		else {
			redirect("/search");
		}

		if($hasduplicatecustomers) {
			redirect("search/beforebook/$id");
		}

		if ($this->input->post('date') && $this->input->post('qty')) 
		{
			$CI =   &get_instance();
			
			$userid = $current_user['id'];
			if(($current_user['type']=='B2B' || $current_user['type']=='B2C') && $current_user['is_admin']!=1) {
				$check = $CI->db->get_where('system_wallets_tbl', array('userid' => $userid, 'type' => 2));
			} else {
				$check = $CI->db->get_where('system_wallets_tbl', array('companyid' => $companyid, 'type' => 1, 'sponsoring_companyid' => -1));
			}

			$result=$check->result_array();
			
			$ticket = $CI->db->get_where('tickets_tbl', array('id' => $id));				
			$result1=$ticket->result_array();
			$no_of_person=$result1[0]["no_of_person"];
			$user_id=$result1[0]["user_id"];
			$pnr=$result1[0]["pnr"];
			$amount=floatval($this->input->post('total'));
			$costprice=floatval($this->input->post('costprice'));
			$qty=intval($this->input->post('qty'));
			$user['user_details']=$this->User_Model->user_details();
			if($current_user['type'] === 'B2B' && $current_user['is_admin']!='1') {
				$user['user_markup']=$this->User_Model->user_settings($current_user['id'], array('markup'));
			}
			else {
				$user['user_markup']= 0;
			}

			$users = $this->User_Model->get_users($result1[0]["companyid"]);
			if($users!==null && count($users)>0) {
				for ($i=0; $i < count($users); $i++) { 
					if($users[$i]["active"]==='1' && $users[$i]["is_admin"]==='1') {
						$user['supplier_user_details'] = $users[$i];
						break;
					}
				}
			}
			
			$supplier_mobile = $user['supplier_user_details']['mobile'];
			$wallet_amount = 0;
			if($result && count($result)>0) {
				$wallet_amount = floatval($result[0]['balance']);
			}

			$total_costprice = ($qty * $costprice);

			//if($amount>$wallet_amount && $this->session->userdata('user_id')!=$user_id && $user['user_details'][0]["credit_ac"]==0)
			if($total_costprice>$wallet_amount && $current_user['is_admin']!='1' && $user['user_details'][0]["credit_ac"]==0)
			{
				$result["footer"]=$this->Search_Model->get_post(5);
				$result["setting"]=$this->Search_Model->setting();
				$current_user = $this->session->userdata("current_user");

				$result['mywallet']= $this->getMyWallet();
			
				$this->load->view('header1',$result);
				$this->load->view('insufficient_amount',$result);
				$this->load->view('footer1');
			}
			else
			{
				$result=$this->User_Model->user_details();
				if($pnr!="" && $this->session->userdata('user_id')==$user_id)
					$status="CONFIRM";
				else
					$status="PENDING";	
				$booking_id = -1;

				$requesting_by = 1;
				$requesting_to = 4;
				$seller_userid = $user['supplier_user_details']['id'];
				$seller_companyid = $user['supplier_user_details']['companyid'];
				$adminmarkup = 0;
				if($current_user["is_admin"]!=='1' && $current_user["type"]!=='EMP' && ($current_user["type"]==='B2B' || $current_user["type"]==='B2C'))
				{
					if($current_user["type"]==='B2B') {
						// $adminmarkup = $result1[0]["admin_markup"];
						if($user['user_markup']!==NULL) {
							if($user['user_markup']['field_value_type'] === '2') {
								$adminmarkup = floatval($user['user_markup']['field_value']);
							}
						}
						$requesting_by = 2;
						$seller_userid = $company['primary_user_id'];
						$seller_companyid = $company['id'];
					}
					else {
						$adminmarkup = 0;
						$requesting_by = 1;
						$seller_userid = $company['primary_user_id'];
						$seller_companyid = $company['id'];
					}
				}
				if($current_user["is_admin"]==='1' || $current_user["type"]==='EMP') {
					$requesting_by = 4;
					// $requesting_to = 4;
					$seller_userid = $company['primary_user_id'];
					$seller_companyid = $company['id'];
				}

				//insert data into bookings_tbl
				$booking_date = date("Y-m-d H:i:s");
				$arr=array(
					"booking_date"=>$booking_date,
					"ticket_id"=>$id,
					"pnr"=>$result1[0]["pnr"],
					"customer_userid"=>$this->session->userdata('user_id'),
					"customer_companyid"=>$companyid,
					"seller_userid"=>$seller_userid,
					"seller_companyid"=>$seller_companyid,
					"status"=>0,
					//"price"=>$this->input->post('price')*$this->input->post('qty'),
					"price"=>$this->input->post('price'),
					"admin_markup"=>$adminmarkup,
					"markup"=>$adminmarkup,
					"srvchg"=>$this->input->post('service_charge'),
					"cgst"=>$this->input->post('igst')/2,
					"sgst"=>$this->input->post('igst')/2,
					"igst"=>$this->input->post('igst'),
					"total"=>$amount,
					"costprice"=>$costprice,
					"rateplanid"=>$this->input->post('rateplanid'),
					"qty"=>$this->input->post('qty'),
					"adult"=>$this->input->post('qty'),
					"created_by"=>$this->session->userdata('user_id'),
					"created_on"=>date("Y-m-d H:i:s"),
				);
				$booking_id_new = $this->Search_Model->save("bookings_tbl",$arr);

				//insert data into booking_activity_tbl
				$arr=array(
					"booking_id"=>$booking_id_new,
					"activity_date"=>$booking_date,  //date("Y-m-d H:i:s"),
					"source_userid"=>$this->session->userdata('user_id'),
					"source_companyid"=>$companyid,
					"requesting_by"=>$requesting_by,
					"target_userid"=>$seller_userid,
					"target_companyid"=>$seller_companyid,
					"requesting_to"=>$requesting_to,
					"status"=>0,
					"notes"=>'',
					"created_by"=>$this->session->userdata('user_id'),
					"created_on"=>date("Y-m-d H:i:s"),
				);
				$booking_activity_id = $this->Search_Model->save("booking_activity_tbl",$arr);

				//insert into accounts account_transactions_tbl
				//$this->Search_Model->get_next_voucherno($companyid);
				$arr=array(
					"voucher_no" => $this->Search_Model->get_next_voucherno($company),
					"transacting_companyid" => $companyid,
					"transacting_userid" => $this->session->userdata('user_id'),
					"documentid" => $booking_id_new,
					"document_date" => $booking_date,
					"document_type" => 1,
					"debit" => (($current_user["type"]=='B2B' && $current_user["is_admin"]!='1')? $costprice : $total_costprice),
					"companyid" => $companyid,
					"credited_accountid" => ($ticket_account==null? -1: $ticket_account['accountid']),
					"created_by" => $this->session->userdata('user_id')
				);
				$voucher_no = $this->Search_Model->save("account_transactions_tbl",$arr);

				$result["flight"]=$this->Search_Model->flight_details($id, $companyid); 
				if($result["flight"][0]["trip_type"]=="ONE")
					$trip="ONE WAY";
				else
					$trip="RETURN";
				if($booking_id_new)	
				{
					if($result["flight"][0]["sale_type"]=="live")
					{
						// FOR LIVE BOOKING
						// $arr=array(
						// "date"=>date("Y-m-d h:i:s"),
						// "ticket_id"=>$id,
						// "seller_id"=>$result1[0]["user_id"],
						// "pnr"=>$result1[0]["pnr"],
						// "customer_id"=>$this->session->userdata('user_id'),
						// "qty"=>$this->input->post('qty'),
						
						// "rate"=>$this->input->post('price'),
						// "amount"=>$this->input->post('price')*$this->input->post('qty'),
						// "service_charge"=>$this->input->post('service_charge'),						
						// "igst"=>$this->input->post('igst'),
						// "total"=>$amount,
						// "costprice"=>$costprice,
						// "type"=>$user['user_details'][0]["type"],
						// "status"=>$status,
						// "booking_id"=>$booking_id_new
						// );
						$refrence_id = -1;
						//$refrence_id=$this->Search_Model->save("refrence_booking_tbl",$arr);
						// FOR LIVE BOOKING
					}
					
					if($this->session->userdata('user_id')==$user_id)
					{
						$amount=$this->input->post('total');
						// $arr=array(
						// 'date'=>date("Y-m-d H:i:s"),
						// 'user_id'=>$this->session->userdata('user_id'),
						// //'amount'=>(0-($result["flight"][0]["price"]*$this->input->post('qty'))),
						// 'amount'=>(0-$costprice),
						// 'booking_id'=>$booking_id_new,
						// 'type'=>'DR'
						// );					 
						// $this->Search_Model->save("wallet_tbl",$arr);
						
						
						// $amount=$this->input->post('total');
						// $arr=array(
						// 'date'=>date("Y-m-d H:i:s"),
						// 'user_id'=>$result1[0]["user_id"],
						// 'amount'=>($amount),
						// 'booking_id'=>$booking_id_new,
						// 'type'=>'CR'
						// );	
						
						// $this->Search_Model->save("wallet_tbl",$arr);

						// Don't reduce the ticket count on self booking. That also has to be reviewed.
						// $where=array("id"=>$id);
						// $current_no_of_person=$no_of_person-$this->input->post('qty');
						// $data=array("no_of_person"=>$current_no_of_person);
						// $this->Search_Model->update("tickets_tbl",$data,$where);
						
						$user['user_details']=$this->User_Model->user_details();	          							
						$data = array(				            
							'name' => $companyname,
							'email'=>$user['user_details'][0]["email"],
							'msg'=>"You have Booked  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person. (Booking No. : ".$booking_id_new.")",
							'msg1'=>' You booking is confirm',
							'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
							);
						$this->send("Booking",$data);
				
				
						$data1 = array(				            
							'name' => $user['user_details'][0]["name"],
							'email'=>$user['user_details'][0]["email"],
							'mobile'=>$user['user_details'][0]["mobile"],
							'msg'=>"A New Booking done by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person (Booking No. : ".$booking_id_new.")",
							'user_id'=> $user['user_details'][0]["user_id"],
							'msg1'=>'',
							'msg2'=>""							 
							);									 
						$this->adminsend("Booking",$data1);
						$no=$user['user_details'][0]["mobile"];
						$msg="You have Booked a trip Booking No. ".$booking_id_new." Thanks, $companyname";
						$this->send_message($no,$msg);
						
					
						//$no="9800412356";
						$no = $supplier_mobile;
						$msg="A New Booking done by User ID : ".$user['user_details'][0]["user_id"]." Booking No. ".$booking_id_new."";
						$this->send_message($no,$msg);
					}							 
					else
					{		
						$amount=$total_costprice; //floatval($this->input->post('costprice'));
						
						if ($current_user['is_admin']=='0') {
							$wallet_trans_date = date("Y-m-d H:i:s");
							$arr=array(
							'wallet_id'=>$current_user['wallet_id'],
							'date'=>$wallet_trans_date,
							'trans_id'=>uniqid(),
							'companyid'=>$companyid,
							'userid'=>$this->session->userdata('user_id'),
							//'amount'=>(0-$amount),
							'amount'=>($total_costprice),
							'dr_cr_type'=>'DR',
							'trans_type'=>20, /*20 is for Ticket Booking */
							'trans_ref_id'=>$booking_id_new,
							'trans_ref_date'=>$booking_date,
							'trans_ref_type'=>'PURCHASE',
							'trans_documentid'=>$booking_id_new,
							'narration'=>"New ticket booking raised (id: $booking_id_new)",
							'sponsoring_companyid'=>$current_user['sponsoring_companyid'],
							'created_by'=>$this->session->userdata('user_id'),
							'status'=>1,
							'approved_by'=>$company['primary_user_id'],
							'approved_on'=>date("Y-m-d H:i:s"),
							'target_companyid'=>$companyid
							);					 
							$wallet_transid = $this->Search_Model->save("wallet_transaction_tbl",$arr);

							$mywallet = $this->Search_Model->get('system_wallets_tbl', array('id' => $current_user['wallet_id']));
							$wl_balance = 0;
							if($mywallet && count($mywallet)>0) {
								$mywallet = $mywallet[0];

								$wl_balance = floatval($mywallet['balance']);
							}

							$wl_balance -= $total_costprice;

							$return = $this->Search_Model->update('system_wallets_tbl', array('balance' => $wl_balance), array('id' => $current_user['wallet_id']));
							//save data to account_transactions_tbl;
							//If wallet balance is there and amount realized from wallet then add that to accounts
							if($total_costprice<=$wallet_amount) {
								$arr=array(
									"voucher_no" => $this->Search_Model->get_next_voucherno($company),
									"transacting_companyid" => $companyid,
									"transacting_userid" => $this->session->userdata('user_id'),
									"documentid" => $wallet_transid,
									"document_date" => $wallet_trans_date,
									"document_type" => 2, /* Payment receive */
									"credit" => $total_costprice,
									"companyid" => $companyid,
									"debited_accountid" => ($ticket_account==null? -1: $ticket_account['accountid']),
									"created_by" => $this->session->userdata('user_id')
								);
								$voucher_no = $this->Search_Model->save("account_transactions_tbl",$arr);
							}
						}

						$user['user_details']=$this->User_Model->user_details();	          							
						$data = array(				            
							'name' => $companyname,
							'email'=>$user['user_details'][0]["email"],
							'msg'=>"You have requested for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person. (Booking No. : ".$booking_id_new.")",
							'msg1'=>"After Admin Approval of <span class='il'>$companyname</span> You booking will be confirm",
							'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
							);
						$this->send("Booking",$data);
				
				
						$data1 = array(				            
							'name' => $user['user_details'][0]["name"],
							'email'=>$user['user_details'][0]["email"],
							'mobile'=>$user['user_details'][0]["mobile"],
							'msg'=>"A New Booking Request by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person (Booking No. : ".$booking_id_new.")",
							'user_id'=> $user['user_details'][0]["user_id"],
							'msg1'=>'',
							'msg2'=>""							 
							);									 
						$this->adminsend("Booking",$data1);

						$no=$user['user_details'][0]["mobile"];
						// $msg="You have Requested for a trip Request No. ".$booking_id." Thanks, OXYTRA";
						$msg="You have Requested for a trip Request No. ".$booking_id_new." Thanks, $companyname";
						
						//hiden for local testing only
						if(SEND_EMAIL)
							$this->send_message($no,$msg);
						
					
						//$no="9800412356";
						$no = $supplier_mobile;
						$msg="A New Booking Requested done by User ID : ".$user['user_details'][0]["user_id"]." Request No. ".$booking_id_new."";
						
					
					}
																																
					$where=array("id"=>$id);
					
					if($result["flight"][0]["sale_type"]=="live")
					{
						foreach($_REQUEST["prefix"] as $key=>$value)
						{
							$arr=array("prefix"=>$_REQUEST["prefix"][$key],
										"first_name"=>$_REQUEST["first_name"][$key],
										"last_name"=>$_REQUEST["last_name"][$key],
										"mobile_no"=>$_REQUEST["mobile_no"][$key],
										"age"=>$_REQUEST["age"][$key],
										"email"=>$_REQUEST["email"][$key],
										//"booking_id"=>$booking_id,
										"companyid"=> $companyid,
										"booking_id"=>$booking_id_new,
										"refrence_id"=>$refrence_id,
										"ticket_fare"=>($amount),
										"costprice"=>($costprice),
										"pnr"=>$result1[0]["pnr"],
										"created_by"=>$this->session->userdata('user_id'),
										"created_on"=>date("Y-m-d H:i:s")
										);
							$this->Search_Model->save("customer_information_tbl",$arr);
						}
						
						$current_no_of_person=$no_of_person-$this->input->post('qty');
						$data=array("no_of_person"=>$current_no_of_person);
						$this->Search_Model->update("tickets_tbl",$data,$where);
					}
					else
					{
						foreach($_REQUEST["prefix"] as $key=>$value)
						{
							$arr=array("prefix"=>$_REQUEST["prefix"][$key],
										"first_name"=>$_REQUEST["first_name"][$key],
										"last_name"=>$_REQUEST["last_name"][$key],
										"mobile_no"=>$_REQUEST["mobile_no"][0], /* was $key */
										"age"=>$_REQUEST["age"][$key], 
										"ticket_fare"=>round($amount/$this->input->post('qty'), 0),
										"costprice"=>round($costprice, 0),
										"email"=>$_REQUEST["email"][0],  /* was $key */
										"companyid"=> $companyid,
										"booking_id"=>$booking_id_new,
										"pnr"=>$result1[0]["pnr"],
										"created_by"=>$this->session->userdata('user_id'),
										"created_on"=>date("Y-m-d H:i:s")
									);
							$this->Search_Model->save("customer_information_tbl",$arr);
						}
					}
					
					redirect("/search/thankyou/".$booking_id_new."");
				}
				else
					echo $this->db->last_query();die();
			}					
				
		}
	}

	private function get_wallet_balance($current_user) {
		$userid = intval($current_user['id']);
		$type = $current_user['type'];
		$companyid = intval($current_user['companyid']);
		$isadmin = intval($current_user['is_admin']);

		$CI =   &get_instance();

		if(($current_user['type']=='B2B' || $current_user['type']=='B2C') && $current_user['is_admin']!=1) {
			$check = $CI->db->get_where('system_wallets_tbl', array('userid' => $userid, 'type' => 2));
		} else {
			$check = $CI->db->get_where('system_wallets_tbl', array('companyid' => $companyid, 'type' => 1, 'sponsoring_companyid' => -1));
		}

		$result=$check->result_array();	

		// $check = $CI->db->get_where('wallet_tbl', array('user_id' => $userid));
		// $result=$check->result_array();
		$wallet_amount = 0;
		if($result && count($result) > 0) {
			$wallet_amount = floatval($result[0]['balance']);
		}
		
		// foreach($result as $key=>$value)
		// {
		// 	$wallet_amount+=$result[$key]["amount"];
		// }

		return $wallet_amount;
	}

	public function sendquote($id)
	{
	    if($_SERVER['REQUEST_METHOD'] == 'POST') 		  
		{
			$company = $this->session->userdata('company');
			$companyid = $company["id"];

		$user['user_details']=$this->User_Model->user_details();
		$result["flight"]=$this->Search_Model->flight_details($id, $companyid);
		
		if($result["flight"][0]["trip_type"]=="ONE")
			 $trip="ONE WAY";
		 else
			 $trip="RETURN";
			 if($result["flight"][0]["trip_type"]=="ONE")
			 {
			  $data = array(				            
					 'customer_id' => $user['user_details'][0]["id"],
					 'supplier_id' => $result['flight'][0]["user_id"],
					 'request_date'=>date("Y-m-d h:i:s"),
					 'trip_type'=>$trip,
					 'departure_date_time'=>date('Y-m-d', strtotime($result["flight"][0]["departure_date_time"])),
					 'source_city'=>$result["flight"][0]["source_city"],
					 'destination_city'=>$result["flight"][0]["destination_city"],
					 'no_of_person'=>$this->input->post('no_of_person')
					 );	
			 }
			 else
			 {
			     $data = array(				            
					 'customer_id' => $user['user_details'][0]["id"],
					 'supplier_id' => $result['flight'][0]["user_id"],
					 'request_date'=>date("Y-m-d h:i:s"),
					 'trip_type'=>$trip,
					 'departure_date_time'=>date('Y-m-d', strtotime($result["flight"][0]["departure_date_time"])),
					 'departure_date_time1'=>date('Y-m-d', strtotime($result["flight"][0]["departure_date_time1"])),
					 'source_city'=>$result["flight"][0]["source_city"],
					 'destination_city'=>$result["flight"][0]["destination_city"],
					 'no_of_person'=>$this->input->post('no_of_person')
					 );	
			 }
		$insert = $this->User_Model->save("quotation_tbl",$data);
		if($insert==true)
		{			   if($result["flight"][0]["trip_type"]=="ONE")
		               {
		                   $msg="Quotation Requested by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." Departure at ".date("Y-m-d",strtotime($result["flight"][0]['departure_date_time']))." for ".$this->input->post('no_of_person')." Passengers";
		               }
		               else
		               {
		                   $msg="Quotation Requested by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." Departure at ".date("Y-m-d",strtotime($result["flight"][0]['departure_date_time']))." Returning at ".date("Y-m-d",strtotime($result["flight"][0]['departure_date_time1']))." for ".$this->input->post('no_of_person')." Passengers";
		               }
    		            $data1 = array(				            
    					 'name' => $user['user_details'][0]["name"],
    					 'email'=>$user['user_details'][0]["email"],
    					 'mobile'=>$user['user_details'][0]["mobile"],
    					 'msg'=>$msg,
    					 'user_id'=> $user['user_details'][0]["user_id"],
    					 'msg1'=>'',
    					 'msg2'=>""							 
    					 );									 
    				    $this->adminsend("Quotation",$data1);
    				    
    				
    					$no="9800412356";
    					//$no="8235266775";
    					if($result["flight"][0]["trip_type"]=="ONE")
    					   $msg="Quote Requested By ".$user['user_details'][0]["user_id"]." for  (".$trip.")  from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]."  Departure ".date("Y-m-d",strtotime($result["flight"][0]['departure_date_time']))." for ".$this->input->post('no_of_person')." Passengers";
    					else
    					  $msg="Quote Requested By ".$user['user_details'][0]["user_id"]." for  (".$trip.")  from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]."  Departure ".date("Y-m-d",strtotime($result["flight"][0]['departure_date_time']))." Returning at ".date("Y-m-d",strtotime($result["flight"][0]['departure_date_time1']))." for ".$this->input->post('no_of_person')." Passengers";
    	                $this->send_message($no,$msg);
    		$result["flight"][0]["qty"]=$this->input->post('no_of_person');		   
    		$result["footer"]=$this->Search_Model->get_post(5);
    		
    		//$result["setting"]=$this->Search_Model->setting();
			$result['setting']=$this->Search_Model->company_setting($companyid);
    		
			$current_user = $this->session->userdata("current_user");

			$result['mywallet']= $this->getMyWallet();
				
    		$this->load->view('header1',$result);
    		$this->load->view('quotation_sent',$result);
    		$this->load->view('footer1'); 
    		}
					      		
		}
		else
		{
		    redirect('/search');
		}
	}
	public function thankyou($id)
	{
		$company = $this->session->userdata('company');
		$current_user = $this->session->userdata("current_user");
		if(!$company || !$current_user) {
			redirect("/login");
		}

		$companyid = $company["id"];
		$result["options"] = array('pdf' => false);  
		$result["details"] = $this->Search_Model->booking_details($id);  
		//$result["setting"] = $this->Search_Model->setting($id); 
		$result['setting']=$this->Search_Model->company_setting($companyid);

		$result["footer"]=$this->Search_Model->get_post(5);

		$result['mywallet']= $this->getMyWallet();

		if($result["details"])
		{		 
			log_message('info', "Search::thankyou - Booking Summary Page: Booking Id: $id | page payload: ".json_encode($result));
			if($result['details'][0]["type"]=="B2B" || $result['details'][0]["type"]=="EMP")
			{			  
				//$result["setting"]=$this->Search_Model->setting();
				$this->load->view('header1',$result);
				// $this->load->view('thank-youb2b',$result);
				$this->load->view('thank-youb2c',$result);
				$this->load->view('footer1');
			}
			if($result['details'][0]["type"]=="B2C")
			{			  
				//$result["setting"]=$this->Search_Model->setting();
				$this->load->view('header1',$result);
				$this->load->view('thank-youb2c',$result);
				$this->load->view('footer1');
			}
			
		}	  		 	      
		else
		{
			redirect("/search");
		}	
	}
	
	public function thankyou1($id)
	{
		$company = $this->session->userdata('company');
		$companyid = $company["id"];
		$result["details"] = $this->Search_Model->supplier_booking_details($id);  
		//$result["setting"] = $this->Search_Model->setting($id); 
		$result['setting']=$this->Search_Model->company_setting($companyid);
		$result["footer"]=$this->Search_Model->get_post(5);

		$current_user = $this->session->userdata("current_user");

		$result['mywallet']= $this->getMyWallet();

		if($result["details"])
		{		 
			if($result['details'][0]["type"]=="B2B")
			{			  
				//$result["setting"]=$this->Search_Model->setting();
				$this->load->view('header1',$result);
				$this->load->view('thank-you',$result);
				$this->load->view('footer1');
			}
			if($result['details'][0]["type"]=="B2C")
			{			  
				//$result["setting"]=$this->Search_Model->setting();
				$this->load->view('header1',$result);
				$this->load->view('thank-you1',$result);
				$this->load->view('footer1');
			}
		}	  		 	      
		else
		{
			redirect("/search");
		}	
	}
	public function update_booking()
	{
	     	$result["footer"]=$this->Search_Model->get_post(5);
		 if(($_SERVER['REQUEST_METHOD'] == 'POST'))
		 {	
	             $booking_id=$this->input->post('booking_id');
	             $booking["details"] = $this->Search_Model->booking_details($booking_id);  
	             if($this->session->userdata('user_id')==$booking["details"][0]["customer_id"])
				 {
					 
					 /*$arr = array('status' => $this->input->post('status'),							 
								 'pnr'=>$this->input->post('pnr')							 							
								 );
					 $result = $this->User_Model->update_table("booking_tbl",$arr,"id",$booking_id);*/
				 }
				 else
				 {
					  redirect("/user/booking-orders");
				 }
  	             				
				 
				 foreach($_REQUEST["prefix"] as $key=>$value)
				 {
					    $arr=array("prefix"=>$_REQUEST["prefix"][$key],
								 "first_name"=>$_REQUEST["first_name"][$key],
								 "last_name"=>$_REQUEST["last_name"][$key],
								 "mobile_no"=>$_REQUEST["mobile_no"][$key],
								 "age"=>$_REQUEST["age"][$key],
								 "email"=>$_REQUEST["email"][$key]
								
								 );
				        //$result=$this->Search_Model->save("customer_information_tbl",$arr);
						$result = $this->User_Model->update_table("customer_information_tbl",$arr,"booking_id",$booking_id);
				 }
				 //echo $this->db->last_query();die();
				 if($result==true)
				 {
					         $this->session->set_flashdata('msg', 'Booking Updated Successfully');							
							 redirect("/user/edit-booking/".$booking_id."");							
							 $this->session->flashdata('msg');
				 }
		 }
			
		 		 
	}
	
	public function customer_cancel_request($id)
	{
		    if ($this->session->userdata('user_id') && isset($id) && ($_SERVER['REQUEST_METHOD'] == 'POST')) 
	        {	
    		      $reason_for_cancellation=$this->input->post('reason_for_cancellation');
    			  $arr=array("reason_for_cancellation"=>$reason_for_cancellation,"status"=>"REQUESTED FOR CANCEL","customer_cancel_request"=>1,"cancel_request_date"=>date("Y-m-d h:i:s"));
    		      $result["booking"]=$this->Search_Model->refrence_booking_details($id); 
    			  $ticket_id=$result["booking"][0]["ticket_id"];
				  $qty=$result["booking"][0]["qty"];
				  $company = $this->session->userdata('company');
				  $companyid = $company["id"];
						
    			  $ticket_details["result"]=$this->Search_Model->flight_details($ticket_id, $companyid); 
    			  $no_of_person=$ticket_details["result"][0]["no_of_person"]+$qty;
                  $journey_date = strtotime($result["booking"][0]["departure_date_time"]);				
                  $cancel_time = strtotime(date("Y-m-d H:i:s"));
    			  $differnce=intval(($cancel_time-$journey_date)/60);
    			  $hours=intval($differnce/60);
    			  
    			  
    			  if($result["booking"][0]["customer_id"]==$this->session->userdata('user_id'))
    			  {
    				     if($hours<24)
    					 {
    						 $check = $this->User_Model->checknum("refrence_booking_tbl",array("booking_id"=>$id,"customer_cancel_request"=>1));
    						 if($check==0)
    						 {
    							 if($result["booking"][0]["customer_id"]==$result["booking"][0]["seller_id"])
    							 {
    								 $arr=array("reason_for_cancellation"=>$reason_for_cancellation,"status"=>"CANCELLED","customer_cancel_request"=>1,"supplier_cancel_request"=>1,"cancel_request_date"=>date("Y-m-d h:i:s"),"cancel_date"=>date("Y-m-d h:i:s"));
    								 $result = $this->User_Model->update_table("refrence_booking_tbl",$arr,"booking_id",$id);
    								 if($result)
    								 {
    									 $ticket_data=array("no_of_person"=>$no_of_person);
    									 $update = $this->User_Model->update_table("tickets_tbl",$ticket_data,"id",$ticket_id);
    									 if($update)
    									 {
    										 $this->session->set_flashdata('msg', 'Booking Cancelled Successfully');							
    										 redirect("/user/my-bookings");							
    										 $this->session->flashdata('msg');
    									 }
    									 else
    									 {
    									    //echo $this->db->last_query();die();
    									    echo $this->db->_error_message();die();
    									 }
    								 }
    								 else
    								 {
    									//echo $this->db->last_query();die();
    									    echo $this->db->_error_message();die();
    								 }
    							 }
    							 else
    							 {
    								 $result = $this->User_Model->update_table("refrence_booking_tbl",$arr,"booking_id",$id);
    								 $arr1=array("reason_for_cancellation"=>$reason_for_cancellation,"status"=>"REQUESTED FOR CANCEL");
    								 $result = $this->User_Model->update_table("booking_tbl",$arr1,"id",$id);
    								 if($result)
    								 {
    									 $this->session->set_flashdata('msg', 'Request Sent Successfully');							
    									 redirect("/user/my-bookings");							
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
    							    $this->session->set_flashdata('emsg', 'This is booking is already applied for cancel');							
    								 redirect("/user/my-bookings");							
    								 $this->session->flashdata('emsg');
    						 }
    					 }
    					 else
    					 {
    						     $this->session->set_flashdata('emsg', 'Cancellation is only possible before 24 hours of Deaprture Date ');							
    							 redirect("/user/my-bookings");							
    							 $this->session->flashdata('emsg');
    					 }
    			  }
    			  else
    			  {
    			        redirect("/user/my-bookings");
    			  }
			}
			else
		    {
			  redirect("/user/my-bookings");
		    }
	}
	
	public function approve_cancel($id)
	{
		if ($this->session->userdata('user_id') && isset($id) && ($_SERVER['REQUEST_METHOD'] == 'POST')) 
		{
			 $result["booking"]=$this->Search_Model->get_booking_details($id); 
			 if($result["booking"][0]["seller_id"]==$this->session->userdata('user_id'))
			 {
				 $supplier_cancel_charge=$this->input->post('supplier_cancel_charge');
			     $arr=array("supplier_cancel_charge"=>$supplier_cancel_charge,"status"=>"PROCESSING FOR CANCEL","supplier_cancel_request"=>1,"cancel_request_date"=>date("Y-m-d h:i:s"));				
				 $check = $this->User_Model->checknum("refrence_booking_tbl",array("id"=>$id,"supplier_cancel_request"=>1));
				 if($check==0)
				 {
					  $result = $this->User_Model->update_table("refrence_booking_tbl",$arr,"id",$id);
					 if($result)
					 {
							 $this->session->set_flashdata('msg', 'Applied Successfully');							
							 redirect("/user/booking-orders");						
							 $this->session->flashdata('msg');
					 }
					 else
					 {
						echo $this->db->last_query();die();
					 }
				 }
				 else
				 {
					     $this->session->set_flashdata('emsg', 'Already Applied For Cancellation');							
						 redirect("/user/booking-orders");						
						 $this->session->flashdata('emsg');
				 }
				 
			 }
			 else
			 {
                redirect("/user/booking-orders");
			 }
		}
		else
		{
			redirect("/user/booking-orders");
		}
	}
	
	public function filter_city()
	{
		
         $response["success"]=$this->Search_Model->filter_city($this->input->post('source'),$this->input->post('trip_type'));        
		 echo json_encode($response);	
	}
	
	public function search_available_date()
	{		
		$company = $this->session->userdata('company');
		$current_user = $this->session->userdata('current_user');

		$companyid = $company["id"];
		$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
		$defaultRP = NULL;
		$defaultRPD = NULL;
		if(count($rateplans)>0) {
			$defaultRP = $rateplans[0];
			$rateplanid = $defaultRP['id'];

			if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && $current_user["rateplanid"]!==null && intval($current_user["rateplanid"])>0) {
				$rateplanid = intval($current_user["rateplanid"]);
			}

			$defaultRPD = $this->Admin_Model->rateplandetails($rateplanid);
		}

		$rateplan_details = $this->Admin_Model->rateplandetails(-1);
		$tickets=$this->Search_Model->search_available_date($this->input->post('source'),$this->input->post('destination'),$this->input->post('trip_type'), $companyid);

		for ($i=0; $tickets && $i < count($tickets); $i++) { 
			$ticket = &$tickets[$i];
			$rateplanid = $ticket['rate_plan_id'];
			$price = $ticket['price'];
			$adminmarkup = 0;
			
			$ticket['whl_markup'] = 0;
			$ticket['whl_srvchg'] = 0;
			$ticket['whl_cgst'] = 0;
			$ticket['whl_sgst'] = 0;
			$ticket['whl_igst'] = 0;
			$ticket['whl_disc'] = 0;

			$ticket['spl_markup'] = 0;
			$ticket['spl_srvchg'] = 0;
			$ticket['spl_cgst'] = 0;
			$ticket['spl_sgst'] = 0;
			$ticket['spl_igst'] = 0;
			$ticket['spl_disc'] = 0;

			if($current_user['is_admin']!=='1' && $current_user['type']=='B2B') {
				$adminmarkup = $ticket['admin_markup'];
				$price += $adminmarkup;
			}

			$tax_others = 0;

			try
			{
				if($ticket['supplierid'] !== $companyid) {
					//sourced tickets
					for ($j=0; $j < count($rateplan_details); $j++) { 
						$rpdetail = $rateplan_details[$j];
						if($rpdetail['rateplanid'] == $rateplanid) {
							$achead = 'whl_'.$rpdetail['head_code'];
							// array_push($ticket, [$achead => '']);
							if($rpdetail['head_code'] !== 'igst') { //because igst can only be calculated for other state
								if($rpdetail['operation'] == 1) {
									$ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
									$tax_others = $tax_others + $ticket[$achead];
								}
								else {
									$ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
									$tax_others = $tax_others - $ticket[$achead];
								}
							}
						}
					}
				}
				// $ticket['price'] += $tax_others;
				$tax_others = 0;
				
				if($defaultRPD !== NULL && count($defaultRPD)>0) {
					//add wholesaler's part
					for ($j=0; $j < count($defaultRPD); $j++) { 
						$rpdetail = $defaultRPD[$j];
						$achead = 'spl_'.$rpdetail['head_code'];
						// array_push($ticket, [$achead => '']);
						if($rpdetail['head_code'] !== 'igst') { //because igst can only be calculated for other state
							if($rpdetail['operation'] == 1) {
								$ticket[$achead] += $this->getProcessedValue($rpdetail, $price, $ticket);
								$tax_others += $ticket[$achead];
							}
							else {
								$ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
								$tax_others -= $ticket[$achead];
							}
						}
					}
				}
			}
			catch(Exception $ex) {

			}

			// $ticket['finalvalue'] = $tax_others;
			// $ticket['price'] += $tax_others;

			$ticket['price'] += ($ticket['whl_markup'] + $ticket['spl_markup'] + $ticket['whl_srvchg'] + $ticket['spl_srvchg'] 
				+ $adminmarkup 
				+ ($ticket['whl_srvchg'] * $ticket['whl_cgst'] / 100)
				+ ($ticket['whl_srvchg'] * $ticket['whl_sgst'] / 100)
				+ ($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100)
				+ ($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100));

			if ($ticket['whl_srvchg'] === 0) {
				$ticket['whl_cgst'] = 0;
				$ticket['whl_sgst'] = 0;
				$ticket['whl_igst'] = 0;
			}

			if ($ticket['spl_srvchg'] === 0) {
				$ticket['spl_cgst'] = 0;
				$ticket['spl_sgst'] = 0;
				$ticket['spl_igst'] = 0;
			}
		}
		
		$response["success"] = $tickets;
		// ob_end_clean();
		// header('Content-type: application/json; charset=utf-8');
		echo json_encode($response);	
	}

	private function getProcessedValue($rpdetail, $price, $ticket) {
		$operation = NULL;
		if(!($rpdetail['calculation']=='' || $rpdetail['calculation']==NULL)) {
			$operation = str_replace('}', '', str_replace('{', '', $rpdetail['calculation']));
		}
		if($operation !== NULL) {
			if(isset($ticket[$operation])) {
				$price = $ticket[$operation];
			}
			else {
				$price = 0;
			}
		}

		if($rpdetail['amount_type'] == 1) { //value
			return $rpdetail['amount'];
		}
		else if($rpdetail['amount_type'] == 2) { //%
			// return $price * ($rpdetail['amount'] / 100); // this is the right approach but let us think how to implement it.
			return $rpdetail['amount'];
		}
		else {
			return 0;
		}
	}

	public function search_available_date1()
	{	
		$company = $this->session->userdata('company');
		$response["success"]=$this->Search_Model->search_available_date1($this->input->post('source'),$this->input->post('destination'),$this->input->post('trip_type'), $company["id"]);
		echo json_encode($response);	
	}

	public function render_template() {
		$company = $this->get_companyinfo();
		$templates = $this->getTemplates();
		$bookingid = 10;
		
		$data = array(
            'company_name' => 'Radharani Holidays',
			'phone_number' => '+91 9874550200',
			'action_url' => 'http://www.oxytra.com',
			'booking_status' => 'CONFIRM',
			'pnr' => 'ARFIJE',
			'booking_number' => 'BK-0175/19-20',
			'booking_date' => '10-10-2019 13:10',
			'departure_city' => 'Kolkaata (CCU)',
			'arrival_city' => 'Bagdogra (IXB)',
			'airline' => 'Go Air',
			'flight_number' => 'G8-345',
			'departure_date' => '25-Oct-2019 17:30',
			'arrival_date' => '25-Oct-2019 20:30',
			'no_of_pax' => 5,
			'invoice_amount' => '6450/-',
			'first_passenger_name' => 'Mr. Sumit Agarwal',
			'companing_count' => '+4',
			'to' => 'majumdar.himadri@gmail.com',
			'cc' => 'majumdar.himadri@gmail.com',
        );

		$flag = $this->send_email("BOOKING_CUSTOMER_EMAIL", "Booking : $bookingid", $company, $data);

		$template = $this->parser->parse('templates/email/option1/booking_confirmation', $data, TRUE);
		$template = $this->parser->conditionals($template, $data, TRUE);

		echo $template;
	}

	public function getTemplates() {
		$templates = $this->Search_Model->getTemplates();

		if($templates) {
			for ($i=0; $i < count($templates); $i++) { 
				$templete = &$templates[$i];

				$templete['default_data_structure'] = json_decode($templete['default_data_structure'], TRUE);
			}
		}

		return $templates;
	}

	public function pdf($id)
	{
		$showprice = true;
		$extra_markup = 0.0;
		$company = $this->session->userdata('company');
		$companyid = $company["id"];
		$result["details"] = $this->Search_Model->booking_details($id);  
		$result['setting']=$this->Search_Model->company_setting($companyid);
		$result["footer"]=$this->Search_Model->get_post(5);

		$current_user = $this->session->userdata("current_user");
		if(!$company || !$current_user) {
			redirect("/login");
		}

		$ticket_no = $result["details"][0]["ticket_no"];

		if(isset($_POST['showprice'])) {
			$showprice = strtolower($_POST['showprice']) === 'off';
		}

		if(isset($_POST['markup'])) {
			//$extra_markup = intval($_GET['markup']);
			$extra_markup = intval($_POST['markup']);

			$qty = intval($result["details"][0]["qty"]);

			$par_ticket_rate = round($extra_markup/$qty);

			$result["details"][0]["rate"] = floatval($result["details"][0]["rate"])+$par_ticket_rate;

			$result["details"][0]["amount"] = $result["details"][0]["rate"] * $qty;
			$result["details"][0]["service_charge"] = intval($result["details"][0]["service_charge"]) * $qty;

			$result["details"][0]["sgst"] = $result["details"][0]["sgst"] * $qty;
			$result["details"][0]["cgst"] = $result["details"][0]["cgst"] * $qty;

			$result["details"][0]["total"] = $result["details"][0]["total"] + $extra_markup;
			// $par_ticket_rate
		}
		$result["options"] = array('pdf' => false, 'showprice' => $showprice);  
		
		if($result["details"])
		{		 
			$this->load->library('pdf');
			// Set Font Style
			$this->pdf->set_option('defaultFont', 'Courier');
			$this->pdf->set_option('isRemoteEnabled', true);
			$this->pdf->set_option('isPhpEnabled', true);
			$this->pdf->set_option('isJavascriptEnabled', true);
			$this->pdf->set_option('isHtml5ParserEnabled', true);
			$this->pdf->setPaper('A4', 'portrait');

			// if($result['details'][0]["type"]=="B2B" || $result['details'][0]["type"]=="EMP")
			// {			  
			// 	$this->load->view('myticket',$result);
			// }

			// if($result['details'][0]["type"]=="B2C")
			// {			  
			// 	$this->load->view('myticket',$result);
			// }

			$this->pdf->load_view('myticket', $result);
			$this->pdf->render();
			$this->pdf->stream("$ticket_no.pdf", array("Attachment"=>1));
		}
		else
		{
			redirect("/search");
		}	
	}

	/*
	private function getMyWallet() {
		$companyid = $this->session->userdata("current_user")["companyid"];
		$current_user = $this->session->userdata("current_user");
		$wallet = NULL;

		if($this->session->userdata('user_id') && $current_user['is_admin']!=='1') {
			$mywallet = $this->Search_Model->getMyWallet($this->session->userdata('user_id'), -1);
			if($mywallet && count($mywallet)>0) {
				//$result["mywallet"] = $mywallet[0];
				$wallet = $mywallet[0];
			}
			else {
				//$result["mywallet"] = array('balance' => 0);
				$wallet = array('balance' => 0);
			}
		}
		else if($companyid) {
			$mywallet = $this->Search_Model->getMyWallet(-1, $companyid);
			
			if($mywallet && count($mywallet)>0) {
				$mywallet=$this->Search_Model->getMyWallet(-1, $companyid);
				if($mywallet && count($mywallet)>0) {
					//$result["mywallet"] = $mywallet[0];
					$wallet = $mywallet[0];
				}
				else {
					//$result["mywallet"] = array('balance' => 0);
					$wallet = array('balance' => 0);
				}
			}
			else {
				//$result["mywallet"] = array('balance' => 0);
				$wallet = array('balance' => 0);
			}
		}
		else {
			//$result["mywallet"] = array('balance' => 0);
			$wallet = array('balance' => 0);
		}

		if(!$result["mywallet"]) {
			//$result["mywallet"] = array('balance' => 0);
			$wallet = array('balance' => 0);
		}	

		return $wallet;
	}

	*/
}
