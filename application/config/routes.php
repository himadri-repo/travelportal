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
$route['user/addtowallet'] = 'User_Controller/addtowallet';
$route['user/update_profile'] = 'User_Controller/update_profile';
$route['user/change_password'] = 'User_Controller/change_password';
$route['paymentgateway/response'] = 'User_Controller/pg_response';
$route['paymentgateway/response_atom'] = 'User_Controller/pg_response_atom';

$route['paymentgateway/booking_payu_response'] = 'Search/pre_booking_pg_response/payu';
$route['paymentgateway/booking_atom_response'] = 'Search/pre_booking_pg_response/atom';
/*********** TestNG ***********************************/

/*********** End of TestNG ***********************************/
/*********** Admin Page related routing ***************/
//$route['admin1'] = 'Admin_Controller';
//$route['admin1/([a-z]+)(.jpg|.JPG|.gif|.GIF|.doc|.DOC|.pdf|.PDF|.js|.css)$'] = "$1/id_$2";
//$route['admin1/([a-z0-9A-Z.]+)(.jpg|.JPG|.gif|.GIF|.doc|.DOC|.pdf|.PDF|.js|.css)$'] = 'Admin_Controller/rawfile/$1';
//$route['admin1/(:any)'] = 'Admin_Controller/rawfile/$1';
//$route['admin1/users'] = 'Admin_Controller/users';
$route['admin/get_users/(:num)'] = 'Admin_Controller/get_users/$1';
$route['admin/services'] = 'Admin_Controller/services';
$route['admin/tickets'] = 'Admin_Controller/tickets';
$route['admin/suppliers'] = 'Admin_Controller/suppliers';
$route['admin/wholesalers'] = 'Admin_Controller/wholesalers';
$route['admin/customer'] = 'Admin_Controller/customers';
/*********** End of Admin Page related routing ***************/
$route['404_override'] = 'Home_Controller/error';
$route['contact'] = 'Home_Controller/contact';
$route['faq'] = 'Home_Controller/faq';
$route['terms-and-conditions'] = 'Home_Controller/terms';
$route['images/random'] = 'Home_Controller/serveRandomImages';
$route['sendmail'] = 'Contact_Controller/send';
$route['translate_uri_dashes'] = FALSE;
/*********** API routing *************/
$route['api/users'] = 'api/Users/users';
$route['api/users/currentuser'] = 'api/Users/currentuser';
$route['api/users/(:num)'] = 'api/Users/user/$1';
$route['api/menus'] = 'api/Common/menus';
$route['api/user/(:any)'] = 'api/Users/token/$1';
$route['api/company/(:any)'] = 'api/Company/$1';
$route['api/wholesalers'] = 'api/Company/wholesalers';
$route['api/suppliers'] = 'api/Company/suppliers';
$route['api/customers'] = 'api/Company/customers';
$route['api/tickets'] = 'api/Company/tickets';
$route['api/company/open_tickets'] = 'api/Company/open_tickets';
$route['api/company/delete_booking_customer/(:num)/(:num)'] = 'api/Company/delete_booking_customer/$1/$2';
$route['api/customer/save'] = 'api/Company/customer';
$route['api/customer/(:num)/(:num)'] = 'api/Company/customer/$1/$2';
$route['api/admin/suppliers'] = 'api/Admin/suppliers';
$route['api/admin/wholesalers'] = 'api/Admin/wholesalers';
$route['api/admin/communications'] = 'api/Admin/communication_query';
$route['api/admin/communications/(:num)'] = 'api/Admin/communication_query/$1';
$route['api/admin/communication_detail/(:num)'] = 'api/Admin/communication_detail/$1';
$route['api/admin/messages/inbox/(:num)'] = 'api/Admin/messages_inbox/$1';
$route['api/admin/messages/outbox/(:num)'] = 'api/Admin/messages_outbox/$1';
$route['api/admin/message'] = 'api/Admin/message';
$route['api/admin/messagedetails'] = 'api/Admin/message_details';
$route['api/admin/message/read'] = 'api/Admin/message_read';
$route['api/admin/rateplans'] = 'api/Admin/rate_plan';
$route['api/admin/rateplans/(:num)'] = 'api/Admin/rate_plan_details/$1';
$route['api/company/(:num)/services'] = 'api/Company/services/$1';
$route['api/admin/wholesaler/save'] = 'api/Admin/save_wholesaler';
$route['api/admin/supplier/save'] = 'api/Admin/save_supplier';
$route['api/admin/rateplan/save'] = 'api/Admin/save_rateplan';
$route['api/admin/supplier/(:num)/wholesaler/(:num)'] = 'api/Admin/save_supplier_details/$1/$2';
$route['api/admin/wholesaler/(:num)/supplier/(:num)'] = 'api/Admin/save_wholesaler_details/$1/$2';
$route['api/metadata/(:any)/(:num)'] = 'api/Common/metadata/$1/$2';
$route['api/bookings/?(:num)?/?(:num)?'] = 'api/Company/bookings/$1/$2';
$route['api/query/bookings'] = 'api/Company/bookingsquery';
$route['api/query/bookings/payment_details'] = 'api/Company/payment_details';
$route['api/save/bookings'] = 'api/Company/upsert_bookings';
$route['api/company/ticket/(:num)'] = 'api/Company/getticket/$1';
$route['api/admin/cities'] = 'api/Admin/cities';
$route['api/admin/airlines'] = 'api/Admin/airlines';
$route['api/company/save/tickets'] = 'api/Company/save_tickets';
$route['api/company/wallet/transactions'] = 'api/Company/wallet_transactions';
$route['api/company/wallet/transactions/settle'] = 'api/Company/settle_wallet_transaction';
$route['api/users/activity'] = 'api/Users/get_user_activities';
$route['api/company/statistics'] = 'api/Company/statistics';
$route['api/query/pnr'] = 'api/Company/pnrsearch';
$route['api/save/pnr'] = 'api/Admin/save_pnr';
$route['api/save/company/generalinfo'] = 'api/Company/save_generalinfo';
$route['api/save/company/logo/(:num)'] = 'api/Company/save_logo/$1';
$route['api/admin/accounts'] = 'api/Admin/accounts';
$route['api/save/company/bankdetails'] = 'api/Company/save_bankdetails';
$route['api/company/ticket/clone'] = 'api/Company/clone_ticket';
$route['api/create/company'] = 'api/Company/create_company';
$route['api/admin/capture/user_query'] = 'api/Admin/capture_user_query';
$route['api/users/queries'] = 'api/Users/user_query';
$route['api/company/tickets/import'] = 'api/Company/upsert_tickets';
$route['api/company/links/gsheet/?(:num)?/?(:any)?'] = 'api/Company/get_linked_gsheets/$1/$2';
$route['api/search/tickets/roundtrip'] = 'api/Search/get_inventory_roundtrip';
$route['api/company/(:num)/links/gsheet/(:num)'] = 'api/Company/change_gsheets/$1/$2';
$route['api/company/account/transactions/?(:num)?'] = 'api/Company/perform_wallet_transaction/$1';
$route['api/company/notify'] = 'api/Company/notify';
