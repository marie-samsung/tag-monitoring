<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRequestedNameToTagsTable extends Migration
{
    public function up()
    {
        $fields = [
            'requested_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'default'    => null,
                'after'      => 'requested_date',
            ],
        ];
        $this->forge->addColumn('tags', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tags', 'requested_name');
    }
}
