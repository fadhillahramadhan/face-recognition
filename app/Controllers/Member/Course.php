<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;
use App\Models\AbsenceModel;

class Course extends BaseController
{
    public function index()
    {
        $breadcumbs = [
            'Home' => [
                'active' => false,
                'href' => '/member/course',
            ],
            'Course' => [
                'active' => true,
                'href' => '/member/course',
            ]
        ];
        return view('member/courseScheduleView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function get_courses()
    {
        $tableName = "courses";
        $columns = [
            "courses_users.id" => "id",
            "courses.name" => "name",
            "courses.code" => "code",
            "studies.class" => "class",
            "courses.sks" => "sks",
            'scheduled_at' => 'scheduled_at_the_time',
            'expired_at' => 'expired_at_the_time',
            "DATE(courses_users.scheduled_at)" => "scheduled_at",
            "TIME(courses_users.scheduled_at)" => "scheduled_at_time",
            "DATE(courses_users.expired_at)" => "expired_at",
            "TIME(courses_users.expired_at)" => "expired_at_time",
        ];
        $joinTable = "
        JOIN courses_users ON courses.id = courses_users.course_id
        JOIN studies ON courses_users.study_id = studies.id
        JOIN users ON courses_users.user_id = users.id
        ";
        $whereCondition = "users.id = " . session('user')['id'];
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['is_enable'] = $this->isEnable($value['id'], $value['scheduled_at_the_time'], $value['expired_at_the_time']);

            $data['results'][$key]['scheduled_at'] = $this->convertDatetime($value['scheduled_at'], 'id');
            $data['results'][$key]['expired_at'] = $this->convertDatetime($value['expired_at'], 'id');
            // is button enable
        }


        $this->rest->responseSuccess("Data Courses", $data);
    }

    private function isEnable($courses_users_id, $scheduled_at, $expired_at)
    {
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');

        $absence = new AbsenceModel();
        $data = $absence->where('courses_users_id', $courses_users_id)->first();

        if ($data) {
            return false;
        }

        return $now >= $scheduled_at && $now <= $expired_at;
    }
}
