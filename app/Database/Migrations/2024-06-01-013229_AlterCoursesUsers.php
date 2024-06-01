<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCoursesUsers extends Migration
{

    // CREATE TABLE `courses_users` (
    //     `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `course_id` INT(11) UNSIGNED NOT NULL,
    //     `user_id` INT(11) UNSIGNED NOT NULL,
    //     `scheduled_at` DATETIME NULL DEFAULT NULL,
    //     `expired_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=7
    // ;

    public function up()
    {
        // add study id to courses
        $this->forge->addColumn('courses_users', [
            'study_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'after' => 'user_id'
            ],
            'room_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'after' => 'study_id'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses_users', 'study_id');
        $this->forge->dropColumn('courses_users', 'room_id');
    }
}
