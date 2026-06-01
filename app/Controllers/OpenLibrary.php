<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class OpenLibrary extends BaseController
{
    protected $helpers = ['form', 'url'];

    /**
     * Halaman Cari Buku Online — mengonsumsi API Open Library.
     * Endpoint: https://openlibrary.org/search.json
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        $books   = [];
        $error   = null;
        $fromCache = false;

        if (!empty($keyword)) {
            // Buat cache key berdasarkan keyword
            $cacheKey = 'openlibrary_' . md5(strtolower(trim($keyword)));

            // Cek apakah data sudah ada di cache (10 menit)
            $cached = cache($cacheKey);

            if ($cached !== null) {
                $books     = $cached;
                $fromCache = true;
            } else {
                try {
                    $client = \Config\Services::curlrequest();

                    $response = $client->request('GET', 'https://openlibrary.org/search.json', [
                        'query' => [
                            'q'      => $keyword,
                            'limit'  => 20,
                            'fields' => 'title,author_name,first_publish_year,publisher,isbn,cover_i,language,edition_count',
                        ],
                        'timeout'         => 10,
                        'connect_timeout' => 5,
                    ]);

                    $statusCode = $response->getStatusCode();

                    if ($statusCode === 200) {
                        $body = json_decode($response->getBody(), true);

                        if (isset($body['docs']) && is_array($body['docs'])) {
                            foreach ($body['docs'] as $doc) {
                                $books[] = [
                                    'judul'        => $doc['title'] ?? 'Tidak diketahui',
                                    'penulis'      => isset($doc['author_name']) ? implode(', ', $doc['author_name']) : 'Tidak diketahui',
                                    'tahun_terbit' => $doc['first_publish_year'] ?? '-',
                                    'penerbit'     => isset($doc['publisher']) ? $doc['publisher'][0] : '-',
                                    'isbn'         => isset($doc['isbn']) ? $doc['isbn'][0] : '-',
                                    'cover_url'    => isset($doc['cover_i']) ? 'https://covers.openlibrary.org/b/id/' . $doc['cover_i'] . '-M.jpg' : null,
                                    'bahasa'       => isset($doc['language']) ? implode(', ', array_slice($doc['language'], 0, 3)) : '-',
                                    'edisi'        => $doc['edition_count'] ?? 0,
                                ];
                            }

                            // Simpan ke cache selama 10 menit (600 detik)
                            cache()->save($cacheKey, $books, 600);
                        }
                    } else {
                        $error = 'API Open Library merespon dengan kode status: ' . $statusCode;
                    }
                } catch (\CodeIgniter\HTTP\Exceptions\HTTPException $e) {
                    $error = 'Gagal menghubungi API Open Library. Pastikan koneksi internet Anda aktif. (' . $e->getMessage() . ')';
                } catch (\Exception $e) {
                    $error = 'Terjadi kesalahan saat mengambil data dari API eksternal. (' . $e->getMessage() . ')';
                }
            }
        }

        $data = [
            'keyword'   => $keyword,
            'books'     => $books,
            'error'     => $error,
            'fromCache' => $fromCache,
        ];

        return view('anggota_fitur/cari_buku_online', $data);
    }
}
