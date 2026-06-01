<?php
/** @var string|null $keyword */
/** @var array $books */
/** @var string|null $error */
/** @var bool $fromCache */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Cari Buku Online<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Cari Buku Online<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">

    <!-- Info Sumber Data -->
    <div class="alert alert-primary d-flex align-items-center" role="alert">
      <i class="bi bi-globe2 me-2" style="font-size: 1.2rem;"></i>
      <div>
        <strong>Sumber: Open Library API</strong> — Cari jutaan buku dari database global Open Library (openlibrary.org).
      </div>
    </div>

    <!-- Search Bar -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Pencarian Buku Online</h5>
        <form action="<?= base_url('dashboard/cari-buku-online') ?>" method="get">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari judul buku, penulis, atau ISBN... (contoh: Harry Potter)" value="<?= esc($keyword ?? '') ?>">
            <button class="btn btn-primary" type="submit">
              <i class="bi bi-search me-1"></i> Cari
            </button>
            <?php if (!empty($keyword)): ?>
              <a href="<?= base_url('dashboard/cari-buku-online') ?>" class="btn btn-secondary">
                <i class="bi bi-x-lg"></i> Reset
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Error Message -->
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>
        <strong>Error:</strong> <?= esc($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Info Keyword -->
    <?php if (!empty($keyword) && empty($error)): ?>
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i>
        Menampilkan hasil pencarian untuk: <strong>"<?= esc($keyword) ?>"</strong>
        — Ditemukan <strong><?= count($books) ?></strong> buku
        <?php if ($fromCache): ?>
          <span class="badge bg-secondary ms-1"><i class="bi bi-lightning me-1"></i>Dari Cache</span>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Hasil Pencarian -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Hasil Pencarian</h5>

        <?php if (empty($keyword)): ?>
          <div class="text-center py-5 text-muted">
            <i class="bi bi-search" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-0">Masukkan kata kunci untuk mulai mencari buku dari database global Open Library.</p>
          </div>
        <?php elseif (!empty($books)): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width: 80px;">Cover</th>
                  <th>Judul Buku</th>
                  <th>Penulis</th>
                  <th class="text-center">Tahun</th>
                  <th>Penerbit</th>
                  <th class="text-center">Edisi</th>
                  <th>ISBN</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($books as $book): ?>
                  <tr>
                    <td class="text-center">
                      <?php if (!empty($book['cover_url'])): ?>
                        <img src="<?= esc($book['cover_url']) ?>" alt="Cover" class="img-thumbnail" style="max-height: 80px; max-width: 60px; object-fit: cover;">
                      <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 80px; width: 60px; font-size: 10px;">
                          No Cover
                        </div>
                      <?php endif; ?>
                    </td>
                    <td><strong><?= esc($book['judul']) ?></strong></td>
                    <td><?= esc($book['penulis']) ?></td>
                    <td class="text-center"><?= esc($book['tahun_terbit']) ?></td>
                    <td><?= esc($book['penerbit']) ?></td>
                    <td class="text-center">
                      <span class="badge bg-info text-dark"><?= (int) $book['edisi'] ?></span>
                    </td>
                    <td><small class="text-muted"><?= esc($book['isbn']) ?></small></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5 text-muted">
            <i class="bi bi-journal-x" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-0">Tidak ditemukan buku dengan kata kunci "<strong><?= esc($keyword) ?></strong>".</p>
          </div>
        <?php endif; ?>

      </div>
    </div>

  </div>
</div>
<?= $this->endSection() ?>
