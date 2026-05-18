<?php
/** @var array $peminjaman */
/** @var \CodeIgniter\Pager\Pager $pager */
/** @var string|null $keyword */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Daftar Peminjaman<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Daftar Peminjaman<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Success & Error Alert -->
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

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center my-3">
          <h5 class="card-title m-0">Data Peminjaman Perpustakaan</h5>
          <a href="<?= base_url('dashboard/peminjaman/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Buat Peminjaman
          </a>
        </div>

        <!-- Search Bar -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-6 col-lg-5">
            <form action="<?= base_url('dashboard/peminjaman') ?>" method="get">
              <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white text-muted border-end-0">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Cari ID, nama anggota, pustakawan, status..." value="<?= esc($keyword ?? '') ?>">
                <?php if (!empty($keyword)): ?>
                  <a href="<?= base_url('dashboard/peminjaman') ?>" class="btn btn-outline-secondary border-start-0 border-end-0 d-flex align-items-center justify-content-center" title="Reset Pencarian">
                    <i class="bi bi-x-lg"></i>
                  </a>
                <?php endif; ?>
                <button class="btn btn-primary px-4" type="submit">Cari</button>
              </div>
            </form>
          </div>
          <div class="col-md-6 col-lg-7 text-md-end">
            <?php if (!empty($keyword)): ?>
              <span class="text-muted small">
                Menampilkan hasil pencarian untuk: <strong class="text-primary">"<?= esc($keyword) ?>"</strong>
              </span>
            <?php endif; ?>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th class="text-center" style="width: 60px;">ID</th>
                <th>Anggota</th>
                <th>Pustakawan</th>
                <th class="text-center">Tgl Pengajuan</th>
                <th class="text-center">Tgl Pinjam</th>
                <th class="text-center">Jatuh Tempo</th>
                <th class="text-center">Status</th>
                <th class="text-center" style="width: 220px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($peminjaman) && count($peminjaman) > 0): ?>
                <?php foreach ($peminjaman as $item): ?>
                  <tr>
                    <td class="text-center"><code class="text-dark">#<?= esc($item['id_peminjaman']) ?></code></td>
                    <td><strong><?= esc($item['nama_anggota'] ?? '-') ?></strong></td>
                    <td><?= esc($item['nama_pustakawan'] ?? '-') ?></td>
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
                    <td class="text-center">
                      <a href="<?= base_url('dashboard/peminjaman/show/' . $item['id_peminjaman']) ?>" class="btn btn-info btn-sm text-dark me-1" title="Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="<?= base_url('dashboard/peminjaman/edit/' . $item['id_peminjaman']) ?>" class="btn btn-warning btn-sm me-1" title="Edit Status">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="<?= base_url('dashboard/peminjaman/delete/' . $item['id_peminjaman']) ?>" 
                         onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')" 
                         class="btn btn-danger btn-sm" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0"><?= !empty($keyword) ? 'Tidak ditemukan data peminjaman dengan kata kunci tersebut.' : 'Belum ada data peminjaman.' ?></p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination Links -->
        <?php if (isset($pager)): ?>
          <div class="d-flex justify-content-end mt-3">
            <?= $pager->only(['keyword'])->links('peminjaman', 'default_full') ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
