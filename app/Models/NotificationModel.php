<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifikasi';
    protected $primaryKey       = 'id_notifikasi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_anggota', 
        'waktu_kirim', 
        'jenis', 
        'isi', 
        'status'
    ];

    // Dates
    protected $useTimestamps = false; // We use waktu_kirim manually instead of CI's created_at
}
