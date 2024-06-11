<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {

        $userModel = new AdminModel();

        $breadcumbs = [
            'Profile' => [
                'active' => false,
                'href' => '/admin/profile',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '#',
            ]
        ];

        return view('admin/profileView', [
            'breadcumbs' => $breadcumbs,
            'user' => $userModel->find(session('admin')['id']),
        ]);
    }

    public function update_image()
    {

        $userModel = new AdminModel();

        $breadcumbs = [
            'Profile' => [
                'active' => false,
                'href' => '/admin/profile',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '#',
            ]
        ];

        return view('admin/profileEditImage', [
            'breadcumbs' => $breadcumbs,
            'user' => $userModel->find(session('admin')['id']),
        ]);
    }


    public function update_user()
    {
        $userModel = new AdminModel();

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

            $userModel->update(session('admin')['id'], $data);



            return $this->rest->responseSuccess("Berhasil mengubah data");
        } catch (\Throwable $th) {
            return  $this->rest->responseFailed("Gagal mengubah data", "process", [], [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
        }
    }

    public function take_photo()
    {
        // file name based on email
        $user = new AdminModel();
        $user = $user->where('id', session('admin')['id'])->first();
        $email = $user['email'];

        $file = $this->request->getFile('webcam_image');
        $fileName = $email . '.' . $file->getExtension();
        $file->move('admin', $fileName, true);

        // update user image
        $user = new AdminModel();
        $user->update(session('admin')['id'], [
            'image' => base_url('admin/' . $fileName)
        ]);

        // set session back

        $user = $user->find(session('admin')['id']);
        session()->set('admin', $user);


        $this->rest->responseSuccess("Success", [
            'file_name' => $fileName,
        ]);
    }
}
