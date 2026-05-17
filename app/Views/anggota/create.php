<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Tambah Anggota Baru<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Tambah Anggota Baru<?= $this->endSection() ?>

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

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Form Tambah Anggota</h5>

        <form action="<?= base_url('dashboard/anggota-list/store') ?>" method="post">
          <?= csrf_field() ?>

          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Ahmad Fauzi" value="<?= old('nama') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Contoh: ahmad@email.com" value="<?= old('email') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="no_telepon" class="form-label">No. Telepon <span class="text-danger">*</span></label>
              <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="Contoh: 081234567890" value="<?= old('no_telepon') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="Aktif" <?= old('status') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="Nonaktif" <?= old('status') === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
              </select>
            </div>

            <div class="col-md-6 col-12">
              <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="password_confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
              <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Ulangi password" required>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/anggota-list') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Anggota
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
