<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use \Config\Database;

class Maintenance implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here

        $mode = $this->check();

        if($mode){
            return redirect()->to(site_url('maintenance'));
        }

    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }

    private function check(){

        $db = Database::connect();
        $builder = $db->table('settings');

        $builder->select('*');
        $builder->where('name', 'maintenance');
        $query = $builder->get();
        $result = $query->getRowArray();

        if($result['value'] == 2 ){
            return true;
        } else {
            return false;
        }

    }
}