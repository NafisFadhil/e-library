<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;
use App\Models\PustakawanModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('auth/login');
    }

    public function processLogin()
    {
        $role = $this->request->getPost('role');

        if ($role === 'anggota') {
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $anggotaModel = new AnggotaModel();
            $user = $anggotaModel->where('email', $email)->first();
            if ($user && password_verify($password, $user['password'])) {
                $this->setUserSession($user, 'anggota');
                return redirect()->to('/dashboard/anggota');
            }
        } elseif ($role === 'pustakawan') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $pustakawanModel = new PustakawanModel();
            $user = $pustakawanModel->where('username', $username)
                                    ->orWhere('email', $username)
                                    ->first();

            if ($user && password_verify($password, $user['password'])) {
                $this->setUserSession($user, 'pustakawan');
                return redirect()->to('/dashboard/pustakawan');
            }
        }

        session()->setFlashdata('error', 'Username/Email atau Password salah.');
        return redirect()->to('/login')->withInput();
    }

    private function setUserSession($user, $role)
    {
        $id_field = ($role === 'anggota') ? 'id_anggota' : 'id_pustakawan';
        $data = [
            'user_id'   => $user[$id_field],
            'nama'      => $user['nama'],
            'role'      => $role,
            'logged_in' => true,
        ];
        session()->set($data);
        return true;
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard/' . session()->get('role'));
        }
        return view('auth/register');
    }

    public function processRegister()
    {
        $rules = [
            'nama'             => 'required|min_length[3]',
            'no_telepon'       => 'required|min_length[10]',
            'email'            => 'required|valid_email|is_unique[anggota.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->to('/register')->withInput();
        }

        $anggotaModel = new AnggotaModel();
        $anggotaModel->insert([
            'nama'       => $this->request->getPost('nama'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'status'     => 'aktif',
        ]);

        session()->setFlashdata('success', 'Pendaftaran berhasil! Silakan login.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
