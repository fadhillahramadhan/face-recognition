<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOnlineAbsence extends Migration
{
    // CREATE TABLE `absence` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `user_id` INT(5) UNSIGNED NOT NULL,
    //     `course_id` INT(5) UNSIGNED NOT NULL,
    //     `study_id` INT(5) NOT NULL,
    //     `date` DATE NOT NULL,
    //     `reason` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `created_at` DATETIME NULL DEFAULT NULL,
    //     `updated_at` DATETIME NULL DEFAULT NULL,
    //     `courses_users_id` INT(5) UNSIGNED NOT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=11
    // ;

    public function up()
    {
        // add status online/offline
        $this->forge->addColumn('absence', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['online', 'offline'],
                'default' => 'offline',
                'after' => 'reason'
            ],
            'accuracy' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'after' => 'status',
                'default' => 0,
                'null' => false
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('absence', 'status');
        $this->forge->dropColumn('absence', 'accuracy');
    }
}
