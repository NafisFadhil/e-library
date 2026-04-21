<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePustakawan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pustakawan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
        $this->forge->addKey('id_pustakawan', true);
        $this->forge->createTable('pustakawan');
    }

    public function down()
    {
        $this->forge->dropTable('pustakawan');
    }
}
