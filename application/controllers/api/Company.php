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
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
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

            if (!empty($company))
            {
                $this->response($company[0], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
            foreach ($customers as $customer) {
                if(intval($customer['booking_id']) === intval($booking['id'])) {
                    $customer_list[] = $customer;
                }
            }
            $booking['customers'] = $customer_list;

            $ticket = $this->Search_Model->get_ticket(intval($booking['ticket_id']));
            if($ticket && count($ticket)>0)
                $booking['ticket'] = $ticket[0];
        }

        $this->set_response($bookings, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
    }
}

?>