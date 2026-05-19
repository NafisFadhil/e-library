<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PustakawanModel;

class Pustakawan extends BaseController
{
    protected $pustakawanModel;
    protected $helpers = ['form', 'url'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->pustakawanModel = new PustakawanModel();
    }

    /**
     * Tampilkan daftar semua pustakawan.
     */
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $model = $this->pustakawanModel;

        if (!empty($keyword)) {
            $model = $model->groupStart()
                           ->like('nama', $keyword)
                           ->orLike('username', $keyword)
                           ->orLike('email', $keyword)
                           ->groupEnd();
        }

        $data = [
            'pustakawan' => $model->orderBy('id_pustakawan', 'DESC')->paginate(10, 'pustakawan'),
            'pager'      => $this->pustakawanModel->pager,
            'keyword'    => $keyword,
        ];
        return view('pustakawan/index', $data);
    }

    /**
     * Form tambah pustakawan baru.
     */
    public function create()
    {
        return view('pustakawan/create');
    }

    /**
     * Simpan pustakawan baru ke database.
     */
    public function store()
    {
        $rules = [
            'nama'             => 'required|min_length[3]|max_length[100]',
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[pustakawan.username]',
            'email'            => 'required|valid_email|max_length[100]|is_unique[pustakawan.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'is_admin'         => 'required|in_list[0,1]',
        ];

        $messages = [
            'nama' => [
                'required'   => 'Nama pustakawan wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'username' => [
                'required'   => 'Username wajib diisi.',
                'min_length' => 'Username minimal 3 karakter.',
                'max_length' => 'Username maksimal 50 karakter.',
                'is_unique'  => 'Username sudah digunakan, cari username lain.',
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
                'matches'  => 'Konfirmasi password tidak cocok dengan password.',
            ],
            'is_admin' => [
                'required' => 'Role wajib dipilih.',
                'in_list'  => 'Role tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $this->pustakawanModel->insert([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'is_admin' => $this->request->getPost('is_admin'),
        ]);

        session()->setFlashdata('success', 'Pustakawan berhasil ditambahkan.');
        return redirect()->to('dashboard/pustakawan-list');
    }

    /**
     * Form edit data pustakawan.
     */
    public function edit($id)
    {
        $pustakawan = $this->pustakawanModel->find($id);
        if (!$pustakawan) {
            session()->setFlashdata('error', 'Pustakawan tidak ditemukan.');
            return redirect()->to('dashboard/pustakawan-list');
        }
        return view('pustakawan/edit', ['pustakawan' => $pustakawan]);
    }

    /**
     * Update data pustakawan di database.
     */
    public function update($id)
    {
        $pustakawan = $this->pustakawanModel->find($id);
        if (!$pustakawan) {
            session()->setFlashdata('error', 'Pustakawan tidak ditemukan.');
            return redirect()->to('dashboard/pustakawan-list');
        }

        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'is_admin' => 'required|in_list[0,1]',
        ];

        // Cek apakah username berubah
        $newUsername = $this->request->getPost('username');
        if ($newUsername !== $pustakawan['username']) {
            $rules['username'] = 'required|min_length[3]|max_length[50]|is_unique[pustakawan.username]';
        } else {
            $rules['username'] = 'required|min_length[3]|max_length[50]';
        }

        // Cek apakah email berubah
        $newEmail = $this->request->getPost('email');
        if ($newEmail !== $pustakawan['email']) {
            $rules['email'] = 'required|valid_email|max_length[100]|is_unique[pustakawan.email]';
        } else {
            $rules['email'] = 'required|valid_email|max_length[100]';
        }

        $messages = [
            'nama' => [
                'required'   => 'Nama pustakawan wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'username' => [
                'required'   => 'Username wajib diisi.',
                'min_length' => 'Username minimal 3 karakter.',
                'max_length' => 'Username maksimal 50 karakter.',
                'is_unique'  => 'Username sudah digunakan, cari username lain.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'max_length'  => 'Email maksimal 100 karakter.',
                'is_unique'   => 'Email sudah terdaftar, gunakan email lain.',
            ],
            'is_admin' => [
                'required' => 'Role wajib dipilih.',
                'in_list'  => 'Role tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $updateData = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $newUsername,
            'email'    => $newEmail,
            'is_admin' => $this->request->getPost('is_admin'),
        ];

        // Jika password baru diisi, update password
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                session()->setFlashdata('error', 'Password baru minimal 6 karakter.');
                return redirect()->back()->withInput();
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->pustakawanModel->update($id, $updateData);

        // Jika user yang diupdate adalah admin yang sedang login, update data sessionnya
        if (session()->get('user_id') == $id) {
            session()->set('nama', $updateData['nama']);
            session()->set('is_admin', (bool)$updateData['is_admin']);
        }

        session()->setFlashdata('success', 'Data pustakawan berhasil diperbarui.');
        return redirect()->to('dashboard/pustakawan-list');
    }

    /**
     * Hapus pustakawan dari database.
     */
    public function delete($id)
    {
        $pustakawan = $this->pustakawanModel->find($id);
        if (!$pustakawan) {
            session()->setFlashdata('error', 'Pustakawan tidak ditemukan.');
            return redirect()->to('dashboard/pustakawan-list');
        }

        // Safeguard: Prevent deleting own account
        if (session()->get('user_id') == $id) {
            session()->setFlashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.');
            return redirect()->to('dashboard/pustakawan-list');
        }

        $this->pustakawanModel->delete($id);
        session()->setFlashdata('success', 'Pustakawan berhasil dihapus.');
        return redirect()->to('dashboard/pustakawan-list');
    }
}
