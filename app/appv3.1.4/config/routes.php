<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = 'home/home/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// order
$route[LINK_ORDER]                                   = 'order/order/index';
$route[LINK_ORDER . '/submit']                         = 'order/order/submit';
$route[LINK_ORDER . '/ajax_add_rework/(:num)']         = 'order/order/ajax_add_rework/$1';
$route[LINK_ORDER . '/ajax_delete_file_attach_rework'] = 'order/order/ajax_delete_file_attach_rework';
$route[LINK_ORDER . '/ajax_add_file_attach_rework']    = 'order/order/ajax_add_file_attach_rework';
$route[LINK_ORDER . '/ajax_edit_file_attach_rework']   = 'order/order/ajax_edit_file_attach_rework';
$route[LINK_ORDER . '/ajax_update_requirement_rework'] = 'order/order/ajax_update_requirement_rework';

// upload
$route['upload']      = 'upload/upload/index';

$route[LINK_ABOUT]    = 'about/about/index';
$route[LINK_CAREERS]  = 'careers/careers/index';
$route[LINK_CONTACT]  = 'contact/contact/index';
$route[LINK_POLICY]   = 'policy/policy/policy';
$route[LINK_TERMS]    = 'policy/policy/terms';
$route[LINK_REFUND]   = 'policy/policy/refund';
$route[LINK_HIW]      = 'home/home/hiw';
$route[LINK_PRICINGS] = 'pricing/pricing/index';
$route[LINK_LIBRARY]  = 'library/library/index';

// usser
$route[LINK_USER_SIGN_IN]                = 'user/user/login';
$route[LINK_USER_SIGN_OUT]               = 'user/user/logout';
$route[LINK_USER_ORDER]                  = 'user/user/order';
$route[LINK_USER_ORDER_DETAIL . '/(:num)'] = 'user/user/orderdetail/$1';
$route[LINK_USER_PROFILES]               = 'user/user/profiles';
$route[LINK_USER_NOTICES]                = 'user/user/notices';
$route[LINK_USER_SETTINGS]               = 'user/user/settings';
$route[LINK_USER_TRANSACTIONS]           = 'user/user/transactions';

// login
$route[LINK_USER_LOGIN]               = 'login/login/index';
$route[LINK_USER_LOGIN . '/auth']       = 'login/login/auth';
$route[LINK_USER_LOGOUT]              = 'logout/logout/index';
$route[LINK_USER_LOGIN . '/ggcallback'] = 'login/login/ggcallback';
$route[LINK_USER_REGISTER]            = 'signup/signup/index';


//discuss
$route['discuss/ajax_discuss_list'] = 'discuss/discuss/ajax_discuss_list';
$route['discuss/ajax_discuss_add'] = 'discuss/discuss/ajax_discuss_add';

//payment
$route['order/ajax-popup-payment/(:num)'] = 'order/order/ajax_popup_payment/$1';
$route['order/ajax-call-api-pay'] = 'order/order/ajax_call_api_pay';
$route['order/ajax-callback-payment'] = 'order/order/ajax_callback_payment';