<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class entity {
    public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Calcutta'); 		
		$this->load->library('session');
		// $this->load->helper('url');
		// $this->load->helper('security');
		// $this->load->helper('form');
		// $this->load->library('form_validation');
		$this->load->database();
		// $this->load->library('upload');
		// $this->load->model('User_Model');
		// $this->load->model('Search_Model');
		// $this->load->model('Admin_Model');		
    }
    
}
?>