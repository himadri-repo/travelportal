<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'entities/baseentity.php');
class company extends entity {
    private $id;
    private $code;
    private $name;
    private $address;
    private $display_name;
    private $tenent_code;
    private $primary_user_id;
    private $gst_no;
    private $pan;
    private $type;
    private $created_by;
    private $created_on;
    private $updated_by;
    private $updated_on;
    
    private $primary_user;

    public function __construct()
	{
		parent::__construct();
	}

    public function __get($name) {

    }
}
?>
