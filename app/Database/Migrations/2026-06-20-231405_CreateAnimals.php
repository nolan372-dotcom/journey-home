<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnimals extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'species'     => ['type' => 'ENUM', 'constraint' => ['dog', 'cat', 'other'], 'default' => 'dog'],
            'breed'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'age_group'   => ['type' => 'ENUM', 'constraint' => ['puppy/kitten', 'young', 'adult', 'senior'], 'default' => 'adult'],
            'size'        => ['type' => 'ENUM', 'constraint' => ['small', 'medium', 'large'], 'default' => 'medium'],
            'intake_date' => ['type' => 'DATE'],
            'status'      => ['type' => 'ENUM', 'constraint' => ['needs_foster', 'in_foster', 'adopted'], 'default' => 'needs_foster'],
            'notes'       => ['type' => 'TEXT', 'null' => true],
            'photo_url'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('animals');
    }

    public function down()
    {
        $this->forge->dropTable('animals');
    }
}
