<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fitur E-Library - Platform Perpustakaan Digital</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>

<body class="bg-white text-slate-800">

  <!-- Header / Nav -->
  <nav class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
    <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
      <div class="bg-indigo-600 p-2 rounded-xl text-white">
        <i data-lucide="library" class="w-6 h-6"></i>
      </div>
      <span class="text-2xl font-bold tracking-tight text-slate-900">E-Lib<span class="text-indigo-600">rary</span></span>
    </a>
    <div class="flex gap-4 items-center">
      <?php if (session()->get('logged_in')): ?>
        <a href="<?= base_url('dashboard/' . session()->get('role')) ?>" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
          Ke Dashboard
        </a>
      <?php else: ?>
        <a href="<?= base_url('login') ?>" class="px-6 py-2.5 font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Masuk</a>
        <a href="<?= base_url('register') ?>" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">Daftar</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="max-w-7xl mx-auto px-6 py-16 text-center">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-sm font-bold border border-indigo-100 mb-6">
      <i data-lucide="sparkles" class="w-4 h-4"></i>
      Fitur Unggulan
    </div>
    <h1 class="text-5xl font-extrabold text-slate-900 leading-tight mb-4">
      Semua yang Kamu Butuhkan untuk <span class="text-indigo-600">Mengelola Perpustakaan</span>
    </h1>
    <p class="text-xl text-slate-500 max-w-2xl mx-auto">
      E-Library menyediakan solusi lengkap bagi pustakawan dan anggota perpustakaan dengan antarmuka modern dan mudah digunakan.
    </p>
  </section>

  <!-- Features Grid -->
  <section class="max-w-7xl mx-auto px-6 pb-20">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

      <!-- Feature 1 -->
      <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="bg-indigo-100 p-4 rounded-2xl text-indigo-600 w-fit mb-6">
          <i data-lucide="book-open" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-3">Manajemen Buku</h3>
        <p class="text-slate-500 leading-relaxed">
          Kelola koleksi buku perpustakaan dengan mudah. Tambah, edit, dan hapus data buku lengkap dengan cover, ISBN, kategori, dan informasi penerbit.
        </p>
      </div>

      <!-- Feature 2 -->
      <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="bg-emerald-100 p-4 rounded-2xl text-emerald-600 w-fit mb-6">
          <i data-lucide="copy" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-3">Eksemplar & Stok</h3>
        <p class="text-slate-500 leading-relaxed">
          Lacak setiap salinan buku secara individual dengan kode unik, kondisi fisik, lokasi rak, dan status ketersediaan real-time.
        </p>
      </div>

      <!-- Feature 3 -->
      <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="bg-amber-100 p-4 rounded-2xl text-amber-600 w-fit mb-6">
          <i data-lucide="users" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-3">Manajemen Anggota</h3>
        <p class="text-slate-500 leading-relaxed">
          Kelola data anggota perpustakaan termasuk registrasi, profil, status keanggotaan, dan autentikasi login yang aman.
        </p>
      </div>

      <!-- Feature 4 -->
      <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="bg-blue-100 p-4 rounded-2xl text-blue-600 w-fit mb-6">
          <i data-lucide="clipboard-check" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-3">Sistem Peminjaman</h3>
        <p class="text-slate-500 leading-relaxed">
          Proses peminjaman multi-buku dengan alur lengkap: pengajuan, persetujuan, peminjaman aktif, pengembalian, hingga perhitungan denda otomatis.
        </p>
      </div>

      <!-- Feature 5 -->
      <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="bg-rose-100 p-4 rounded-2xl text-rose-600 w-fit mb-6">
          <i data-lucide="search" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-3">Pencarian Buku</h3>
        <p class="text-slate-500 leading-relaxed">
          Anggota dapat mencari buku berdasarkan judul, penulis, kategori, atau ISBN dengan hasil pencarian yang cepat dan akurat.
        </p>
      </div>

      <!-- Feature 6 -->
      <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="bg-violet-100 p-4 rounded-2xl text-violet-600 w-fit mb-6">
          <i data-lucide="shield-check" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-3">Keamanan & Role</h3>
        <p class="text-slate-500 leading-relaxed">
          Sistem autentikasi berbasis role (Pustakawan & Anggota) dengan password terenkripsi, CSRF protection, dan akses kontrol pada setiap halaman.
        </p>
      </div>

    </div>
  </section>

  <!-- Role Comparison -->
  <section class="max-w-7xl mx-auto px-6 pb-20">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-extrabold text-slate-900 mb-3">Dua Role, Satu Platform</h2>
      <p class="text-lg text-slate-500">Setiap pengguna mendapat pengalaman yang disesuaikan dengan kebutuhannya.</p>
    </div>
    <div class="grid md:grid-cols-2 gap-8">

      <!-- Pustakawan -->
      <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-3xl p-8">
        <div class="flex items-center gap-3 mb-6">
          <div class="bg-indigo-600 p-3 rounded-xl text-white">
            <i data-lucide="settings" class="w-6 h-6"></i>
          </div>
          <h3 class="text-2xl font-bold text-indigo-900">Pustakawan</h3>
        </div>
        <ul class="space-y-3 text-indigo-800">
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-indigo-600"></i> Dashboard statistik real-time</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-indigo-600"></i> CRUD Buku & Eksemplar</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-indigo-600"></i> Manajemen Data Anggota</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-indigo-600"></i> Kelola Peminjaman & Pengembalian</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-indigo-600"></i> Hitung Denda Otomatis</li>
        </ul>
      </div>

      <!-- Anggota -->
      <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-3xl p-8">
        <div class="flex items-center gap-3 mb-6">
          <div class="bg-emerald-600 p-3 rounded-xl text-white">
            <i data-lucide="user" class="w-6 h-6"></i>
          </div>
          <h3 class="text-2xl font-bold text-emerald-900">Anggota</h3>
        </div>
        <ul class="space-y-3 text-emerald-800">
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i> Dashboard pribadi</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i> Cari & Telusuri Katalog Buku</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i> Riwayat Peminjaman Lengkap</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i> Cek Detail & Status Pinjaman</li>
          <li class="flex items-center gap-2"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i> Informasi Denda Transparan</li>
        </ul>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="max-w-7xl mx-auto px-6 pb-20">
    <div class="bg-slate-900 rounded-3xl p-12 text-center">
      <h2 class="text-3xl font-extrabold text-white mb-4">Siap Mulai?</h2>
      <p class="text-slate-400 text-lg mb-8 max-w-lg mx-auto">Daftar sekarang dan nikmati kemudahan mengelola perpustakaan digital.</p>
      <div class="flex justify-center gap-4 flex-wrap">
        <?php if (session()->get('logged_in')): ?>
          <a href="<?= base_url('dashboard/' . session()->get('role')) ?>" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg">
            Buka Dashboard
          </a>
        <?php else: ?>
          <a href="<?= base_url('register') ?>" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg">
            Daftar Gratis
          </a>
          <a href="<?= base_url('login') ?>" class="px-8 py-4 border-2 border-slate-600 text-slate-300 rounded-2xl font-bold hover:bg-slate-800 transition-all">
            Masuk
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="max-w-7xl mx-auto px-6 py-8 border-t border-slate-100 text-center">
    <p class="text-slate-400 text-sm">&copy; <?= date('Y') ?> E-Library. Dibuat dengan ❤️ untuk perpustakaan modern.</p>
  </footer>

  <script>
    lucide.createIcons();
  </script>
</body>

</html>
