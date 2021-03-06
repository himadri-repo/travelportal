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
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Admin extends REST_Controller {

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

    public function save_wholesaler_post() {
        $wholesaler = $this->post('wholesaler');
        $result = array();

        $wholesalerDetail = NULL;
        if($wholesaler['details']!==null && count($wholesaler['details'])) {
            $wholesalerDetail = $wholesaler['details'][0];
        }

        $this->db->trans_start();
        try
        {
            $wholesaler_update = $this->Admin_Model->wholesaler_save($wholesaler);
            $wholesalerDetail["wholesaler_rel_id"] = $wholesaler_update[0]["id"];
            $result["id"] = $wholesaler_update[0]["id"];
            $result["parent_msg"] = "Wholesaler saved successfully";
        }
        catch(Exception $ex) {
            //console.log($ex);
            log_message('error', $ex);
        }

        try
        {
            $wholesaler_detail_update = $this->Admin_Model->wholesaler_detail_save($wholesalerDetail);
            $result["detail_id"] = $wholesaler_detail_update[0]["id"];
            $result["message"] = "Wholesaler details saved successfully";
            if(isset($wholesalerDetail["tracking_id"])) {
                // tracking id passed so lets save it into supplier_services_tbl also.
                $supplier_detail = array("id" => $wholesalerDetail["tracking_id"], "tracking_id" => $result["detail_id"]);
                $this->Admin_Model->supplier_detail_save($supplier_detail);
            }
            $this->db->trans_complete();
        }
        catch(Exception $ex) {
            $this->db->trans_rollback();
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function save_supplier_post() {
        $supplier = $this->post('supplier');
        $result = array();

        $supplierDetail = NULL;
        if($supplier['details']!==null && count($supplier['details'])) {
            $supplierDetail = $supplier['details'][0];
        }

        $this->db->trans_start();
        try
        {
            $supplier_update = $this->Admin_Model->supplier_save($supplier);
            $supplierDetail["supplier_rel_id"] = $supplier_update[0]["id"];
            $result["id"] = $supplier_update[0]["id"];
            $result["parent_msg"] = "Supplier saved successfully";
        }
        catch(Exception $ex) {
            //console.log($ex);
            log_message('error', $ex);
        }

        try
        {
            $supplier_detail_update = $this->Admin_Model->supplier_detail_save($supplierDetail);
            $result["detail_id"] = $supplier_detail_update[0]["id"];
            $result["message"] = "Supplier details saved successfully";
            if(isset($supplierDetail["tracking_id"])) {
                // tracking id passed so lets save it into supplier_services_tbl also.
                $wholesaler_detail = array("id" => $supplierDetail["tracking_id"], "tracking_id" => $result["detail_id"]);
                $this->Admin_Model->supplier_detail_save($wholesaler_detail);
            }
            $this->db->trans_complete();
        }
        catch(Exception $ex) {
            $this->db->trans_rollback();
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function communication_query_post() {
        $inviteeid = $this->post('inviteeid');
        $invitorid = $this->post('invitorid');
        $invitation_type = $this->post('invitationType');

        $communications = $this->Admin_Model->search_communications($inviteeid, $invitorid, $invitation_type);

        $this->set_response($communications, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function communication_query_get($communicationid) {
        // $inviteeid = $this->post('inviteeid');
        // $invitorid = $this->post('invitorid');

        $communicationDetails = $this->Admin_Model->search_communication_details($communicationid);

        $this->set_response($communicationDetails, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function communication_detail_get($commdetailid) {
        // $inviteeid = $this->post('inviteeid');
        // $invitorid = $this->post('invitorid');

        $communicationDetails = $this->Admin_Model->search_communication_detail($commdetailid);

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
        $messageItem["status"] = $message["status"];
        $messageItem["companyid"] = $message["companyid"];
        $messageItem["created_by"] = $message["created_by"];

        $this->db->trans_start();

        try
        {
            log_message('info', "Saving messages/invitation into communication table");
            log_message('info', json_encode($messageItem));
            $communication_update = $this->Admin_Model->message_add($messageItem);
            $messageDetail["pid"] = $communication_update[0]["id"];
            $result["id"] = $communication_update[0]["id"];
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $communicationDetail = $message["details"][0];
        $messageDetail["message"] = $communicationDetail["message"];
        $messageDetail["from_companyid"] = intval($communicationDetail["from_companyid"]);
        $messageDetail["to_companyid"] = $communicationDetail["to_companyid"];
        $messageDetail["ref_no"] = $communicationDetail["ref_no"];
        $messageDetail["type"] = $communicationDetail["type"];
        $messageDetail["active"] = $communicationDetail["active"];
        $messageDetail["invitation_type"] = intval($communicationDetail["invitation_type"]);
        $messageDetail["serviceid"] = intval($communicationDetail["serviceid"]);
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

    public function message_details_post() {
        $communicationDetail = $this->post('communicationdetail');
        $result = array();
        try
        {
            log_message('info', json_encode($communicationDetail));
            $communication_detail_update = $this->Admin_Model->message_detail_add($communicationDetail);
            $result["child_id"] = $communication_detail_update[0]["id"];
            $result["message"] = "Records inserted successfully";
            $result["status"] = true;
        }
        catch(Exception $ex) {
            $result["message"] = "Record insertion failed";
            $result["status"] = false;
            log_message('error', $ex);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function rate_plan_post() {
        $companyid = $this->post('companyid');
        try
        {
            $rateplans = $this->Admin_Model->rateplanByCompanyid($companyid);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }
        $this->set_response($rateplans, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function rate_plan_details_get($rateplanid) {
        //$rateplanid = $this->post('rateplanid');
        try
        {
            $rateplansdetails = $this->Admin_Model->rateplandetails($rateplanid);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }
        $this->set_response($rateplansdetails, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function save_rateplan_post() {
        $rateplan = $this->post('rateplan');
        $rateplanDetail = NULL;
        if($rateplan["details"]!=null && count($rateplan["details"]))
        {
            $rateplanDetail = $rateplan["details"];
            unset($rateplan["details"]);
        }

        try
        {
            log_message('debug', 'Admin:save_rateplan_post:: Rateplan => '.json_encode($rateplan, TRUE));
            log_message('debug', 'Admin:save_rateplan_post:: RateplanDetails => '.json_encode($rateplanDetail, TRUE));

            $this->db->trans_start();
            $rateplan_info = $this->Admin_Model->save_rateplan($rateplan);
            $rateplan["id"] = $rateplan_info[0]["id"];
            $result["id"] = $rateplan_info[0]["id"];

            for ($i=0; $i<count($rateplanDetail); $i++) { 
                $rateplanDetail[$i]["rateplanid"] = intval($rateplan["id"]);
            }

            $rateplan_detail_info = $this->Admin_Model->save_rateplan_details($rateplanDetail);
            if($rateplan_detail_info!=null && count($rateplan_detail_info)>0) {
                $rateplan_detail_info = $rateplan_detail_info[0];
                for ($i=0; $i<count($rateplanDetail) ; $i++) { 
                    $rateplanDetail[$i]["id"] = intval($rateplan_detail_info[$i]["id"]);
                    $result["child.id-$i"] = $rateplan_detail_info[$i]["id"];
                    $result["child.id-$i-message"] = "$i - Rateplan details saved successfully";
                }
            }

            $result["message"] = "Rateplan with details saved successfully";

            $this->db->trans_commit();
        }
        catch(Exception $ex) {
            $result["message"] = "Rateplan with details failed to saved <$ex>";
            $this->db->trans_rollback();
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function save_supplier_details_post($supplierid, $wholesalerid) {
        $supplierDetail = $this->post('detail');
        $result = array();

        try
        {
            $supplier = $this->Admin_Model->get_supplier($supplierid, $wholesalerid);
            $supplier = $supplier[0];
            $supplierDetail["id"] = $supplier["id"];
            $changedSupplierDetail = $this->Admin_Model->supplier_detail_save($supplierDetail);

            $result["id"] = $supplier["id"];
            $result["message"] = "Records successfully saved";
        }
        catch(Exception $ex) {
            $result["id"] = -1;
            $result["message"] = "Record failed to saved <$ex>";
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function save_wholesaler_details_post($supplierid, $wholesalerid) {
        $wholesalerDetail = $this->post('detail');
        $result = array();

        try
        {
            $wholesaler = $this->Admin_Model->get_wholesaler($supplierid, $wholesalerid);
            $wholesaler = $wholesaler[0];
            $wholesalerDetail["id"] = $wholesaler["id"];
            $changedWholesalerDetail = $this->Admin_Model->wholesaler_detail_save($wholesalerDetail);

            $result["id"] = $wholesaler["id"];
            $result["message"] = "Records successfully saved";
        }
        catch(Exception $ex) {
            $result["id"] = -1;
            $result["message"] = "Record failed to saved <$ex>";
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function cities_get() {
        try
        {
            $cities = $this->Admin_Model->get_cities();
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($cities, REST_Controller::HTTP_OK);
    }

    public function airlines_get() {
        try
        {
            $airlines = $this->Admin_Model->get_airlines();
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($airlines, REST_Controller::HTTP_OK);
    }

    public function save_pnr_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
 
        try {
            $result = $this->Admin_Model->save_pnr($payload);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function accounts_get() {
        try
        {
            $accounts = $this->Admin_Model->get_accounts();
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($accounts, REST_Controller::HTTP_OK);
    }

   public function capture_user_query_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        //$payload = $this->input->raw_input_stream;
        $payload = json_decode($payload, true);
        $reqid = uniqid();
        //$payload['payload']['reqid'] = $reqid;
        //$result = array('code' => 200, 'status' => 'success', 'message' => "Your request has been successfully posted [Id: $reqid]");
        $result = array('code' => 500, 'status' => 'error', 'message' => 'Can`t process your request at this moment.');
        $company = $this->Search_Model->get('company_tbl', array('id' => intval($payload['payload']['companyid'])));
        if($company && is_array($company) && count($company)>0) {
            $company = $company[0];

            $settings = $this->Search_Model->company_setting($company["id"]);
            $company = array_merge($company, $settings);
        }

        log_message('debug', "Posted message for capturing user query [$reqid]=> ".json_encode($payload));
        try {
            $result = $this->Search_Model->save('user_query_tbl', array(
                'reqid' => $reqid,
                'source_city' => $payload['payload']['source_sector'],
                'destination_city' => $payload['payload']['destination_sector'],
                'departure_date' => date('Y-m-d', strtotime($payload['payload']['departure_date'])),
                'no_of_person' => $payload['payload']['no_of_person'],
                'start_price' => $payload['payload']['start_price'],
                'end_price' => $payload['payload']['end_price'],
                'time_range' => $payload['payload']['time_range'],
                'mobile' => $payload['payload']['mobile'],
                'email' => $payload['payload']['email'],
                'is_flexible' => $payload['payload']['is_flexible'],
                'remarks' => $payload['payload']['remarks'],
                'userid' => $payload['payload']['userid'],
                'companyid' => $payload['payload']['companyid'],
                'created_by' => $payload['payload']['userid']
            ));

            log_message('debug', 'user query saved successfully => '.json_encode($result));
            $result = array('code' => 200, 'status' => 'success', 'message' => "Your request has been successfully posted [Id: $reqid]");

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