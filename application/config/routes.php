<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Home_Controller';
$route['register'] = 'User_Controller/register';
$route['user/add-oneway-ticket'] = 'User_Controller/add_one_way';
$route['user/add-testimonial'] = 'User_Controller/add_testimonial';
$route['user/add-return-ticket'] = 'User_Controller/add_return_ticket';
$route['user/edit-ticket/(:num)'] = 'User_Controller/edit_ticket/$1';
$route['user/edit-testimonial/(:num)'] = 'User_Controller/edit_testimonial/$1';
$route['user/update-ticket'] = 'User_Controller/update_ticket';
$route['user/import-request-ticket'] = 'User_Controller/import_request_ticket';
$route['login'] = 'User_Controller/login';
$route['verify'] = 'User_Controller/verify';
$route['login-otp'] = 'User_Controller/login_otp';
$route['user/submit-ticket'] = 'User_Controller/submit_ticket';
$route['user/submit-testimonial'] = 'User_Controller/submit_testimonial';
$route['user'] = 'User_Controller';
$route['user/logout'] = 'User_Controller/logout';
$route['user/updatepnr'] = 'User_Controller/updatepnr';
$route['user/transaction'] = 'User_Controller/transaction';
$route['user/tickets'] = 'User_Controller/tickets';
$route['user/testimonials'] = 'User_Controller/testimonials';
$route['user/my-bookings'] = 'User_Controller/mybookings';
$route['user/booking-orders'] = 'User_Controller/bookingorders';
$route['user/booking-details/(:num)'] = 'User_Controller/booking_details/$1';
$route['user/edit-booking/(:num)'] = 'User_Controller/edit_booking/$1';
$route['user/cancel-request'] = 'User_Controller/cancelrequest';
$route['admin'] = 'Admin_Controller';
$route['404_override'] = 'Home_Controller/error';
$route['contact'] = 'Home_Controller/contact';
$route['faq'] = 'Home_Controller/faq';
$route['terms-and-conditions'] = 'Home_Controller/terms';
$route['images/random'] = 'Home_Controller/serveRandomImages';
$route['sendmail'] = 'Contact_Controller/send';
$route['translate_uri_dashes'] = FALSE;
