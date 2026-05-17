<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Tambah Eksemplar - <?= esc($buku['judul']) ?><?= $this->endSection() ?>

<?= $this->section('page_title') ?>Tambah Eksemplar Buku<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-8 col-12 mx-auto">
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Context Book Info -->
    <div class="card mb-3 border-start border-info border-3 bg-light">
      <div class="card-body py-2">
        <span class="text-muted small fw-semibold">Menambahkan eksemplar untuk buku:</span>
        <h6 class="m-0 fw-bold text-dark mt-1"><?= esc($buku['judul']) ?> <span class="font-monospace text-muted small">(ISBN: <?= esc($buku['isbn']) ?>)</span></h6>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Form Tambah Eksemplar</h5>

        <form action="<?= base_url('dashboard/buku/' . esc($buku['isbn']) . '/eksemplar/store') ?>" method="post">
          <?= csrf_field() ?>

          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label for="kode" class="form-label">Kode Eksemplar <span class="text-danger">*</span></label>
              <input type="text" name="kode" id="kode" class="form-control font-monospace" placeholder="Contoh: EKS-978602-001" value="<?= old('kode') ?>" required>
              <small class="text-muted">Gunakan kode unik untuk salinan buku ini.</small>
            </div>

            <div class="col-md-6 col-12">
              <label for="lokasi_rak" class="form-label">Lokasi Rak <span class="text-danger">*</span></label>
              <input type="text" name="lokasi_rak" id="lokasi_rak" class="form-control" placeholder="Contoh: Rak A-3, Lemari 2" value="<?= old('lokasi_rak') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="kondisi" class="form-label">Kondisi Buku <span class="text-danger">*</span></label>
              <select name="kondisi" id="kondisi" class="form-select" required>
                <option value="" disabled selected>-- Pilih Kondisi --</option>
                <option value="Bagus" <?= old('kondisi') === 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                <option value="Rusak Ringan" <?= old('kondisi') === 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                <option value="Rusak Berat" <?= old('kondisi') === 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
              </select>
            </div>

            <div class="col-md-6 col-12">
              <label for="ketersediaan" class="form-label">Status Ketersediaan <span class="text-danger">*</span></label>
              <select name="ketersediaan" id="ketersediaan" class="form-select" required>
                <option value="Tersedia" <?= old('ketersediaan', 'Tersedia') === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="Dipinjam" <?= old('ketersediaan') === 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                <option value="Tidak Tersedia" <?= old('ketersediaan') === 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
              </select>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/buku/' . esc($buku['isbn']) . '/eksemplar') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Eksemplar
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
