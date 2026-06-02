<?php
/** @var array $apiKeys */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Manajemen API Key<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Manajemen API Key<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('new_key')): ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-key me-1"></i>
        <strong>API Key baru berhasil dibuat!</strong> Simpan key berikut karena tidak akan ditampilkan lagi secara penuh:
        <div class="mt-2">
          <code id="newKeyDisplay" class="d-block p-2 bg-dark text-light rounded" style="font-size: 0.85rem; word-break: break-all;">
            <?= esc(session()->getFlashdata('new_key')) ?>
          </code>
        </div>
        <button type="button" class="btn btn-sm btn-outline-dark mt-2" onclick="copyKey()">
          <i class="bi bi-clipboard me-1"></i> Salin Key
        </button>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Form Generate Key Baru -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Generate API Key Baru</h5>
        <form action="<?= base_url('dashboard/api-keys/generate') ?>" method="post" class="row g-3">
          <?= csrf_field() ?>
          <div class="col-md-8">
            <label for="nama_aplikasi" class="form-label">Nama Aplikasi</label>
            <input type="text" class="form-control" id="nama_aplikasi" name="nama_aplikasi" placeholder="Contoh: Mobile App Android, Sistem Inventaris..." required>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-plus-circle me-1"></i> Generate Key
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Daftar API Key -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Daftar API Key</h5>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 40px;">#</th>
                <th>Nama Aplikasi</th>
                <th>API Key</th>
                <th class="text-center" style="width: 100px;">Status</th>
                <th>Dibuat</th>
                <th class="text-center" style="width: 180px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($apiKeys)): ?>
                <?php foreach ($apiKeys as $i => $key): ?>
                  <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= esc($key['nama_aplikasi']) ?></strong></td>
                    <td>
                      <code style="font-size: 0.8rem; word-break: break-all;">
                        <?= substr(esc($key['api_key']), 0, 16) ?>...<?= substr(esc($key['api_key']), -8) ?>
                      </code>
                    </td>
                    <td class="text-center">
                      <?php if ($key['status'] === 'aktif'): ?>
                        <span class="badge bg-success">Aktif</span>
                      <?php else: ?>
                        <span class="badge bg-danger">Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td><?= date('d M Y H:i', strtotime($key['created_at'])) ?></td>
                    <td class="text-center">
                      <a href="<?= base_url('dashboard/api-keys/toggle/' . $key['id']) ?>" class="btn btn-sm <?= $key['status'] === 'aktif' ? 'btn-warning' : 'btn-success' ?>" title="<?= $key['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>">
                        <i class="bi <?= $key['status'] === 'aktif' ? 'bi-pause-circle' : 'bi-play-circle' ?>"></i>
                      </a>
                      <a href="<?= base_url('dashboard/api-keys/delete/' . $key['id']) ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus API Key ini?')">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-key" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">Belum ada API Key. Buat key pertama Anda di atas.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Panduan Singkat -->
        <div class="alert alert-info mt-3" role="alert">
          <h6 class="alert-heading"><i class="bi bi-info-circle me-1"></i> Cara Menggunakan API</h6>
          <p class="mb-1">Sertakan API Key pada header setiap request ke endpoint API:</p>
          <code class="d-block p-2 bg-dark text-light rounded mb-2" style="font-size: 0.85rem;">
            X-API-KEY: &lt;your_api_key_here&gt;
          </code>
          <p class="mb-1">Contoh dengan curl:</p>
          <code class="d-block p-2 bg-dark text-light rounded" style="font-size: 0.85rem;">
            curl -H "X-API-KEY: &lt;key&gt;" <?= base_url('api/v1/buku') ?>
          </code>
        </div>

      </div>
    </div>

  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function copyKey() {
  const keyText = document.getElementById('newKeyDisplay').innerText.trim();
  navigator.clipboard.writeText(keyText).then(function() {
    alert('API Key berhasil disalin!');
  });
}
</script>
<?= $this->endSection() ?>
