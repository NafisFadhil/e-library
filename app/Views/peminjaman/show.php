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
