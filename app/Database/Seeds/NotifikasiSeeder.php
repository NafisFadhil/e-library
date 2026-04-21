<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class NotifikasiSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $anggota = $this->db->table('anggota')->get()->getResult();
        $data = [];

        foreach ($anggota as $a) {
            for ($i = 0; $i < 3; $i++) {
                $data[] = [
                    'id_anggota'  => $a->id_anggota,
                    'waktu_kirim' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
                    'jenis'       => $faker->randomElement(['Info', 'Peringatan', 'Promo']),
                    'isi'         => $faker->paragraph(),
                    'status'      => $faker->randomElement(['Sudah Dibaca', 'Belum Dibaca']),
                ];
            }
        }

        $this->db->table('notifikasi')->insertBatch($data);
    }
}
