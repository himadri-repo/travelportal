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

class Admin extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
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

    public function wholesalers_post() {
        $query = $this->post('query');
        $wholesalers = $this->Admin_Model->search_wholesalers();

        $this->set_response($wholesalers, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function suppliers_post() {
        $query = $this->post('query');
        $suppliers = $this->Admin_Model->search_suppliers();

        $this->set_response($suppliers, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function communication_query_post() {
        $inviteeid = $this->post('inviteeid');
        $invitorid = $this->post('invitorid');

        $communications = $this->Admin_Model->search_communications($inviteeid, $invitorid);

        $this->set_response($communications, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function communication_query_get($communicationid) {
        // $inviteeid = $this->post('inviteeid');
        // $invitorid = $this->post('invitorid');

        $communicationDetails = $this->Admin_Model->search_communication_details($communicationid);

        $this->set_response($communicationDetails, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function messages_inbox_get($companyid) {
        $communicationDetails = $this->Admin_Model->messagesByCompanyid('inbox', $companyid);

        $this->set_response($communicationDetails, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function messages_outbox_get($companyid) {
        $communicationDetails = $this->Admin_Model->messagesByCompanyid('outbox', $companyid);

        $this->set_response($communicationDetails, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function message_read_post() {
        $msgid = $this->post('msgid');
        $userid = $this->post('userid');

        $result = array();
        try
        {
            $returnvalue = $this->Admin_Model->message_read($msgid, $userid);
            // $messageDetail["pid"] = $communication_update[0]["id"];
            $result["id"] = $msgid;
            $result["message"] = "Records updated successfully";
            $result["status"] = true;
        }
        catch(Exception $ex) {
            $result["id"] = $msgid;
            $result["message"] = "Records updation failed $ex";
            $result["status"] = false;
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function message_post() {
        $message = $this->post('communication');
        $result = array();

        //$message = json_decode($communication, true);
        $messageItem = array();
        $messageDetail = array();

        if($message["id"] > 0) {
            $messageItem["id"] = $message["id"];
        }
        $messageItem["title"] = $message["title"];
        $messageItem["active"] = $message["active"];
        $messageItem["companyid"] = $message["companyid"];
        $messageItem["created_by"] = $message["created_by"];

        $this->db->trans_start();

        try
        {
            $communication_update = $this->Admin_Model->message_add($messageItem);
            $messageDetail["pid"] = $communication_update[0]["id"];
            $result["id"] = $communication_update[0]["id"];
        }
        catch(Exception $ex) {

        }

        $communicationDetail = $message["details"][0];
        $messageDetail["message"] = $communicationDetail["message"];
        $messageDetail["from_companyid"] = intval($communicationDetail["from_companyid"]);
        $messageDetail["to_companyid"] = $communicationDetail["to_companyid"];
        $messageDetail["ref_no"] = $communicationDetail["ref_no"];
        $messageDetail["type"] = $communicationDetail["type"];
        $messageDetail["active"] = $communicationDetail["active"];
        $messageDetail["created_by"] = $communicationDetail["created_by"];
        // $messageDetail["created_on"] = $communicationDetail["created_on"];

        try
        {
            $communication_detail_update = $this->Admin_Model->message_detail_add($messageDetail);
            $result["child_id"] = $communication_detail_update[0]["id"];
            $result["message"] = "Records inserted successfully";
            $this->db->trans_complete();
        }
        catch(Exception $ex) {
            $this->db->trans_rollback();
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }
}