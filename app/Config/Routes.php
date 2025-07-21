<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Health check endpoints
$routes->get('/health', 'HealthController::check');
$routes->get('/health/ping', 'HealthController::ping');

// Test route tanpa database
$routes->get('/test-rekap', function() {
    return view('test-index');
});

// Test route for holiday integration
$routes->get('/test-holiday-integration', 'TestController::holidayIntegration');

// Enhanced Absensi Public Access (for demo/testing)
$routes->get('/rekap-enhanced', 'Admin\AbsensiEnhanced::rekap');
$routes->get('/rekap-clean', 'Admin\AbsensiEnhanced::rekapClean');
$routes->get('/rekap-enhanced/export-excel', 'Admin\AbsensiEnhanced::exportExcel');

$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::authenticate');

// Admin Routes Group
$routes->group('admin', function($routes) {
    // Default admin route - redirect to dashboard
    $routes->get('/', 'Admin\Dashboard::index');
    
    // Main Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Data Siswa Routes
    $routes->get('data-siswa', 'Admin\DataSiswa::index');
    $routes->get('data-siswa/detail/(:num)', 'Admin\DataSiswa::detail/$1');
    $routes->get('data-siswa/export', 'Admin\DataSiswa::export');
    $routes->get('data-siswa/export-excel', 'Admin\DataSiswa::exportExcel');
    $routes->get('data-siswa/create', 'Admin\DataSiswa::create');
    $routes->post('data-siswa/store', 'Admin\DataSiswa::store');
    $routes->get('data-siswa/edit/(:num)', 'Admin\DataSiswa::edit/$1');
    $routes->post('data-siswa/update/(:num)', 'Admin\DataSiswa::update/$1');
    $routes->post('data-siswa/delete/(:num)', 'Admin\DataSiswa::delete/$1');
    $routes->get('data-siswa/view/(:num)', 'Admin\DataSiswa::view/$1');
    
    // Profil Sekolah Routes
    $routes->get('profil-sekolah', 'Admin\ProfilSekolah::index');
    $routes->post('profil-sekolah/save', 'Admin\ProfilSekolah::save');
    
    // User Management Routes
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->post('users/toggle-status/(:num)', 'Admin\Users::toggleStatus/$1');
    
    // Profile Routes
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/update', 'Admin\Profile::update');
    $routes->post('profile/change-password', 'Admin\Profile::changePassword');
    $routes->post('profile/upload-avatar', 'Admin\Profile::uploadAvatar');
    
    // Settings Routes
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/update', 'Admin\Settings::update');
    $routes->post('settings/update-school-info', 'Admin\Settings::updateSchoolInfo');
    $routes->post('settings/update-system', 'Admin\Settings::updateSystem');
    
    // Berita Routes (existing - keeping for backward compatibility)
    $routes->get('berita', 'Admin\Berita::index');
    $routes->get('berita/create', 'Admin\Berita::create');
    $routes->post('berita/store', 'Admin\Berita::store');
    $routes->get('berita/edit/(:num)', 'Admin\Berita::edit/$1');
    $routes->post('berita/update/(:num)', 'Admin\Berita::update/$1');
    $routes->post('berita/delete/(:num)', 'Admin\Berita::delete/$1');
    $routes->post('berita/upload-image', 'Admin\Berita::uploadImage');
    
    // Naik Kelas Routes
    $routes->get('naik-kelas', 'Admin\NaikKelas::index');
    $routes->post('naik-kelas/preview', 'Admin\NaikKelas::preview');
    $routes->post('naik-kelas/execute', 'Admin\NaikKelas::execute');
    $routes->post('naik-kelas/batch-naik-kelas', 'Admin\NaikKelas::batchNaikKelas');
    $routes->post('naik-kelas/graduate-class-6', 'Admin\NaikKelas::graduateClass6');
    $routes->post('naik-kelas/check-target-class', 'Admin\NaikKelas::checkTargetClass');
    
    // Kalender Akademik Routes
    $routes->get('kalender-akademik', 'Admin\KalenderAkademik::index');
    $routes->post('kalender-akademik/get-events-by-date', 'Admin\KalenderAkademik::getEventsByDate');
    $routes->post('kalender-akademik/save-event', 'Admin\KalenderAkademik::saveEvent');
    $routes->post('kalender-akademik/update-event', 'Admin\KalenderAkademik::updateEvent');
    $routes->post('kalender-akademik/delete-event', 'Admin\KalenderAkademik::deleteEvent');
    
    // Nilai Routes
    $routes->get('nilai', 'Admin\Nilai::index');
    $routes->get('nilai/create', 'Admin\Nilai::create');
    $routes->post('nilai/store', 'Admin\Nilai::store');
    $routes->get('nilai/detail/(:num)', 'Admin\Nilai::detail/$1');
    $routes->get('nilai/edit/(:num)', 'Admin\Nilai::edit/$1');
    $routes->post('nilai/update/(:num)', 'Admin\Nilai::update/$1');
    $routes->post('nilai/delete/(:num)', 'Admin\Nilai::delete/$1');
    
    // Absensi Routes
    $routes->get('absensi/input', 'Admin\Absensi::input');
    $routes->post('absensi/input', 'Admin\Absensi::input'); // For AJAX requests
    $routes->get('absensi/rekap', 'Admin\Absensi::rekap');
    $routes->get('absensi/rekap-test', 'Admin\AbsensiTest::rekap'); // Testing route tanpa database
    
    // Enhanced Absensi with real Excel export
    $routes->get('absensi-enhanced/rekap', 'Admin\AbsensiEnhanced::rekap');
    $routes->get('absensi-enhanced/rekap-clean', 'Admin\AbsensiEnhanced::rekapClean');
    $routes->get('absensi-enhanced/export-excel', 'Admin\AbsensiEnhanced::exportExcel');
    
    $routes->post('absensi/save', 'Admin\Absensi::save');
    $routes->post('absensi/save_all', 'Admin\Absensi::save_all');
    $routes->post('absensi/getDetailData', 'Admin\Absensi::getDetailData');
    $routes->get('absensi/getSummary', 'Admin\Absensi::getSummary');
    $routes->get('absensi/export', 'Admin\Absensi::export');
});

// Logout Route (accessible from anywhere)
$routes->get('logout', 'Login::logout');
