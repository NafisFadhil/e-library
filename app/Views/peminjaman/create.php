<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Buat Peminjaman Baru<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Buat Peminjaman Baru<?= $this->endSection() ?>

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
        <h5 class="card-title">Form Peminjaman Buku</h5>

        <form action="<?= base_url('dashboard/peminjaman/store') ?>" method="post">
          <?= csrf_field() ?>

          <div class="row g-3">
            <!-- Pilih Anggota -->
            <div class="col-12">
              <label for="id_anggota" class="form-label">Anggota Peminjam <span class="text-danger">*</span></label>
              <select name="id_anggota" id="id_anggota" class="form-select" required>
                <option value="">-- Pilih Anggota --</option>
                <?php foreach ($anggota as $a): ?>
                  <option value="<?= $a['id_anggota'] ?>" <?= old('id_anggota') == $a['id_anggota'] ? 'selected' : '' ?>>
                    <?= esc($a['nama']) ?> — <?= esc($a['email']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Pilih Eksemplar -->
            <div class="col-12">
              <label class="form-label">Eksemplar Buku <span class="text-danger">*</span></label>
              <p class="text-muted small mb-2">Centang eksemplar yang ingin dipinjam. Hanya eksemplar yang tersedia yang ditampilkan.</p>
              
              <?php if (!empty($eksemplar)): ?>
                <div class="table-responsive">
                  <table class="table table-bordered table-sm align-middle">
                    <thead class="table-light">
                      <tr>
                        <th class="text-center" style="width: 50px;">Pilih</th>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Kondisi</th>
                        <th>Lokasi Rak</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($eksemplar as $e): ?>
                        <tr>
                          <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="kode_eksemplar[]" value="<?= esc($e['kode']) ?>"
                              <?php
                                $oldKodes = old('kode_eksemplar') ?? [];
                                echo in_array($e['kode'], $oldKodes) ? 'checked' : '';
                              ?>
                            >
                          </td>
                          <td><code><?= esc($e['kode']) ?></code></td>
                          <td><?= esc($e['judul_buku'] ?? '-') ?></td>
                          <td>
                            <?php
                              $kondisiBadge = match($e['kondisi']) {
                                'Bagus'        => 'bg-success',
                                'Rusak Ringan' => 'bg-warning text-dark',
                                'Rusak Berat'  => 'bg-danger',
                                default        => 'bg-secondary',
                              };
                            ?>
                            <span class="badge <?= $kondisiBadge ?>"><?= esc($e['kondisi']) ?></span>
                          </td>
                          <td><?= esc($e['lokasi_rak']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php else: ?>
                <div class="alert alert-warning mb-0">
                  <i class="bi bi-exclamation-triangle me-1"></i>
                  Tidak ada eksemplar yang tersedia saat ini.
                </div>
              <?php endif; ?>
            </div>

            <div class="col-12">
              <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Info:</strong> Durasi peminjaman otomatis 7 hari dari tanggal peminjaman. Denda keterlambatan: Rp 1.000 / hari.
              </div>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/peminjaman') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Peminjaman
              </button>
            </div>

          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
