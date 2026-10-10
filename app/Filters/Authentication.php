<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Authentication implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is logged in
        if (!session()->get('staff_id')) {
            // If not logged in, redirect to the login page
            return redirect()->to(site_url('/login'));
        }
    }

    public function after(
        RequestInterface $request, 
        ResponseInterface $response, 
        $arguments = null)
    {
        // No action needed after the request
    }
}