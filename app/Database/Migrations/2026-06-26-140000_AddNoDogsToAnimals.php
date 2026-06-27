<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoDogsToAnimals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('animals', [
            'no_dogs' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'no_other_dogs'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('animals', 'no_dogs');
    }
}
