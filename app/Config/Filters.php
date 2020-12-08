<?php namespace Config;

use App\Filters\LoginPage;
use CodeIgniter\Config\BaseConfig;

use App\Filters\RedirectAuthentication;
use App\Filters\Maintenance;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
		'RedirectAuth' => RedirectAuthentication::class,
		'LoginPage' => LoginPage::class,
		'Maintenance' => Maintenance::class,
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			'honeypot',
			'csrf',
			// 'Maintenance',
			// 'csrf' => ['except' => [
			// 	'api/contact/save',
			// 	'api/contact/delete'
			// 	]
			// ]
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [];
}
