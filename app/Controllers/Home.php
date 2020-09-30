<?php namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Libraries\Datatabel;

class Home extends BaseController
{

	public function index()
	{
		return view('datatable');
	}

	public function getData()
    {
		$dataTable = new Datatabel('users');
		$dataTable->addDtNumberHandler();
		$dataTable->addDtDb(1, 'username', true, true);
		$dataTable->addDtDb(2, 'first_name', true, true);
		$dataTable->addDtDb(3, 'last_name', true, true);
		$dataTable->addDtDb(4, 'id', false, false);
		return $this->responeDataTable($dataTable);
    }

	private function responeDataTable($dataTable)
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

	//--------------------------------------------------------------------

}
