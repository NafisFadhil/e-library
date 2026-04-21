<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class DetailPeminjamanSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $peminjaman = $this->db->table('peminjaman')->get()->getResult();
        $eksemplar = $this->db->table('eksemplar')->get()->getResult();
        $data = [];

        foreach ($peminjaman as $p) {
            if ($p->status_peminjaman == 'Ditolak') continue;
            
            // Each loan has 1-2 books
            $numBooks = rand(1, 2);
            $selectedEksemplar = (array)array_rand($eksemplar, $numBooks);
            
            foreach ($selectedEksemplar as $idx) {
                $eks = $eksemplar[$idx];
                $tglKembali = null;
                $denda = 0;

                if ($p->status_peminjaman == 'Selesai') {
                    $tglKembali = clone new \DateTime($p->tanggal_jatuh_tempo);
                    // 30% chance of being late
                    if (rand(1, 10) > 7) {
                        $diff = rand(1, 5);
                        $tglKembali->modify("+$diff days");
                        $denda = $diff * 1000;
                    } else {
                        $tglKembali->modify("-" . rand(0, 3) . " days");
                    }
                }

                $data[] = [
                    'id_peminjaman'  => $p->id_peminjaman,
                    'kode_eksemplar' => $eks->kode,
                    'tanggal_kembali' => $tglKembali ? $tglKembali->format('Y-m-d H:i:s') : null,
                    'denda'          => $denda,
                ];
            }
        }

        $this->db->table('detail_peminjaman')->insertBatch($data);
    }
}
