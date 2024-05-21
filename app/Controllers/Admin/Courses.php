<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CoursesModel;

class Courses extends BaseController
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

        if ($data) {
            return $this->rest->responseSuccess("Data User", $data);
        } else {
            return $this->rest->responseFailed("Data tidak ditemukan");
        }
    }
}
