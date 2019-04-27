<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Admin_Model extends CI_Model
{
    public function __construct()
	{ 
	    date_default_timezone_set("Asia/Calcutta");   
	}

    public function get_modules($permision)
	{
        //$arr=array("m.code"=>$id);
		$this->db->select('m.*');
		$this->db->from('modules_tbl as m');
		$this->db->where('m.code & '.$permision);
        $this->db->order_by("m.category asc, m.display_order asc");
		$query = $this->db->get();					
		//echo $this->db->last_query();die();
		if ($query->num_rows() > 0) 
		{					
            return $query->result_array();		
		}
		else
		{
            return false;
		}
    }
}	
?>