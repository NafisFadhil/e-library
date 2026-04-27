<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAnggota implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'anggota') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman anggota.');
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
