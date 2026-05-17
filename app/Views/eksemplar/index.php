<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Kelola Eksemplar - <?= esc($buku['judul']) ?><?= $this->endSection() ?>

<?= $this->section('page_title') ?>Manajemen Eksemplar Buku<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <!-- Success & Error Alert Snippet -->
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

    <!-- Informasi Buku Terkait (Konteks) -->
    <div class="card mb-4 border-start border-info border-3">
      <div class="card-body py-3">
        <h5 class="card-title py-1 m-0 text-info">Konteks Buku</h5>
        <div class="row mt-2">
          <div class="col-md-2 col-12 text-center text-md-start">
            <?php if (!empty($buku['url_cover'])): ?>
              <img src="<?= esc($buku['url_cover']) ?>" alt="Cover" class="img-thumbnail rounded" style="max-height: 120px; max-width: 90px; object-fit: cover;">
            <?php else: ?>
              <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded mx-auto" style="height: 120px; width: 90px; font-size: 11px;">
                No Cover
              </div>
            <?php endif; ?>
          </div>
          <div class="col-md-10 col-12 mt-3 mt-md-0">
            <h4 class="mb-1 text-dark fw-bold"><?= esc($buku['judul']) ?></h4>
            <p class="mb-2 text-muted">Ditulis oleh <strong><?= esc($buku['penulis']) ?></strong> | Diterbitkan oleh <?= esc($buku['penerbit']) ?> (<?= esc($buku['tahun_terbit']) ?>)</p>
            <div class="d-flex flex-wrap gap-2">
              <span class="badge bg-secondary font-monospace fs-7">ISBN: <?= esc($buku['isbn']) ?></span>
              <span class="badge bg-dark fs-7"><?= esc($buku['kategori']) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Daftar Eksemplar -->
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center my-3">
          <h5 class="card-title m-0">Daftar Salinan / Eksemplar</h5>
          <div>
            <a href="<?= base_url('dashboard/buku') ?>" class="btn btn-secondary btn-sm me-2">
              <i class="bi bi-arrow-left me-1"></i> Kembali ke Buku
            </a>
            <a href="<?= base_url('dashboard/buku/' . esc($buku['isbn']) . '/eksemplar/create') ?>" class="btn btn-primary btn-sm">
              <i class="bi bi-plus-lg me-1"></i> Tambah Eksemplar
            </a>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover datatable align-middle">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Kode Eksemplar</th>
                <th>Kondisi</th>
                <th>Lokasi Rak</th>
                <th>Ketersediaan</th>
                <th class="text-center" style="width: 150px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($eksemplar) && count($eksemplar) > 0): ?>
                <?php $no = 1; foreach ($eksemplar as $copy): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><code class="text-primary fw-bold font-monospace"><?= esc($copy['kode']) ?></code></td>
                    <td>
                      <?php if ($copy['kondisi'] === 'Bagus'): ?>
                        <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i> Bagus</span>
                      <?php elseif ($copy['kondisi'] === 'Rusak Ringan'): ?>
                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Rusak Ringan</span>
                      <?php else: ?>
                        <span class="badge bg-danger"><i class="bi bi-x-octagon me-1"></i> Rusak Berat</span>
                      <?php endif; ?>
                    </td>
                    <td><i class="bi bi-tag me-1 text-muted"></i><?= esc($copy['lokasi_rak']) ?></td>
                    <td>
                      <?php if ($copy['ketersediaan'] === 'Tersedia'): ?>
                        <span class="badge bg-info text-dark">Tersedia</span>
                      <?php elseif ($copy['ketersediaan'] === 'Dipinjam'): ?>
                        <span class="badge bg-primary">Dipinjam</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Tidak Tersedia</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="<?= base_url('dashboard/eksemplar/edit/' . esc($copy['kode'])) ?>" class="btn btn-warning btn-sm me-1" title="Edit Eksemplar">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="<?= base_url('dashboard/eksemplar/delete/' . esc($copy['kode'])) ?>" 
                         onclick="return confirm('Yakin ingin menghapus eksemplar dengan kode <?= esc($copy['kode']) ?> ini?')" 
                         class="btn btn-danger btn-sm" title="Hapus Eksemplar">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-archive-fill" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">Belum ada salinan (eksemplar) untuk buku ini.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
