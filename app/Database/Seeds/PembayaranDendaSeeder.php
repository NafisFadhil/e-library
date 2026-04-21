<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PembayaranDendaSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $details = $this->db->table('detail_peminjaman')->where('denda >', 0)->get()->getResult();
        $data = [];

        foreach ($details as $d) {
            $data[] = [
                'id_peminjaman'         => $d->id_peminjaman,
                'metode_pembayaran'     => $faker->randomElement(['Transfer Bank', 'E-Wallet', 'Tunai']),
                'tripay_reference'      => strtoupper($faker->bothify('DEV-T#######')),
                'transaction_reference' => strtoupper($faker->bothify('TRX#######')),
                'status_pembayaran'     => 'Berhasil',
                'waktu_pembayaran'      => (new \DateTime($d->tanggal_kembali))->modify('+1 hour')->format('Y-m-d H:i:s'),
            ];
        }

        if (!empty($data)) {
            $this->db->table('pembayaran_denda')->insertBatch($data);
        }
    }
}
