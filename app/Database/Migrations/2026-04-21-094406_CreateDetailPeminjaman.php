<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_peminjaman' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kode_eksemplar' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'tanggal_kembali' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'denda' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
        ]);
        $this->forge->addKey(['id_peminjaman', 'kode_eksemplar'], true);
        $this->forge->addForeignKey('id_peminjaman', 'peminjaman', 'id_peminjaman', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kode_eksemplar', 'eksemplar', 'kode', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_peminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('detail_peminjaman');
    }
}
