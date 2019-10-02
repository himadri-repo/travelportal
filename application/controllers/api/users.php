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
class Users extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
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
    
    public function user_get($id=0)
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];

        //$id = $this->get('id');

        // If the id parameter doesn't exist return all the users
        $user = NULL;

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Invalid user id passed. No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
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
            $this->response([
                'status' => FALSE,
                'message' => 'Invalid user id passed.'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            //$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
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
        $companyid = $this->post('companyid');
        $users = $this->User_Model->get_users($companyid);

        $this->set_response($users, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code REST_Controller::HTTP_CREATED
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

        $this->set_response($message, REST_Controller::HTTP_OK); // NO_CONTENT (204) being the HTTP response code
    }

    public function currentuser_get($uuid) {
        //$current_user = $this->session->userdata("current_user");
        $current_user = $this->User_Model->getUserByUUID($uuid);

        if($current_user!=null) {
            $this->response($current_user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else {
            $this->response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function get_user_activities_post() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $posted_value = json_decode($stream_clean, true);
        $activities = [];

        if($posted_value!=null) {

            $cities = $this->User_Model->get('city_tbl', array());

            $user_activities = $this->User_Model->get_user_activities('search', $posted_value);

            foreach ($user_activities as $activity) {
                $user_activity = &$activity;

                $useragent = $user_activity['user_agent'];
                $device = 'Desktop';
                if($useragent!==NULL) {
                    $device = strpos(strtolower($useragent), 'mobile')>-1?'Mobile':'Desktop';
                }
                $user_activity['device'] = $device;
                $requested_on = (new DateTime($user_activity['requested_on']))->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $user_activity['requested_on'] = $requested_on->format('Y-m-d H:i:s');

                $la_time = new DateTimeZone('America/Los_Angeles');

                $posted_data = json_decode($user_activity['posted_data'], true);
                if($posted_data!=null) {
                    $source = intval($posted_data['source']);
                    $source_city_name = '';
                    $destination = intval($posted_data['destination']);
                    $destination_city_name = '';
                    $no_of_person = intval($posted_data['no_of_person']);
                    $travel_date = '';
                    if(isset($posted_data['departure_date'])) {
                        $travel_date = (new DateTime($posted_data['departure_date']))->format('Y-m-d');
                    }

                    foreach ($cities as $city) {
                        if(intval($city['id']) === $source) {
                            $source_city_name = $city['city'];
                        }
                        if(intval($city['id']) === $destination) {
                            $destination_city_name = $city['city'];
                        }
                        if($source_city_name!=='' && $destination_city_name!=='') {
                            break;
                        }
                    }

                    if($source_city_name!=='' && $destination_city_name!=='') {
                        $user_activity['source_city_name'] = $source_city_name;
                        $user_activity['destination_city_name'] = $destination_city_name;
                        $user_activity['no_of_person'] = $no_of_person;
                        $user_activity['travel_date'] = $travel_date;
                    }

                    $activities[] = $user_activity;
                }
            }
        }

        $this->set_response($activities, REST_Controller::HTTP_OK); // NO_CONTENT (204) being the HTTP response code
    }
}