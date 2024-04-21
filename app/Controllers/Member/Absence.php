<?php

namespace App\Controllers\Member;


use App\Controllers\BaseController;

class Absence extends BaseController
{
    public function index(): string
    {
        $breadcumbs = [
            'Laporan' => [
                'active' => false,
                'href' => '/member/absence',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '/member/absence',
            ]
        ];

        return view('member/absenceTakeView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function report(): string
    {
        $breadcumbs = [
            'Laporan' => [
                'active' => false,
                'href' => '/member/absence',
            ],
            'Presensi' => [
                'active' => true,
                'href' => '/member/absence/report',
            ]
        ];

        return view('member/absenceReportView', [
            'breadcumbs' => $breadcumbs,
        ]);
    }
}
