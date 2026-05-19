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
                'is_admin' => 1,
            ],
            [
                'nama'     => 'Pustakawan Pusat',
                'username' => 'pustakawan',
                'email'    => 'pustakawan@elib.com',
                'password' => password_hash('pustakawan123', PASSWORD_BCRYPT),
                'is_admin' => 0,
            ]
        ];

        $this->db->table('pustakawan')->insertBatch($data);
    }
}
