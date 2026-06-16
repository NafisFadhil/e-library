<?php
/** @var array $buku */
/** @var \CodeIgniter\Pager\Pager $pager */
/** @var string|null $keyword */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Cari Buku<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Cari Buku<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">

    <!-- Search Bar -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Pencarian Buku</h5>
        <form action="<?= base_url('dashboard/cari-buku') ?>" method="get">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari judul, penulis, kategori, atau ISBN..." value="<?= esc($keyword ?? '') ?>">
            <button class="btn btn-primary" type="submit">
              <i class="bi bi-search me-1"></i> Cari
            </button>
            <?php if (!empty($keyword)): ?>
              <a href="<?= base_url('dashboard/cari-buku') ?>" class="btn btn-secondary">
                <i class="bi bi-x-lg"></i> Reset
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <?php if (!empty($keyword)): ?>
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i>
        Menampilkan hasil pencarian untuk: <strong>"<?= esc($keyword) ?>"</strong>
      </div>
    <?php endif; ?>

    <!-- Hasil Buku -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Koleksi Buku Perpustakaan</h5>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 80px;">Cover</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Penerbit</th>
                <th class="text-center">Tahun</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Aksi</th>
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
                    <td><strong><?= esc($item['judul']) ?></strong></td>
                    <td><?= esc($item['penulis']) ?></td>
                    <td><span class="badge bg-secondary"><?= esc($item['kategori']) ?></span></td>
                    <td><?= esc($item['penerbit']) ?></td>
                    <td class="text-center"><?= esc($item['tahun_terbit']) ?></td>
                    <td class="text-center">
                      <?php
                        $stok = (int)($item['stok_tersedia'] ?? 0);
                        $badgeClass = $stok > 0 ? 'bg-success' : 'bg-danger';
                      ?>
                      <span class="badge <?= $badgeClass ?>"><?= $stok ?></span>
                    </td>
                    <td class="text-center">
                      <?php if ($stok > 0): ?>
                        <form action="<?= base_url('dashboard/ajukan-pinjam') ?>" method="post" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="isbn" value="<?= esc($item['isbn']) ?>">
                          <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Ajukan peminjaman untuk buku ini?')">
                            <i class="bi bi-hand-index-thumb"></i> Pinjam
                          </button>
                        </form>
                      <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>Kosong</button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0"><?= !empty($keyword) ? 'Tidak ditemukan buku dengan kata kunci tersebut.' : 'Belum ada data buku.' ?></p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if (isset($pager)): ?>
          <div class="d-flex justify-content-end mt-3">
            <?= $pager->links('buku', 'default_full') ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
