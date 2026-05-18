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

    public function search()
    {
        $keyword = $this->request->getGet('keyword');
        
        // Redirect kembali ke halaman sebelumnya (tempat semula) jika pencarian kosong
        if (empty(trim((string)$keyword))) {
            $returnUrl = $this->request->getGet('return_url');
            if ($returnUrl) {
                return redirect()->to($returnUrl);
            }
            return redirect()->back();
        }
        
        $bukuModel       = new BukuModel();
        $anggotaModel    = new AnggotaModel();
        $peminjamanModel = new PeminjamanModel();

        $buku       = [];
        $anggota    = [];
        $peminjaman = [];

        if ($keyword) {
            $buku = $bukuModel->groupStart()
                              ->like('judul', $keyword)
                              ->orLike('penulis', $keyword)
                              ->orLike('isbn', $keyword)
                              ->groupEnd()
                              ->findAll();

            $anggota = $anggotaModel->groupStart()
                                    ->like('nama', $keyword)
                                    ->orLike('email', $keyword)
                                    ->groupEnd()
                                    ->findAll();

            $peminjaman = $peminjamanModel->select('peminjaman.*, anggota.nama as nama_anggota')
                                          ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota', 'left')
                                          ->groupStart()
                                              ->like('peminjaman.id_peminjaman', $keyword)
                                              ->orLike('anggota.nama', $keyword)
                                          ->groupEnd()
                                          ->findAll();
        }

        $data = [
            'keyword'    => $keyword,
            'buku'       => $buku,
            'anggota'    => $anggota,
            'peminjaman' => $peminjaman,
        ];

        return view('dashboard/search_results', $data);
    }
}
