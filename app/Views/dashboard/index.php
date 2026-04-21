<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Dashboard Utama<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Selamat Datang di E-Library</h5>
        <p>Ini adalah konten utama halaman dashboard menggunakan layouting NiceAdmin.</p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
