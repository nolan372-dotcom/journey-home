<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomCanHandleToFosterHomes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('foster_homes', [
            'custom_can_handle' => ['type' => 'TEXT', 'null' => true, 'default' => null, 'after' => 'has_fenced_yard'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('foster_homes', 'custom_can_handle');
    }
}
