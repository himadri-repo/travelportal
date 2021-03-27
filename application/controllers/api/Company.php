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
class Company extends REST_Controller {

    function __construct()
    {
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
        $this->methods['company_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['company_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['company_delete']['limit'] = 50; // 50 requests per hour per user/key

        //loading models
        date_default_timezone_set('Asia/Calcutta');
		$this->load->database();
		$this->load->library('session');
		$this->load->model('User_Model');
        $this->load->model('Search_Model');
        $this->load->model('Admin_Model');
    }
    
    public function index_get($id=0)
    {
        // If the id parameter doesn't exist return all the users
        $company = NULL;

        if ($id == 0)
        {
            // Find and return a single record for a particular user.
            // Invalid id, set the response and exit.
            $this->response([
                'status' => FALSE,
                'message' => 'Invalid user id passed.'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        else
        {
            $company = $this->Admin_Model->get_company($id);
            if($company && count($company)>0) {
                $company = $company[0];
            }

            $companysetting = $this->Search_Model->company_setting($id, TRUE);
            if($companysetting && count($companysetting)>0) {
                $company['settings'] = $companysetting[0];
            }

            if (!empty($company))
            {
                $this->response($company, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->response([
                    'status' => FALSE,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function users_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users
        $user = NULL;

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                //exit;
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        else if ($id <= 0)
        {
            // Find and return a single record for a particular user.
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        else if (!empty($users))
        {
            foreach ($users as $key => $value)
            {
                if (isset($value['id']) && $value['id'] === intval($id))
                {
                    $user = $value;
                }
            }
            if (!empty($user))
            {
                //$this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                $this->response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // $this->set_response([
                //     'status' => FALSE,
                //     'message' => 'User could not be found'
                // ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                $this->response([
                    'status' => FALSE,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

    public function wholesalers_post() {
        $companyid = $this->post('companyid');
        $wholesalers = $this->Admin_Model->get_wholesalers($companyid);

        $this->set_response($wholesalers, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function suppliers_post() {
        $companyid = $this->post('companyid');
        $suppliers = $this->Admin_Model->get_suppliers($companyid);

        $this->set_response($suppliers, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function customers_post() {
        $companyid = intval($this->post('companyid'));
        $email = $this->post('email');
        $mobile = $this->post('mobile');
        $compid = intval($this->post('compid'));
        $id = intval($this->post('id'));

        if ($companyid>0) {
            $customers = $this->Admin_Model->get_customers($companyid);
        } else if ($email != null && $mobile != null) {
            $customers = $this->Admin_Model->get_customersByEmailOrMobile($email, $mobile, $compid, $id);
        }

        $this->set_response($customers, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    //Filter customers by email and mobile
    public function customers_get($email, $mobile) {
        $customers = $this->Admin_Model->get_customersByEmailOrMobile($email, $mobile);

        $this->set_response($customers, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function customer_post() {
        $customer = $this->post('customer');
        $customer_update = $this->Admin_Model->set_customer($customer);

        $this->set_response($customer_update, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function customer_get($company, $customerid) {
        $customer = $this->Admin_Model->get_customer(intval($company), intval($customerid));
        if($customer && count($customer)>0) {
            $customer[0]['transactions'] = [];

            $transactions = $this->Admin_Model->get_transactions(intval($company), intval($customerid));
            if($transactions) {
                $customer[0]['transactions'] = $transactions;
            }
        }

        $this->set_response($customer, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function tickets_post() {
        $companyid = $this->post('companyid');
        $tickets = $this->Search_Model->get_tickets($companyid);

        $this->set_response($tickets, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function services_get($companyid) {
        $services = $this->Admin_Model->get_services(intval($companyid));

        $this->set_response($services, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

/*
    public function bookings_get($companyid, $userid) {
        if($userid === NULL || $userid==='') {
            $userid = -1;
        }
        if($companyid === NULL || $companyid==='') {
            $companyid = -1;
        }

        $companyid = intval($companyid);
        $userid = intval($userid);

        $rateplans = $this->Admin_Model->rateplanByCompanyid(-1);

        $customers = $this->Search_Model->get_booking_customers(-1, intval($companyid), intval($userid));

        $bookings = $this->Search_Model->get_bookings(intval($companyid), intval($userid));

        $tickets = $this->Search_Model->get_tickets(intval($companyid));

        for ($i=0; $bookings && $i < count($bookings); $i++) { 
            $booking = &$bookings[$i];
            $objrateplan = NULL;
            foreach ($rateplans as $rateplan) {
                if($booking['rateplanid']===$rateplan['id']) {
                    $objrateplan = $rateplan;
                    break;
                }
            }

            $booking['rateplan'] = $objrateplan;

            $booking_activities = $this->Search_Model->get_booking_activity(intval($booking['id']));
            if($booking_activities) {
                $booking['booking_activities'] = $booking_activities;
            }

            $customer_list = array();

            if($customers && count($customers)>0) {
                $lastcustid = 0;
                foreach ($customers as $customer) {
                    $cust_companyid = intval($customer['companyid']);
                    $cust_bookingid = intval($customer['cus_booking_id']);
                    $cust_refid = intval($customer['refrence_id']);
                    $bookid = intval($booking['id']);
                    // if((intval($customer['booking_id']) === intval($booking['id']) || (intval($customer['booking_id']) === intval($booking['parent_booking_id'])))
                    if((($companyid === $cust_companyid && ($cust_bookingid === $bookid || $cust_refid === $bookid))
                        || ($companyid !== $cust_companyid && $cust_refid === $bookid))
                        && $lastcustid !== intval($customer['id'])) 
                    {
                        $customer_list[] = $customer;
                    }
                    $lastcustid = intval($customer['id']);
                }
            }

            $booking['customers'] = $customer_list;
            
            $b2bmarkup_value = 0;
            if($booking['customer_type']=='B2B' && intval($booking['is_admin'])==0) {
                $b2bmarkup_value = floatval($booking['field_value']);
            }
            
            $booking['rate'] = floatval($booking['rate']) - $b2bmarkup_value;
            $booking['amount'] = floatval($booking['amount']) - ($b2bmarkup_value * intval($booking['qty']));
            $booking['total'] = floatval($booking['total']) - ($b2bmarkup_value * intval($booking['qty']));

            $ticket = $this->Search_Model->get_ticket(intval($booking['ticket_id']));
            if($ticket && count($ticket)>0)
                $booking['ticket'] = $ticket[0];
        }

        $this->set_response($bookings, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }
*/

    public function bookings_post($companyid, $userid) {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $filter = json_decode($stream_clean, true);
        $fromdate = null;
        $todate = null;
        
        if($filter && isset($filter['fromdate'])) {
            $fromdate = Date($filter['fromdate']);
        }
        if($filter && isset($filter['todate'])) {
            $todate = Date($filter['todate']);
        }

        if($userid === NULL || $userid==='') {
            $userid = -1;
        }
        if($companyid === NULL || $companyid==='') {
            $companyid = -1;
        }

        $companyid = intval($companyid);
        $userid = intval($userid);

        $rateplans = $this->Admin_Model->rateplanByCompanyid(-1);

        $customers = $this->Search_Model->get_booking_customers(-1, intval($companyid), intval($userid), $fromdate, $todate);

        $bookings = $this->Search_Model->get_bookings(intval($companyid), intval($userid), $fromdate, $todate);

        $tickets = $this->Search_Model->get_tickets(intval($companyid), $fromdate);

        for ($i=0; $bookings && $i < count($bookings); $i++) { 
            $booking = &$bookings[$i];
            $objrateplan = NULL;
            foreach ($rateplans as $rateplan) {
                if($booking['rateplanid']===$rateplan['id']) {
                    $objrateplan = $rateplan;
                    break;
                }
            }

            $booking['rateplan'] = $objrateplan;

            $booking_activities = $this->Search_Model->get_booking_activity(intval($booking['id']));
            if($booking_activities) {
                $booking['booking_activities'] = $booking_activities;
            }

            $customer_list = array();

            if($customers && count($customers)>0) {
                $lastcustid = 0;
                foreach ($customers as $customer) {
                    $cust_companyid = intval($customer['companyid']);
                    $cust_bookingid = intval($customer['cus_booking_id']);
                    $cust_refid = intval($customer['refrence_id']);
                    $bookid = intval($booking['id']);
                    // if((intval($customer['booking_id']) === intval($booking['id']) || (intval($customer['booking_id']) === intval($booking['parent_booking_id'])))
                    if((($companyid === $cust_companyid && ($cust_bookingid === $bookid || $cust_refid === $bookid))
                        || ($companyid !== $cust_companyid && $cust_refid === $bookid))
                        && $lastcustid !== intval($customer['id'])) 
                    {
                        $customer_list[] = $customer;
                    }
                    $lastcustid = intval($customer['id']);
                }
            }

            $booking['customers'] = $customer_list;
            
            $b2bmarkup_value = 0;
            if($booking['customer_type']=='B2B' && intval($booking['is_admin'])==0) {
                $b2bmarkup_value = floatval($booking['field_value']);
            }
            
            // $booking['rate'] = floatval($booking['rate']) - $b2bmarkup_value;
            // $booking['amount'] = floatval($booking['amount']) - ($b2bmarkup_value * intval($booking['qty']));
            // $booking['total'] = floatval($booking['total']) - ($b2bmarkup_value * intval($booking['qty']));

            $booking['rate'] = floatval($booking['rate']);
            $booking['amount'] = floatval($booking['amount']);
            $booking['total'] = floatval($booking['total']);

            $ticket = null;
            for ($ti=0; $ti<count($tickets); $ti++) { 
                $ticket = $tickets[$ti];
                if($ticket && is_array($ticket) && isset($ticket['id']) && intval($ticket['id']) === intval($booking['ticket_id'])) {
                    $booking['ticket'] = $ticket; 
                }
            }

            if(!isset($booking['ticket'])) {
                $booking['ticket'] = array();
            }

            //$ticket = $this->Search_Model->get_ticket(intval($booking['ticket_id']));
            // if($ticket && count($ticket)>0)
            //     $booking['ticket'] = $ticket[0];
        }

        $this->set_response($bookings, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function open_tickets_post() {
        $tickets = NULL;
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $arg = json_decode($stream_clean, true);
        $adminmarkup = 0; //This will be admin markup for Travel Agents. Can be get by userid

        try
        {
            $rateplan_details = $this->Admin_Model->rateplandetails(-1);
            $companyid = intval($arg["companyid"]);

            $tickets = $this->Search_Model->search_one_wayV2($arg);

            for ($i=0; $tickets && $i < count($tickets); $i++) { 
                $ticket = &$tickets[$i];
                $suprpd = [];
                $sellrpd = [];

                if(intval($companyid) === intval($ticket["companyid"])) {
                    $suprpid = 0;
                    $sellrpid = intval($ticket["rate_plan_id"]);
                }
                else {
                    $suprpid = intval($ticket["rate_plan_id"]);
                    $sellrpid = intval($ticket["seller_rateplan_id"]);
                }

                if($rateplan_details && count($rateplan_details)>0) {
                    foreach ($rateplan_details as $rateplan_detail) {
                        $rpid = intval($rateplan_detail["rateplanid"]);
    
                        if($rpid === $suprpid) {
                            $suprpd[] = $rateplan_detail;
                        }
                        if($rpid === $sellrpid) {
                            $sellrpd[] = $rateplan_detail;
                        }
                    }
                    $usertype = '';
                    if(count($suprpd)>0 || count($sellrpd)>0) {
                        $this->calculationTicketValue($ticket, $suprpd, $sellrpd, $companyid, $adminmarkup, $usertype);
                    }
                }
            }
        }
        catch(Exception $ex) {
            
        }

        $this->set_response($tickets, REST_Controller::HTTP_OK);
    }

	private function calculationTicketValue(&$ticket, $supplier_rpdetails, $seller_rpdetails, $companyid, $adminmarkup=0, $usertype='') {
		$rateplanid = $ticket['rate_plan_id'];
		$price = $ticket['price'];
		
		$ticket['whl_markup'] = 0;
		$ticket['whl_srvchg'] = 0;
		$ticket['whl_cgst'] = 0;
		$ticket['whl_sgst'] = 0;
		$ticket['whl_igst'] = 0;
		$ticket['whl_disc'] = 0;

		$ticket['spl_markup'] = 0;
		$ticket['spl_srvchg'] = 0;
		$ticket['spl_cgst'] = 0;
		$ticket['spl_sgst'] = 0;
		$ticket['spl_igst'] = 0;
		$ticket['spl_disc'] = 0;

		$ticket['cost_price'] = 0;

		$tax_others = 0;

		try
		{
			if(intval($ticket['supplierid']) !== $companyid) {
                //accomodate supplier rateplan
                for ($j=0; $j < count($supplier_rpdetails); $j++) { 
                    $rpdetail = $supplier_rpdetails[$j];
                    $achead = 'spl_'.$rpdetail['head_code'];
                    // array_push($ticket, [$achead => '']);
                    //if($rpdetail['head_code'] !== 'igst') { //because igst can only be calculated for other state
                        if($rpdetail['operation'] == 1) {
                            // add operation
                            $ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
                            $tax_others = $tax_others + $ticket[$achead];
                        }
                        else {
                            // subtraction operation
                            $ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
                            $tax_others = $tax_others - $ticket[$achead];
                        }
                    //}
                }
            }

			// $ticket['price'] += $tax_others;
			$tax_others = 0;
			
            //accomodate wholesaler rateplan
            for ($j=0; $j < count($seller_rpdetails); $j++) { 
                $rpdetail = $seller_rpdetails[$j];
                $achead = 'whl_'.$rpdetail['head_code'];

                // if(intval($ticket['supplierid']) === intval($companyid) && $usertype !== 'B2B') {
                //     $achead = 'whl_'.$rpdetail['head_code'];
                // }
                // else {
                //     $achead = 'spl_'.$rpdetail['head_code'];
                // }

                // array_push($ticket, [$achead => '']);
                //if($rpdetail['head_code'] !== 'igst') { //because igst can only be calculated for other state
                    if($rpdetail['operation'] == 1) {
                        $ticket[$achead] += $this->getProcessedValue($rpdetail, $price, $ticket);
                        $tax_others += $ticket[$achead];
                    }
                    else {
                        $ticket[$achead] = $this->getProcessedValue($rpdetail, $price, $ticket);
                        $tax_others -= $ticket[$achead];
                    }
                //}
            }
		}
		catch(Exception $ex) {

		}

		// $ticket['finalvalue'] = $tax_others;
        // $ticket['price'] += $tax_others;

        // Original code : This code commented to have below code
        // if(!(intval($ticket['supplierid']) === intval($companyid) && $usertype !== 'B2B')) {
        //     if ($ticket['whl_srvchg'] > 0) {
        //         $ticket['spl_srvchg'] += $ticket['whl_srvchg'];
        //         $ticket['spl_cgst'] += $ticket['whl_cgst'];
        //         $ticket['spl_sgst'] += $ticket['whl_sgst'];
        //         $ticket['spl_igst'] += $ticket['whl_igst'];

        //         $ticket['whl_srvchg'] = 0;
        //         $ticket['whl_cgst'] = 0;
        //         $ticket['whl_sgst'] = 0;
        //         $ticket['whl_igst'] = 0;
        //     }
        // }
        
        $price = $ticket['price'];

		$ticket['admin_markup'] = $adminmarkup;
		$ticket['price'] += ($ticket['whl_markup'] + $ticket['spl_markup'] + $ticket['whl_srvchg'] + $ticket['spl_srvchg'] 
				+ ($ticket['whl_srvchg'] * $ticket['whl_cgst'] / 100)
				+ ($ticket['whl_srvchg'] * $ticket['whl_sgst'] / 100)
				+ ($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100)
                + ($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100));
                
        $ticket['cost_price'] = (($price + $ticket['spl_markup'] + $ticket['spl_srvchg'])
                                + ($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100)
                                + ($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100));

        if(intval($ticket['cost_price'])===0) {
            $ticket['cost_price'] = intval($price);
        }


		if ($ticket['whl_srvchg'] === 0) {
			$ticket['whl_cgst'] = 0;
			$ticket['whl_sgst'] = 0;
			$ticket['whl_igst'] = 0;
		}
		else {
			$ticket['whl_cgst'] = ($ticket['whl_srvchg'] * $ticket['whl_cgst'] / 100);
			$ticket['whl_sgst'] = ($ticket['whl_srvchg'] * $ticket['whl_sgst'] / 100);
			$ticket['whl_igst'] = ($ticket['whl_srvchg'] * $ticket['whl_igst'] / 100);
		}

		if ($ticket['spl_srvchg'] === 0) {
			$ticket['spl_cgst'] = 0;
			$ticket['spl_sgst'] = 0;
			$ticket['spl_igst'] = 0;
		}
		else {
			$ticket['spl_cgst'] = ($ticket['spl_srvchg'] * $ticket['spl_cgst'] / 100);
			$ticket['spl_sgst'] = ($ticket['spl_srvchg'] * $ticket['spl_sgst'] / 100);
			$ticket['spl_igst'] = ($ticket['spl_srvchg'] * $ticket['spl_igst'] / 100);
		}

		return $ticket;
    }
    
	private function getProcessedValue($rpdetail, $price, $ticket) {
		$operation = NULL;
		if(!($rpdetail['calculation']=='' || $rpdetail['calculation']==NULL)) {
			$operation = str_replace('}', '', str_replace('{', '', $rpdetail['calculation']));
		}
		if($operation !== NULL) {
			if(isset($ticket[$operation])) {
				$price = $ticket[$operation];
			}
			else {
				$price = 0;
			}
		}

		if($rpdetail['amount_type'] == 1) { //value
			return floatval($rpdetail['amount']);
		}
		else if($rpdetail['amount_type'] == 2) { //%
			// return $price * ($rpdetail['amount'] / 100); // this is the right approach but let us think how to implement it.
			return floatval($rpdetail['amount']);
		}
		else {
			return 0;
		}
    }

    public function bookingsquery_post() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $arg = json_decode($stream_clean, true);

        try
        {
            $bookings = $this->Search_Model->get_bookings_by_query($arg);
        }
        catch(Exception $ex) {
            $bookings = array();
        }

        $this->set_response($bookings, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function payment_details_post() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $arg = json_decode($stream_clean, true);

        try
        {
            $booking_payment = $this->Search_Model->get_booking_payment_by_query($arg);
        }
        catch(Exception $ex) {
            $booking_payment = array();
        }

        $this->set_response($booking_payment, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function upsert_bookings_post() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($stream_clean, true);
        $selectedTicket = [];
        $originalbooking = [];
        $pricediffaction = 'pass';

        if(isset($payload['bookings'])) {
            $bookings = $payload['bookings'];
            $selectedTicket = $payload['selectedticket'];
            $originalbooking = $payload['originalbooking'];
            $pricediffaction = $payload['price_diff_action'];
        }
        else {
            $bookings = $payload;
        }

        $idx = 1;
        $feedbacks = array();

        try
        {
            foreach ($bookings as $booking) {
                log_message('debug', "Booking to be settled => ".json_encode($booking));
                $feedbacks[] = array('idx' => $idx++, 'feedback' => $this->Search_Model->upsert_booking($booking, $selectedTicket, $originalbooking, $pricediffaction));
            }
        }
        catch(Exception $ex) {
            // $bookings = array();
            log_message('error', $ex);
        }

        $this->set_response($feedbacks, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function getticket_get($ticketid = -1) {
        
        try
        {
            $ticket = $this->Search_Model->get_ticket($ticketid);
        }
        catch(Exception $ex) {

        }

        $this->set_response($ticket, REST_Controller::HTTP_OK);
    }

    public function delete_booking_customer_get($bookingid=-1, $customerid=-1) {
        $flg = true;
        $process_db_interaction = true;
        try
        {
            if($process_db_interaction) {
             $this->Search_Model->update('customer_information_tbl', array('status' => 127), array('booking_id' => $bookingid, 'id' => $customerid));
            }
             
             $ordered_booking = $this->User_Model->get('bookings_tbl', array('id' => $bookingid));
             if($ordered_booking!=NULL && count($ordered_booking)>0) {
                 $ordered_booking = $ordered_booking[0];
             }

             if($ordered_booking) {
                $user_info = $this->User_Model->get('user_tbl', array('id' => $ordered_booking['customer_userid']));
                if($user_info && count($user_info)>0) {
                    $user_info = $user_info[0];
                }

                // $price = floatval($ordered_booking['price']);
                log_message('debug', "Company::delete_booking_customer - Before Wallet Trans: Booking Id : $bookingid | Customer Id : $customerid | Bookings Details: ".json_encode($ordered_booking));
                $price = floatval($ordered_booking['costprice']);
                $srvchg = floatval($ordered_booking['srvchg']);
                $cgst = floatval($ordered_booking['cgst']);
                $sgst = floatval($ordered_booking['sgst']);
                $igst = floatval($ordered_booking['igst']);

                $refundprice = $price + $srvchg + $cgst + $sgst;
                log_message('debug', "Company::delete_booking_customer - Wallet Trans Info: Booking Id : $bookingid | Customer Id : $customerid | Price : $price value will be credit or debited from wallet");

                log_message('debug', "Company::delete_booking_customer - Wallet Trans Info(Changed): Booking Id : $bookingid | Customer Id : $customerid | Price : $refundprice | srvchg : $srvchg | cgst : $cgst | sgst : $sgst");

                if(intval($user_info['is_admin'])!==1 && $process_db_interaction) {
                    $transactionid = $this->User_Model->perform_wallet_transaction($ordered_booking['customer_userid'], array(
                        'amount'=>$price,
                        'trans_type'=>$price>0?'DR':'CR',
                        'trans_ref_id'=>$bookingid,
                        'trans_ref_date'=>date("Y-m-d H:i:s"),
                        'trans_ref_type'=>$price>0 ?'CREDIT NOTE':'DEBIT NOTE',
                        "trans_documentid" => $bookingid
                    ));
                    
                    log_message('debug', "Company::delete_booking_customer - After Wallet Trans: Booking Id : $bookingid | Customer Id : $customerid | Wallet Trans Id: $transactionid");

                    $customer_companyid = intval($ordered_booking['customer_companyid']);
                    $cust_company = $this->Search_Model->get('company_tbl', array('id' => $customer_companyid));
                    if($cust_company && is_array($cust_company) && count($cust_company)>0) {
                        $cust_company = $cust_company[0];
                    }

                    log_message('debug', "[Search:delete_booking_customer] Transacting Accounts | Customer Companyid: $customer_companyid");
				
                    if($process_db_interaction) {
                            $voucher_no = $this->Search_Model->save("account_transactions_tbl", array(
                            "voucher_no" => $this->Search_Model->get_next_voucherno($cust_company), 
                            "transacting_companyid" => $customer_companyid, 
                            "transacting_userid" => intval($ordered_booking['customer_userid']), 
                            "documentid" => $bookingid, 
                            "document_date" => date("Y-m-d H:i:s"), 
                            "document_type" => 1,
                            "transaction_type" => "CREDIT NOTE", /* It was COLLECTION. Changing it to PURCHASE as it is PURCHASE for B2B & B2C */
                            //"debit" => $parameters["debit"],  /*Payment made by B2B/B2C towards wholesaler company. But ticket is not being issued. So amount is being posed towards B2B/B2C's accounts */
                            "credit" => $price,  
                            "companyid" => $customer_companyid, 
                            "credited_accountid" => 7,  
                            "created_by"=>$ordered_booking['customer_userid'],
                            "narration"=>"Amount ($price) returned as traveller deleted (Booking.Id : $bookingid | Customer Id : $customerid)"
                        ));

                        log_message('debug', "[Search:delete_booking_customer] Amount returned | Voucher No: $voucher_no");
                    }
                }

                //update booking details as one person deleted
                if($ordered_booking) {
                    log_message('debug', "Company::delete_booking_customer - Deleting customer: Booking Id : $bookingid | Customer Id : $customerid");
                    $qty = intval($ordered_booking['qty']);
                    $newqty = $qty-1;
                    $admin_markup = floatval($ordered_booking['admin_markup']);
                    $markup = floatval($ordered_booking['markup']);
                    $discount = floatval($ordered_booking['discount']);

                    $oldtotal = floatval($ordered_booking['total']);
                    //$total = round(($price+$srvchg+$cgst+$sgst-$discount)*$newqty);
                    $total = round(($oldtotal/$qty)*$newqty);
                    $costprice = floatval($ordered_booking['costprice']);
                    
                    log_message('debug', "Company::delete_booking_customer - Updating booking: Qty: $qty | NewQty: $newqty | Price: $price | Admin.Markup: $admin_markup | Total: $total");

                    if($process_db_interaction) {
                        $result = $this->Search_Model->update('bookings_tbl', array(
                            'total' => $total,
                            'qty' => $newqty,
                            'adult' => $newqty
                        ), array('id' => $bookingid));
                        log_message('debug', "Company::delete_booking_customer - Updating booking: Booking Id: $bookingid | Booking update result: $result");
                    }
                }
            }
        }
        catch(Exception $ex) {
            $flg = false;
            log_message('error', 'Company::delete_booking_customer - $ex');
        }

        $this->set_response($flg, REST_Controller::HTTP_OK);
    }

    public function save_tickets_post() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $tickets = json_decode($stream_clean, true);

        $idx = 1;
        $feedbacks = array();

        try
        {
            foreach ($tickets as $ticket) {
                if(!isset($ticket['id'])) {
                    // unset($ticket['id']);
                    $feedbacks[] = array('idx' => $idx++, 'insert - feedback' => $this->Search_Model->save('tickets_tbl', $ticket));
                } else {
                    $feedbacks[] = array('idx' => $idx++, 'update - feedback' => $this->Search_Model->update('tickets_tbl', $ticket, array('id' => $ticket['id'])));
                }
            }
        }
        catch(Exception $ex) {
            // $bookings = array();
        }

        $this->set_response($feedbacks, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function wallet_transactions_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);

        try {
            $wallet_transactions = $this->User_Model->get_wallet_transactions($payload);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($wallet_transactions, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function settle_wallet_transaction_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);

        try {
            $status = $this->User_Model->settle_wallet_transaction($payload);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($status, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function statistics_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
        $stats = array();

        try {
            $stats['stats'] = $this->Search_Model->statistics($payload);

            $stats['historical_sales'] = $this->Search_Model->historical_sales($payload);
            $stats['inventory_circle'] = $this->Search_Model->inventory_circle($payload);
            $stats['inventory_search'] = $this->Search_Model->inventory_search($payload);

        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($stats, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function pnrsearch_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
        $result = array();

        try {
            $result = $this->Search_Model->pnr_search($payload);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function save_logo_post($companyid) {
        $company = $this->Admin_Model->get_company($companyid);
        if($company && count($company)>0) {
            $company = $company[0];
        }

        $result = array();
        if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $file = $_FILES['logo'];
            $path = realpath('./upload').DIRECTORY_SEPARATOR;
            log_message('debug', "Logo upload to real path $path");
            $path_parts = pathinfo($file['tmp_name']);
            $newfilepath = '';
            $filename = '';

            if(strpos($file['type'], 'image')>=0) {
                if(!file_exists($path.$file['name'])) {
                    log_message('debug', "New logo file moved from ".$file['tmp_name']." to ".$path.$file['name']);
                    move_uploaded_file($file['tmp_name'], $path.$file['name']);
                    $newfilepath = $path.$file['name'];
                    $filename = $file['name'];
                }
                else {
                    move_uploaded_file($file['tmp_name'], $path.$company['code'].'.'.$path_parts['extension']);
                    $newfilepath = $path.$company['code'].'.'.$path_parts['extension'];
                    $filename = $company['code'].'.'.$path_parts['extension'];
                }

                if($newfilepath !== '') {
                    $flag = $this->Search_Model->update('attributes_tbl', array('datavalue' => $filename), array('companyid' => $companyid, 'code' => 'logo'));
                    if($flag) {
                        $result['code'] = 200;
                        $result['message'] = "Logo file uploaded and processed successfully";
                        $result['data'] = ['companyid' => $companyid, 'name' => $filename, 'type' => $file['type'], 'error' => $file['error'], 'size' => $file['size']];
                    }
                    else {
                        $result['code'] = 504;
                        $result['message'] = "Unable to update record to company";
                        $result['data'] = ['companyid' => $companyid, 'name' => $filename, 'type' => $file['type'], 'error' => $file['error'], 'size' => $file['size']];
                    }
                }
                else {
                    $result['code'] = 503;
                    $result['message'] = "Sorry unable to process the uploaded image file";
                    $result['data'] = ['companyid' => $companyid, 'name' => $filename, 'type' => $file['type'], 'error' => $file['error'], 'size' => $file['size']];
                }
            }
            else {
                $result['code'] = 502;
                $result['message'] = "Uploaded file must be an image file";
                $result['data'] = ['companyid' => $companyid, 'name' => $file['name'], 'type' => $file['type'], 'error' => $file['error'], 'size' => $file['size']];
            }
        }
        else {
            $result['code'] = 501;
            $result['message'] = "File not uploaded or some error occured during upload. File must be an image";
            $result['data'] = [];
        }

        try {
            //$result = $this->Search_Model->pnr_search($payload);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function save_generalinfo_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
        $result = array();

        try {
            if($payload && intval($payload['id'])>0) {
                $id = intval($payload['id']);
                //update company_tbl
                $result = $this->Search_Model->update('company_tbl', array(
                    'name' => $payload['display_name'],
                    'display_name' => $payload['display_name'],
                    'address' => $payload['address'],
                    'state' => intval($payload['state']),
                    'country' => intval($payload['country']),
                    'primary_user_id' => intval($payload['primary_user_id']), /*We also need to update in user_tbl against this user id*/
                    'gst_no' => $payload['gst'],
                    'pan' => $payload['pan'],
                    'pin' => $payload['pin'],
                    'active' => $payload['active']?1:0,
                ), array('id' => $id));

                //update attributes_tbl table
                //Site_Title
                $result = $this->Search_Model->update('attributes_tbl', array('datavalue' => $payload['display_name']), array('companyid' => $id, 'code' => 'site_title'));
                //Phone Number
                $result = $this->Search_Model->update('attributes_tbl', array('datavalue' => $payload['phone']), array('companyid' => $id, 'code' => 'phone_no'));
                //Fax
                $result = $this->Search_Model->update('attributes_tbl', array('datavalue' => $payload['fax']), array('companyid' => $id, 'code' => 'fax'));
                //Email
                $result = $this->Search_Model->update('attributes_tbl', array('datavalue' => $payload['email']), array('companyid' => $id, 'code' => 'email'));
                //facebook_link
                $result = $this->Search_Model->save_attribute('attributes_tbl', array('datavalue' => $payload['facebook_link'], 'updated_by' => intval($payload['primary_user_id']), 'updated_on' => date('Y-m-d H:i:s')), 
                    array('code' => 'facebook_link', 'name' => 'Facebook Link', 'display_name' => 'Facebook Link', 'category' => 'General', 'datatype' => 'string', 'datavalue' => $payload['facebook_link'], 'target_object_type' => 'company', 'target_object_id' => $id, 'active' => 1, 'companyid' => $id, 'created_by' => intval($payload['primary_user_id'])), 
                    array('companyid' => $id, 'code' => 'facebook_link'));

                //twitter_link
                $result = $this->Search_Model->save_attribute('attributes_tbl', array('datavalue' => $payload['twitter_link'], 'updated_by' => intval($payload['primary_user_id']), 'updated_on' => date('Y-m-d H:i:s')), 
                    array('code' => 'twitter_link', 'name' => 'Twitter Link', 'display_name' => 'Twitter Link', 'category' => 'General', 'datatype' => 'string', 'datavalue' => $payload['twitter_link'], 'target_object_type' => 'company', 'target_object_id' => $id, 'active' => 1, 'companyid' => $id, 'created_by' => intval($payload['primary_user_id'])), 
                    array('companyid' => $id, 'code' => 'twitter_link'));

                //youtube_link
                $result = $this->Search_Model->save_attribute('attributes_tbl', array('datavalue' => $payload['youtube_link'], 'updated_by' => intval($payload['primary_user_id']), 'updated_on' => date('Y-m-d H:i:s')), 
                    array('code' => 'youtube_link', 'name' => 'Youtube Link', 'display_name' => 'Youtube Link', 'category' => 'General', 'datatype' => 'string', 'datavalue' => $payload['youtube_link'], 'target_object_type' => 'company', 'target_object_id' => $id, 'active' => 1, 'companyid' => $id, 'created_by' => intval($payload['primary_user_id'])), 
                    array('companyid' => $id, 'code' => 'youtube_link'));

                //pinterest_link
                $result = $this->Search_Model->save_attribute('attributes_tbl', array('datavalue' => $payload['pinterest_link'], 'updated_by' => intval($payload['primary_user_id']), 'updated_on' => date('Y-m-d H:i:s')), 
                    array('code' => 'pinterest_link', 'name' => 'Pinterest Link', 'display_name' => 'Pinterest Link', 'category' => 'General', 'datatype' => 'string', 'datavalue' => $payload['pinterest_link'], 'target_object_type' => 'company', 'target_object_id' => $id, 'active' => 1, 'companyid' => $id, 'created_by' => intval($payload['primary_user_id'])), 
                    array('companyid' => $id, 'code' => 'pinterest_link'));

                //instagram_link
                $result = $this->Search_Model->save_attribute('attributes_tbl', array('datavalue' => $payload['instagram_link'], 'updated_by' => intval($payload['primary_user_id']), 'updated_on' => date('Y-m-d H:i:s')), 
                    array('code' => 'instagram_link', 'name' => 'Instagram Link', 'display_name' => 'Instagram Link', 'category' => 'General', 'datatype' => 'string', 'datavalue' => $payload['instagram_link'], 'target_object_type' => 'company', 'target_object_id' => $id, 'active' => 1, 'companyid' => $id, 'created_by' => intval($payload['primary_user_id'])), 
                    array('companyid' => $id, 'code' => 'instagram_link'));

                //map
                $result = $this->Search_Model->save_attribute('attributes_tbl', array('datavalue' => $payload['map'], 'updated_by' => intval($payload['primary_user_id']), 'updated_on' => date('Y-m-d H:i:s')), 
                    array('code' => 'map', 'name' => 'Google Map', 'display_name' => 'Google Map', 'category' => 'General', 'datatype' => 'string', 'datavalue' => $payload['map'], 'target_object_type' => 'company', 'target_object_id' => $id, 'active' => 1, 'companyid' => $id, 'created_by' => intval($payload['primary_user_id'])), 
                    array('companyid' => $id, 'code' => 'map'));

                //Change Admin of the account
                //We should not make all existing admin as non-admin
                //We can have new admin but can't have only one admin throught the company
                //$result = $this->Search_Model->update('user_tbl', array('is_admin' => 0), array('companyid' => $id));
                $result = $this->Search_Model->update('user_tbl', array('is_admin' => 1), array('id' => intval($payload['primary_user_id']), 'companyid' => $id));

                $company = $this->Search_Model->get('company_tbl', array('id' => $id));
                if($company && count($company) > 0) {
                    $company = $company[0];
                }

                $result = array();
                $result['code'] = 200;
                $result['message'] = 'Record saved successfully';
                $result['data'] = $company;
            }
            else {
                $result = array();
                $result['code'] = 501;
                $result['message'] = 'Record could not be saved as invalid primary id passed';
                $result['data'] = [];
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function save_bankdetails_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
        $result = array();

        try
        {
            $companyid = $payload['companyid'];
            $bankdetails = $payload['bankdetails'];

            $company = $this->Admin_Model->get_company($companyid);
            if($company && count($company)>0) {
                $company = $company[0];
            }
    
            if(intval($companyid) > 0) {
                $result = $this->Search_Model->save_attribute('attributes_tbl', array('datavalue' => json_encode($bankdetails), 'updated_by' => intval($company['primary_user_id']), 'updated_on' => date('Y-m-d H:i:s')), 
                    array('code' => 'bank_accounts', 'name' => 'Bank Accounts', 'display_name' => 'Bank Accounts', 'category' => 'Financial', 'datatype' => 'object', 'datavalue' => json_encode($bankdetails), 'target_object_type' => 'company', 'target_object_id' => $companyid, 'active' => 1, 'companyid' => $companyid, 'created_by' => intval($company['primary_user_id'])), 
                    array('companyid' => $companyid, 'code' => 'bank_accounts'));
            }

            $company = $this->Admin_Model->get_company($companyid);
            if($company && count($company)>0) {
                $company = $company[0];
            }
            
            $result = array();
            $result['code'] = 200;
            $result['message'] = 'Bank details saved successfully';
            $result['data'] = $company;
        }
        catch(Exception $ex) {
            log_message('error', $ex);
            $result = array();
            $result['code'] = 501;
            $result['message'] = $ex;
            $result['data'] = [];
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function clone_ticket_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
        $result = array();
      
        try
        {
            $companyid = isset($payload['companyid'])?$payload['companyid']:-1;
            $company = $this->Admin_Model->get_company($companyid);
            if($company && count($company)>0) {
                $company = $company[0];
            }
            $current_userid = isset($payload['current_userid'])?$payload['current_userid']:-1;
            $ticketid = isset($payload['ticketid'])?intval($payload['ticketid']):-1;
            $flight_no = isset($payload['flight_no'])?$payload['flight_no']:'';
            $no_of_person = isset($payload['no_of_person'])?intval($payload['no_of_person']):1;
            $price = isset($payload['price'])?floatval($payload['price']):0.00;
            $tag = isset($payload['tag'])?$payload['tag']:'';

            if($companyid>0 && $current_userid>0 && $ticketid>0 && $price>0) {
                $ticket = $this->Search_Model->get('tickets_tbl', array('id' => $ticketid));
                if($ticket && count($ticket)>0) {
                    $ticket = $ticket[0];
                }

                $dept_date_time = date('Y-m-d H:i:s', strtotime($ticket['departure_date_time'].' '.$ticket['departure_date_time'].':00'));
                $arrv_date_time = date('Y-m-d H:i:s', strtotime($ticket['arrival_date_time'].' '.$ticket['arrival_date_time'].':00'));
        
                $result = $this->Search_Model->clone_ticket(intval($ticket['id']), $companyid, array(
                    'current_userid' => intval($current_userid),
                    'flight_no' => $flight_no,
                    'no_of_person' => $no_of_person,
                    'price' => $price,
                    'tag' => $tag,
                    'departure_date_time' => $ticket['departure_date_time'],
                    'arrival_date_time' => $ticket['arrival_date_time'],
                    'booking_freeze_by' => $ticket['departure_date_time']
                ));    
            }
            else {
                $result = array('code' => 501, 'message' => "Invalid company and user id passed or Other posted value is invalid", 'data' => []);
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
            $result = array();
            $result['code'] = 501;
            $result['message'] = $ex;
            $result['data'] = [];
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function create_company_post() {
        $payload = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($payload, true);
        $result = array();

        try
        {
            $this->Search_Model->db->trans_begin();

            $company = $this->create_new_company($payload);
            $primary_user = $this->create_user($company, $payload);
            $result = $this->add_default_accounts($company, $primary_user, $payload);
            $default_rate_plan = $this->add_rateplan($company, $primary_user, $payload);
            $wallet = $this->add_wallet($company, $primary_user, $payload);
            $attributes = $this->add_company_attributes($company, $primary_user, $payload);

            $result = array();
            $result['code'] = 200;
            $result['message'] = 'Company created successfully';
            $result['data'] = array('company' => $company, 'primary_user' => $primary_user, 'rateplan' => $default_rate_plan, 'wallet' => $wallet, 'company_attribute' => $attributes);
            
            $this->Search_Model->db->trans_complete();
        }
        catch(Exception $ex) {
            $this->Search_Model->db->trans_rollback();
            log_message('error', $ex);
            $result = array();
            $result['code'] = 501;
            $result['message'] = $ex;
            $result['data'] = [];
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function upsert_tickets_post() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $tickets = json_decode($stream_clean, true);

        $idx = 1;
        $feedbacks = array();

        try
        {
            $cities = $this->Search_Model->get('city_tbl', null);
            $airlines = $this->Search_Model->get('airline_tbl', null);
            $companies = $this->Search_Model->get('company_tbl', array('active' => 1));

            log_message('debug', 'No of Ticket Posted => '.(count($tickets)));

            foreach ($tickets as $ticket) {
                if($this->is_ticket_valid($ticket)) {
                    try
                    {
                        log_message('debug', 'Saving Ticket => '.json_encode($ticket));
                        $current_ticket = $this->Search_Model->get('tickets_tbl', array('ticket_no' => "'".$ticket['ticket_no']."'"));
                        if($current_ticket && is_array($current_ticket) && count($current_ticket)>0) {
                            $current_ticket = $current_ticket[0];

                            // $current_ticket['departure_date_time'] = date("Y-m-d H:i:s", strtotime($ticket['departure_date_time']));
                            // $current_ticket['arrival_date_time'] = date("Y-m-d H:i:s", strtotime($ticket['arrival_date_time']));
                            // $current_ticket['booking_freeze_by'] = date("Y-m-d H:i:s", strtotime($ticket['departure_date_time']));

                            $current_ticket['no_of_person'] = intval($ticket['no_of_person']);
                            $current_ticket['max_no_of_person'] = intval($ticket['no_of_person']);
                            $current_ticket['availibility'] = intval($ticket['no_of_person']);
                            $current_ticket['adult_count'] = intval($ticket['no_of_person']);
                            $current_ticket['available'] = intval($ticket['no_of_person'])>0 ? 'YES' : 'NO';
                            $current_ticket['last_sync_key'] = $ticket['runid'];
                            $current_ticket['flight_no'] = $ticket['flight_code'];
                            if(isset($current_ticket['pnr']) && $current_ticket['pnr'] === '') {
                                $current_ticket['pnr'] = $ticket['pnr'];
                            }
                            $current_ticket['price'] = floatval($ticket['price']);
                            $current_ticket['total'] = floatval($ticket['price']);
                            $current_ticket['remarks'] = isset($ticket['remarks']) ? $ticket['remarks'] : '';
                            $company = search_array_item($companies, 'id', $ticket['companyid']);
                            if($company && is_array($company) && count($company)>0 && isset($company['id'])) {
                                $current_ticket['updated_by'] =  intval($company['primary_user_id']);
                            }
                            
                            log_message('debug', 'Updating existing Ticket => '.json_encode($current_ticket));
                        }
                        else {
                            //new ticket
                            $current_ticket = [];
                            $company = search_array_item($companies, 'id', $ticket['companyid']);
                            if($company && is_array($company) && count($company)>0 && isset($company['id'])) {
                                $current_ticket['companyid'] =  intval($company['id']);
                                $current_ticket['created_by'] =  intval($company['primary_user_id']);
                                $current_ticket['user_id'] =  intval($company['primary_user_id']);
                            }
                            $current_ticket['source'] = -1;
                            $current_ticket['destination'] = -1;
                            $current_ticket['airline'] = -1;
                            $city = search_array_item($cities, 'code', $ticket['source_city']);
                            if($city && is_array($city) && count($city)>0 && isset($city['id'])) {
                                $current_ticket['source'] =  intval($city['id']);
                            }
                            $city = search_array_item($cities, 'code', $ticket['destination_city']);
                            if($city && is_array($city) && count($city)>0 && isset($city['id'])) {
                                $current_ticket['destination'] =  intval($city['id']);
                            }
                            $airline = search_array_item($airlines, 'aircode', $ticket['airline']);
                            if($airline && is_array($airline) && count($airline)>0 && isset($airline['id'])) {
                                $current_ticket['airline'] =  intval($airline['id']);
                            }

                            $current_ticket['ticket_no'] = $ticket['ticket_no'];
                            $current_ticket['departure_date_time'] = date("Y-m-d H:i:s", strtotime($ticket['departure_date_time']));
                            $current_ticket['arrival_date_time'] = date("Y-m-d H:i:s", strtotime($ticket['arrival_date_time']));
                            $current_ticket['booking_freeze_by'] = date("Y-m-d H:i:s", strtotime($ticket['departure_date_time']));
                            $current_ticket['terminal'] = 'NA';
                            $current_ticket['terminal1'] = 'NA';
                            $current_ticket['sale_type'] = 'request';
                            $current_ticket['refundable'] = 'N';
                            $current_ticket['approved'] = 1;
                            $current_ticket['admin_markup'] = 300.00;
                            $current_ticket['no_of_stops'] = 0;
                            $current_ticket['stops_name'] = 'NA';
                            $current_ticket['remarks'] = isset($ticket['remarks']) ? $ticket['remarks'] : '';
                            $current_ticket['trip_type'] = $ticket['trip_type'];
                            $current_ticket['tag'] = 'Synced via Google Sheet';
                            $current_ticket['no_of_person'] = intval($ticket['no_of_person']);
                            $current_ticket['max_no_of_person'] = intval($ticket['no_of_person']);
                            $current_ticket['availibility'] = intval($ticket['no_of_person']);
                            $current_ticket['adult_count'] = intval($ticket['no_of_person']);
                            $current_ticket['available'] = intval($ticket['no_of_person'])>0 ? 'YES' : 'NO';
                            $current_ticket['last_sync_key'] = $ticket['runid'];
                            $current_ticket['flight_no'] = $ticket['flight_code'];
                            $current_ticket['aircode'] = $ticket['aircode'];
                            $current_ticket['data_collected_from'] = $ticket['data_collected_from'];
                            $current_ticket['class'] = $ticket['class'];
                            $current_ticket['pnr'] = $ticket['pnr'];
                            $current_ticket['price'] = floatval($ticket['price']);
                            $current_ticket['total'] = floatval($ticket['price']);

                            log_message('debug', 'Assing new Ticket => '.json_encode($current_ticket));
                        }

                        if($current_ticket && is_array($current_ticket) && count($current_ticket)>0 && intval($current_ticket['source'])>0 
                            && intval($current_ticket['destination'])>0 && intval($current_ticket['airline'])>0) {
                            if(isset($current_ticket['id']) && intval($current_ticket['id'])>0) {
                                $return_value = $this->Search_Model->update('tickets_tbl', $current_ticket, array('id' => intval($current_ticket['id'])));
                                log_message('debug', "Ticket Updated => $return_value");
                                $feedbacks[] = array('mode' => 'update', 'id' => $return_value, 'ticket' => $current_ticket);
                            }
                            else {
                                $return_value = $this->Search_Model->save('tickets_tbl', $current_ticket);
                                log_message('debug', "Ticket Inserted => $return_value");
                                $feedbacks[] = array('mode' => 'insert', 'id' => $return_value, 'ticket' => $current_ticket);
                            }
                        }
                    }
                    catch(Exception $ex) {
                        log_message('error', "upsert tickets Error => $ex");
                    }
                }
                else {
                    log_message('debug', 'Ignoring Ticket => '.json_encode($ticket));
                }
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($feedbacks, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function get_linked_gsheets_get($companyid=-1, $status=1) {
        $sheets = [];

        if($status === '')
            $status = 1;
        else 
            $status = intval($status);

        try
        {
            // [
            //     {"id": 1, "name": "gsheet of bookmyfly", "sheetid": "1B0RfMO6cuT_MAuCj9m6nuqugl09VNLYmFxdbbfOU9qs", "status": 1, "sourcecode": "bmf", "target_companyid": 1},
            //     {"id": 2, "name": "gsheet of radharani holidays", "sheetid": "1QY_t4LkLuZMO_DlLjVaeThHvXW8B9IAjs47FNGTpeY8", "status": 1, "sourcecode": "rrh", "target_companyid": 1}
            // ]            
            $companyid = intval($companyid);
            if($companyid>0) {
                if($status>-1) {
                    $sheets = $this->Search_Model->get('linked_gsheets_tbl', array('status' => $status, 'target_companyid' => $companyid));
                }
                else {
                    $sheets = $this->Search_Model->get('linked_gsheets_tbl', array('target_companyid' => $companyid));
                }
            }
            else {
                if($status>-1) {
                    $sheets = $this->Search_Model->get('linked_gsheets_tbl', array('status' => $status));
                }
                else {
                    $sheets = $this->Search_Model->get('linked_gsheets_tbl', array());
                }
            }
            log_message('debug', "Collecting inventory linked gSheets of $companyid => ".json_encode($sheets));
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        $this->set_response($sheets, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }

    public function change_gsheets_post($companyid = -1, $sheetid = -1) {
        $gsheet_update_payload = $this->security->xss_clean($this->input->raw_input_stream);
        $gsheet = json_decode($gsheet_update_payload, true);
        $flag = false;

        if($gsheet) {
            if(intval($gsheet['id'])>0) {
                $flag = $this->Search_Model->update('linked_gsheets_tbl', array(
                    'name' => $gsheet['name'],
                    'sheetid' => $gsheet['sheetid'],
                    'status' => (intval($gsheet['status'])>0 ? 1 : 0),
                    'sourcecode' => $gsheet['sourcecode'],
                    'target_companyid' => $companyid,
                    'sheet_url' => $gsheet['sheet_url'],
                ), array(
                    'id' => intval($gsheet['id'])
                ));
            }
            else {
                $flag = $this->Search_Model->save('linked_gsheets_tbl', array(
                    'name' => $gsheet['name'],
                    'sheetid' => $gsheet['sheetid'],
                    'status' => (intval($gsheet['status'])>0 ? 1 : 0),
                    'sourcecode' => $gsheet['sourcecode'],
                    'target_companyid' => $companyid,
                    'sheet_url' => $gsheet['sheet_url'],
                ));

                $gsheet['id'] = $flag ? $flag : -1;
            }
        }

        return $flag;
    }

    public function perform_wallet_transaction_post($userid = 0) {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $payload = json_decode($stream_clean, true);

        if($userid <= 0 || !isset($payload['user_id'])) return;

        try
        {
            $amount = isset($payload['amount']) ? floatval($payload['amount']) : 0;
            $dr_cr = isset($payload['dr_cr_type']) ? $payload['dr_cr_type'] : '';
            $amount = (($dr_cr === 'DR') ?  ($amount * -1) : $amount);
            $trans_ref_id = isset($payload['trans_ref_id']) ? $payload['trans_ref_id'] : '';
            $trans_docid = NULL;
            $narration = isset($payload['narration']) ? $payload['narration'] : '';

            $transactionid = $this->User_Model->perform_wallet_transaction(intval($payload['user_id']), array(
                "amount"=>$amount,
                "trans_type"=>$amount>0?'DR':'CR',
                "trans_ref_id"=>$trans_ref_id,
                "trans_ref_date"=>date("Y-m-d H:i:s"),
                "trans_ref_type"=>$amount>0 ?'CREDIT NOTE':'DEBIT NOTE',
                "trans_documentid" => $trans_docid,
                "narration" => $narration
            ));
            
            if($transactionid>-1) {
                $payload['transactionid'] = $transactionid;

                $voucher_no = $this->User_Model->perform_account_transaction(intval($payload['user_id']), $payload);

                $result = array();
                $result['code'] = 200;
                $result['message'] = `Wallet transaction successfully recorded : $transactionid | Voucher No: $voucher_no`;
                $result['data'] = ['transactionid' => $transactionid, 'voucher_no' => $voucher_no];
            }
            else {
                throw new Exception("Unable to save wallet transaction");
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
            $result = array();
            $result['code'] = 501;
            $result['message'] = $ex;
            $result['data'] = [];
        }

        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    #region helper method    
    private function is_ticket_valid($ticket) {
        $flag = false;

        if($ticket && is_array($ticket) && count($ticket)>0) {
            $departure_date_time = '';
            $arrival_date_time = '';
            if(isset($ticket['departure_date_time'])) {
                $departure_date_time = strtotime($ticket['departure_date_time']);
            }
            if(isset($ticket['arrival_date_time'])) {
                $arrival_date_time = strtotime($ticket['arrival_date_time']);
            }

            if($ticket['flight_code'] != '' && $ticket['source_city'] != '' && $ticket['destination_city'] != '' && $departure_date_time>=strtotime('now') && $arrival_date_time>=strtotime('now')) {
                $flag = true;
            }
        }

        return $flag;
    }

    private function create_new_company($payload) {
        $company = array();
        try {
            $last_company_code = 'OXY0001';
            $companyies = $this->Search_Model->get('company_tbl', array('active' => 1));
            if($companyies && is_array($companyies) && count($companyies)>0) {
                $last_company = $companyies[count($companyies)-1];
                $last_company_code = intval(str_replace('OXY', '', $last_company['code']));
                if($last_company_code>0) {
                    $last_company_code = "OXY".str_pad($last_company_code+1,4,"0",STR_PAD_LEFT);
                }
            }

            $company['code'] = $last_company_code;
            $company['name'] = isset($payload['name'])?$payload['name']:'';
            $company['display_name'] = isset($payload['name'])?$payload['name']:'';
            $company['address'] = isset($payload['address'])?$payload['address']:'';
            $company['state'] = isset($payload['state'])?$this->get_state_code($payload['state']):-1;
            $company['country'] = isset($payload['country'])?$this->get_country_code($payload['country']):-1;
            $company['tenent_code'] = $company['code'].'100';
            $company['primary_user_id'] = -1;
            $company['gst_no'] = isset($payload['gst_no'])?$payload['gst_no']:'';
            $company['pan'] = isset($payload['pan'])?$payload['pan']:'';
            $company['type'] = 6;
            $company['created_by'] = 0;
            $company['created_on'] = date("Y-m-d H:i:s");
            $company['updated_by'] = 0;
            $company['updated_on'] = date("Y-m-d H:i:s");
            $company['active'] = 1;
            $company['parent_companyid'] = -1;
            $company['baseurl'] = isset($payload['baseurl'])?$payload['baseurl']:'';
            $company['pin'] = isset($payload['pin'])?$payload['pin']:'';

            //now lets save the company
            log_message('debug', 'Company:create_company | Creating company => '.json_encode($company));

            $companyid = $this->Search_Model->save('company_tbl', $company);
            if($companyid>0) {
                $company = $this->Search_Model->get('company_tbl', array('id' => $companyid));
                if($company && is_array($company) && count($company)>0) {
                    $company = $company[0];

                    $result = $this->add_default_service($company);

                    log_message('debug', 'Company created -> New Company => '.json_encode($company)." | Default service added ? $result");
                }
            }
            else {
                log_message('debug', 'Can`t create company => '.json_encode($company));
            }
        }   
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        return $company;
    }

    private function add_default_service($company) {
        try
        {
            $result = $this->Search_Model->save('company_services_tbl', array('serviceid' => 1, 'companyid' => intval($company['id']), 'active' => 1));
            log_message('debug', 'Default service has been provissioned to company : '.$company['name']);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        return $result;
    }

    private function add_default_accounts($company, $user, $payload) {
        try
        {
            $result = $this->Search_Model->save('accounts_tbl', array(
                'account_head_code' => 'ACC0003', 
                'account_head_name' => 'Ticket Sales', 
                'account_groupid' => 7, 
                'parent_accountid' => -1, 
                'configuration_data' => '{}', 
                'debit' => 0, 
                'credit' => 0,
                'companyid' => intval($company['id']), 
                'active' => 1,
                'created_by' => intval($user['id'])
            ));
            log_message('debug', 'Default accounts has been provissioned to company : '.$company['name']);
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        return $result;
    }

    private function add_rateplan($company, $user, $payload) {
        $rateplan = array();
        try
        {
            $rateplanid = $this->Search_Model->save('rateplan_tbl', array(
                'name' => 'Default Rateplan', 
                'display_name' => 'Default Rateplan', 
                'assigned_to' => 14, 
                'companyid' => intval($company['id']), 
                'active' => 1,
                'default' => 1,
                'created_by' => intval($user['id'])
            ));

            if($rateplanid>0) {
                log_message('debug', 'Default rateplan has been provissioned to company : '.$company['name']);
                $rateplan = $this->Search_Model->get('rateplan_tbl', array('id' => $rateplanid));
                if($rateplan && is_array($rateplan) && count($rateplan)>0) {
                    $rateplan = $rateplan[0];
                }

                //insert rateplan details
                $idx = 1;
                //1. markup
                $rateplandetail = $this->Search_Model->save('rateplan_detail_tbl', array(
                    'rateplanid' => $rateplanid,
                    'companyid' => intval($company['id']), 
                    'serialno' => $idx++,
                    'head_name' => 'Mark Up', 
                    'head_code' => 'markup',
                    'amount' => 200.00,
                    'amount_type' => 1,
                    'operation' => 1,
                    'calculation' => '',
                    'active' => 1,
                    'created_by' => intval($user['id'])
                ));

                //2. srvchg
                $rateplandetail = $this->Search_Model->save('rateplan_detail_tbl', array(
                    'rateplanid' => $rateplanid,
                    'companyid' => intval($company['id']), 
                    'serialno' => $idx++,
                    'head_name' => 'Service Charge', 
                    'head_code' => 'srvchg',
                    'amount' => 0.00,
                    'amount_type' => 1,
                    'operation' => 1,
                    'calculation' => '',
                    'active' => 1,
                    'created_by' => intval($user['id'])
                ));

                //3. SGST
                $rateplandetail = $this->Search_Model->save('rateplan_detail_tbl', array(
                    'rateplanid' => $rateplanid,
                    'companyid' => intval($company['id']), 
                    'serialno' => $idx++,
                    'head_name' => 'SGST', 
                    'head_code' => 'sgst',
                    'amount' => 0.00,
                    'amount_type' => 2,
                    'operation' => 1,
                    'calculation' => '{srvchg}',
                    'active' => 1,
                    'created_by' => intval($user['id'])
                ));

                //4. CGST
                $rateplandetail = $this->Search_Model->save('rateplan_detail_tbl', array(
                    'rateplanid' => $rateplanid,
                    'companyid' => intval($company['id']), 
                    'serialno' => $idx++,
                    'head_name' => 'CGST', 
                    'head_code' => 'cgst',
                    'amount' => 0.00,
                    'amount_type' => 2,
                    'operation' => 1,
                    'calculation' => '{srvchg}',
                    'active' => 1,
                    'created_by' => intval($user['id'])
                ));

                //5. IGST
                $rateplandetail = $this->Search_Model->save('rateplan_detail_tbl', array(
                    'rateplanid' => $rateplanid,
                    'companyid' => intval($company['id']), 
                    'serialno' => $idx++,
                    'head_name' => 'IGST', 
                    'head_code' => 'igst',
                    'amount' => 0.00,
                    'amount_type' => 2,
                    'operation' => 1,
                    'calculation' => '{srvchg}',
                    'active' => 1,
                    'created_by' => intval($user['id'])
                ));

                $rateplandetail = $this->Search_Model->get('rateplan_detail_tbl', array('rateplanid' => $rateplanid));
                if($rateplandetail && is_array($rateplandetail) && count($rateplandetail)>0) {
                    $rateplan['details'] = $rateplandetail;
                }                
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        return $rateplan;
    }

    private function create_user($company, $payload) {
        $user = array();
        try
        {
            $last_user_code = 'USR0001';
            $users = $this->Search_Model->get('user_tbl', array('active' => 1));
            if($users && is_array($users) && count($users)>0) {
                $last_user = $users[count($users)-1];
                $last_user_code = intval($last_user['id']);
                if($last_user_code>0) {
                    $last_user_code = "USR".str_pad($last_user_code+1,4,"0",STR_PAD_LEFT);
                }
            }

            $user['user_id'] = $last_user_code;
            $user['name'] = isset($payload['user_name'])?$payload['user_name']:'';
            $user['profile_image'] = isset($payload['profile_image'])?$payload['profile_image']:'';
            $user['email'] = isset($payload['email'])?$payload['email']:'';
            $user['mobile'] = isset($payload['mobile'])?$payload['mobile']:'';
            $user['address'] = isset($payload['address'])?$payload['address']:'';
            $user['state'] = intval($company['state']);
            $user['country'] = intval($company['country']);
            $user['password'] = '123456';
            $user['is_supplier'] = 1;
            $user['is_customer'] = 1;
            $user['active'] = 1;
            $user['type'] = 'EMP';
            $user['credit_ac'] = 1;
            $user['uid'] = uniqid(time(),FALSE);
            $user['doj'] = date("Y-m-d H:i:s");
            $user['companyid'] = intval($company['id']);
            $user['permission'] = 4294967295;
            $user['is_admin'] = 1;
            $user['pan'] = $company['pan'];
            $user['gst'] = $company['gst_no'];
            $user['created_by'] = 0;
            $user['created_on'] = date("Y-m-d H:i:s");
            $user['updated_by'] = 0;
            $user['updated_on'] = date("Y-m-d H:i:s");

            //now lets save the user
            log_message('debug', 'Company:create_user | Creating user => '.json_encode($user));

            $userid = $this->Search_Model->save('user_tbl', $user);
            if($userid>0) {
                $result = $this->Search_Model->update('company_tbl', array('primary_user_id' => $userid, 'created_by' => $userid), array('id' => intval($company['id'])));
                $user = $this->Search_Model->get('user_tbl', array('id' => $userid));
                if($user && is_array($user) && count($user)>0) {
                    $user = $user[0];

                    log_message('debug', 'User created -> New User => '.json_encode($company));
                }
            }
            else {
                log_message('debug', 'Can`t create user => '.json_encode($user));
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        return $user;
    }

    private function add_wallet($company, $user, $payload) {
        $system_wallet = array();
        try
        {
            $wallet_id= $this->Search_Model->save('system_wallets_tbl', array(
                'name' => intval($company['code']).'_wallet_sys_'.intval($user['id']), 
                'display_name' => 'Wallet by System', 
                'companyid' => intval($company['id']), 
                'userid' => 0,
                'sponsoring_companyid' => -1,
                'allowed_transactions' => "[{      trans_type: 'Bank Transfer',      trans _code: '0001',      },{      trans_type: 'On-line',      trans _code: '0002',            gateways: ['paytm', 'payu', 'atom', 'razorpay']}]", 
                'wallet_account_code' => 'WL_'.intval($user['id']),
                'balance' => 0,
                'type' => 1,
                'status' => 1,
                'created_by' => intval($user['id'])
            ));
            log_message('debug', 'Default system wallet has been provissioned to company : '.$company['name']);

            if($wallet_id>0) {
                $system_wallet = $this->Search_Model->get('system_wallets_tbl', array('id' => $wallet_id));
                if($system_wallet && is_array($system_wallet) && count($system_wallet)>0) {
                    $system_wallet = $system_wallet[0];
                }
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        return $system_wallet;
    }

    private function add_company_attributes($company, $user, $payload) {
        $attributes = array();
        try
        {
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'site_title',
                'name' => 'Site Title',
                'display_name' => 'Site Title',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $company['name'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'phone_no',
                'name' => 'Phone Number',
                'display_name' => 'Phone Number',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $user['mobile'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'fax',
                'name' => 'Fax',
                'display_name' => 'Fax',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $payload['fax'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //email
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'email',
                'name' => 'Email',
                'display_name' => 'Email',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $user['email'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));
            
            //logo
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'logo',
                'name' => 'Logo',
                'display_name' => 'Logo',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $payload['logo'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //bank_name
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'bank_name',
                'name' => 'Bank Name',
                'display_name' => 'Bank Name',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $payload['bank_name'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //bank_branch
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'bank_branch',
                'name' => 'Branch',
                'display_name' => 'Branch',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $payload['bank_branch'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //acc_no
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'acc_no',
                'name' => 'A/C. Number',
                'display_name' => 'A/C. Number',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $payload['acc_no'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //ifsc
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'ifsc',
                'name' => 'IFSC',
                'display_name' => 'IFSC',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $payload['ifsc'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //acc_name
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'acc_name',
                'name' => 'A/C. NAME',
                'display_name' => 'A/C. NAME',
                'category' => 'General',
                'datatype' => 'string',
                'datavalue' => $payload['acc_name'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //bank_accounts
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'bank_accounts',
                'name' => 'Bank Accounts',
                'display_name' => 'Bank Accounts',
                'category' => 'General',
                'datatype' => 'object',
                'datavalue' => $payload['bank_accounts'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //payment_gateway
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'payment_gateway',
                'name' => 'Payment Gateway',
                'display_name' => 'Payment Gateway',
                'category' => 'General',
                'datatype' => 'object',
                'datavalue' => $payload['payment_gateway'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //configuration
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'configuration',
                'name' => 'Company Configurations',
                'display_name' => 'Company Configurations',
                'category' => 'General',
                'datatype' => 'object',
                'datavalue' => $payload['configuration'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            //api_integration
            $attribute = $this->Search_Model->save('attributes_tbl', array(
                'code' => 'api_integration',
                'name' => 'API Integration',
                'display_name' => 'API Integration',
                'category' => 'General',
                'datatype' => 'object',
                'datavalue' => $payload['api_integration'],
                'target_object_type' => 'company',
                'target_object_id' => intval($company['id']),
                'active' => 1,
                'companyid' => intval($company['id']),
                'created_by' => intval($user['id']) 
            ));

            if($attribute>0) {
                log_message('debug', 'Default company attributes has been provissioned to company : '.$company['name']);

                $attributes = $this->Search_Model->get('attributes_tbl', array('companyid' => intval($company['id'])));
                if($attributes && is_array($attributes) && count($attributes)>0) {
                    //$system_wallet = $system_wallet[0];
                }
            }
        }
        catch(Exception $ex) {
            log_message('error', $ex);
        }

        return $attributes;
    }

    private function get_state_code($statename) {
        $statecode = -1;
        $state = $this->Search_Model->get('metadata_tbl', array('datavalue' => "'$statename'", 'associated_object_type' => "'state'", 'active' => 1));
        if($state && is_array($state) && count($state)>0) {
            $state = $state[0];
            $statecode = intval($state['id']);
        }


        return $statecode;
    }

    private function get_country_code($countryname) {
        $countrycode = -1;
        $country = $this->Search_Model->get('metadata_tbl', array('datavalue' => "'$countryname'", 'associated_object_type' => "'country'", 'active' => 1));
        if($country && is_array($country) && count($country)>0) {
            $country = $country[0];
            $countrycode = intval($country['id']);
        }

        return $countrycode;
    }    
    #endregion
}

?>