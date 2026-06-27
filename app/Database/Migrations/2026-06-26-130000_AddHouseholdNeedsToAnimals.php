<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHouseholdNeedsToAnimals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('animals', [
            'no_other_dogs' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'needs_fenced_yard'],
            'no_cats'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'no_other_dogs'],
            'no_kids'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'no_cats'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('animals', ['no_other_dogs', 'no_cats', 'no_kids']);
    }
}
