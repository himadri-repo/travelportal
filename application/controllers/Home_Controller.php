<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(APPPATH.'core/Mail_Controller.php');
include_once(APPPATH.'core/Common.php');
define('PAGE_SIZE', 25);

class Home_Controller extends Mail_Controller 
{
    public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Calcutta'); 		
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('Search_Model');
		$this->load->model('User_Model');
		$this->load->model('Admin_Model');
	}
	
	public function index()
	{
		$companies = $this->Admin_Model->get_companies();
		$company = null;
		$siteUrl = siteURL();

		for($idx=0; $idx<count($companies); $idx++) {
			if(strtolower($companies[$idx]["baseurl"]) === strtolower($siteUrl)) {
				$company = &$companies[$idx];
				break;
			}
		}

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
			
			if(NEW_FLOW && $companyid!=NULL)
			{
				$result['company_setting']=$this->Search_Model->company_setting($companyid);
			}
		}
		else {
			$companyid = NULL;
			$cname = NULL;
			$result['cname']='';
			$result['company_setting'] = array();
		}

		//$result["setting"]=$this->Search_Model->setting();
		$result["setting"]=$this->Search_Model->company_setting($company["id"]);
	   	$result["slider"]=$this->User_Model->select("slider_tbl");
	   	
	   	$today=date("Y-m-d H:i:s");
	   	$arr=array(
				 "t.departure_date_time >"=>$today,
				 //"DATE_FORMAT(t.departure_date_time, '%Y-%m-%d')<"=>date('Y-m-d', strtotime(date("Y-m-d").' +1 day')),
				 "t.approved"=>"1",
				 "t.no_of_person>="=>"1",
				 "t.availibility>="=>1
				 );
	   	$result["best_offer"]=$this->Search_Model->best_offer($arr);
	   	$result["number"]=$this->Search_Model->best_offer_num($arr);
	   	//echo $this->db->last_query();die();
	   	$result["testimonial"]=$this->Search_Model->testimonial();
		$result["first"]=$this->Search_Model->get_post(1);
		$result["second"]=$this->Search_Model->get_post(2);
		$result["third"]=$this->Search_Model->get_post(3);
		$result["fourth"]=$this->Search_Model->get_post(4);
		$result["footer"]=$this->Search_Model->get_post(5);
		
		$current_user = $this->session->userdata("current_user");

		$result['mywallet']= $this->getMyWallet();

		//$this->load->view('header',$result);
		$this->load->view('headernew',$result);
		//$this->load->view('home');
		if($company!==null) {
			if(isset($result["setting"]) && count($result["setting"])>0) {
				$company['setting'] = $result["setting"][0];
			}
			$this->session->set_userdata('company', $company);
			$this->load->view('homenew');
		}
		$this->load->view('footer');
	}
	public function terms()
	{
		$company = $this->get_current_company();
		$companyid = intval($company["id"]);
		$result["setting"]=$this->Search_Model->company_setting($companyid);

	   	//$result["setting"]=$this->Search_Model->setting();
	   	$result["term"]=$this->Search_Model->terms($companyid, 'GENERAL');
	   	$result["footer"]=$this->Search_Model->get_post(5);
	   	$result["need_help"]=$this->Search_Model->get_post(6);
		$this->load->view('header',$result);
		$this->load->view('term');
		$this->load->view('footer');
	}
	public function serveRandomImages() {
		$arr = array("images/home_sea_bg_1.jpg", "images/home_sea_bg_01_1.jpg", "images/cover-registration.jpg", "images/flight-slider-1.jpg", "images/newsletter.jpg", "images/video-banner.jpg", "images/cover-holiday.jpg");
		$idx = rand(0, count($arr)-1);
		$filename=FCPATH.$arr[$idx]; //<-- specify the image  file
		if(file_exists($filename)){ 
		  $mime = mime_content_type($filename); //<-- detect file type
		  header('Content-Length: '.filesize($filename)); //<-- sends filesize header
		  header("Content-Type: $mime"); //<-- send mime-type header
		  header('Content-Disposition: inline; filename="'.$filename.'";'); //<-- sends filename header
		  readfile($filename); //<--reads and outputs the file onto the output buffer
		  die(); //<--cleanup
		  exit; //and exit
		}
	}
	public function faq()
	{
	   	//$result["setting"]=$this->Search_Model->setting();
		$company = $this->get_current_company();
		$companyid = intval($company["id"]);
		$result["setting"]=$this->Search_Model->company_setting($companyid);
	   	$result["faq"]=$this->Search_Model->faq();
	   	$result["footer"]=$this->Search_Model->get_post(5);
	   	$result["need_help"]=$this->Search_Model->get_post(6);
		$this->load->view('header',$result);
		$this->load->view('faq');
		$this->load->view('footer');
	}
	public function contact()
	{
		//$result["setting"]=$this->Search_Model->setting();
		$company = $this->get_current_company();
		$companyid = intval($company["id"]);
		$result["setting"]=$this->Search_Model->company_setting($companyid);
	   	$result["footer"]=$this->Search_Model->get_post(5);
		$this->load->view('header',$result);
		$this->load->view('contact');
		$this->load->view('footer');
	}
	public function error()
	{
		
		$this->load->view('error');
		
	}

	public function get_current_company() {
		$companies = $this->Admin_Model->get_companies();
		$company = null;
		$siteUrl = siteURL();

		for($idx=0; $idx<count($companies); $idx++) {
			if(strtolower($companies[$idx]["baseurl"]) === strtolower($siteUrl)) {
				$company = &$companies[$idx];
				break;
			}
		}

		return $company;
	}
}
