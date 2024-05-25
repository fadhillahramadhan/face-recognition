<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsenceModel;
use App\Models\CoursesUsersModel;
// user model
use App\Models\UserModel;

class Absence extends BaseController
{
    public function index()
    {
        $breadcumbs = [
            'Laporan' => [
                'active' => false,
                'href' => '/admin/absence',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '/admin/absence',
            ]
        ];
        return view('admin/absenceReportView', [
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
            "users.name" => "user_name",
            "users.email" => "user_email",
            "absence.date" => "date",
            "absence.reason" => "reason",
            "absence.created_at" => "created_at",
            "absence.updated_at" => "updated_at",
        ];
        $joinTable = "
        JOIN courses ON courses.id = absence.course_id
        JOIN users ON users.id = absence.user_id
        ";
        $whereCondition = "";
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['created_at'] = $this->convertDatetime($value['created_at'], 'id');
        }

        $this->rest->responseSuccess("Data Courses", $data);
    }
}
