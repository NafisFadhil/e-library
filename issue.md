# Issue: Implementasi Fitur Login, Register, dan Dashboard

## Ringkasan

Implementasikan fitur autentikasi (login & register) dan halaman dashboard untuk dua jenis pengguna:
- **Anggota** — pengguna umum yang mendaftar sendiri melalui halaman register
- **Pustakawan** — staf perpustakaan yang datanya dikelola langsung di database

Login menggunakan **satu halaman dengan 2 tab** (tab Anggota dan tab Pustakawan). Setelah login berhasil, masing-masing diarahkan ke dashboard mereka sendiri yang diproteksi oleh **Filter CodeIgniter**.

---

## Konteks Proyek

- Framework: **CodeIgniter 4**
- Template UI: **NiceAdmin** (Bootstrap 5) — asset sudah tersedia di `public/assets/`
- Layout utama sudah ada di `app/Views/layout/main.php` (extend layout ini untuk halaman dashboard)
- Halaman login & register **tidak** menggunakan layout utama (standalone page, tanpa sidebar/header)
- Routes sudah ada sebagian di `app/Config/Routes.php`
- View login sudah ada di `app/Views/auth/login.php` tapi belum fungsional — **refactor sesuai step ini**
- View register sudah ada di `app/Views/auth/register.php` tapi belum fungsional — **refactor sesuai step ini**
- Controller `Auth.php` sudah ada tapi belum ada logic — **tambahkan method yang dibutuhkan**

---

## Step-by-Step Implementasi

### Step 1 — Buat Tabel Database

Buat 2 tabel berikut di database MySQL.

**Tabel `anggota`** — menyimpan data anggota yang mendaftar:

```sql
CREATE TABLE anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    no_identitas VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_wa VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

**Tabel `pustakawan`** — diisi manual/seeding oleh admin:

```sql
CREATE TABLE pustakawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Seed satu akun pustakawan untuk testing
INSERT INTO pustakawan (nama, username, password)
VALUES ('Admin Pustaka', 'pustakawan', '$2y$10$...'); 
-- Generate password hash dengan: password_hash('password123', PASSWORD_BCRYPT)
```

> Jalankan perintah ini di terminal untuk mendapat hash password:
> ```bash
> php -r "echo password_hash('password123', PASSWORD_BCRYPT);"
> ```
> Lalu paste hasilnya ke query INSERT di atas.

---

### Step 2 — Buat Model

Buat dua file model di `app/Models/`.

**`app/Models/AnggotaModel.php`**

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table      = 'anggota';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama', 'no_identitas', 'email', 'no_wa', 'password'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
```

**`app/Models/PustakawanModel.php`**

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class PustakawanModel extends Model
{
    protected $table      = 'pustakawan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama', 'username', 'password'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
```

---

### Step 3 — Update View Login (2 Tab)

Refactor `app/Views/auth/login.php` menjadi halaman standalone (tidak extend layout main) dengan **2 tab Bootstrap**: tab "Anggota" dan tab "Pustakawan".

Struktur HTML yang diinginkan:

- Gunakan aset dari `base_url('assets/...')` bukan path relatif
- Ada logo E-Library di atas card
- Card berisi **Bootstrap Nav Tabs** dengan 2 tab:
  - **Tab Anggota**: form dengan field `email` + `password`, action POST ke `/login/process`, ada hidden input `role` bernilai `anggota`
  - **Tab Pustakawan**: form dengan field `username` + `password`, action POST ke `/login/process`, ada hidden input `role` bernilai `pustakawan`
- Di bawah card tab anggota: link "Belum punya akun? Daftar di sini" → `/register`
- Tampilkan flash message error jika login gagal (gunakan `session()->getFlashdata('error')`)

Contoh struktur form tiap tab:

```html
<!-- Tab Anggota -->
<form action="<?= base_url('login/process') ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="role" value="anggota">
    <!-- field email & password -->
    <button type="submit" class="btn btn-primary w-100">Login sebagai Anggota</button>
</form>

<!-- Tab Pustakawan -->
<form action="<?= base_url('login/process') ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="role" value="pustakawan">
    <!-- field username & password -->
    <button type="submit" class="btn btn-primary w-100">Login sebagai Pustakawan</button>
</form>
```

---

### Step 4 — Update View Register

Refactor `app/Views/auth/register.php` menjadi halaman standalone dengan form pendaftaran anggota.

Field yang dibutuhkan:
- Nama Lengkap (`nama`)
- Nomor Identitas (`no_identitas`) — NIM / NIK
- Email (`email`)
- Nomor WhatsApp (`no_wa`)
- Password (`password`)
- Konfirmasi Password (`password_confirm`)

Form action POST ke `/register/process`, tambahkan `<?= csrf_field() ?>`.

Tampilkan flash message `error` dan `success` di atas form jika ada.

---

### Step 5 — Lengkapi Auth Controller

Edit `app/Controllers/Auth.php`. Tambahkan method `processLogin()` dan `processRegister()`.

**Method `processLogin()`** — logika:

1. Ambil `role` dari POST (`anggota` atau `pustakawan`)
2. Validasi input (tidak boleh kosong)
3. Jika `role == 'anggota'`:
   - Cari user di tabel `anggota` berdasarkan `email`
   - Verifikasi password dengan `password_verify()`
   - Jika cocok → simpan session: `user_id`, `nama`, `role` = `'anggota'`
   - Redirect ke `/dashboard/anggota`
4. Jika `role == 'pustakawan'`:
   - Cari user di tabel `pustakawan` berdasarkan `username`
   - Verifikasi password dengan `password_verify()`
   - Jika cocok → simpan session: `user_id`, `nama`, `role` = `'pustakawan'`
   - Redirect ke `/dashboard/pustakawan`
5. Jika gagal (user tidak ditemukan / password salah): set flash message error, redirect kembali ke `/login`

```php
public function processLogin()
{
    $role = $this->request->getPost('role');

    if ($role === 'anggota') {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $anggotaModel = new \App\Models\AnggotaModel();
        $user = $anggotaModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id' => $user['id'],
                'nama'    => $user['nama'],
                'role'    => 'anggota',
                'logged_in' => true,
            ]);
            return redirect()->to('/dashboard/anggota');
        }
    } elseif ($role === 'pustakawan') {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $pustakawanModel = new \App\Models\PustakawanModel();
        $user = $pustakawanModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id' => $user['id'],
                'nama'    => $user['nama'],
                'role'    => 'pustakawan',
                'logged_in' => true,
            ]);
            return redirect()->to('/dashboard/pustakawan');
        }
    }

    session()->setFlashdata('error', 'Username/email atau password salah.');
    return redirect()->to('/login');
}
```

**Method `processRegister()`** — logika:

1. Validasi input: semua field wajib diisi, email unik, no_identitas unik, password & password_confirm harus sama
2. Hash password dengan `password_hash($password, PASSWORD_BCRYPT)`
3. Simpan ke tabel `anggota`
4. Set flash message success, redirect ke `/login`

```php
public function processRegister()
{
    $validation = \Config\Services::validation();

    $rules = [
        'nama'             => 'required',
        'no_identitas'     => 'required|is_unique[anggota.no_identitas]',
        'email'            => 'required|valid_email|is_unique[anggota.email]',
        'no_wa'            => 'required',
        'password'         => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
    ];

    if (!$this->validate($rules)) {
        session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
        return redirect()->to('/register');
    }

    $anggotaModel = new \App\Models\AnggotaModel();
    $anggotaModel->insert([
        'nama'         => $this->request->getPost('nama'),
        'no_identitas' => $this->request->getPost('no_identitas'),
        'email'        => $this->request->getPost('email'),
        'no_wa'        => $this->request->getPost('no_wa'),
        'password'     => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
    ]);

    session()->setFlashdata('success', 'Pendaftaran berhasil! Silakan login.');
    return redirect()->to('/login');
}
```

Tambahkan juga method `logout()`:

```php
public function logout()
{
    session()->destroy();
    return redirect()->to('/login');
}
```

---

### Step 6 — Buat Filter

Buat 2 file filter di `app/Filters/`.

**`app/Filters/AuthAnggota.php`** — memproteksi halaman khusus anggota:

```php
<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAnggota implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'anggota') {
            session()->setFlashdata('error', 'Anda harus login sebagai anggota.');
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
```

**`app/Filters/AuthPustakawan.php`** — memproteksi halaman khusus pustakawan:

```php
<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthPustakawan implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'pustakawan') {
            session()->setFlashdata('error', 'Anda harus login sebagai pustakawan.');
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
```

---

### Step 7 — Daftarkan Filter di Config

Edit `app/Config/Filters.php`. Di dalam array `$aliases`, tambahkan:

```php
public array $aliases = [
    // ... alias bawaan CI4 ...
    'auth_anggota'    => \App\Filters\AuthAnggota::class,
    'auth_pustakawan' => \App\Filters\AuthPustakawan::class,
];
```

---

### Step 8 — Update Routes

Edit `app/Config/Routes.php` agar menjadi seperti ini:

```php
<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Halaman publik
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::processLogin');
$routes->get('register', 'Auth::register');
$routes->post('register/process', 'Auth::processRegister');
$routes->get('logout', 'Auth::logout');

// Dashboard Anggota — diproteksi filter auth_anggota
$routes->group('dashboard', ['filter' => 'auth_anggota'], function ($routes) {
    $routes->get('anggota', 'Dashboard::anggota');
});

// Dashboard Pustakawan — diproteksi filter auth_pustakawan
$routes->group('dashboard', ['filter' => 'auth_pustakawan'], function ($routes) {
    $routes->get('pustakawan', 'Dashboard::pustakawan');
});
```

---

### Step 9 — Buat Dashboard Controller

Buat file `app/Controllers/Dashboard.php`:

```php
<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function anggota()
    {
        $data = [
            'nama' => session()->get('nama'),
        ];
        return view('dashboard/anggota', $data);
    }

    public function pustakawan()
    {
        $data = [
            'nama' => session()->get('nama'),
        ];
        return view('dashboard/pustakawan', $data);
    }
}
```

---

### Step 10 — Buat View Dashboard

**`app/Views/dashboard/anggota.php`** — dashboard sederhana untuk anggota, extend `layout/main`:

```php
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard Anggota<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Dashboard Anggota<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="alert alert-success">
      Selamat datang, <strong><?= esc($nama) ?></strong>! Kamu login sebagai Anggota.
    </div>
  </div>

  <!-- Kartu ringkasan -->
  <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card">
      <div class="card-body">
        <h5 class="card-title">Buku Dipinjam</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-book"></i>
          </div>
          <div class="ps-3">
            <h6>0</h6>
            <span class="text-muted small">buku aktif</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-4 col-md-6">
    <div class="card info-card revenue-card">
      <div class="card-body">
        <h5 class="card-title">Riwayat Peminjaman</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-clock-history"></i>
          </div>
          <div class="ps-3">
            <h6>0</h6>
            <span class="text-muted small">total pinjaman</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 mt-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Buku Tersedia</h5>
        <p class="text-muted">Fitur pencarian dan peminjaman buku akan tersedia di sini.</p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
```

**`app/Views/dashboard/pustakawan.php`** — dashboard sederhana untuk pustakawan, extend `layout/main`:

```php
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard Pustakawan<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Dashboard Pustakawan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="alert alert-info">
      Selamat datang, <strong><?= esc($nama) ?></strong>! Kamu login sebagai Pustakawan.
    </div>
  </div>

  <!-- Kartu ringkasan -->
  <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card">
      <div class="card-body">
        <h5 class="card-title">Total Anggota</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-people"></i>
          </div>
          <div class="ps-3">
            <h6>0</h6>
            <span class="text-muted small">anggota terdaftar</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-4 col-md-6">
    <div class="card info-card revenue-card">
      <div class="card-body">
        <h5 class="card-title">Total Buku</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-journals"></i>
          </div>
          <div class="ps-3">
            <h6>0</h6>
            <span class="text-muted small">koleksi buku</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-4 col-md-6">
    <div class="card info-card customers-card">
      <div class="card-body">
        <h5 class="card-title">Peminjaman Aktif</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-bookmark-check"></i>
          </div>
          <div class="ps-3">
            <h6>0</h6>
            <span class="text-muted small">sedang dipinjam</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 mt-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Manajemen Perpustakaan</h5>
        <p class="text-muted">Fitur manajemen buku, anggota, dan peminjaman akan tersedia di sini.</p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
```

---

## Checklist

Gunakan checklist ini untuk memastikan semua sudah selesai:

- [ ] Tabel `anggota` dan `pustakawan` sudah dibuat di database
- [ ] Seed data pustakawan awal sudah dimasukkan
- [ ] `AnggotaModel.php` dan `PustakawanModel.php` sudah dibuat
- [ ] View `auth/login.php` sudah direfactor dengan 2 tab Bootstrap
- [ ] View `auth/register.php` sudah direfactor dengan form yang fungsional
- [ ] `Auth::processLogin()` sudah menghandle kedua role dan redirect yang benar
- [ ] `Auth::processRegister()` sudah memvalidasi dan menyimpan data anggota
- [ ] `Auth::logout()` sudah menghapus session dan redirect ke login
- [ ] Filter `AuthAnggota.php` dan `AuthPustakawan.php` sudah dibuat
- [ ] Filter sudah didaftarkan di `app/Config/Filters.php` (`$aliases`)
- [ ] Routes sudah diupdate dengan group dan filter yang benar
- [ ] `Dashboard.php` controller sudah dibuat dengan method `anggota()` dan `pustakawan()`
- [ ] View `dashboard/anggota.php` dan `dashboard/pustakawan.php` sudah dibuat
- [ ] Login anggota berhasil redirect ke `/dashboard/anggota`
- [ ] Login pustakawan berhasil redirect ke `/dashboard/pustakawan`
- [ ] Akses `/dashboard/anggota` tanpa login diredirect ke `/login`
- [ ] Akses `/dashboard/pustakawan` tanpa login diredirect ke `/login`
- [ ] Anggota tidak bisa akses dashboard pustakawan (dan sebaliknya)
- [ ] Logout berfungsi dan menghapus session

---

## Alur Lengkap (Flow)

```
[Pengunjung]
    │
    ├─ GET /login
    │       └─ Tampil halaman login dengan 2 tab
    │               ├─ Tab Anggota → POST /login/process (role=anggota)
    │               │       ├─ Sukses → session set (role=anggota) → redirect /dashboard/anggota
    │               │       └─ Gagal  → flash error → redirect /login
    │               │
    │               └─ Tab Pustakawan → POST /login/process (role=pustakawan)
    │                       ├─ Sukses → session set (role=pustakawan) → redirect /dashboard/pustakawan
    │                       └─ Gagal  → flash error → redirect /login
    │
    ├─ GET /register
    │       └─ Tampil form pendaftaran anggota
    │               └─ POST /register/process
    │                       ├─ Sukses → flash success → redirect /login
    │                       └─ Gagal  → flash error   → redirect /register
    │
    ├─ GET /dashboard/anggota   [Filter: AuthAnggota]
    │       ├─ Session valid (role=anggota) → tampil dashboard anggota
    │       └─ Tidak valid → redirect /login
    │
    ├─ GET /dashboard/pustakawan [Filter: AuthPustakawan]
    │       ├─ Session valid (role=pustakawan) → tampil dashboard pustakawan
    │       └─ Tidak valid → redirect /login
    │
    └─ GET /logout
            └─ session()->destroy() → redirect /login
```

---

## Catatan Penting

1. **CSRF**: Semua form POST wajib menggunakan `<?= csrf_field() ?>` karena CSRF protection CI4 aktif secara default.
2. **Password**: Selalu gunakan `password_hash()` saat simpan dan `password_verify()` saat cek. Jangan simpan plain text.
3. **XSS**: Gunakan `esc()` saat menampilkan data dari session atau database ke view.
4. **Asset path**: View login & register adalah standalone (tidak extend layout), gunakan `base_url('assets/...')` agar path asset benar.
5. **Session driver**: Pastikan session sudah dikonfigurasi di `.env` atau `app/Config/Session.php`. Default CI4 sudah cukup untuk development.
6. **Redirect setelah login**: Jika user sudah login dan mengakses `/login`, pertimbangkan untuk langsung redirect ke dashboard mereka (opsional, bisa diimplementasi di method `login()` di Auth controller).
