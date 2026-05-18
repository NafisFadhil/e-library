<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Daftar Buku<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Daftar Buku<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Success & Error Alert Snippet -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center my-3">
          <h5 class="card-title m-0">Koleksi Buku Perpustakaan</h5>
          <a href="<?= base_url('dashboard/buku/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Buku
          </a>
        </div>

        <!-- Search Bar -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-6 col-lg-5">
            <form action="<?= base_url('dashboard/buku') ?>" method="get">
              <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white text-muted border-end-0">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Cari judul, penulis, kategori, penerbit, ISBN..." value="<?= esc($keyword ?? '') ?>">
                <?php if (!empty($keyword)): ?>
                  <a href="<?= base_url('dashboard/buku') ?>" class="btn btn-outline-secondary border-start-0 border-end-0 d-flex align-items-center justify-content-center" title="Reset Pencarian">
                    <i class="bi bi-x-lg"></i>
                  </a>
                <?php endif; ?>
                <button class="btn btn-primary px-4" type="submit">Cari</button>
              </div>
            </form>
          </div>
          <div class="col-md-6 col-lg-7 text-md-end">
            <?php if (!empty($keyword)): ?>
              <span class="text-muted small">
                Menampilkan hasil pencarian untuk: <strong class="text-primary">"<?= esc($keyword) ?>"</strong>
              </span>
            <?php endif; ?>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 80px;">Cover</th>
                <th>ISBN</th>
                <th>Judul Buku</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th class="text-center">Tahun</th>
                <th class="text-center">Eksemplar</th>
                <th class="text-center" style="width: 250px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($buku) && count($buku) > 0): ?>
                <?php foreach ($buku as $item): ?>
                  <tr>
                    <td class="text-center">
                      <?php if (!empty($item['url_cover'])): ?>
                        <img src="<?= esc($item['url_cover']) ?>" alt="Cover" class="img-thumbnail" style="max-height: 80px; max-width: 60px; object-fit: cover;">
                      <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 80px; width: 60px; font-size: 10px;">
                          No Cover
                        </div>
                      <?php endif; ?>
                    </td>
                    <td><code class="text-dark font-monospace"><?= esc($item['isbn']) ?></code></td>
                    <td><strong><?= esc($item['judul']) ?></strong></td>
                    <td><span class="badge bg-secondary"><?= esc($item['kategori']) ?></span></td>
                    <td><?= esc($item['penulis']) ?></td>
                    <td><?= esc($item['penerbit']) ?></td>
                    <td class="text-center"><?= esc($item['tahun_terbit']) ?></td>
                    <td class="text-center">
                      <span class="badge bg-info text-dark font-monospace"><?= esc($item['jumlah_eksemplar']) ?></span>
                    </td>
                    <td class="text-center">
                      <a href="<?= base_url('dashboard/buku/' . esc($item['isbn']) . '/eksemplar') ?>" class="btn btn-info btn-sm text-dark me-1" title="Kelola Eksemplar">
                        <i class="bi bi-journal-text me-1"></i> Eksemplar
                      </a>
                      <a href="<?= base_url('dashboard/buku/edit/' . esc($item['isbn'])) ?>" class="btn btn-warning btn-sm me-1" title="Edit Buku">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="<?= base_url('dashboard/buku/delete/' . esc($item['isbn'])) ?>" 
                         onclick="return confirm('Yakin ingin menghapus buku ini? Semua eksemplar terkait juga akan terhapus.')" 
                         class="btn btn-danger btn-sm" title="Hapus Buku">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="9" class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0"><?= !empty($keyword) ? 'Tidak ditemukan buku dengan kata kunci tersebut.' : 'Belum ada data buku.' ?></p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Server-side Pagination Links -->
        <?php if (isset($pager)): ?>
          <div class="d-flex justify-content-end mt-3">
            <?= $pager->only(['keyword'])->links('buku', 'default_full') ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
