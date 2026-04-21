<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('BukuSeeder');
        $this->call('AnggotaSeeder');
        $this->call('PustakawanSeeder');
        $this->call('EksemplarSeeder');
        $this->call('PeminjamanSeeder');
        $this->call('DetailPeminjamanSeeder');
        $this->call('PembayaranDendaSeeder');
        $this->call('NotifikasiSeeder');
    }
}
