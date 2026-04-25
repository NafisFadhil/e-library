<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function anggota()
    {
        $data = [
            'nama' => session()->get('nama'),
        ];
        return view('dashboard/anggota', $data);
    }

    public function pustakawan()
    {
        $data = [
            'nama' => session()->get('nama'),
        ];
        return view('dashboard/pustakawan', $data);
    }
}
