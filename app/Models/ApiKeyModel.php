<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiKeyModel extends Model
{
    protected $table            = 'api_keys';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nama_aplikasi', 'api_key', 'status', 'created_at'
    ];
    protected $useTimestamps = false;

    /**
     * Cari API Key yang statusnya aktif.
     */
    public function getActiveKey(string $key): ?array
    {
        return $this->where('api_key', $key)
                    ->where('status', 'aktif')
                    ->first();
    }
}
