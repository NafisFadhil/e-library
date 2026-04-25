  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="<?= base_url('dashboard/' . session()->get('role')) ?>">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <?php if (session()->get('role') === 'pustakawan') : ?>
        <li class="nav-heading">Manajemen</li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#">
            <i class="bi bi-book"></i>
            <span>Data Buku</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#">
            <i class="bi bi-people"></i>
            <span>Data Anggota</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#">
            <i class="bi bi-journal-check"></i>
            <span>Peminjaman</span>
          </a>
        </li>
      <?php else : ?>
        <li class="nav-heading">Layanan</li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#">
            <i class="bi bi-search"></i>
            <span>Cari Buku</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat Pinjam</span>
          </a>
        </li>
      <?php endif; ?>

      <li class="nav-heading">Akun</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?= base_url('logout') ?>">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </a>
      </li>

    </ul>

  </aside><!-- End Sidebar-->
