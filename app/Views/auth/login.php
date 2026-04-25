<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login - E-Library</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('assets/img/favicon.png') ?>" rel="icon">
  <link href="<?= base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/boxicons/css/boxicons.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/quill/quill.snow.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/quill/quill.bubble.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/remixicon/remixicon.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/simple-datatables/style.css') ?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="<?= base_url('/') ?>" class="logo d-flex align-items-center w-auto">
                  <img src="<?= base_url('assets/img/logo.png') ?>" alt="">
                  <span class="d-none d-lg-block">E-Library</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login ke Akun Anda</h5>
                    <p class="text-center small">Pilih peran Anda untuk masuk</p>
                  </div>

                  <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <?= session()->getFlashdata('error') ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  <?php endif; ?>

                  <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <?= session()->getFlashdata('success') ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  <?php endif; ?>

                  <!-- Bordered Tabs -->
                  <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTab" role="tablist">
                    <li class="nav-item flex-fill" role="presentation">
                      <button class="nav-link w-100 active" id="anggota-tab" data-bs-toggle="tab" data-bs-target="#bordered-anggota" type="button" role="tab" aria-controls="anggota" aria-selected="true">Anggota</button>
                    </li>
                    <li class="nav-item flex-fill" role="presentation">
                      <button class="nav-link w-100" id="pustakawan-tab" data-bs-toggle="tab" data-bs-target="#bordered-pustakawan" type="button" role="tab" aria-controls="pustakawan" aria-selected="false">Pustakawan</button>
                    </li>
                  </ul>
                  <div class="tab-content pt-2" id="borderedTabContent">
                    <div class="tab-pane fade show active" id="bordered-anggota" role="tabpanel" aria-labelledby="anggota-tab">
                      <form action="<?= base_url('login/process') ?>" method="POST" class="row g-3 needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="role" value="anggota">
                        <div class="col-12">
                          <label for="emailAnggota" class="form-label">Email</label>
                          <input type="email" name="email" class="form-control" id="emailAnggota" required>
                          <div class="invalid-feedback">Silakan masukkan email Anda.</div>
                        </div>
                        <div class="col-12">
                          <label for="passwordAnggota" class="form-label">Password</label>
                          <input type="password" name="password" class="form-control" id="passwordAnggota" required>
                          <div class="invalid-feedback">Silakan masukkan password Anda.</div>
                        </div>
                        <div class="col-12">
                          <button class="btn btn-primary w-100" type="submit">Login sebagai Anggota</button>
                        </div>
                        <div class="col-12">
                          <p class="small mb-0 text-center">Belum punya akun? <a href="<?= base_url('register') ?>">Daftar di sini</a></p>
                        </div>
                      </form>
                    </div>
                    <div class="tab-pane fade" id="bordered-pustakawan" role="tabpanel" aria-labelledby="pustakawan-tab">
                      <form action="<?= base_url('login/process') ?>" method="POST" class="row g-3 needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="role" value="pustakawan">
                        <div class="col-12">
                          <label for="usernamePustakawan" class="form-label">Username</label>
                          <input type="text" name="username" class="form-control" id="usernamePustakawan" required>
                          <div class="invalid-feedback">Silakan masukkan username Anda.</div>
                        </div>
                        <div class="col-12">
                          <label for="passwordPustakawan" class="form-label">Password</label>
                          <input type="password" name="password" class="form-control" id="passwordPustakawan" required>
                          <div class="invalid-feedback">Silakan masukkan password Anda.</div>
                        </div>
                        <div class="col-12">
                          <button class="btn btn-primary w-100" type="submit">Login sebagai Pustakawan</button>
                        </div>
                      </form>
                    </div>
                  </div><!-- End Bordered Tabs -->

                </div>
              </div>

              <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/php-email-form/validate.js') ?>"></script>

  <!-- Template Main JS File -->
  <script src="<?= base_url('assets/js/main.js') ?>"></script>

</body>

</html>
