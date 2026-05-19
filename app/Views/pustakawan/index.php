<?php
/** @var array $pustakawan */
/** @var \CodeIgniter\Pager\Pager $pager */
/** @var string|null $keyword */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Daftar Pustakawan<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Daftar Pustakawan<?= $this->endSection() ?>

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
          <h5 class="card-title m-0">Data Pustakawan E-Library</h5>
          <a href="<?= base_url('dashboard/pustakawan-list/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pustakawan
          </a>
        </div>

        <!-- Search Bar -->
        <div class="row g-3 mb-4 align-items-center">
          <div class="col-md-6 col-lg-5">
            <form action="<?= base_url('dashboard/pustakawan-list') ?>" method="get">
              <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white text-muted border-end-0">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Cari nama, username, atau email..." value="<?= esc($keyword ?? '') ?>">
                <?php if (!empty($keyword)): ?>
                  <a href="<?= base_url('dashboard/pustakawan-list') ?>" class="btn btn-outline-secondary border-start-0 border-end-0 d-flex align-items-center justify-content-center" title="Reset Pencarian">
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
                <th>Username</th>
                <th>Email</th>
                <th class="text-center">Role</th>
                <th class="text-center" style="width: 180px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($pustakawan) && count($pustakawan) > 0): ?>
                <?php $no = ($pager->getCurrentPage('pustakawan') - 1) * 10 + 1; ?>
                <?php foreach ($pustakawan as $item): ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><strong><?= esc((string) $item['nama']) ?></strong></td>
                    <td><?= esc((string) $item['username']) ?></td>
                    <td><?= esc((string) $item['email']) ?></td>
                    <td class="text-center">
                      <?php if ($item['is_admin']): ?>
                        <span class="badge bg-primary"><i class="bi bi-shield-lock me-1"></i> Admin</span>
                      <?php else: ?>
                        <span class="badge bg-secondary"><i class="bi bi-person me-1"></i> Pustakawan</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="<?= base_url('dashboard/pustakawan-list/edit/' . $item['id_pustakawan']) ?>" class="btn btn-warning btn-sm me-1" title="Edit Pustakawan">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <?php if (session()->get('user_id') != $item['id_pustakawan']): ?>
                        <a href="<?= base_url('dashboard/pustakawan-list/delete/' . $item['id_pustakawan']) ?>" 
                           onclick="return confirm('Yakin ingin menghapus pustakawan ini?')" 
                           class="btn btn-danger btn-sm" title="Hapus Pustakawan">
                          <i class="bi bi-trash"></i>
                        </a>
                      <?php else: ?>
                        <button class="btn btn-danger btn-sm disabled" title="Anda tidak dapat menghapus akun Anda sendiri" style="opacity: 0.5; cursor: not-allowed;">
                          <i class="bi bi-trash"></i>
                        </button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0"><?= !empty($keyword) ? 'Tidak ditemukan pustakawan dengan kata kunci tersebut.' : 'Belum ada data pustakawan.' ?></p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination Links -->
        <?php if (isset($pager)): ?>
          <div class="d-flex justify-content-end mt-3">
            <?= $pager->only(['keyword'])->links('pustakawan', 'default_full') ?>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
