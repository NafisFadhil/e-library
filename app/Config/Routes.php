<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::processLogin');
$routes->get('register', 'Auth::register');
$routes->post('register/process', 'Auth::processRegister');
$routes->get('logout', 'Auth::logout');

$routes->group('dashboard', ['filter' => 'auth_anggota'], function ($routes) {
    $routes->get('anggota', 'Dashboard::anggota');
});

$routes->group('dashboard', ['filter' => 'auth_pustakawan'], function ($routes) {
    $routes->get('pustakawan', 'Dashboard::pustakawan');

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
});
