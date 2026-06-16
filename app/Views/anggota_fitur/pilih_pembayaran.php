<?php
/** @var array $peminjaman */
/** @var array $pembayaran */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Pilih Metode Pembayaran denda<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Pembayaran Denda #<?= esc($peminjaman['id_peminjaman']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-8 col-12 mx-auto">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Ringkasan Tagihan</h5>
        <table class="table table-borderless table-sm mb-0">
          <tr>
            <td class="text-muted" style="width: 180px;">ID Peminjaman</td>
            <td><strong>#<?= esc($peminjaman['id_peminjaman']) ?></strong></td>
          </tr>
          <tr>
            <td class="text-muted">Jumlah Denda</td>
            <td><strong class="text-danger fs-5">Rp <?= number_format($pembayaran['jumlah_denda'], 0, ',', '.') ?></strong></td>
          </tr>
        </table>
      </div>
    </div>

    <div class="row">
      <!-- Opsi Pembayaran Online (Tripay) -->
      <div class="col-md-6 col-12 mb-3">
        <div class="card h-100 border-primary">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title text-primary"><i class="bi bi-credit-card-2-front-fill me-1"></i> Bayar Online (Tripay)</h5>
            <p class="text-muted mb-3">Pilih saluran pembayaran otomatis online menggunakan payment gateway Tripay:</p>
            
            <form action="<?= base_url('dashboard/riwayat-pinjam/bayar-tripay/' . $peminjaman['id_peminjaman']) ?>" method="post" class="mt-auto">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label for="payment_method" class="form-label fw-bold">Pilih Saluran Pembayaran</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                  <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                  <?php 
                    // Grouping channels
                    $grouped = [];
                    foreach ($channels as $chan) {
                        if (isset($chan['active']) && !$chan['active']) continue;
                        $groupName = $chan['group'] ?? 'Lainnya';
                        $grouped[$groupName][] = $chan;
                    }
                  ?>
                  <?php foreach ($grouped as $group => $list): ?>
                    <optgroup label="<?= esc($group) ?>">
                      <?php foreach ($list as $c): ?>
                        <option value="<?= esc($c['code']) ?>"><?= esc($c['name']) ?></option>
                      <?php endforeach; ?>
                    </optgroup>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-wallet2 me-1"></i> Lanjutkan Pembayaran
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Opsi Pembayaran Manual (Transfer) -->
      <div class="col-md-6 col-12 mb-3">
        <div class="card h-100 border-secondary">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title text-secondary"><i class="bi bi-bank me-1"></i> Transfer Manual</h5>
            <p class="text-muted">Lakukan transfer ke rekening bank perpustakaan dan unggah bukti transfer di bawah ini.</p>
            <div class="alert alert-light border py-2 mb-3">
              <strong>Info Rekening:</strong><br>
              <?= nl2br(esc($pembayaran['catatan_admin'])) ?>
            </div>
            
            <form action="<?= base_url('dashboard/riwayat-pinjam/upload-bukti/' . $peminjaman['id_peminjaman']) ?>" method="post" enctype="multipart/form-data" class="mt-auto">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label for="bukti_bayar" class="form-label fw-bold">Upload Bukti Transfer</label>
                <input class="form-control" type="file" id="bukti_bayar" name="bukti_bayar" accept="image/*" required>
                <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
              </div>
              <button type="submit" class="btn btn-secondary w-100">
                <i class="bi bi-upload me-1"></i> Unggah Bukti
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Back Button -->
    <div class="mt-2 text-center">
      <a href="<?= base_url('dashboard/riwayat-pinjam/detail/' . $peminjaman['id_peminjaman']) ?>" class="btn btn-link text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Peminjaman
      </a>
    </div>

  </div>
</div>
<?= $this->endSection() ?>
