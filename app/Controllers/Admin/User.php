<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    public function index(): string
    {
        $breadcumbs = [
            'Data' => [
                'active' => false,
                'href' => '#',
            ],
            'Dosen' => [
                'active' => true,
                'href' => '/admin/user',
            ]
        ];


        return view('admin/userView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function get_users()
    {
        $tableName = "users";
        $columns = [
            "users.id" => "id",
            "users.name" => "name",
            'users.image' => 'image', // Add this line to get the image column from the users table
            "users.email" => "email",
            "users.password" => "password",
            "users.study_id" => "study_id", // Add this line to get the study_id column from the users table
            "studies.name" => "study",
            "users.created_at" => "created_at",
            "users.updated_at" => "updated_at",
        ];
        $joinTable = "LEFT JOIN studies ON users.study_id = studies.id";
        $whereCondition = "";
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['created_at'] = $this->convertDatetime($value['created_at'], 'id');
            $data['results'][$key]['updated_at'] = $this->convertDatetime($value['updated_at'], 'id');
            $data['results'][$key]['image'] = $value['image'] == '' ? base_url() . 'assets/images/profile/user-1.jpg' :  $value['image']; // Add this line to get the image column from the users table
        }

        $this->rest->responseSuccess("Data User", $data);
    }

    public function add_user()
    {
        $validate = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Dosen harus diisi',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                    'is_unique' => 'Email sudah terdaftar',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus diisi',
                ]
            ],
        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $password = (string) $this->request->getPost('password');


            $model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'study_id' =>  $this->request->getPost('study_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $model->insert($data);

            return $this->rest->responseSuccess("Berhasil menambahkan data");
        } catch (\Throwable $th) {
            return  $this->rest->responseFailed("Gagal menambahkan data", "process", [], [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
        }
    }

    public function update_user()
    {
        $validate = $this->validate([
            'id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID harus diisi',
                ]
            ],
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Dosen harus diisi',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus diisi',
                ]
            ],
        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $password = (string) $this->request->getPost('password');
            $model = new UserModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'updated_at' => date('Y-m-d H:i:s'),
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            // if study id
            if ($this->request->getPost('study_id')) {
                $data['study_id'] = $this->request->getPost('study_id');
            }

            $model->update($this->request->getPost('id'), $data);

            return $this->rest->responseSuccess("Berhasil mengubah data");
        } catch (\Throwable $th) {
            return  $this->rest->responseFailed("Gagal mengubah data", "process", [], [
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);
        }
    }

    public function get_user($id)
    {
        $model = new UserModel();
        $data = $model->find($id);

        if ($data) {
            return $this->rest->responseSuccess("Data User", $data);
        } else {
            return $this->rest->responseFailed("Data tidak ditemukan");
        }
    }

    public function delete_users()
    {
        $id = $this->request->getPost('data');

        if (is_array($id)) {
            $success = $failed = 0;
            foreach ($id as $value) {
                $model = new UserModel();
                if ($model->delete($value)) {
                    $success++;
                } else {
                    $failed++;
                }
            }
            $dataActive = [
                'success' => $success,
                'failed' => $failed
            ];
            $message = 'Berhasil menghapus Data User!';
            if ($success == 0) {
                $message = 'Gagal menghapus Data User';
            }
            return $this->rest->responseSuccess($message, $dataActive);
        } else {
            return $this->rest->responseFailed("Data tidak valid");
        }
    }
}
