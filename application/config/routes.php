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

$route['default_controller'] = "front_page";
$route['404_override'] = '';

$route[''] = '';
$route['ajax'] = 'ajax';
$route['api'] = 'api';
$route['blog'] = 'blog';
$route['compose'] = 'compose';
$route['create_stuff'] = 'create_stuff';
$route['cron'] = 'cron';
$route['debug'] = 'debug';
$route['feed'] = 'feed';
$route['forgot_password'] = 'forgot_password';
$route['front_page'] = 'front_page';
$route['home'] = 'home';
$route['login'] = 'login';
$route['logout'] = 'logout';
$route['p'] = 'posts';
$route['post'] = 'post';
$route['profile'] = 'profile';
$route['refer'] = 'refer';
$route['saved'] = 'saved';
$route['settings'] = 'settings';
$route['signup'] = 'signup';
$route['test'] = 'test';
$route['settings'] = 'settings';
$route['validate_email'] = 'validate_email';
$route['tags'] = 'tags';
$route['unit_test'] = 'unit_test';
$route['users'] = 'users';

$route['ajax/(:any)'] = 'ajax/$1';
$route['api/(:any)'] = 'api/$1';
$route['avatars/(:any)'] = 'avatars/$1';
$route['c/(:any)'] = 'comment/$1';
$route['compose/(:any)'] = 'compose/$1';
$route['create_stuff/(:any)'] = 'create_stuff/$1';
$route['cron/(:any)'] = 'cron/$1';
$route['debug/(:any)'] = 'debug/$1';
$route['feed/(:any)'] = 'feed/$1';
$route['forgot_password/(:any)'] = 'forgot_password/$1';
$route['home/(:any)'] = 'home/$1';
$route['i/(:any)'] = 'images/1$';
$route['login/(:any)'] = 'login/$1';
$route['logout/(:any)'] = 'logout/$1';
$route['profile/(:any)'] = 'profile/$1';
$route['blog/(:any)'] = 'blog/display_by_username/$1';
$route['p/(:any)'] = 'post/display_by_sid/$1';
$route['refer/(:any)'] = 'refer/$1';
$route['saved/(:any)'] = 'saved/$1';
$route['scripts/(:any)'] = 'scripts/$1';
$route['settings/(:any)'] = 'settings/$1';
$route['signup/(:any)'] = 'signup/$1';
$route['tags/(:any)'] = 'tags/display_by_name/$1';
$route['t/(:any)'] = 'tags/display_by_name/$1';
$route['test/(:any)'] = 'test/$1';
$route['unit_test/(:any)'] = 'unit_test/$1';
$route['users/(:any)'] = 'users/display_by_username/$1';
$route['validate_email/(:any)'] = 'validate_email/$1';

//log $route['(:any)'] = 'blog/display_by_username/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */