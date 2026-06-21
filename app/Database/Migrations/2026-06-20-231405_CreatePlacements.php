<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlacements extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'animal_id'      => ['type' => 'INT', 'unsigned' => true],
            'foster_home_id' => ['type' => 'INT', 'unsigned' => true],
            'start_date'     => ['type' => 'DATE'],
            'end_date'       => ['type' => 'DATE', 'null' => true],
            'notes'          => ['type' => 'TEXT', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('animal_id', 'animals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('foster_home_id', 'foster_homes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('placements');
    }

    public function down()
    {
        $this->forge->dropTable('placements');
    }
}
