<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'core/Mail_Controller.php');
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
			if($_SERVER['REQUEST_METHOD'] == 'POST') 
			{			  
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
				$rateplans = $this->Admin_Model->rateplanByCompanyid($companyid, array('rp.default='=>'1'));
				$defaultRP = NULL;
				$defaultRPD = NULL;
				if(count($rateplans)>0) {
					$defaultRP = $rateplans[0];
					$defaultRPD = $this->Admin_Model->rateplandetails($defaultRP['id']);
				}
				$rateplan_details = $this->Admin_Model->rateplandetails(-1);

				$tickets = $this->Search_Model->search_one_wayV2($arr);

				//$default_rp = $this->Admin_Model->rateplanByCompanyid($company["id"], array('rp.default' => 1));
				// $default_rp_detail = [];
				// if(count($default_rp)>0) {
				// 	$default_rp_detail = $this->Admin_Model->rateplandetails($default_rp[0]["id"]);
				// }

				for ($i=0; $tickets && $i < count($tickets); $i++) { 
					$ticket = &$tickets[$i];

					$ticketupdated = $this->calculationTicketValue($ticket, $defaultRPD, $rateplan_details, $companyid);
	
					$total = $ticket['total'];
					// $ticket["wsl_markup"] = 0;
					// $ticket["wsl_srvchg"] = 0;
					// $ticket["splr_markup"] = 0;
					// $ticket["splr_srvchg"] = 0;
					// $ticket["cgst"] = 0;
					// $ticket["sgst"] = 0;
					// $ticket["igst"] = 0;
					$ticket["new"] = 1;

					#region Comments
					// if($company["id"]==$ticket["companyid"] && count($default_rp_detail)>0) {
					// 	// This is own ticket to rate should be picked from default rate plan

					// 	$rateplandetail = $this->get_rateplan_detail_by_head('markup', $default_rp_detail);
					// 	if($rateplandetail!=NULL) {
					// 		if($rateplandetail["amount_type"]==1) {
					// 			//Fixed amount
					// 			$wsl_markup = $rateplandetail["amount"];
					// 		}
					// 		else {
					// 			$wsl_markup = round(($total * ($rateplandetail["amount"]/100)),0);
					// 		}

					// 		$ticket["wsl_markup"] = $wsl_markup;
					// 	}

					// 	$rateplandetail = $this->get_rateplan_detail_by_head('srvchg', $default_rp_detail);
					// 	if($rateplandetail!=NULL) {
					// 		if($rateplandetail["amount_type"]==1) {
					// 			//Fixed amount
					// 			$wsl_srvchg = $rateplandetail["amount"];
					// 		}
					// 		else {
					// 			$wsl_srvchg = round(($total * ($rateplandetail["amount"]/100)),0);
					// 		}
					// 		$ticket["wsl_srvchg"] = $wsl_srvchg;
					// 	}

					// 	$rateplandetail = $this->get_rateplan_detail_by_head('cgst', $default_rp_detail);
					// 	if($rateplandetail!=NULL) {
					// 		$cgst = round(($ticket["wsl_srvchg"] * ($rateplandetail["amount"]/100)),0);
					// 		$ticket["cgst"] = $cgst;
					// 	}
					// 	$rateplandetail = $this->get_rateplan_detail_by_head('sgst', $default_rp_detail);
					// 	if($rateplandetail!=NULL) {
					// 		$sgst = round(($ticket["wsl_srvchg"] * ($rateplandetail["amount"]/100)),0);
					// 		$ticket["sgst"] = $sgst;
					// 	}
					// 	$rateplandetail = $this->get_rateplan_detail_by_head('igst', $default_rp_detail);
					// 	if($rateplandetail!=NULL) {
					// 		$igst = round(($ticket["wsl_srvchg"] * ($rateplandetail["amount"]/100)),0);
					// 		$ticket["igst"] = $igst;
					// 	}
					// }
					// else {
					// 	//This is supplier's ticket to both rate should be applied as supplier and wholesaler
					// 	//now for supplier's commision
					// 	if($ticket["markup_rate"]<=0) {
					// 		$splr_markup = round(($total * $ticket["markup_rate"]),0);
					// 	}
					// 	else {
					// 		$splr_markup = round(($ticket["markup_rate"]),0);
					// 	}
					// 	if($ticket["srvchg_rate"]<=0) {
					// 		$splr_srvchg = round(($total * $ticket["srvchg_rate"]),0);
					// 	}
					// 	else {
					// 		$splr_srvchg = round(($ticket["srvchg_rate"]),0);
					// 	}
					// 	$ticket["splr_markup"] = $splr_markup;
					// 	$ticket["splr_srvchg"] = $splr_srvchg;

					// 	//now for wholesaler's commision
					// 	if($ticket["admin_markup"]<=0) {
					// 		$wsl_markup = round(($total * $ticket["admin_markup"]),0);
					// 	}
					// 	else {
					// 		$wsl_markup = round(($ticket["admin_markup"]),0);
					// 	}
					// 	if($ticket["admin_crvchg"]<=0) {
					// 		$wsl_srvchg = round(($total * $ticket["admin_crvchg"]),0);
					// 	}
					// 	else {
					// 		$wsl_srvchg = round(($ticket["admin_crvchg"]),0);
					// 	}
					// 	$ticket["wsl_markup"] = $wsl_markup;
					// 	$ticket["wsl_srvchg"] = $wsl_srvchg;
					// 	$cgst = round((($wsl_srvchg+$splr_srvchg) * $ticket["cgst_rate"]/100),0);
					// 	$sgst = round((($wsl_srvchg+$splr_srvchg) * $ticket["sgst_rate"]/100),0);
					// 	$igst = round((($wsl_srvchg+$splr_srvchg) * $ticket["igst_rate"]/100),0);
					// 	$ticket["cgst"] = $cgst;
					// 	$ticket["sgst"] = $sgst;
					// 	$ticket["igst"] = $igst;
					// }
					#endregion
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
				$result["setting"]=$this->Search_Model->setting();
				$result["footer"]=$this->Search_Model->get_post(5);
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
			if(count($rateplans)>0) {
				$defaultRP = $rateplans[0];
				$defaultRPD = $this->Admin_Model->rateplandetails($defaultRP['id']);
			}
	
			$rateplan_details = $this->Admin_Model->rateplandetails(-1);
	
			$tickets = $this->Search_Model->flight_details($id, $companyid);
			
			if($tickets && count($tickets)>0) {
				$ticket = $tickets[0];

				$ticketupdated = $this->calculationTicketValue($ticket, $defaultRPD, $rateplan_details, $companyid);

				$result["flight"]=array($ticket); 
				$result["currentuser"]=$current_user;
				$result["setting"]=$this->Search_Model->setting();
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
					
					$result["setting"]=$this->Search_Model->setting();
					$result["footer"]=$this->Search_Model->get_post(5);
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

	private function calculationTicketValue(&$ticket, $defaultRPD, $rateplan_details, $companyid) {
		$rateplanid = $ticket['rate_plan_id'];
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
			
			if($defaultRPD !== NULL) {
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
			if(count($rateplans)>0) {
				$defaultRP = $rateplans[0];
				$defaultRPD = $this->Admin_Model->rateplandetails($defaultRP['id']);
			}
	
			$rateplan_details = $this->Admin_Model->rateplandetails(-1);

			$tickets = $this->Search_Model->flight_details($id, $companyid);
			$result["flight"]=$tickets;
			if($result["flight"][0]["no_of_person"]>0 && $result["flight"][0]["approved"]==1)
			{
				$ticket = $tickets[0];

				$ticketupdated = $this->calculationTicketValue($ticket, $defaultRPD, $rateplan_details, $companyid);

				$service_charge = floatval($this->input->post('service_charge'));
				$result1["user"]=$this->User_Model->user_details();
				$result["currentuser"] = $current_user;
				$result["setting"]=$this->Search_Model->setting(); 	

				$baserate = ($ticket["total"]) + $ticket["whl_markup"] + $ticket["spl_markup"];
				$srvchg = $ticket["whl_srvchg"] + $ticket["spl_srvchg"];
				$gst = $ticket["whl_cgst"] + $ticket["spl_cgst"] + $ticket["whl_sgst"] + $ticket["spl_sgst"];
				$total = $baserate + $srvchg + $gst;
				$costprice = 0;
				if($current_user["is_admin"]==='1' || $current_user["type"]==='EMP')
				{
					//this is company admin or employee
					//$flight[$key]["total"] + $flight[$key]["whl_markup"] + $flight[$key]["whl_srvchg"] + ($flight[$key]['whl_srvchg'] * $flight[$key]['whl_cgst'] / 100) + ($flight[$key]['whl_srvchg'] * $flight[$key]['whl_sgst'] / 100);
					$costprice = $ticket["total"] + $ticket["whl_markup"] + $ticket["whl_srvchg"] + ($ticket["whl_srvchg"] * $ticket["whl_cgst"]/100) + ($ticket["whl_srvchg"] * $ticket["whl_sgst"]/100);
				}
				else if($current_user["type"]==='B2B') {
					$costprice = $ticket["price"];
				}

				// if($result["flight"][0]["user_id"]==$this->session->userdata('user_id'))
				// 	$result["flight"][0]["price"]=floatval( $result["flight"][0]["price"]-$result["flight"][0]["discount"]+ $result["flight"][0]["markup"] + $this->input->post('markup'));	
				// else
				// 	$result["flight"][0]["price"]=floatval( $result["flight"][0]["price"]-$result["flight"][0]["discount"]+ $result["flight"][0]["markup"] + $result["flight"][0]["admin_markup"]+$this->input->post('markup'));	 

				// if($result["flight"][0]["user_id"]==$this->session->userdata('user_id'))
				// 	$result["flight"][0]["price"]=floatval( $result["flight"][0]["price"]-$result["flight"][0]["discount"]+ $result["flight"][0]["markup"] + $service_charge);	
				// else
				// 	$result["flight"][0]["price"]=floatval( $result["flight"][0]["price"]-$result["flight"][0]["discount"]+ $result["flight"][0]["markup"] + $result["flight"][0]["admin_markup"]+$service_charge);
				//$result["flight"][0]["price"]= $baserate + $gst;
				$result["flight"][0]["price"]= $baserate;
				$result["flight"][0]["service_charge"]= $srvchg;
				$result["flight"][0]["gst"]= $gst;
				$result["flight"][0]["costprice"]= $costprice;

					
				//$result["flight"][0]["markup"]=$this->input->post('markup');
				// $result["flight"][0]["markup"]=$service_charge;
				// $result["flight"][0]["total"]=$this->input->post('total');
				$result["flight"][0]["markup"]=$srvchg;
				$result["flight"][0]["total"]=$total;
				$result["flight"][0]["qty"]=$this->input->post('qty');
				$result["flight"][0]["wallet_balance"]=$this->get_wallet_balance($this->session->userdata('user_id'));
				$result["flight"][0]["id"]=$id;	
				$result["flight"][0]["user_email"]=$result1["user"][0]["email"];
				$result["flight"][0]["mobile_no"]=$result1["user"][0]["mobile"];			
				$result["flight"][0]["first_name"]=$result1["user"][0]["name"];
				$result["flight"][0]["user"]=$result1["user"][0];
			
									
				if($result["flight"][0]["no_of_person"]>0 && $result["flight"][0]["no_of_person"]>=$this->input->post('qty'))
				{					  			  			  
					//$result["setting"]=$this->Search_Model->setting();
					$result["footer"]=$this->Search_Model->get_post(5);
					$this->load->view('header1',$result);;
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
		$company = $this->session->userdata('company');
		$companyid = $company["id"];

		$result["footer"]=$this->Search_Model->get_post(5);
		$wallet_amount=0;
		if ($this->input->post('date') && $this->input->post('qty')) 
		{
			$CI =   &get_instance();
			$check = $CI->db->get_where('wallet_tbl', array('user_id' => $this->session->userdata('user_id')));				
			$result=$check->result_array();
			
			$ticket = $CI->db->get_where('tickets_tbl', array('id' => $id));				
			$result1=$ticket->result_array();
			$no_of_person=$result1[0]["no_of_person"];								 
			$user_id=$result1[0]["user_id"];
			$pnr=$result1[0]["pnr"];
			$amount=$this->input->post('total');
			$costprice=$this->input->post('costprice');
			$user['user_details']=$this->User_Model->user_details();
			
			
			foreach($result as $key=>$value)
			{
				$wallet_amount+=$result[$key]["amount"];
			}				

			//if($amount>$wallet_amount && $this->session->userdata('user_id')!=$user_id && $user['user_details'][0]["credit_ac"]==0)
			if($costprice>$wallet_amount && $this->session->userdata('user_id')!=$user_id && $user['user_details'][0]["credit_ac"]==0)
			{
				$result["setting"]=$this->Search_Model->setting();
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

					
					$arr=array(
					"date"=>date("Y-m-d h:i:s"),
					"ticket_id"=>$id,
					"seller_id"=>$result1[0]["user_id"],
					"pnr"=>$result1[0]["pnr"],
					"customer_id"=>$this->session->userdata('user_id'),
					"qty"=>$this->input->post('qty'),
					"available_qty"=>$this->input->post('qty'),
					"rate"=>$this->input->post('price'),
					"amount"=>$this->input->post('price')*$this->input->post('qty'),
					"service_charge"=>$this->input->post('service_charge'),
					"igst"=>$this->input->post('igst'),
					"total"=>$amount,
					"costprice"=>$costprice,
					"type"=>$user['user_details'][0]["type"],
					"status"=>$status
					);
						
						$booking_id = $this->Search_Model->save("booking_tbl",$arr);
						$result["flight"]=$this->Search_Model->flight_details($id, $companyid); 
						if($result["flight"][0]["trip_type"]=="ONE")
							$trip="ONE WAY";
						else
							$trip="RETURN";
						if($booking_id)	
						{
							if($result["flight"][0]["sale_type"]=="live")
							{
								// FOR LIVE BOOKING
								$arr=array(
								"date"=>date("Y-m-d h:i:s"),
								"ticket_id"=>$id,
								"seller_id"=>$result1[0]["user_id"],
								"pnr"=>$result1[0]["pnr"],
								"customer_id"=>$this->session->userdata('user_id'),
								"qty"=>$this->input->post('qty'),
								
								"rate"=>$this->input->post('price'),
								"amount"=>$this->input->post('price')*$this->input->post('qty'),
								"service_charge"=>$this->input->post('service_charge'),						
								"igst"=>$this->input->post('igst'),
								"total"=>$amount,
								"costprice"=>$costprice,
								"type"=>$user['user_details'][0]["type"],
								"status"=>$status,
								"booking_id"=>$booking_id
								);
								$refrence_id=$this->Search_Model->save("refrence_booking_tbl",$arr);
								// FOR LIVE BOOKING
							}
							
							if($this->session->userdata('user_id')==$user_id)
							{
								$amount=$this->input->post('total');
								$arr=array(
								'date'=>date("Y-m-d h:i:s"),
								'user_id'=>$this->session->userdata('user_id'),
								//'amount'=>(0-($result["flight"][0]["price"]*$this->input->post('qty'))),
								'amount'=>(0-$costprice),
								'booking_id'=>$booking_id,
								'type'=>'DR'
								);					 
								$this->Search_Model->save("wallet_tbl",$arr);
								
								
								$amount=$this->input->post('total');
								$arr=array(
								'date'=>date("Y-m-d h:i:s"),
								'user_id'=>$result1[0]["user_id"],
								'amount'=>($amount),
								'booking_id'=>$booking_id,
								'type'=>'CR'
								);	
								
								$this->Search_Model->save("wallet_tbl",$arr);
								$where=array("id"=>$id);
								$current_no_of_person=$no_of_person-$this->input->post('qty');
								$data=array("no_of_person"=>$current_no_of_person);
								$this->Search_Model->update("tickets_tbl",$data,$where);
								
								
								$user['user_details']=$this->User_Model->user_details();	          							
								$data = array(				            
									'name' => "OXYTRA",
									'email'=>$user['user_details'][0]["email"],
									'msg'=>"You have Booked  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person. (Booking No. : ".$booking_id.")",
									'msg1'=>' You booking is confirm',
									'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
									);
								$this->send("Booking",$data);
						
						
								$data1 = array(				            
									'name' => $user['user_details'][0]["name"],
									'email'=>$user['user_details'][0]["email"],
									'mobile'=>$user['user_details'][0]["mobile"],
									'msg'=>"A New Booking done by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person (Booking No. : ".$booking_id.")",
									'user_id'=> $user['user_details'][0]["user_id"],
									'msg1'=>'',
									'msg2'=>""							 
									);									 
								$this->adminsend("Booking",$data1);
								$no=$user['user_details'][0]["mobile"];
								$msg="You have Booked a trip Booking No. ".$booking_id." Thanks, OXYTRA";
								$this->send_message($no,$msg);
								
							
								$no="9800412356";
								$msg="A New Booking done by User ID : ".$user['user_details'][0]["user_id"]." Booking No. ".$booking_id."";
								$this->send_message($no,$msg);
							}							 
							else
							{		
								$amount=($this->input->post('total'));
								$arr=array(
								'date'=>date("Y-m-d h:i:s"),
								'user_id'=>$this->session->userdata('user_id'),
								//'amount'=>(0-$amount),
								'amount'=>(0-$costprice),
								'booking_id'=>$booking_id,
								'type'=>'DR'
								);					 
								$this->Search_Model->save("wallet_tbl",$arr);
								
								
								
								$user['user_details']=$this->User_Model->user_details();	          							
								$data = array(				            
									'name' => "OXYTRA",
									'email'=>$user['user_details'][0]["email"],
									'msg'=>"You have requested for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person. (Booking No. : ".$booking_id.")",
									'msg1'=>'After Admin Approval of <span class="il">OXYTRA</span> You booking will be confirm',
									'msg2'=>"Enjoy! You will be connected to no.1 Air Ticket booking site"
									);
								$this->send("Booking",$data);
						
						
								$data1 = array(				            
									'name' => $user['user_details'][0]["name"],
									'email'=>$user['user_details'][0]["email"],
									'mobile'=>$user['user_details'][0]["mobile"],
									'msg'=>"A New Booking Request by ".$user['user_details'][0]["user_id"]." for  (".$trip.") ticket from  ".$result["flight"][0]["source_city"]." to ".$result["flight"][0]["destination_city"]." of ".$amount." INR for ".$this->input->post('qty')." person (Booking No. : ".$booking_id.")",
									'user_id'=> $user['user_details'][0]["user_id"],
									'msg1'=>'',
									'msg2'=>""							 
									);									 
								$this->adminsend("Booking",$data1);

								$no=$user['user_details'][0]["mobile"];
								$msg="You have Requested for a trip Request No. ".$booking_id." Thanks, OXYTRA";
								$this->send_message($no,$msg);
								
							
								$no="9800412356";
								$msg="A New Booking Requested done by User ID : ".$user['user_details'][0]["user_id"]." Request No. ".$booking_id."";
								
							
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
												"booking_id"=>$booking_id,
												"refrence_id"=>$refrence_id,
												"ticket_fare"=>($amount),
												"costprice"=>($costprice),
												"pnr"=>$result1[0]["pnr"]
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
												"mobile_no"=>$_REQUEST["mobile_no"][$key],
												"age"=>$_REQUEST["age"][$key],
												"ticket_fare"=>($amount),
												"costprice"=>($costprice),
												"email"=>$_REQUEST["email"][$key],
												"booking_id"=>$booking_id,
												"pnr"=>$result1[0]["pnr"]
												);
									$this->Search_Model->save("customer_information_tbl",$arr);
								}
							}
							
							redirect("/search/thankyou/".$booking_id."");
						}
						else
							echo $this->db->last_query();die();
			}					
				
		}
	}

	private function get_wallet_balance($userid) {
		$CI =   &get_instance();
		$check = $CI->db->get_where('wallet_tbl', array('user_id' => $userid));
		$result=$check->result_array();
		$wallet_amount = 0;
		
		foreach($result as $key=>$value)
		{
			$wallet_amount+=$result[$key]["amount"];
		}

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
    		
    		$result["setting"]=$this->Search_Model->setting();
    		
    		
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
		  $result["details"] = $this->Search_Model->booking_details($id);  
		  $result["setting"] = $this->Search_Model->setting($id); 
		  	$result["footer"]=$this->Search_Model->get_post(5);
          if($result["details"])
		  {		 
	             if($result['details'][0]["type"]=="B2B")
			     {			  
					  $result["setting"]=$this->Search_Model->setting();
					  $this->load->view('header1',$result);
					  $this->load->view('thank-youb2b',$result);
					  $this->load->view('footer1');
				 }
				 if($result['details'][0]["type"]=="B2C")
			     {			  
					  $result["setting"]=$this->Search_Model->setting();
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
		  $result["details"] = $this->Search_Model->supplier_booking_details($id);  
		  $result["setting"] = $this->Search_Model->setting($id); 
		 	$result["footer"]=$this->Search_Model->get_post(5);
          if($result["details"])
		  {		 
	             if($result['details'][0]["type"]=="B2B")
			     {			  
					  $result["setting"]=$this->Search_Model->setting();
					  $this->load->view('header1',$result);
					  $this->load->view('thank-you',$result);
					  $this->load->view('footer1');
				 }
				 if($result['details'][0]["type"]=="B2C")
			     {			  
					  $result["setting"]=$this->Search_Model->setting();
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
			$defaultRPD = $this->Admin_Model->rateplandetails($defaultRP['id']);
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
				
				if($defaultRPD !== NULL) {
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
	public function pdf($id)
	{
		
		
	      $result["details"] = $this->Search_Model->booking_details($id);  
		  $result["setting"] = $this->Search_Model->setting($id); 
		  	$result["footer"]=$this->Search_Model->get_post(5);
          if($result["details"])
		  {		 
	             if($result['details'][0]["type"]=="B2B")
			     {			  
					  $result["setting"]=$this->Search_Model->setting();
					  $this->load->view('header1',$result);
					  $this->load->view('thank-you',$result);
					  $this->load->view('footer1');
					  
					$html = $this->output->get_output();
            		$this->load->library('pdf');
            		$this->dompdf->loadHtml($html);
            		$this->dompdf->setPaper('A4', 'portrait');
            		
            		$this->dompdf->render();
            		$this->dompdf->stream("ticket.pdf", array("Attachment"=>1));
				 }
				 if($result['details'][0]["type"]=="B2C")
			     {			  
					  $result["setting"]=$this->Search_Model->setting();
					  $this->load->view('header1',$result);
					  $this->load->view('thank-you1',$result);
					  $this->load->view('footer1');
					  
					$html = $this->output->get_output();
            		$this->load->library('pdf');
            		$this->dompdf->loadHtml($html);
            		$this->dompdf->setPaper('A4', 'portrait');
            		$this->dompdf->render();
            		$this->dompdf->stream("ticket.pdf", array("Attachment"=>1));
				 }
			  
		  }	  		 	      
		  else
		  {
			   redirect("/search");
		  }	
	}
}
