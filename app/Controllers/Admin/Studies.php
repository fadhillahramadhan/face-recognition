<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudiesModel;

class Studies extends BaseController
{
    public function index(): string
    {
        $breadcumbs = [
            'Data Master' => [
                'active' => false,
                'href' => '#',
            ],
            'Prodi' => [
                'active' => true,
                'href' => '/admin/studies',
            ]
        ];

        return view('admin/studiesView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function get_studies()
    {
        $tableName = "studies";
        $columns = [
            "studies.id" => "id",
            "studies.name" => "name",
            "studies.code" => "code", // add this line
            "studies.class" => "class", // add this line
        ];
        $joinTable = "";
        $whereCondition = "";
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        $this->rest->responseSuccess("Data", $data);
    }

    public function add_study()
    {
        $validate = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Dosen harus diisi',
                ]
            ],
            'code' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode Mata Kuliah harus diisi',
                ]
            ],

        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $password = (string) $this->request->getPost('password');


            $model = new StudiesModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'), // add this line
                'class' => $this->request->getPost('class') ?? 'A'
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

    public function update_study()
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
            'code' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode Mata Kuliah harus diisi',
                ]
            ],
        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $password = (string) $this->request->getPost('password');
            $model = new StudiesModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'), // add this line
                'class' => $this->request->getPost('class') ?? 'A'
            ];

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

    public function get_study($id)
    {
        $model = new StudiesModel();
        $data = $model->find($id);

        // check also absnce
        if ($data) {
            return $this->rest->responseSuccess("Data User", $data);
        } else {
            return $this->rest->responseFailed("Data tidak ditemukan");
        }
    }

    public function delete_study()
    {
        $id = $this->request->getPost('data');

        if (is_array($id)) {
            $success = $failed = 0;
            foreach ($id as $value) {
                $model = new StudiesModel();
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
            $message = 'Berhasil menghapus Data ';
            if ($success == 0) {
                $message = 'Gagal menghapus Data ';
            }
            return $this->rest->responseSuccess($message, $dataActive);
        } else {
            return $this->rest->responseFailed("Data tidak valid");
        }
    }
}
