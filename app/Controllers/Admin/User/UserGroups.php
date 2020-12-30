<?php namespace App\Controllers\Admin\User;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Services;

use App\Controllers\Admin\AdminController;
use App\Libraries\Datatabel;

use IonAuth\Libraries\IonAuth;

class UserGroups extends AdminController
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
	 * Redirect if needed, otherwise display the group list
	 *
	 * @return string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function index()
	{
		$site_data = array(
			'appName' => 'Admin Panel',
			'pageTitle' => 'User Groups',
			'contentTitle' => 'Group',
			'authFullname' => $this->currentUserData['username'],
			'contentView' => null,
			'actionUrl' => '#',
			'backWardUrl' => '#',
			'viewScripts' => [],
		);

		echo view('App\\Views\\admin\\user\\userGroups', $site_data);
	}

	/**
	 * Get all groups
	 *
	 * @param string datatables api
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
	public function getData()
    {
		$dataTable = new Datatabel('groups');
		$dataTable->addDtNumberHandler();
		$dataTable->addDtDb(1, 'name', true, true, false);
		$dataTable->addDtDb(2, 'description', true, true, false);
		$dataTable->addDtDb(3, 'id', false, false, false);
		return parent::responeDataTable($dataTable);
	}

	/**
	 * get a group specific data
	 *
	 * @param int $id user
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
	public function detail(int $id)
	{
		if (! $this->ionAuth->loggedIn() || ! $this->ionAuth->isAdmin())
		{
			return redirectAdmin('auth');
		}

		if($this->request->isAJAX()){
			if(!$id){
				return $this->failNotFound('Group tidak ditemukan');
			}else{
				$output = [];
				$group = $this->ionAuth->group($id)->row();

				$output['status'] = 200;
				$output['data'] = [
					'name' => $group->name,
					'description' => $group->description
				];

				return $this->setResponseFormat('json')->respond($output, 200, 'Succes');
			}
		} else {
			return $this->failForbidden('Forbidden');
		}
	}

	/**
	 * Delete a group
	 *
	 * @param int $id group
	 *
	 * @return string|CodeIgniter\HTTP\ResponseInterface;
	 */
	public function delete(int $id)
	{
		if (! $this->ionAuth->loggedIn() || ! $this->ionAuth->isAdmin())
		{
			return redirectAdmin('auth');
		}

		if ($this->request->isAJAX()) {
			if (empty($id)) {
				return $this->failNotFound('Group tidak ditemukan');
			} else {
				$output = [];
				$output['status'] = 200;
				if($this->ionAuth->deleteGroup($id)){
					$output['messages'] = 'Berhasil delete group';
				} else {
					$output['messages'] = 'Gagal delete group';
				}

				return $this->respondDeleted($output, 'Succes');
				// return $this->setResponseFormat('json')->respond($output, 200, 'Succes');
			}
		} else {
			return $this->failForbidden('Forbidden');
		}
	}

	/**
	 * Create a new group
	 *
	 * @return string string|\CodeIgniter\HTTP\RedirectResponse
	 */
	public function add()
	{
		if (!$this->request->getPost()) {
			$site_data = array(
				'appName' => 'Admin Panel',
				'pageTitle' => 'Add New Group',
				'contentTitle' => 'Group',
				'authFullname' => $this->currentUserData['username'],
				'contentView' => null,
				'actionUrl' => adminURL('admin/usergroups/add'),
				'backWardUrl' => adminURL('admin/usergroups'),
				'viewScripts' => [],
			);
	
			echo view('App\\Views\\admin\\user\\userGroups_Add', $site_data);
		} else {
			// validate form input
			$this->validation->setRule('group_name', 'group_name', 'trim|required|alpha_dash');

			if ($this->request->getPost() && $this->validation->withRequest($this->request)->run())
			{
				$newGroupId = $this->ionAuth->createGroup($this->request->getPost('group_name'), $this->request->getPost('group_description'));
				if ($newGroupId)
				{
					// check to see if we are creating the group
					// redirect them back to the admin page
					$this->session->setFlashdata('message', $this->ionAuth->messages());
					return redirectAdmin('admin/usergroups');
				}
			} else {
				// display the create group form
				// set the flash data error message if there is one
				$this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : ($this->ionAuth->errors($this->validationListTemplate) ? $this->ionAuth->errors($this->validationListTemplate) : $this->session->getFlashdata('message'));

				return redirectAdmin('admin/usergroups/add');
			}
		}
		
	}

	/**
	 * Edit a group
	 *
	 * @param integer $id Group id
	 *
	 * @return string|CodeIgniter\Http\Response
	 */
	public function edit(int $id = 0)
	{
		$group = $this->ionAuth->group($id)->row();

		if (!$this->request->getPost()) {
			$site_data = array(
				'appName' => 'Admin Panel',
				'pageTitle' => 'Edit Group',
				'contentTitle' => 'Group',
				'authFullname' => $this->currentUserData['username'],
				'contentView' => null,
				'actionUrl' => adminURL('admin/usergroups/edit/'.$id),
				'backWardUrl' => adminURL('admin/usergroups'),
				'viewScripts' => [],
				'data' => array(
					'group' => $group
				)
			);
	
			echo view('App\\Views\\admin\\user\\userGroups_Edit', $site_data);
		} else {

			// validate form input
			$this->validation->setRule('group_name', 'group_name', 'required|alpha_dash');

			if ($this->request->getPost() && $this->validation->withRequest($this->request)->run())
			{
				$groupUpdate = $this->ionAuth->updateGroup($id, $this->request->getPost('group_name'), ['description' => $this->request->getPost('group_description')]);

				if ($groupUpdate)
				{
					$this->session->setFlashdata('message', lang('Auth.edit_group_saved'));
				}
				else
				{
					$this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
				}

				return redirectAdmin('admin/usergroups');
			} else {

				// set the flash data error message if there is one
				$this->data['message'] = $this->validation->listErrors($this->validationListTemplate) ?: ($this->ionAuth->errors($this->validationListTemplate) ?: $this->session->getFlashdata('message'));

				return redirectAdmin('admin/usergroups/edit');
			}

		}
		
	}

	//--------------------------------------------------------------------

}
