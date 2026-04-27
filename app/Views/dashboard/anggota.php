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