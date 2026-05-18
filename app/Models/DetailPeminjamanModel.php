<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPeminjamanModel extends Model
{
    protected $table            = 'detail_peminjaman';
    protected $primaryKey       = 'id_peminjaman';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_peminjaman', 'kode_eksemplar', 'tanggal_kembali', 'denda'
    ];
    protected $useTimestamps = false;

    /**
     * Get detail peminjaman with eksemplar & buku info.
     */
    public function getByPeminjaman($idPeminjaman)
    {
        return $this->select('detail_peminjaman.*, eksemplar.isbn, eksemplar.kondisi, eksemplar.lokasi_rak, buku.judul as judul_buku')
                    ->join('eksemplar', 'eksemplar.kode = detail_peminjaman.kode_eksemplar', 'left')
                    ->join('buku', 'buku.isbn = eksemplar.isbn', 'left')
                    ->where('detail_peminjaman.id_peminjaman', $idPeminjaman)
                    ->findAll();
    }

    /**
     * Insert detail peminjaman menggunakan db builder langsung
     * karena tabel ini punya composite primary key.
     */
    public function insertDetail(array $data)
    {
        return $this->db->table($this->table)->insert($data);
    }
}
