<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDescription extends Migration
{

    public function up()
    {
        // remove descriptions
        $this->forge->dropColumn('courses', 'description');
        $this->forge->dropColumn('studies', 'description');
    }

    public function down()
    {
        // add descriptions
        $this->forge->addColumn('courses', [
            'description' => [
                'type' => 'TEXT',
                'null' => false,
                'collate' => 'utf8mb4_general_ci',
            ],
        ]);

        $this->forge->addColumn('studies', [
            'description' => [
                'type' => 'TEXT',
                'null' => false,
                'collate' => 'utf8mb4_general_ci',
            ],
        ]);
    }
}
