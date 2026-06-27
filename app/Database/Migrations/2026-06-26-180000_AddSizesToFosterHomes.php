<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSizesToFosterHomes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('foster_homes', [
            'ok_small'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'has_fenced_yard'],
            'ok_medium' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_small'],
            'ok_large'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_medium'],
            'ok_xlarge' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_large'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('foster_homes', ['ok_small', 'ok_medium', 'ok_large', 'ok_xlarge']);
    }
}
