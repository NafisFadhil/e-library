<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAdminPustakawan implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'pustakawan') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman pustakawan.');
            return redirect()->to('/');
        }

        if (!session()->get('is_admin')) {
            session()->setFlashdata('error', 'Anda tidak memiliki hak akses administrator.');
            return redirect()->to('/dashboard/pustakawan');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
