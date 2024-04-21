<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;

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
            "courses.id" => "id",
            "courses.name" => "name",
            "courses.description" => "description",
            "courses_users.scheduled_at",
            "courses_users.expired_at",
        ];
        $joinTable = "
        JOIN courses_users ON courses.id = courses_users.course_id
        JOIN users ON courses_users.user_id = users.id
        ";
        $whereCondition = "users.id = " . session('user')['id'];
        $groupBy = "";

        $data = $this->dataTable->getListDataTable($this->request, $tableName, $columns, $joinTable, $whereCondition, $groupBy);


        foreach ($data['results'] as $key => $value) {
            $data['results'][$key]['scheduled_at'] = $this->convertDatetime($value['scheduled_at'], 'id');
            $data['results'][$key]['expired_at'] = $this->convertDatetime($value['expired_at'], 'id');
        }


        $this->rest->responseSuccess("Data Courses", $data);
    }
}
