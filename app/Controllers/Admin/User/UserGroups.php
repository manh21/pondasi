<?php namespace App\Controllers\Admin\User;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Controllers\Admin\AdminController;
use App\Libraries\Datatabel;

class UserGroups extends AdminController
{
	use ResponseTrait;

	public function index()
	{
		$currentUserData = parent::geCurrentUserData();

		$site_data = array(
			'appName' => 'Admin Panel',
			'pageTitle' => 'User Groups',
			'contentTitle' => 'Content Title',
			'authFullname' => $currentUserData['username'],
			'contentView' => null,
			'actionUrl' => '#',
			'backWardUrl' => '#',
			'viewScripts' => [],
		);

		echo view('App\\Views\\admin\\userGroups', $site_data);
	}

	public function getData()
    {
		$dataTable = new Datatabel('test');
		$dataTable->addDtNumberHandler();
		$dataTable->addDtDb(1, 'username', true, true);
		$dataTable->addDtDb(2, 'first_name', true, true);
		$dataTable->addDtDb(3, 'email', true, true);
		$dataTable->addDtDb(4, 'active', false, false, function($record, $value, $meta) {
			$result = '';

			if($value == 1){
				$result = `<a href="https://mpk.manh21.com/admin/auth/deactivate/$record" class="label label-info">Active</a>`;
			} else {
				$result = `<a href="https://mpk.manh21.com/admin/auth/activate/$record" class="label label-info">Inactive</a>`;
			}
			
			return $result; 
        });
		$dataTable->addDtDb(5, 'id', false, false, function($record, $value, $meta) {
            return $value;
        });
		return parent::responeDataTable($dataTable);
	}

	//--------------------------------------------------------------------

}
