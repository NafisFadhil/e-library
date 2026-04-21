<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEksemplar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'isbn' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'kondisi' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'lokasi_rak' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'ketersediaan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
        ]);
        $this->forge->addKey('kode', true);
        $this->forge->addForeignKey('isbn', 'buku', 'isbn', 'CASCADE', 'CASCADE');
        $this->forge->createTable('eksemplar');
    }

    public function down()
    {
        $this->forge->dropTable('eksemplar');
    }
}
