<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {

        $userModel = new UserModel();

        $breadcumbs = [
            'Profile' => [
                'active' => false,
                'href' => '/member/profile',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '#',
            ]
        ];

        return view('member/profileView', [
            'breadcumbs' => $breadcumbs,
            'user' => $userModel->find(session('user')['id']),
        ]);
    }

    public function update_image()
    {

        $userModel = new UserModel();

        $breadcumbs = [
            'Profile' => [
                'active' => false,
                'href' => '/member/profile',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '#',
            ]
        ];

        return view('member/profileEditImage', [
            'breadcumbs' => $breadcumbs,
            'user' => $userModel->find(session('user')['id']),
        ]);
    }


    public function update_user()
    {
        $userModel = new UserModel();

        $validate = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus diisi',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                ]
            ],
        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
            ];

            $password = (string) $this->request->getPost('password');
            // if theres any password 
            if ($password) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $userModel->update(session('user')['id'], $data);

            // get updated user set session

            $user = $userModel->find(session('user')['id']);
            session()->set('user', $user);

            return $this->rest->responseSuccess("Berhasil mengubah data");
        } catch (\Throwable $th) {
            return  $this->rest->responseFailed("Gagal mengubah data", "process", [], [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
        }
    }
}
