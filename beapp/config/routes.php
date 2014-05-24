<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

//---- Rutas default ---- //

$route['default_controller'] = "controller_home";
$route['404_override'] = '';

// '/en' and '/fr' -> use default controller
$route['^(en|es)$'] = $route['default_controller'];
//----- Rutas directorios ------ // 
$route['^(en|es)/home'] = 'controller_home';
$route['^(en|es)/soon'] = 'controller_home/soon';
$route['^(en|es)/email'] = 'controller_home/email';
$route['^(en|es)/album'] = 'controller_home/album';
$route['^(en|es)/justin'] = 'controller_home/justin';
$route['^(en|es)/logintwitter'] = 'controller_user/logintwitter';
$route['^(en|es)/loginfacebook'] = 'controller_user/loginfacebook';
$route['^(en|es)/logout'] = 'controller_user/logout';
$route['^(en|es)/feed'] = 'controller_user/feed';
$route['^(en|es)/profile/(:num)'] = 'controller_user/profile/$1';
$route['^(en|es)/group'] = 'controller_group/groups';
$route['^(en|es)/group/create'] = 'controller_group/groups/create';
$route['^(en|es)/group/delete'] = 'controller_group/groups/delete';
$route['^(en|es)/group/save'] = 'controller_group/groups/save';
$route['^(en|es)/group/invite'] = 'controller_group/groups/invite';
$route['^(en|es)/group/delete_member'] = 'controller_group/groups/delete_member';
$route['^(en|es)/group/(:num)'] = 'controller_group/group/$1';
$route['^(en|es)/feed/comments/(:num)/(:any)'] = 'controller_feed/comments/$1/$2';
$route['^(en|es)/feed/likes/(:num)/(:any)'] = 'controller_feed/likes/$1/$2';
$route['^(en|es)/feed/syncf/(:num)/(:any)'] = 'controller_feed/syncf/$1/$2';
$route['^(en|es)/feed/publications/feed/(:num)/(:any)'] = 'controller_feed/publications_feed/$1/$2';
$route['^(en|es)/feed/publications/profile/(:num)/(:num)/(:any)'] = 'controller_feed/publications_profile/$1/$2/$3';
$route['^(en|es)/feed/publications/group/(:num)/(:num)/(:any)'] = 'controller_feed/publications_group/$1/$2/$3';


/* End of file routes.php */
/* Location: ./application/config/routes.php */