<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CoursesUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expired_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('courses_users');
    }

    public function down()
    {
        $this->forge->dropTable('courses_users');
    }
}
