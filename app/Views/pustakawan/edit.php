<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Edit Data Pustakawan<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Edit Data Pustakawan<?= $this->endSection() ?>

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

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="card-title">Form Edit Pustakawan</h5>

        <form action="<?= base_url('dashboard/pustakawan-list/update/' . $pustakawan['id_pustakawan']) ?>" method="post">
          <?= csrf_field() ?>

          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Budi Santoso" value="<?= old('nama', $pustakawan['nama']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" id="username" class="form-control" placeholder="Contoh: budi123" value="<?= old('username', $pustakawan['username']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Contoh: budi@email.com" value="<?= old('email', $pustakawan['email']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="is_admin" class="form-label">Role <span class="text-danger">*</span></label>
              <select name="is_admin" id="is_admin" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                <option value="1" <?= old('is_admin', $pustakawan['is_admin']) == '1' ? 'selected' : '' ?>>Admin</option>
                <option value="0" <?= old('is_admin', $pustakawan['is_admin']) == '0' ? 'selected' : '' ?>>Pustakawan</option>
              </select>
            </div>

            <div class="col-md-6 col-12">
              <label for="password" class="form-label">Password Baru <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter">
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/pustakawan-list') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Perbarui Pustakawan
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
