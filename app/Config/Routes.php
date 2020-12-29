<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

if(!empty($_ENV['ADMIN_PREFIX'])){
	$adminPrefix = $_ENV['ADMIN_PREFIX'];
}  else {
	$adminPrefix = '';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->group('/', [
	'namespace' => 'App\Controllers',
	'filter' => 'Maintenance'
], function ($routes){
	$routes->get('', 'Home::index');
});

$routes->add('maintenance', 'Maintenance');

$routes->group($adminPrefix, function($routes){
	$routes->group('auth', [
		'namespace' => 'App\Controllers\Admin',
		'filter' => 'LoginPage'
	], function ($routes) {
		$routes->add('login', 'Auth::login');
		$routes->get('logout', 'Auth::logout');
		$routes->add('forgot_password', 'Auth::forgot_password');
		$routes->post('reset_password/(:hash)', 'Auth::reset_password/$1');
		$routes->get('reset_password/(:hash)', 'Auth::reset_password/$1');
		// $routes->get('/', 'Auth::index');
		// $routes->add('create_user', 'Auth::create_user');
		// $routes->add('edit_user/(:num)', 'Auth::edit_user/$1');
		// $routes->add('create_group', 'Auth::create_group');
		// $routes->get('activate/(:num)', 'Auth::activate/$1');
		// $routes->get('activate/(:num)/(:hash)', 'Auth::activate/$1/$2');
		// $routes->add('deactivate/(:num)', 'Auth::deactivate/$1');
		// ...
	});

	$routes->group('admin', [
		'namespace' => 'App\Controllers\Admin',
		'filter' => 'RedirectAuth'
	], function($routes) {
		$routes->get('logout', 'Auth::logout', []);

		$routes->get('', 'Dashboard', ['as' => 'dashboard']);
		$routes->get('dashboard', 'Dashboard', []);
		$routes->get('settings', 'Settings', []);
		$routes->post('settings/save', 'Settings::update', []);

		$routes->group('userlist', [
			'namespace' => 'App\Controllers\Admin\User',
			'filter' => 'RedirectAuth'
		], function($routes) {
			$routes->get('', 'UserList', []);
			$routes->get('getdata', 'UserList::getData', []);
			$routes->post('activate/(:num)', 'UserList::activate/$1', []);
			$routes->post('deactivate/(:num)', 'UserList::deactivate/$1', []);
			$routes->get('add', 'UserList::addUser', []);
			$routes->post('add', 'UserList::addUser', []);
			$routes->get('edit/(:num)', 'UserList::editUser/$1', []);
			$routes->post('edit/(:num)', 'UserList::editUser/$1', []);
			$routes->get('getdetail/(:num)', 'UserList::getUserDetail/$1', []);
			$routes->delete('delete/(:num)', 'UserList::deleteUser/$1', []);
		});

		$routes->group('usergroups', [
			'namespace' => 'App\Controllers\Admin\User',
			'filter' => 'RedirectAuth'
		], function($routes) {
			$routes->get('', 'UserGroups', []);
			$routes->get('getdata', 'UserGroups::getData', []);
			$routes->get('detail/(:num)', 'UserGroups::detail/$1', []);
			$routes->get('add', 'UserGroups::add', []);
			$routes->post('add', 'UserGroups::add', []);
			$routes->get('edit/(:num)', 'UserGroups::edit/$1', []);
			$routes->post('edit/(:num)', 'UserGroups::edit/$1', []);
			$routes->delete('delete', 'UserGroups::delete', []);
		});
	});
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
