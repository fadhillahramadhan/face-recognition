<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{

    public function run()
    {
        $this->db->table('courses')->insert([
            'name' => 'CodeIgniter 4',
            'description' => 'Learn how to build web applications using CodeIgniter 4',
            'scheduled_at' => date('Y-m-d 17:00:00'),
            'expired_at' => date('Y-m-d 18:00:00'),
        ]);
    }
}
