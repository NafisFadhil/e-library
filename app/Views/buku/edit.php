<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Edit Buku<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Edit Buku<?= $this->endSection() ?>

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
        <h5 class="card-title">Form Edit Buku</h5>

        <form action="<?= base_url('dashboard/buku/update/' . esc($buku['isbn'])) ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>

          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label for="isbn" class="form-label">ISBN (Tidak dapat diubah)</label>
              <input type="text" name="isbn" id="isbn" class="form-control bg-light" value="<?= esc($buku['isbn']) ?>" readonly>
            </div>

            <div class="col-md-6 col-12">
              <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
              <input type="text" name="judul" id="judul" class="form-control" placeholder="Contoh: Laskar Pelangi" value="<?= old('judul', $buku['judul']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
              <input type="text" name="kategori" id="kategori" class="form-control" placeholder="Contoh: Novel, Sains, Sejarah" value="<?= old('kategori', $buku['kategori']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
              <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" placeholder="Contoh: 2018" value="<?= old('tahun_terbit', $buku['tahun_terbit']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
              <input type="text" name="penulis" id="penulis" class="form-control" placeholder="Contoh: Andrea Hirata" value="<?= old('penulis', $buku['penulis']) ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
              <input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="Contoh: Bentang Pustaka" value="<?= old('penerbit', $buku['penerbit']) ?>" required>
            </div>

            <div class="col-12 border-top pt-3 mt-4">
              <label class="form-label d-block fw-bold">Cover Buku</label>

              <!-- Preview Cover Saat Ini -->
              <div class="mb-3">
                <span class="text-muted d-block small mb-1">Cover Saat Ini:</span>
                <?php if (!empty($buku['url_cover'])): ?>
                  <div class="d-flex align-items-start gap-3">
                    <img src="<?= esc($buku['url_cover']) ?>" alt="Cover Saat Ini" class="img-thumbnail" style="max-height: 120px; max-width: 90px; object-fit: cover;">
                    <div>
                      <span class="badge bg-success mb-2">Terpasang</span>
                      <small class="d-block text-truncate text-muted" style="max-width: 250px;"><?= esc($buku['url_cover']) ?></small>
                    </div>
                  </div>
                <?php else: ?>
                  <span class="text-muted small italic">Belum ada cover buku.</span>
                <?php endif; ?>
              </div>

              <!-- Metode Ubah Cover -->
              <div class="mb-3 pt-2">
                <span class="text-muted d-block small mb-2 fw-semibold">Ubah Cover (Opsional):</span>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="cover_option" id="coverUrlOpt" value="url" <?= empty($buku['url_cover']) || strpos($buku['url_cover'], base_url('uploads/covers/')) === false ? 'checked' : '' ?>>
                  <label class="form-check-label" for="coverUrlOpt">Gunakan URL Gambar</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="cover_option" id="coverFileOpt" value="file" <?= !empty($buku['url_cover']) && strpos($buku['url_cover'], base_url('uploads/covers/')) !== false ? 'checked' : '' ?>>
                  <label class="form-check-label" for="coverFileOpt">Upload File Gambar</label>
                </div>
              </div>

              <!-- Input URL Gambar -->
              <div class="mb-3 <?= !empty($buku['url_cover']) && strpos($buku['url_cover'], base_url('uploads/covers/')) !== false ? 'd-none' : '' ?>" id="urlCoverGroup">
                <label for="url_cover" class="form-label">URL Cover Baru</label>
                <input type="url" name="url_cover" id="url_cover" class="form-control" placeholder="https://example.com/cover.jpg" value="<?= old('url_cover', empty($buku['url_cover']) || strpos($buku['url_cover'], base_url('uploads/covers/')) === false ? $buku['url_cover'] : '') ?>">
              </div>

              <!-- Input Upload File -->
              <div class="mb-3 <?= empty($buku['url_cover']) || strpos($buku['url_cover'], base_url('uploads/covers/')) === false ? 'd-none' : '' ?>" id="fileCoverGroup">
                <label for="cover_file" class="form-label">Pilih File Cover Baru (JPG/PNG/WEBP, Maks 2MB)</label>
                <input type="file" name="cover_file" id="cover_file" class="form-control" accept="image/*">
              </div>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/buku') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-warning text-dark">
                <i class="bi bi-pencil-square me-1"></i> Perbarui Buku
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const coverUrlOpt = document.getElementById('coverUrlOpt');
  const coverFileOpt = document.getElementById('coverFileOpt');
  const urlCoverGroup = document.getElementById('urlCoverGroup');
  const fileCoverGroup = document.getElementById('fileCoverGroup');
  const urlInput = document.getElementById('url_cover');
  const fileInput = document.getElementById('cover_file');

  function toggleCoverInputs() {
    if (coverUrlOpt.checked) {
      urlCoverGroup.classList.remove('d-none');
      fileCoverGroup.classList.add('d-none');
      fileInput.value = ''; // Reset file input
    } else {
      urlCoverGroup.classList.add('d-none');
      fileCoverGroup.classList.remove('d-none');
      urlInput.value = ''; // Reset url input
    }
  }

  coverUrlOpt.addEventListener('change', toggleCoverInputs);
  coverFileOpt.addEventListener('change', toggleCoverInputs);

  // Set initial state from old validation redirect
  <?php if (old('cover_option')): ?>
    if ("<?= old('cover_option') ?>" === 'file') {
      coverFileOpt.checked = true;
    } else {
      coverUrlOpt.checked = true;
    }
    toggleCoverInputs();
  <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
