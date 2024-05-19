<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index(): string
    {
        return view('member/loginView');
    }

    public function login()
    {
        $validation = $this->validate([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi'
                ]
            ]
        ]);

        if (!$validation) {
            return redirect()->to('/auth')->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AdminModel();
        $email = $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $user = $model->where('email', $email)->first();


        if (!$user) {
            session()->setFlashdata('errors', ['email' => 'Email tidak terdaftar']);
            return redirect()->to('/auth')->withInput();
        }

        if (!is_null($user) && !password_verify($password, $user['password'])) {
            session()->setFlashdata('errors', 'Password salah');
            return redirect()->to('/auth')->withInput();
        }


        // put member session
        session()->set('user', $user);

        // check session

        return redirect()->to('/member/dashboard');
    }

    public function logout()
    {
        //remove session
        session()->remove('user');

        return redirect()->to('/auth');
    }
}
