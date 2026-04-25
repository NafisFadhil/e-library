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
