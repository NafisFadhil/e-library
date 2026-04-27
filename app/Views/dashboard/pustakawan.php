<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pustakawan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <!-- Sidebar Mini -->
    <aside class="w-20 bg-slate-900 flex flex-col items-center py-8 gap-8 fixed h-full">
        <a href="<?= base_url('/') ?>" class="bg-indigo-600 p-2 rounded-xl text-white">
            <i data-lucide="library" class="w-6 h-6"></i>
        </a>
        <nav class="flex flex-col gap-6 text-slate-400 flex-1">
            <a href="<?= base_url('dashboard/pustakawan') ?>" class="p-3 bg-white/10 text-white rounded-xl" title="Dashboard"><i data-lucide="layout-dashboard"></i></a>
            <a href="#" class="p-3 hover:text-white transition-colors" title="Data Buku"><i data-lucide="book"></i></a>
            <a href="#" class="p-3 hover:text-white transition-colors" title="Data Anggota"><i data-lucide="users"></i></a>
            <a href="#" class="p-3 hover:text-white transition-colors" title="Pengaturan"><i data-lucide="settings"></i></a>
        </nav>
        <!-- Logout di bawah sidebar -->
        <a href="<?= base_url('logout') ?>" class="p-3 text-slate-400 hover:text-rose-400 transition-colors" title="Logout">
            <i data-lucide="log-out"></i>
        </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 ml-20">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Dashboard Pustakawan</h1>
                <p class="text-slate-500">Selamat datang, <strong><?= esc(session()->get('nama') ?? 'Pustakawan') ?></strong>! Kelola data perpustakaan di sini.</p>
            </div>
            <div class="flex items-center gap-4">
                <button class="bg-white border border-slate-200 px-4 py-2.5 rounded-xl flex items-center gap-2 font-medium hover:bg-slate-50 transition-all text-sm">
                    <i data-lucide="download" class="w-4 h-4"></i> Export Data
                </button>
                <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 text-sm">
                    <i data-lucide="plus" class="w-5 h-5"></i> Buku Baru
                </button>
                <!-- Profil -->
                <div class="flex items-center gap-3 ml-4 pl-4 border-l border-slate-200">
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-900"><?= esc(session()->get('nama') ?? 'Guest') ?></p>
                        <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider"><?= esc(ucfirst(session()->get('role') ?? 'Visitor')) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center font-bold text-indigo-700">
                        <?= strtoupper(substr(session()->get('nama') ?? 'G', 0, 2)) ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-400 text-sm font-semibold mb-2 uppercase tracking-wider">Total Buku</p>
                <h3 class="text-4xl font-black text-slate-900">2,450</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 border-l-4 border-l-orange-400">
                <p class="text-slate-400 text-sm font-semibold mb-2 uppercase tracking-wider">Peminjaman</p>
                <h3 class="text-4xl font-black text-slate-900">142</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-400 text-sm font-semibold mb-2 uppercase tracking-wider">Anggota</p>
                <h3 class="text-4xl font-black text-slate-900">1,200</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-400 text-sm font-semibold mb-2 uppercase tracking-wider">Terlambat</p>
                <h3 class="text-4xl font-black text-rose-600">8</h3>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h2 class="font-bold text-slate-800">Daftar Koleksi Terbaru</h2>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Cari buku..." class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                </div>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-xs font-bold uppercase border-b border-slate-100">
                        <th class="px-8 py-5">Judul & Penulis</th>
                        <th class="px-8 py-5">Kategori</th>
                        <th class="px-8 py-5 text-center">Tersedia</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-8 py-5">
                            <div class="font-bold text-slate-900">Atomic Habits</div>
                            <div class="text-xs text-slate-400">James Clear</div>
                        </td>
                        <td class="px-8 py-5 text-sm">Pengembangan Diri</td>
                        <td class="px-8 py-5 text-center text-sm font-medium">12</td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold uppercase">Tersedia</span>
                        </td>
                        <td class="px-8 py-5">
                            <button class="text-slate-400 hover:text-indigo-600 transition-colors"><i data-lucide="edit-3" class="w-5 h-5"></i></button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-8 py-5">
                            <div class="font-bold text-slate-900">Filosofi Teras</div>
                            <div class="text-xs text-slate-400">Henry Manampiring</div>
                        </td>
                        <td class="px-8 py-5 text-sm">Filsafat</td>
                        <td class="px-8 py-5 text-center text-sm font-medium">0</td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-bold uppercase">Kosong</span>
                        </td>
                        <td class="px-8 py-5">
                            <button class="text-slate-400 hover:text-indigo-600 transition-colors"><i data-lucide="edit-3" class="w-5 h-5"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>