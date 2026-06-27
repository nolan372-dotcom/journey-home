<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNeedsFieldsToAnimals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('animals', [
            'needs_medical'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'notes'],
            'needs_behavior'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'needs_medical'],
            'needs_fenced_yard' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'needs_behavior'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('animals', ['needs_medical', 'needs_behavior', 'needs_fenced_yard']);
    }
}
