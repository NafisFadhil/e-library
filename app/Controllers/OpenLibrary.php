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

    /**
     * Fetch detail buku berdasarkan ISBN dari Open Library API.
     * Digunakan untuk fitur auto-fill via AJAX di halaman Tambah Buku.
     */
    public function fetchByIsbn()
    {
        $isbn = $this->request->getGet('isbn');
        
        if (empty($isbn)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ISBN tidak boleh kosong.'
            ]);
        }

        $isbn = trim((string)$isbn);
        
        try {
            $client = \Config\Services::curlrequest();
            $url = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
            
            $response = $client->request('GET', $url, [
                'timeout'         => 10,
                'connect_timeout' => 5,
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                $key = "ISBN:{$isbn}";

                if (isset($body[$key])) {
                    $bookData = $body[$key];
                    
                    $result = [
                        'success'      => true,
                        'judul'        => $bookData['title'] ?? '',
                        'penulis'      => isset($bookData['authors']) ? implode(', ', array_column($bookData['authors'], 'name')) : '',
                        'penerbit'     => isset($bookData['publishers']) ? implode(', ', array_column($bookData['publishers'], 'name')) : '',
                        'tahun_terbit' => isset($bookData['publish_date']) ? substr($bookData['publish_date'], -4) : '', // Try to extract year
                        'kategori'     => isset($bookData['subjects']) ? implode(', ', array_slice(array_column($bookData['subjects'], 'name'), 0, 3)) : '', // Ambil maksimal 3 kategori pertama
                        'cover_url'    => $bookData['cover']['large'] ?? $bookData['cover']['medium'] ?? $bookData['cover']['small'] ?? null,
                    ];
                    
                    return $this->response->setJSON($result);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Data buku tidak ditemukan di Open Library.'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghubungi server Open Library.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
}
