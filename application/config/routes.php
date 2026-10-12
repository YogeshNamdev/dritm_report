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

$route['default_controller'] =  'index';
$route['daily-quiz']="DailyQuiz";
$route['AiGroq']                     = 'AiGroq/index';
$route['AiGroq/enhance_remark']      = 'AiGroq/enhance_remark';
$route['ai-agent/enhance-remark']    = 'AiGroq/enhance_remark';
$route['hrtc']                     = 'Hrtc/index';

$route['hrtc/search']              = 'Hrtc/search';

$route['hrtc/route/(:num)']        = 'Hrtc/route/$1';

$route['hrtc/timeline']            = 'Hrtc/timeline';

$route['hrtc/journey']             = 'Hrtc/journey';

$route['hrtc/autocomplete']        = 'Hrtc/autocompleteStops';

$route['hrtc/details']             = 'Hrtc/routeDetails';


$route['app'] = 'app/login';
$route['app/(:any)'] = 'app/$1';
$route['index/(:any)'] = 'index/$1';
$route['student-login'] = 'studentauth/login';
$route['student-login-check'] = 'studentauth/login_check';
$route['student-dashboard'] = 'student/student_dashboard';
$route['app/attendance']                          = 'app/attendance/index';
$route['app/attendance/upload_center']             = 'app/attendance/upload_center';
$route['app/attendance/matrix']                    = 'app/attendance/matrix';
$route['app/attendance/tl_wise']                   = 'app/attendance/tl_wise';
$route['app/attendance/exceptions']                = 'app/attendance/exceptions';
$route['app/attendance/roster_builder'] = 'app/attendance/roster_builder';
$route['app/attendance/employees'] = 'app/attendance/employees';
$route['app/attendance/cl_tracker'] = 'app/attendance/cl_tracker';
$route['app/attendance/roster_compliance'] = 'app/attendance/roster_compliance';
$route['app/attendance/my_roster'] = 'app/attendance/my_roster';
$route['app/attendance/request_roster_change'] = 'app/attendance/request_roster_change';
$route['app/attendance/roster_requests'] = 'app/attendance/roster_requests';
$route['app/attendance/wo_cl_preference'] = 'app/attendance/wo_cl_preference';
$route['app/attendance/preference_overview'] = 'app/attendance/preference_overview';
$route['(:any)/(:any)'] = 'index/index/$1/$2';
$route['(:any)'] = 'index/index/$1';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


