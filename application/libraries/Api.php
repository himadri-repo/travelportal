<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api {
    private $ci;
	public function __construct()
	{
        date_default_timezone_set('Asia/Calcutta'); 		
        
        $this->ci=& get_instance();
		$this->ci->load->library('session');
		$this->ci->load->helper('url');
		$this->ci->load->helper('security');

        $this->ci->load->database();
	}

}

?>