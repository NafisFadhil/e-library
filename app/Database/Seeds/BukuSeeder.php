<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class BukuSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $data = [];

        for ($i = 0; $i < 60; $i++) {
            $data[] = [
                'isbn'         => $faker->isbn13(),
                'judul'        => $faker->sentence(3),
                'kategori'     => $faker->randomElement(['Fiksi', 'Non-Fiksi', 'Sains', 'Sejarah', 'Teknologi']),
                'url_cover'    => $faker->imageUrl(200, 300, 'books'),
                'tahun_terbit' => (string)$faker->year(),
                'penerbit'     => $faker->company(),
                'penulis'      => $faker->name(),
            ];
        }

        $this->db->table('buku')->insertBatch($data);
    }
}
