<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaranDenda extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pembayaran' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_peminjaman' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'metode_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'tripay_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'transaction_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'status_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'waktu_pembayaran' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_pembayaran', true);
        $this->forge->addForeignKey('id_peminjaman', 'peminjaman', 'id_peminjaman', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pembayaran_denda');
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran_denda');
    }
}
