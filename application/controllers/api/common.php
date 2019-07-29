<?php
//use Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Common extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Cache-Control, Pragma, Expires");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        //loading models
        date_default_timezone_set('Asia/Calcutta');
		$this->load->database();
		$this->load->library('session');
		$this->load->model('User_Model');
        $this->load->model('Search_Model');
        $this->load->model('Admin_Model');
    }
    
    public function menus_get($uid=104)
    {
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];
        $currentuser = $this->session->userdata('current_user');
        if($currentuser==null)
            $currentuser = $this->User_Model->get_userbyid($uid);

        $menus = array();
        $uid = $this->session->userdata('user_id');
        $settings = $this->Search_Model->setting();
        $modules = $this->Admin_Model->get_modules($currentuser["permission"]);
        $category = ''; //count($modules)>0?$modules[0]["category"]:0;
        $index=0;
        for ($i=0; $i<count($modules); $i++) { 
            if($modules[$i]["category"]==$category)
            {
                $index++;
            }
            else {
                $index = 0;
            }
            $menus[$modules[$i]["category"]][$index] = array('name'=>$modules[$i]["name"], 'display_name'=>$modules[$i]["display_name"], 'path'=>$modules[$i]["path"], 'code'=>$modules[$i]["code"], 'active'=>$modules[$i]["active"]);
            
            $category = $modules[$i]["category"];
        }

        $this->response($menus, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function metadata_get($object_type, $companyid=0) {
        $result = array();

        try
        {
            $result = $this->Admin_Model->get_metadata($object_type, $companyid);
        }
        catch(Exception $ex) {
            
        }

        $this->set_response($result, REST_Controller::HTTP_OK);
    }
}