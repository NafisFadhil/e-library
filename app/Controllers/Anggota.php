<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;

class Anggota extends BaseController
{
    protected $anggotaModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->anggotaModel = new AnggotaModel();
    }

    /**
     * Tampilkan daftar semua anggota.
     */
    public function index()
    {
        $data = [
            'anggota' => $this->anggotaModel->orderBy('id_anggota', 'DESC')->paginate(10, 'anggota'),
            'pager'   => $this->anggotaModel->pager,
        ];
        return view('anggota/index', $data);
    }

    /**
     * Form tambah anggota baru.
     */
    public function create()
    {
        return view('anggota/create');
    }

    /**
     * Simpan anggota baru ke database.
     */
    public function store()
    {
        $rules = [
            'nama'             => 'required|min_length[3]|max_length[100]',
            'no_telepon'       => 'required|min_length[10]|max_length[20]',
            'email'            => 'required|valid_email|max_length[100]|is_unique[anggota.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'status'           => 'required|in_list[Aktif,Nonaktif]',
        ];

        $messages = [
            'nama' => [
                'required'   => 'Nama anggota wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'no_telepon' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'min_length' => 'Nomor telepon minimal 10 digit.',
                'max_length' => 'Nomor telepon maksimal 20 digit.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'max_length'  => 'Email maksimal 100 karakter.',
                'is_unique'   => 'Email sudah terdaftar, gunakan email lain.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok.',
            ],
            'status' => [
                'required' => 'Status wajib dipilih.',
                'in_list'  => 'Status tidak valid. Pilih: Aktif atau Nonaktif.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $this->anggotaModel->insert([
            'nama'       => $this->request->getPost('nama'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'status'     => $this->request->getPost('status'),
        ]);

        session()->setFlashdata('success', 'Anggota berhasil ditambahkan.');
        return redirect()->to('dashboard/anggota-list');
    }

    /**
     * Form edit data anggota.
     */
    public function edit($id)
    {
        $anggota = $this->anggotaModel->find($id);
        if (!$anggota) {
            session()->setFlashdata('error', 'Anggota tidak ditemukan.');
            return redirect()->to('dashboard/anggota-list');
        }
        return view('anggota/edit', ['anggota' => $anggota]);
    }

    /**
     * Update data anggota di database.
     */
    public function update($id)
    {
        $anggota = $this->anggotaModel->find($id);
        if (!$anggota) {
            session()->setFlashdata('error', 'Anggota tidak ditemukan.');
            return redirect()->to('dashboard/anggota-list');
        }

        $rules = [
            'nama'       => 'required|min_length[3]|max_length[100]',
            'no_telepon' => 'required|min_length[10]|max_length[20]',
            'status'     => 'required|in_list[Aktif,Nonaktif]',
        ];

        // Cek apakah email berubah, jika iya validasi unique
        $newEmail = $this->request->getPost('email');
        if ($newEmail !== $anggota['email']) {
            $rules['email'] = 'required|valid_email|max_length[100]|is_unique[anggota.email]';
        } else {
            $rules['email'] = 'required|valid_email|max_length[100]';
        }

        $messages = [
            'nama' => [
                'required'   => 'Nama anggota wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'no_telepon' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'min_length' => 'Nomor telepon minimal 10 digit.',
                'max_length' => 'Nomor telepon maksimal 20 digit.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'max_length'  => 'Email maksimal 100 karakter.',
                'is_unique'   => 'Email sudah terdaftar, gunakan email lain.',
            ],
            'status' => [
                'required' => 'Status wajib dipilih.',
                'in_list'  => 'Status tidak valid. Pilih: Aktif atau Nonaktif.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $updateData = [
            'nama'       => $this->request->getPost('nama'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'email'      => $newEmail,
            'status'     => $this->request->getPost('status'),
        ];

        // Jika password diisi, update password juga
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                session()->setFlashdata('error', 'Password baru minimal 6 karakter.');
                return redirect()->back()->withInput();
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->anggotaModel->update($id, $updateData);

        session()->setFlashdata('success', 'Data anggota berhasil diperbarui.');
        return redirect()->to('dashboard/anggota-list');
    }

    /**
     * Hapus anggota dari database.
     */
    public function delete($id)
    {
        $anggota = $this->anggotaModel->find($id);
        if (!$anggota) {
            session()->setFlashdata('error', 'Anggota tidak ditemukan.');
            return redirect()->to('dashboard/anggota-list');
        }

        $this->anggotaModel->delete($id);
        session()->setFlashdata('success', 'Anggota berhasil dihapus.');
        return redirect()->to('dashboard/anggota-list');
    }
}
