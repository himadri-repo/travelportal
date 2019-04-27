<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Admin_Controller extends CI_Controller 
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

    //Admin controller landing page
    public function index() {
        if($this->session->userdata('user_id') && $this->session->userdata('current_user')) {
            $currentuser = $this->session->userdata('current_user');
            if($currentuser["is_admin"]) {
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
    
                $result["setting"]=$this->Search_Model->setting();
                $result["modules"]=$this->Admin_Model->get_modules($currentuser["permission"]);
    
                //$this->load->view('header1', $result);
                $this->load->view('adminheader', $result);
                $this->load->view('adminsidebar', $result);
                $this->load->view('admin', $result);
                //$this->load->view('footer1');
                $this->load->view('adminfooter');
            }
            else {
                redirect('/login');
            }
        }
        else {
            redirect('/login');
        }
    }
}
?>