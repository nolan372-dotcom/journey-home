<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoOtherCatsToAnimals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('animals', [
            'no_other_cats' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'no_cats'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('animals', 'no_other_cats');
    }
}
