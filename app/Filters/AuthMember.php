<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class AuthMember implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session()->get('user');

        if (!$session) {
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
