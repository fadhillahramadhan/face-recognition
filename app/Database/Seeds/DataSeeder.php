<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataSeeder extends Seeder
{
    public function run()
    {
        // truncate first
        $this->db->table('users')->truncate();
        $this->db->table('courses')->truncate();
        $this->db->table('courses_users')->truncate();
        $this->db->table('absence')->truncate();
        $this->db->table('admin')->truncate();

        $this->call('UserSeeder');
        $this->call('CourseSeeder');
        $this->call('AbsenceSeeder');
        $this->call('AdminSeeder');
        $this->call('CoursesUsers');
    }

    // to run it call
    // php spark db:seed DataSeeder
}
