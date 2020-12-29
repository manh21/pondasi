<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\Config\Services;

class RedirectAuthentication implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('general');

        $auth = Services::auth();
        $isAdmin = $auth->isAdmin();


        if(!$isAdmin){
            return redirectAdmin('auth/login');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}