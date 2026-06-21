<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFosterHomes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 150],
            'phone'            => ['type' => 'VARCHAR', 'constraint' => 20],
            'area_zip'         => ['type' => 'VARCHAR', 'constraint' => 10],
            'species_accepted' => ['type' => 'ENUM', 'constraint' => ['dog', 'cat', 'both'], 'default' => 'both'],
            'size_preference'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'max_capacity'     => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 1],
            'has_kids'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'has_other_pets'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'status'           => ['type' => 'ENUM', 'constraint' => ['active', 'paused', 'inactive'], 'default' => 'active'],
            'notes'            => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('foster_homes');
    }

    public function down()
    {
        $this->forge->dropTable('foster_homes');
    }
}
