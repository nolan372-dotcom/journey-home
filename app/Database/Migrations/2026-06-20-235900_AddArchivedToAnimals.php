<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArchivedToAnimals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('animals', [
            'archived' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'photo_url',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('animals', 'archived');
    }
}
