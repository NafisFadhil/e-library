<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsAdminToPustakawan extends Migration
{
    public function up()
    {
        $fields = [
            'is_admin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'password',
            ],
        ];
        $this->forge->addColumn('pustakawan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pustakawan', 'is_admin');
    }
}
