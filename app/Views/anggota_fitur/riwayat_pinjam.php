<?php
/** @var array $peminjaman */
/** @var \CodeIgniter\Pager\Pager $pager */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Riwayat Peminjaman<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Riwayat Peminjaman<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
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
        <h5 class="card-title">Riwayat Peminjaman Anda</h5>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th class="text-center" style="width: 60px;">ID</th>
                <th class="text-center">Tgl Pengajuan</th>
                <th class="text-center">Tgl Pinjam</th>
                <th class="text-center">Jatuh Tempo</th>
                <th class="text-center">Status</th>
                <th>Pustakawan</th>
                <th class="text-center" style="width: 100px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($peminjaman) && count($peminjaman) > 0): ?>
                <?php foreach ($peminjaman as $item): ?>
                  <tr>
                    <td class="text-center"><code class="text-dark">#<?= esc($item['id_peminjaman']) ?></code></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal_pengajuan'])) ?></td>
                    <td class="text-center"><?= $item['tanggal_pinjam'] ? date('d/m/Y', strtotime($item['tanggal_pinjam'])) : '-' ?></td>
                    <td class="text-center">
                      <?php if ($item['tanggal_jatuh_tempo']): ?>
                        <?php
                          $jatuhTempo = strtotime($item['tanggal_jatuh_tempo']);
                          $isOverdue = ($item['status_peminjaman'] === 'Dipinjam' && $jatuhTempo < time());
                        ?>
                        <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                          <?= date('d/m/Y', $jatuhTempo) ?>
                          <?= $isOverdue ? '<i class="bi bi-exclamation-triangle-fill"></i>' : '' ?>
                        </span>
                      <?php else: ?>
                        -
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php
                        $badgeClass = match($item['status_peminjaman']) {
                          'Diajukan' => 'bg-warning text-dark',
                          'Dipinjam' => 'bg-primary',
                          'Selesai'  => 'bg-success',
                          'Ditolak'  => 'bg-danger',
                          default    => 'bg-secondary',
                        };
                      ?>
                      <span class="badge <?= $badgeClass ?>"><?= esc($item['status_peminjaman']) ?></span>
                    </td>
                    <td><?= esc($item['nama_pustakawan'] ?? '-') ?></td>
                    <td class="text-center">
                      <a href="<?= base_url('dashboard/riwayat-pinjam/detail/' . $item['id_peminjaman']) ?>" class="btn btn-info btn-sm text-dark" title="Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">
                    <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">Anda belum pernah melakukan peminjaman.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if (isset($pager)): ?>
          <div class="d-flex justify-content-end mt-3">
            <?= $pager->links('peminjaman', 'default_full') ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
