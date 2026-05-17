<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $bukuModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->bukuModel = new BukuModel();
    }

    public function index()
    {
        $data = [
            'buku'  => $this->bukuModel->getWithStockCount()->paginate(10, 'buku'),
            'pager' => $this->bukuModel->pager,
        ];
        return view('buku/index', $data);
    }

    public function create()
    {
        return view('buku/create');
    }

    public function store()
    {
        $rules = [
            'isbn'         => 'required|max_length[20]|is_unique[buku.isbn]',
            'judul'        => 'required|max_length[255]',
            'kategori'     => 'required|max_length[100]',
            'tahun_terbit' => 'required|exact_length[4]|numeric',
            'penerbit'     => 'required|max_length[100]',
            'penulis'      => 'required|max_length[100]',
        ];

        $hasCoverFile = $this->request->getFile('cover_file') && $this->request->getFile('cover_file')->isValid();
        if ($hasCoverFile) {
            $rules['cover_file'] = 'is_image[cover_file]|max_size[cover_file,2048]|ext_in[cover_file,jpg,png,webp]';
        }

        $messages = [
            'isbn' => [
                'required'   => 'ISBN wajib diisi.',
                'max_length' => 'ISBN maksimal 20 karakter.',
                'is_unique'  => 'ISBN sudah terdaftar, gunakan ISBN lain.',
            ],
            'judul' => [
                'required'   => 'Judul buku wajib diisi.',
                'max_length' => 'Judul buku maksimal 255 karakter.',
            ],
            'kategori' => [
                'required'   => 'Kategori wajib diisi.',
                'max_length' => 'Kategori maksimal 100 karakter.',
            ],
            'tahun_terbit' => [
                'required'      => 'Tahun terbit wajib diisi.',
                'exact_length'  => 'Tahun terbit harus 4 digit (contoh: 2024).',
                'numeric'       => 'Tahun terbit harus berupa angka.',
            ],
            'penerbit' => [
                'required'   => 'Penerbit wajib diisi.',
                'max_length' => 'Penerbit maksimal 100 karakter.',
            ],
            'penulis' => [
                'required'   => 'Penulis wajib diisi.',
                'max_length' => 'Penulis maksimal 100 karakter.',
            ],
        ];

        if ($hasCoverFile) {
            $messages['cover_file'] = [
                'is_image' => 'File cover harus berupa gambar (jpg, png, webp).',
                'max_size' => 'Ukuran cover maksimal 2MB.',
                'ext_in'   => 'Format cover tidak didukung. Gunakan jpg, png, atau webp.',
            ];
        }

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $coverUrl = null;
        if ($hasCoverFile) {
            $file = $this->request->getFile('cover_file');
            $uploadPath = FCPATH . 'uploads/covers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $coverUrl = base_url('uploads/covers/' . $newName);
        } elseif ($this->request->getPost('url_cover')) {
            $coverUrl = $this->request->getPost('url_cover');
        }

        $this->bukuModel->insert([
            'isbn'         => $this->request->getPost('isbn'),
            'judul'        => $this->request->getPost('judul'),
            'kategori'     => $this->request->getPost('kategori'),
            'url_cover'    => $coverUrl,
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'penulis'      => $this->request->getPost('penulis'),
        ]);

        session()->setFlashdata('success', 'Buku berhasil ditambahkan.');
        return redirect()->to('dashboard/buku');
    }

    public function edit($isbn)
    {
        $buku = $this->bukuModel->find($isbn);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }
        return view('buku/edit', ['buku' => $buku]);
    }

    public function update($isbn)
    {
        $buku = $this->bukuModel->find($isbn);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        $rules = [
            'judul'        => 'required|max_length[255]',
            'kategori'     => 'required|max_length[100]',
            'tahun_terbit' => 'required|exact_length[4]|numeric',
            'penerbit'     => 'required|max_length[100]',
            'penulis'      => 'required|max_length[100]',
        ];

        $hasCoverFile = $this->request->getFile('cover_file') && $this->request->getFile('cover_file')->isValid();
        if ($hasCoverFile) {
            $rules['cover_file'] = 'is_image[cover_file]|max_size[cover_file,2048]|ext_in[cover_file,jpg,png,webp]';
        }

        $messages = [
            'judul' => [
                'required'   => 'Judul buku wajib diisi.',
                'max_length' => 'Judul buku maksimal 255 karakter.',
            ],
            'kategori' => [
                'required'   => 'Kategori wajib diisi.',
                'max_length' => 'Kategori maksimal 100 karakter.',
            ],
            'tahun_terbit' => [
                'required'      => 'Tahun terbit wajib diisi.',
                'exact_length'  => 'Tahun terbit harus 4 digit (contoh: 2024).',
                'numeric'       => 'Tahun terbit harus berupa angka.',
            ],
            'penerbit' => [
                'required'   => 'Penerbit wajib diisi.',
                'max_length' => 'Penerbit maksimal 100 karakter.',
            ],
            'penulis' => [
                'required'   => 'Penulis wajib diisi.',
                'max_length' => 'Penulis maksimal 100 karakter.',
            ],
        ];

        if ($hasCoverFile) {
            $messages['cover_file'] = [
                'is_image' => 'File cover harus berupa gambar (jpg, png, webp).',
                'max_size' => 'Ukuran cover maksimal 2MB.',
                'ext_in'   => 'Format cover tidak didukung. Gunakan jpg, png, atau webp.',
            ];
        }

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $coverUrl = $buku['url_cover'];
        if ($hasCoverFile) {
            $file = $this->request->getFile('cover_file');
            $uploadPath = FCPATH . 'uploads/covers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Delete old cover if it was uploaded locally
            if (!empty($buku['url_cover']) && strpos($buku['url_cover'], base_url('uploads/covers/')) !== false) {
                $oldFileName = str_replace(base_url('uploads/covers/'), '', $buku['url_cover']);
                if (is_file($uploadPath . $oldFileName)) {
                    @unlink($uploadPath . $oldFileName);
                }
            }

            $coverUrl = base_url('uploads/covers/' . $newName);
        } elseif ($this->request->getPost('url_cover') !== null) {
            $coverUrl = $this->request->getPost('url_cover');
        }

        $this->bukuModel->update($isbn, [
            'judul'        => $this->request->getPost('judul'),
            'kategori'     => $this->request->getPost('kategori'),
            'url_cover'    => $coverUrl ?: null,
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'penulis'      => $this->request->getPost('penulis'),
        ]);

        session()->setFlashdata('success', 'Buku berhasil diperbarui.');
        return redirect()->to('dashboard/buku');
    }

    public function delete($isbn)
    {
        $buku = $this->bukuModel->find($isbn);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->to('dashboard/buku');
        }

        // Delete cover file if it exists locally
        if (!empty($buku['url_cover']) && strpos($buku['url_cover'], base_url('uploads/covers/')) !== false) {
            $uploadPath = FCPATH . 'uploads/covers/';
            $oldFileName = str_replace(base_url('uploads/covers/'), '', $buku['url_cover']);
            if (is_file($uploadPath . $oldFileName)) {
                @unlink($uploadPath . $oldFileName);
            }
        }

        $this->bukuModel->delete($isbn);
        session()->setFlashdata('success', 'Buku dan semua eksemplar terkait berhasil dihapus.');
        return redirect()->to('dashboard/buku');
    }
}
