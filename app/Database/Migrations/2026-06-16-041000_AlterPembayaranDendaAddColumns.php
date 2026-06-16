<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPembayaranDendaAddColumns extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembayaran_denda', [
            'jumlah_denda' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'bukti_bayar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'catatan_admin' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'merchant_ref' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pembayaran_denda', ['jumlah_denda', 'bukti_bayar', 'catatan_admin', 'merchant_ref']);
    }
}
