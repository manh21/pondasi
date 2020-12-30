<?php namespace App\Controllers\Admin;

use CodeIgniter\API\ResponseTrait;

use App\Controllers\Admin\AdminController;
use App\Libraries\Datatabel;

use App\Models\Admin\M_Settings;

class Dashboard extends AdminController
{
	/**
	 * ResponseTrait
	 *
	 * @var CodeIgniter\Api\ResponseTrait
	 */
	use ResponseTrait;

	/**
	 * Current User Data
	 *
	 * @var App\Controllers\Admin\AdminController::getCurrentUserData
	 */
	private $currentUserData;
	
	 /**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->currentUserData = parent::getCurrentUserData();
	}

	public function index()
	{
		$site_data = array(
			'appName' => 'Admin Panel',
			'pageTitle' => 'User List',
			'contentTitle' => 'Content Title',
			'authFullname' => $this->currentUserData['username'],
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
