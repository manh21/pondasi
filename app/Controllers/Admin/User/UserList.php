<?php namespace App\Controllers\Admin\User;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Controllers\Admin\AdminController;
use App\Libraries\Datatabel;

class UserList extends AdminController
{
	use ResponseTrait;

	public function index()
	{
		$currentUserData = parent::geCurrentUserData();

		$site_data = array(
			'appName' => 'Admin Panel',
			'pageTitle' => 'User List',
			'contentTitle' => 'Content Title',
			'authFullname' => $currentUserData['username'],
			'contentView' => null,
			'actionUrl' => '#',
			'backWardUrl' => '#',
			'viewScripts' => [],
		);

		echo view('App\\Views\\admin\\userList', $site_data);
	}

	public function getData()
    {
		$dataTable = new Datatabel('users');
		$dataTable->addDtNumberHandler();
		$dataTable->addDtDb(1, 'username', true, true);
		$dataTable->addDtDb(2, 'first_name', true, true);
		$dataTable->addDtDb(3, 'email', true, true);
		$dataTable->addDtDb(4, 'active', false, false);
		$dataTable->addDtDb(5, 'id', false, false);
		return parent::responeDataTable($dataTable);
	}

	//--------------------------------------------------------------------

}
