<?php
/** @var array $peminjaman */
/** @var array $detail */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Detail Peminjaman<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Detail Peminjaman #<?= esc($peminjaman['id_peminjaman']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-8 col-12 mx-auto">

    <!-- Info Peminjaman -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Informasi Peminjaman</h5>
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless table-sm mb-0">
              <tr>
                <td class="text-muted" style="width: 140px;">ID Peminjaman</td>
                <td><strong>#<?= esc($peminjaman['id_peminjaman']) ?></strong></td>
              </tr>
              <tr>
                <td class="text-muted">Pustakawan</td>
                <td><?= esc($peminjaman['nama_pustakawan'] ?? '-') ?></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless table-sm mb-0">
              <tr>
                <td class="text-muted" style="width: 140px;">Tgl Pengajuan</td>
                <td><?= date('d/m/Y H:i', strtotime($peminjaman['tanggal_pengajuan'])) ?></td>
              </tr>
              <tr>
                <td class="text-muted">Tgl Pinjam</td>
                <td><?= $peminjaman['tanggal_pinjam'] ? date('d/m/Y H:i', strtotime($peminjaman['tanggal_pinjam'])) : '<span class="text-muted">-</span>' ?></td>
              </tr>
              <tr>
                <td class="text-muted">Jatuh Tempo</td>
                <td>
                  <?php if ($peminjaman['tanggal_jatuh_tempo']): ?>
                    <?php $isOverdue = ($peminjaman['status_peminjaman'] === 'Dipinjam' && strtotime($peminjaman['tanggal_jatuh_tempo']) < time()); ?>
                    <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                      <?= date('d/m/Y H:i', strtotime($peminjaman['tanggal_jatuh_tempo'])) ?>
                      <?= $isOverdue ? ' <i class="bi bi-exclamation-triangle-fill"></i> TERLAMBAT' : '' ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td class="text-muted">Status</td>
                <td>
                  <?php
                    $badgeClass = match($peminjaman['status_peminjaman']) {
                      'Diajukan' => 'bg-warning text-dark',
                      'Dipinjam' => 'bg-primary',
                      'Selesai'  => 'bg-success',
                      'Ditolak'  => 'bg-danger',
                      default    => 'bg-secondary',
                    };
                  ?>
                  <span class="badge <?= $badgeClass ?> fs-6"><?= esc($peminjaman['status_peminjaman']) ?></span>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail Buku -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Buku yang Dipinjam</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 60px;" class="text-center">No</th>
                <th>Kode Eksemplar</th>
                <th>Judul Buku</th>
                <th class="text-center">Tgl Kembali</th>
                <th class="text-center">Denda</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; $totalDenda = 0; ?>
              <?php foreach ($detail as $d): ?>
                <?php $totalDenda += $d['denda']; ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><code class="text-dark"><?= esc($d['kode_eksemplar']) ?></code></td>
                  <td><strong><?= esc($d['judul_buku'] ?? '-') ?></strong></td>
                  <td class="text-center">
                    <?= $d['tanggal_kembali'] ? date('d/m/Y', strtotime($d['tanggal_kembali'])) : '<span class="badge bg-secondary">Belum</span>' ?>
                  </td>
                  <td class="text-center">
                    <?php if ($d['denda'] > 0): ?>
                      <span class="text-danger fw-bold">Rp <?= number_format($d['denda'], 0, ',', '.') ?></span>
                    <?php else: ?>
                      <span class="text-success">Rp 0</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if ($totalDenda > 0): ?>
                <tr class="table-danger">
                  <td colspan="4" class="text-end fw-bold">Total Denda:</td>
                  <td class="text-center fw-bold text-danger">Rp <?= number_format($totalDenda, 0, ',', '.') ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Status Pembayaran Denda -->
    <?php if ($totalDenda > 0): ?>
      <?php if (empty($pembayaran)): ?>
        <div class="card mb-3 border-warning">
          <div class="card-body">
            <h5 class="card-title text-warning"><i class="bi bi-exclamation-triangle-fill"></i> Tagihan Denda</h5>
            <p class="mb-0">Tagihan denda belum diterbitkan oleh pustakawan. Harap hubungi admin perpustakaan untuk menerbitkan denda sebesar <strong>Rp <?= number_format($totalDenda, 0, ',', '.') ?></strong>.</p>
          </div>
        </div>
      <?php elseif ($pembayaran['status_pembayaran'] === 'Menunggu'): ?>
        <?php if ($pembayaran['metode_pembayaran'] === 'Tripay'): ?>
          <div class="card mb-3 border-warning">
            <div class="card-body">
              <h5 class="card-title text-warning"><i class="bi bi-wallet2"></i> Transaksi Tripay Aktif</h5>
              <p>Silakan selesaikan pembayaran denda online Anda via Tripay.</p>
              <?php
                $txRef = $pembayaran['transaction_reference'] ?? '';
                $payCode = '';
                $payName = 'Tripay';
                
                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $txRef, $matches)) {
                    $payCode = trim($matches[1]);
                    $payName = trim($matches[2]);
                } else {
                    $payCode = $txRef;
                }
              ?>
              <div class="mb-3">
                <strong>Metode Pembayaran:</strong> <?= esc($payName) ?> (Tripay)<br>
                <?php if (!empty($payCode)): ?>
                  <strong>Nomor Virtual Account / Kode Bayar:</strong> <code class="fs-5 text-primary"><?= esc($payCode) ?></code><br>
                <?php endif; ?>
                <strong>Jumlah Tagihan:</strong> <strong class="text-danger">Rp <?= number_format($pembayaran['jumlah_denda'], 0, ',', '.') ?></strong>
              </div>
              <div class="alert alert-light border">
                <?php if (!empty($payCode)): ?>
                  Silakan lakukan pembayaran ke nomor Virtual Account / Kode Bayar di atas. Jika Anda ingin mengganti metode pembayaran atau mengunggah bukti pembayaran manual, silakan klik tombol di bawah.
                <?php else: ?>
                  Silakan klik tombol <strong>Cara Pembayaran</strong> di bawah untuk menampilkan kode QR / petunjuk pembayaran. Jika Anda ingin mengganti metode pembayaran atau mengunggah bukti pembayaran manual, silakan klik tombol di bawah.
                <?php endif; ?>
              </div>
              <div class="d-flex gap-2">
                <a href="https://tripay.co.id/checkout/<?= esc($pembayaran['tripay_reference']) ?>" target="_blank" class="btn btn-success">
                  <i class="bi bi-box-arrow-up-right me-1"></i> Cara Pembayaran
                </a>
                <a href="<?= base_url('dashboard/riwayat-pinjam/bayar/' . $peminjaman['id_peminjaman']) ?>" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left-right me-1"></i> Ganti Metode / Upload Manual
                </a>
              </div>
            </div>
          </div>
        <?php elseif (!empty($pembayaran['bukti_bayar'])): ?>
          <div class="card mb-3 border-warning">
            <div class="card-body">
              <h5 class="card-title text-warning"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi</h5>
              <p>Bukti pembayaran manual telah diunggah. Menunggu konfirmasi verifikasi dari pustakawan.</p>
              <div class="mb-3">
                <strong>Jumlah Denda:</strong> Rp <?= number_format($pembayaran['jumlah_denda'], 0, ',', '.') ?>
              </div>
              <div class="mb-3">
                <strong>Bukti Pembayaran:</strong><br>
                <a href="<?= base_url($pembayaran['bukti_bayar']) ?>" target="_blank">
                  <img src="<?= base_url($pembayaran['bukti_bayar']) ?>" alt="Bukti Pembayaran" class="img-thumbnail mt-2" style="max-width: 200px;">
                </a>
              </div>
              <a href="<?= base_url('dashboard/riwayat-pinjam/bayar/' . $peminjaman['id_peminjaman']) ?>" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-upload me-1"></i> Unggah Ulang Bukti
              </a>
            </div>
          </div>
        <?php else: ?>
          <div class="card mb-3 border-danger">
            <div class="card-body">
              <h5 class="card-title text-danger"><i class="bi bi-exclamation-circle-fill"></i> Pembayaran Denda</h5>
              <p>Anda memiliki denda keterlambatan sebesar <strong class="text-danger">Rp <?= number_format($pembayaran['jumlah_denda'], 0, ',', '.') ?></strong>.</p>
              <div class="alert alert-info py-2 mb-3">
                <strong>Informasi Rekening & Catatan Admin:</strong><br>
                <?= nl2br(esc($pembayaran['catatan_admin'])) ?>
              </div>
              <a href="<?= base_url('dashboard/riwayat-pinjam/bayar/' . $peminjaman['id_peminjaman']) ?>" class="btn btn-primary">
                <i class="bi bi-cash-coin me-1"></i> Bayar Sekarang
              </a>
            </div>
          </div>
        <?php endif; ?>
      <?php elseif ($pembayaran['status_pembayaran'] === 'Lunas'): ?>
        <div class="card mb-3 border-success">
          <div class="card-body">
            <h5 class="card-title text-success"><i class="bi bi-check-circle-fill"></i> Denda Lunas</h5>
            <p>Pembayaran denda sebesar <strong class="text-success">Rp <?= number_format($pembayaran['jumlah_denda'], 0, ',', '.') ?></strong> telah dikonfirmasi lunas.</p>
            <div class="mb-0 text-muted">
              <strong>Metode Pembayaran:</strong> <?= esc($pembayaran['metode_pembayaran']) ?><br>
              <strong>Waktu Pembayaran:</strong> <?= date('d/m/Y H:i', strtotime($pembayaran['waktu_pembayaran'])) ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Back Button -->
    <a href="<?= base_url('dashboard/riwayat-pinjam') ?>" class="btn btn-secondary">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
    </a>

  </div>
</div>
<?= $this->endSection() ?>
