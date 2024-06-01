<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsenceModel;
use App\Models\CoursesModel;

class Courses extends BaseController
{
    public function index(): string
    {
        $breadcumbs = [
            'Data Master' => [
                'active' => false,
                'href' => '#',
            ],
            'Mata Kuliah' => [
                'active' => true,
                'href' => '/admin/user',
            ]
        ];


        return view('admin/coursesView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function get_courses()
    {
        $tableName = "courses";
        $columns = [
            "courses.id" => "id",
            "courses.name" => "name",
            "courses.code" => "code", // add this line
            "courses.sks" => "sks",
            "courses.status" => "status",
            "courses.description" => "description",
            "courses.scheduled_at" => "scheduled_at",
            "courses.expired_at" => "expired_at",
        ];
        $joinTable = "";
        $whereCondition = "";
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['scheduled_at'] = $this->convertDatetime($value['scheduled_at'], 'id');
            $data['results'][$key]['expired_at'] = $this->convertDatetime($value['expired_at'], 'id');
        }

        $this->rest->responseSuccess("Data", $data);
    }

    public function add_course()
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
            'sks' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'SKS harus diisi',
                ]
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status harus diisi',
                ]
            ],

        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $password = (string) $this->request->getPost('password');


            $model = new CoursesModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'code' => $this->request->getPost('code'), // add this line
                'sks' => $this->request->getPost('sks') ?? 0,
                'status' => $this->request->getPost('status') ?? 'offline',
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

    public function update_course()
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
            $model = new CoursesModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'code' => $this->request->getPost('code'), // add this line
                'sks' => $this->request->getPost('sks') ?? 0,
                'status' => $this->request->getPost('status') ?? 'offline',
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

    public function get_course($id)
    {
        $model = new CoursesModel();
        $data = $model->find($id);

        // check also absnce
        if ($data) {
            return $this->rest->responseSuccess("Data User", $data);
        } else {
            return $this->rest->responseFailed("Data tidak ditemukan");
        }
    }
}
