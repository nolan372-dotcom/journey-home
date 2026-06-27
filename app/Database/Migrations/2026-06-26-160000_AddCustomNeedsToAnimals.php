<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomNeedsToAnimals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('animals', [
            'custom_needs' => ['type' => 'TEXT', 'null' => true, 'default' => null, 'after' => 'no_kids'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('animals', 'custom_needs');
    }
}
