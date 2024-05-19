<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $breadcumbs = [
            'Home' => [
                'active' => false,
                'href' => '/admin/absence',
            ],
            'Dashboard' => [
                'active' => true,
                'href' => '/admin/absence',
            ]
        ];


        return view('admin/dashboardView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }
}
