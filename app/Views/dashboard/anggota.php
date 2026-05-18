<?php
/** @var string $nama */
/** @var int $buku_dipinjam */
/** @var int $total_riwayat */
/** @var int $total_buku */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard Anggota<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Dashboard Anggota<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="alert alert-success">
      Selamat datang, <strong><?= esc($nama) ?></strong>! Anda login sebagai Anggota.
    </div>
  </div>

  <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card">
      <div class="card-body">
        <h5 class="card-title">Buku Dipinjam</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-book"></i>
          </div>
          <div class="ps-3">
            <h6><?= (int) $buku_dipinjam ?></h6>
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
            <h6><?= (int) $total_riwayat ?></h6>
            <span class="text-muted small">total pinjaman</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-4 col-md-6">
    <div class="card info-card customers-card">
      <div class="card-body">
        <h5 class="card-title">Koleksi Perpustakaan</h5>
        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-journals"></i>
          </div>
          <div class="ps-3">
            <h6><?= (int) $total_buku ?></h6>
            <span class="text-muted small">judul buku tersedia</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 mt-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Layanan Perpustakaan</h5>
        <div class="d-flex gap-2 flex-wrap">
          <a href="<?= base_url('dashboard/cari-buku') ?>" class="btn btn-primary">
            <i class="bi bi-search me-1"></i> Cari Buku
          </a>
          <a href="<?= base_url('dashboard/riwayat-pinjam') ?>" class="btn btn-success">
            <i class="bi bi-clock-history me-1"></i> Riwayat Pinjam
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>