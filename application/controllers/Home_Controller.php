<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_Controller extends CI_Controller 
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
	   
		
	}
	
	public function index()
	{
	   	$result["setting"]=$this->Search_Model->setting();
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
        
        
		
        
		$this->load->view('header',$result);
		$this->load->view('home');
		$this->load->view('footer');
	}
	public function terms()
	{
	   	$result["setting"]=$this->Search_Model->setting();
	   	$result["term"]=$this->Search_Model->terms();
	   	$result["footer"]=$this->Search_Model->get_post(5);
	   	$result["need_help"]=$this->Search_Model->get_post(6);
		$this->load->view('header',$result);
		$this->load->view('term');
		$this->load->view('footer');
	}
	public function faq()
	{
	   	$result["setting"]=$this->Search_Model->setting();
	   	$result["faq"]=$this->Search_Model->faq();
	   	$result["footer"]=$this->Search_Model->get_post(5);
	   	$result["need_help"]=$this->Search_Model->get_post(6);
		$this->load->view('header',$result);
		$this->load->view('faq');
		$this->load->view('footer');
	}
	public function contact()
	{
	   	$result["setting"]=$this->Search_Model->setting();
	   	$result["footer"]=$this->Search_Model->get_post(5);
		$this->load->view('header',$result);
		$this->load->view('contact');
		$this->load->view('footer');
	}
	public function error()
	{
		
		$this->load->view('error');
		
	}
}