<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\EksemplarModel;

class Dashboard extends BaseController
{
    public function anggota()
    {
        $peminjamanModel = new PeminjamanModel();
        $bukuModel       = new BukuModel();
        $eksemplarModel  = new EksemplarModel();
        $userId          = session()->get('user_id');

        $data = [
            'nama'            => session()->get('nama'),
            'buku_dipinjam'   => $peminjamanModel->where('id_anggota', $userId)->where('status_peminjaman', 'Dipinjam')->countAllResults(),
            'total_riwayat'   => $peminjamanModel->where('id_anggota', $userId)->countAllResults(),
            'total_buku'      => $bukuModel->countAllResults(),
        ];
        return view('dashboard/anggota', $data);
    }

    public function pustakawan()
    {
        $anggotaModel    = new AnggotaModel();
        $bukuModel       = new BukuModel();
        $peminjamanModel = new PeminjamanModel();

        $data = [
            'nama'              => session()->get('nama'),
            'total_anggota'     => $anggotaModel->countAllResults(),
            'total_buku'        => $bukuModel->countAllResults(),
            'peminjaman_aktif'  => $peminjamanModel->where('status_peminjaman', 'Dipinjam')->countAllResults(),
        ];
        return view('dashboard/pustakawan', $data);
    }
}
