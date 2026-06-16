<?php
/** @var array $peminjaman */
/** @var array $detail */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Detail Peminjaman<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Detail Peminjaman #<?= esc((string) $peminjaman['id_peminjaman']) ?><?= $this->endSection() ?>

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

    <!-- Info Peminjaman -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Informasi Peminjaman</h5>
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless table-sm mb-0">
              <tr>
                <td class="text-muted" style="width: 140px;">ID Peminjaman</td>
                <td><strong>#<?= esc((string) $peminjaman['id_peminjaman']) ?></strong></td>
              </tr>
              <tr>
                <td class="text-muted">Anggota</td>
                <td><strong><?= esc((string) ($peminjaman['nama_anggota'] ?? '-')) ?></strong></td>
              </tr>
              <tr>
                <td class="text-muted">Pustakawan</td>
                <td><?= esc((string) ($peminjaman['nama_pustakawan'] ?? '-')) ?></td>
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
                  <span class="badge <?= $badgeClass ?> fs-6"><?= esc((string) $peminjaman['status_peminjaman']) ?></span>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail Buku yang Dipinjam -->
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
                <th>Kondisi</th>
                <th>Lokasi Rak</th>
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
                  <td><code class="text-dark"><?= esc((string) $d['kode_eksemplar']) ?></code></td>
                  <td><strong><?= esc((string) ($d['judul_buku'] ?? '-')) ?></strong></td>
                  <td>
                    <?php
                      $kondisiBadge = match($d['kondisi'] ?? '') {
                        'Bagus'        => 'bg-success',
                        'Rusak Ringan' => 'bg-warning text-dark',
                        'Rusak Berat'  => 'bg-danger',
                        default        => 'bg-secondary',
                      };
                    ?>
                    <span class="badge <?= $kondisiBadge ?>"><?= esc((string) ($d['kondisi'] ?? '-')) ?></span>
                  </td>
                  <td><?= esc((string) ($d['lokasi_rak'] ?? '-')) ?></td>
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
                  <td colspan="6" class="text-end fw-bold">Total Denda:</td>
                  <td class="text-center fw-bold text-danger">Rp <?= number_format($totalDenda, 0, ',', '.') ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Pengelolaan Denda Peminjaman -->
    <?php if ($totalDenda > 0): ?>
      <?php if (empty($pembayaran)): ?>
        <div class="card mb-3 border-danger">
          <div class="card-body">
            <h5 class="card-title text-danger"><i class="bi bi-exclamation-circle-fill"></i> Terbitkan Tagihan Denda</h5>
            <p>Peminjaman ini memiliki denda terhitung sebesar <strong>Rp <?= number_format($totalDenda, 0, ',', '.') ?></strong> yang belum diterbitkan.</p>
            
            <form action="<?= base_url('dashboard/peminjaman/terbitkan-denda/' . $peminjaman['id_peminjaman']) ?>" method="post">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label for="catatan_admin" class="form-label fw-bold">Informasi Rekening & Instruksi Pembayaran</label>
                <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="3" required placeholder="Contoh: Silakan transfer ke Bank Mandiri 123456789 a.n. Perpustakaan Pintar, lalu unggah bukti pembayaran di sini."></textarea>
              </div>
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-send me-1"></i> Terbitkan Tagihan Denda
              </button>
            </form>
          </div>
        </div>
      <?php else: ?>
        <div class="card mb-3 border-secondary">
          <div class="card-body">
            <h5 class="card-title text-secondary"><i class="bi bi-cash-coin"></i> Status Tagihan & Pembayaran Denda</h5>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                  <tr>
                    <td class="text-muted" style="width: 160px;">Jumlah Denda</td>
                    <td><strong class="text-danger">Rp <?= number_format($pembayaran['jumlah_denda'], 0, ',', '.') ?></strong></td>
                  </tr>
                  <tr>
                    <td class="text-muted">Metode Pembayaran</td>
                    <td><span class="badge bg-info"><?= esc($pembayaran['metode_pembayaran']) ?></span></td>
                  </tr>
                  <tr>
                    <td class="text-muted">Status Pembayaran</td>
                    <td>
                      <?php $statusBadge = $pembayaran['status_pembayaran'] === 'Lunas' ? 'bg-success' : 'bg-warning text-dark'; ?>
                      <span class="badge <?= $statusBadge ?>"><?= esc($pembayaran['status_pembayaran']) ?></span>
                    </td>
                  </tr>
                </table>
              </div>
              
              <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                  <?php if ($pembayaran['metode_pembayaran'] === 'Tripay'): ?>
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
                    <tr>
                      <td class="text-muted" style="width: 160px;">Tripay Channel</td>
                      <td><span class="badge bg-secondary"><?= esc($payName) ?></span></td>
                    </tr>
                    <tr>
                      <td class="text-muted" style="width: 160px;">Tripay Reference</td>
                      <td><code><?= esc($pembayaran['tripay_reference']) ?></code></td>
                    </tr>
                    <?php if (!empty($payCode)): ?>
                      <tr>
                        <td class="text-muted">Payment Code / VA</td>
                        <td><code><?= esc($payCode) ?></code></td>
                      </tr>
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php if ($pembayaran['status_pembayaran'] === 'Lunas'): ?>
                    <tr>
                      <td class="text-muted" style="width: 160px;">Waktu Konfirmasi</td>
                      <td><?= date('d/m/Y H:i', strtotime($pembayaran['waktu_pembayaran'])) ?></td>
                    </tr>
                  <?php endif; ?>
                </table>
              </div>
            </div>

            <div class="alert alert-info py-2 mb-3">
              <strong>Catatan Admin / Informasi Rekening:</strong><br>
              <?= nl2br(esc($pembayaran['catatan_admin'])) ?>
            </div>

            <?php if ($pembayaran['status_pembayaran'] === 'Menunggu' && !empty($pembayaran['bukti_bayar'])): ?>
              <div class="border rounded p-3 mb-3 bg-light">
                <h6 class="fw-bold"><i class="bi bi-file-image"></i> Bukti Transfer Anggota</h6>
                <div class="mb-3">
                  <a href="<?= base_url($pembayaran['bukti_bayar']) ?>" target="_blank">
                    <img src="<?= base_url($pembayaran['bukti_bayar']) ?>" alt="Bukti Pembayaran" class="img-thumbnail" style="max-width: 300px;">
                  </a>
                </div>
                <form action="<?= base_url('dashboard/peminjaman/konfirmasi-bayar/' . $peminjaman['id_peminjaman']) ?>" method="post">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id_pembayaran" value="<?= esc($pembayaran['id_pembayaran']) ?>">
                  <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle-fill me-1"></i> Konfirmasi Pembayaran Lunas
                  </button>
                </form>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between">
      <a href="<?= base_url('dashboard/peminjaman') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
      </a>
      <a href="<?= base_url('dashboard/peminjaman/edit/' . $peminjaman['id_peminjaman']) ?>" class="btn btn-warning">
        <i class="bi bi-pencil me-1"></i> Ubah Status
      </a>
    </div>

  </div>
</div>
<?= $this->endSection() ?>
