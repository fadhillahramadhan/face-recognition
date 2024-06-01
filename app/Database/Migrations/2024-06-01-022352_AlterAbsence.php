<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterAbsence extends Migration
{
    // CREATE TABLE `absence` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `user_id` INT(5) UNSIGNED NOT NULL,
    //     `course_id` INT(5) UNSIGNED NOT NULL,
    //     `date` DATE NOT NULL,
    //     `reason` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `created_at` DATETIME NULL DEFAULT NULL,
    //     `updated_at` DATETIME NULL DEFAULT NULL,
    //     `courses_users_id` INT(5) UNSIGNED NOT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=9
    // ;

    public function up()
    {
        // add study id and room id
        $this->forge->addColumn('absence', [
            'study_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'after' => 'course_id',
            ],
            'room_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'after' => 'study_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('absence', 'study_id');
        $this->forge->dropColumn('absence', 'room_id');
    }
}
