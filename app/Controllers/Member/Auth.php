<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function index(): string
    {
        return view('member/loginView');
    }
}
