<?php namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;

use App\Controllers\Admin\AdminController;
use App\Libraries\Datatabel;

use App\Models\Admin\M_Settings;

class Dashboard extends AdminController
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

		echo view('App\\Views\\admin\\dashboard', $site_data);
	}

	public function getData()
    {
		$dataTable = new Datatabel('test');
		$dataTable->addDtNumberHandler();
		$dataTable->addDtDb(1, 'username', true, true);
		$dataTable->addDtDb(2, 'fullname', true, true);
		$dataTable->addDtDb(3, 'email', true, true);
		$dataTable->addDtDb(4, 'id', false, false);
		return parent::responeDataTable($dataTable);
    }

	//--------------------------------------------------------------------

}
