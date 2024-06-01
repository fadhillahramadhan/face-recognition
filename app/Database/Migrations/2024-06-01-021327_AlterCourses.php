<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCourses extends Migration
{
    // CREATE TABLE `courses` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `code` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `description` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `scheduled_at` DATETIME NULL DEFAULT NULL,
    //     `expired_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=5
    // ;

    public function up()
    {
        // add sks and status offline or online
        $this->forge->addColumn('courses', [
            'sks' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => false,
                'default' => 0,
                'after' => 'description',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['offline', 'online'],
                'null' => false,
                'default' => 'offline',
                'after' => 'sks',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'sks');
        $this->forge->dropColumn('courses', 'status');
    }
}
