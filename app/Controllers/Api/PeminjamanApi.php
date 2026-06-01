<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PeminjamanModel;
use App\Models\DetailPeminjamanModel;

class PeminjamanApi extends ResourceController
{
    protected $format = 'json';

    protected $peminjamanModel;
    protected $detailModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailModel     = new DetailPeminjamanModel();
    }

    /**
     * GET /api/v1/peminjaman
     * List semua peminjaman dengan pagination dan filter status.
     */
    public function index()
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);
        $status  = $this->request->getGet('status');

        if ($page < 1) $page = 1;
        if ($perPage < 1 || $perPage > 100) $perPage = 10;

        $builder = $this->peminjamanModel->getWithRelations();

        if (!empty($status)) {
            $builder->where('peminjaman.status_peminjaman', $status);
        }

        // Hitung total sebelum pagination
        $total = $builder->countAllResults(false);
        $totalPages = (int) ceil($total / $perPage);

        $offset = ($page - 1) * $perPage;
        $data = $builder->limit($perPage, $offset)->findAll();

        return $this->respond([
            'status'  => 200,
            'message' => 'Data peminjaman berhasil diambil.',
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
     * GET /api/v1/peminjaman/{id}
     * Detail satu peminjaman beserta detail buku yang dipinjam.
     */
    public function show($id = null)
    {
        $peminjaman = $this->peminjamanModel->getWithRelations()->find($id);

        if (!$peminjaman) {
            return $this->failNotFound('Data peminjaman dengan ID "' . $id . '" tidak ditemukan.');
        }

        // Ambil detail peminjaman (buku-buku yang dipinjam)
        $detail = $this->detailModel->getByPeminjaman($id);

        $peminjaman['detail_buku'] = $detail;

        return $this->respond([
            'status'  => 200,
            'message' => 'Detail peminjaman berhasil diambil.',
            'data'    => $peminjaman,
        ]);
    }
}
