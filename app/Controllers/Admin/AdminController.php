<?php
namespace App\Controllers\Admin;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Controllers\BaseController;

use CodeIgniter\Config\Services;
use CodeIgniter\API\ResponseTrait;

class AdminController extends BaseController
{

	use ResponseTrait;

	protected $vars = [];

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['general', 'url', 'form'];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
	}

	protected function outputJson($status, $message, $csrf = true)
	{
		if ($csrf) {
			$this->vars['csrfName'] = csrf_token();
			$this->vars['csrfToken'] = csrf_hash();
		}
		$this->vars['status'] = $status;
		$this->vars['message'] = $message;

		return $this->respond($this->vars, 200);
    }
    
    protected function getCurrentUserData()
	{
		$auth = Services::auth();
		// $current_userId = $auth->getUserId();

		$userData = $auth->user()->row();
		
		$data = array(
			'username' => $userData->username,
			'first_name' => $userData->first_name,
			'last_name' => $userData->last_name
		);

        return $data;
	}

	protected function responeDataTable($dataTable)
	{
		if ($this->request->isAJAX()) {
			if ($requestData = $this->request->getGet()) {
				$output = $dataTable->getOutput($requestData);
				return $this->respond($output, 200, 'Succes');
			}
		} else {
			return $this->failForbidden('Harus AJAX Request');
		}
	}

}
