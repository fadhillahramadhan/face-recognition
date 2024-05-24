<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;
// user model
use App\Models\UserModel;

class Absence extends BaseController
{
    public function take()
    {
        $breadcumbs = [
            'Laporan' => [
                'active' => false,
                'href' => '/member/absence',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '/member/absence',
            ]
        ];

        return view('member/absenceTakeView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function add($id = null)
    {
        $breadcumbs = [
            'Laporan' => [
                'active' => false,
                'href' => '/member/absence',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '/member/absence',
            ]
        ];

        return view('member/absencePresentionView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }



    public function report(): string
    {
        $breadcumbs = [
            'Laporan' => [
                'active' => false,
                'href' => '/member/absence',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '/member/absence/report',
            ]
        ];

        return view('member/absenceReportView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function get_absence()
    {
        $tableName = "absence";
        $columns = [
            "absence.id" => "id",
            "absence.user_id" => "user_id",
            "absence.course_id" => "course_id",
            "courses.name" => "course_name",
            "absence.date" => "date",
            "absence.reason" => "reason",
            "absence.created_at" => "created_at",
            "absence.updated_at" => "updated_at",
        ];
        $joinTable = "
        JOIN courses_users ON absence.course_id = courses_users.course_id
        JOIN courses ON courses_users.course_id = courses.id
        JOIN users ON absence.user_id = users.id
        ";
        $whereCondition = "users.id = " . session('user')['id'];
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['created_at'] = $this->convertDatetime($value['created_at'], 'id');
        }


        $this->rest->responseSuccess("Data Courses", $data);
    }

    // take photo
    public function take_photo()
    {
        // file name based on email
        $user = new UserModel();
        $user = $user->where('id', session('user')['id'])->first();
        $email = $user['email'];

        $file = $this->request->getFile('webcam_image');
        $fileName = $email . '.' . $file->getExtension();
        $file->move('images', $fileName);

        // update user image
        $user = new UserModel();
        $user->update(session('user')['id'], [
            'image' => base_url('images/' . $fileName)
        ]);

        $this->rest->responseSuccess("Success", [
            'file_name' => $fileName,
        ]);
    }
}
