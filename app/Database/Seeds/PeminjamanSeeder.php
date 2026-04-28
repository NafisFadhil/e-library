<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PeminjamanSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $anggota = $this->db->table('anggota')->get()->getResult();
        $pustakawan = $this->db->table('pustakawan')->get()->getResult();
        $data = [];

        for ($i = 0; $i < 40; $i++) {
            $tglPengajuan = $faker->dateTimeBetween('-1 month', 'now');
            $status = $faker->randomElement(['Diajukan', 'Dipinjam', 'Selesai', 'Ditolak']);
            
            $tglPinjam = ($status != 'Diajukan' && $status != 'Ditolak') ? clone $tglPengajuan : null;
            if ($tglPinjam) {
                $tglPinjam->modify('+1 day');
            }
            
            $tglJatuhTempo = ($tglPinjam) ? clone $tglPinjam : null;
            if ($tglJatuhTempo) {
                $tglJatuhTempo->modify('+7 days');
            }

            $data[] = [
                'id_anggota'        => $faker->randomElement($anggota)->id_anggota,
                'id_pustakawan'     => $faker->randomElement($pustakawan)->id_pustakawan,
                'tanggal_pengajuan' => $tglPengajuan->format('Y-m-d H:i:s'),
                'tanggal_pinjam'     => $tglPinjam ? $tglPinjam->format('Y-m-d H:i:s') : null,
                'tanggal_jatuh_tempo' => $tglJatuhTempo ? $tglJatuhTempo->format('Y-m-d H:i:s') : null,
                'status_peminjaman' => $status,
            ];
        }

        $this->db->table('peminjaman')->insertBatch($data);
    }
}
