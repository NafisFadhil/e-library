<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\BukuModel;

class Home extends BaseController
{
    public function index(): string
    {
        $anggotaModel = new AnggotaModel();
        $bukuModel    = new BukuModel();

        $data = [
            'total_anggota_aktif' => $anggotaModel
                ->groupStart()
                    ->where('status', 'Aktif')
                    ->orWhere('status', 'aktif')
                ->groupEnd()
                ->countAllResults(),
            'total_koleksi_buku'  => $bukuModel->countAllResults(),
        ];
        return view('dashboard/index', $data);
    }

    public function fitur(): string
    {
        return view('dashboard/fitur');
    }
}
