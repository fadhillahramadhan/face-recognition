<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CoursesModel;
use App\Models\CoursesUsersModel;
use App\Models\RoomsModel;
use App\Models\StudiesModel;
use App\Models\UserModel;

class Addcourses extends BaseController
{
    public function index(): string
    {
        $studiesModel = new StudiesModel();
        $studies = $studiesModel->findAll();


        $breadcumbs = [
            'Dosen' => [
                'active' => false,
                'href' => '#',
            ],
            'Data Dosen' => [
                'active' => true,
                'href' => '/admin/addcourses',
            ]
        ];


        return view('admin/addCoursesView', [
            'breadcumbs' => $breadcumbs,
            'studies' => $studies,
        ]);
    }

    public function add($id): string
    {
        $breadcumbs = [
            'Data' => [
                'active' => false,
                'href' => '#',
            ],
            'Tambah Mata kuliah' => [
                'active' => true,
                'href' => '/admin/addcourses',
            ]
        ];

        $coursesModel = new CoursesModel();
        $courses = $coursesModel->findAll();

        $studiesModel = new StudiesModel();
        $studies = $studiesModel->findAll();

        $userModel = new UserModel();
        $users = $userModel->find($id);


        return view('admin/addCoursesViewSchedule', [
            'breadcumbs' => $breadcumbs,
            'courses' => $courses,
            'studies' => $studies,
            'user' => $users,
            'id' => $id
        ]);
    }

    public function get_courses_users($id)
    {
        $tableName = "courses_users";
        $columns = [
            "courses_users.id" => "id",
            "courses_users.course_id" => "course_id",
            "courses.name" => "name",
            "courses.code" => "code", // add this line
            'courses.sks' => 'sks',
            'courses.status' => 'status',
            // room and code
            'studies.class' => 'class',
            'studies.name' => 'study_name',
            'studies.code' => 'study_code',
            "courses_users.user_id" => "user_id",
            "DATE(courses_users.scheduled_at)" => "scheduled_at",
            "TIME(courses_users.scheduled_at)" => "scheduled_at_time",
            "DATE(courses_users.expired_at)" => "expired_at",
            "TIME(courses_users.expired_at)" => "expired_at_time",
        ];
        $joinTable = "
        JOIN users ON users.id = courses_users.user_id
        JOIN courses ON courses.id = courses_users.course_id
        JOIN studies ON studies.id = courses_users.study_id
        ";
        $whereCondition = "courses_users.user_id = $id";
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
            'course_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Mata kuliah harus diisi',
                ]
            ],
            'user_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'User harus diisi',
                ]
            ],
            'scheduled_at' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jadwal harus diisi',
                ]
            ],
            'expired_at' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kadaluarsa harus diisi',
                ]
            ],

        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $model = new CoursesUsersModel();

            // get study id from user
            $userModel = new UserModel();
            $user = $userModel->find($this->request->getPost('user_id'));

            $data = [
                'course_id' => $this->request->getPost('course_id'),
                'user_id' => $this->request->getPost('user_id'),
                'study_id' => $user['study_id'],
                'scheduled_at' => $this->request->getPost('scheduled_at'),
                'expired_at' => $this->request->getPost('expired_at'),
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
            'course_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Mata kuliah harus diisi',
                ]
            ],
            'user_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'User harus diisi',
                ]
            ],
            'scheduled_at' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jadwal harus diisi',
                ]
            ],
            'expired_at' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kadaluarsa harus diisi',
                ]
            ],
        ]);

        if (!$validate) {
            return $this->rest->responseFailed("Data tidak valid", "validation", $this->validator->getErrors());
        }

        try {
            $model = new CoursesUsersModel();

            $userModel = new UserModel();
            $user = $userModel->find($this->request->getPost('user_id'));

            $data = [
                'course_id' => $this->request->getPost('course_id'),
                'user_id' => $this->request->getPost('user_id'),
                'study_id' => $user['study_id'],
                'scheduled_at' => $this->request->getPost('scheduled_at'),
                'expired_at' => $this->request->getPost('expired_at'),
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
        $db = \Config\Database::connect();
        $builder = $db->table('courses_users');
        $builder->join('courses', 'courses.id = courses_users.course_id');
        $builder->join('users', 'users.id = courses_users.user_id');
        $builder->where('courses_users.id', $id);
        $data = $builder->get()->getRowArray();

        if ($data) {
            return $this->rest->responseSuccess("Data User", $data);
        } else {
            return $this->rest->responseFailed("Data tidak ditemukan");
        }
    }

    public function delete_course()
    {
        $id = $this->request->getPost('data');

        if (is_array($id)) {
            $success = $failed = 0;
            foreach ($id as $value) {
                $model = new CoursesUsersModel();
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
