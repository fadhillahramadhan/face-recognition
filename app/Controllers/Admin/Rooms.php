<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoomsModel;

class Rooms extends BaseController
{
    public function index(): string
    {
        $breadcumbs = [
            'Data Master' => [
                'active' => false,
                'href' => '#',
            ],
            'Ruangan' => [
                'active' => true,
                'href' => '/admin/rooms',
            ]
        ];

        return view('admin/roomsView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function get_rooms()
    {
        $tableName = "rooms";
        $columns = [
            "rooms.id" => "id",
            "rooms.name" => "name",
            "rooms.code" => "code", // add this line
            "rooms.description" => "description",
            "rooms.created_at" => "created_at",
            "rooms.updated_at" => "updated_at",
        ];
        $joinTable = "";
        $whereCondition = "";
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['created_at'] = $this->convertDatetime($value['created_at'], 'id');
            $data['results'][$key]['updated_at'] = $this->convertDatetime($value['updated_at'], 'id');
        }

        $this->rest->responseSuccess("Data", $data);
    }

    public function add_room()
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


            $model = new RoomsModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'code' => $this->request->getPost('code'), // add this line
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

    public function update_room()
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
            $model = new RoomsModel();
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'code' => $this->request->getPost('code'), // add this line
                'updated_at' => date('Y-m-d H:i:s'),
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

    public function get_room($id)
    {
        $model = new RoomsModel();
        $data = $model->find($id);

        // check also absnce
        if ($data) {
            return $this->rest->responseSuccess("Data User", $data);
        } else {
            return $this->rest->responseFailed("Data tidak ditemukan");
        }
    }
}
