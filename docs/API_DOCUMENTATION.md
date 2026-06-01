# API Documentation — E-Library Webservice

## Base URL

```
http://localhost:8080/api/v1
```

## Autentikasi

Semua endpoint API dilindungi oleh **API Key**. Sertakan key pada header setiap request:

```
X-API-KEY: <your_api_key>
```

API Key dapat di-generate melalui menu **Dashboard Pustakawan → API Keys**.

### Contoh Request dengan API Key

```bash
curl -H "X-API-KEY: your_api_key_here" http://localhost:8080/api/v1/buku
```

---

## Format Response

Semua response menggunakan format **JSON** dengan struktur berikut:

### Response Sukses

```json
{
  "status": 200,
  "message": "Deskripsi sukses",
  "data": { ... },
  "pagination": {
    "page": 1,
    "per_page": 10,
    "total": 50,
    "total_pages": 5
  }
}
```

### Response Error

```json
{
  "status": 401,
  "message": "API Key tidak valid atau sudah dinonaktifkan."
}
```

---

## Kode Status HTTP

| Kode | Keterangan |
|------|------------|
| `200` | OK — Request berhasil |
| `201` | Created — Data berhasil dibuat |
| `400` | Bad Request — Validasi gagal |
| `401` | Unauthorized — API Key tidak valid |
| `404` | Not Found — Data tidak ditemukan |
| `500` | Internal Server Error |

---

## Endpoints

### 1. Buku

#### `GET /api/v1/buku`

Mengambil daftar semua buku dengan pagination dan pencarian.

**Query Parameters:**

| Parameter | Tipe | Wajib | Keterangan |
|-----------|------|-------|------------|
| `page` | int | Tidak | Halaman ke-n (default: 1) |
| `per_page` | int | Tidak | Jumlah data per halaman, max 100 (default: 10) |
| `keyword` | string | Tidak | Kata kunci pencarian (judul, ISBN, penulis, penerbit, kategori) |

**Contoh Request:**

```bash
curl -H "X-API-KEY: your_key" "http://localhost:8080/api/v1/buku?page=1&per_page=5&keyword=pemrograman"
```

**Contoh Response (200 OK):**

```json
{
  "status": 200,
  "message": "Data buku berhasil diambil.",
  "data": [
    {
      "isbn": "978-602-1234-56-7",
      "judul": "Pemrograman Web dengan PHP",
      "kategori": "Teknologi",
      "url_cover": "http://localhost:8080/uploads/covers/abc123.jpg",
      "tahun_terbit": "2024",
      "penerbit": "Gramedia",
      "penulis": "Budi Raharjo",
      "jumlah_eksemplar": 3
    }
  ],
  "pagination": {
    "page": 1,
    "per_page": 5,
    "total": 1,
    "total_pages": 1
  }
}
```

---

#### `GET /api/v1/buku/{isbn}`

Mengambil detail satu buku beserta daftar eksemplarnya.

**Path Parameters:**

| Parameter | Tipe | Keterangan |
|-----------|------|------------|
| `isbn` | string | ISBN buku |

**Contoh Request:**

```bash
curl -H "X-API-KEY: your_key" http://localhost:8080/api/v1/buku/978-602-1234-56-7
```

**Contoh Response (200 OK):**

```json
{
  "status": 200,
  "message": "Detail buku berhasil diambil.",
  "data": {
    "isbn": "978-602-1234-56-7",
    "judul": "Pemrograman Web dengan PHP",
    "kategori": "Teknologi",
    "url_cover": null,
    "tahun_terbit": "2024",
    "penerbit": "Gramedia",
    "penulis": "Budi Raharjo",
    "eksemplar": [
      {
        "kode": "EKS-001",
        "isbn": "978-602-1234-56-7",
        "kondisi": "Baik",
        "lokasi_rak": "A-01",
        "ketersediaan": "Tersedia"
      },
      {
        "kode": "EKS-002",
        "isbn": "978-602-1234-56-7",
        "kondisi": "Baik",
        "lokasi_rak": "A-02",
        "ketersediaan": "Dipinjam"
      }
    ]
  }
}
```

**Contoh Response (404 Not Found):**

```json
{
  "status": 404,
  "error": 404,
  "messages": {
    "error": "Buku dengan ISBN \"999\" tidak ditemukan."
  }
}
```

---

#### `POST /api/v1/buku`

Menambahkan buku baru ke dalam database.

**Headers:**

```
Content-Type: application/json
X-API-KEY: your_key
```

**Body Parameters (JSON):**

| Parameter | Tipe | Wajib | Keterangan |
|-----------|------|-------|------------|
| `isbn` | string | Ya | ISBN buku (unik, max 20 karakter) |
| `judul` | string | Ya | Judul buku (max 255 karakter) |
| `kategori` | string | Ya | Kategori buku (max 100 karakter) |
| `tahun_terbit` | string | Ya | Tahun terbit (4 digit angka) |
| `penerbit` | string | Ya | Nama penerbit (max 100 karakter) |
| `penulis` | string | Ya | Nama penulis (max 100 karakter) |
| `url_cover` | string | Tidak | URL gambar cover buku |

**Contoh Request:**

```bash
curl -X POST \
  -H "X-API-KEY: your_key" \
  -H "Content-Type: application/json" \
  -d '{
    "isbn": "978-602-9999-00-1",
    "judul": "Belajar CodeIgniter 4",
    "kategori": "Teknologi",
    "tahun_terbit": "2025",
    "penerbit": "PT Informatika",
    "penulis": "Ahmad Fauzi"
  }' \
  http://localhost:8080/api/v1/buku
```

**Contoh Response (201 Created):**

```json
{
  "status": 201,
  "message": "Buku berhasil ditambahkan.",
  "data": {
    "isbn": "978-602-9999-00-1",
    "judul": "Belajar CodeIgniter 4",
    "kategori": "Teknologi",
    "url_cover": null,
    "tahun_terbit": "2025",
    "penerbit": "PT Informatika",
    "penulis": "Ahmad Fauzi"
  }
}
```

**Contoh Response (400 Validation Error):**

```json
{
  "status": 400,
  "error": 400,
  "messages": {
    "isbn": "ISBN wajib diisi.",
    "judul": "Judul buku wajib diisi."
  }
}
```

---

### 2. Peminjaman

#### `GET /api/v1/peminjaman`

Mengambil daftar semua peminjaman dengan pagination dan filter status.

**Query Parameters:**

| Parameter | Tipe | Wajib | Keterangan |
|-----------|------|-------|------------|
| `page` | int | Tidak | Halaman ke-n (default: 1) |
| `per_page` | int | Tidak | Jumlah data per halaman, max 100 (default: 10) |
| `status` | string | Tidak | Filter status: `Diajukan`, `Dipinjam`, `Selesai`, `Ditolak` |

**Contoh Request:**

```bash
curl -H "X-API-KEY: your_key" "http://localhost:8080/api/v1/peminjaman?status=Dipinjam&page=1"
```

**Contoh Response (200 OK):**

```json
{
  "status": 200,
  "message": "Data peminjaman berhasil diambil.",
  "data": [
    {
      "id_peminjaman": 1,
      "id_anggota": 1,
      "id_pustakawan": 1,
      "tanggal_pengajuan": "2025-06-01 10:00:00",
      "tanggal_pinjam": "2025-06-01 10:00:00",
      "tanggal_jatuh_tempo": "2025-06-08 10:00:00",
      "status_peminjaman": "Dipinjam",
      "nama_anggota": "Budi Santoso",
      "nama_pustakawan": "Admin Perpus"
    }
  ],
  "pagination": {
    "page": 1,
    "per_page": 10,
    "total": 1,
    "total_pages": 1
  }
}
```

---

#### `GET /api/v1/peminjaman/{id}`

Mengambil detail satu peminjaman beserta detail buku yang dipinjam.

**Path Parameters:**

| Parameter | Tipe | Keterangan |
|-----------|------|------------|
| `id` | int | ID peminjaman |

**Contoh Request:**

```bash
curl -H "X-API-KEY: your_key" http://localhost:8080/api/v1/peminjaman/1
```

**Contoh Response (200 OK):**

```json
{
  "status": 200,
  "message": "Detail peminjaman berhasil diambil.",
  "data": {
    "id_peminjaman": 1,
    "id_anggota": 1,
    "id_pustakawan": 1,
    "tanggal_pengajuan": "2025-06-01 10:00:00",
    "tanggal_pinjam": "2025-06-01 10:00:00",
    "tanggal_jatuh_tempo": "2025-06-08 10:00:00",
    "status_peminjaman": "Dipinjam",
    "nama_anggota": "Budi Santoso",
    "nama_pustakawan": "Admin Perpus",
    "detail_buku": [
      {
        "id_peminjaman": 1,
        "kode_eksemplar": "EKS-001",
        "tanggal_kembali": null,
        "denda": 0,
        "isbn": "978-602-1234-56-7",
        "kondisi": "Baik",
        "lokasi_rak": "A-01",
        "judul_buku": "Pemrograman Web dengan PHP"
      }
    ]
  }
}
```

---

## Catatan Penting

1. **Rate Limiting:** Saat ini belum ada rate limiting. Gunakan API secara bijak.
2. **API Key:** Jaga kerahasiaan API Key Anda. Jika key terekspos, nonaktifkan segera melalui dashboard dan buat key baru.
3. **CORS:** API ini belum dikonfigurasi untuk cross-origin requests. Jika perlu diakses dari frontend JavaScript, hubungi administrator.
