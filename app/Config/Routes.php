<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
$routes->get('/', 'HomeController::index');

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');

$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::attemptRegister');

$routes->post('/logout', 'AuthController::logout');
$routes->get('/logout', static function () {
    return redirect()->to('/');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Catatan:
| Di CodeIgniter 4.6.5, multiple filter jangan ditulis 'auth,admin'.
| Gunakan array ['auth', 'admin'].
*/
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['auth', 'admin']], static function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');

    $routes->get('users', 'UserController::index');
    $routes->get('users/detail/(:num)', 'UserController::detail/$1');
    $routes->post('users/toggle/(:num)', 'UserController::toggleStatus/$1');

    $routes->get('detections', 'DetectionController::index');
    $routes->get('detections/detail/(:num)', 'DetectionController::detail/$1');
    $routes->post('detections/review/(:num)', 'DetectionController::markReviewed/$1');

    $routes->get('api-settings', 'ApiSettingController::index');
    $routes->post('api-settings/update', 'ApiSettingController::update');
    $routes->post('api-settings/test', 'ApiSettingController::testConnection');

    $routes->get('reports', 'ReportController::index');

    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/update', 'ProfileController::update');
    $routes->post('profile/password', 'ProfileController::changePassword');
});

/*
|--------------------------------------------------------------------------
| User Masyarakat Routes
|--------------------------------------------------------------------------
| Gunakan array ['auth', 'user'], bukan 'auth,user'.
*/
$routes->group('user', ['namespace' => 'App\Controllers\User', 'filter' => ['auth', 'user']], static function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');

    $routes->get('detections/create', 'DetectionController::create');
    $routes->post('detections/store', 'DetectionController::store');

    $routes->get('history', 'HistoryController::index');
    $routes->get('history/detail/(:num)', 'HistoryController::detail/$1');

    $routes->get('education', 'EducationController::index');

    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/update', 'ProfileController::update');
    $routes->post('profile/password', 'ProfileController::changePassword');

    // $routes->get('video/play/(:num)', 'VideoController::play/$1');
});
