<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableForEverything extends Migration
{

    // CREATE TABLE `courses` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `code` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `description` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `sks` INT(3) NOT NULL DEFAULT '0',
    //     `status` ENUM('offline','online') NOT NULL DEFAULT 'offline' COLLATE 'utf8mb4_general_ci',
    //     `scheduled_at` DATETIME NULL DEFAULT NULL,
    //     `expired_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=6
    // ;

    // CREATE TABLE `rooms` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `code` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `description` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `created_at` DATETIME NULL DEFAULT NULL,
    //     `updated_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=3
    // ;

    // CREATE TABLE `courses_users` (
    //     `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `course_id` INT(11) UNSIGNED NOT NULL,
    //     `user_id` INT(11) UNSIGNED NOT NULL,
    //     `study_id` INT(11) UNSIGNED NOT NULL,
    //     `room_id` INT(11) UNSIGNED NOT NULL,
    //     `scheduled_at` DATETIME NULL DEFAULT NULL,
    //     `expired_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=8
    // ;

    // CREATE TABLE `courses` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `code` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `description` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `sks` INT(3) NOT NULL DEFAULT '0',
    //     `status` ENUM('offline','online') NOT NULL DEFAULT 'offline' COLLATE 'utf8mb4_general_ci',
    //     `scheduled_at` DATETIME NULL DEFAULT NULL,
    //     `expired_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=6
    // ;


    // CREATE TABLE `users` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `email` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `created_at` DATETIME NULL DEFAULT NULL,
    //     `updated_at` DATETIME NULL DEFAULT NULL,
    //     `image` VARCHAR(255) NULL DEFAULT '' COLLATE 'utf8mb4_general_ci',
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=6
    // ;

    // CREATE TABLE `studies` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `code` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `class` ENUM('A','B') NOT NULL DEFAULT 'A' COLLATE 'utf8mb4_general_ci',
    //     `description` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `created_at` DATETIME NULL DEFAULT NULL,
    //     `updated_at` DATETIME NULL DEFAULT NULL,
    //     PRIMARY KEY (`id`) USING BTREE
    // )
    // COLLATE='utf8mb4_general_ci'
    // ENGINE=InnoDB
    // AUTO_INCREMENT=3
    // ;



    public function up()
    {
        // remove column status
        $this->forge->dropColumn('courses', 'status');
        // change status to W/O
        $this->forge->addColumn('courses', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['W', 'O'],
                'null' => false,
                'default' => 'O',
                'after' => 'sks',
            ],
        ]);

        // drop rooms
        $this->forge->dropTable('rooms');
        // drop room_id and study_id
        $this->forge->dropColumn('absence', 'room_id');

        $this->forge->dropColumn('courses_users', 'room_id');


        // remove scheduled_at and expired_at
        $this->forge->dropColumn('courses', 'scheduled_at');
        $this->forge->dropColumn('courses', 'expired_at');

        // add study id to users
        $this->forge->addColumn('users', [
            'study_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'after' => 'id',
            ],
        ]);

        // add class to studies A and B
        $this->forge->addColumn('studies', [
            'class' => [
                'type' => 'ENUM',
                'constraint' => ['A', 'B'],
                'null' => false,
                'default' => 'A',
                'after' => 'name',
            ],
        ]);

        // remove created_at and updated_at
        $this->forge->dropColumn('studies', 'created_at');
        $this->forge->dropColumn('studies', 'updated_at');
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'status');
        $this->forge->addColumn('courses', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['offline', 'online'],
                'null' => false,
                'default' => 'offline',
                'after' => 'sks',
            ],
        ]);

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('rooms');

        // add room_id and study_id
        $this->forge->addColumn('absence', [
            'room_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'after' => 'study_id',
            ],
        ]);

        $this->forge->addColumn('courses_users', [
            'room_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'after' => 'study_id',
            ],
        ]);

        // add scheduled_at and expired_at
        $this->forge->addColumn('courses', [
            'scheduled_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status',
            ],
            'expired_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'scheduled_at',
            ],
        ]);

        // drop study id from users
        $this->forge->dropColumn('users', 'study_id');

        // drop class from studies
        $this->forge->dropColumn('studies', 'class');

        // add created_at and updated_at
        $this->forge->addColumn('studies', [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'description',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'created_at',
            ],
        ]);
    }
}
