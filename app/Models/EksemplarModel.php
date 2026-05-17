<?php

namespace App\Models;

use CodeIgniter\Model;

class EksemplarModel extends Model
{
    protected $table            = 'eksemplar';
    protected $primaryKey       = 'kode';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'kode', 'isbn', 'kondisi', 'lokasi_rak', 'ketersediaan'
    ];
    protected $useTimestamps    = false;
}
