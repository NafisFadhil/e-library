<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ApiKeyModel;

class ApiAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');

        if (empty($apiKey)) {
            return service('response')
                ->setJSON([
                    'status'  => 401,
                    'message' => 'API Key tidak ditemukan. Sertakan header X-API-KEY.',
                ])
                ->setStatusCode(401);
        }

        $model = new ApiKeyModel();
        $key = $model->getActiveKey($apiKey);

        if (!$key) {
            return service('response')
                ->setJSON([
                    'status'  => 401,
                    'message' => 'API Key tidak valid atau sudah dinonaktifkan.',
                ])
                ->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
