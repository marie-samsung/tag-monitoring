<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJsCodeToTags extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tags', [
            'js_code' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'default' => null,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tags', 'js_code');
    }
}
