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
|	http://codeigniter.com/user_guide/general/routing.html
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
//$route['disprpt1'] = 'report/setreports';
//$route['display_report/(:any)'] = 'display_report/setreport/$1';
//$route['default_controller'] = 'open_so/getOpenSO';
$route['default_controller'] = 'open_so/getOpenSO';
//$route['displayreport'] = 'Reports/setreports';
//$route['disp'] = 'report/demo';
//$route['displayreport'] = 'Reports/setreport';
//$route['404_override'] = '';
//$route['translate_uri_dashes'] = FALSE;


$route['open_so'] = 'open_so/getOpenSO';
$route['open_so/(:any)'] = 'news/getOpenSO/$1';


$route['sso'] = 'Utility/get_username';
$route['hardlock_release_notification'] = 'Utility/check_hlr';
$route['get_ValidSOs']  = 'ManualLocks/get_ValidSOs';
$route['get_ValidEMPs'] = 'ManualLocks/get_ValidEMPs';
$route['get_ValidTNEs'] = 'ManualLocks/get_ValidTNEs';
$route['Lock_EMPs']     = 'ManualLocks/Lock_EMPs';
$route['BAfilepath']    = 'Utility/BAfilepath';



$route['deployable_emp']     = 'Proposals/deployable_emps';
$route['approve_soft_lock']  = 'Locks/approve_soft_lock';
$route['approve_hard_lock']  = 'Locks/approve_hard_lock';
$route['get_where_proposed'] = 'Locks/getwhereProposed';
$route['get_partial']        = 'Proposals/getPartialProposals';
$route['NoPerfectMatch']     = 'Proposals/getSoForPartialProposal';
$route['loadRAS']            = 'Utility/load_RAS_File';
$route['loadFULLFILLSTAT']   = 'Utility/load_FULLFILLSTAT_File';
$route['loadAmendments']     = 'Utility/loadAmendments';
//$route['hello']              = 'Utility/hello';

$route['displayreport']      = 'Reports/setreports';
$route['viewreport']         =  'Reports/viewreports';
$route['getamendment']       = 'Amendments/getamendments';
$route['approveamendment']   = 'Amendments/approveamendment';
$route['get_ValidSOs/(:any)'] = 'ManualLocks/get_ValidSOs/$1';

