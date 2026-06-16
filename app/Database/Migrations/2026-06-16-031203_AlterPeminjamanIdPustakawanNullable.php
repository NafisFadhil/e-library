<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPeminjamanIdPustakawanNullable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('peminjaman', [
            'id_pustakawan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        // Reverting this is tricky if there are actual NULL values.
        // But for safety, we define the down method.
        $this->forge->modifyColumn('peminjaman', [
            'id_pustakawan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
    }
}
