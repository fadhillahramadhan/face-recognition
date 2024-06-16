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

        $current_datetime = date('Y-m-d H:i:s');
        $tableName = "courses_users";
        $columns = [
            "courses.code" => "kode",
            "courses.name" => "nama_matkul",
            "studies.class" => "class", // "studies.class" => "class
            "courses.sks" => "sks",
            // "courses_users.scheduled_at" => "waktu_mulai",
            // "courses_users.expired_at" => "waktu_akhir",
            "DATE(courses_users.scheduled_at)" => "waktu_mulai",
            "TIME(courses_users.scheduled_at)" => "waktu_mulai_time",
            "DATE(courses_users.expired_at)" => "waktu_akhir",
            "TIME(courses_users.expired_at)" => "waktu_akhir_time",
            "IF(IFNULL(absence.id,0)>0,'Hadir','Tidak Hadir')" => "kehadiran",
            "IFNULL(absence.`status`,'-')" => "status_online"
        ];
        $joinTable = "
        LEFT JOIN absence ON courses_users.id = absence.courses_users_id
        JOIN courses ON courses.id = courses_users.course_id
        JOIN studies ON studies.id = courses_users.study_id
        JOIN users ON users.id = courses_users.user_id
        ";
        // $whereCondition = "user_id = " . session('user')['id'];
        $whereCondition = "courses_users.scheduled_at <= '$current_datetime' AND courses_users.user_id = " . session('user')['id'];
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['waktu_mulai'] = $this->convertDatetime($value['waktu_mulai'], 'id');
            $data['results'][$key]['waktu_akhir'] = $this->convertDatetime($value['waktu_akhir'], 'id');
            // ucfirst status
            $data['results'][$key]['status_online'] = ucfirst($value['status_online']);
        }


        $this->rest->responseSuccess("Data Courses", $data);
    }



    public function presence()
    {
        $courses_user = new CoursesUsersModel();
        $courses_user = $courses_user->where('id', session('absence_id'))->first();

        if (!$courses_user) {
            $this->rest->responseFailed("Data not found");
        }


        $check_absence = new AbsenceModel();
        $check_absence = $check_absence->where('courses_users_id', session('absence_id'))->first();

        if ($check_absence) {
            return $this->rest->responseFailed("Anda sudah melakukan presensi");
        }

        $absence = new AbsenceModel();
        $absence->insert([
            'user_id' => session('user')['id'],
            'course_id' => $courses_user['course_id'],
            'study_id' => $courses_user['study_id'],
            'date' => date('Y-m-d'),
            'accuracy' => $this->request->getVar('accuracy'),
            'status' => $this->request->getVar('status'),
            'reason' => 'Presensi',
            'courses_users_id' => session('absence_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->rest->responseSuccess("Success");
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


        $userModel = new UserModel();
        $user = $userModel->find(session('user')['id']);
        session()->set('user', $user);

        $this->rest->responseSuccess("Success", [
            'file_name' => $fileName,
        ]);
    }
}
