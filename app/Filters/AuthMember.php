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
        } else {
            // check photo
            $user = new \App\Models\UserModel();
            $user = $user->find($session['id']);
            if (!$user['image']) {
                // if on same path 
                // allow path absence 
                if ($request->getUri()->getPath() != '/member/absence/take' && $request->getUri()->getPath() != '/member/absence/take_photo') {
                    // allow path any absence
                    return redirect()->to('/member/absence/take');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
