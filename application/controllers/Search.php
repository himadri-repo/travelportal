<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'core/Mail_Controller.php');
include(APPPATH.'entities/company.php');
include_once(APPPATH.'core/Common.php');
include_once(APPPATH.'core/TransactionResponse.php');
define('PAGE_SIZE', 25);

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
			$current_user = $this->session->userdata('current_user');
			$company = $this->session->userdata('company');
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

			$circles = $this->Search_Model->get_inventory_circles($companyid, 365, 'ONE');
			$sources = $this->get_filtered_sources($circles);

			$result['sources']=$sources;
			$result['circles']=json_encode($circles);
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

			$state = [];
			$state['contact_number'] = isset($company['mobile'])?$company['mobile']:null;
			$state['sectors'] = $this->Search_Model->get('city_tbl', null);
			$state['current_user'] = $current_user;
			$state['sources'] = $sources;
			//$state['circles'] = json_encode($circles);
			$state['circles'] = $circles;
			
			$state['no_of_person'] = 1;
			$state['adult'] = 1;
			$state['child'] = 0;
			$state['infant'] = 0;
			$state['class'] = 0;
			$state['trip_type'] = 'ONE';
			$state['source'] = -1;
			$state['destination'] = -1;
			$state['departure_date'] = date("d-m-Y");
			$state['return_date'] = '';
			$state['source_city_name']='';
			$state['destination_city_name']='';
			
			$result["state"] = $state;			
				
			$this->load->view('header1',$result);
			$this->load->view('search',$result);
			$this->load->view('footer1');
		}
		else
			redirect("/login");
	}
	
	public function getThirdpartyIntegrations($companyid, $user) {
		$selected_3pp_api = [];
		$company_settings = $this->Search_Model->company_setting($companyid);
		$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
		$default_rateplan_id = -1;
		if($rateplans && is_array($rateplans) && count($rateplans)>0) {
			$default_rateplan_id = intval($rateplans[0]['id']);
		}

		$thirdparty_apis = $this->Search_Model->get('thirdparty_api_tbl', array('status' => 1, 'category' => "'INVENTORY_SOURCE'"));
		if($company_settings && is_array($company_settings) && count($company_settings)>0) {
			$company_settings = $company_settings[0];
			$api_integrations = isset($company_settings['api_integration'])?json_decode($company_settings['api_integration'], TRUE):null;

			if ($api_integrations && is_array($api_integrations) && count($api_integrations)>0 && 
				$thirdparty_apis && is_array($thirdparty_apis) && count($thirdparty_apis)>0) {
				foreach ($api_integrations as $api_integration) {
					if(isset($api_integration['enabled']) && boolval($api_integration['enabled']) && isset($api_integration['thirdparty_name'])) {
						$api_name = $api_integration['thirdparty_name'];
						$conf_3pp = null;
						// $conf_3pp = array_filter($thirdparty_apis, array(function($api, $val) {
						// 	return ($api['name'] === $val);
						// }, $api_name));
						foreach ($thirdparty_apis as $api) {
							if($api['name'] === $api_name) {
								$conf_3pp = $api;
								break;
							}
						}

						if($conf_3pp && is_array($conf_3pp) && count($conf_3pp)>0) {
							//$conf_3pp = $conf_3pp[0];
							$thirdparty_api = array(
								'companyid' => $companyid,
								'name' => $api_integration['thirdparty_name'],
								'library_name' => $conf_3pp['library_name'],
								'enabled' => (isset($api_integration['enabled']) && boolval($api_integration['enabled'])),
								'url' => (isset($api_integration['mode']) && $api_integration['mode'] === 'test') ? $conf_3pp['test_url'] : $conf_3pp['live_url'],
								'supplier_companyid' => intval($api_integration['sponsor_config']['Companyid']),
								'supplier_rateplanid' => intval($api_integration['sponsor_config']['Rateplanid']),
								'seller_companyid' => intval($companyid),
								'seller_rateplan' => (($user['type']==='EMP') ? -1 : 
												(($user['type']==='B2B') ? intval($api_integration['tenant_config']['B2B_rateplanid']) : 
												(($user['type']==='B2C') ? intval($api_integration['tenant_config']['B2C_rateplanid']) : $default_rateplan_id))),
								'host' => (isset($api_integration['sponsor_config']['Host'])?$api_integration['sponsor_config']['Host']:''),
								'userid' => ((isset($api_integration['tenant_config']['UserID']) && $api_integration['tenant_config']['UserID'] !== '') ? 
												$api_integration['tenant_config']['UserID'] : $api_integration['sponsor_config']['UserID']),
								'password' => ((isset($api_integration['tenant_config']['UserPassword']) && $api_integration['tenant_config']['UserPassword'] !== '') ? 
												$api_integration['tenant_config']['UserPassword'] : $api_integration['sponsor_config']['UserPassword']),
								'sponsoring_config' => isset($api_integration['sponsor_config'])?$api_integration['sponsor_config']:false,
								'tenant_config' => isset($api_integration['tenant_config'])?$api_integration['tenant_config']:false
							);

							array_push($selected_3pp_api, $thirdparty_api);
						}
					}
				}
			}
		}

		log_message('debug', "3pp api => ".json_encode($selected_3pp_api, TRUE));
		return $selected_3pp_api;
	}

	public function search_one_way()
	{ 
		if ($this->session->userdata('user_id')) 
		{	
			$company = $this->session->userdata('company');
			$current_user = $this->session->userdata('current_user');
			$companyid = intval($company["id"]);
			$source = intval($this->input->post('source'));
			$destination = intval($this->input->post('destination'));
			$no_of_person = intval($this->input->post('no_of_person'));

			$source_city_name = $this->input->post('sc_source');
			$destination_city_name = $this->input->post('sc_destination');

			$adult = intval($this->input->post('adult')) > 0 ? intval($this->input->post('adult')) : $no_of_person;
			$child = intval($this->input->post('child')) > 0 ? intval($this->input->post('child')) : 0;
			$infant = intval($this->input->post('infant')) > 0 ? intval($this->input->post('infant')) : 0;

			$no_of_person = $adult + $child + $infant;

			$class_type = $this->input->post('class_type');
			$class = intval($this->input->post('optClass'))>0 ? intval($this->input->post('optClass')) : 0; //0 means economy

			$dept_date = date_format(date_create($this->input->post('departure_date')), 'Ymd');

			log_message('debug', 'Search posted payload: '.json_encode($_POST));

			if($_SERVER['REQUEST_METHOD'] == 'POST' && $source>0 && $destination>0) 
			{			  
				$source_city = $this->User_Model->get('city_tbl', array('id' => $source));
				if($source_city && count($source_city)>0) {
					$source_city = $source_city[0];
					$source_city_name = $source_city['city'];
				}
				$destination_city = $this->User_Model->get('city_tbl', array('id' => $destination));
				if($destination_city && count($destination_city)>0) {
					$destination_city = $destination_city[0];
					$destination_city_name = $destination_city['city'];
				}

				// Load 3rd party inventory
				$thirdparty_tickets = null;
				//read company settings.
				//what all thirdparty integration enabled, work on those library only.
				$listof3ppintegrations = $this->getThirdpartyIntegrations($companyid, $current_user);
				if($companyid === 1 || $companyid === 7) {
					//
					$api_integration = null;
					$company_settings = $this->Search_Model->company_setting($companyid);
					$airlines = $this->Search_Model->get('airline_tbl', array());

					#region commented
					// $thirdparty_apis = $this->Search_Model->get('thirdparty_api_tbl', array('name' => "'tmz'"));
					// $url = "http://demoapi.tripmaza.com/";
					// if($thirdparty_apis && is_array($thirdparty_apis) && count($thirdparty_apis)>0) {
					// 	$thirdparty_apis = $thirdparty_apis[0];
					// }

					// if($company_settings && is_array($company_settings) && count($company_settings)>0) {
					// 	$company_settings = $company_settings[0];
					// 	$api_integration = isset($company_settings['api_integration'])?json_decode($company_settings['api_integration'], TRUE):null;
					// 	$selected_3pp_api = null;
					// 	for($i=0; $api_integration && $i<count($api_integration); $i++) {
					// 		if($api_integration[$i]['thirdparty_name'] === 'tmz') {
					// 			$selected_3pp_api = $api_integration[$i];
					// 			break;
					// 		}
					// 	}

					// 	$api_integration = $selected_3pp_api;
					// }
					// $rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
					// $default_rateplan_id = 0;
					// $default_b2b_rateplan_id = 0;
					// $default_b2c_rateplan_id = 0;
					// if($rateplans && is_array($rateplans) && count($rateplans)>0) {
					// 	$default_rateplan_id = intval($rateplans[0]['id']);
					// }

					// $flag = true;
					// if($api_integration && $api_integration!=='' && isset($api_integration['tenant_config']) && isset($api_integration['sponsor_config']) 
					// 	&& isset($api_integration['enabled']) && $api_integration['enabled']) {
					// 	$supl_companyid = isset($api_integration['sponsor_config']['Companyid'])? intval($api_integration['sponsor_config']['Companyid']) : -1;
					// 	$supl_rateplan_id = isset($api_integration['sponsor_config']['Rateplanid'])? intval($api_integration['sponsor_config']['Rateplanid']) : -1;
					// 	$host = isset($api_integration['sponsor_config']['Host'])? $api_integration['sponsor_config']['Host'] : "demo.api";
					// 	$mode = isset($api_integration['mode'])? $api_integration['mode'] : "test";
					// 	if($mode === 'test') {
					// 		$url = (isset($thirdparty_apis['test_url']) && $thirdparty_apis['test_url']!=='')?$thirdparty_apis['test_url']:$url;
					// 	}

					// 	$api_uid = (isset($api_integration['tenant_config']) && isset($api_integration['tenant_config']['UserID'])) ? $api_integration['tenant_config']['UserID'] : $api_integration['sponsor_config']['UserID']; //'9800412356';
					// 	$api_pwd = (isset($api_integration['tenant_config']) && isset($api_integration['tenant_config']['UserPassword'])) ? $api_integration['tenant_config']['UserPassword'] : $api_integration['sponsor_config']['UserPassword']; //'4434045132';
					// 	$default_b2b_rateplan_id = isset($api_integration['B2B_rateplanid'])?$api_integration['B2B_rateplanid']:-1;
					// 	$default_b2c_rateplan_id = isset($api_integration['B2C_rateplanid'])?$api_integration['B2C_rateplanid']:-1;
						
					// 	if($current_user['type'] == 'B2B') {
					// 		$default_rateplan_id = $default_b2b_rateplan_id;
					// 	}
					// 	else {
					// 		$default_rateplan_id = $default_b2c_rateplan_id;
					// 	}

					// 	$config = array("companyid" => $companyid, "host" => $host, "userid" => $api_uid, "userpassword" => $api_pwd, "url" => "http://demoapi.tripmaza.com/");
					// }
					// else {
					// 	$config = array("companyid" => $companyid, "host" => "demo.api", "userid" => "9800412356", "userpassword" => "4434045132", "url" => "http://demoapi.tripmaza.com/");
					// 	$flag = false;
					// }
					#endregion
					
					$thirdparty_tickets = [];
					if($listof3ppintegrations && is_array($listof3ppintegrations) && count($listof3ppintegrations)>0) {

						foreach ($listof3ppintegrations as $api) {
							$api_3pp_name = $api['name'];
							$this->load->library($api['library_name'], $api);

							$load_lib = $this->load_thirdparty_inventory($api['library_name']);
							$suplcompanyid = intval($api['supplier_companyid']);
							if($suplcompanyid>-1) {
								$supplier_company = $this->Admin_Model->get_company($api['supplier_companyid']);
								if($supplier_company && is_array($supplier_company) && count($supplier_company)>0) {
									$supplier_company = $supplier_company[0];
								}
							}
							else {
								$supplier_company = $company;
							}
	
							$tkts_3pp = $this->search_inventory($api['library_name'], array(
								'no_of_person' => $no_of_person,
								'adult' => $adult,
								'child' => $child,
								'infant' => $infant,
								'departure_date' => $this->input->post('departure_date'),
								'source_city' => $source_city,
								'destination_city' => $destination_city,
								'source_city_code' => trim($source_city['code']),
								'destination_city_code' => trim($destination_city['code']),
								'direct' => 0,
								'one_stop' => 0,
								'company' => $company,
								'current_user' => $current_user,
								'supl_companyid' => $api['supplier_companyid'],
								'supl_company' => $supplier_company,
								'supl_rateplan_id' => $api['supplier_rateplanid'],
								'default_rateplan_id' => $api['seller_rateplan'],
								'airlines' => $airlines
							));
	
							log_message('debug', "3pp ($api_3pp_name) => ".json_encode($tkts_3pp));

							if($tkts_3pp && is_array($tkts_3pp) && count($tkts_3pp)>0) {
								array_push($thirdparty_tickets, ...$tkts_3pp);
							}
						}
					}
				}

				//Live ticket checks
				$dept_date = date_format(date_create($this->input->post('departure_date')), 'Ymd');
				$url = "http://developer.goibibo.com/api/search/?app_id=f8803086&app_key=5239f6f98b93cbe7b61a9f47a7ba1634&format=json&source=".$source_city['code']."&destination=".$destination_city['code']."&dateofdeparture=".$dept_date."&seatingclass=E&adult=1&children=0&infant=0&counter=100";
	
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
				if($live_ticket_data && count($live_ticket_data)>0 && isset($live_ticket_data['data']['onwardflights'])) {
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

					if($currentuser["type"]==='B2B' && $currentuser["is_admin"]!=='1' && isset($currentuser["rateplanid"]) && intval($currentuser["rateplanid"])>0) {
						$rateplanid = intval($currentuser["rateplanid"]);
					}

					$defaultRPD = $this->Admin_Model->rateplandetails($rateplanid);
				}
				$rateplan_details = $this->Admin_Model->rateplandetails(-1);

				$tickets = $this->Search_Model->search_one_wayV2($arr);
				if(!$tickets)
				{
					$tickets = [];
				}

				//append thirdparty tickets
				if($thirdparty_tickets && is_array($thirdparty_tickets) && count($thirdparty_tickets)>0) {
					$tickets = $this->append_thiredparty_tickets($tickets, [$thirdparty_tickets]);
				}

				$modifiable_attributes = [];

				for ($i=0; $tickets && $i < count($tickets); $i++) { 
					$ticket = &$tickets[$i];

					if(!isset($ticket['library'])) {
						$ticket = $this->map_ticket_fields($ticket);
					}

					$modifiable_attributes[] = array('id' => $ticket['id'], 'ticket_no' => $ticket['ticket_no'], 'source_city' => $ticket['source_city'], 
						'destination_city' => $ticket['destination_city'], 
						'departure_date' => date("d-m-Y",strtotime($ticket['departure_date_time'])), 
						'departure_time' => date("H:i",strtotime($ticket['departure_date_time'])), 
						'arrival_date' => date("d-m-Y",strtotime($ticket['arrival_date_time'])),
						'arrival_time' => date("H:i",strtotime($ticket['arrival_date_time'])), 
						'flight_no' => $ticket['flight_no'], 
						'no_of_person' => $ticket['no_of_person'], 
						'price' => $ticket['price'], 
						'terminal' => $ticket['terminal'], 
						'no_of_person' => $ticket['no_of_person'], 
						'tag' => $ticket['tag']
					);
					$live_ticket = NULL;
					if($live_ticket_data && count($live_ticket_data)>0 && $ticket['sale_type']!=='api') {
						for ($tk=0; $tk < count($live_ticket_data); $tk++) {
							$live_ticket = $live_ticket_data[$tk];
							$depttime = (isset($live_ticket['deptime']) ? $live_ticket['deptime']: '');
							$tkt_deptime = date('H:i', strtotime($ticket['departure_date_time']));
							$range1 = date('H:i', strtotime($ticket['departure_date_time'])-(60*20));
							$range2 = date('H:i', strtotime($ticket['departure_date_time'])+(60*20)); //If 20 mins is risky we can reduce it.
							$carrierid = (isset($live_ticket['carrierid']) ? $live_ticket['carrierid'] : '');
							$cachekey = $carrierid.'-'.$live_ticket['flightno'];
							//$flight_no = str_replace('_', '', str_replace('-', '', $ticket['flight_no']));
							$flight_no = trim($ticket['flight_no']);
							$fl_no = 0;
							preg_match_all('/\d+$/', $flight_no, $matches);
							if(count($matches)>0 && count($matches[0])>0) {
								$fl_no = intval($matches[0][0]);
							}
							$flight_no = $ticket['aircode'].'-'.$fl_no;
							log_message('debug', "Matching ticket with live ticket - ".$ticket['id']." => "."$flight_no | $cachekey");

							log_message('debug', "Match criteria => $carrierid === ".$ticket['aircode']." | $depttime === $tkt_deptime | $depttime>=$range1 && $depttime<=$range2 | Within range -> ".($depttime>=$range1 && $depttime<=$range2));

							if($live_ticket && intval($live_ticket['stops'])===0 && ($flight_no===$cachekey || ($carrierid===$ticket['aircode'] && ($depttime===$tkt_deptime || ($depttime>=$range1 && $depttime<=$range2))))) {
								if($flight_no===$cachekey) {
									$ticket['live_corrected'] = false;
								} else {
									$ticket['live_corrected'] = true;
								}
								break;
							}
							else {
								$live_ticket = NULL;
							}
						}
					}

					if($live_ticket) {
						$ticketid = intval($ticket['id']);
						log_message('debug', "Matching Live Ticket => ".json_encode($live_ticket, TRUE));
						$live_dept_date = date('Y-m-d H:i:s', strtotime($live_ticket['depdate']));
						$live_arrv_date = date('Y-m-d H:i:s', strtotime($live_ticket['arrdate']));
						$live_flight_no = $live_ticket['carrierid']."-".$live_ticket['flightcode'];

						log_message('debug', "Dept Date => $live_dept_date | Arrv Date => ".$live_arrv_date);

						$ticket['departure_date_time'] = $live_dept_date;
						$ticket['arrival_date_time'] = $live_arrv_date;
						$ticket['flight_no'] = $live_flight_no;
						$ticket['terminal'] = isset($live_ticket['depterminal'])?'T-'.$live_ticket['depterminal']:'';
						$ticket['departure_terminal'] = isset($live_ticket['depterminal'])?'T-'.$live_ticket['depterminal']:'';
						$ticket['arrival_terminal'] = isset($live_ticket['arrterminal'])?'T-'.$live_ticket['arrterminal']:'';

						$ticket['live_fare'] = floatval($live_ticket['fare']['adulttotalfare']);
						$ticket['seatsavailable'] = intval($live_ticket['seatsavailable']);

						if(isset($ticket['live_corrected']) && boolval($ticket['live_corrected'])) {
							log_message('debug', 'There is a change according to system info => flight_no: '.$ticket['flight_no'].' | terminal: '.$ticket['departure_terminal'].' | terminal1: '.$ticket['arrival_terminal']);
							$ticket_update_result = $this->Search_Model->update('tickets_tbl', array(
								'departure_date_time' => $live_dept_date, 
								'arrival_date_time' => $live_arrv_date, 
								'flight_no' => $live_flight_no, 
								'terminal' => isset($live_ticket['depterminal'])?'T-'.$live_ticket['depterminal']:'', 
								'terminal1' => isset($live_ticket['arrterminal'])?'T-'.$live_ticket['arrterminal']:''
							), array('id' => $ticketid));
	
							log_message('debug', "Ticket updated with live info => $ticket_update_result");
						}
					}
					else {
						$ticket['live_fare'] = -1;
						$ticket['seatsavailable'] = -1;
						$ticket['live_corrected'] = false;
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
							if($companyid === intval($ticket['companyid'])) {
								$sellrpd = $defaultRPD;
								$suprpd = $defaultRPD;
							}

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

					log_message('debug', "Ticket -> ".json_encode($ticket));
				}	
				
				$this->session->set_userdata('tickets',$tickets);

				if($companyid==1 || $companyid==7) {
					//Please enable this if system fare is integrated in this account
					$circles = $this->Search_Model->get_cities();
				}
				else {
					$circles = $this->Search_Model->get_inventory_circles($companyid, 365, 'ONE');
				}
				$sources = $this->get_filtered_sources($circles);
					
				$result['sources']=$sources;
				$result['circles']=json_encode($circles);

				$state = [];
				$state['contact_number'] = isset($company['mobile'])?$company['mobile']:null;
				$state['sectors'] = $this->Search_Model->get('city_tbl', null);
				$state['current_user'] = $current_user;
				$state['sources'] = $sources;
				//$state['circles'] = json_encode($circles);
				$state['circles'] = $circles;
				
				$state['no_of_person'] = $no_of_person;
				$state['adult'] = $adult;
				$state['child'] = $child;
				$state['infant'] = $infant;
				$state['class'] = $class;
				$state['trip_type'] = 'ONE';
				$state['source'] = $this->input->post('source');
				$state['destination'] = $this->input->post('destination');
				$state['departure_date'] = $this->input->post('departure_date');
				$state['return_date'] = $this->input->post('return_date');
				$state['source_city_name']=$source_city_name;
				$state['destination_city_name']=$destination_city_name;
				
				$result["state"] = $state;
				$result["flight"]=$tickets;
				$result["flight_attributes"]=$modifiable_attributes;
				$result["rateplan"]=$rateplans; // $default_rp;
				$result["currentuser"]=$currentuser;
				
				$this->session->set_userdata('no_of_person', $no_of_person);
				$this->session->set_userdata('state', $state);
				
				$result["post"][0]["source"]=$this->input->post('source');
				$result["post"][0]["destination"]=$this->input->post('destination');
				$result["post"][0]["departure_date_time"]=$this->input->post('departure_date_time');
				$result["post"][0]["departure_date"]=$this->input->post('departure_date');
				$result["post"][0]["no_of_person"]=$adult + $child;
				$result["post"][0]["hid_trip_type"]="ONE";
				//echo $this->db->last_query();die(); 
				$result["post"][0]["qty"]=$no_of_person;

				$result["post"][0]["adult"]=$adult;
				$result["post"][0]["child"]=$child;
				$result["post"][0]["infant"]=$infant;
				$result["post"][0]["class"]=$class;
				$result["post"][0]["source_city_name"]=$source_city_name;
				$result["post"][0]["destination_city_name"]=$destination_city_name;

				//$result["setting"]=$this->Search_Model->setting();
				$result['setting']=$this->Search_Model->company_setting($companyid);
				if(isset($result['setting']) && isset($result['setting'][0]['payment_gateway'])) {
					$result['setting'][0]['payment_gateway'] = json_decode($result['setting'][0]['payment_gateway'], true);
				}
				if(isset($result['setting']) && isset($result['setting'][0]['bank_accounts'])) {
					$result['setting'][0]['bank_accounts'] = json_decode($result['setting'][0]['bank_accounts'], true);
				}
				if(isset($result['setting']) && isset($result['setting'][0]['configuration'])) {
					$result['setting'][0]['configuration'] = json_decode($result['setting'][0]['configuration'], true);
				}
				if(isset($result['setting']) && isset($result['setting'][0]['api_integration'])) {
					$result['setting'][0]['api_integration'] = json_decode($result['setting'][0]['api_integration'], true);
				}

				$result["footer"]=$this->Search_Model->get_post(5);
				$current_user = $this->session->userdata("current_user");

				$result['mywallet']= $this->getMyWallet();
				$result['controller']= $this;
							
				$this->load->view('header1',$result);
				//$this->load->view('search_one_way',$result);
				// if($companyid === 7) {
				$this->load->view('search_one_wayv2',$result);
				// }
				// else {
				// 	$this->load->view('search_one_wayv2',$result);
				// }
				$this->load->view('footer1');
			}
			else
				redirect("/search");  
		}
		else
			redirect("/login");  
	}

	private function map_ticket_fields(&$ticket) {
		$company_settings = $this->Search_Model->company_setting(intval($ticket['companyid']));
		$infant_price = floatval($ticket['infant_price']);
		if($infant_price<=0 && $company_settings && is_array($company_settings) && count($company_settings) > 0 && isset($company_settings[0]['infant_price'])) {
			$company_settings = $company_settings[0];
			$infant_price = floatval($company_settings['infant_price']);
		}

		$diff = intval((strtotime($ticket['arrival_date_time'])-strtotime($ticket['departure_date_time']))/60);

		$myticket = array_merge($ticket, array(
			'library' => '',
			'source1' => 0,
			'destination' => 0,
			'uid' => intval($ticket['user_id']),
			'remarks' => 'FD ticket',
			'departure_date_time1' => '',
			'arrival_date_time1' => '',
			'flight_no1' => '',
			'terminal1' => '',
			'terminal2' => '',
			'terminal3' => '',
			'adult' => 0,
			'child' => 0,
			'infant' => 0,
			'source_code' => $ticket['source_city_code'],
			'destination_code' => $ticket['destination_city_code'],
			'library_name' => '',
			'config' => [],
			'airline_name' => $ticket['airline'],
			'infant_price' => $infant_price,
			'ResultIndex' => '',
			'IsLCC' => false,
			'GSTAllowed' => false,
			'IsHoldAllowedWithSSR' => false,
			'IsPanRequiredAtTicket' => false,
			'IsPassportRequiredAtBook' => false,
			'IsPassportRequiredAtTicket' => false,
			'IsCouponAppilcable' => false,
			'IsGSTMandatory' => false,
			'SuppTokenId' => '',
			'SuppTraceId' => '',
			'tokenid' => '',
			'clientid' => '',
			'agencytype' => '',
			'segments' => array(
				array(
					'segmentindicator' => 1,
					'aircode' => $ticket['aircode'],
					'airname' => $ticket['airline'],
					'flight_number' => $ticket['flight_no'],
					'fare_class' => $ticket['class'],
					'operating_carrier' => '',
					'duration' => $diff,
					'departure_city' => $ticket['source_city_code'],
					'departure_terminal' => $ticket['terminal'],
					'departure_datetime' => $ticket['departure_date_time'],
					'arrival_city' => $ticket['destination_city_code'],
					'arrival_terminal' => $ticket['arrival_terminal'],
					'arrival_datetime' => $ticket['arrival_date_time'],
					'aircraft' => '',
					'flight_status' => 'Confirmed',
					'iseticketeligible' => true
				)
			),
			'live_corrected' => false,
		));
		return $myticket;
	}

	private function append_thiredparty_tickets(&$tickets, $thirdpartyinventory) {
		if($thirdpartyinventory && is_array($thirdpartyinventory) && count($thirdpartyinventory)>0) {
			for($partyidx=0; $partyidx < count($thirdpartyinventory); $partyidx++) {
				$thirdpartytickets = $thirdpartyinventory[$partyidx];
				for($i=0; $i<count($thirdpartytickets); $i++) {
					$ticket = $thirdpartytickets[$i];

					if($ticket) {
						array_push($tickets, $ticket);
					}
				}
			}

			return $tickets;
		}
		else {
			return $tickets;
		}
	}

	private function load_thirdparty_inventory($libname) {
		//call_user_func(array($classname, 'say_hello'));

		//return $this->api_tripmaza->reauthenticate();
		if(isset($this->$libname)) {
			return $this->$libname->reauthenticate();
		}
		else {
			log_message('error', "Library ($libname) not loaded yet. Can't call library method load_thirdparty_inventory");
			return [];
		}
	}

	private function search_inventory($libname, $data) {
		// $config = array("companyid" => $companyid, "host" => "demo.api", "userid" => "9800412356", "userpassword" => "4434045132", "url" => "http://demoapi.tripmaza.com/");

		$ticket_format = array(
			"library" => $libname,
			"id" => 0,
			"uid" => 0,
			"remarks" => "Live inventory",
			"source" => 0,
			"destination" => 0,
			"source1" => 0,
			"destination1" => 0,
			"pnr" => "",
			"source_city" => "",
			"destination_city" => "",
			"trip_type" => "ONE",
			"departure_date_time" => "",
			"arrival_date_time" => "",
			"departure_date_time1" => "",
			"arrival_date_time1" => "",
			"flight_no" => "",
			"terminal" => "NA",
			"flight_no1" => "",
			"terminal" => "",
			"terminal1" => "",
			"terminal2" => "",
			"terminal3" => "",
			"no_of_person" => 0,
			"adult" => 0,
			"child" => 0,
			"infant" => 0,
			"class" => "ECONOMY",
			"no_of_stops" => 0,
			"data_collected_from" => "",
			"sale_type" => "",
			"refundable" => "",
			"total" => 0.00,
			"airline" => null,
			"image" => "faculty_1554735599.png",
			"aircode" => "",
			"ticket_no" => "",
			"price" => 0,
			"companyid" => 0,
			"companyname" => "",
			"user_id" => -1,
			"updated_on" => "",
			"updated_by" => "",
			"tag" => null,
			"admin_markup" => 0,
			"last_sync_key" => "",
			"approved" => 1,
			"rate_plan_id" => -1,
			"supplierid" => -1,
			"sellerid" => -1,
			"seller_rateplan_id" => -1,
			"dept_date_time" => null,
			"arrv_date_time" => null,
			"adultbasefare" => null,
			"adult_tax_fees" => null,
			"timediff" => null,
			"departure_terminal" => null,
			"arrival_terminal" => null,
			"adult_total" => null,
			"live_fare" => -1,
			"seatsavailable" => 0,
			"whl_markup" => 0.00,
			"whl_srvchg" => 0.00,
			"whl_cgst" => 0,
			"whl_sgst" => 0,
			"whl_igst" => 0,
			"whl_disc" => 0,
			"spl_markup" => 0,
			"spl_srvchg" => 0,
			"spl_cgst" => 0,
			"spl_sgst" => 0,
			"spl_igst" => 0,
			"spl_disc" => 0,
			"cost_price" => 0.00,
			"new" => 1
		);

		$data['ticket_format'] = $ticket_format;

		// $this->load->library('api_tripmaza', $config);
		//return $this->api_tripmaza->search_inventory($data);
		if(isset($this->$libname)) {
			return $this->$libname->search_inventory($data);
		}
		else {
			log_message('error', "Library ($libname) not loaded yet. Can't call library method - search_inventory");
			return [];
		}
		
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
		$direct_qty = intval($this->input->get('qty'));
		if($direct_qty>0) {
			$this->session->set_userdata('no_of_person', $direct_qty);
		}

		$api_fare_quote = [];
		$state = $this->session->userdata('state');
		$error = $this->session->userdata('ERROR');

		if(isset($error)) {
			$this->session->unset_userdata('ERROR');
		}

		$current_user = $this->session->userdata('current_user');
		$posted_tickets = $this->session->userdata('tickets');
		if ($this->session->userdata('user_id') && isset($id)) 
		{			  
			$company = $this->session->userdata('company');
			$companyid = intval($company["id"]);
			$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
			$defaultRP = NULL;
			$defaultRPD = NULL;
			$suprpd = [];
			$sellrpd = [];

			if(count($rateplans)>0) {
				$defaultRP = $rateplans[0];
				$rateplanid = $defaultRP['id'];

				if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && isset($current_user["rateplanid"]) && intval($current_user["rateplanid"])>0) {
				// if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && $current_user["rateplanid"]!==null) {
					$rateplanid = intval($current_user["rateplanid"]);
				}
					
				$defaultRPD = $this->Admin_Model->rateplandetails($rateplanid);
			}
	
			$rateplan_details = $this->Admin_Model->rateplandetails(-1);
	
			$tickets = [];
			//Lets not reading the table once again. Read it from the session and proceed next. If we see issue in reading session then we can create a temp table with intrasient ticket and go from there.
			// $tickets = $this->Search_Model->flight_details($id, $companyid);
			if(!($tickets && is_array($tickets) && count($tickets)>0)) {
				//This ticket is an API ticket. Not available in local table
				$tickets = [];

				foreach($posted_tickets as $posted_ticket) {
					// Lets not use it only for API, even for FD also read it from session stored tickets
					// if(intval($posted_ticket['id']) === intval($id) && $posted_ticket['sale_type'] === 'api') {
					if(intval($posted_ticket['id']) === intval($id)) {
						array_push($tickets, $posted_ticket);
						break;
					}
				}
			}
			
			if($tickets && count($tickets)>0) {
				$ticket = $tickets[0];

				$ticket['adult'] = $state['adult'];
				$ticket['child'] = $state['child'];
				$ticket['infant'] = $state['infant'];
				$ticket['class'] = $state['class'];

				$adult = intval($state['adult']);
				$child = intval($state['child']);
				$infant = intval($state['infant']);
				$pax = $adult + $child;

				log_message('debug', "Selected Ticket => ".json_encode($ticket));
				log_message('debug', "State => ".json_encode($state));

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
					if($companyid === intval($ticket['companyid'])) {
						$sellrpd = $defaultRPD;
						$suprpd = $defaultRPD;
					}
					
					$user['user_markup']=$this->User_Model->user_settings($current_user['id'], array('markup'));
	
					if($user['user_markup']!==NULL) {
						if($user['user_markup']['field_value_type'] === '2') {
							$adminmarkup = floatval($user['user_markup']['field_value']);
						}
					}	

					$state['adminmarkup'] = $adminmarkup;
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
				if($ticket['sale_type'] !== 'api') {
					$ticketupdated = $this->calculationTicketValue($ticket, $suprpd, $sellrpd, $companyid, $adminmarkup, $usertype);
					$api_fare_quote = $this->getFareQuote($ticket, $ticketupdated, $current_user, $company, $state);
				}
				else {
					$library_name = $ticket['library_name'];
					$lib_config = $ticket['config'];
					$traceid = $ticket['SuppTraceId'];
					$tokenid = $ticket['SuppTokenId'];
					$result_index = $ticket['ResultIndex'];

					if(isset($library_name)) {
						$this->load->library($library_name, $lib_config);
						$api_fare_quote = $this->$library_name->fare_quote($ticket);

						if($api_fare_quote && is_array($api_fare_quote) && count($api_fare_quote) > 0) {
							$isprice_changed = isset($api_fare_quote['isprice_changed']) ? boolval($api_fare_quote['isprice_changed']) : false;
							if($isprice_changed) {
								$ticket['price'] = round($api_fare_quote['price'], 0);
								$ticket['total'] = round($api_fare_quote['base_price'], 0);
							}
						}
					}
				}

				if($error && $error !== null && $error !== '') {
					$result["error"]=$error; 
				}
				else {
					$result["error"]=false; 
				}
				$result["flight"]=array($ticket); 
				$result["currentuser"]=$current_user;
				//$result["setting"]=$this->Search_Model->setting();
				$setting = $this->Search_Model->company_setting($companyid);
				$result['setting'] = $setting;
				if(isset($result['setting']) && isset($result['setting'][0]['payment_gateway'])) {
					$result['setting'][0]['payment_gateway'] = json_decode($result['setting'][0]['payment_gateway'], true);
				}
				if(isset($result['setting']) && isset($result['setting'][0]['bank_accounts'])) {
					$result['setting'][0]['bank_accounts'] = json_decode($result['setting'][0]['bank_accounts'], true);
				}
				if(isset($result['setting']) && isset($result['setting'][0]['configuration'])) {
					$result['setting'][0]['configuration'] = json_decode($result['setting'][0]['configuration'], true);
				}
				if(isset($result['setting']) && isset($result['setting'][0]['api_integration'])) {
					$result['setting'][0]['api_integration'] = json_decode($result['setting'][0]['api_integration'], true);
				}

				$result["user_type"]=strtoupper($current_user["type"]);
				
				$result['user_details']=$this->User_Model->user_details();
				if($result["flight"][0]["no_of_person"]>0 && $result["flight"][0]["approved"]==1 && $api_fare_quote) {
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

					if($api_fare_quote && $api_fare_quote['passengers_fare'] && is_array($api_fare_quote['passengers_fare']) && count($api_fare_quote['passengers_fare']) > 0) { 
						$passengers_fare = $api_fare_quote['passengers_fare'];
						$tds = floatval($api_fare_quote['fare']['total_tds']);
						$commision = floatval($api_fare_quote['fare']['total_commission']);
						$discount = floatval($api_fare_quote['fare']['discount']);
						$total_spl_margin = floatval($api_fare_quote['fare']['total_spl_margin']);
						$total_whl_margin = floatval($api_fare_quote['fare']['total_whl_margin']);
						$tax = round(floatval($api_fare_quote['fare']['tax']) + floatval($api_fare_quote['fare']['othercharges']) + $tds + (($total_spl_margin + $total_whl_margin) * ($adult + $child)) - $commision - $discount, 0);

						$infant_price = floatval($api_fare_quote['fare']['infant_price']);
						$offeredfare = floatval($api_fare_quote['fare']['offeredfare']);
						$price = ($offeredfare + $tds - ($infant_price * $infant)) / ($adult + $child);
						$total = 0;
						foreach ($passengers_fare as $passenger_fare) { 
							$value = floatval($passenger_fare['BaseFare']) * intval($passenger_fare['PassengerCount']);
							$total += $value;
						}

						$financial = array(
							'offeredfare' => $offeredfare,
							'total_tds' => $tds,
							'commision' => $commision,
							'discount' => $discount,
							'total_spl_maargin' => $total_spl_margin,
							'total_whl_margin' => $total_whl_margin,
							'taxandcharges' => $tax,
							'total_basefare' => $total,
							'grand_total' => ($total + $tax),
							'price' => $price,
							'adult' => $adult,
							'child' => $child,
							'infant' => $infant,
							'infant_price' => $infant_price
						);

						$state['financial'] = $financial;
					}

					$state['sale_type'] = $ticket['sale_type'];

					$result['mywallet']= $this->getMyWallet();
					$result['state']= $state;
					$result['fare_quote'] = $api_fare_quote;

					log_message('debug', json_encode($result));

					$this->session->set_userdata('selected_ticket',$result);
					
					$this->load->view('header1',$result);
					//$this->load->view('flight-detail',$result);
					$this->load->view('flight-detailv2',$result);
					$this->load->view('footer1');
				}
				else
				{
					redirect("/search");
				}
			}
		}
	}

	private function getFareQuote($ticket, $ticketupdated, $current_user, $company, $state) {

		$adult = intval($state['adult']);
		$child = intval($state['child']);
		$infant = intval($state['infant']);
		$pax = $adult + $child;
		$infant_fare = floatval($ticket['infant_price']);
		if($infant===0) {
			$infant_fare = 0.00;
		}

		$adminmarkup = isset($state['adminmarkup']) ? floatval($state['adminmarkup']) : 0;
		$published_fare = ((floatval($ticket['total']) + $adminmarkup) * $pax) + ($infant_fare * $infant);
		$offered_fare = ((floatval($ticket['total']) + $adminmarkup) * $pax) + ($infant_fare * $infant);

		$whl = floatval($ticket['whl_markup']) + floatval($ticket['whl_srvchg']) + floatval($ticket['whl_cgst']) + floatval($ticket['whl_sgst']) - floatval($ticket['whl_disc']);
		$spl = floatval($ticket['spl_markup']) + floatval($ticket['spl_srvchg']) + floatval($ticket['spl_cgst']) + floatval($ticket['spl_sgst']) - floatval($ticket['spl_disc']);
		$traceid = gen_uuid();

		$segment = false;
		if($ticket && isset($ticket['segments']) && count($ticket['segments']) > 0) {
			$segment = $ticket['segments'][0];
		}

		$fare_quote = array(
			'isprice_changed' => false,
			'offeredfare' => $offered_fare,
			'traceid' => $traceid,
			'fqindex' => intval($ticket['id']),
			'price' => floatval($ticket['price']),
			'base_price' => floatval($ticket['total']),
		);

		$diff = intval((strtotime($ticket['arrival_date_time'])-strtotime($ticket['departure_date_time']))/60);

		$fare_quote['ob_flight_segments'] = [];
		
		$flight_segment = array(
			'Baggage' => '15 KG',
			'CabinBaggage' => ' 7 KG',
			'CabinClass' => 2,
			'Craft' => "",
			'SegmentIndicator' => (($ticket['trip_type'] === 'ONE') ? 1 : 2),
			'Duration' => $diff,
			'IsETicketEligible' => $segment ? $segment['iseticketeligible'] : true,
			'FlightStatus' => $segment ? $segment['flight_status'] : 'Confirmed',
			'AirlineCode' => $segment ? $segment['aircode'] : '',
			'AirlineName' => $segment ? $segment['airname'] : '',
			'FareClass' => '',
			'FlightNumber' => $segment ? $segment['flight_number'] : '',
			'OperatingCarrier' => '',
			'DepTime' => $ticket['departure_date_time'],
			'ArrTime' => $ticket['arrival_date_time'],
			'Dep_AirportCode' => $ticket['source_city_code'],
			'Dep_AirportName' => $ticket['source_city'],
			'Dep_Terminal' => $segment ? $segment['departure_terminal'] : '',
			'Dep_CityCode' => $ticket['source_city_code'],
			'Dep_CityName' => $ticket['source_city'],
			'Dep_CountryCode' => 'IN',
			'Dep_CountryName' => 'India',
			'Arr_AirportCode' => $ticket['destination_city_code'],
			'Arr_AirportName' => $ticket['destination_city'],
			'Arr_Terminal' => $segment ? $segment['arrival_terminal'] : '',
			'Arr_CityCode' => $ticket['destination_city_code'],
			'Arr_CityName' => $ticket['destination_city'],
			'Arr_CountryCode' => 'IN',
			'Arr_CountryName' => 'India',
		);

		array_push($fare_quote['ob_flight_segments'], $flight_segment);

		$fare_quote['fare'] = array(
			'currency' => 'INR',
			'basefare' => floatval($ticket['total']),
			'tax' => 0,
			'yqtax' => 0,
			'othercharges' => 0,
			'additionaltxnfeepub' => 0,
			'additionaltxnfeeofrd' => 0,
			'discount' => 0,
			'publishedfare' => $published_fare,
			'offeredfare' => $offered_fare,
			'commissionearned' => 0,
			'plbearned' => 0,
			'incentiveearned' => 0,
			'total_commission' => 0,
			'tdsoncommission' => 0,
			'tdsonplb' => 0,
			'tdsonincentive' => 0,
			'total_tds' => 0,
			'total_spl_margin' => $spl,
			'total_whl_margin' => $whl,
			'servicefee' => 0,
			'totalbaggagecharges' => 0,
			'totalmealcharges' => 0,
			'totalseatcharges' => 0,
			'totalspecialservicecharges' => 0,
			'airlineremark' => 'Fixed Departure Ticket',
			'gstallowed' => isset($ticket['GSTAllowed']) ? boolval($ticket['GSTAllowed']) : false,
			'isrefundable' => 0,
			'islcc' => boolval($ticket['IsLCC']),
			'isholdallowedwithssr' => boolval($ticket['IsHoldAllowedWithSSR']),
			'ispanrequiredatticket' => boolval($ticket['IsPanRequiredAtTicket']),
			'ispassportrequiredatbook' => boolval($ticket['IsPassportRequiredAtBook']),
			'ispassportrequiredatticket' => boolval($ticket['IsPassportRequiredAtTicket']),
			'iscouponappilcable' => boolval($ticket['IsCouponAppilcable']),
			'isgstmandatory' => boolval($ticket['IsGSTMandatory']),
			'isprice_changed' => false,
			'traceid' => $traceid,
			'infant_price' => floatval($ticket['infant_price'])
		);

		$fare_quote['passengers_fare'] = [];

		if($adult > 0) {
			// for($ai=0; $ai<$adult; $ai++) {
				$adult_passenger = array(
					'Currency' => 'INR',
					'PassengerType' => 1,
					'PassengerCount' => $adult,
					'BaseFare' => floatval($ticket['total']) + $adminmarkup,
					'Tax' => 0.00,
					'YQTax' => 0.00,
					'AdditionalTxnFeeOfrd' => 0.00,
					'AdditionalTxnFeePub' => 0.00,
					'PGCharge' => 0.00
				);
	
				array_push($fare_quote['passengers_fare'], $adult_passenger);
			// }
		}
		
		if($child > 0) {
			// for($ai=0; $ai<$child; $ai++) {
				$child_passenger = array(
					'Currency' => 'INR',
					'PassengerType' => 2,
					'PassengerCount' => $child,
					'BaseFare' => floatval($ticket['total']) + $adminmarkup,
					'Tax' => 0.00,
					'YQTax' => 0.00,
					'AdditionalTxnFeeOfrd' => 0.00,
					'AdditionalTxnFeePub' => 0.00,
					'PGCharge' => 0.00
				);

				array_push($fare_quote['passengers_fare'], $child_passenger);
			// }
		}

		if($infant > 0) {
			// for($ai=0; $ai<$infant; $ai++) {
				$infant_passenger = array(
					'Currency' => 'INR',
					'PassengerType' => 3,
					'PassengerCount' => $infant,
					'BaseFare' => floatval($ticket['infant_price']),
					'Tax' => 0.00,
					'YQTax' => 0.00,
					'AdditionalTxnFeeOfrd' => 0.00,
					'AdditionalTxnFeePub' => 0.00,
					'PGCharge' => 0.00
				);

				array_push($fare_quote['passengers_fare'], $infant_passenger);
			// }
		}


		return $fare_quote;
	}

	private function calculationTicketValue(&$ticket, $defaultRPD, $rateplan_details, $companyid, $adminmarkup=0, $usertype='') {
		$rateplanid = $ticket['rate_plan_id'];
		$seller_rateplanid = $ticket['seller_rateplan_id'];
		$price = $ticket['price'];
		$price_infant = isset($ticket['infant_price']) ? $ticket['infant_price'] : 0;
		
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
			if(intval($ticket['supplierid']) !== intval($companyid)) {
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
	
	public function review_booking($id) {
		$prev_error = $this->session->userdata('ERROR'); //,'Unable to process ticket at this moment (API timedout). Please try after some time.');
		if(isset($prev_error)) {
			$this->session->unset_userdata('ERROR');
		}

		$selected_ticket = $this->session->userdata('selected_ticket');
		log_message('debug', "Selected Ticket Data => ".json_encode($selected_ticket));
		$passengers = $this->get_passengerslist($_POST, $selected_ticket['state']);
		$payment_mode = $_POST['payment-gw'];

		$state = &$selected_ticket['state'];
		$state['passengers'] = $passengers;
		$state['payment_gateway'] = $payment_mode;

		$current_user = $this->session->userdata('current_user');
		$wallet=$this->get_walletbalance($current_user);
		$wallet_balance = $wallet['balance'];
		//$total_costprice = ($qty * $costprice);

		if ($this->session->userdata('user_id') && isset($id)) 
		{	
			$company = $this->session->userdata('company');
			$companyid = intval($company["id"]);

			$this->session->set_userdata('selected_ticket', $selected_ticket);
			log_message('debug', "Updated State => ".json_encode($selected_ticket));

			// log_message('debug', json_encode($selected_ticket));

			$this->load->view('header1',$selected_ticket);
			//$this->load->view('flight-detail',$result);
			$this->load->view('review-booking',$selected_ticket);
			$this->load->view('footer1');
		}
		else
		{
			redirect("/search");
		}
	}

	public function pre_booking($id) {
		$prev_error = $this->session->userdata('ERROR'); //,'Unable to process ticket at this moment (API timedout). Please try after some time.');
		if(isset($prev_error)) {
			$this->session->unset_userdata('ERROR');
		}

		$selected_ticket = $this->session->userdata('selected_ticket');
		log_message('debug', "Selected Ticket Data => ".json_encode($selected_ticket));

		$setting = $selected_ticket['setting'];
		$setting = $setting[0];
		$state = $selected_ticket['state'];
		$wallet = $selected_ticket['mywallet'];
		$current_user = $this->session->userdata('current_user');
		$user_id = intval($current_user['id']);

		$company = $this->session->userdata('company');
		$companyid = $company['id'];
		$financial = $state['financial'];
		$amount = floatval($financial['grand_total']);
		$isonlinepayment = false;
		if($state && isset($state['payment_gateway'])) {
			$isonlinepayment = ($state['payment_gateway'] !== 'Wallet');
		}

		if($isonlinepayment) {
			// take payment from payment gateway
			$pg = $state['payment_gateway'];
			$pg_configs = $setting['payment_gateway'];
			
			$selected_pg_config = false;

			if($pg_configs && is_array($pg_configs) && count($pg_configs) > 0) {
				foreach($pg_configs as $pg_config) {
					if($pg_config && isset($pg_config['pw_name']) && $pg_config['pw_name'] === $pg) {
						$selected_pg_config = $pg_config;
						break;
					}
				}
			}

			// Save transaction into DB and pass the transaction for processing
			$sponsoring_companyid = $current_user['sponsoring_companyid'];

			//Get the gateway specific feed so that data can be posted into 
			$gateway_feed = $this->processPayment(array(
				"pg_config" => $selected_pg_config,
				"total_amount" => $amount,
				"current_user" => $state['current_user'],
				"payment_gateway" => $state['payment_gateway'],
				"company" => $company
			));

			$view_name = $this->getPGViewName($state['payment_gateway']);
			//Place to same transaction id into pg_transactions_tbl and same will be updated once the response received.
			$wallet_trans_id = $this->User_Model->save("pg_transactions_tbl", 
				array(
					'trans_tracking_id' => $gateway_feed['txnid'], 
					'amount' => $amount,
					'final_amount' => $gateway_feed['final_amount'],
					'companyid' => $companyid,
					'userid' => $user_id,
					'sponsoring_companyid' => $sponsoring_companyid,
					'payment_gateway' => $state['payment_gateway'],
					'pg_config' => json_encode($selected_pg_config, JSON_HEX_QUOT),
					'payment_mode' => -1,
					'request_data' => json_encode($gateway_feed, JSON_HEX_QUOT),
					'created_by' => $user_id
				)
			);

			$result['payment_gateway'] = $state['payment_gateway'];
			$result['company_setting'] = $this->Search_Model->company_setting($companyid);
			if(isset($result['company_setting']) && isset($result['company_setting'][0]['payment_gateway'])) {
				$result['company_setting'][0]['payment_gateway'] = json_decode($result['company_setting'][0]['payment_gateway'], true);
			}
			if(isset($result['company_setting']) && isset($result['company_setting'][0]['bank_accounts'])) {
				$result['company_setting'][0]['bank_accounts'] = json_decode($result['company_setting'][0]['bank_accounts'], true);
			}
			if(isset($result['company_setting']) && isset($result['company_setting'][0]['configuration'])) {
				$result['company_setting'][0]['configuration'] = json_decode($result['company_setting'][0]['configuration'], true);
			}
			if(isset($result['company_setting']) && isset($result['company_setting'][0]['api_integration'])) {
				$result['company_setting'][0]['api_integration'] = json_decode($result['company_setting'][0]['api_integration'], true);
			}

			$result['pg'] = $gateway_feed;

			$this->load->view($view_name, $result);
		}
		else if($wallet) {

			$mywallet = $this->Search_Model->get_wallet_balance($companyid, $user_id);


			$current_wallet_balance = isset($wallet['balance']) ? floatval($wallet['balance']) : 0.00;
			if($mywallet && isset($mywallet['balance'])) {
				$current_wallet_balance = floatval($mywallet['balance']);
			}
			// $walletid = intval($wallet['walletid']);
			// $wallet_balance = isset($wallet['balance']) ? floatval($wallet['balance']) : 0.00;
			if($current_wallet_balance >= $amount) {
				// yes enough balance present now proceed with booking.
				$payload = false;
				$this->process_booking($payload);
			}
			else {
				$id = -1;
				$flight = $selected_ticket['flight'];
				if($flight && is_array($flight) && count($flight) > 0) {
					$flight = $flight[0];
					$id = $flight['id'];
				}
				$this->session->set_userdata('ERROR','Insufficient wallet balance. Can`t proceed further.');
				redirect("search/flightdetails/$id");
			}
		}
	}

	private function processPayment($payload) {
		$payment_gateway_data = false;
		if($payload && isset($payload['pg_config'])) {
			$amount = $payload['total_amount'];
			$company = $payload['company'];
			$current_user = $payload['current_user'];
			$selected_pg = $payload['pg_config'];
			
			if($payload['payment_gateway'] === 'PayU') {
				$return_url = base_url().'paymentgateway/booking_payu_response';
				$payment_gateway_data = $this->getPG_payload_payu(array('payment_type' => -1, 'amount' => $amount, 'selected_pg' => $selected_pg, 'company' => $company, 'current_user' => $current_user, 'return_url' => $return_url));
			}
			else if($payload['payment_gateway'] === 'Atom') {
				$return_url = base_url().'paymentgateway/booking_atom_response';
				$payment_gateway_data = $this->getPG_payload_atom(array('payment_type' => -1, 'amount' => $amount, 'selected_pg' => $selected_pg, 'company' => $company, 'current_user' => $current_user, 'return_url' => $return_url));
			}
		}

		return $payment_gateway_data;
	}

	public function pre_booking_pg_response($pg_name) {
		// After payment receive it will land here. Now we need to process the booking.
		$payload = false;
		if($pg_name === 'atom') {
			$payload = $this->processAtom_payment_response();
		}
		else if($pg_name === 'payu') {
			$payload = $this->processPayU_payment_response();
		}
		else {
			// nothing yet
		}
		$this->process_booking($payload);
	}

	private function process_booking($payload) {
		// now processing booking
		$selected_ticket = $this->session->userdata('selected_ticket');
		$selected_ticket['mywallet']= $this->getMyWallet();
		
		$this->session->set_userdata('selected_ticket', $selected_ticket);
		
		log_message('debug', "Selected Ticket Data => ".json_encode($selected_ticket));
		log_message('debug', "After PG response [Payload] => ".json_encode($payload));

		$this->book_new(array('payload' => $selected_ticket, 'pg_confirmation' => $payload));
	}

	private function processAtom_payment_response() {
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

			// $this->session->set_flashdata('payment_response', $payload);
			// redirect('/search');

			return $payload;
		}
		else {
			redirect('/search');
		}
	}

	private function processPayU_payment_response() {
		$payload = false;
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
		}

		return $payload;
	}

	private function get_passengerslist($postedvalue, $state) {
		$passengers = [];
		$no_of_passengers = 0;
		if($postedvalue && $state && is_array($state) && intval($state['no_of_person'])>0) {
			$no_of_passengers = intval($state['no_of_person']);

			for ($i=0; $i < $no_of_passengers; $i++) { 
				$enable = (($postedvalue["chkPassenger_$i"] === 'on') ? true : false);
				$pass_type_code = 1;
				if($postedvalue["passenger_type_$i"] === 'Child') {
					$pass_type_code = 2;
				}
				else if($postedvalue["passenger_type_$i"] === 'Infant') {
					$pass_type_code = 3;
				}

				if($enable) {
					$passenger = array(
						'passenger_type' => $postedvalue["passenger_type_$i"],
						'passenger_type_code' => $pass_type_code,
						'enable' => $enable,
						'passenger_title' => $postedvalue["slPassenger_$i"],
						'passenger_first_name' => $postedvalue["txtPassenger_FN_$i"],
						'passenger_last_name' => $postedvalue["txtPassenger_LN_$i"],
						'passenger_email' => isset($postedvalue["txtPassenger_EML_$i"]) ? $postedvalue["txtPassenger_EML_$i"] : '',
						'passenger_mobile' => isset($postedvalue["txtPassenger_MBL_$i"]) ? $postedvalue["txtPassenger_MBL_$i"] : '',
						'passenger_dob_day' => isset($postedvalue["slDOBDay_Passenger_$i"]) ? intval($postedvalue["slDOBDay_Passenger_$i"]) : -1,
						'passenger_dob_month' => isset($postedvalue["slDOBMonth_Passenger_$i"]) ? intval($postedvalue["slDOBMonth_Passenger_$i"]) : -1,
						'passenger_dob_year' => isset($postedvalue["slDOBYear_Passenger_$i"]) ? intval($postedvalue["slDOBYear_Passenger_$i"]) : -1,
						'passenger_age' => isset($postedvalue["slDOBYear_Passenger_$i"]) ? (intval(date("Y")) - intval($postedvalue["slDOBYear_Passenger_$i"])) : -1
					);
				}

				array_push($passengers, $passenger);
			}
		}
		return $passengers;
	}

	public function beforebook($id)
	{
		$prev_error = $this->session->userdata('ERROR'); //,'Unable to process ticket at this moment (API timedout). Please try after some time.');
		if(isset($prev_error)) {
			$this->session->unset_userdata('ERROR');
		}

		$selected_ticket = $this->session->userdata('selected_ticket');
		log_message('debug', "Selected Ticket Data => ".json_encode($selected_ticket));

		$current_user = $this->session->userdata('current_user');
		if ($this->session->userdata('user_id') && isset($id)) 
		{	
			$company = $this->session->userdata('company');
			$companyid = intval($company["id"]);
			$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
			$defaultRP = NULL;
			$defaultRPD = NULL;
			$suprpd = [];
			$sellrpd = [];
			if(count($rateplans)>0) {
				$defaultRP = $rateplans[0];
				$rateplanid = $defaultRP['id'];
				
				if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && isset($current_user["rateplanid"]) && intval($current_user["rateplanid"])>0) {				
				//if($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1' && $current_user["rateplanid"]!==null) {
					$rateplanid = intval($current_user["rateplanid"]);
				}
	
				$defaultRPD = $this->Admin_Model->rateplandetails($rateplanid);
			}
	
			$rateplan_details = $this->Admin_Model->rateplandetails(-1);

			$tickets = $this->Search_Model->flight_details($id, $companyid);
			if(!($tickets && is_array($tickets) && count($tickets)>0)) {
				//This ticket is an API ticket. Not available in local table
				$tickets = [];
				$posted_tickets = $this->session->userdata('tickets');

				foreach($posted_tickets as $posted_ticket) {
					if(intval($posted_ticket['id']) === intval($id) && $posted_ticket['sale_type'] === 'api') {
						array_push($tickets, $posted_ticket);
						break;
					}
				}
			}

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
					if($companyid === intval($ticket['companyid'])) {
						$sellrpd = $defaultRPD;
						$suprpd = $defaultRPD;
					}
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

				if($ticket['sale_type'] !== 'api') {
					$ticketupdated = $this->calculationTicketValue($ticket, $suprpd, $sellrpd, $companyid, $adminmarkup, $usertype);
					$this->session->set_userdata('current_ticket',$ticket);
				}

				$service_charge = floatval($this->input->post('service_charge'));
				$result1["user"]=$this->User_Model->user_details();
				$result["currentuser"] = $current_user;
				$result["prev_error"] = $prev_error;
				$result['setting']=$this->Search_Model->company_setting($companyid);

				$baserate = ($ticket["total"]) + $ticket["whl_markup"] + $ticket["spl_markup"];
				$srvchg = $ticket["whl_srvchg"] + $ticket["spl_srvchg"];
				$gst = $ticket["whl_cgst"] + $ticket["spl_cgst"] + $ticket["whl_sgst"] + $ticket["spl_sgst"];
				$total = $baserate + $srvchg + $gst;
				$costprice = 0;
				if($current_user["is_admin"]==='1' || $current_user["type"]==='EMP')
				{
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

	public function book_api_ticket($payload) {
		$result = null;

		$ticket = $payload['ticket'];
		$current_user = $payload['current_user'];
		$company = $payload['company'];
		$companyid = intval($company['id']);
		$posteddata = $payload['posteddata'];
		$customers = $payload['customers'];

		$admin_user = $this->User_Model->get('user_tbl', array('id' => $company['primary_user_id']));
		if($admin_user && is_array($admin_user) && count($admin_user)>0) {
			$admin_user = $admin_user[0];
		}

		$payload['admin_user'] = $admin_user;

		log_message('debug', 'Booking via API => [Customers]'.json_encode($customers));

		if($ticket && $ticket['sale_type']=='api' && isset($ticket['library'])) {
			$library = $ticket['library'];
			log_message('debug', "Selected library -> $library");
			$listof3ppintegrations = $this->getThirdpartyIntegrations($companyid, $current_user);

			$api = null;
			for($i=0; $i<count($listof3ppintegrations); $i++) {
				if($listof3ppintegrations[$i]['library_name'] === $library) {
					$api = $listof3ppintegrations[$i];
					break;
				}
			}

			$this->load->library($library, $api);
			//$this->load_thirdparty_inventory($library);

			$returnValue = $this->$library->book($payload);
	
			log_message('debug', 'Booking API Result '.json_encode($returnValue));

			if($returnValue && isset($returnValue['Status']) && boolval($returnValue['Status'])) {
				$payload['booking_details'] = $returnValue;
				$booking_details = $this->$library->get_booking_details($payload);
				log_message('debug', 'Booking Details from API => '.json_encode($booking_details));
				$returnValue = isset($returnValue['Result']) ? $returnValue['Result'] : NULL;
				if($returnValue && isset($returnValue['bookingid']) && intval($returnValue['bookingid'])>0) {
					$bookingid = intval($returnValue['bookingid']);

					$result = array(
						'company' => $company,
						'admin_user' => $admin_user,
						'current_user' => $current_user,
						'ticket' => $ticket,
						'customers' => $customers,
						'booking_confirmation' => $returnValue,
						'itinerary' => $booking_details
					);

					$synthetic_ticket = $this->create_synthetic_ticket($result);

					$result['synthetic_ticket'] = $synthetic_ticket;
				}

				log_message('debug', 'Api call paylod => '.json_encode($result));
			}
			else {
				log_message('debug', 'Api call failed => '.json_encode($returnValue));
				$result = $returnValue;
			}
		}
		
		return $result;
	}

	public function create_synthetic_ticket($payload) {
		if(!$payload || !is_array($payload)) return NULL;

		$company =  $payload['company'];
		$admin_user =  $payload['admin_user'];
		$current_user =  $payload['current_user'];
		$ticket =& $payload['ticket'];
		$customers = $payload['customers'];
		$booking_confirmation = $payload['booking_confirmation'];
		$itinerary = $payload['itinerary'];
		$duplicate_ticket = null;
		$tktid = -1;

		if ($ticket && is_array($ticket)) {
			$pnr = isset($booking_confirmation['pnrlist']) ? $booking_confirmation['pnrlist'] : '';
			$flight_no = isset($ticket['flight_no']) ? $ticket['flight_no'] : '';

			$query = array('companyid' => $ticket['companyid'], 'aid' => $ticket['id'], 'data_collected_from' => "'".$ticket['data_collected_from']."'");
			if($pnr !== '') {
				$query['pnr'] = "'$pnr'";
			}
			if($flight_no !== '') {
				$query['flight_no'] = "'$flight_no'";
			}
			$duplicate_ticket = $this->isDuplicateTicketPresent($query);
			if($duplicate_ticket && is_array($duplicate_ticket) && count($duplicate_ticket)>0) {
				$duplicate_ticket = $duplicate_ticket[0];
				$tktid = $duplicate_ticket['id'];
				$tag = $duplicate_ticket['tag'];
				$api_extra_data = array(json_decode($duplicate_ticket['api_extra_data'], TRUE), array('itinerary' => $itinerary, 'ResultIndex' => intval($ticket['ResultIndex']),'SearchIndex' => intval($ticket['SearchIndex']),'SuppSource' => intval($ticket['SuppSource']),'SuppTokenId' => $ticket['SuppTokenId'],'SuppTraceId' => $ticket['SuppTraceId'],'tokenid' => $ticket['tokenid'],'clientid' => $ticket['clientid'],'agencytype' => $ticket['agencytype']));

				$result = $this->Search_Model->update('tickets_tbl', 
					array(
						'departure_date_time' => $ticket['departure_date_time'], 
						'arrival_date_time' => $ticket['arrival_date_time'],
						"terminal" => isset($ticket['terminal'])?$ticket['terminal']:'',
						"total" => $ticket['total'],
						"price" => $ticket['price'],
						"tag" => "$tag | ".("Booking id: ".$booking_confirmation['bookingid']),
						"api_extra_data" => json_encode($api_extra_data, JSON_UNESCAPED_SLASHES),
						"updated_by" => $admin_user['id'],
						"updated_on" => date('Y-m-d H:i:s')
					),
					array('id' => $tktid));
			}
			else {
				//lets create a duplicate ticket same as $ticket passed (API Ticket)
				$tktid = $this->Search_Model->save('tickets_tbl', array(
					'aid' => $ticket['id'],
					'created_date' => date('Y-m-d H:i:s'),
					'source' => $ticket['source'],
					'destination' => $ticket['destination'],
					'source1' => $ticket['source1'],
					'destination1' => $ticket['destination1'],
					'trip_type' => $ticket['trip_type'],
					'departure_date_time' => $ticket['departure_date_time'],
					'arrival_date_time' => $ticket['arrival_date_time'],
					'pnr' => $itinerary['pnr'],
					"trip_type" => $ticket['trip_type'],
					"departure_date_time1" => $ticket['departure_date_time1'],
					"arrival_date_time1" => $ticket['arrival_date_time1'],
					"flight_no" => $ticket['flight_no'],
					"flight_no1" => $ticket['flight_no1'],
					"terminal" => isset($ticket['terminal'])?$ticket['terminal']:'',
					"terminal1" => isset($ticket['terminal1'])?$ticket['terminal1']:'',
					"terminal2" => isset($ticket['terminal2'])?$ticket['terminal2']:'',
					"terminal3" => isset($ticket['terminal3'])?$ticket['terminal3']:'',
					"no_of_person" => 0, //$ticket['no_of_person'], //Making this as a temporary fix. With live booking this will be changed
					"max_no_of_person" => 0, // $ticket['no_of_person'], //Making this as a temporary fix. With live booking this will be changed
					"class" => $ticket['class'],
					"class1" => $ticket['class'],
					"no_of_stops" => $ticket['no_of_stops'],
					// "stops_name" => $ticket['stop_name'],
					// "no_of_stops1" => $ticket['no_of_stops1'],
					// "stops_name1" => $ticket['stops_name1'],
					"airline" => $ticket['airline'],
					// "airline1" => $ticket['airline1'],
					"aircode" => $ticket['aircode'],
					// "aircode1" => $ticket['aircode1'],
					"ticket_no" => $ticket['ticket_no'],
					"price" => $ticket['price'],
					// "baggage" => $ticket['baggage'],
					// "meal" => $ticket['meal'],
					// "markup" => $ticket['markup'],
					"admin_markup" => isset($ticket['adminmarkup']) ? $ticket['adminmarkup'] : 0,
					// "discount" => $ticket['discount'],
					"total" => $ticket['total'],
					"sale_type" => 'live',
					"refundable" => $ticket['refundable'],
					"availibility" => 0, //$ticket['no_of_person'], //Making this as a temporary fix. With live booking this will be changed
					"user_id" => $admin_user['id'],
					"remarks" => $ticket['remarks'],
					"approved" => '1',
					"available" => 'NO', //'YES', //Making this as a temporary fix. With live booking this will be changed
					"data_collected_from" => $ticket['data_collected_from'],
					"last_sync_key" => $ticket['last_sync_key'],
					"created_by" => $admin_user['id'],
					"companyid" => $ticket['companyid'],
					"price_child" => 0.00,
					"price_infant" => 0.00,
					"cancel_rate" => 0.00,
					"booking_freeze_by" => $ticket['departure_date_time'],
					"tag" => ("Booking id: ".$booking_confirmation['bookingid']),
					"library_name" => $ticket['library'],
					"api_extra_data" => json_encode(array(
						'itinerary' => $itinerary, 
						'ResultIndex' => isset($ticket['ResultIndex']) ? $ticket['ResultIndex'] : '-1',
						'SearchIndex' => isset($ticket['SearchIndex']) ? $ticket['SearchIndex'] : '-1',
						'SuppSource' => isset($ticket['SuppSource']) ? $ticket['SuppSource'] : '-1',
						'SuppTokenId' => $ticket['SuppTokenId'],
						'SuppTraceId' => $ticket['SuppTraceId'],
						'tokenid' => $ticket['tokenid'],
						'clientid' => $ticket['clientid'],
						'agencytype' => $ticket['agencytype'],
					), JSON_UNESCAPED_SLASHES)
				));
			}
		}

		if($tktid>0) {
			$duplicate_ticket = $this->get_ticket($tktid, $current_user, $company, null);

			log_message('debug', "Synthetic Ticket Created: ".json_encode($duplicate_ticket));
		}

		return $duplicate_ticket;
	}

	public function isDuplicateTicketPresent($condition)  {
		if(!$condition || !is_array($condition)) return NULL;

		return $this->Search_Model->getTableData('tickets_tbl', $condition);
	}

	private function book_new($booking_payload) {
		if(!$booking_payload) return;

		$payload = $booking_payload['payload'];
		$pg_confirmation = $booking_payload['pg_confirmation'];
		$current_user = $payload['currentuser'];
		$company = $this->session->userdata('company');
		$companyid = intval($company['id']);
		$state = $payload['state'];
		$booking_info = false;
		$isapi = false;
		$ticket = $payload['flight'][0];
		$id = intval($ticket['id']);
		log_message('debug', "Book New -> Payload => : ".json_encode($booking_payload));

		try
		{
			if($state && isset($state['sale_type']) && $this->is_booking_validv2($booking_payload)) {
				if($state['sale_type'] === 'api') {
					//Take API flow
					$booking_result = $this->do_api_booking($booking_payload);
					$payload = $booking_result['payload'];
					$state = $payload['state'];
					$isapi = true;
				}
				else {
					//Take FD flow
					$id = intval($ticket['id']);

					if(isset($ticket['sale_type']) && $ticket['sale_type'] === 'request') {
						$status = 'PENDING';
					}
					else {
						$status = ($ticket['pnr']!="") ? "CONFIRMED" : "PENDING";
					}
					
					$requesting_by = isset($ticket['requesting_by']) ? $ticket['requesting_by'] : 1; //TODO
					$requesting_to = isset($ticket['requesting_to']) ? $ticket['requesting_to'] : 1; //TODO
					$seller_userid = isset($ticket['seller_userid']) ? intval($ticket['seller_userid']) : intval($ticket['uid']);
					$seller_companyid = isset($ticket['seller_companyid']) ? intval($ticket['seller_companyid']) : intval($ticket['companyid']);
					$adminmarkup = isset($ticket['adminmarkup']) ? floatval($ticket['adminmarkup']) : floatval($ticket['admin_markup']);
	
					$pnr = $ticket['pnr'];
					$user_id = intval($ticket['user_id']);
					$state['booked_ticket'] = $ticket;
					$state['id'] = $id;
					$state['booking_status'] = $status;
					$state['pnr'] = $pnr;

					$ticket['requesting_by'] = $requesting_by;
					$ticket['requesting_to'] = $requesting_to;
					$ticket['seller_userid'] = $seller_userid;
					$ticket['seller_companyid'] = $seller_companyid;
					$ticket['adminmarkup'] = $adminmarkup;

					$state['booked_ticket'] = $ticket;
				}

				// Save booking
				$ticket = isset($state['booked_ticket']) ? $state['booked_ticket'] : false;
				log_message('debug', 'Booked Ticket -> '.json_encode($ticket));

				$sale_type = 'request';
				$requesting_by = isset($ticket['requesting_by']) ? $ticket['requesting_by'] : 1; //TODO
				$requesting_to = isset($ticket['requesting_to']) ? $ticket['requesting_to'] : 1; //TODO
				$seller_userid = isset($ticket['seller_userid']) ? intval($ticket['seller_userid']) : intval($ticket['uid']);
				$seller_companyid = isset($ticket['seller_companyid']) ? intval($ticket['seller_companyid']) : intval($ticket['companyid']);
				$adminmarkup = isset($ticket['adminmarkup']) ? floatval($ticket['adminmarkup']) : floatval($ticket['admin_markup']);
				$financial = isset($state['financial']) ? $state['financial'] : false;

				if($ticket) {
					$suplcompany = $this->Admin_Model->get_company(intval($ticket['companyid']));
					if($suplcompany && is_array($suplcompany) && count($suplcompany)>0) {
						$suplcompany = $suplcompany[0];
					}
					$ticket = $state['booked_ticket'];
					$wallet = $payload['mywallet'];
					$passengers = $state['passengers'];
					$costprice = (floatval($ticket['cost_price']));
					$price = (floatval($ticket['cost_price']));
					// $total = (floatval($ticket['total']));
					$total = $financial ? floatval($financial['grand_total']) : (floatval($ticket['total']));

					$is_owned_ticket = intval($ticket['companyid'])===$companyid;
					$posteddata = array(
						"isownticket" => $is_owned_ticket,
						"isapi" => $isapi,
						"costprice" => $costprice,
						"price" => $price,
						"service_charge" => (floatval($ticket['whl_srvchg'])),
						"igst" => (floatval($ticket['whl_igst'])),
						"total_amount" => $total,
						"rateplanid" => intval($ticket['rate_plan_id']),
						"qty" => intval($state['adult']) + intval($state['child']),
						"adult" => intval($state['adult']),
						"child" => intval($state['child']),
						"infant" => intval($state['infant'])
					);
					$booking_payload['posteddata'] = $posteddata;
					log_message('debug', "POSTDATA => ".json_encode($posteddata));

					$booking_info = $this->save_booking($current_user, $ticket, $status, $wallet, $company, $booking_payload, $passengers);

					$booking_id = intval($booking_info['booking_id']);
					$booking_date = $booking_info['booking_date'];
					$booking_activity_id = intval($booking_info['booking_activity_id']);
					$voucher_no = intval($booking_info['voucher_no']);
					$sale_type = isset($booking_info['sale_type'])?$booking_info['sale_type']:'request';
					$book_status = isset($booking_info['book_status'])?$booking_info['book_status']:'PENDING';
					log_message('debug', "After Save_Book call => Sale Type : $sale_type | Book Status : $book_status");

					if($book_status == 'PENDING' && !$isapi && !$is_owned_ticket) {
						$sale_type = "request";
					}
					log_message('debug', "After validation => Sale Type : $sale_type | Book Status : $book_status");
					$newbookinginfo=$this->Search_Model->booking_details($booking_id);
					if($newbookinginfo && count($newbookinginfo)>0) {
						$newbookinginfo = $newbookinginfo[0];
					}

					//
					$state['booking_info'] = $booking_info;
					$sale_type = isset($booking_info['sale_type'])?$booking_info['sale_type']:'request';
				}

				//perform wallet transactions
				$transactionresult = null;
				if($booking_info && $current_user["is_admin"]!='1' && $current_user["type"]!='EMP') {
					$booking_id = intval($booking_info['booking_id']);
					$booking_date = $booking_info['booking_date'];
					$total_costprice = floatval($state['financial']['grand_total']);
					$transactionresult = $this->do_wallet_transaction($current_user, $company, $ticket, array('booking_id' => $booking_id, 'booking_date' => $booking_date, 'total_costprice'=>$total_costprice));
				}

				if(($sale_type === 'live' || $isapi) && !$is_owned_ticket) {
					//This ticket is not own ticket and status became confirmed. So money has to be moved to wholsaler to supplier account
					$posteddata['status'] = $status;
					$posteddata['current_user'] = $current_user;
					$posteddata['mode'] = 'DR';
					$posteddata['transtype'] = 20;
					$posteddata['transreftype'] = 'PURCHASE';
					$posteddata['narration'] = "Buying supplier ticket (id: $booking_id)";
			
					$transactionresult = $this->do_system_wallet_transaction($company, $suplcompany, $ticket, $newbookinginfo, $posteddata);
					log_message('debug', "System Wallet Transaction => ".json_encode($transactionresult));
				}

				$wallettransid = 0;
				if($transactionresult && is_array($transactionresult) && isset($transactionresult['trans_id'])) {
					$wallettransid = intval($transactionresult['trans_id']);
				}


				//Update user accounts as purchased ticket as collection was received before
				$ordered_booking = $this->Search_Model->get('bookings_tbl', array('id' => $booking_id));
				if($ordered_booking && is_array($ordered_booking) && count($ordered_booking)>0) {
					$ordered_booking = $ordered_booking[0];
				}

				$customer_user = $this->Search_Model->get('user_tbl', array('id' => intval($ordered_booking['customer_userid'])));
				if($customer_user && is_array($customer_user) && count($customer_user)>0) {
					$customer_user = $customer_user[0];
				}

				if($customer_user && $status==='CONFIRMED' && intval($ordered_booking['pbooking_id'])===0 && isset($customer_user['is_admin']) && intval($customer_user['is_admin']) === 0) {
					$customer_companyid = $company['id'];
					log_message('debug', "Ticket fullfilled so lets give customer user`s accounts entry");
					log_message('debug', "Booking Id: $booking_id | Booking Status: $status | Amount: ".$ordered_booking['total']);

					log_message('debug', "[SAVED] Booking Id: $booking_id | Booking Status: $status | Amount: ".$ordered_booking['total']);
				}

				if($is_owned_ticket) {
					$sale_type = $ticket['sale_type'];
					$parent_booking_status = 'PENDING';
					$posteddata['isapi'] = $isapi;
					if($sale_type=='live' && $status=='CONFIRMED') {
						$parent_booking_status = 'APPROVED';
					}
					log_message('debug', "1. This is own ticket. Wholeslaer id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type");
				}
				else {
					//$sale_type = $ticket['sale_type'];
					log_message('debug', "2. This is not own ticket. Wholeslaer id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type");
					$suplbookinginfo = null;
					$parent_booking_status = 'PENDING';
					if($isapi || $sale_type==='live') {
						//get the booking info of supplier.
						//Assuming since its API means ticket is in live booking
						$posteddata['isapi'] = $isapi;
						$suplbookinginfo = $this->do_supplier_booking($newbookinginfo, $ticket, $posteddata, $company, $suplcompany, $booking_payload);
						$parent_booking_status = isset($suplbookinginfo['book_status']) ? $suplbookinginfo['book_status'] : 'PENDING';
						if($sale_type=='live' && $parent_booking_status=='CONFIRMED') {
							$parent_booking_status = 'APPROVED';
						}

						log_message('debug', "parent booking status : Wholesaler id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type | parent_booking_status => ".json_encode($suplbookinginfo, TRUE));
					}
				}

				log_message('debug', "Before ticket reducing : Wholesaler id : $companyid | Supplier id : $seller_companyid | API: $isapi | Sale.Type: $sale_type | parent_booking_status: $parent_booking_status");
				
				if(!$isapi && $sale_type==='live' && $parent_booking_status==="APPROVED") {
					//lets reduce ticket count
					$posteddata['qty'] = isset($newbookinginfo['qty'])?intval($newbookinginfo['qty']):0;
					$posteddata['ticketid'] = isset($newbookinginfo['ticket_id'])?intval($newbookinginfo['ticket_id']):0;
					$updated_ticket = $this->do_reducee_inventory($posteddata);
				}


				if((isset($ticket['pnr']) && $ticket['pnr']=='')) {
					$sale_type = 'request';
					log_message('debug', "Original sale type: $sale_type | PNR: ".$ticket['pnr']." | Changed to $sale_type sale type");
				}

				if($booking_id>0 && $wallettransid>=0) {
					$this->session->unset_userdata('state');
					$this->session->unset_userdata('no_of_person');
					log_message('debug', "Booking processed in $sale_type mode | Booking Id: $booking_id | Wallet Transaction id: $wallettransid | Accounts posting id: $voucher_no");
					redirect("/search/thankyou/".$booking_id."");
				}
				else {
					log_message('debug', "Can't process booking some error | Booking Id: $booking_id | Wallet Transaction id: $wallettransid | Accounts posting id: $voucher_no");
					redirect("search/beforebook/$id");
				}
			}
		}
		catch(Exception $ex) {
			log_message('error', $ex);
		}
	}

	private function do_api_booking($booking_payload) {
		if(!$booking_payload) return;

		$payload = $booking_payload['payload'];
		$pg_confirmation = $booking_payload['pg_confirmation'];
		$state = &$payload['state'];

		$is_booking_valid = $this->is_booking_validv2($booking_payload);

		$state['is_booking_valid'] = $is_booking_valid;

		if($is_booking_valid) {
			$state['status'] = "CONFIRM";
			$wallet = $payload['mywallet'];
			$current_user = $payload['currentuser'];
			$financial = $state['financial'];
			$passengers = $state['passengers'];
			$ticket = $payload['flight'][0]; //We need to change here for round trip flight
			$company = $this->get_companyinfo();
			$id = intval($ticket['id']);
			//Call API to book ticket
			$result = $this->book_api_ticket(array('current_user' => $current_user,'ticket' => $ticket,'company' => $company,'posteddata' => $booking_payload,'customers' => $passengers));

			log_message('debug', 'live booking result => '.json_encode($result));
			if(!$result || (isset($result['Error']) && intval($result['Error']['ErrorCode'])>0)) {
				$error_code = $result['Error']['ErrorCode'];
				$error_message = $result['Error']['ErrorMessage'];
				if($error_message==='') {
					$this->session->set_userdata('ERROR','Unable to process ticket at this moment (API timedout). Please try after some time.');
				}
				else {
					$this->session->set_userdata('ERROR',"$error_message (Code: $error_code)");
				}
				redirect("search/flightdetails/$id");
			}
			else {
				//for the time being insert a new ticket against API billing company. If present no need to create duplicate.
				if($result && isset($result['synthetic_ticket']) && $ticket && is_array($ticket)) {
					$ticket = array_merge($ticket, $result['synthetic_ticket']);
					$id = intval($ticket['id']);
					$status = ($ticket['pnr']!="") ? "CONFIRMED" : "PENDING";
					$pnr = $ticket['pnr'];
					$user_id = intval($ticket['user_id']);
					$state['booked_ticket'] = $ticket;
					$state['id'] = $id;
					$state['booking_status'] = $status;
					$state['pnr'] = $pnr;
				}	
			}
		}
		else {
			//redirect to search page with reason of error
		}

		$booking_payload['payload'] = $payload;
		log_message('debug', "Online booking => ".json_encode($booking_payload));

		return $booking_payload;
	}

	private function is_booking_validv2($booking_payload) {
		// check on
		// $flag = (floatval($wallet["balance"])>=$totalcostprice) || intval($current_user['is_admin'])===1 || (intval($current_user["credit_ac"])===1 && $totalcostprice<=$credit_limit);
		$payload = $booking_payload['payload'];
		$wallet = $payload['mywallet'];
		$current_user = $payload['currentuser'];
		$financial = $payload['state']['financial'];
		$passengers = $payload['state']['passengers'];
		$credit_limit = 999999999; /* This should be actual credit limit */

		$totalcostprice = floatval($financial['grand_total']);

		$bflag = (floatval($wallet["balance"])>=$totalcostprice) || intval($current_user['is_admin'])===1 || (intval($current_user["credit_ac"])===1 && $totalcostprice<=$credit_limit);

		//check duplicate passengers
		$hasduplicatecustomers = false;
		$customers = [];
		if($passengers && is_array($passengers) && count($passengers) > 0) {
			foreach($passengers as $passenger) {
				$uniquekey = $passenger['passenger_title'].'-'.$passenger['passenger_first_name'].'-'.$passenger['passenger_last_name'];

				if($passenger['passenger_title'] === '' || $passenger['passenger_first_name'] === '' || $passenger['passenger_last_name'] === '') {
					$hasduplicatecustomers = true;
				}

				if(isset($customers[$uniquekey])) {
					$hasduplicatecustomers = true;
				}

				array_push($customers, $uniquekey);
			}

			$bflag = $bflag && !$hasduplicatecustomers;
		}

		return $bflag;
	}

	public function book($id)
	{
		$isapi = false;
		$current_ticket = $this->session->userdata('current_ticket');
		$posted_tickets = $this->session->userdata('tickets');
		$ordering_ticket = null;
		if($posted_tickets && is_array($posted_tickets) && count($posted_tickets)>0) {
			foreach($posted_tickets as $posted_ticket) {
				if(intval($posted_ticket['id']) === intval($id) && $posted_ticket['sale_type'] === 'api') {
					$ordering_ticket = $posted_ticket;
					$isapi = true;
					break;
				}
			}
		}

		$amount=floatval($this->input->post('total'));
		$costprice=floatval($this->input->post('costprice'));
		$qty=intval($this->input->post('qty'));
		$date = $this->input->post('date');
		$price = $this->input->post('price');
		$service_charge = $this->input->post('service_charge');
		$igst = $this->input->post('igst');
		$rateplanid = $this->input->post('rateplanid');
		
		$company = $this->get_companyinfo();
		$current_user = $company['current_user'];
		$companyid = intval($company['id']);
		$form_valid = $this->is_booking_valid($companyid);

		$customers = $form_valid['customers'];
		
		if($form_valid['hasduplicatecustomers']!='true') {
			redirect("search/beforebook/$id");
		}

		$wallet=$this->get_walletbalance($current_user);
		$wallet_balance = $wallet['balance'];
		$total_costprice = ($qty * $costprice);

		if (isset($date) && isset($qty)) {
			$ticket = $this->get_ticket(($ordering_ticket===null? $id : -1), $current_user, $company, $ordering_ticket);
			$status = ($ticket['pnr']!="" && intval($ticket['user_id'])==intval($current_user["id"])) ? "CONFIRMED" : "PENDING";
			$pnr = $ticket['pnr'];
			$user_id = intval($ticket['user_id']);
			$is_owned_ticket = intval($ticket['companyid'])===$companyid;
			$ticket_source = (($posted_ticket['sale_type'] === 'api') ? 'API' : ($is_owned_ticket? 'OWN' : 'SOURCED'));
			//$posteddata['isownticket'] = $is_owned_ticket;
			$posteddata = array( "total_amount" => $amount, "costprice" => $costprice, "qty" => $qty, "date" => $date, "price" => $price, "service_charge" => $service_charge, "igst" => $igst, "rateplanid" => $rateplanid, "isownticket" => $is_owned_ticket, "ticket_source" => $ticket_source);

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
					if($pnr!="" && $this->session->userdata('user_id')==$user_id || $ticket['sale_type']==='api')
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

					log_message('debug', "Current_User : ".json_encode($current_user));
					log_message('debug', "Ticket : ".json_encode($ticket));
					log_message('debug', "Company : ".json_encode($company));
					log_message('debug', "Posted Data : ".json_encode($posteddata));
					log_message('debug', "Customers : ".json_encode($customers));

					//Book Live ticket as this is via API
					if($ticket['sale_type'] === 'api') {
						//Call API to book ticket
						$result = $this->book_api_ticket(array('current_user' => $current_user,'ticket' => $ticket,'company' => $company,'posteddata' => $posteddata,'customers' => $customers));

						if(!$result || (isset($result['Error']) && intval($result['Error']['ErrorCode'])>0)) {
							$error_code = $result['Error']['ErrorCode'];
							$error_message = $result['Error']['ErrorMessage'];
							if($error_message==='') {
								$this->session->set_userdata('ERROR','Unable to process ticket at this moment (API timedout). Please try after some time.');
							}
							else {
								$this->session->set_userdata('ERROR',"$error_message (Code: $error_code)");
							}
							redirect("search/beforebook/$id");
						}
						else {
							//for the time being insert a new ticket against API billing company. If present no need to create duplicate.
							if($result && isset($result['synthetic_ticket']) && $ticket && is_array($ticket)) {
								$ticket = array_merge($ticket, $result['synthetic_ticket']);
								$id = intval($ticket['id']);
								$status = ($ticket['pnr']!="") ? "CONFIRMED" : "PENDING";
								$pnr = $ticket['pnr'];
								$user_id = intval($ticket['user_id']);
							}	
						}
					}
					else {
						if($current_ticket && is_array($current_ticket) && count($current_ticket)>0) {
							$ticket = array_merge($current_ticket, $ticket);
						}
					}

					$suplcompany = $this->Admin_Model->get_company(intval($ticket['companyid']));
					if($suplcompany && is_array($suplcompany) && count($suplcompany)>0) {
						$suplcompany = $suplcompany[0];
					}

					$posteddata['isapi'] = $isapi;
					$booking_info = $this->save_booking($current_user, $ticket, $status, $wallet, $company, $posteddata, $customers);

					$booking_id = intval($booking_info['booking_id']);
					$booking_date = $booking_info['booking_date'];
					$booking_activity_id = intval($booking_info['booking_activity_id']);
					$voucher_no = intval($booking_info['voucher_no']);
					$sale_type = isset($booking_info['sale_type'])?$booking_info['sale_type']:'request';
					$book_status = isset($booking_info['book_status'])?$booking_info['book_status']:'PENDING';
					log_message('debug', "After Save_Book call => Sale Type : $sale_type | Book Status : $book_status");

					if($book_status == 'PENDING' && !$isapi && !$is_owned_ticket) {
						$sale_type = "request";
					}
					log_message('debug', "After validation => Sale Type : $sale_type | Book Status : $book_status");
					$newbookinginfo=$this->Search_Model->booking_details($booking_id);
					if($newbookinginfo && count($newbookinginfo)>0) {
						$newbookinginfo = $newbookinginfo[0];
					}

					log_message('debug', "Booking info : ".json_encode($booking_info));
					log_message('debug', "Booking Details : ".json_encode($newbookinginfo));
					
					$flight = $this->Search_Model->flight_details($id, $companyid);
					if(!($flight && is_array($flight) && count($flight)>0)) {
						$flight = array($ordering_ticket);
					}

					if($flight && count($flight)>0) {
						$flight = $flight[0];
					}
					$trip = ($flight['trip_type']=='ONE')?"ONE WAY":"RETURN";
					
					log_message('debug', "Flight : ".json_encode($flight));

					if(SEND_EMAIL) {
						//send email to customer here
						$posteddata['booking_status'] = $status;
						$this->prepare_send_email("BOOKING_CUSTOMER_EMAIL", $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo);
					}

					if(SEND_SMS) {
						//send sms to customer here
						$this->prepare_send_sms("BOOKING_CUSTOMER_SMS", $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo);
						//$this->prepare_send_sms("BOOKING_CUSTOMER_SMS", $booking_info, $company, $ticket, $customers, $posteddata);
						$primary_admin_user = $this->Search_Model->get('user_tbl', array('id' => intval($company['primary_user_id']))); //$seller_companyid
						if($primary_admin_user && is_array($primary_admin_user) && count($primary_admin_user)>0) {
							$primary_admin_user = $primary_admin_user[0];
						}

						log_message('debug', "Admin mobile number -> ".json_encode($primary_admin_user));
						$this->prepare_send_sms("BOOKING_CUSTOMER_SMS", $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo, $primary_admin_user['mobile']);
					}

					$transactionresult = null;
					if($current_user["is_admin"]!='1' && $current_user["type"]!='EMP') {
						$transactionresult = $this->do_wallet_transaction($current_user, $company, $ticket, array('booking_id' => $booking_id, 'booking_date' => $booking_date, 'total_costprice'=>$total_costprice));
					}

					log_message('debug', "Going to transact system wallet | Status => $status | Sale.Type => $sale_type");
					//if($status === 'CONFIRMED' && !$is_owned_ticket) {
					if(($sale_type === 'live' || $isapi) && !$is_owned_ticket) {
						//This ticket is not own ticket and status became confirmed. So money has to be moved to wholsaler to supplier account
						$posteddata['status'] = $status;
						$posteddata['current_user'] = $current_user;
						$posteddata['mode'] = 'DR';
						$posteddata['transtype'] = 20;
						$posteddata['transreftype'] = 'PURCHASE';
						$posteddata['narration'] = "Buying supplier ticket (id: $booking_id)";
				
						$transactionresult = $this->do_system_wallet_transaction($company, $suplcompany, $ticket, $newbookinginfo, $posteddata);
						log_message('debug', "System Wallet Transaction => ".json_encode($transactionresult));
					}

					$wallettransid = 0;
					if($transactionresult && is_array($transactionresult) && isset($transactionresult['trans_id'])) {
						$wallettransid = intval($transactionresult['trans_id']);
					}

					//Update user accounts as purchased ticket as collection was received before
					$ordered_booking = $this->Search_Model->get('bookings_tbl', array('id' => $booking_id));
					if($ordered_booking && is_array($ordered_booking) && count($ordered_booking)>0) {
						$ordered_booking = $ordered_booking[0];
					}

					$customer_user = $this->Search_Model->get('user_tbl', array('id' => intval($ordered_booking['customer_userid'])));
					if($customer_user && is_array($customer_user) && count($customer_user)>0) {
						$customer_user = $customer_user[0];
					}

					if($customer_user && $status==='CONFIRMED' && intval($ordered_booking['pbooking_id'])===0 && isset($customer_user['is_admin']) && intval($customer_user['is_admin']) === 0) {
						$customer_companyid = $company['id'];
						log_message('debug', "Ticket fullfilled so lets give customer user`s accounts entry");
						log_message('debug', "Booking Id: $booking_id | Booking Status: $status | Amount: ".$ordered_booking['total']);

						// Commenting it as B2B/B2C customer already debited as PURCHASE
						// $customer_company = $this->Search_Model->get('company_tbl', array('id' => $customer_companyid));
						// if($customer_company && is_array($customer_company) && count($customer_company)>0) { 
						// 	$customer_company = $customer_company[0];
						// }
						// //This is the place where we should add customer user's account transaction as purchased ticket
						// $vc_no = $this->Search_Model->get_next_voucherno($customer_company);
						// $whl_voucher_no = $this->Search_Model->save("account_transactions_tbl", array(
						// 	"voucher_no" => $vc_no, 
						// 	"transacting_companyid" => $customer_companyid, 
						// 	"transacting_userid" => intval($ordered_booking['customer_userid']),
						// 	"documentid" => $booking_id, 
						// 	"document_date" => $ordered_booking['booking_date'], 
						// 	"document_type" => 1,
						// 	"transaction_type" => "PURCHASE", //Changing it to Purchase for customer. It was Sales.
						// 	//"credit" => $total_costprice,  
						// 	"debit" => $total_costprice,  
						// 	"companyid" => $customer_companyid,  
						// 	"credited_accountid" => 7,  
						// 	"created_by"=>$ordered_booking['customer_userid'],
						// 	"narration" => "Sales booking (Booking id: $booking_id dated: ".$ordered_booking['booking_date']
						// ));

						log_message('debug', "[SAVED] Booking Id: $booking_id | Booking Status: $status | Amount: ".$ordered_booking['total']);
					}

					if($is_owned_ticket) {
						$sale_type = $ticket['sale_type'];
						$parent_booking_status = 'PENDING';
						$posteddata['isapi'] = $isapi;
						if($sale_type=='live' && $status=='CONFIRMED') {
							$parent_booking_status = 'APPROVED';
						}
						log_message('debug', "1. This is own ticket. Wholeslaer id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type");
					}
					else {
						//$sale_type = $ticket['sale_type'];
						log_message('debug', "2. This is not own ticket. Wholeslaer id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type");
						$suplbookinginfo = null;
						$parent_booking_status = 'PENDING';
						if($isapi || $sale_type==='live') {
							//get the booking info of supplier.
							//Assuming since its API means ticket is in live booking
							$posteddata['isapi'] = $isapi;
							$suplbookinginfo = $this->do_supplier_booking($newbookinginfo, $ticket, $posteddata, $company, $suplcompany);
							$parent_booking_status = isset($suplbookinginfo['book_status']) ? $suplbookinginfo['book_status'] : 'PENDING';
							if($sale_type=='live' && $parent_booking_status=='CONFIRMED') {
								$parent_booking_status = 'APPROVED';
							}
	
							log_message('debug', "parent booking status : Wholesaler id : $companyid | Supplier id : $seller_companyid |  Sale.Type: $sale_type | parent_booking_status => ".json_encode($suplbookinginfo, TRUE));
						}
					}

					log_message('debug', "Before ticket reducing : Wholesaler id : $companyid | Supplier id : $seller_companyid | API: $isapi | Sale.Type: $sale_type | parent_booking_status: $parent_booking_status");
					
					if(!$isapi && $sale_type==='live' && $parent_booking_status==="APPROVED") {
						//lets reduce ticket count
						$posteddata['qty'] = isset($newbookinginfo['qty'])?intval($newbookinginfo['qty']):0;
						$posteddata['ticketid'] = isset($newbookinginfo['ticket_id'])?intval($newbookinginfo['ticket_id']):0;
						$updated_ticket = $this->do_reducee_inventory($posteddata);
					}


					if((isset($ticket['pnr']) && $ticket['pnr']=='')) {
						$sale_type = 'request';
						log_message('debug', "Original sale type: $sale_type | PNR: ".$ticket['pnr']." | Changed to $sale_type sale type");
					}

					if($booking_id>0 && $wallettransid>=0) {
						$this->session->unset_userdata('state');
						$this->session->unset_userdata('no_of_person');
						log_message('debug', "Booking processed in $sale_type mode | Booking Id: $booking_id | Wallet Transaction id: $wallettransid | Accounts posting id: $voucher_no");
						redirect("/search/thankyou/".$booking_id."");
					}
					else {
						log_message('debug', "Can't process booking some error | Booking Id: $booking_id | Wallet Transaction id: $wallettransid | Accounts posting id: $voucher_no");
						redirect("search/beforebook/$id");
					}
				}
				catch(Exception $ex1) {
					log_message('error', $ex1);
				}
			}
		}
	}

	protected function do_reducee_inventory($payload) {
		$result = [];
		$qty = intval($payload['qty']);
		$ticketid = intval($payload['ticketid']);

		try
		{
			$returnvalue = $this->Search_Model->do_reducee_inventory($ticketid, $qty);

			$ticket = $this->Search_Model->get('tickets_tbl', array('id' => $ticketid));
			if($ticket && isset($ticket) && is_array($ticket) && count($ticket)>0) {
				$ticket = $ticket[0];

				log_message('debug', "Updated Ticket => ".json_encode($ticket));
				$result['ticket'] = $ticket;
			}
		}
		catch(Exception $ex) {
			log_message('debug', "Error => $ex");
		}
		return $result;
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

	protected function prepare_send_sms($function, $booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo, $mobile=-1) {
		$bookingid = $booking_info["booking_id"];
		switch ($function) {
			case 'BOOKING_CUSTOMER_SMS':
				$booking_data = $this->preparesmsdata4booking($booking_info, $company, $ticket, $customers, $posteddata, $flight, $newbookinginfo, $mobile);
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
		$pnr = (isset($ticket['pnr'])?$ticket['pnr']:'');
		$status = isset($posteddata['booking_status'])?$posteddata['booking_status']:'PENDING';

		$first_passenger_name = $customers[0]['prefix'].' '.$customers[0]['first_name'].' '.$customers[0]['last_name'];
		$to = $customers[0]['email'];
		$cc = ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? $current_user["email"] : $booking["email"];
		//$flight['source_city']
		
		$data = array(
            'company_name' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? $current_user["name"] : $company["display_name"],
			'phone_number' => $current_user["mobile"],
			'action_url' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? '': $company["baseurl"],
			'booking_status' => $status,
			'pnr' => $pnr,
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

	protected function preparesmsdata4booking($booking_info, $company, $ticket, $customers, $posteddata, $flight, $booking, $mobile=-1) {
		$company = $this->get_companyinfo();
		$current_user = $company["current_user"];
		$templates = $this->getTemplates();
		$bookingid = "BK-".$booking_info["booking_id"];
		$pax = count($customers);
		$pnr = (isset($ticket['pnr'])?$ticket['pnr']:'');
		$status = isset($posteddata['booking_status'])?$posteddata['booking_status']:'PENDING';

		$first_passenger_name = $customers[0]['prefix'].' '.$customers[0]['first_name'].' '.$customers[0]['last_name'];

		if($mobile===-1) {
			$mobile = $customers[0]['mobile_no'];
		}

		$to = $mobile; // $customers[0]['mobile_no'];
		$cc = '';
		//$flight['source_city']
		
		$data = array(
            'company_name' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? $current_user["name"] : $company["display_name"],
			'phone_number' => $current_user["mobile"],
			'action_url' => ($current_user["type"]==='B2B' && $current_user["is_admin"]!=='1')? '': $company["baseurl"],
			'booking_status' => $status,
			'pnr' => $pnr,
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

	protected function save_booking($current_user, $ticket, &$status, $wallet, $company, $posteddata_payload, $customers) {
		$booking_date = date("Y-m-d H:i:s");
		$companyid = intval($company['id']);
		$posteddata = $posteddata_payload['posteddata'];
		$isownticket = boolval($posteddata['isownticket']);
		$system_wallet_balance = 0;
		$sale_type = $ticket['sale_type']; //"request";
		$isapi = isset($posteddata['isapi']) ? boolval($posteddata['isapi']) : false;
		$pnr = isset($ticket['pnr'])?$ticket['pnr']:'';
		$state = isset($posteddata_payload['payload']) ? $posteddata_payload['payload']['state'] : false;
		$financial = false;
		if($state) {
			$financial = isset($state['financial']) ? $state['financial'] : false;
		}
		$price = floatval($posteddata['price']);
		$costprice = floatval($posteddata['costprice']);
		if($financial) {
			$price = isset($financial['price']) ? (floatval($financial['price']) + ($financial['total_spl_maargin'] + $financial['total_whl_margin'])) : $price;
			$costprice = isset($financial['price']) ? (floatval($financial['price']) + ($financial['total_spl_maargin'])) : $price;
		}

		if($companyid !== intval($ticket['companyid'])) {
			$supplier_contract = $this->Search_Model->get_suppliers_contract($companyid, intval($ticket['companyid']));
			if($supplier_contract && is_array($supplier_contract) && count($supplier_contract)>0) {
				$supplier_contract = $supplier_contract[0];
				if($supplier_contract && is_array($supplier_contract) && count($supplier_contract)>0) {
					$system_wallet_entry =  $this->Search_Model->get_wallet_balance($companyid, -1);
					if($system_wallet_entry && is_array($system_wallet_entry) && count($system_wallet_entry)>0) {
						$system_wallet_balance = floatval($system_wallet_entry['balance']);
	
						log_message('debug', "Wallet Balance => company: $companyid | user: -1 | Balance: $system_wallet_balance => ".json_encode($system_wallet_entry));
					}
				}
	
				if($supplier_contract && isset($supplier_contract['sale_type']) && $supplier_contract['sale_type']!=='') {
					if($supplier_contract['sale_type'] === 'live') {
						$sale_type = "live";
						if($system_wallet_balance>=floatval($posteddata['costprice'])) {
							$status = 'CONFIRMED';
						}
						else {
							log_message('debug', "Wholesaler don't have enough ticket value ");
							$status = 'PENDING';
						}
					}
					else if($isapi && $pnr !== '') {
						$status = 'CONFIRMED';
						$sale_type = "live";
					}
					else {
						$status = 'PENDING';
						$sale_type = "request";
					}
				}
			}
		} 
		else {
			if($sale_type ==='live' && $pnr!=='') {
				$status = 'CONFIRMED';
			}
		}

		$status_code = (($status==='CONFIRMED')?2:0);
		if($sale_type === "live" && $status!=='CONFIRMED') {
			$status_code = 4;
		}

		$arr = array(
			"booking_date"=>$booking_date,
			"ticket_id"=>$ticket["id"],
			"pnr"=>$ticket["pnr"],
			"customer_userid"=>$current_user["id"],
			"customer_companyid"=>$company["id"],
			"seller_userid"=>$ticket['seller_userid'],
			"seller_companyid"=>$ticket['seller_companyid'],
			"status"=>$status_code,
			"price"=>$price,
			"admin_markup"=>floatval($ticket['adminmarkup']),
			"markup"=>0,
			"srvchg"=>floatval($posteddata['service_charge']),
			"cgst"=>floatval($posteddata['igst'])/2, /* This is wrong should be directly taken from cgst value */
			"sgst"=>floatval($posteddata['igst'])/2, /* This is wrong should be directly taken from sgst value */
			"igst"=>floatval($posteddata['igst']),
			"total"=>floatval($posteddata['total_amount']),
			"costprice"=>$costprice,
			"rateplanid"=>intval($posteddata['rateplanid']),
			"qty"=>intval($posteddata['qty']),
			"adult"=>intval($ticket['adult']),
			"child"=>intval($ticket['child']),
			"infant"=>intval($ticket['infant']),
			"infant_price"=>floatval($ticket['infant_price']),
			"created_by"=>$current_user["id"],
			"requesting_by"=>intval($ticket['requesting_by']),
			"target_userid"=>intval($ticket['seller_userid']),
			"target_companyid"=>intval($ticket['seller_companyid']),
			"requesting_to"=>intval($ticket['requesting_to']),
			"debit" => (($current_user["type"]=='B2B' && $current_user["is_admin"]!='1')? floatval($posteddata['costprice'] * intval($posteddata['qty'])) : floatval($posteddata['total_amount'])),
			"ticket_account" => (!isset($company['ticket_sale_account']) || $company['ticket_sale_account']==null)? -1 : $company['ticket_sale_account']['accountid'],
			"isownticket" => boolval($posteddata['isownticket']),
			"sale_type" => $sale_type,
			"wallet_balance" => $system_wallet_balance
		);

		log_message('debug', "Save Booking => ".json_encode($arr));

		if(floatval($posteddata['total_amount'])>0) {
			//insert data into booking_activity_tbl
			$booking_result = $this->Search_Model->book_ticket($arr, $company, $current_user, $ticket, $wallet, $posteddata, $customers);
			return array("booking_id" => $booking_result['booking_id'], "booking_date" => $arr['booking_date'], "booking_activity_id" => $booking_result['booking_activity_id'], 
				"voucher_no" => $booking_result['voucher_no'], 'wallet_balance' => $system_wallet_balance, 'book_status' => $status, 'sale_type' => $sale_type);
		}
		else {
			return array("booking_id" => -1, "booking_date" => date("Y-m-d H:i:s"), "booking_activity_id" => -1, "voucher_no" => -1, 'wallet_balance' => $system_wallet_balance, 'book_status' => '', 'sale_type' => $sale_type);
		}
	}

	protected function do_supplier_booking($newbookinginfo, $ticket, $posteddata, $company, $suplcompany, $posteddata_payload = false) {
		$booking_date = date("Y-m-d H:i:s");
		$companyid = intval($company['id']);
		$seller_userid = intval($company['primary_user_id']);
		$supl_compannyid = intval($suplcompany['id']);
		$supl_userid = intval($suplcompany['primary_user_id']);
		$seller_wallet = $this->Search_Model->get_wallet_balance($companyid, -1);
		$supplier_wallet = $this->Search_Model->get_wallet_balance($supl_compannyid, -1);

		$seller_user = $this->User_Model->get_userbyid($seller_userid);

		$parent_bookingid = intval($newbookinginfo['id']);

		$state = ($posteddata_payload && isset($posteddata_payload['payload'])) ? $posteddata_payload['payload']['state'] : false;
		$adult = isset($state['adult']) ? intval($state['adult']) : 0;
		$child = isset($state['child']) ? intval($state['child']) : 0;
		$infant = isset($state['infant']) ? intval($state['infant']) : 0;

		$financial = false;
		if($state) {
			$financial = isset($state['financial']) ? $state['financial'] : false;
		}
		$price = floatval($posteddata['price']);
		$costprice = floatval($posteddata['costprice']);
		$infant_price = 0;
		if($financial) {
			$price = round(isset($financial['price']) ? (floatval($financial['price']) + ($financial['total_spl_maargin'])) : $price, 0);
			$costprice = isset($financial['price']) ? (floatval($financial['price'])) : $price;
			$infant_price = isset($financial['infant_price']) ? (floatval($financial['infant_price'])) : $infant_price;
		}

		// $baseprice = floatval($ticket['total']);
		$baseprice = floatval($ticket['total']);
		// $spl_markup = floatval($ticket['spl_markup']);
		$spl_markup = $financial['total_spl_maargin'];
		
		// $price = ($baseprice+$spl_markup);
		$spl_srvchg = floatval($ticket['spl_srvchg']);
		// $spl_cgst = round(($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100), 0);
		// $spl_sgst = round(($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100), 0);
		$spl_cgst = floatval($ticket['spl_cgst']);
		$spl_sgst = floatval($ticket['spl_sgst']);
		$spl_igst = ($spl_cgst + $spl_sgst);

		//$whl_costrate = floatval($ticket['cost_price']); //($baseprice + $spl_markup + $spl_srvchg) + ($spl_cgst + $spl_sgst);
		$whl_costrate = $costprice;

		// $whl_costprice = $whl_costrate * intval($posteddata['qty']);
		$whl_costprice = ($price * intval($posteddata['qty'])) + ($infant_price * $infant);

		log_message('debug', "Ticket for supplier booking => ".json_encode($ticket));
		log_message('debug', "Booking commercials => $price - $spl_markup - $spl_srvchg - $spl_cgst - $spl_sgst - $spl_igst - $whl_costprice - $whl_costrate");
		//$supplier_company = $this->Admin_Model->get_company($supl_compamnyid);

		$isownticket = boolval($posteddata['isownticket']);
		$isapi = boolval($posteddata['isapi']);
		$sale_type = boolval($ticket['sale_type']);

		$status = 'PENDING';
		if($sale_type === 'live') {
			$status = 'CONFIRMED';
		}
		$system_wallet_balance = 0;

		if($companyid !== $supl_compannyid) {
			$saller_contract = $this->Search_Model->get_wholesaler_contract($companyid, $supl_compannyid);
			if($saller_contract && is_array($saller_contract) && count($saller_contract)>0) {
				$saller_contract = $saller_contract[0];
				if($saller_contract && is_array($saller_contract) && count($saller_contract)>0) {
					$system_wallet_entry =  $this->Search_Model->get_wallet_balance($companyid, -1);
					if($system_wallet_entry && is_array($system_wallet_entry) && count($system_wallet_entry)>0) {
						$system_wallet_balance = floatval($system_wallet_entry['balance']);
	
						log_message('debug', "Wallet Balance => company: $companyid | user: -1 | Balance: $system_wallet_balance => ".json_encode($system_wallet_entry));
					}
				}
	
				if($saller_contract && isset($saller_contract['sale_type']) && $saller_contract['sale_type']!=='') {
					if($saller_contract['sale_type'] === 'live') {
						if($system_wallet_balance>=floatval($posteddata['costprice'])) {
							$status = 'CONFIRMED';
						}
						else {
							log_message('debug', "Wholesaler don't have enough ticket value ");
							$status = 'PENDING';
						}
					}
				}
			}
		}

		if(isset($ticket["pnr"]) && trim($ticket["pnr"]) === '') {
			$status = 'PENDING';
		}
		else {
			if($isapi && trim($ticket["pnr"]) !== '') {
				$status = 'CONFIRMED';
			}
		}

		$arr = array(
			"booking_date"=>$booking_date,
			"ticket_id"=>$ticket["id"],
			"pbooking_id" => $parent_bookingid>0?$parent_bookingid:null,
			"pnr" => $ticket["pnr"],
			"customer_userid" => intval($company["primary_user_id"]),
			"customer_companyid" => intval($company["id"]),
			"seller_userid" => intval($suplcompany['primary_user_id']),
			"seller_companyid" => $supl_compannyid,
			"status" => (($status==='CONFIRMED')?2:0),
			"price" => $price,
			"admin_markup" => 0,
			"markup" => $spl_markup,
			"srvchg" => $spl_srvchg,
			"cgst" => $spl_cgst, /* This is wrong should be directly taken from cgst value */
			"sgst" => $spl_sgst, /* This is wrong should be directly taken from sgst value */
			"igst" => $spl_igst,
			"total" => $whl_costprice,
			"costprice" => $baseprice,
			"rateplanid" => intval($posteddata['rateplanid']),
			"qty" => intval($posteddata['qty']),
			"adult" => intval($posteddata['adult']),
			"child" => intval($posteddata['child']),
			"infant" => intval($posteddata['infant']),
			"infant_price"=>floatval($ticket['infant_price']),
			"created_by" => $seller_userid,
			"requesting_by" => 4,  //always 4 because requesting by is wholesaler
			"target_userid" => intval($ticket['seller_userid']),
			"target_companyid" => intval($ticket['seller_companyid']),
			"requesting_to" => 8, // intval($ticket['requesting_to']), always supplier
			"debit" => $whl_costprice,
			"ticket_account" => $company['ticket_sale_account']==null? -1 : $company['ticket_sale_account']['accountid'],
			"isownticket" => boolval($posteddata['isownticket']),
			"seller_company" => $suplcompany,
			"sale_type" => $sale_type
		);

		if($whl_costprice>0) {
			//insert data into booking_activity_tbl
			$posteddata['booking_type'] = 'WHL-SPL';
			$booking_result = $this->Search_Model->book_ticket($arr, $company, $seller_user, $ticket, $seller_wallet, $posteddata, null);
			unset($posteddata['booking_type']);
			return array("booking_id" => $booking_result['booking_id'], "booking_date" => $arr['booking_date'], "booking_activity_id" => $booking_result['booking_activity_id'], 
				"voucher_no" => $booking_result['voucher_no'], 'wallet_balance' => $system_wallet_balance, 'book_status' => $status);
		}
		else {
			return array("booking_id" => -1, "booking_date" => date("Y-m-d H:i:s"), "booking_activity_id" => -1, "voucher_no" => -1, 'wallet_balance' => $system_wallet_balance, 'book_status' => '');
		}
	}

	protected function do_wallet_transaction($current_user, $company, $ticket, $postedvalue) {
		$amount=floatval($postedvalue['total_costprice']); //floatval($this->input->post('costprice'));
		$booking_id = intval($postedvalue['booking_id']);
		$booking_date = intval($postedvalue['booking_date']);
		$companyid = intval($company['id']);
		$walletid = intval($current_user['wallet_id']);
		$userid = intval($current_user['id']);
		$ticket_account = isset($company['ticket_sale_account']) ? $company['ticket_sale_account'] : -1;
		$wallet_transid = 0;
		$voucher_no = 0;
		
		if ($current_user['is_admin']=='0') {
			log_message('debug', "[Search:do_wallet_transaction] | User Id: $userid | Wallet Id: $walletid | User is not admin - ".json_encode($current_user));
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

			log_message('debug', "[Search:do_wallet_transaction] | User Id: $userid | Wallet transaction successful - $wallet_transid | Amount : $amount");

			$mywallet = $this->Search_Model->get('system_wallets_tbl', array('id' => $current_user['wallet_id']));
			$wl_balance = 0;
			if($mywallet && count($mywallet)>0) {
				$mywallet = $mywallet[0];

				$wl_balance = floatval($mywallet['balance']);
				$wallet_amount = $wl_balance;
			}

			log_message('debug', "[Search:do_wallet_transaction] | User Id: $userid | Wallet Id: $walletid | Current Balance: $wl_balance | Transaction amount: $amount | New Balance: ". ($wl_balance-$amount));
			$wl_balance -= $amount;
			$voucher_no = -1;
			$return = $this->Search_Model->update('system_wallets_tbl', array('balance' => $wl_balance), array('id' => $current_user['wallet_id']));
			log_message('debug', "[Search:do_wallet_transaction] Wallet Id: $walletid | New wallet balance updated: $wl_balance | Wallet Balance: $wallet_amount");
			//save data to account_transactions_tbl;
			//If wallet balance is there and amount realized from wallet then add that to accounts
			//if($amount<=$wallet_amount) {
			if($wallet_amount>0) {
				log_message('debug', "[Search:do_wallet_transaction] Transacting Accounts | User Id: $userid | Wallet Id: $walletid | Previous Wallet Balance: $wallet_amount | Transaction amount: $amount");

				/* Correction made as it was set as credit, but once the amount deducted from account it should be debit. So changing it to Debit and marking it as COLLECTION */
				// Commenting code below
				// $arr=array(
				// 	"voucher_no" => $this->Search_Model->get_next_voucherno($company),
				// 	"transacting_companyid" => $companyid,
				// 	"transacting_userid" => intval($current_user['id']), 
				// 	"documentid" => $wallet_transid,
				// 	"document_date" => $wallet_trans_date,
				// 	"document_type" => 2, /* Payment receive */
				// 	"transaction_type" => "PURCHASE",
				// 	//"debit" => ($amount<=$wallet_amount)?$amount:$wallet_amount,
				// 	"debit" => $amount,
				// 	"companyid" => $companyid,
				// 	"debited_accountid" => ($ticket_account==null? -1: $ticket_account['accountid']),
				// 	"created_by" => intval($current_user['id']),
				// 	"narration" => (($amount<=$wallet_amount)?"Full":"Partial")." collection received towards (Booking id: $booking_id dated: $booking_date"
				// );
				// $voucher_no = $this->Search_Model->save("account_transactions_tbl",$arr);
			}
			else {
				log_message('debug', "[Search:do_wallet_transaction] Enough wallet balance not present | User Id: $userid | Wallet Id: $walletid | Previous Wallet Balance: $wallet_amount | Transaction amount: $amount");
			}
		}

		return array('wallet_transid' => $wallet_transid, 'voucher_no' => $voucher_no);
	}

	protected function do_system_wallet_transaction($company, $suplcompany, $ticket, $newbookinginfo, $posteddata) {
		$wholesaler_wallet = $this->Search_Model->get_wallet_balance(intval($company['id']), -1);
		$supplier_wallet = $this->Search_Model->get_wallet_balance(intval($suplcompany['id']), -1);

		$whl_wallet_id = intval($wholesaler_wallet['id']);
		$spl_wallet_id = intval($supplier_wallet['id']);

		$baseprice = intval($ticket['total']);
		$spl_costprice = $baseprice;
		$whl_costprice = intval($ticket['cost_price']);
		$pax = intval($newbookinginfo['qty']);
		$whl_totalcost = $pax*$whl_costprice;
		$current_user = $posteddata['current_user'];

		$mode = $posteddata['mode'];
		$transtype = intval($posteddata['transtype']);
		$transreftype = $posteddata['transreftype'];
		$narration = $posteddata['narration'];

		$booking_id = intval($newbookinginfo['id']);
		$booking_date = date('Y-m-d H:i:s', strtotime($newbookinginfo['date']));
		$transid = uniqid();

		log_message('debug', "[Search:do_system_wallet_transaction] | Wholesaler Wallet.ID: $whl_wallet_id | Supplier Wallet.ID: $spl_wallet_id | Baseprice: $baseprice | SPL.Cost: $spl_costprice | WHL.Cost: $whl_costprice | PAX: $pax");
		$wallet_trans_date = date("Y-m-d H:i:s");
		
		$arr=array(
			'wallet_id' => $whl_wallet_id,
			'date' => $wallet_trans_date,
			'trans_id' => $transid,
			'companyid' => intval($company['id']),
			'userid' => 0,
			'amount' => $whl_totalcost,
			'dr_cr_type'=> $mode, //'DR',
			'trans_type' => $transtype, // 20, /*20 is for Ticket Booking */
			'trans_ref_id'=>$booking_id,
			'trans_ref_date'=>$booking_date,
			'trans_ref_type' => $transreftype, //'PURCHASE',
			'trans_tracking_id' => $transid,
			'trans_documentid' => $booking_id,
			'narration' => $narration, // "Buying supplier ticket (id: $booking_id)",
			'sponsoring_companyid' => -1, //$current_user['sponsoring_companyid'],
			'created_by'=>intval($current_user['id']),
			'status'=>1,
			'approved_by'=>$company['primary_user_id'],
			'approved_on'=>date("Y-m-d H:i:s"),
			'target_companyid'=>intval($suplcompany['id'])
		);					 

		//wholesaler's wallet got debited for this ticket supplied by supplier
		$result = $this->Search_Model->transact_wallet($arr);
		$wallet_transid = -1;
		if($result && intval($result['trans_id'])>0) {
			$wallet_transid = intval($result['trans_id']);
			log_message('debug', "[Search:do_system_wallet_transaction | Wholesaler Side] | Wallet transaction successful - $wallet_transid");
		}

		//After wallet transaction same transaction should be made in accounts. Now perform that
		$whl_voucher_no = $this->Search_Model->save("account_transactions_tbl", array(
			"voucher_no" => $this->Search_Model->get_next_voucherno($suplcompany), 
			"transacting_companyid" => intval($company['id']), 
			"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
			"documentid" => $booking_id, 
			"document_date" => $booking_date, 
			"document_type" => 1,
			"transaction_type" => "COLLECTION",
			//"debit" => $whl_totalcost,  
			"credit" => $whl_totalcost,  /*Since payment receive from wholealer to supplier but ticket not yet provided. So amount should be posted as credit in the name of Wholesaler. Which should be settled by ticket raised */
			"companyid" => intval($suplcompany['id']),  
			"credited_accountid" => 7,  //some dummy value
			"created_by" => $suplcompany['primary_user_id'],
			"narration" => "Collection received towards (Booking id: $booking_id dated: ".date("Y-m-d H:i:s", strtotime($booking_date.'+00:00'))
		));	
		//$wallet_transid = $this->Search_Model->save("wallet_transaction_tbl",$arr);

		//supplier system wallet should credited with same amount as Payment
		$arr=array(
			'wallet_id' => $spl_wallet_id,
			'date' => $wallet_trans_date,
			'trans_id' => $transid,
			'companyid' => intval($suplcompany['id']),
			'userid' => 0,
			'amount' => $whl_totalcost,
			'dr_cr_type'=> ($mode=='DR'?'CR':'DR'), //'DR',
			'trans_type' => $transtype, // 20, /*20 is for Ticket Booking */
			'trans_ref_id'=>$booking_id,
			'trans_ref_date'=>$booking_date,
			'trans_ref_type' => 'PAYMENT', //'PURCHASE',
			'trans_tracking_id' => $transid,
			'trans_documentid' => $wallet_transid,
			'narration' => $narration, // "Buying supplier ticket (id: $booking_id)",
			'sponsoring_companyid' => -1, //$current_user['sponsoring_companyid'],
			'created_by'=>intval($current_user['id']),
			'status'=>1,
			'approved_by'=>$suplcompany['primary_user_id'],
			'approved_on'=>date("Y-m-d H:i:s"),
			'target_companyid'=>intval($company['id'])
		);					 

		//wholesaler's wallet got debited for this ticket supplied by supplier
		$result = $this->Search_Model->transact_wallet($arr);
		$wallet_transid = -1;
		if($result && intval($result['trans_id'])>0) {
			$wallet_transid = intval($result['trans_id']);
			log_message('debug', "[Search:do_system_wallet_transaction | Supplier Side] | Wallet transaction successful - $wallet_transid");
		}

		//After wallet transaction same transaction should be made in accounts. Now perform that
		$spl_voucher_no = $this->Search_Model->save("account_transactions_tbl", array(
			"voucher_no" => $this->Search_Model->get_next_voucherno($company), 
			"transacting_companyid" => intval($suplcompany['id']), 
			"transacting_userid" => 0, //$parameters["customer_userid"],  This is in between wholesaler and supplier primary account so userid is zero
			"documentid" => $booking_id, 
			"document_date" => $booking_date, 
			"document_type" => 1,
			"transaction_type" => "PAYMENT",
			//"credit" => $whl_totalcost,  
			"debit" => $whl_totalcost,  /*This payment made by wholesaler to supplier. So it should debit supplier account. Once the ticket is being provide same amount will be credited supplier account. */
			"companyid" => intval($company['id']),  
			"credited_accountid" => 7,  //some dummy value
			"created_by" => intval($company['primary_user_id']),
			"narration" => "Payment made towards (Booking id: $booking_id dated: ".date("Y-m-d H:i:s", strtotime($booking_date.'+00:00'))
		));	

		return $result;
	}
	
	protected function get_ticket($ticketid, $current_user, $company, $passedticket) {
		$CI =   &get_instance();

		if($ticketid>-1) {
			$ticket = $CI->db->get_where('tickets_tbl', array('id' => $ticketid));
			$result1=$ticket->result_array();
			if(isset($result1) && count($result1)>0) {
				$ticket = $result1[0];
			} else {
				$ticket = NULL;
	
				return $ticket;
			}
		}
		else {
			$ticket = $passedticket;
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

		log_message("debug", "book.get_ticket-ticket - ".json_encode($ticket));
		log_message("debug", "book.get_ticket-company - ".json_encode($company));
		log_message("debug", "book.get_ticket-current_user - ".json_encode($current_user));

		return $ticket;
	}

	protected function is_booking_allowed($totalcostprice, $current_user, $wallet) {
		$user = $this->User_Model->user_details($current_user['id']);
		$credit_limit = 999999999; /* This should be actual credit limit */
		$wallet_balance = floatval($wallet["balance"]);

		log_message("debug", "Ticket Cost: $totalcostprice | Wallet Balance: $wallet_balance | user id: ".$current_user['id']." | credit allowed: ".intval($current_user["credit_ac"])." | is_admin: ".intval($current_user['is_admin']));

		//$flag = $totalcostprice>floatval($wallet["balance"]) && intval($current_user['is_admin'])!=1 && intval($current_user["credit_ac"])==0;

		//We need to check credit limit
		$flag = (floatval($wallet["balance"])>=$totalcostprice) || intval($current_user['is_admin'])===1 || (intval($current_user["credit_ac"])===1 && $totalcostprice<=$credit_limit);

		return $flag;
	}

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

		log_message("debug", "book.is_booking_valid-customers - ".json_encode($customerlist));
		log_message("debug", "book.is_booking_valid-hasduplicatecustomers - ".$hasduplicatecustomers);

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
		$details = $this->Search_Model->booking_details($id);
		$result["options"] = array('pdf' => false);  
		$result["details"] = $details;

		$adult = 0;
		$child = 0;
		$infant = 0;
		foreach($details as $passenger) {
			if(intval($passenger['passenger_type']) === 1) { //Adult
				$adult++;
			}
			else if(intval($passenger['passenger_type']) === 2) { //Child
				$child++;
			}
			else if(intval($passenger['passenger_type']) === 3) { //Infant
				$infant++;
			}
		}


		//$result["setting"] = $this->Search_Model->setting($id); 
		$result['setting']=$this->Search_Model->company_setting($companyid);

		$result["footer"]=$this->Search_Model->get_post(5);

		$result['mywallet']= $this->getMyWallet();
		$result['state'] = array(
			"passengers" => array(
				"adult" => $adult,
				"child" => $child,
				"infant" => $infant
			)
		);

		if($result["details"])
		{		 
			log_message('debug', "Search::thankyou - Booking Summary Page: Booking Id: $id | page payload: ".json_encode($result));
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
		$company = $this->session->userdata('company');
		$current_user = $this->session->userdata('current_user');
		$companyid = intval($company['id']);
		$source = $this->input->post('source');
		$triptype = $this->input->post('trip_type');

		$circles = $this->Search_Model->get_inventory_circles($companyid, 365, $triptype);
		$sectors = $this->get_filtered_destination($circles, $source);
	
		//$response["success"]=$this->Search_Model->filter_city($this->input->post('source'),$this->input->post('trip_type'));        

		$response["success"] = $sectors;
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
		$details = $this->Search_Model->booking_details($id);
		
		$result["details"] = $details;
		$result['setting']=$this->Search_Model->company_setting($companyid);
		$result["footer"]=$this->Search_Model->get_post(5);

		$adult = 0;
		$child = 0;
		$infant = 0;
		foreach($details as $passenger) {
			if(intval($passenger['passenger_type']) === 1) { //Adult
				$adult++;
			}
			else if(intval($passenger['passenger_type']) === 2) { //Child
				$child++;
			}
			else if(intval($passenger['passenger_type']) === 3) { //Infant
				$infant++;
			}
		}

		$result['state'] = array(
			"passengers" => array(
				"adult" => $adult,
				"child" => $child,
				"infant" => $infant
			)
		);

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

			$this->pdf->load_view('myticket', $result);
			$this->pdf->render();
			$this->pdf->stream("$ticket_no.pdf", array("Attachment"=>1));
		}
		else
		{
			redirect("/search");
		}	
	}

	public function save_ticket_post() {
		$mode = $this->input->post('mode');
		$payload = $this->input->post('payload');
		$company = $this->get_companyinfo();
		$current_user = $company['current_user'];
		$companyid = intval($company['id']);
		$result = [];

		$ticket = $payload['source_ticket'];
		$dept_date_time = date('Y-m-d H:i:s', strtotime($ticket['departure_date'].' '.$ticket['departure_time'].':00'));
		$arrv_date_time = date('Y-m-d H:i:s', strtotime($ticket['arrival_date'].' '.$ticket['arrival_time'].':00'));

		if($mode==='update') {
			//Same ticket should be updated. Before doing it please check the logged-in user is the owner of the ticket or not
			$targetticket = $this->Search_Model->get('tickets_tbl', array('id' => intval($ticket['id'])));
			if($targetticket && count($targetticket)>0) {
				$targetticket = $targetticket[0];
			}
			log_message('debug', "Taking action ($mode) on Ticket - ".$ticket['id']." => ".json_encode($ticket)." | ".json_encode($targetticket));
			if(intval($targetticket['companyid']) === $companyid) {
				if($this->Search_Model->update('tickets_tbl', array(
					'flight_no' => $ticket['flight_no'], 
					'no_of_person' => $ticket['no_of_person'], 
					'max_no_of_person' => $ticket['no_of_person'], 
					'availibility' => $ticket['no_of_person'], 
					'available' => intval($ticket['no_of_person'])>0?'YES':'NO', 
					'price' => $ticket['price'], 
					'total' => $ticket['price'], 
					'departure_date_time' => $dept_date_time,
					'arrival_date_time' => $arrv_date_time,
					'tag' => $ticket['tag'],
					'updated_by' => $current_user['id'],
					'updated_on' => date("Y-m-d H:i:s")
				), array('id' => intval($ticket['id'])))) {
					$targetticket = $this->Search_Model->get('tickets_tbl', array('id' => intval($ticket['id'])));
					if($targetticket && count($targetticket)>0) {
						$targetticket = $targetticket[0];
					}
					$result['status'] ='Ticket successfully saved';
					$result['code'] =200;
					$result['data'] =$targetticket;
				}
			}
			else {
				$result['status'] ='You are not authorized to make any changes to this ticket';
				$result['code'] =401;
				$result['data'] =[];
			}

			echo json_encode($result, JSON_HEX_APOS);
		} else if($mode==='clone') {
			// //This ticket will be cloned and create a new ticket under current companyid. But if the same ticket is present 
			#region old clone code
			// $targetticket = $this->Search_Model->get('tickets_tbl', array('id' => intval($ticket['id'])));
			// if($targetticket && count($targetticket)>0) {
			// 	$targetticket = &$targetticket[0];
			// }
			// log_message('debug', "Taking action ($mode) on Ticket - ".$ticket['id']." => ".json_encode($ticket)." | ".json_encode($targetticket));
			// if(intval($targetticket['companyid']) !== $companyid) {
			// 	unset($targetticket['id']);
			// 	unset($targetticket['pnr']);
			// 	unset($targetticket['created_date']);
			// 	unset($targetticket['created_on']);
			// 	unset($targetticket['data_collected_from']);
			// 	unset($targetticket['last_sync_key']);
			// 	unset($targetticket['updated_by']);
			// 	unset($targetticket['updated_on']);

			// 	try
			// 	{
			// 		$targetticket['companyid'] = $companyid;
			// 		$targetticket['cloned_from'] = intval($ticket['id']);
			// 		$targetticket['flight_no'] = $ticket['flight_no'];
			// 		$targetticket['no_of_person'] = $ticket['no_of_person'];
			// 		$targetticket['max_no_of_person'] = $ticket['no_of_person'];
			// 		$targetticket['availibility'] = $ticket['no_of_person'];
			// 		$targetticket['available'] = intval($ticket['no_of_person'])>0?'YES':'NO';
			// 		$targetticket['created_by'] = $current_user['id'];
			// 		$targetticket['price'] = $ticket['price'];
			// 		$targetticket['total'] = $ticket['price'];
			// 		$targetticket['tag'] = $ticket['tag'];
			// 		$targetticket['departure_date_time'] = $dept_date_time;
			// 		$targetticket['arrival_date_time'] = $arrv_date_time;
			// 		$targetticket['booking_freeze_by'] = $dept_date_time;
			// 		$targetticket['user_id'] = $current_user['id'];
			// 		$targetticket['ticket_no'] = "CLONED-".$targetticket['ticket_no'];

			// 		$tktid = $this->Search_Model->save('tickets_tbl', $targetticket);

			// 		if($tktid>0) {
			// 			$targetticket = $this->Search_Model->get('tickets_tbl', array('id' => intval($tktid)));
			// 			if($targetticket && count($targetticket)>0) {
			// 				$targetticket = $targetticket[0];
			// 			}
			// 			$result['status'] ='Ticket successfully cloned';
			// 			$result['code'] =200;
			// 			$result['data'] =$targetticket;
	
			// 			log_message('debug', "Taking cloned - ".$tktid." => ".json_encode($targetticket));
			// 		}
			// 		else {
			// 			$result['status'] ='Ticket clonning failed. Check with admin.';
			// 			$result['code'] =501;
			// 			$result['data'] =[];
			// 		}
			// 	}
			// 	catch(Exception $ex) {
			// 		log_message('error', "Clone Error => $ex");
			// 	}
			// }
			// else {
			// 	$result['status'] ="This ticket belongs to you only, can't clone it. You can modify this ticket if needed.";
			// 	$result['code'] =501;
			// 	$result['data'] =[];
			// }
			#endregion

			$result = $this->Search_Model->clone_ticket(intval($ticket['id']), $companyid, array(
				'current_userid' => intval($current_user['id']),
				'flight_no' => $ticket['flight_no'],
				'no_of_person' => $ticket['no_of_person'],
				'price' => $ticket['price'],
				'tag' => $ticket['tag'],
				'departure_date_time' => $dept_date_time,
				'arrival_date_time' => $arrv_date_time,
				'booking_freeze_by' => $dept_date_time
			));
			echo json_encode($result, JSON_HEX_APOS);
		}
	}

	public function mydeals($id) {
		if($id) {
			$user = $this->get_user($id);
			$isauthenticated = $this->session->userdata('user_id') !== null;

			if($user) {
				$circle = ($this->input->post('circle') !== null) ? $this->input->post('circle') : '-1';
	
				$companyid = intval($user['companyid']);
				$company = $this->get_company($companyid);
				$circles = $this->Search_Model->get_inventory_circles($companyid);

				$result = array();
				$result['company'] = $this->get_company($companyid);
				$result['circles'] = $circles;
				$result['circle'] = $circle;
				$result['setting']=$this->Search_Model->company_setting($companyid);
				$result["currentuser"]=$user;
				$result["footer"]=$this->Search_Model->get_post(5);
				$result["isauthenticated"]=$isauthenticated;
				$result["ticket_calender"]=$this->Search_Model->get_inventory_calender($companyid, 7);
				if($isauthenticated) {
					$result['mywallet']= $this->getMyWallet();
				}
				
				$selected_circle = '';
				if($circle !== '-1') {
					$source = intval(explode('~^~', $circle)[0]);
					$destination = intval(explode('~^~', $circle)[1]);

					foreach ($circles as $circleitem) {
						$circlekey = ($circleitem["source_id"].'~^~'.$circleitem["destination_id"]);
						
						if($circle === $circlekey) {
							$selected_circle = $circleitem;
							break;
						}
					}
					if($selected_circle && is_array($selected_circle) && count($selected_circle)>0) {
						$selected_circle = $selected_circle['source_city']." To ".$selected_circle['destination_city'];
					}
					else {
						$selected_circle = '';
					}

					$result["tickets"] = $this->Search_Model->get_my_inventory($companyid, $source, $destination);
				}
				else {
					$result["tickets"] = false;
				}
				$result["selected_circle"] = $selected_circle;

				$this->load->view('header1', $result);
				$this->load->view('mydeals', $result);
				$this->load->view('footer1', $result);
			}
			else {
				redirect("/login");	
			}
		}
		else {
			redirect("/login");
		}
	}

	#region Helper methods
	public function get_user($uid) {
		if($uid) {
			$user = $this->Search_Model->get('user_tbl', array('uid' => "'$uid'", 'active' => 1, 'is_admin' => 1));
			if($user && is_array($user) && count($user)>0) {
				$user = $user[0];
			}
		}
		else {
			$user = null;
		}

		return $user;
	}
	
	public function get_company($companyid=-1) {
		$company = [];
		if($companyid>0) {
			$company = $this->Search_Model->get('company_tbl', array('id' => $companyid, 'active' => 1));
			if($company && is_array($company) && count($company)>0) {
				$company = $company[0];
			}
		}

		return $company;
	}

	public function get_filtered_sources($circles) {
		$sources = [];
		if($circles) {
			foreach($circles as $circle) {
				$filtered_sector = array('sector' => trim($circle['source_city']), 'id' => intval($circle['source_id']), 'code' => trim($circle['source_code']));
				if(!in_array($filtered_sector , $sources)) {
					$sources[] = $filtered_sector;
				}
			}
		}

		return $sources;
	}

	public function get_filtered_destination($circles, $source) {
		$sectors = [];
		if($circles) {
			foreach($circles as $circle) {
				$filtered_sector = array('sector' => $circle['destination_city'], 'id' => intval($circle['destination_id']));
				if(!in_array($filtered_sector , $sectors) && intval($circle['source_id']) === intval($source)) {
					$sectors[] = $filtered_sector;
				}
			}
		}

		return $sectors;
	}

	public function show_price($userid) {
		return true;
	}

	#endregion

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
