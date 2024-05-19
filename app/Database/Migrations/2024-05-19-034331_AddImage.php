<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImage extends Migration
{
    // CREATE TABLE `users` (
    //     `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    //     `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `email` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    //     `password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
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

        $this->forge->addColumn('users', [
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => '',
            ],
        ]);

        $this->forge->addColumn('admin', [
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => '',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'image');
        $this->forge->dropColumn('admin', 'image');
    }
}
