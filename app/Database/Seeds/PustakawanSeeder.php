<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PustakawanSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $data = [
            [
                'nama'     => 'Admin E-Library',
                'username' => 'admin',
                'email'    => 'admin@elib.com',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
            ],
            [
                'nama'     => 'Pustakawan Pusat',
                'username' => 'pustakawan',
                'email'    => 'pustakawan@elib.com',
                'password' => password_hash('pustakawan123', PASSWORD_BCRYPT),
            ]
        ];

        $this->db->table('pustakawan')->insertBatch($data);
    }
}
