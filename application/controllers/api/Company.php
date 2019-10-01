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
                $feedbacks[] = array('idx' => $idx++, 'feedback' => $this->Search_Model->upsert_booking($booking, $selectedTicket, $originalbooking, $pricediffaction));
            }
        }
        catch(Exception $ex) {
            // $bookings = array();
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
                log_message('info', "Company::delete_booking_customer - Before Wallet Trans: Booking Id : $bookingid | Customer Id : $customerid | Bookings Details: ".json_encode($ordered_booking));
                $price = floatval($ordered_booking['costprice']);
                $srvchg = floatval($ordered_booking['srvchg']);
                $cgst = floatval($ordered_booking['cgst']);
                $sgst = floatval($ordered_booking['sgst']);
                $igst = floatval($ordered_booking['igst']);

                $refundprice = $price + $srvchg + $cgst + $sgst;
                log_message('info', "Company::delete_booking_customer - Wallet Trans Info: Booking Id : $bookingid | Customer Id : $customerid | Price : $price value will be credit or debited from wallet");

                log_message('info', "Company::delete_booking_customer - Wallet Trans Info(Changed): Booking Id : $bookingid | Customer Id : $customerid | Price : $refundprice | srvchg : $srvchg | cgst : $cgst | sgst : $sgst");

                if(intval($user_info['is_admin'])!==1 && $process_db_interaction) {
                    $transactionid = $this->User_Model->perform_wallet_transaction($ordered_booking['customer_userid'], array(
                        'amount'=>$price,
                        'trans_type'=>$price>0?'DR':'CR',
                        'trans_ref_id'=>$bookingid,
                        'trans_ref_date'=>date("Y-m-d H:i:s"),
                        'trans_ref_type'=>$price>0 ?'DEBIT NOTE':'CREDIT NOTE',
                        "trans_documentid" => $bookingid
                    ));
                    
                    log_message('info', "Company::delete_booking_customer - After Wallet Trans: Booking Id : $bookingid | Customer Id : $customerid | Wallet Trans Id: $transactionid");
                }

                //update booking details as one person deleted
                if($ordered_booking) {
                    log_message('info', "Company::delete_booking_customer - Deleting customer: Booking Id : $bookingid | Customer Id : $customerid");
                    $qty = intval($ordered_booking['qty']);
                    $newqty = $qty-1;
                    $admin_markup = floatval($ordered_booking['admin_markup']);
                    $markup = floatval($ordered_booking['markup']);
                    $discount = floatval($ordered_booking['discount']);

                    $oldtotal = floatval($ordered_booking['total']);
                    //$total = round(($price+$srvchg+$cgst+$sgst-$discount)*$newqty);
                    $total = round(($oldtotal/$qty)*$newqty);
                    $costprice = floatval($ordered_booking['costprice']);
                    
                    log_message('info', "Company::delete_booking_customer - Updating booking: Qty: $qty | NewQty: $newqty | Price: $price | Admin.Markup: $admin_markup | Total: $total");

                    if($process_db_interaction) {
                        $result = $this->Search_Model->update('bookings_tbl', array(
                            'total' => $total,
                            'qty' => $newqty,
                            'adult' => $newqty
                        ), array('id' => $bookingid));
                        log_message('info', "Company::delete_booking_customer - Updating booking: Booking Id: $bookingid | Booking update result: $result");
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
}

?>