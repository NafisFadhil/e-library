<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table      = 'anggota';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama', 'no_identitas', 'email', 'no_wa', 'password'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
