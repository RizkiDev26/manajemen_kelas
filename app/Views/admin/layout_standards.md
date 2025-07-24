<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- 
===========================================
IMPROVED ATTENDANCE SYSTEM
===========================================
Author: Manus AI
Version: 2.0
Description: Enhanced attendance management system with modern UI/UX,
             improved functionality, and better code organization.

Key Improvements:
- Modern design with consistent design system
- Enhanced user experience with micro-interactions
- Better accessibility and mobile responsiveness
- Improved code organization and maintainability
- Advanced features like bulk operations and real-time feedback
===========================================
-->

<!-- Debug Info (Development Only) -->
<?php if (ENVIRONMENT === 'development'): ?>
<div class="fixed top-4 right-4 z-50 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow-lg max-w-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                <strong>Debug:</strong> Kelas: <?= $selectedKelas ?? 'NULL' ?> | 
                Siswa: <?= count($students ?? []) ?> | 
                Role: <?= $userRole ?? 'NULL' ?>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php 
/**
 * Indonesian day names mapping for better localization
 * This ensures consistent date display across the application
 */
$dayNames = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa', 
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];
$englishDay = date('l', strtotime($selectedDate ?? date('Y-m-d')));
$indonesianDay = $dayNames[$englishDay] ?? $englishDay;
$formattedDate = date('d M Y', strtotime($selectedDate ?? date('Y-m-d')));
?>

<!-- Main Content Container -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 pb-32">
    
    <?php if ($selectedKelas && !empty($students)): ?>
    
    <!-- Enhanced Page Header with Gradient Background -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-2xl shadow-2xl mb-8">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative px-8 py-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Header Content -->
                <div class="text-white">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold mb-2">
                                Absensi Kelas <?= htmlspecialchars($selectedKelas) ?>
                            </h1>
                            <p class="text-blue-100 text-lg font-medium">
                                <?= $indonesianDay ?>, <?= $formattedDate ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-4 mt-6">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                            <span class="text-blue-100 text-sm">Total Siswa</span>
                            <div class="text-2xl font-bold"><?= count($students) ?></div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                            <span class="text-blue-100 text-sm">Sudah Absen</span>
                            <div class="text-2xl font-bold" id="attendedCount">0</div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                            <span class="text-blue-100 text-sm">Belum Absen</span>
                            <div class="text-2xl font-bold" id="pendingCount"><?= count($students) ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" id="markAllPresent" 
                            class="group relative overflow-hidden bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg border border-white border-opacity-30">
                        <div class="flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Hadir Semua</span>
                        </div>
                        <!-- Hover effect overlay -->
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    </button>
                    
                    <button type="button" id="exportData" 
                            class="group relative overflow-hidden bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg border border-white border-opacity-30">
                        <div class="flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Export</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filter Controls with Modern Design -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-8 backdrop-blur-sm">
        <form id="filterForm" method="GET" class="space-y-6">
            
            <!-- Filter Header -->
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter & Navigasi
                </h3>
                
                <!-- Auto-reload Status -->
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-gray-600">Auto-reload aktif</span>
                </div>
            </div>
            
            <!-- Filter Controls Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Previous Day Button -->
                <div class="lg:col-span-1">
                    <button type="button" id="prevDay" 
                            class="w-full group relative overflow-hidden bg-gray-50 hover:bg-gray-100 border border-gray-200 hover:border-gray-300 text-gray-700 px-4 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span class="hidden sm:inline">Hari Sebelumnya</span>
                            <span class="sm:hidden">Prev</span>
                        </div>
                    </button>
                </div>
                
                <!-- Date Input -->
                <div class="lg:col-span-1">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Tanggal
                    </label>
                    <input type="date" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white hover:border-gray-300" 
                           id="tanggal" name="tanggal" value="<?= $selectedDate ?>" required>
                </div>
                
                <!-- Class Dropdown (Admin Only) -->
                <?php if ($userRole === 'admin'): ?>
                <div class="lg:col-span-1">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kelas
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white hover:border-gray-300" 
                            id="kelas" name="kelas" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($allKelas as $kelas): ?>
                        <option value="<?= htmlspecialchars($kelas['kelas']) ?>" 
                                <?= $selectedKelas === $kelas['kelas'] ? 'selected' : '' ?>>
                            Kelas <?= htmlspecialchars($kelas['kelas']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                <input type="hidden" name="kelas" value="<?= htmlspecialchars($userKelas ?? '') ?>">
                <div class="lg:col-span-1"></div> <!-- Spacer for grid alignment -->
                <?php endif; ?>
                
                <!-- Search Input -->
                <div class="lg:col-span-1">
                    <label for="searchStudent" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Siswa
                    </label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white hover:border-gray-300" 
                           id="searchStudent" placeholder="Nama atau NISN..." autocomplete="off">
                </div>
                
                <!-- Next Day Button -->
                <div class="lg:col-span-1">
                    <button type="button" id="nextDay" 
                            class="w-full group relative overflow-hidden bg-gray-50 hover:bg-gray-100 border border-gray-200 hover:border-gray-300 text-gray-700 px-4 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 hover:shadow-md mt-7">
                        <div class="flex items-center justify-center gap-2">
                            <span class="hidden sm:inline">Hari Selanjutnya</span>
                            <span class="sm:hidden">Next</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Quick Filter Buttons -->
            <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-100">
                <span class="text-sm font-medium text-gray-700 mr-2">Filter cepat:</span>
                <button type="button" class="filter-btn" data-filter="all">Semua</button>
                <button type="button" class="filter-btn" data-filter="hadir">Hadir</button>
                <button type="button" class="filter-btn" data-filter="sakit">Sakit</button>
                <button type="button" class="filter-btn" data-filter="izin">Izin</button>
                <button type="button" class="filter-btn" data-filter="alpha">Alpha</button>
                <button type="button" class="filter-btn" data-filter="belum">Belum Absen</button>
            </div>
        </form>
    </div>


    <!-- Enhanced Students Grid Layout with Modern Cards -->
    <div id="studentsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 mb-8">
        <?php foreach ($students as $index => $student): ?>
        <div class="student-card group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105" 
             data-siswa-id="<?= htmlspecialchars($student['siswa_id']) ?>"
             data-student-name="<?= strtolower(htmlspecialchars($student['nama'])) ?>"
             data-student-nisn="<?= htmlspecialchars($student['nisn'] ?? '') ?>">
            
            <!-- Card Header with Avatar and Student Info -->
            <div class="relative p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                <!-- Status Indicator -->
                <div class="absolute top-4 right-4">
                    <div class="status-indicator w-3 h-3 rounded-full <?= 
                        $student['status'] === 'hadir' ? 'bg-green-500' : 
                        ($student['status'] === 'sakit' ? 'bg-yellow-500' : 
                        ($student['status'] === 'izin' ? 'bg-blue-500' : 
                        ($student['status'] === 'alpha' ? 'bg-red-500' : 'bg-gray-300'))) 
                    ?> shadow-lg"></div>
                </div>
                
                <!-- Avatar Section -->
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="relative mb-4">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 flex items-center justify-center shadow-xl ring-4 ring-white group-hover:ring-blue-100 transition-all duration-300">
                            <span class="text-white font-bold text-xl">
                                <?= strtoupper(substr($student['nama'], 0, 2)) ?>
                            </span>
                        </div>
                        <!-- Online indicator (could be dynamic based on real-time data) -->
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-white shadow-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Student Name -->
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors duration-300">
                        <?= htmlspecialchars($student['nama']) ?>
                    </h3>
                    
                    <!-- Student Details -->
                    <div class="space-y-2 w-full">
                        <div class="flex items-center justify-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 011-1h2a2 2 0 011 1v2m-4 0a2 2 0 01-2 2h2a2 2 0 01-2-2m0 0V4a2 2 0 012-2h2a2 2 0 012 2v2"></path>
                            </svg>
                            <span class="font-medium">NISN:</span>
                            <span><?= htmlspecialchars($student['nisn'] ?? '-') ?></span>
                        </div>
                        
                        <div class="flex items-center justify-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= 
                                isset($student['jk']) && $student['jk'] == 'L' ? 
                                'bg-blue-100 text-blue-800 border border-blue-200' : 
                                'bg-pink-100 text-pink-800 border border-pink-200' 
                            ?>">
                                <?php if (isset($student['jk'])): ?>
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <?php if ($student['jk'] == 'L'): ?>
                                            <path d="M10 2L3 7v11c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7l-7-5z"/>
                                        <?php else: ?>
                                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>
                                        <?php endif; ?>
                                    </svg>
                                <?php endif; ?>
                                <?= isset($student['jk']) ? ($student['jk'] == 'L' ? 'Laki-laki' : 'Perempuan') : 'Tidak diketahui' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Attendance Status Buttons -->
            <div class="p-6 pt-0">
                <div class="grid grid-cols-2 gap-3">
                    <!-- Hadir Button -->
                    <button type="button" 
                            class="btn-attendance group relative overflow-hidden rounded-xl border-2 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 <?= 
                                $student['status'] === 'hadir' ? 
                                'border-green-500 bg-green-500 text-white shadow-lg shadow-green-200' : 
                                'border-green-200 bg-green-50 text-green-700 hover:border-green-300 hover:bg-green-100 hover:shadow-md' 
                            ?>" 
                            data-status="hadir"
                            aria-label="Tandai <?= htmlspecialchars($student['nama']) ?> hadir">
                        <div class="flex flex-col items-center justify-center py-4 px-2">
                            <svg class="w-6 h-6 mb-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Hadir</span>
                        </div>
                        <!-- Ripple effect -->
                        <div class="absolute inset-0 bg-white opacity-0 group-active:opacity-20 transition-opacity duration-150 rounded-xl"></div>
                    </button>
                    
                    <!-- Sakit Button -->
                    <button type="button" 
                            class="btn-attendance group relative overflow-hidden rounded-xl border-2 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 <?= 
                                $student['status'] === 'sakit' ? 
                                'border-yellow-500 bg-yellow-500 text-white shadow-lg shadow-yellow-200' : 
                                'border-yellow-200 bg-yellow-50 text-yellow-700 hover:border-yellow-300 hover:bg-yellow-100 hover:shadow-md' 
                            ?>" 
                            data-status="sakit"
                            aria-label="Tandai <?= htmlspecialchars($student['nama']) ?> sakit">
                        <div class="flex flex-col items-center justify-center py-4 px-2">
                            <svg class="w-6 h-6 mb-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Sakit</span>
                        </div>
                        <div class="absolute inset-0 bg-white opacity-0 group-active:opacity-20 transition-opacity duration-150 rounded-xl"></div>
                    </button>
                    
                    <!-- Izin Button -->
                    <button type="button" 
                            class="btn-attendance group relative overflow-hidden rounded-xl border-2 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 <?= 
                                $student['status'] === 'izin' ? 
                                'border-blue-500 bg-blue-500 text-white shadow-lg shadow-blue-200' : 
                                'border-blue-200 bg-blue-50 text-blue-700 hover:border-blue-300 hover:bg-blue-100 hover:shadow-md' 
                            ?>" 
                            data-status="izin"
                            aria-label="Tandai <?= htmlspecialchars($student['nama']) ?> izin">
                        <div class="flex flex-col items-center justify-center py-4 px-2">
                            <svg class="w-6 h-6 mb-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Izin</span>
                        </div>
                        <div class="absolute inset-0 bg-white opacity-0 group-active:opacity-20 transition-opacity duration-150 rounded-xl"></div>
                    </button>
                    
                    <!-- Alpha Button -->
                    <button type="button" 
                            class="btn-attendance group relative overflow-hidden rounded-xl border-2 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 <?= 
                                $student['status'] === 'alpha' ? 
                                'border-red-500 bg-red-500 text-white shadow-lg shadow-red-200' : 
                                'border-red-200 bg-red-50 text-red-700 hover:border-red-300 hover:bg-red-100 hover:shadow-md' 
                            ?>" 
                            data-status="alpha"
                            aria-label="Tandai <?= htmlspecialchars($student['nama']) ?> alpha">
                        <div class="flex flex-col items-center justify-center py-4 px-2">
                            <svg class="w-6 h-6 mb-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Alpha</span>
                        </div>
                        <div class="absolute inset-0 bg-white opacity-0 group-active:opacity-20 transition-opacity duration-150 rounded-xl"></div>
                    </button>
                </div>
                
                <!-- Notes Section (Hidden by default, can be expanded) -->
                <div class="mt-4 hidden" id="notes-<?= $student['siswa_id'] ?>">
                    <textarea class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              rows="2" 
                              placeholder="Catatan tambahan..."
                              data-siswa-id="<?= $student['siswa_id'] ?>"><?= htmlspecialchars($student['keterangan'] ?? '') ?></textarea>
                </div>
                
                <!-- Quick Actions -->
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" 
                            class="text-xs text-gray-500 hover:text-blue-600 transition-colors duration-200 flex items-center gap-1"
                            onclick="toggleNotes('<?= $student['siswa_id'] ?>')">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Catatan
                    </button>
                    
                    <div class="text-xs text-gray-400">
                        #<?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                    </div>
                </div>
            </div>
            
            <!-- Hidden input for keterangan -->
            <input type="hidden" class="student-keterangan" value="<?= htmlspecialchars($student['keterangan'] ?? '') ?>">
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Enhanced Bottom Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white bg-opacity-95 backdrop-blur-lg border-t border-gray-200 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Progress Indicator -->
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span>Sudah: <span id="completedCount" class="font-semibold">0</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                        <span>Sisa: <span id="remainingCount" class="font-semibold"><?= count($students) ?></span></span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <!-- Save Draft Button -->
                    <button type="button" id="saveDraft" 
                            class="group relative overflow-hidden bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 border border-gray-200 hover:border-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span>Simpan Draft</span>
                        </div>
                    </button>
                    
                    <!-- Main Submit Button -->
                    <button type="button" id="saveAll" 
                            class="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span>Kirim Daftar Hadir</span>
                            <div class="pending-badge hidden absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center border-2 border-white" id="pendingBadge">
                                0
                            </div>
                        </div>
                        
                        <!-- Loading overlay -->
                        <div class="absolute inset-0 bg-white bg-opacity-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                    </button>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-3 bg-gray-200 rounded-full h-2 overflow-hidden">
                <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <?php elseif ($selectedKelas): ?>
    <!-- Empty State for No Students -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Tidak ada siswa ditemukan</h3>
            <p class="text-gray-600 mb-6">Kelas <?= htmlspecialchars($selectedKelas) ?> belum memiliki siswa yang terdaftar atau semua siswa sedang tidak aktif.</p>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                Kelola Data Siswa
            </button>
        </div>
    </div>

    <?php else: ?>
    <!-- Empty State for No Selection -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Mulai Input Absensi</h3>
            <p class="text-gray-600 mb-6">Pilih tanggal dan kelas pada filter di atas untuk memulai proses input absensi siswa.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" onclick="document.getElementById('tanggal').focus()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    Pilih Tanggal
                </button>
                <?php if ($userRole === 'admin'): ?>
                <button type="button" onclick="document.getElementById('kelas').focus()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    Pilih Kelas
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div> <!-- End main content container -->


<!-- Enhanced CSS Styles with Modern Design System -->
<style>
/**
 * ==========================================
 * ENHANCED ATTENDANCE SYSTEM STYLES
 * ==========================================
 * Modern, consistent design system with improved UX
 * Author: Manus AI
 * Version: 2.0
 */

/* CSS Custom Properties for Design System */
:root {
    /* Color Palette */
    --primary-50: #eff6ff;
    --primary-100: #dbeafe;
    --primary-500: #3b82f6;
    --primary-600: #2563eb;
    --primary-700: #1d4ed8;
    
    --success-50: #f0fdf4;
    --success-100: #dcfce7;
    --success-500: #22c55e;
    --success-600: #16a34a;
    
    --warning-50: #fffbeb;
    --warning-100: #fef3c7;
    --warning-500: #f59e0b;
    --warning-600: #d97706;
    
    --error-50: #fef2f2;
    --error-100: #fee2e2;
    --error-500: #ef4444;
    --error-600: #dc2626;
    
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    
    /* Spacing Scale */
    --space-1: 0.25rem;
    --space-2: 0.5rem;
    --space-3: 0.75rem;
    --space-4: 1rem;
    --space-6: 1.5rem;
    --space-8: 2rem;
    --space-12: 3rem;
    
    /* Border Radius */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    
    /* Transitions */
    --transition-fast: 150ms ease-in-out;
    --transition-normal: 300ms ease-in-out;
    --transition-slow: 500ms ease-in-out;
}

/* Global Improvements */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: var(--gray-800);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

/* Enhanced Button Styles */
.btn-attendance {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    user-select: none;
    transition: all var(--transition-normal);
    backdrop-filter: blur(10px);
}

.btn-attendance:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-attendance::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left var(--transition-slow);
}

.btn-attendance:hover::before {
    left: 100%;
}

.btn-attendance:focus {
    outline: none;
    ring: 2px;
    ring-offset: 2px;
}

/* Filter Button Styles */
.filter-btn {
    padding: var(--space-2) var(--space-4);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    background: white;
    color: var(--gray-700);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.filter-btn:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    transform: translateY(-1px);
}

.filter-btn.active {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: white;
    box-shadow: var(--shadow-md);
}

/* Enhanced Card Animations */
.student-card {
    transition: all var(--transition-normal);
    will-change: transform;
}

.student-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-2xl);
}

.student-card.filtered-out {
    opacity: 0.3;
    transform: scale(0.95);
    pointer-events: none;
}

/* Status Indicator Animations */
.status-indicator {
    transition: all var(--transition-normal);
    position: relative;
}

.status-indicator::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 50%;
    background: inherit;
    opacity: 0.3;
    animation: pulse-ring 2s infinite;
}

@keyframes pulse-ring {
    0% {
        transform: scale(1);
        opacity: 0.3;
    }
    50% {
        transform: scale(1.5);
        opacity: 0.1;
    }
    100% {
        transform: scale(2);
        opacity: 0;
    }
}

/* Enhanced Loading States */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Notification System */
.notification {
    position: fixed;
    top: var(--space-6);
    right: var(--space-6);
    z-index: 9999;
    max-width: 400px;
    padding: var(--space-4) var(--space-6);
    border-radius: var(--radius-xl);
    color: white;
    font-weight: 600;
    box-shadow: var(--shadow-2xl);
    transform: translateX(100%);
    transition: transform var(--transition-normal);
    backdrop-filter: blur(10px);
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    background: linear-gradient(135deg, var(--success-500), var(--success-600));
}

.notification.error {
    background: linear-gradient(135deg, var(--error-500), var(--error-600));
}

.notification.warning {
    background: linear-gradient(135deg, var(--warning-500), var(--warning-600));
}

.notification.info {
    background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
}

/* Progress Bar Enhancements */
.progress-bar-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: rgba(0,0,0,0.1);
    z-index: 9998;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
    width: 0%;
    transition: width var(--transition-normal);
    position: relative;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: progress-shine 2s infinite;
}

@keyframes progress-shine {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Enhanced Responsive Design */
@media (max-width: 768px) {
    .student-card {
        margin-bottom: var(--space-4);
    }
    
    .student-card:hover {
        transform: translateY(-4px) scale(1.01);
    }
    
    .btn-attendance {
        padding: var(--space-3);
    }
    
    .btn-attendance svg {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .notification {
        top: var(--space-4);
        right: var(--space-4);
        left: var(--space-4);
        max-width: none;
    }
}

@media (max-width: 640px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    .student-card {
        max-width: 100%;
    }
}

/* Dark Mode Support (Optional) */
@media (prefers-color-scheme: dark) {
    :root {
        --gray-50: #1f2937;
        --gray-100: #374151;
        --gray-200: #4b5563;
        --gray-300: #6b7280;
        --gray-800: #f9fafb;
        --gray-900: #ffffff;
    }
    
    body {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: var(--gray-100);
    }
}

/* Accessibility Improvements */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Focus Visible for Better Keyboard Navigation */
.btn-attendance:focus-visible {
    outline: 2px solid var(--primary-500);
    outline-offset: 2px;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .btn-attendance {
        border-width: 2px;
    }
    
    .student-card {
        border-width: 2px;
    }
}

/* Print Styles */
@media print {
    .fixed,
    .sticky {
        position: static !important;
    }
    
    .student-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .btn-attendance {
        border: 1px solid #000;
        background: white !important;
        color: black !important;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: var(--radius-md);
}

::-webkit-scrollbar-thumb {
    background: var(--gray-300);
    border-radius: var(--radius-md);
    transition: background var(--transition-fast);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--gray-400);
}

/* Selection Styles */
::selection {
    background: var(--primary-100);
    color: var(--primary-800);
}

/* Enhanced Form Styles */
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
    cursor: pointer;
}

input[type="date"]::-webkit-calendar-picker-indicator:hover {
    filter: invert(0.3);
}

/* Improved Button Hover Effects */
button:not(:disabled):hover {
    transform: translateY(-1px);
}

button:not(:disabled):active {
    transform: translateY(0);
}

/* Enhanced Card Interactions */
.student-card:active {
    transform: translateY(-4px) scale(1.01);
}

/* Smooth Page Transitions */
.page-transition {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease-out forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading Skeleton */
.skeleton {
    background: linear-gradient(90deg, var(--gray-200) 25%, var(--gray-100) 50%, var(--gray-200) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>


<!-- Enhanced JavaScript with Modern ES6+ Features and Better Organization -->
<script>
/**
 * ==========================================
 * ENHANCED ATTENDANCE SYSTEM JAVASCRIPT
 * ==========================================
 * Modern, modular JavaScript with improved UX and functionality
 * Author: Manus AI
 * Version: 2.0
 */

// Modern JavaScript Class-based Architecture
class AttendanceManager {
    constructor() {
        this.attendanceData = new Map();
        this.students = [];
        this.isLoading = false;
        this.autoSaveInterval = null;
        this.searchTimeout = null;
        
        this.init();
    }
    
    /**
     * Initialize the attendance manager
     */
    init() {
        this.loadExistingData();
        this.bindEvents();
        this.updateCounters();
        this.initializeAutoSave();
        this.setupKeyboardShortcuts();
        this.setupAccessibility();
        
        // Add page transition effect
        document.body.classList.add('page-transition');
        
        console.log('AttendanceManager initialized with', this.students.length, 'students');
    }
    
    /**
     * Load existing attendance data from the DOM
     */
    loadExistingData() {
        const studentCards = document.querySelectorAll('[data-siswa-id]');
        
        studentCards.forEach(card => {
            const siswaId = card.dataset.siswaId;
            const activeButton = card.querySelector('.btn-attendance.border-green-500, .btn-attendance.border-yellow-500, .btn-attendance.border-blue-500, .btn-attendance.border-red-500');
            
            const studentData = {
                id: siswaId,
                name: card.dataset.studentName,
                nisn: card.dataset.studentNisn,
                element: card
            };
            
            this.students.push(studentData);
            
            if (activeButton) {
                const status = activeButton.dataset.status;
                const keterangan = card.querySelector('.student-keterangan')?.value || '';
                
                this.attendanceData.set(siswaId, {
                    status: status,
                    keterangan: keterangan,
                    timestamp: new Date().toISOString()
                });
            }
        });
    }
    
    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Attendance button clicks with event delegation
        document.addEventListener('click', this.handleAttendanceClick.bind(this));
        
        // Mark all present
        const markAllBtn = document.getElementById('markAllPresent');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', this.markAllPresent.bind(this));
        }
        
        // Date navigation
        const prevDayBtn = document.getElementById('prevDay');
        const nextDayBtn = document.getElementById('nextDay');
        
        if (prevDayBtn) prevDayBtn.addEventListener('click', () => this.navigateDate(-1));
        if (nextDayBtn) nextDayBtn.addEventListener('click', () => this.navigateDate(1));
        
        // Form auto-submit
        const dateInput = document.getElementById('tanggal');
        const kelasSelect = document.getElementById('kelas');
        
        if (dateInput) {
            dateInput.addEventListener('change', this.debounce(() => {
                this.submitForm();
            }, 300));
        }
        
        if (kelasSelect) {
            kelasSelect.addEventListener('change', this.debounce(() => {
                this.submitForm();
            }, 300));
        }
        
        // Search functionality
        const searchInput = document.getElementById('searchStudent');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.filterStudents(e.target.value);
            }, 300));
        }
        
        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.handleFilterClick(e.target);
            });
        });
        
        // Save actions
        const saveAllBtn = document.getElementById('saveAll');
        const saveDraftBtn = document.getElementById('saveDraft');
        const exportBtn = document.getElementById('exportData');
        
        if (saveAllBtn) saveAllBtn.addEventListener('click', this.saveAllAttendance.bind(this));
        if (saveDraftBtn) saveDraftBtn.addEventListener('click', this.saveDraft.bind(this));
        if (exportBtn) exportBtn.addEventListener('click', this.exportData.bind(this));
        
        // Window events
        window.addEventListener('beforeunload', this.handleBeforeUnload.bind(this));
        window.addEventListener('online', this.handleOnline.bind(this));
        window.addEventListener('offline', this.handleOffline.bind(this));
    }
    
    /**
     * Handle attendance button clicks
     */
    handleAttendanceClick(e) {
        const button = e.target.closest('.btn-attendance');
        if (!button) return;
        
        e.preventDefault();
        
        const card = button.closest('[data-siswa-id]');
        const siswaId = card.dataset.siswaId;
        const status = button.dataset.status;
        const studentName = card.querySelector('h3').textContent.trim();
        
        // Haptic feedback for mobile devices
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
        
        // Update UI with smooth animation
        this.updateAttendanceUI(card, status);
        
        // Update data
        this.attendanceData.set(siswaId, {
            status: status,
            keterangan: card.querySelector('.student-keterangan')?.value || '',
            timestamp: new Date().toISOString()
        });
        
        // Update counters
        this.updateCounters();
        
        // Show feedback
        this.showMicroFeedback(button, status);
        
        // Auto-save after delay
        this.scheduleAutoSave();
        
        // Accessibility announcement
        this.announceToScreenReader(`${studentName} ditandai ${status}`);
        
        console.log(`Updated attendance for ${studentName}:`, this.attendanceData.get(siswaId));
    }
    
    /**
     * Update attendance UI with smooth animations
     */
    updateAttendanceUI(card, activeStatus) {
        const buttons = card.querySelectorAll('.btn-attendance');
        
        buttons.forEach(btn => {
            const btnStatus = btn.dataset.status;
            const isActive = btnStatus === activeStatus;
            
            // Remove all status classes
            btn.className = btn.className.replace(/border-\w+-\d+|bg-\w+-\d+|text-\w+|shadow-\w+/g, '');
            
            // Add base classes
            btn.classList.add('btn-attendance', 'group', 'relative', 'overflow-hidden', 'rounded-xl', 'border-2', 'transition-all', 'duration-300', 'transform', 'hover:scale-105', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2');
            
            // Add status-specific classes
            if (isActive) {
                this.addActiveClasses(btn, btnStatus);
                // Add success animation
                btn.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    btn.style.transform = '';
                }, 150);
            } else {
                this.addInactiveClasses(btn, btnStatus);
            }
        });
    }
    
    /**
     * Add active state classes based on status
     */
    addActiveClasses(btn, status) {
        const statusClasses = {
            hadir: ['border-green-500', 'bg-green-500', 'text-white', 'shadow-lg', 'shadow-green-200', 'focus:ring-green-500'],
            sakit: ['border-yellow-500', 'bg-yellow-500', 'text-white', 'shadow-lg', 'shadow-yellow-200', 'focus:ring-yellow-500'],
            izin: ['border-blue-500', 'bg-blue-500', 'text-white', 'shadow-lg', 'shadow-blue-200', 'focus:ring-blue-500'],
            alpha: ['border-red-500', 'bg-red-500', 'text-white', 'shadow-lg', 'shadow-red-200', 'focus:ring-red-500']
        };
        
        btn.classList.add(...statusClasses[status]);
    }
    
    /**
     * Add inactive state classes based on status
     */
    addInactiveClasses(btn, status) {
        const statusClasses = {
            hadir: ['border-green-200', 'bg-green-50', 'text-green-700', 'hover:border-green-300', 'hover:bg-green-100', 'hover:shadow-md', 'focus:ring-green-500'],
            sakit: ['border-yellow-200', 'bg-yellow-50', 'text-yellow-700', 'hover:border-yellow-300', 'hover:bg-yellow-100', 'hover:shadow-md', 'focus:ring-yellow-500'],
            izin: ['border-blue-200', 'bg-blue-50', 'text-blue-700', 'hover:border-blue-300', 'hover:bg-blue-100', 'hover:shadow-md', 'focus:ring-blue-500'],
            alpha: ['border-red-200', 'bg-red-50', 'text-red-700', 'hover:border-red-300', 'hover:bg-red-100', 'hover:shadow-md', 'focus:ring-red-500']
        };
        
        btn.classList.add(...statusClasses[status]);
    }
    
    /**
     * Show micro-feedback for button interaction
     */
    showMicroFeedback(button, status) {
        const feedback = document.createElement('div');
        feedback.className = 'absolute inset-0 bg-white opacity-30 rounded-xl pointer-events-none';
        feedback.style.animation = 'flash 0.3s ease-out';
        
        button.style.position = 'relative';
        button.appendChild(feedback);
        
        setTimeout(() => {
            if (feedback.parentNode) {
                feedback.remove();
            }
        }, 300);
        
        // Add CSS for flash animation if not exists
        if (!document.querySelector('#flash-animation-style')) {
            const style = document.createElement('style');
            style.id = 'flash-animation-style';
            style.textContent = `
                @keyframes flash {
                    0% { opacity: 0; transform: scale(1); }
                    50% { opacity: 0.3; transform: scale(1.05); }
                    100% { opacity: 0; transform: scale(1); }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    /**
     * Mark all students as present with staggered animation
     */
    async markAllPresent() {
        const button = document.getElementById('markAllPresent');
        const originalHTML = button.innerHTML;
        
        // Show loading state
        button.innerHTML = `
            <div class="flex items-center justify-center gap-3">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Memproses...</span>
            </div>
        `;
        button.disabled = true;
        
        // Process each student with staggered animation
        const studentCards = document.querySelectorAll('[data-siswa-id]');
        const promises = [];
        
        studentCards.forEach((card, index) => {
            const promise = new Promise(resolve => {
                setTimeout(() => {
                    const siswaId = card.dataset.siswaId;
                    const studentName = card.querySelector('h3').textContent.trim();
                    
                    // Update UI
                    this.updateAttendanceUI(card, 'hadir');
                    
                    // Update data
                    this.attendanceData.set(siswaId, {
                        status: 'hadir',
                        keterangan: '',
                        timestamp: new Date().toISOString()
                    });
                    
                    // Add wave animation
                    card.style.transform = 'scale(1.02)';
                    card.style.background = 'linear-gradient(135deg, #ecfdf5, #d1fae5)';
                    
                    setTimeout(() => {
                        card.style.transform = '';
                        card.style.background = '';
                    }, 300);
                    
                    resolve();
                }, index * 100); // Stagger by 100ms
            });
            
            promises.push(promise);
        });
        
        // Wait for all animations to complete
        await Promise.all(promises);
        
        // Update counters
        this.updateCounters();
        
        // Restore button
        button.innerHTML = originalHTML;
        button.disabled = false;
        
        // Show success notification
        this.showNotification('Semua siswa telah ditandai hadir!', 'success');
        
        // Confetti effect if available
        if (typeof confetti !== 'undefined') {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        }
        
        // Auto-save
        this.scheduleAutoSave();
    }
    
    /**
     * Navigate to previous/next day
     */
    navigateDate(direction) {
        const dateInput = document.getElementById('tanggal');
        if (!dateInput) return;
        
        const currentDate = new Date(dateInput.value);
        currentDate.setDate(currentDate.getDate() + direction);
        
        const newDate = currentDate.toISOString().split('T')[0];
        dateInput.value = newDate;
        
        // Add loading state
        this.showLoadingState();
        
        // Submit form with delay for better UX
        setTimeout(() => {
            this.submitForm();
        }, 300);
    }
    
    /**
     * Filter students based on search query
     */
    filterStudents(query) {
        const normalizedQuery = query.toLowerCase().trim();
        const studentCards = document.querySelectorAll('[data-siswa-id]');
        
        studentCards.forEach(card => {
            const name = card.dataset.studentName || '';
            const nisn = card.dataset.studentNisn || '';
            
            const matches = name.includes(normalizedQuery) || nisn.includes(normalizedQuery);
            
            if (matches || !normalizedQuery) {
                card.classList.remove('filtered-out');
                card.style.display = '';
            } else {
                card.classList.add('filtered-out');
                setTimeout(() => {
                    if (card.classList.contains('filtered-out')) {
                        card.style.display = 'none';
                    }
                }, 300);
            }
        });
        
        // Update visible count
        const visibleCount = document.querySelectorAll('[data-siswa-id]:not(.filtered-out)').length;
        this.announceToScreenReader(`${visibleCount} siswa ditemukan`);
    }
    
    /**
     * Handle filter button clicks
     */
    handleFilterClick(button) {
        const filter = button.dataset.filter;
        
        // Update active state
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
        
        // Apply filter
        this.applyStatusFilter(filter);
    }
    
    /**
     * Apply status-based filter
     */
    applyStatusFilter(filter) {
        const studentCards = document.querySelectorAll('[data-siswa-id]');
        
        studentCards.forEach(card => {
            const siswaId = card.dataset.siswaId;
            const attendanceRecord = this.attendanceData.get(siswaId);
            const currentStatus = attendanceRecord?.status || 'belum';
            
            let shouldShow = false;
            
            switch (filter) {
                case 'all':
                    shouldShow = true;
                    break;
                case 'belum':
                    shouldShow = !attendanceRecord;
                    break;
                default:
                    shouldShow = currentStatus === filter;
                    break;
            }
            
            if (shouldShow) {
                card.classList.remove('filtered-out');
                card.style.display = '';
            } else {
                card.classList.add('filtered-out');
                setTimeout(() => {
                    if (card.classList.contains('filtered-out')) {
                        card.style.display = 'none';
                    }
                }, 300);
            }
        });
    }
    
    /**
     * Update attendance counters
     */
    updateCounters() {
        const totalStudents = this.students.length;
        const attendedCount = this.attendanceData.size;
        const remainingCount = totalStudents - attendedCount;
        
        // Update header stats
        const attendedElement = document.getElementById('attendedCount');
        const pendingElement = document.getElementById('pendingCount');
        
        if (attendedElement) {
            this.animateNumber(attendedElement, attendedCount);
        }
        
        if (pendingElement) {
            this.animateNumber(pendingElement, remainingCount);
        }
        
        // Update bottom bar stats
        const completedElement = document.getElementById('completedCount');
        const remainingElement = document.getElementById('remainingCount');
        
        if (completedElement) {
            this.animateNumber(completedElement, attendedCount);
        }
        
        if (remainingElement) {
            this.animateNumber(remainingElement, remainingCount);
        }
        
        // Update progress bar
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            const percentage = totalStudents > 0 ? (attendedCount / totalStudents) * 100 : 0;
            progressBar.style.width = `${percentage}%`;
        }
        
        // Update pending badge
        const pendingBadge = document.getElementById('pendingBadge');
        if (pendingBadge) {
            if (attendedCount > 0) {
                pendingBadge.textContent = attendedCount;
                pendingBadge.classList.remove('hidden');
                pendingBadge.style.animation = 'pulse 2s infinite';
            } else {
                pendingBadge.classList.add('hidden');
                pendingBadge.style.animation = '';
            }
        }
    }
    
    /**
     * Animate number changes
     */
    animateNumber(element, targetValue) {
        const currentValue = parseInt(element.textContent) || 0;
        const increment = targetValue > currentValue ? 1 : -1;
        const duration = 300;
        const steps = Math.abs(targetValue - currentValue);
        const stepDuration = steps > 0 ? duration / steps : 0;
        
        let current = currentValue;
        
        const timer = setInterval(() => {
            current += increment;
            element.textContent = current;
            
            if (current === targetValue) {
                clearInterval(timer);
            }
        }, stepDuration);
    }
    
    /**
     * Save all attendance data
     */
    async saveAllAttendance() {
        const button = document.getElementById('saveAll');
        const originalHTML = button.innerHTML;
        
        // Validation
        if (this.attendanceData.size === 0) {
            this.showNotification('Silakan pilih status kehadiran untuk setidaknya satu siswa', 'warning');
            return;
        }
        
        const totalStudents = this.students.length;
        const selectedStudents = this.attendanceData.size;
        
        if (selectedStudents < totalStudents) {
            const remaining = totalStudents - selectedStudents;
            const confirmed = await this.showConfirmDialog(
                `Masih ada ${remaining} siswa yang belum dipilih status kehadirannya. Lanjutkan menyimpan?`
            );
            
            if (!confirmed) return;
        }
        
        // Show loading state
        this.setButtonLoading(button, 'Menyimpan...');
        
        // Create progress indicator
        const progressIndicator = this.createProgressIndicator();
        
        try {
            // Prepare data
            const formData = new FormData();
            formData.append('tanggal', document.getElementById('tanggal').value);
            formData.append('kelas', document.getElementById('kelas')?.value || '<?= $userKelas ?? '' ?>');
            
            const attendanceArray = Array.from(this.attendanceData.entries()).map(([siswaId, data]) => ({
                siswa_id: siswaId,
                status: data.status,
                keterangan: data.keterangan || ''
            }));
            
            formData.append('attendance_data', JSON.stringify(attendanceArray));
            
            console.log('Submitting attendance data:', {
                tanggal: document.getElementById('tanggal').value,
                kelas: document.getElementById('kelas')?.value || '<?= $userKelas ?? '' ?>',
                attendanceArray: attendanceArray
            });
            
            // Submit data
            const response = await fetch('<?= base_url('admin/absensi/save_all') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            progressIndicator.complete();
            
            if (data.success) {
                // Success animation
                await this.showSuccessAnimation();
                
                this.showNotification('Data absensi berhasil disimpan!', 'success');
                
                // Reset data
                this.attendanceData.clear();
                this.updateCounters();
                
                // Confetti effect
                if (typeof confetti !== 'undefined') {
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 }
                    });
                }
                
                // Clear auto-save
                this.clearAutoSave();
                
            } else {
                this.showNotification(data.message || 'Gagal menyimpan data', 'error');
            }
            
        } catch (error) {
            console.error('Save error:', error);
            progressIndicator.complete();
            this.showNotification('Gagal menyimpan data: ' + error.message, 'error');
        } finally {
            // Restore button
            setTimeout(() => {
                this.restoreButton(button, originalHTML);
            }, 1000);
        }
    }
    
    /**
     * Show success animation
     */
    async showSuccessAnimation() {
        const studentCards = document.querySelectorAll('[data-siswa-id]');
        const promises = [];
        
        studentCards.forEach((card, index) => {
            const promise = new Promise(resolve => {
                setTimeout(() => {
                    card.style.background = 'linear-gradient(135deg, #ecfdf5, #d1fae5)';
                    card.style.transform = 'scale(1.02)';
                    
                    setTimeout(() => {
                        card.style.transform = '';
                        card.style.background = '';
                        resolve();
                    }, 200);
                }, index * 30);
            });
            
            promises.push(promise);
        });
        
        await Promise.all(promises);
    }
    
    /**
     * Utility Functions
     */
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icon = this.getNotificationIcon(type);
        
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                ${icon}
                <span class="flex-1">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
    
    getNotificationIcon(type) {
        const icons = {
            success: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            error: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            warning: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
            info: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        };
        
        return icons[type] || icons.info;
    }
    
    createProgressIndicator() {
        const container = document.createElement('div');
        container.className = 'progress-bar-container';
        
        const bar = document.createElement('div');
        bar.className = 'progress-bar';
        
        container.appendChild(bar);
        document.body.appendChild(container);
        
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress > 90) progress = 90;
            bar.style.width = progress + '%';
        }, 200);
        
        return {
            complete: () => {
                clearInterval(interval);
                bar.style.width = '100%';
                setTimeout(() => {
                    container.remove();
                }, 500);
            }
        };
    }
    
    setButtonLoading(button, text) {
        button.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>${text}</span>
            </div>
        `;
        button.disabled = true;
        button.classList.add('opacity-75');
    }
    
    restoreButton(button, originalHTML) {
        button.innerHTML = originalHTML;
        button.disabled = false;
        button.classList.remove('opacity-75');
    }
    
    showConfirmDialog(message) {
        return new Promise(resolve => {
            const confirmed = confirm(message);
            resolve(confirmed);
        });
    }
    
    submitForm() {
        const form = document.getElementById('filterForm');
        if (form) {
            form.submit();
        }
    }
    
    showLoadingState() {
        document.body.style.opacity = '0.7';
        document.body.style.pointerEvents = 'none';
    }
    
    announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        
        setTimeout(() => {
            announcement.remove();
        }, 1000);
    }
    
    // Additional methods for enhanced functionality
    initializeAutoSave() {
        this.autoSaveInterval = setInterval(() => {
            if (this.attendanceData.size > 0) {
                this.saveDraft();
            }
        }, 30000); // Auto-save every 30 seconds
    }
    
    clearAutoSave() {
        if (this.autoSaveInterval) {
            clearInterval(this.autoSaveInterval);
            this.autoSaveInterval = null;
        }
    }
    
    scheduleAutoSave() {
        clearTimeout(this.autoSaveTimeout);
        this.autoSaveTimeout = setTimeout(() => {
            this.saveDraft();
        }, 5000);
    }
    
    saveDraft() {
        if (this.attendanceData.size === 0) return;
        
        const draftData = {
            timestamp: new Date().toISOString(),
            data: Array.from(this.attendanceData.entries())
        };
        
        localStorage.setItem('attendance_draft', JSON.stringify(draftData));
        console.log('Draft saved to localStorage');
    }
    
    loadDraft() {
        const draft = localStorage.getItem('attendance_draft');
        if (draft) {
            try {
                const draftData = JSON.parse(draft);
                // Load draft data if it's from today
                const draftDate = new Date(draftData.timestamp).toDateString();
                const today = new Date().toDateString();
                
                if (draftDate === today) {
                    this.attendanceData = new Map(draftData.data);
                    this.updateCounters();
                    console.log('Draft loaded from localStorage');
                }
            } catch (error) {
                console.error('Error loading draft:', error);
            }
        }
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.saveAllAttendance();
            }
            
            // Ctrl/Cmd + A to mark all present
            if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.shiftKey) {
                e.preventDefault();
                this.markAllPresent();
            }
            
            // Escape to clear search
            if (e.key === 'Escape') {
                const searchInput = document.getElementById('searchStudent');
                if (searchInput && searchInput.value) {
                    searchInput.value = '';
                    this.filterStudents('');
                }
            }
        });
    }
    
    setupAccessibility() {
        // Add ARIA labels and roles
        document.querySelectorAll('.btn-attendance').forEach(btn => {
            if (!btn.getAttribute('aria-label')) {
                const status = btn.dataset.status;
                const card = btn.closest('[data-siswa-id]');
                const studentName = card.querySelector('h3').textContent.trim();
                btn.setAttribute('aria-label', `Tandai ${studentName} ${status}`);
            }
        });
        
        // Add role and aria-live to counters
        const counters = document.querySelectorAll('#attendedCount, #pendingCount, #completedCount, #remainingCount');
        counters.forEach(counter => {
            counter.setAttribute('aria-live', 'polite');
            counter.setAttribute('role', 'status');
        });
    }
    
    exportData() {
        const data = Array.from(this.attendanceData.entries()).map(([siswaId, attendance]) => {
            const student = this.students.find(s => s.id === siswaId);
            return {
                siswa_id: siswaId,
                nama: student?.name || '',
                nisn: student?.nisn || '',
                status: attendance.status,
                keterangan: attendance.keterangan,
                timestamp: attendance.timestamp
            };
        });
        
        const csv = this.convertToCSV(data);
        this.downloadCSV(csv, `absensi_${new Date().toISOString().split('T')[0]}.csv`);
    }
    
    convertToCSV(data) {
        if (data.length === 0) return '';
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${row[header]}"`).join(','))
        ].join('\n');
        
        return csvContent;
    }
    
    downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
    
    handleBeforeUnload(e) {
        if (this.attendanceData.size > 0) {
            e.preventDefault();
            e.returnValue = 'Anda memiliki data absensi yang belum disimpan. Yakin ingin meninggalkan halaman?';
        }
    }
    
    handleOnline() {
        this.showNotification('Koneksi internet tersambung kembali', 'success');
    }
    
    handleOffline() {
        this.showNotification('Koneksi internet terputus. Data akan disimpan secara lokal.', 'warning');
    }
}

// Global function for toggling notes
function toggleNotes(siswaId) {
    const notesElement = document.getElementById(`notes-${siswaId}`);
    if (notesElement) {
        notesElement.classList.toggle('hidden');
        
        if (!notesElement.classList.contains('hidden')) {
            const textarea = notesElement.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        }
    }
}

// Initialize the attendance manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.attendanceManager = new AttendanceManager();
    
    // Load draft if available
    window.attendanceManager.loadDraft();
    
    console.log('Enhanced Attendance System v2.0 initialized successfully');
});

// Service Worker registration for PWA capabilities (optional)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
</script>

<?= $this->endSection() ?>

