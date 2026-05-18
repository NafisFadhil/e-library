<?php
/** @var array $anggota */
/** @var \CodeIgniter\Pager\Pager $pager */
/** @var string|null $keyword */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Daftar Anggota<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Daftar Anggota<?= $this->endSection() ?>

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
          <h5 class="card-title m-0">Data Anggota Perpustakaan</h5>
          <a href="<?= base_url('dashboard/anggota-list/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Anggota
          </a>
        </div>

        <!-- Search Bar -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-6 col-lg-5">
            <form action="<?= base_url('dashboard/anggota-list') ?>" method="get">
              <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white text-muted border-end-0">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Cari nama, email, atau nomor telepon..." value="<?= esc($keyword ?? '') ?>">
                <?php if (!empty($keyword)): ?>
                  <a href="<?= base_url('dashboard/anggota-list') ?>" class="btn btn-outline-secondary border-start-0 border-end-0 d-flex align-items-center justify-content-center" title="Reset Pencarian">
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
                <th class="text-center" style="width: 60px;">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th class="text-center">Status</th>
                <th class="text-center" style="width: 180px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($anggota) && count($anggota) > 0): ?>
                <?php $no = ($pager->getCurrentPage('anggota') - 1) * 10 + 1; ?>
                <?php foreach ($anggota as $item): ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><strong><?= esc((string) $item['nama']) ?></strong></td>
                    <td><?= esc((string) $item['email']) ?></td>
                    <td><?= esc((string) $item['no_telepon']) ?></td>
                    <td class="text-center">
                      <?php if (strtolower($item['status']) === 'aktif'): ?>
                        <span class="badge bg-success">Aktif</span>
                      <?php else: ?>
                        <span class="badge bg-danger">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="<?= base_url('dashboard/anggota-list/edit/' . $item['id_anggota']) ?>" class="btn btn-warning btn-sm me-1" title="Edit Anggota">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="<?= base_url('dashboard/anggota-list/delete/' . $item['id_anggota']) ?>" 
                         onclick="return confirm('Yakin ingin menghapus anggota ini?')" 
                         class="btn btn-danger btn-sm" title="Hapus Anggota">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0"><?= !empty($keyword) ? 'Tidak ditemukan anggota dengan kata kunci tersebut.' : 'Belum ada data anggota.' ?></p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination Links -->
        <?php if (isset($pager)): ?>
          <div class="d-flex justify-content-end mt-3">
            <?= $pager->only(['keyword'])->links('anggota', 'default_full') ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
