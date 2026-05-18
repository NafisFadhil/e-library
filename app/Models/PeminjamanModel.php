<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id_peminjaman';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_anggota', 'id_pustakawan', 'tanggal_pengajuan',
        'tanggal_pinjam', 'tanggal_jatuh_tempo', 'status_peminjaman'
    ];
    protected $useTimestamps = false;

    /**
     * Get peminjaman with anggota & pustakawan names via JOIN.
     */
    public function getWithRelations()
    {
        return $this->select('peminjaman.*, anggota.nama as nama_anggota, pustakawan.nama as nama_pustakawan')
                    ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota', 'left')
                    ->join('pustakawan', 'pustakawan.id_pustakawan = peminjaman.id_pustakawan', 'left')
                    ->orderBy('peminjaman.id_peminjaman', 'DESC');
    }
}
