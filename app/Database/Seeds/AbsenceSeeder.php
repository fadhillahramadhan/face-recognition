<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('absence')->insert([
            'id' => 1,
            'course_id' => 1,
            'user_id' => 1,
            'date' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
