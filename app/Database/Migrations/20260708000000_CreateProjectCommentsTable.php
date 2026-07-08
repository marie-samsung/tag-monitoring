<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectCommentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'project_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'comment' => [
                'type' => 'TEXT',
            ],
            'commented_by' => [
                'type'       => 'TINYINT',
                'constraint' => 4,
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
        $this->forge->addKey('project_name');
        $this->forge->createTable('project_comments', true);
    }

    public function down()
    {
        $this->forge->dropTable('project_comments', true);
    }
}
