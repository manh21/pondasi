<?php namespace App\Controllers\Admin\User;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Controllers\Admin\AdminController;
use App\Libraries\Datatabel;

use IonAuth\Libraries\IonAuth;

class UserList extends AdminController
{
	use ResponseTrait;
	private $ionAuth;

	public function __construct()
	{
		$this->ionAuth = new IonAuth();	
	}

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

		echo view('App\\Views\\admin\\user\\userList', $site_data);
	}

	public function getData()
    {		
		$dataTable = new Datatabel('users');
		$dataTable->addDtNumberHandler();
		$dataTable->addDtDb(1, 'username', true, true, false);
		$dataTable->addDtDb(2, 'first_name', true, true, false);
		$dataTable->addDtDb(3, 'email', true, true, false);
		$dataTable->addDtDb(4, 'id', false, false, true, function($record, $value, $meta){
			return $this->ionAuth->getUsersGroups($value)->getResult();
		});
		$dataTable->addDtDb(5, 'active', true, false, false);
		$dataTable->addDtDb(6, 'id', false, false, false);
		return parent::responeDataTable($dataTable);
	}

	public function getUserDetail($id)
	{
		if ($this->request->isAJAX()) {
			if(!$id){
				return $this->failNotFound('Users tidak ditemukan');
			}else{
				$output = [];
				$user = $this->ionAuth->users($id)->row();

				$output['status'] = 200;
				$output['data'] = [
					'first_name' => $user->first_name,
					'last_name' => $user->last_name,
					'username' => $user->username,
					'email' => $user->email,
					'phone' => $user->phone,
					'company' => $user->company,
					'active' => $user->active
				];

				return $this->setResponseFormat('json')->respond($output, 200, 'Succes');
			}
		} else {
			return $this->failForbidden('Forbidden');
		}
	}

	public function deleteUser($code)
	{
		if($this->request->isAJAX()){
			if(!$code){
				// throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
				return $this->failNotFound('User tidak ditemukan.');
			}else{
				$output = [];
				$output['status'] = 200;
				$output['message'] = 'Berhasil delete user';

				return $this->respondDeleted($output, 'Succes');
				// return $this->setResponseFormat('json')->respond($output, 200, 'Succes');
			}
		}else {
			return $this->failForbidden('Forbidden');
		}
	}

	//--------------------------------------------------------------------

}
