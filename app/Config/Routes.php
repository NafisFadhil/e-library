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
});
