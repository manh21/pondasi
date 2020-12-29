<?php namespace App\Controllers;

use \Config\Database;

class Maintenance extends BaseController
{
    public function index()
    {
        $mode = $this->check();

        if(!$mode){
            return redirect()->to(site_url());
        }        

        return view('Maintenance');
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

?>