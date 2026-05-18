<?php
/** @var string $nama */
/** @var int $total_anggota */
/** @var int $total_buku */
/** @var int $peminjaman_aktif */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard Pustakawan<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Dashboard Pustakawan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="alert alert-info">
      Selamat datang, <strong><?= esc($nama) ?></strong>! Anda login sebagai Pustakawan.
    </div>
  </div>

  <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card">
      <div class="card-body">
        <h5 class="card-title">Total Anggota</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-people"></i>
          </div>
          <div class="ps-3">
            <h6><?= (int) $total_anggota ?></h6>
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
            <h6><?= (int) $total_buku ?></h6>
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
            <h6><?= (int) $peminjaman_aktif ?></h6>
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
        <div class="d-flex gap-2 flex-wrap">
          <a href="<?= base_url('dashboard/buku') ?>" class="btn btn-primary">
            <i class="bi bi-book me-1"></i> Kelola Buku
          </a>
          <a href="<?= base_url('dashboard/anggota-list') ?>" class="btn btn-success">
            <i class="bi bi-people me-1"></i> Kelola Anggota
          </a>
          <a href="<?= base_url('dashboard/peminjaman') ?>" class="btn btn-info text-dark">
            <i class="bi bi-journal-check me-1"></i> Kelola Peminjaman
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>