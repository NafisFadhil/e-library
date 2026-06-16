<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\DetailPeminjamanModel;
use App\Models\EksemplarModel;
use App\Models\PembayaranDendaModel;
use App\Models\AnggotaModel;

class AnggotaFitur extends BaseController
{
    protected $bukuModel;
    protected $peminjamanModel;
    protected $detailModel;
    protected $eksemplarModel;
    protected $pembayaranModel;
    protected $anggotaModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->bukuModel       = new BukuModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailModel     = new DetailPeminjamanModel();
        $this->eksemplarModel  = new EksemplarModel();
        $this->pembayaranModel = new PembayaranDendaModel();
        $this->anggotaModel    = new AnggotaModel();
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

        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, pustakawan.nama as nama_pustakawan')
            ->join('pustakawan', 'pustakawan.id_pustakawan = peminjaman.id_pustakawan', 'left')
            ->where('peminjaman.id_anggota', $userId)
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->paginate(10, 'peminjaman');

        $pembayaranStatus = [];
        if (!empty($peminjaman)) {
            $peminjamanIds = array_column($peminjaman, 'id_peminjaman');
            if (!empty($peminjamanIds)) {
                $pembayaranList = $this->pembayaranModel->whereIn('id_peminjaman', $peminjamanIds)->findAll();
                foreach ($pembayaranList as $p) {
                    $pembayaranStatus[$p['id_peminjaman']] = $p['status_pembayaran'];
                }
            }
        }

        $data = [
            'peminjaman'        => $peminjaman,
            'pager'             => $this->peminjamanModel->pager,
            'pembayaran_status' => $pembayaranStatus,
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
        $pembayaran = $this->pembayaranModel->getByPeminjaman($id);

        $data = [
            'peminjaman' => $peminjaman,
            'detail'     => $detail,
            'pembayaran' => $pembayaran,
        ];
        return view('anggota_fitur/detail_pinjam', $data);
    }

    /**
     * Mengajukan peminjaman buku dari halaman cari buku.
     */
    public function ajukanPinjam()
    {
        $isbn = $this->request->getPost('isbn');
        $userId = session()->get('user_id');

        if (!$isbn) {
            session()->setFlashdata('error', 'ISBN buku tidak valid.');
            return redirect()->back();
        }

        // Cari eksemplar yang tersedia untuk buku ini
        $eksemplar = $this->eksemplarModel
            ->where('isbn', $isbn)
            ->where('ketersediaan', 'Tersedia')
            ->first();

        if (!$eksemplar) {
            session()->setFlashdata('error', 'Maaf, buku ini sedang tidak tersedia untuk dipinjam.');
            return redirect()->back();
        }

        $now = date('Y-m-d H:i:s');

        // Buat record peminjaman dengan status Diajukan
        $this->peminjamanModel->insert([
            'id_anggota'          => $userId,
            'id_pustakawan'       => null,
            'tanggal_pengajuan'   => $now,
            'status_peminjaman'   => 'Diajukan',
        ]);
        $idPeminjaman = $this->peminjamanModel->getInsertID();

        // Buat record detail peminjaman
        $this->detailModel->insertDetail([
            'id_peminjaman'  => $idPeminjaman,
            'kode_eksemplar' => $eksemplar['kode'],
            'tanggal_kembali' => null,
            'denda'          => 0,
        ]);

        // Update status eksemplar jadi Dipinjam (agar tidak bisa dipinjam orang lain)
        $this->eksemplarModel->update($eksemplar['kode'], ['ketersediaan' => 'Dipinjam']);

        session()->setFlashdata('success', 'Pengajuan peminjaman berhasil. Silakan tunggu konfirmasi pustakawan.');
        return redirect()->to('dashboard/riwayat-pinjam');
    }

    /**
     * Pilih metode pembayaran denda.
     */
    public function pilihPembayaran($id)
    {
        $userId = session()->get('user_id');

        $peminjaman = $this->peminjamanModel
            ->where('id_anggota', $userId)
            ->find($id);

        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/riwayat-pinjam');
        }

        $pembayaran = $this->pembayaranModel->getByPeminjaman($id);
        if (!$pembayaran || $pembayaran['status_pembayaran'] === 'Lunas') {
            session()->setFlashdata('error', 'Tagihan denda tidak ditemukan atau sudah lunas.');
            return redirect()->to('dashboard/riwayat-pinjam/detail/' . $id);
        }

        $apiKey = env('TRIPAY_API_KEY') ?: 'api_key_anda';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        $channels = [];
        if (empty($error)) {
            $resObj = json_decode($response, true);
            if (isset($resObj['success']) && $resObj['success'] === true) {
                $channels = $resObj['data'];
            }
        }

        if (empty($channels)) {
            // Fallback list of channels if API fails or offline
            $channels = [
                ['code' => 'BRIVA', 'name' => 'BRI Virtual Account', 'group' => 'Virtual Account', 'active' => true],
                ['code' => 'BCAVA', 'name' => 'BCA Virtual Account', 'group' => 'Virtual Account', 'active' => true],
                ['code' => 'BNIVA', 'name' => 'BNI Virtual Account', 'group' => 'Virtual Account', 'active' => true],
                ['code' => 'MANDIRIVA', 'name' => 'Mandiri Virtual Account', 'group' => 'Virtual Account', 'active' => true],
                ['code' => 'PERMATAVA', 'name' => 'Permata Virtual Account', 'group' => 'Virtual Account', 'active' => true],
                ['code' => 'ALFAMART', 'name' => 'Alfamart', 'group' => 'Convenience Store', 'active' => true],
                ['code' => 'INDOMARET', 'name' => 'Indomaret', 'group' => 'Convenience Store', 'active' => true],
                ['code' => 'QRIS', 'name' => 'QRIS by ShopeePay', 'group' => 'E-Wallet', 'active' => true],
            ];
        }

        $data = [
            'peminjaman' => $peminjaman,
            'pembayaran' => $pembayaran,
            'channels'   => $channels,
        ];
        return view('anggota_fitur/pilih_pembayaran', $data);
    }

    /**
     * Proses transaksi pembayaran denda via Tripay.
     */
    public function bayarTripay($id)
    {
        $userId = session()->get('user_id');

        $peminjaman = $this->peminjamanModel
            ->where('id_anggota', $userId)
            ->find($id);

        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/riwayat-pinjam');
        }

        $pembayaran = $this->pembayaranModel->getByPeminjaman($id);
        if (!$pembayaran || $pembayaran['status_pembayaran'] === 'Lunas') {
            session()->setFlashdata('error', 'Tagihan denda tidak ditemukan atau sudah lunas.');
            return redirect()->to('dashboard/riwayat-pinjam/detail/' . $id);
        }

        $selectedMethod = $this->request->getPost('payment_method');
        if (empty($selectedMethod)) {
            session()->setFlashdata('error', 'Silakan pilih metode pembayaran Tripay.');
            return redirect()->back();
        }

        $anggota = $this->anggotaModel->find($userId);
        if (!$anggota) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
            return redirect()->back();
        }

        $apiKey       = env('TRIPAY_API_KEY') ?: 'api_key_anda';
        $privateKey   = env('TRIPAY_PRIVATE_KEY') ?: 'private_key_anda';
        $merchantCode = env('TRIPAY_MERCHANT_CODE') ?: 'kode_merchant_anda';
        $merchantRef  = 'DENDA-' . $id . '-' . time();
        $amount       = (int) $pembayaran['jumlah_denda'];

        $signature = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);

        $data = [
            'method'         => $selectedMethod,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $anggota['nama'] ?? 'Nama Pelanggan',
            'customer_email' => $anggota['email'] ?? 'emailpelanggan@domain.com',
            'customer_phone' => $anggota['no_telepon'] ?? '081234567890',
            'order_items'    => [
                [
                    'sku'      => 'DENDA-' . $id,
                    'name'     => 'Denda Peminjaman #' . $id,
                    'price'    => $amount,
                    'quantity' => 1,
                ]
            ],
            'return_url'   => base_url('dashboard/riwayat-pinjam/detail/' . $id),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => $signature
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if (!empty($error)) {
            session()->setFlashdata('error', 'Gagal menghubungi server Tripay: ' . $error);
            return redirect()->back();
        }

        $resObj = json_decode($response, true);
        if (isset($resObj['success']) && $resObj['success'] === true) {
            $reference = $resObj['data']['reference'];
            $payCode   = $resObj['data']['pay_code'] ?? '';
            $payName   = $resObj['data']['payment_name'] ?? $selectedMethod;
            
            $this->pembayaranModel->update($pembayaran['id_pembayaran'], [
                'metode_pembayaran'     => 'Tripay',
                'tripay_reference'      => $reference,
                'merchant_ref'          => $merchantRef,
                'transaction_reference' => $payCode . ' (' . $payName . ')',
            ]);

            session()->setFlashdata('success', 'Transaksi Tripay berhasil dibuat. Silakan lakukan pembayaran.');
            return redirect()->to('dashboard/riwayat-pinjam/detail/' . $id);
        } else {
            $msg = $resObj['message'] ?? 'Respons tidak diketahui dari Tripay.';
            session()->setFlashdata('error', 'Gagal memproses pembayaran via Tripay: ' . $msg);
            return redirect()->back();
        }
    }

    /**
     * Unggah bukti pembayaran denda manual.
     */
    public function uploadBuktiBayar($id)
    {
        $userId = session()->get('user_id');

        $peminjaman = $this->peminjamanModel
            ->where('id_anggota', $userId)
            ->find($id);

        if (!$peminjaman) {
            session()->setFlashdata('error', 'Data peminjaman tidak ditemukan.');
            return redirect()->to('dashboard/riwayat-pinjam');
        }

        $pembayaran = $this->pembayaranModel->getByPeminjaman($id);
        if (!$pembayaran || $pembayaran['status_pembayaran'] === 'Lunas') {
            session()->setFlashdata('error', 'Tagihan denda tidak ditemukan atau sudah lunas.');
            return redirect()->to('dashboard/riwayat-pinjam/detail/' . $id);
        }

        $validationRule = [
            'bukti_bayar' => [
                'label' => 'Bukti Pembayaran',
                'rules' => 'uploaded[bukti_bayar]'
                    . '|is_image[bukti_bayar]'
                    . '|mime_in[bukti_bayar,image/jpg,image/jpeg,image/png]'
                    . '|max_size[bukti_bayar,2048]',
                'errors' => [
                    'uploaded' => 'File bukti pembayaran wajib diunggah.',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in'  => 'Format file gambar harus JPG, JPEG, atau PNG.',
                    'max_size' => 'Ukuran file maksimal 2MB.',
                ]
            ],
        ];

        if (!$this->validate($validationRule)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $img = $this->request->getFile('bukti_bayar');

        if ($img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            
            $uploadPath = ROOTPATH . 'public/uploads/bukti_bayar/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $img->move($uploadPath, $newName);

            $this->pembayaranModel->update($pembayaran['id_pembayaran'], [
                'metode_pembayaran' => 'Manual',
                'bukti_bayar'       => 'uploads/bukti_bayar/' . $newName,
            ]);

            session()->setFlashdata('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu konfirmasi admin.');
            return redirect()->to('dashboard/riwayat-pinjam/detail/' . $id);
        }

        session()->setFlashdata('error', 'Gagal mengunggah file bukti pembayaran.');
        return redirect()->back();
    }
}
