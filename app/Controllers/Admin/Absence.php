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
            'Data Rekapitulasi' => [
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
            "MONTH(absence.date)" => "bulan",
            "YEAR(absence.date)" => "tahun",
            "users.name" => "user_name",
            "courses.code" => "course_code",
            "courses.name" => "course_name",
            "studies.name" => "study_name",
            "courses.status" => "status",
            "courses.sks" => "sks",
            "COUNT(*)" => "total_absence",
        ];
        $joinTable = "
        JOIN users ON users.id = absence.user_id
        JOIN courses ON courses.id = absence.course_id
        JOIN studies ON studies.id = absence.study_id
        ";
        $whereCondition = "";
        $groupBy = "GROUP BY MONTH(absence.date),YEAR(absence.date),user_id,course_id,study_id
        ";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['bulan'] = $this->convertMonth($value['bulan']);
        }

        $this->rest->responseSuccess("Data Courses", $data);
    }
}
