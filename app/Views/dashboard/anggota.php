<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Member - E-library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
                    <i data-lucide="library" class="text-indigo-600"></i>
                    <span class="font-bold text-xl">E-lib<span class="text-indigo-600">rary</span></span>
                </a>
                <span class="text-slate-300">|</span>
                <span class="text-sm font-semibold text-slate-500">Katalog Member</span>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative hidden md:block">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Cari buku..." class="pl-10 pr-4 py-2 bg-slate-100 rounded-xl text-sm focus:outline-none w-64">
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-900"><?= esc(session()->get('nama') ?? 'Guest') ?></p>
                        <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider"><?= esc(ucfirst(session()->get('role') ?? 'Visitor')) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center font-bold text-indigo-700">
                        <?= strtoupper(substr(session()->get('nama') ?? 'G', 0, 2)) ?>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="ml-2 p-2 text-slate-400 hover:text-rose-500 transition-colors rounded-lg hover:bg-rose-50" title="Logout">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <header class="mb-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Jelajahi Koleksi</h1>
            <p class="text-slate-500">Selamat datang, <strong><?= esc(session()->get('nama') ?? 'Member') ?></strong>! Temukan ribuan buku dari berbagai kategori terbaik.</p>
        </header>

        <!-- Categories -->
        <div class="flex gap-3 mb-10 overflow-x-auto pb-4 no-scrollbar">
            <button class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold whitespace-nowrap">Semua Buku</button>
            <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold whitespace-nowrap hover:bg-slate-50 transition-colors">Fiksi</button>
            <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold whitespace-nowrap hover:bg-slate-50 transition-colors">Teknologi</button>
            <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold whitespace-nowrap hover:bg-slate-50 transition-colors">Bisnis</button>
            <button class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold whitespace-nowrap hover:bg-slate-50 transition-colors">Sejarah</button>
        </div>

        <!-- Book Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            <!-- Book Card -->
            <div class="group cursor-pointer">
                <div class="bg-indigo-100 aspect-[3/4] rounded-3xl mb-4 overflow-hidden relative shadow-sm group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                        <i data-lucide="book-open" class="w-20 h-20"></i>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-[10px] font-bold text-indigo-700 uppercase tracking-widest shadow-sm">Hot</span>
                    </div>
                </div>
                <h3 class="font-bold text-slate-900 line-clamp-1 group-hover:text-indigo-600">Sapiens: Sejarah Singkat Manusia</h3>
                <p class="text-xs text-slate-400">Yuval Noah Harari</p>
            </div>

            <div class="group cursor-pointer">
                <div class="bg-rose-100 aspect-[3/4] rounded-3xl mb-4 overflow-hidden relative shadow-sm group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                        <i data-lucide="book-open" class="w-20 h-20"></i>
                    </div>
                </div>
                <h3 class="font-bold text-slate-900 line-clamp-1 group-hover:text-indigo-600">Laskar Pelangi</h3>
                <p class="text-xs text-slate-400">Andrea Hirata</p>
            </div>

            <div class="group cursor-pointer">
                <div class="bg-amber-100 aspect-[3/4] rounded-3xl mb-4 overflow-hidden relative shadow-sm group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                        <i data-lucide="book-open" class="w-20 h-20"></i>
                    </div>
                </div>
                <h3 class="font-bold text-slate-900 line-clamp-1 group-hover:text-indigo-600">Rich Dad Poor Dad</h3>
                <p class="text-xs text-slate-400">Robert T. Kiyosaki</p>
            </div>

            <div class="group cursor-pointer">
                <div class="bg-emerald-100 aspect-[3/4] rounded-3xl mb-4 overflow-hidden relative shadow-sm group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                        <i data-lucide="book-open" class="w-20 h-20"></i>
                    </div>
                </div>
                <h3 class="font-bold text-slate-900 line-clamp-1 group-hover:text-indigo-600">Filosofi Teras</h3>
                <p class="text-xs text-slate-400">Henry Manampiring</p>
            </div>

            <div class="group cursor-pointer">
                <div class="bg-slate-200 aspect-[3/4] rounded-3xl mb-4 overflow-hidden relative shadow-sm group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                        <i data-lucide="book-open" class="w-20 h-20"></i>
                    </div>
                </div>
                <h3 class="font-bold text-slate-900 line-clamp-1 group-hover:text-indigo-600">Deep Work</h3>
                <p class="text-xs text-slate-400">Cal Newport</p>
            </div>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>