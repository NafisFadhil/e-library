<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\DetailPeminjamanModel;
use App\Models\AnggotaModel;
use App\Models\EksemplarModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $detailModel;
    protected $anggotaModel;
    protected $eksemplarModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailModel     = new DetailPeminjamanModel();
        $this->anggotaModel    = new AnggotaModel();
        $this->eksemplarModel  = new EksemplarModel();
    }

    /**
     * Tampilkan daftar semua peminjaman.
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $model = $this->peminjamanModel->getWithRelations();

        if (!empty($keyword)) {
            $model->groupStart()
                  ->like('peminjaman.id_peminjaman', $keyword)
                  ->orLike('anggota.nama', $keyword)
                  ->orLike('pustakawan.nama', $keyword)
                  ->orLike('peminjaman.status_peminjaman', $keyword)
                  ->groupEnd();
        }

        $data = [
            'peminjaman' => $model->paginate(10, 'peminjaman'),
            'pager'      => $this->peminjamanModel->pager,
            'keyword'    => $keyword,
        ];
        return view('peminjaman/index', $data);
    }

    /**
     * Form tambah peminjaman baru.
     */
    public function create()
    {
        $data = [
            'anggota'   => $this->anggotaModel
                            ->groupStart()
                                ->where('status', 'Aktif')
                                ->orWhere('status', 'aktif')
                            ->groupEnd()
                            ->findAll(),
            'eksemplar' => $this->eksemplarModel
                            ->select('eksemplar.*, buku.judul as judul_buku')
                            ->join('buku', 'buku.isbn = eksemplar.isbn', 'left')
                            ->where('eksemplar.ketersediaan', 'Tersedia')
                            ->findAll(),
        ];
        return view('peminjaman/create', $data);
    }

    /**
     * Simpan peminjaman baru ke database.
     */
    public function store()
    {
        $rules = [
            'id_anggota'      => 'required|numeric',
            'kode_eksemplar'  => 'required',
        ];

        $messages = [
            'id_anggota' => [
                'required' => 'Anggota wajib dipilih.',
                'numeric'  => 'ID anggota tidak valid.',
            ],
            'kode_eksemplar' => [
                'required' => 'Minimal pilih 1 eksemplar buku untuk dipinjam.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $idAnggota      = $this->request->getPost('id_anggota');
        $kodeEksemplars = $this->request->getPost('kode_eksemplar');
        $idPustakawan   = session()->get('user_id');

        // Pastikan kode_eksemplar adalah array
        if (!is_array($kodeEksemplars)) {
            $kodeEksemplars = [$kodeEksemplars];
        }

        // Validasi: cek apakah anggota exist dan aktif
        $anggota = $this->anggotaModel->find($idAnggota);
        if (!$anggota || strtolower($anggota['status']) !== 'aktif') {
            session()->setFlashdata('error', 'Anggota tidak ditemukan atau tidak aktif.');
            return redirect()->back()->withInput();
        }

        // Validasi: cek apakah semua eksemplar tersedia
        foreach ($kodeEksemplars as $kode) {
            $eks = $this->eksemplarModel->find($kode);
            if (!$eks || $eks['ketersediaan'] !== 'Tersedia') {
                session()->setFlashdata('error', "Eksemplar dengan kode \"{$kode}\" tidak tersedia.");
                return redirect()->back()->withInput();
            }
        }

        $now = date('Y-m-d H:i:s');
        $tanggalPinjam    = $now;
        $tanggalJatuhTempo = date('Y-m-d H:i:s', strtotime('+7 days'));

        // Insert peminjaman
        $this->peminjamanModel->insert([
            'id_anggota'          => $idAnggota,
            'id_pustakawan'       => $idPustakawan,
            'tanggal_pengajuan'   => $now,
            'tanggal_pinjam'      => $tanggalPinjam,
            'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            'status_peminjaman'   => 'Dipinjam',
        ]);

        $idPeminjaman = $this->peminjamanModel->getInsertID();

        // Insert detail peminjaman & update ketersediaan eksemplar
        foreach ($kodeEksemplars as $kode) {
            $this->detailModel->insertDetail([
                'id_peminjaman'  => $idPeminjaman,
                'kode_eksemplar' => $kode,
                'tanggal_kembali' => null,
                'denda'          => 0,
            ]);

            // Update status eksemplar jadi "Dipinjam"
            $this->eksemplarModel->update($kode, ['ketersediaan' => 'Dipinjam']);
        }

        session()->setFlashdata('success', 'Peminjaman berhasil dibuat. ID Peminjaman: #' . $idPeminjaman);
        return redirect()->to('dashboard/peminjaman');
    }

    /**
     * Tampilkan detail peminjaman.
     */
    public function show($id)
    {
        $peminjaman = $this->peminjamanModel->getWithRelations()->find($id);
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/peminjaman');
        }

        $detail = $this->detailModel->getByPeminjaman($id);

        $data = [
            'peminjaman' => $peminjaman,
            'detail'     => $detail,
        ];
        return view('peminjaman/show', $data);
    }

    /**
     * Form edit peminjaman (ubah status).
     */
    public function edit($id)
    {
        $peminjaman = $this->peminjamanModel->getWithRelations()->find($id);
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/peminjaman');
        }

        $detail = $this->detailModel->getByPeminjaman($id);

        $data = [
            'peminjaman' => $peminjaman,
            'detail'     => $detail,
        ];
        return view('peminjaman/edit', $data);
    }

    /**
     * Update status peminjaman.
     */
    public function update($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/peminjaman');
        }

        $rules = [
            'status_peminjaman' => 'required|in_list[Diajukan,Dipinjam,Selesai,Ditolak]',
        ];

        $messages = [
            'status_peminjaman' => [
                'required' => 'Status peminjaman wajib dipilih.',
                'in_list'  => 'Status tidak valid. Pilih: Diajukan, Dipinjam, Selesai, atau Ditolak.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $newStatus = $this->request->getPost('status_peminjaman');
        $updateData = [
            'status_peminjaman' => $newStatus,
        ];

        // Jika status berubah menjadi "Selesai", set tanggal kembali pada semua detail
        if ($newStatus === 'Selesai') {
            $now = date('Y-m-d H:i:s');
            $details = $this->detailModel->where('id_peminjaman', $id)->findAll();

            foreach ($details as $detail) {
                // Update tanggal kembali jika belum ada
                if (empty($detail['tanggal_kembali'])) {
                    // Hitung denda jika terlambat (Rp 1.000 per hari)
                    $denda = 0;
                    if (!empty($peminjaman['tanggal_jatuh_tempo'])) {
                        $jatuhTempo = strtotime($peminjaman['tanggal_jatuh_tempo']);
                        $sekarang   = strtotime($now);
                        if ($sekarang > $jatuhTempo) {
                            $selisihHari = ceil(($sekarang - $jatuhTempo) / 86400);
                            $denda = $selisihHari * 1000;
                        }
                    }

                    $this->detailModel->db->table('detail_peminjaman')
                        ->where('id_peminjaman', $detail['id_peminjaman'])
                        ->where('kode_eksemplar', $detail['kode_eksemplar'])
                        ->update(['tanggal_kembali' => $now, 'denda' => $denda]);
                }

                // Kembalikan ketersediaan eksemplar
                $this->eksemplarModel->update($detail['kode_eksemplar'], ['ketersediaan' => 'Tersedia']);
            }
        }

        // Jika status "Ditolak", kembalikan ketersediaan eksemplar
        if ($newStatus === 'Ditolak') {
            $details = $this->detailModel->where('id_peminjaman', $id)->findAll();
            foreach ($details as $detail) {
                $this->eksemplarModel->update($detail['kode_eksemplar'], ['ketersediaan' => 'Tersedia']);
            }
        }

        // Jika status "Dipinjam", set tanggal pinjam dan jatuh tempo jika belum ada
        if ($newStatus === 'Dipinjam' && empty($peminjaman['tanggal_pinjam'])) {
            $updateData['tanggal_pinjam']      = date('Y-m-d H:i:s');
            $updateData['tanggal_jatuh_tempo'] = date('Y-m-d H:i:s', strtotime('+7 days'));
        }

        $this->peminjamanModel->update($id, $updateData);

        session()->setFlashdata('success', 'Status peminjaman berhasil diperbarui.');
        return redirect()->to('dashboard/peminjaman');
    }

    /**
     * Hapus peminjaman.
     */
    public function delete($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/peminjaman');
        }

        // Kembalikan ketersediaan eksemplar jika masih dipinjam
        if ($peminjaman['status_peminjaman'] === 'Dipinjam' || $peminjaman['status_peminjaman'] === 'Diajukan') {
            $details = $this->detailModel->where('id_peminjaman', $id)->findAll();
            foreach ($details as $detail) {
                $this->eksemplarModel->update($detail['kode_eksemplar'], ['ketersediaan' => 'Tersedia']);
            }
        }

        // Hapus detail dulu (karena FK), lalu peminjaman
        $this->detailModel->where('id_peminjaman', $id)->delete();
        $this->peminjamanModel->delete($id);

        session()->setFlashdata('success', 'Data peminjaman berhasil dihapus.');
        return redirect()->to('dashboard/peminjaman');
    }
}
