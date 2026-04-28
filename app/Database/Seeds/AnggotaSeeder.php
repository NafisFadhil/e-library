<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $data = [];

        for ($i = 0; $i < 30; $i++) {
            $data[] = [
                'nama'       => $faker->name(),
                'no_telepon' => $faker->phoneNumber(),
                'email'      => $faker->unique()->email(),
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'status'     => 'Aktif',
            ];
        }

        $this->db->table('anggota')->insertBatch($data);
    }
}
