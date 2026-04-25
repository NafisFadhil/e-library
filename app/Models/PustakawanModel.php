<?php

namespace App\Models;

use CodeIgniter\Model;

class PustakawanModel extends Model
{
    protected $table      = 'pustakawan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama', 'username', 'password'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
