<?php
/** @var array $peminjaman */
/** @var array $detail */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Edit Peminjaman<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Edit Peminjaman #<?= esc($peminjaman['id_peminjaman']) ?><?= $this->endSection() ?>

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

    <!-- Info Peminjaman -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Informasi Peminjaman</h5>
        <div class="row">
          <div class="col-md-6">
            <p class="mb-1"><strong>Anggota:</strong> <?= esc($peminjaman['nama_anggota'] ?? '-') ?></p>
            <p class="mb-1"><strong>Pustakawan:</strong> <?= esc($peminjaman['nama_pustakawan'] ?? '-') ?></p>
          </div>
          <div class="col-md-6">
            <p class="mb-1"><strong>Tgl Pengajuan:</strong> <?= date('d/m/Y H:i', strtotime($peminjaman['tanggal_pengajuan'])) ?></p>
            <p class="mb-1"><strong>Tgl Pinjam:</strong> <?= $peminjaman['tanggal_pinjam'] ? date('d/m/Y H:i', strtotime($peminjaman['tanggal_pinjam'])) : '-' ?></p>
            <p class="mb-1"><strong>Jatuh Tempo:</strong> <?= $peminjaman['tanggal_jatuh_tempo'] ? date('d/m/Y H:i', strtotime($peminjaman['tanggal_jatuh_tempo'])) : '-' ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Daftar Buku Dipinjam -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Buku yang Dipinjam</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Kode Eksemplar</th>
                <th>Judul Buku</th>
                <th class="text-center">Tgl Kembali</th>
                <th class="text-center">Denda</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($detail as $d): ?>
                <tr>
                  <td><code><?= esc($d['kode_eksemplar']) ?></code></td>
                  <td><?= esc($d['judul_buku'] ?? '-') ?></td>
                  <td class="text-center"><?= $d['tanggal_kembali'] ? date('d/m/Y', strtotime($d['tanggal_kembali'])) : '<span class="text-muted">Belum dikembalikan</span>' ?></td>
                  <td class="text-center">
                    <?php if ($d['denda'] > 0): ?>
                      <span class="text-danger fw-bold">Rp <?= number_format($d['denda'], 0, ',', '.') ?></span>
                    <?php else: ?>
                      <span class="text-success">Rp 0</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Form Ubah Status -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Ubah Status Peminjaman</h5>

        <form action="<?= base_url('dashboard/peminjaman/update/' . $peminjaman['id_peminjaman']) ?>" method="post">
          <?= csrf_field() ?>

          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label for="status_peminjaman" class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status_peminjaman" id="status_peminjaman" class="form-select" required>
                <?php
                  $statuses = ['Diajukan', 'Dipinjam', 'Selesai', 'Ditolak'];
                  foreach ($statuses as $s):
                ?>
                  <option value="<?= $s ?>" <?= $peminjaman['status_peminjaman'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6 col-12 d-flex align-items-end">
              <div class="alert alert-warning mb-0 w-100 py-2">
                <small>
                  <i class="bi bi-exclamation-triangle me-1"></i>
                  <strong>Selesai</strong> = eksemplar dikembalikan & denda dihitung otomatis.<br>
                  <strong>Ditolak</strong> = eksemplar dikembalikan ke stok.
                </small>
              </div>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-between">
              <a href="<?= base_url('dashboard/peminjaman') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Update Status
              </button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
