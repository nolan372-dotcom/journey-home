<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPreferencesToFosterHomes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('foster_homes', [
            'ok_puppies'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'has_other_pets'],
            'ok_kittens'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_puppies'],
            'ok_medical'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_kittens'],
            'ok_large_dogs' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_medical'],
            'ok_behavior'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_large_dogs'],
            'has_fenced_yard' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'ok_behavior'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('foster_homes', [
            'ok_puppies', 'ok_kittens', 'ok_medical', 'ok_large_dogs', 'ok_behavior', 'has_fenced_yard',
        ]);
    }
}
