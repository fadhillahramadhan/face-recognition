<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCoursesCode extends Migration
{
    // CREATE TABLE `courses` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `description` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `scheduled_at` DATETIME NULL DEFAULT NULL,
    //     `expired_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=5
    // ;

    // add code column
    public function up()
    {
        $this->forge->addColumn('courses', [
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'code');
    }
}
