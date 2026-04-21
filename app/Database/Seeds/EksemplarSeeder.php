<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class EksemplarSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $books = $this->db->table('buku')->get()->getResult();
        $data = [];

        foreach ($books as $book) {
            $numCopies = rand(2, 5);
            for ($i = 1; $i <= $numCopies; $i++) {
                $data[] = [
                    'kode'         => 'EKS-' . $book->isbn . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'isbn'         => $book->isbn,
                    'kondisi'      => $faker->randomElement(['Bagus', 'Rusak Ringan', 'Rusak Berat']),
                    'lokasi_rak'   => 'RAK-' . strtoupper($faker->bothify('?#')),
                    'ketersediaan' => 'Tersedia',
                ];
            }
        }

        $this->db->table('eksemplar')->insertBatch($data);
    }
}
