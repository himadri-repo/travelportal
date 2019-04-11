<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Contact_Controller extends CI_Controller 

{

	 public function __construct()
	 {

            parent::__construct();		
			$this->load->library('form_validation');
			$this->load->library('encrypt');
			$this->load->library('email');
			$this->load->helper('url');
            $this->load->library('session');
			$this->load->helper('form');
          			
    }

	public function send()
	{			  
		 $config['protocol'] = "smtp";		
		 $config['smtp_host'] = "ssl://smtp.gmail.com";
		 $config['smtp_port'] = "465";		
		 $config['smtp_user'] = "noreply@oxytra.com";
		 $config['smtp_pass'] = "password@123";
		 $config['charset']    = "utf-8";    
         $config['mailtype'] = "html";     	
		 $this->load->library('email',$config);
         $this->email->set_newline("\r\n");
		
		 $name =$this->input->post('name');
		 $sender_email = $this->input->post('email');
		 $phone = $this->input->post('phone');
		 $msg = $this->input->post('msg');	
		 $msg="Name : ".$name."\n Phone : ".$phone."\n Message : ".$msg."";
		 
		 $this->email->from('noreply@oxytra.com',$name); 
		 $this->email->reply_to($sender_email,$name);       
		 $this->email->to('info@oxytra.com');		
		 $this->email->subject("Enquiry");	
		 $this->email->message($msg);	
		if ($this->email->send())
            $data['success'] = "hello, '".$name."' Your Message delivered. We will contact you soon!";
        else 
           $data['error'] =  $this->email->print_debugger();	     
	   echo json_encode($data);

	

	}

	

	
}

	

	



?>