<?php

namespace App\Models;

use CodeIgniter\Model;

class PustakawanModel extends Model
{
    protected $table      = 'pustakawan';
    protected $primaryKey = 'id_pustakawan';

    protected $allowedFields = [
        'nama', 'username', 'email', 'password', 'is_admin'
    ];

    protected $useTimestamps = false;
}
