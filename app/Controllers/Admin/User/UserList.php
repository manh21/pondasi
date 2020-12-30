<?php namespace App\Controllers\Admin\User;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;

use App\Controllers\Admin\AdminController;
use App\Libraries\Datatabel;

use IonAuth\Libraries\IonAuth;

class UserList extends AdminController
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
	 * IonAuth library
	 *
	 * @var \IonAuth\Libraries\IonAuth
	 */
	private $ionAuth;

	/**
	 * Validation library
	 *
	 * @var \CodeIgniter\Validation\Validation
	 */
	private $validation;

	/**
	 * Validation list template.
	 *
	 * @var string
	 * @see https://bcit-ci.github.io/CodeIgniter4/libraries/validation.html#configuration
	 */
	protected $validationListTemplate = 'list';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->ionAuth = new IonAuth();	
		$this->validation = Services::validation();
		$this->currentUserData = parent::getCurrentUserData();
		$configIonAuth = config('IonAuth');
		if (! empty($configIonAuth->templates['errors']['list']))
		{
			$this->validationListTemplate = $configIonAuth->templates['errors']['list'];
		}
	}
	

	/**
	 * Redirect if needed, otherwise display the user list
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
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

		echo view('App\\Views\\admin\\user\\userList', $site_data);
	}

	/**
	 * Get all user data
	 *
	 * @param string datatables api
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
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

	/**
	 * get a user specific data
	 *
	 * @param int $id user
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
	public function getUserDetail($id)
	{
		if ($this->request->isAJAX()) {
			if(!$id){
				return $this->failNotFound('User tidak ditemukan');
			}else{
				$id = (int) $id;
				$output = [];
				$user = $this->ionAuth->user($id)->row();

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

	/**
	 * Create a new user
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function addUser()
	{
		if (! $this->ionAuth->loggedIn() || ! $this->ionAuth->isAdmin())
		{
			return redirectAdmin('auth/login');
		}

		if(!$this->request->getPost()){
			$groups        = $this->ionAuth->groups()->resultArray();

			$site_data = array(
				'appName' => 'Admin Panel',
				'pageTitle' => 'Add User ',
				'contentTitle' => '',
				'authFullname' => $this->currentUserData['username'],
				'contentView' => null,
				'actionUrl' => adminURL('admin/userlist/add'),
				'backWardUrl' => adminURL('admin/userlist'),
				'viewScripts' => [],
				'ionAuth' => $this->ionAuth,
				'data' => array(
					'groups' => $groups,
				),
			);

			echo view('App\\Views\\admin\\user\\userList_Add', $site_data);
		} else {
			$configIonAuth = config('IonAuth');
			$tables                        = $configIonAuth->tables;
			$identityColumn                = $configIonAuth->identity;

			// validate form input
			$this->validation->setRule('first_name', 'first_name', 'trim|required');
			$this->validation->setRule('last_name', 'last_name', 'trim');

			if ($identityColumn !== 'email') {
				$this->validation->setRule('identity', 'identity', 'trim|required|is_unique[' . $tables['users'] . '.' . $identityColumn . ']');
				$this->validation->setRule('email', 'email', 'trim|required|valid_email');
			} else {
				$this->validation->setRule('email', 'email', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
			}

			$this->validation->setRule('phone', 'phone', 'trim');
			$this->validation->setRule('company', 'company', 'trim');
			$this->validation->setRule('password', 'password', 'required|min_length[' . $configIonAuth->minPasswordLength . ']|matches[password_confirm]');
			$this->validation->setRule('password_confirm', 'password_confirm', 'required');

			if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {
				$email    = strtolower($this->request->getPost('email'));
				$identity = ($identityColumn === 'email') ? $email : $this->request->getPost('identity');
				$password = $this->request->getPost('password');

				$additionalData = [
					'first_name' => $this->request->getPost('first_name'),
					'last_name'  => $this->request->getPost('last_name'),
					'company'    => $this->request->getPost('company'),
					'phone'      => $this->request->getPost('phone'),
				];
			}

			if ($this->request->getPost() && $this->validation->withRequest($this->request)->run() && $createUser =$this->ionAuth->register($identity, $password, $email, $additionalData)) {
				$id = $createUser;

				// Only allow updating groups if user is admin
				if ($this->ionAuth->isAdmin())
				{
					// Update the groups user belongs to
					$groupData = $this->request->getPost('groups');

					if (! empty($groupData))
					{
						$this->ionAuth->removeFromGroup('', $id);

						foreach ($groupData as $grp)
						{
							$this->ionAuth->addToGroup($grp, $id);
						}
					}
				}

				// check to see if we are creating the user
				// redirect them back to the admin page
				$this->session->setFlashdata('message', $this->ionAuth->messages());
				return redirectAdmin('admin/userlist');
			} else {
				// display the create user form
				// set the flash data error message if there is one
				$this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : ($this->ionAuth->errors($this->validationListTemplate) ? $this->ionAuth->errors($this->validationListTemplate) : $this->session->getFlashdata('message'));
				return redirectAdmin('admin/userlist/add');
			}
		}
	}

	/**
	 * Edit a user
	 *
	 * @param integer $id User id
	 *
	 * @return string string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function editUser(int $id){

		$user          = $this->ionAuth->user($id)->row();
		$groups        = $this->ionAuth->groups()->resultArray();
		$currentGroups = $this->ionAuth->getUsersGroups($id)->getResult();
		if(!$this->request->getPost()){
			$site_data = array(
				'appName' => 'Admin Panel',
				'pageTitle' => 'Edit User ' . $user->first_name . $user->last_name,
				'contentTitle' => '',
				'authFullname' => $this->currentUserData['username'],
				'contentView' => null,
				'actionUrl' => adminURL('admin/userlist/edit/'.$id),
				'backWardUrl' => adminURL('admin/userlist'),
				'viewScripts' => [],
				'ionAuth' => $this->ionAuth,
				'data' => array(
					'user' => $user,
					'groups' => $groups,
					'currentGroups' => $currentGroups,
				),
			);

			echo view('App\\Views\\admin\\user\\userList_Edit', $site_data);
		} else {
			// validate form input
			$this->validation->setRule('first_name', 'first_name', 'trim|required');
			$this->validation->setRule('last_name', 'last_name', 'trim');
			$this->validation->setRule('phone', 'phone', 'trim|required');
			$this->validation->setRule('company', 'company', 'trim|required');
			$this->validation->setRule('username', 'username', 'trim|required');
			$this->validation->setRule('email', 'email', 'trim|required|valid_email');

			// do we have a valid request?
			if ($id !== $this->request->getPost('id', FILTER_VALIDATE_INT))
			{
				//show_error(lang('Auth.error_security'));
				throw new \Exception(lang('Auth.error_security'));
			}

			// update the password if it was posted
			if ($this->request->getPost('password'))
			{
				$this->validation->setRule('password', 'password', 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[password_confirm]');
				$this->validation->setRule('password_confirm', 'password_confirm', 'required');
			}

			if ($this->request->getPost() && $this->validation->withRequest($this->request)->run())
			{
				$data = [
					'first_name' => $this->request->getPost('first_name'),
					'last_name'  => $this->request->getPost('last_name'),
					'company'    => $this->request->getPost('company'),
					'phone'      => $this->request->getPost('phone'),
					'email'      => $this->request->getPost('email'),
					'username'   => $this->request->getPost('username'),
				];

				// update the password if it was posted
				if ($this->request->getPost('password'))
				{
					$data['password'] = $this->request->getPost('password');
				}

				// Only allow updating groups if user is admin
				if ($this->ionAuth->isAdmin())
				{
					// Update the groups user belongs to
					$groupData = $this->request->getPost('groups');

					if (! empty($groupData))
					{
						$this->ionAuth->removeFromGroup('', $id);

						foreach ($groupData as $grp)
						{
							$this->ionAuth->addToGroup($grp, $id);
						}
					}
				}

				// check to see if we are updating the user
				if ($this->ionAuth->update($user->id, $data)) {
					$this->session->setFlashdata('message', $this->ionAuth->messages());
				} else {
					$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
				}
				
				// redirect them back to the admin page if admin, or to the base url if non admin
				return redirectAdmin('admin/userlist');
			}
		}
	}

	/**
	 * Delete a user
	 *
	 * @param int $id user
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
	public function deleteUser(int $id)
	{
		if (! $this->ionAuth->loggedIn() || ! $this->ionAuth->isAdmin())
		{
			return redirectAdmin('auth/login');
		}
		
		if($this->request->isAJAX()){
			if(!$id){
				// throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
				return $this->failNotFound('User tidak ditemukan.');
			}else{
				$output = [];
				$output['status'] = 200;
				if($this->ionAuth->deleteUser($id)){
					$output['messages'] = 'Berhasil delete user';
				} else {
					$output['messages'] = 'Gagal delete user';
				}

				return $this->respondDeleted($output, 'Succes');
				// return $this->setResponseFormat('json')->respond($output, 200, 'Succes');
			}
		}else {
			return $this->failForbidden('Forbidden');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param integer $id   The user ID
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
	public function activate(int $id)
	{
		if($this->request->isAJAX()){
			if (! $this->ionAuth->loggedIn() || ! $this->ionAuth->isAdmin())
			{
				// throw new \Exception('You must be an administrator to view this page.');
				return $this->failUnauthorized('You must be an administrator to access this.');
			}

			$activation = false;

			if ($this->ionAuth->isAdmin())
			{
				$activation = $this->ionAuth->activate($id);
			}

			if ($activation)
			{
				$output = [];
				$output['status'] = 200;
				$output['messages'] = $this->ionAuth->messages();

				return $this->respondUpdated($output, 'Succes');
			}
			else
			{
				return $this->failUnauthorized('You must be an administrator to access this.');
			}
		} else {
			return $this->failForbidden('Forbidden');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param integer $id The user ID
	 *
	 * @throw Exception
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
	public function deactivate(int $id = 0)
	{
		if($this->request->isAJAX()){
			if (! $this->ionAuth->loggedIn() || ! $this->ionAuth->isAdmin())
			{
				// throw new \Exception('You must be an administrator to view this page.');
				return $this->failUnauthorized('You must be an administrator to access this.');
			}

			$this->validation->setRule('id', 'id', 'required|integer');

			if($this->validation->withRequest($this->request)->run()) {
				// do we have a valid request?
				if ($id !== $this->request->getPost('id', FILTER_VALIDATE_INT))
				{
					// throw new \Exception(lang('Auth.error_security'));
					return $this->failServerError('Request Not Valid');
				}

				// do we have the right userlevel?
				if ($this->ionAuth->loggedIn() && $this->ionAuth->isAdmin())
				{
					$message = $this->ionAuth->deactivate($id) ? $this->ionAuth->messages() : $this->ionAuth->errors($this->validationListTemplate);

					$output = [];
					$output['status'] = 200;
					$output['messages'] = $message;

					return $this->respondUpdated($output, 'Succes');
				}
			} else {
				return $this->failValidationError('Request Not Valid');
			}
		} else {
			return $this->failForbidden('Forbidden');
		}
	}

	//----------------------------- END ---------------------------------------

}
