<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToProjectComments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('project_comments', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('project_comments', 'deleted_at');
    }
}
