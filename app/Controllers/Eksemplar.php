<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\EksemplarModel;

class Eksemplar extends BaseController
{
    protected $bukuModel;
    protected $eksemplarModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->bukuModel = new BukuModel();
        $this->eksemplarModel = new EksemplarModel();
    }

    public function index($isbn)
    {
        $buku = $this->bukuModel->find($isbn);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        $eksemplar = $this->eksemplarModel->where('isbn', $isbn)->findAll();

        $data = [
            'buku'      => $buku,
            'eksemplar' => $eksemplar
        ];
        return view('eksemplar/index', $data);
    }

    public function create($isbn)
    {
        $buku = $this->bukuModel->find($isbn);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        return view('eksemplar/create', ['buku' => $buku]);
    }

    public function store($isbn)
    {
        $buku = $this->bukuModel->find($isbn);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        $rules = [
            'kode'         => 'required|max_length[50]|is_unique[eksemplar.kode]',
            'kondisi'      => 'required|in_list[Bagus,Rusak Ringan,Rusak Berat]',
            'lokasi_rak'   => 'required|max_length[50]',
            'ketersediaan' => 'required|in_list[Tersedia,Dipinjam,Tidak Tersedia]',
        ];

        $messages = [
            'kode' => [
                'required'   => 'Kode eksemplar wajib diisi.',
                'max_length' => 'Kode eksemplar maksimal 50 karakter.',
                'is_unique'  => 'Kode eksemplar sudah ada, gunakan kode lain.',
            ],
            'kondisi' => [
                'required' => 'Kondisi buku wajib dipilih.',
                'in_list'  => 'Kondisi tidak valid. Pilih: Bagus, Rusak Ringan, atau Rusak Berat.',
            ],
            'lokasi_rak' => [
                'required'   => 'Lokasi rak wajib diisi.',
                'max_length' => 'Lokasi rak maksimal 50 karakter.',
            ],
            'ketersediaan' => [
                'required' => 'Status ketersediaan wajib dipilih.',
                'in_list'  => 'Status tidak valid. Pilih: Tersedia, Dipinjam, atau Tidak Tersedia.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $this->eksemplarModel->insert([
            'kode'         => $this->request->getPost('kode'),
            'isbn'         => $isbn,
            'kondisi'      => $this->request->getPost('kondisi'),
            'lokasi_rak'   => $this->request->getPost('lokasi_rak'),
            'ketersediaan' => $this->request->getPost('ketersediaan'),
        ]);

        session()->setFlashdata('success', 'Eksemplar berhasil ditambahkan.');
        return redirect()->to("dashboard/buku/{$isbn}/eksemplar");
    }

    public function edit($kode)
    {
        $eksemplar = $this->eksemplarModel->find($kode);
        if (!$eksemplar) {
            session()->setFlashdata('error', 'Eksemplar tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        $buku = $this->bukuModel->find($eksemplar['isbn']);

        $data = [
            'buku'      => $buku,
            'eksemplar' => $eksemplar
        ];
        return view('eksemplar/edit', $data);
    }

    public function update($kode)
    {
        $eksemplar = $this->eksemplarModel->find($kode);
        if (!$eksemplar) {
            session()->setFlashdata('error', 'Eksemplar tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        $rules = [
            'kondisi'      => 'required|in_list[Bagus,Rusak Ringan,Rusak Berat]',
            'lokasi_rak'   => 'required|max_length[50]',
            'ketersediaan' => 'required|in_list[Tersedia,Dipinjam,Tidak Tersedia]',
        ];

        $messages = [
            'kondisi' => [
                'required' => 'Kondisi buku wajib dipilih.',
                'in_list'  => 'Kondisi tidak valid. Pilih: Bagus, Rusak Ringan, atau Rusak Berat.',
            ],
            'lokasi_rak' => [
                'required'   => 'Lokasi rak wajib diisi.',
                'max_length' => 'Lokasi rak maksimal 50 karakter.',
            ],
            'ketersediaan' => [
                'required' => 'Status ketersediaan wajib dipilih.',
                'in_list'  => 'Status tidak valid. Pilih: Tersedia, Dipinjam, atau Tidak Tersedia.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $this->eksemplarModel->update($kode, [
            'kondisi'      => $this->request->getPost('kondisi'),
            'lokasi_rak'   => $this->request->getPost('lokasi_rak'),
            'ketersediaan' => $this->request->getPost('ketersediaan'),
        ]);

        session()->setFlashdata('success', 'Eksemplar berhasil diperbarui.');
        return redirect()->to("dashboard/buku/{$eksemplar['isbn']}/eksemplar");
    }

    public function delete($kode)
    {
        $eksemplar = $this->eksemplarModel->find($kode);
        if (!$eksemplar) {
            session()->setFlashdata('error', 'Eksemplar tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        $isbn = $eksemplar['isbn'];
        $this->eksemplarModel->delete($kode);

        session()->setFlashdata('success', 'Eksemplar berhasil dihapus.');
        return redirect()->to("dashboard/buku/{$isbn}/eksemplar");
    }
}
