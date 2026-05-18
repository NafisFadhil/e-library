  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link <?= url_is('dashboard/pustakawan') || url_is('dashboard/anggota') ? '' : 'collapsed' ?>" href="<?= session()->get('logged_in') ? base_url('dashboard/' . session()->get('role')) : base_url('/') ?>">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <?php if (session()->get('role') === 'pustakawan') : ?>
        <li class="nav-heading">Manajemen</li>

        <li class="nav-item">
          <a class="nav-link <?= url_is('dashboard/buku*') || url_is('dashboard/eksemplar*') ? '' : 'collapsed' ?>" href="<?= base_url('dashboard/buku') ?>">
            <i class="bi bi-book"></i>
            <span>Data Buku</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= url_is('dashboard/anggota-list*') ? '' : 'collapsed' ?>" href="<?= base_url('dashboard/anggota-list') ?>">
            <i class="bi bi-people"></i>
            <span>Data Anggota</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= url_is('dashboard/peminjaman*') ? '' : 'collapsed' ?>" href="<?= base_url('dashboard/peminjaman') ?>">
            <i class="bi bi-journal-check"></i>
            <span>Peminjaman</span>
          </a>
        </li>
      <?php else : ?>
        <li class="nav-heading">Layanan</li>

        <li class="nav-item">
          <a class="nav-link <?= url_is('dashboard/cari-buku*') ? '' : 'collapsed' ?>" href="<?= base_url('dashboard/cari-buku') ?>">
            <i class="bi bi-search"></i>
            <span>Cari Buku</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= url_is('dashboard/riwayat-pinjam*') ? '' : 'collapsed' ?>" href="<?= base_url('dashboard/riwayat-pinjam') ?>">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat Pinjam</span>
          </a>
        </li>
      <?php endif; ?>

      <li class="nav-heading">Akun</li>

      <li class="nav-item">
        <?php if (session()->get('logged_in')): ?>
          <a class="nav-link collapsed" href="<?= base_url('logout') ?>">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
          </a>
        <?php else: ?>
          <a class="nav-link collapsed" href="<?= base_url('login') ?>">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Login</span>
          </a>
        <?php endif; ?>
      </li>

    </ul>

  </aside><!-- End Sidebar-->
