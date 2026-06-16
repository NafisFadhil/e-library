<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranDendaModel extends Model
{
    protected $table            = 'pembayaran_denda';
    protected $primaryKey       = 'id_pembayaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_peminjaman', 'metode_pembayaran', 'jumlah_denda',
        'tripay_reference', 'transaction_reference', 'merchant_ref',
        'status_pembayaran', 'bukti_bayar', 'catatan_admin', 'waktu_pembayaran'
    ];
    protected $useTimestamps = false;

    /**
     * Ambil pembayaran aktif (bukan Lunas) untuk 1 peminjaman.
     */
    public function getByPeminjaman(int $idPeminjaman): ?array
    {
        return $this->where('id_peminjaman', $idPeminjaman)->first();
    }
}
