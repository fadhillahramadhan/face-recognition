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
        $start_date_of_this_month = date('Y-m-01');
        $end_date_of_this_month = date('Y-m-t');

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
            'start_date_of_this_month' => $start_date_of_this_month,
            'end_date_of_this_month' => $end_date_of_this_month
        ]);
    }

    public function get_absence($start_date = null, $end_date = null)
    {
        $current_datetime = date('Y-m-d H:i:s');
        $tableName = "courses_users";
        $columns = [
            "users.name" => "nama",
            "courses.code" => "kode",
            "courses.name" => "nama_matkul",
            "courses.sks" => "sks",
            "courses.status" => "status",
            "studies.class" => "kelas",
            "studies.name" => "jurusan",
            "SUM(IF(IFNULL(absence.id,0) > 0 ,1,0))" => "total_hadir",
            "SUM(IF(IFNULL(absence.id,0) > 0 ,0,1))" => "total_tidak_hadir",
            'SUM(IF(IFNULL(absence.`status`,"-") = "online",1,0))' => 'total_online',
            'SUM(IF(IFNULL(absence.`status`,"-") = "offline",1,0))' => 'total_offline'

        ];
        $joinTable = "
        LEFT JOIN absence ON courses_users.id = absence.courses_users_id
        JOIN courses ON courses.id = courses_users.course_id
        JOIN studies ON studies.id = courses_users.study_id
        JOIN users ON users.id = courses_users.user_id
        ";
        $whereCondition = "courses_users.scheduled_at <= '$current_datetime'";

        if ($start_date != null && $end_date != null) {
            $whereCondition .= " AND courses_users.scheduled_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
        }

        $groupBy = "GROUP BY 
        courses_users.user_id, 
        courses_users.course_id, 
        courses_users.study_id
        ";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);



        // foreach ($data['results'] as $key => $value) {
        //     $data['results'][$key]['date'] = $this->convertDate($value['date']);
        // }

        $this->rest->responseSuccess("Data Courses", $data);
    }
}
