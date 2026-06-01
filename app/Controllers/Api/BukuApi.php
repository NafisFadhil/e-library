<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BukuModel;
use App\Models\EksemplarModel;

class BukuApi extends ResourceController
{
    protected $format = 'json';

    protected $bukuModel;
    protected $eksemplarModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->bukuModel      = new BukuModel();
        $this->eksemplarModel = new EksemplarModel();
    }

    /**
     * GET /api/v1/buku
     * List semua buku dengan pagination dan pencarian.
     */
    public function index()
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);
        $keyword = $this->request->getGet('keyword');

        if ($page < 1) $page = 1;
        if ($perPage < 1 || $perPage > 100) $perPage = 10;

        $builder = $this->bukuModel->getWithStockCount();

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('buku.judul', $keyword)
                    ->orLike('buku.isbn', $keyword)
                    ->orLike('buku.penulis', $keyword)
                    ->orLike('buku.penerbit', $keyword)
                    ->orLike('buku.kategori', $keyword)
                    ->groupEnd();
        }

        // Hitung total dulu sebelum pagination
        $total = $builder->countAllResults(false);
        $totalPages = (int) ceil($total / $perPage);

        $offset = ($page - 1) * $perPage;
        $data = $builder->limit($perPage, $offset)->findAll();

        return $this->respond([
            'status'  => 200,
            'message' => 'Data buku berhasil diambil.',
            'data'    => $data,
            'pagination' => [
                'page'        => $page,
                'per_page'    => $perPage,
                'total'       => $total,
                'total_pages' => $totalPages,
            ],
        ]);
    }

    /**
     * GET /api/v1/buku/{isbn}
     * Detail satu buku beserta daftar eksemplarnya.
     */
    public function show($isbn = null)
    {
        $buku = $this->bukuModel->find($isbn);

        if (!$buku) {
            return $this->failNotFound('Buku dengan ISBN "' . $isbn . '" tidak ditemukan.');
        }

        // Ambil daftar eksemplar buku ini
        $eksemplar = $this->eksemplarModel->where('isbn', $isbn)->findAll();

        $buku['eksemplar'] = $eksemplar;

        return $this->respond([
            'status'  => 200,
            'message' => 'Detail buku berhasil diambil.',
            'data'    => $buku,
        ]);
    }

    /**
     * POST /api/v1/buku
     * Tambah buku baru via API.
     */
    public function create()
    {
        $rules = [
            'isbn'         => 'required|max_length[20]|is_unique[buku.isbn]',
            'judul'        => 'required|max_length[255]',
            'kategori'     => 'required|max_length[100]',
            'tahun_terbit' => 'required|exact_length[4]|numeric',
            'penerbit'     => 'required|max_length[100]',
            'penulis'      => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'isbn'         => $this->request->getJsonVar('isbn') ?? $this->request->getPost('isbn'),
            'judul'        => $this->request->getJsonVar('judul') ?? $this->request->getPost('judul'),
            'kategori'     => $this->request->getJsonVar('kategori') ?? $this->request->getPost('kategori'),
            'url_cover'    => $this->request->getJsonVar('url_cover') ?? $this->request->getPost('url_cover'),
            'tahun_terbit' => $this->request->getJsonVar('tahun_terbit') ?? $this->request->getPost('tahun_terbit'),
            'penerbit'     => $this->request->getJsonVar('penerbit') ?? $this->request->getPost('penerbit'),
            'penulis'      => $this->request->getJsonVar('penulis') ?? $this->request->getPost('penulis'),
        ];

        $this->bukuModel->insert($data);

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Buku berhasil ditambahkan.',
            'data'    => $data,
        ]);
    }

    /**
     * GET /api/v1/availability/{id}
     * Cek ketersediaan berdasarkan ID (bisa ISBN atau Kode Eksemplar).
     */
    public function availability($id = null)
    {
        if (!$id) {
            return $this->failValidationErrors('ID (ISBN atau Kode Eksemplar) wajib disertakan.');
        }

        // 1. Coba cari sebagai kode_eksemplar
        $eksemplar = $this->eksemplarModel->find($id);
        if ($eksemplar) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Data ketersediaan eksemplar berhasil diambil.',
                'data'    => [
                    'tipe_id'      => 'kode_eksemplar',
                    'id'           => $id,
                    'isbn'         => $eksemplar['isbn'],
                    'kondisi'      => $eksemplar['kondisi'],
                    'ketersediaan' => $eksemplar['ketersediaan'],
                    'lokasi_rak'   => $eksemplar['lokasi_rak']
                ]
            ]);
        }

        // 2. Jika tidak ketemu, coba cari sebagai ISBN
        $buku = $this->bukuModel->find($id);
        if ($buku) {
            $tersedia = $this->eksemplarModel->where('isbn', $id)->where('ketersediaan', 'Tersedia')->countAllResults();
            $total    = $this->eksemplarModel->where('isbn', $id)->countAllResults();
            
            return $this->respond([
                'status'  => 200,
                'message' => 'Data ketersediaan buku berhasil diambil.',
                'data'    => [
                    'tipe_id'           => 'isbn',
                    'id'                => $id,
                    'judul'             => $buku['judul'],
                    'total_eksemplar'   => $total,
                    'eksemplar_tersedia'=> $tersedia,
                    'status'            => $tersedia > 0 ? 'Tersedia' : 'Habis'
                ]
            ]);
        }

        return $this->failNotFound('Data dengan ID/ISBN "' . $id . '" tidak ditemukan.');
    }
}
