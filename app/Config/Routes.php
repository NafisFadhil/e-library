<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('fitur', 'Home::fitur');

$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::processLogin');
$routes->get('register', 'Auth::register');
$routes->post('register/process', 'Auth::processRegister');
$routes->get('logout', 'Auth::logout');

$routes->group('dashboard', ['filter' => 'auth_anggota'], function ($routes) {
    $routes->get('anggota', 'Dashboard::anggota');

    // Fitur Cari Buku
    $routes->get('cari-buku', 'AnggotaFitur::cariBuku');

    // Fitur Cari Buku Online (Konsumsi API Eksternal — Open Library)
    $routes->get('cari-buku-online', 'OpenLibrary::search');

    // Fitur Riwayat Pinjam
    $routes->get('riwayat-pinjam', 'AnggotaFitur::riwayatPinjam');
    $routes->get('riwayat-pinjam/detail/(:segment)', 'AnggotaFitur::detailPinjam/$1');
});

$routes->group('dashboard', ['filter' => 'auth_pustakawan'], function ($routes) {
    $routes->get('pustakawan', 'Dashboard::pustakawan');
    $routes->get('search', 'Dashboard::search');

    // CRUD Buku
    $routes->get('buku', 'Buku::index');
    $routes->get('buku/create', 'Buku::create');
    $routes->post('buku/store', 'Buku::store');
    $routes->get('buku/edit/(:segment)', 'Buku::edit/$1');
    $routes->post('buku/update/(:segment)', 'Buku::update/$1');
    $routes->get('buku/delete/(:segment)', 'Buku::delete/$1');

    // Manajemen Eksemplar (nested di bawah buku)
    $routes->get('buku/(:segment)/eksemplar', 'Eksemplar::index/$1');
    $routes->get('buku/(:segment)/eksemplar/create', 'Eksemplar::create/$1');
    $routes->post('buku/(:segment)/eksemplar/store', 'Eksemplar::store/$1');
    $routes->get('eksemplar/edit/(:segment)', 'Eksemplar::edit/$1');
    $routes->post('eksemplar/update/(:segment)', 'Eksemplar::update/$1');
    $routes->get('eksemplar/delete/(:segment)', 'Eksemplar::delete/$1');

    // CRUD Anggota
    $routes->get('anggota-list', 'Anggota::index');
    $routes->get('anggota-list/create', 'Anggota::create');
    $routes->post('anggota-list/store', 'Anggota::store');
    $routes->get('anggota-list/edit/(:segment)', 'Anggota::edit/$1');
    $routes->post('anggota-list/update/(:segment)', 'Anggota::update/$1');
    $routes->get('anggota-list/delete/(:segment)', 'Anggota::delete/$1');

    // CRUD Peminjaman
    $routes->get('peminjaman', 'Peminjaman::index');
    $routes->get('peminjaman/create', 'Peminjaman::create');
    $routes->post('peminjaman/store', 'Peminjaman::store');
    $routes->get('peminjaman/show/(:segment)', 'Peminjaman::show/$1');
    $routes->get('peminjaman/edit/(:segment)', 'Peminjaman::edit/$1');
    $routes->post('peminjaman/update/(:segment)', 'Peminjaman::update/$1');
    $routes->get('peminjaman/delete/(:segment)', 'Peminjaman::delete/$1');

    // Manajemen API Key
    $routes->get('api-keys', 'ApiKeyController::index');
    $routes->post('api-keys/generate', 'ApiKeyController::generate');
    $routes->get('api-keys/toggle/(:segment)', 'ApiKeyController::toggleStatus/$1');
    $routes->get('api-keys/delete/(:segment)', 'ApiKeyController::delete/$1');
});

$routes->group('dashboard', ['filter' => 'auth_admin_pustakawan'], function ($routes) {
    // CRUD Pustakawan
    $routes->get('pustakawan-list', 'Pustakawan::index');
    $routes->get('pustakawan-list/create', 'Pustakawan::create');
    $routes->post('pustakawan-list/store', 'Pustakawan::store');
    $routes->get('pustakawan-list/edit/(:segment)', 'Pustakawan::edit/$1');
    $routes->post('pustakawan-list/update/(:segment)', 'Pustakawan::update/$1');
    $routes->get('pustakawan-list/delete/(:segment)', 'Pustakawan::delete/$1');
});

// ============================================================
// REST API Endpoints (Webservice Server — dilindungi API Key)
// ============================================================
$routes->group('api/v1', ['filter' => 'api_auth'], function ($routes) {
    // API Buku
    $routes->get('buku', 'Api\BukuApi::index');
    $routes->get('buku/(:segment)', 'Api\BukuApi::show/$1');
    $routes->post('buku', 'Api\BukuApi::create');

    // API Peminjaman
    $routes->get('peminjaman', 'Api\PeminjamanApi::index');
    $routes->get('peminjaman/(:segment)', 'Api\PeminjamanApi::show/$1');
});
