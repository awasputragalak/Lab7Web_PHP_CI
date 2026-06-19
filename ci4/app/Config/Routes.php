<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute Publik (Bisa diakses siapa saja)
$routes->get('/', 'Artikel::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
$routes->get('/artikel', 'Artikel::index');

// Rute Login & Logout
$routes->match(['get', 'post'], '/user/login', 'User::login');
$routes->get('/user/logout', 'User::logout');

// Rute Admin (DILINDUNGI OLEH FILTER 'auth')
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->match(['get', 'post'], 'artikel/add', 'Artikel::add');
    $routes->match(['get', 'post'], 'artikel/edit/(:num)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:num)', 'Artikel::delete/$1');
});

// Rute Kategori & Detail Artikel (URUTAN SANGAT PENTING)
// Route yang lebih spesifik (/kategori/) HARUS ditaruh di atas
$routes->get('/artikel/kategori/(:any)', 'Artikel::category/$1'); 

// Route wildcard (tangkap semua) ditaruh paling bawah
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

// Rute khusus untuk halaman dan fungsi AJAX
$routes->get('/ajax', 'AjaxController::index');
$routes->get('/ajax/getData', 'AjaxController::getData');
$routes->get('/ajax/delete/(:num)', 'AjaxController::delete/$1');

// Rute untuk ambil 1 data spesifik (buat form Edit) dan nyimpen data
$routes->get('/ajax/getDetail/(:num)', 'AjaxController::getDetail/$1');
$routes->post('/ajax/save', 'AjaxController::save');

$routes->resource('post');

$routes->post('api/login', 'Api\Auth::login'); // Route Login Praktikum 13
$routes->options('api/login', 'Api\Auth::login');

// Mengamankan method POST, PUT, dan DELETE (Praktikum 14)[cite: 4]
$routes->post('post', 'Api\Post::create', ['filter' => 'apiauth']);
$routes->put('post/(:segment)', 'Api\Post::update/$1', ['filter' => 'apiauth']);
$routes->delete('post/(:segment)', 'Api\Post::delete/$1', ['filter' => 'apiauth']);
$routes->options('post', 'Post::index');
$routes->options('post/(:segment)', 'Post::index');