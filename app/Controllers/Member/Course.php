<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;

class Course extends BaseController
{
    public function index(): string
    {
        return view('member/courseScheduleView');
    }
}
