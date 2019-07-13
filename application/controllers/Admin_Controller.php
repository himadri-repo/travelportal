<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

defined('ADMIN_USERS') OR define('ADMIN_USERS', 1);
defined('ADMIN_SERVICES') OR define('ADMIN_SERVICES', 4);
defined('ADMIN_TICKETS') OR define('ADMIN_TICKETS', 8);
defined('ADMIN_SUPPLIERS') OR define('ADMIN_SUPPLIERS', 16);
defined('ADMIN_WHOLESALERS') OR define('ADMIN_WHOLESALERS', 64);
defined('ADMIN_CUSTOMER') OR define('ADMIN_CUSTOMER', 4096);

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
                $result["active_module"]='admin';

                //$this->load->view('header1', $result);
                //$this->load->view('adminheader', $result);
                //$this->load->view('adminsidebar', $result);
                //$this->load->view('../admin/index', $result);
                $this->load->ext_view("admin/", "index", $result);
                //$this->load->view('footer1');
                //$this->load->view('adminfooter');
            }
            else {
                redirect('/login');
            }
        }
        else {
            redirect('/login');
        }
    }    

    public function rawfile($file) {
		$filename="FCPATH".$file; //<-- specify the image  file
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

    //Admin controller landing page
    public function index1() {
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
                $result["active_module"]='admin';

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

    public function users() {
        //check is user having access rights
        if($this->session->userdata('user_id') && $this->session->userdata('current_user')) {
            $currentuser = $this->session->userdata('current_user');
            //if($currentuser["is_admin"]) {
            if(ADMIN_USERS & $currentuser["permission"]) {
                $companyid = $this->session->userdata("current_user")["companyid"];
                $cname = $this->session->userdata("current_user")["cname"];
                $result['cname']=$cname;
                
                $result["footer"]=$this->Search_Model->get_post(5);
                
                $result['company_setting']=$this->Search_Model->company_setting($companyid);
    
                $result["setting"]=$this->Search_Model->setting();
                $result["modules"]=$this->Admin_Model->get_modules($currentuser["permission"]);
                $result["active_module"]='admin/users';
    
                //$this->load->view('header1', $result);
                $this->load->view('adminheader', $result);
                $this->load->view('adminsidebar', $result);
                $this->load->view('admin_users', $result);
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

    public function suppliers() {
        //check is user having access rights
        if($this->session->userdata('user_id') && $this->session->userdata('current_user')) {
            $currentuser = $this->session->userdata('current_user');
            //if($currentuser["is_admin"]) {
            if(ADMIN_USERS & $currentuser["permission"]) {
                $companyid = $this->session->userdata("current_user")["companyid"];
                $cname = $this->session->userdata("current_user")["cname"];
                $result['cname']=$cname;
                
                $result["footer"]=$this->Search_Model->get_post(5);
                
                $result['company_setting']=$this->Search_Model->company_setting($companyid);
    
                $result["setting"]=$this->Search_Model->setting();
                $result["modules"]=$this->Admin_Model->get_modules($currentuser["permission"]);
                $result["suppliers"]=$this->Admin_Model->get_suppliers($companyid);
                $result["active_module"]='admin/suppliers';
    
                //$this->load->view('header1', $result);
                $this->load->view('adminheader', $result);
                $this->load->view('adminsidebar', $result);
                $this->load->view('admin_suppliers', $result);
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

    public function wholesalers() {
        //check is user having access rights
        if($this->session->userdata('user_id') && $this->session->userdata('current_user')) {
            $currentuser = $this->session->userdata('current_user');
            //if($currentuser["is_admin"]) {
            if(ADMIN_USERS & $currentuser["permission"]) {
                $companyid = $this->session->userdata("current_user")["companyid"];
                $cname = $this->session->userdata("current_user")["cname"];
                $result['cname']=$cname;
                
                $result["footer"]=$this->Search_Model->get_post(5);
                
                $result['company_setting']=$this->Search_Model->company_setting($companyid);
    
                $result["setting"]=$this->Search_Model->setting();
                $result["modules"]=$this->Admin_Model->get_modules($currentuser["permission"]);
                $result["wholesalers"]=$this->Admin_Model->get_wholesalers($companyid);
                $result["active_module"]='admin/wholesalers';
    
                //$this->load->view('header1', $result);
                $this->load->view('adminheader', $result);
                $this->load->view('adminsidebar', $result);
                $this->load->view('admin_wholesalers', $result);
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

    public function get_users($companyid) {
        //$companyid = $this->session->userdata("current_user")["companyid"];
        $result = array();
        $cid = $this->session->userdata("current_user")["companyid"];
        header('Content-type: application/json');
        if($cid==$companyid) {
            $users = $this->User_Model->get_users($companyid);
            $flag = true;
        }
        else {
            $flag = false;
            $users = array();
        }
        //$result->message = $flag?'Success':'Failed';
        //$result->result = $users;

        $idx = 0;
        foreach($users as $user) {
            $valarr = array();
            foreach($user as $key => $val) {
                array_push($valarr, ($val!=null?$val:''));
            }
            //$values = array_filter($userlist);
            array_push($result, array('id'=>$idx, 'data'=>array_values($valarr)));
            $idx++;
        }

        $json_data = json_encode(array('rows'=>$result));
        echo $json_data;
    }
}
?>