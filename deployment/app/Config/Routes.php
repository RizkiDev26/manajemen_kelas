<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

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
    $routes->get('data-siswa/create', 'Admin\DataSiswa::create');
    $routes->post('data-siswa/store', 'Admin\DataSiswa::store');
    $routes->get('data-siswa/edit/(:num)', 'Admin\DataSiswa::edit/$1');
    $routes->post('data-siswa/update/(:num)', 'Admin\DataSiswa::update/$1');
    $routes->post('data-siswa/delete/(:num)', 'Admin\DataSiswa::delete/$1');
    $routes->get('data-siswa/view/(:num)', 'Admin\DataSiswa::view/$1');
    
    // Daftar Hadir Routes
    $routes->get('daftar-hadir', 'Admin\DaftarHadir::index');
    $routes->get('daftar-hadir/create', 'Admin\DaftarHadir::create');
    $routes->post('daftar-hadir/store', 'Admin\DaftarHadir::store');
    $routes->get('daftar-hadir/edit/(:num)', 'Admin\DaftarHadir::edit/$1');
    $routes->post('daftar-hadir/update/(:num)', 'Admin\DaftarHadir::update/$1');
    $routes->post('daftar-hadir/delete/(:num)', 'Admin\DaftarHadir::delete/$1');
    $routes->get('daftar-hadir/view/(:segment)', 'Admin\DaftarHadir::view/$1'); // for date or class
    $routes->post('daftar-hadir/mark-attendance', 'Admin\DaftarHadir::markAttendance');
    
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
    $routes->post('kalender-akademik/delete-event', 'Admin\KalenderAkademik::deleteEvent');
    
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
