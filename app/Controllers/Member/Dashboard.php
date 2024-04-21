<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $breadcumbs = [
            'Home' => [
                'active' => false,
                'href' => '/member/absence',
            ],
            'Dashboard' => [
                'active' => true,
                'href' => '/member/absence',
            ]
        ];


        return view('member/dashboardView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }
}
