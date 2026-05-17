<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'isbn';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'isbn', 'judul', 'kategori', 'url_cover', 'tahun_terbit', 'penerbit', 'penulis'
    ];
    protected $useTimestamps    = false;

    /**
     * Get books along with their copy counts.
     * Can be used for pagination or lists.
     */
    public function getWithStockCount()
    {
        return $this->select('buku.*, COUNT(eksemplar.kode) as jumlah_eksemplar')
                    ->join('eksemplar', 'eksemplar.isbn = buku.isbn', 'left')
                    ->groupBy('buku.isbn');
    }
}
