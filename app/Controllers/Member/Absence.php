<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;
use App\Models\AbsenceModel;
use App\Models\CoursesUsersModel;
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
        if ($id) {
            // set session absence id
            session()->set('absence_id', $id);
            // remove /1 from url
            return redirect()->to('/member/absence/add');
        }

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
        JOIN courses ON courses.id = absence.course_id
        ";
        $whereCondition = "user_id = " . session('user')['id'];
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['created_at'] = $this->convertDatetime($value['created_at'], 'id');
        }


        $this->rest->responseSuccess("Data Courses", $data);
    }

    // presence
    // CREATE TABLE `absence` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `user_id` INT(5) UNSIGNED NOT NULL,
    //     `course_id` INT(5) UNSIGNED NOT NULL,
    //     `date` DATE NOT NULL,
    //     `reason` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `created_at` DATETIME NULL DEFAULT NULL,
    //     `updated_at` DATETIME NULL DEFAULT NULL,
    //     `courses_users_id` INT(5) UNSIGNED NOT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=2
    // ;

    public function presence()
    {
        $courses_user = new CoursesUsersModel();
        $courses_user = $courses_user->where('id', session('absence_id'))->first();


        $absence = new AbsenceModel();
        $absence->insert([
            'user_id' => session('user')['id'],
            'course_id' => $courses_user['course_id'],
            'date' => date('Y-m-d'),
            'reason' => 'Presensi',
            'courses_users_id' => session('absence_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->rest->responseSuccess("Success");
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
        $file->move('images', $fileName, true);

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
