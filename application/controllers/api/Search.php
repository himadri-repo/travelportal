<?php
//use Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'core/Common.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Himadri Majumdar
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Search extends REST_Controller {

    protected $default_infant_price = 1500;

    function __construct()
    {
        // Construct the parent class
        header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Cache-Control, Pragma, Expires");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        parent::__construct();
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['suppliers_post']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['wholesalers_post']['limit'] = 500; // 100 requests per hour per user/key

        //loading models
        date_default_timezone_set('Asia/Calcutta');
		$this->load->database();
		$this->load->library('session');
		$this->load->model('User_Model');
        $this->load->model('Search_Model');
        $this->load->model('Admin_Model');
    }

    public function get_inventory_roundtrip_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
        $reqid = uniqid();
        //$payload['payload']['reqid'] = $reqid;
        //$result = array('code' => 200, 'status' => 'success', 'message' => "Your request has been successfully posted [Id: $reqid]");

		$companyid = $payload["companyid"];
		$source = $payload["source"];
		$destination = $payload["destination"];
		$from_date = $payload["from_date"];
		$to_date = $payload["to_date"];
		$triptype = $payload["trip_type"];
		$approved = isset($payload["approved"]) ? intval($payload["approved"]) : 1;
		$available = isset($payload["available"]) ? $payload["available"] : "YES";
        $no_of_person = $payload["no_of_person"];
		$infant_price = $this->default_infant_price;        

        $result = array('code' => 500, 'status' => 'error', 'message' => 'Can`t process your request at this moment.');
        $company = $this->Search_Model->get('company_tbl', array('id' => intval($companyid)));
        if($company && is_array($company) && count($company)>0) {
            $company = $company[0];

            $settings = $this->Search_Model->company_setting($company["id"]);
            $company = array_merge($company, $settings);
        }

        log_message('debug', "Posted message for capturing user query [$reqid]=> ".json_encode($payload));
        try {
            $result = $this->Search_Model->search_round_wayv2(array(
                'reqid' => $reqid,
                'companyid' => $companyid,
                'source' => $source,
                'destination' => $destination,
                'from_date' => date('Y-m-d', strtotime($from_date)),
                'to_date' => date('Y-m-d', strtotime($to_date)),
                'no_of_person' => $no_of_person,
                'trip_type' => $triptype,
                'approved' => $approved,
                'available' => $available
            ));

            log_message('debug', 'user query saved successfully => '.json_encode($result));
            //$result = array('code' => 200, 'status' => 'success', 'message' => "Your request has been successfully posted [Id: $reqid]");

            // $flag = send_email('CUSTOMER_QUERY', "Customer Query Raised : $reqid", $company, array_merge($payload['payload'], array(
            //     'company_name' => $company['name']
            //     )
            // ));
        }
        catch(Exception $ex) {
            log_message('error', $ex);
            $result = array('code' => 500, 'status' => 'error', 'message' => "Can`t process your request at this moment [$ex].");
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }
}