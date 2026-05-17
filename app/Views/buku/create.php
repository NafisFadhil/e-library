<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Tambah Buku Baru<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Tambah Buku Baru<?= $this->endSection() ?>

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
        <h5 class="card-title">Form Tambah Buku</h5>

        <form action="<?= base_url('dashboard/buku/store') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>

          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label for="isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
              <input type="text" name="isbn" id="isbn" class="form-control" placeholder="Contoh: 9786020331607" value="<?= old('isbn') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
              <input type="text" name="judul" id="judul" class="form-control" placeholder="Contoh: Laskar Pelangi" value="<?= old('judul') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
              <input type="text" name="kategori" id="kategori" class="form-control" placeholder="Contoh: Novel, Sains, Sejarah" value="<?= old('kategori') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
              <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" placeholder="Contoh: 2018" value="<?= old('tahun_terbit') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
              <input type="text" name="penulis" id="penulis" class="form-control" placeholder="Contoh: Andrea Hirata" value="<?= old('penulis') ?>" required>
            </div>

            <div class="col-md-6 col-12">
              <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
              <input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="Contoh: Bentang Pustaka" value="<?= old('penerbit') ?>" required>
            </div>

            <div class="col-12 border-top pt-3 mt-4">
              <label class="form-label d-block fw-bold">Cover Buku (Opsional)</label>
              
              <div class="mb-3">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="cover_option" id="coverUrlOpt" value="url" checked>
                  <label class="form-check-label" for="coverUrlOpt">Opsi 1: URL Gambar Eksternal</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="cover_option" id="coverFileOpt" value="file">
                  <label class="form-check-label" for="coverFileOpt">Opsi 2: Upload File Gambar</label>
                </div>
              </div>

              <!-- Input URL Gambar -->
              <div class="mb-3" id="urlCoverGroup">
                <label for="url_cover" class="form-label">URL Cover Buku</label>
                <input type="url" name="url_cover" id="url_cover" class="form-control" placeholder="https://example.com/cover.jpg" value="<?= old('url_cover') ?>">
              </div>

              <!-- Input Upload File -->
              <div class="mb-3 d-none" id="fileCoverGroup">
                <label for="cover_file" class="form-label">Pilih File Cover (JPG/PNG/WEBP, Maks 2MB)</label>
                <input type="file" name="cover_file" id="cover_file" class="form-control" accept="image/*">
              </div>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/buku') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Buku
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

  // Set initial state from old flash data
  <?php if (old('cover_option') === 'file'): ?>
    coverFileOpt.checked = true;
    toggleCoverInputs();
  <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
