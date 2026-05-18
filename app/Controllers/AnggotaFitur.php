<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\DetailPeminjamanModel;

class AnggotaFitur extends BaseController
{
    protected $bukuModel;
    protected $peminjamanModel;
    protected $detailModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->bukuModel       = new BukuModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailModel     = new DetailPeminjamanModel();
    }

    /**
     * Halaman Cari Buku — anggota bisa melihat koleksi buku perpustakaan.
     */
    public function cariBuku()
    {
        $keyword = $this->request->getGet('q');

        $builder = $this->bukuModel->getWithStockCount();

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('buku.judul', $keyword)
                    ->orLike('buku.penulis', $keyword)
                    ->orLike('buku.kategori', $keyword)
                    ->orLike('buku.isbn', $keyword)
                    ->groupEnd();
        }

        $data = [
            'buku'    => $builder->paginate(10, 'buku'),
            'pager'   => $this->bukuModel->pager,
            'keyword' => $keyword,
        ];
        return view('anggota_fitur/cari_buku', $data);
    }

    /**
     * Halaman Riwayat Pinjam — anggota bisa melihat riwayat peminjaman miliknya.
     */
    public function riwayatPinjam()
    {
        $userId = session()->get('user_id');

        $data = [
            'peminjaman' => $this->peminjamanModel
                ->select('peminjaman.*, pustakawan.nama as nama_pustakawan')
                ->join('pustakawan', 'pustakawan.id_pustakawan = peminjaman.id_pustakawan', 'left')
                ->where('peminjaman.id_anggota', $userId)
                ->orderBy('peminjaman.id_peminjaman', 'DESC')
                ->paginate(10, 'peminjaman'),
            'pager' => $this->peminjamanModel->pager,
        ];
        return view('anggota_fitur/riwayat_pinjam', $data);
    }

    /**
     * Detail peminjaman milik anggota.
     */
    public function detailPinjam($id)
    {
        $userId = session()->get('user_id');

        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, pustakawan.nama as nama_pustakawan')
            ->join('pustakawan', 'pustakawan.id_pustakawan = peminjaman.id_pustakawan', 'left')
            ->where('peminjaman.id_anggota', $userId)
            ->find($id);

        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/riwayat-pinjam');
        }

        $detail = $this->detailModel->getByPeminjaman($id);

        $data = [
            'peminjaman' => $peminjaman,
            'detail'     => $detail,
        ];
        return view('anggota_fitur/detail_pinjam', $data);
    }
}
