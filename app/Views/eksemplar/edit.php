<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Edit Eksemplar - <?= esc($eksemplar['kode']) ?><?= $this->endSection() ?>

<?= $this->section('page_title') ?>Edit Eksemplar Buku<?= $this->endSection() ?>

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
        <span class="text-muted small fw-semibold">Eksemplar untuk buku:</span>
        <h6 class="m-0 fw-bold text-dark mt-1"><?= esc($buku['judul']) ?> <span class="font-monospace text-muted small">(ISBN: <?= esc($buku['isbn']) ?>)</span></h6>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Form Edit Eksemplar</h5>

        <form action="<?= base_url('dashboard/eksemplar/update/' . esc($eksemplar['kode'])) ?>" method="post">
          <?= csrf_field() ?>

          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label for="kode" class="form-label">Kode Eksemplar (Tidak dapat diubah)</label>
              <input type="text" name="kode" id="kode" class="form-control bg-light font-monospace" value="<?= esc($eksemplar['kode']) ?>" readonly>
            </div>

            <div class="col-md-6 col-12">
              <label for="lokasi_rak" class="form-label">Lokasi Rak <span class="text-danger">*</span></label>
              <input type="text" name="lokasi_rak" id="lokasi_rak" class="form-control" placeholder="Contoh: Rak A-3, Lemari 2" value="<?= old('lokasi_rak', $eksemplar['lokasi_rak']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="kondisi" class="form-label">Kondisi Buku <span class="text-danger">*</span></label>
              <select name="kondisi" id="kondisi" class="form-select" required>
                <option value="Bagus" <?= old('kondisi', $eksemplar['kondisi']) === 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                <option value="Rusak Ringan" <?= old('kondisi', $eksemplar['kondisi']) === 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                <option value="Rusak Berat" <?= old('kondisi', $eksemplar['kondisi']) === 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
              </select>
            </div>

            <div class="col-md-6 col-12">
              <label for="ketersediaan" class="form-label">Status Ketersediaan <span class="text-danger">*</span></label>
              <select name="ketersediaan" id="ketersediaan" class="form-select" required>
                <option value="Tersedia" <?= old('ketersediaan', $eksemplar['ketersediaan']) === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="Dipinjam" <?= old('ketersediaan', $eksemplar['ketersediaan']) === 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                <option value="Tidak Tersedia" <?= old('ketersediaan', $eksemplar['ketersediaan']) === 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
              </select>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/buku/' . esc($eksemplar['isbn']) . '/eksemplar') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-warning text-dark">
                <i class="bi bi-pencil-square me-1"></i> Perbarui Eksemplar
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
