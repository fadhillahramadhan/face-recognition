<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoursesUsers extends Seeder
{
    public function run()
    {
        $data = [
            [
                'course_id' => 1,
                'user_id' => 1,
                'scheduled_at' => '2024-04-21 08:00:00',
                'expired_at' => '2024-04-21 09:00:00',
            ],
        ];

        // Using Query Builder
        $this->db->table('courses_users')->insertBatch($data);
    }
}
