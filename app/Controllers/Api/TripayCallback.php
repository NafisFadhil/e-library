<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PembayaranDendaModel;

class TripayCallback extends BaseController
{
    protected $pembayaranModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->pembayaranModel = new PembayaranDendaModel();
    }

    public function index()
    {
        $json = $this->request->getBody();
        $callbackSignature = $this->request->getHeaderLine('X-Callback-Signature');
        $callbackEvent     = $this->request->getHeaderLine('X-Callback-Event');

        $privateKey = env('TRIPAY_PRIVATE_KEY') ?: 'private_key_anda';
        $signature  = hash_hmac('sha256', $json, $privateKey);

        if ($callbackSignature !== $signature) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid signature',
            ]);
        }

        $data = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid data sent by payment gateway',
            ]);
        }

        if ('payment_status' !== $callbackEvent) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Unrecognized callback event: ' . $callbackEvent,
            ]);
        }

        $merchantRef     = $data->merchant_ref;
        $tripayReference = $data->reference;
        $status          = strtoupper((string) $data->status);

        if (isset($data->is_closed_payment) && $data->is_closed_payment === 1) {
            // Ekstrak id_peminjaman dari merchant_ref (Format: DENDA-{id_peminjaman}-{time})
            $parts = explode('-', $merchantRef);
            if (count($parts) >= 2 && $parts[0] === 'DENDA') {
                $idPeminjaman = $parts[1];
                $pembayaran = $this->pembayaranModel->where('id_peminjaman', $idPeminjaman)->first();
            } else {
                $pembayaran = $this->pembayaranModel->where([
                    'merchant_ref'     => $merchantRef,
                    'tripay_reference' => $tripayReference,
                ])->first();
            }

            if (!$pembayaran) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Invoice not found: ' . $merchantRef,
                ]);
            }

            // Hentikan proses jika status pembayaran di database sudah Lunas
            if ($pembayaran['status_pembayaran'] === 'Lunas') {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Invoice already paid',
                ]);
            }

            switch ($status) {
                case 'PAID':
                    $this->pembayaranModel->update($pembayaran['id_pembayaran'], [
                        'status_pembayaran' => 'Lunas',
                        'waktu_pembayaran'  => date('Y-m-d H:i:s'),
                        'tripay_reference'  => $tripayReference,
                        'merchant_ref'      => $merchantRef,
                    ]);
                    break;

                case 'EXPIRED':
                    // Hanya update jika referensi yang kedaluwarsa adalah yang aktif saat ini
                    if ($pembayaran['tripay_reference'] === $tripayReference) {
                        $this->pembayaranModel->update($pembayaran['id_pembayaran'], [
                            'status_pembayaran' => 'Kedaluwarsa',
                        ]);
                    }
                    break;

                case 'FAILED':
                    // Hanya update jika referensi yang gagal adalah yang aktif saat ini
                    if ($pembayaran['tripay_reference'] === $tripayReference) {
                        $this->pembayaranModel->update($pembayaran['id_pembayaran'], [
                            'status_pembayaran' => 'Gagal',
                        ]);
                    }
                    break;

                default:
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false,
                        'message' => 'Unrecognized payment status: ' . $status,
                    ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Callback processed successfully',
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'success' => false,
            'message' => 'Not a closed payment or invalid payload format',
        ]);
    }
}
