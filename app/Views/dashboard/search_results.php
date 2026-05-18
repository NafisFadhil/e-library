<?php
/** @var string $keyword */
/** @var array $buku */
/** @var array $anggota */
/** @var array $peminjaman */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Hasil Pencarian<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Pencarian Global<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12 mb-4">
    <div class="card">
      <div class="card-body pt-4">
        <h5 class="card-title p-0 m-0">
          Hasil pencarian untuk: <strong class="text-primary">"<?= esc((string) $keyword) ?>"</strong>
        </h5>
        <p class="text-muted small m-0 mt-2">Ditemukan <?= count($buku) ?> Buku, <?= count($anggota) ?> Anggota, dan <?= count($peminjaman) ?> Peminjaman.</p>
      </div>
    </div>
  </div>

  <div class="col-12">
    <!-- Bordered Tabs -->
    <ul class="nav nav-tabs nav-tabs-bordered" id="searchTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="buku-tab" data-bs-toggle="tab" data-bs-target="#buku" type="button" role="tab" aria-controls="buku" aria-selected="true">
          <i class="bi bi-book"></i> Buku <span class="badge bg-primary rounded-pill ms-1"><?= count($buku) ?></span>
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="anggota-tab" data-bs-toggle="tab" data-bs-target="#anggota" type="button" role="tab" aria-controls="anggota" aria-selected="false">
          <i class="bi bi-people"></i> Anggota <span class="badge bg-success rounded-pill ms-1"><?= count($anggota) ?></span>
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="peminjaman-tab" data-bs-toggle="tab" data-bs-target="#peminjaman" type="button" role="tab" aria-controls="peminjaman" aria-selected="false">
          <i class="bi bi-journal-check"></i> Peminjaman <span class="badge bg-info rounded-pill ms-1"><?= count($peminjaman) ?></span>
        </button>
      </li>
    </ul>
    
    <div class="tab-content pt-4" id="searchTabContent">
      <!-- Tab Buku -->
      <div class="tab-pane fade show active" id="buku" role="tabpanel" aria-labelledby="buku-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Data Buku</h5>
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>ISBN</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($buku)): ?>
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">Buku tidak ditemukan.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($buku as $item): ?>
                      <tr>
                        <td><code><?= esc((string) $item['isbn']) ?></code></td>
                        <td><strong><?= esc((string) $item['judul']) ?></strong></td>
                        <td><?= esc((string) $item['penulis']) ?></td>
                        <td><?= esc((string) $item['kategori']) ?></td>
                        <td>
                          <a href="<?= base_url('dashboard/buku/edit/' . $item['isbn']) ?>" class="btn btn-sm btn-primary">Lihat/Edit</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Anggota -->
      <div class="tab-pane fade" id="anggota" role="tabpanel" aria-labelledby="anggota-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Data Anggota</h5>
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($anggota)): ?>
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">Anggota tidak ditemukan.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($anggota as $item): ?>
                      <tr>
                        <td><strong><?= esc((string) $item['nama']) ?></strong></td>
                        <td><?= esc((string) $item['email']) ?></td>
                        <td><?= esc((string) $item['no_telepon']) ?></td>
                        <td>
                          <?php if (strtolower((string) $item['status']) === 'aktif'): ?>
                            <span class="badge bg-success">Aktif</span>
                          <?php else: ?>
                            <span class="badge bg-danger">Nonaktif</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <a href="<?= base_url('dashboard/anggota-list/edit/' . $item['id_anggota']) ?>" class="btn btn-sm btn-primary">Lihat/Edit</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Peminjaman -->
      <div class="tab-pane fade" id="peminjaman" role="tabpanel" aria-labelledby="peminjaman-tab">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Data Peminjaman</h5>
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Anggota</th>
                    <th>Tgl Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($peminjaman)): ?>
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">Data peminjaman tidak ditemukan.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($peminjaman as $item): ?>
                      <tr>
                        <td><strong>#<?= esc((string) $item['id_peminjaman']) ?></strong></td>
                        <td><?= esc((string) $item['nama_anggota']) ?></td>
                        <td><?= $item['tanggal_pinjam'] ? date('d/m/Y', strtotime($item['tanggal_pinjam'])) : '-' ?></td>
                        <td>
                          <?php
                            $badgeClass = match($item['status_peminjaman']) {
                              'Diajukan' => 'bg-warning text-dark',
                              'Dipinjam' => 'bg-primary',
                              'Selesai'  => 'bg-success',
                              'Ditolak'  => 'bg-danger',
                              default    => 'bg-secondary',
                            };
                          ?>
                          <span class="badge <?= $badgeClass ?>"><?= esc((string) $item['status_peminjaman']) ?></span>
                        </td>
                        <td>
                          <a href="<?= base_url('dashboard/peminjaman/show/' . $item['id_peminjaman']) ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Detail</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
<?= $this->endSection() ?>
