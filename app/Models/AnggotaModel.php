<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table      = 'anggota';
    protected $primaryKey = 'id_anggota';

    protected $allowedFields = [
        'nama', 'no_telepon', 'email', 'password', 'status'
    ];

    protected $useTimestamps = false;
}
