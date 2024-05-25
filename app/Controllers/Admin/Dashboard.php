<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;
use Config\Database;

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
            'chart' => $this->getChart(),
            'absence' => $this->getAbsenceAndNotAbsence()['absence'] ?? 0,
            'not_absence' => $this->getAbsenceAndNotAbsence()['not_absence'] ?? 0
        ]);
    }

    public function getAbsenceAndNotAbsence()
    {
        $db = Database::connect();

        $builder = $db->table('courses_users');
        $builder->select(
            '
        SUM(IF(IFNULL(absence.id,0) > 0, 1,0)) AS absence,
        SUM(IF(IFNULL(absence.id,0) > 0, 0,1)) AS not_absence'
        );
        $builder->join('absence', 'absence.courses_users_id = courses_users.id', 'left');
        $query = $builder->get();

        $result = $query->getRow();


        return [
            'absence' => $result->absence,
            'not_absence' => $result->not_absence
        ];
    }

    public function getChart()
    {
        $db = Database::connect();

        $builder = $db->table('courses_users');
        $builder->select(
            '
        SUM(IF(IFNULL(absence.id,0) > 0, 1,0)) AS absence,
        SUM(IF(IFNULL(absence.id,0) > 0, 0,1)) AS not_absence,
        DATE(scheduled_at) AS date'
        );
        $builder->join('absence', 'absence.courses_users_id = courses_users.id', 'left');
        $builder->groupBy('DATE(scheduled_at)');
        $query = $builder->get();

        $result = $query->getResult();

        $date = [];
        $absence = [];
        $not_absence = [];
        foreach ($result as $row) {
            $date[] = $row->date;
            $absence[] = $row->absence;
            $not_absence[] = $row->not_absence;
        }

        return [
            'date' => $date,
            'absence' => $absence,
            'not_absence' => $not_absence
        ];
    }
}
