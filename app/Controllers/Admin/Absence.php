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

        $tableName = "courses_users";
        $columns = [
            "MONTH(courses_users.scheduled_at)" => "bulan",
            "YEAR(courses_users.scheduled_at)" => "tahun",
            "users.name" => "nama",
            "courses.code" => "kode",
            "courses.name" => "nama_matkul",
            "courses.sks" => "sks",
            "courses.status" => "status",
            "studies.class" => "kelas",
            "studies.name" => "jurusan",
            "SUM(IF(IFNULL(absence.id,0) > 0 ,1,0))" => "total_hadir",
            "SUM(IF(IFNULL(absence.id,0) > 0 ,0,1))" => "total_tidak_hadir"

        ];
        $joinTable = "
        LEFT JOIN absence ON courses_users.id = absence.courses_users_id
        JOIN courses ON courses.id = courses_users.course_id
        JOIN studies ON studies.id = courses_users.study_id
        JOIN users ON users.id = courses_users.user_id
        ";
        $whereCondition = "";
        $groupBy = "GROUP BY MONTH(courses_users.scheduled_at), 
        YEAR(courses_users.scheduled_at), 
        courses_users.user_id, 
        courses_users.course_id, 
        courses_users.study_id
        ";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);

        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['bulan'] = $this->convertMonth($value['bulan']);
        }

        $this->rest->responseSuccess("Data Courses", $data);
    }
}
