<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ApiKeyModel;

class ApiKeyController extends BaseController
{
    protected $apiKeyModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->apiKeyModel = new ApiKeyModel();
    }

    /**
     * Tampilkan daftar semua API Key.
     */
    public function index()
    {
        $data = [
            'apiKeys' => $this->apiKeyModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('api_keys/index', $data);
    }

    /**
     * Generate API Key baru.
     */
    public function generate()
    {
        $namaAplikasi = $this->request->getPost('nama_aplikasi');

        if (empty($namaAplikasi)) {
            session()->setFlashdata('error', 'Nama aplikasi wajib diisi.');
            return redirect()->back()->withInput();
        }

        // Generate random API Key (64 karakter hex)
        $apiKey = bin2hex(random_bytes(32));

        $this->apiKeyModel->insert([
            'nama_aplikasi' => $namaAplikasi,
            'api_key'       => $apiKey,
            'status'        => 'aktif',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'API Key berhasil dibuat.');
        session()->setFlashdata('new_key', $apiKey);
        return redirect()->to('dashboard/api-keys');
    }

    /**
     * Toggle status API Key (aktif/nonaktif).
     */
    public function toggleStatus($id)
    {
        $key = $this->apiKeyModel->find($id);
        if (!$key) {
            session()->setFlashdata('error', 'API Key tidak ditemukan.');
            return redirect()->to('dashboard/api-keys');
        }

        $newStatus = ($key['status'] === 'aktif') ? 'nonaktif' : 'aktif';
        $this->apiKeyModel->update($id, ['status' => $newStatus]);

        session()->setFlashdata('success', 'Status API Key berhasil diubah menjadi "' . $newStatus . '".');
        return redirect()->to('dashboard/api-keys');
    }

    /**
     * Hapus API Key.
     */
    public function delete($id)
    {
        $key = $this->apiKeyModel->find($id);
        if (!$key) {
            session()->setFlashdata('error', 'API Key tidak ditemukan.');
            return redirect()->to('dashboard/api-keys');
        }

        $this->apiKeyModel->delete($id);

        session()->setFlashdata('success', 'API Key berhasil dihapus.');
        return redirect()->to('dashboard/api-keys');
    }
}
