<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAddAlterAbsence extends Migration
{
    // CREATE TABLE `absence` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `user_id` INT(5) UNSIGNED NOT NULL,
    //     `course_id` INT(5) UNSIGNED NOT NULL,
    //     `date` DATE NOT NULL,
    //     `reason` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `created_at` DATETIME NULL DEFAULT NULL,
    //     `updated_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=2
    // ;


    public function up()
    {
        // add courses courses_users_id
        $this->forge->addColumn('absence', [
            'courses_users_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('absence', 'courses_users_id');
    }
}
