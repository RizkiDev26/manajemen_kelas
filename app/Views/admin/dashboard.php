<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Page Header - Enhanced Mobile -->
<div class="mb-8 sm:mb-10 lg:mb-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-6 sm:gap-8">
        <div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">Dashboard</h1>
            <p class="text-gray-600 text-xl sm:text-2xl leading-relaxed">Selamat datang kembali, <span class="font-semibold text-blue-600"><?= esc($currentUser['nama']) ?></span>! Berikut adalah ringkasan sekolah hari ini.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <!-- Refresh Button - Mobile Optimized -->
            <button id="refreshDashboard" class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2 text-sm sm:text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 touch-manipulation">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Refresh Data</span>
            </button>
            
            <!-- Last Updated - Mobile Optimized -->
            <div class="text-sm sm:text-base text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                <span class="font-medium">Terakhir diperbarui:</span>
                <span id="lastUpdated" class="ml-1"><?= $lastUpdated ?? date('d M Y, H:i') ?></span>
                <?php if (isset($fromCache) && $fromCache): ?>
                    <span class="text-blue-600 ml-2 font-medium">(cache)</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Error Alert - Mobile Optimized -->
    <?php if (isset($dbError) && $dbError): ?>
    <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-xl p-4 shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-base font-semibold text-yellow-800">Peringatan Database</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p><?= esc($errorMessage) ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Statistics Cards - Mobile Optimized -->
<div class="px-4 sm:px-6 lg:px-8 mb-6 sm:mb-8 lg:mb-10">
    <?php if (isset($isWalikelas) && $isWalikelas): ?>
    <!-- Walikelas Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Teacher Name Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="text-blue-600 text-sm font-semibold bg-blue-50 px-3 py-1.5 rounded-full">Wali Kelas</span>
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2"><?= esc($currentUser['nama']) ?></h3>
            <p class="text-gray-500 text-sm sm:text-base"><?= isset($walikelasInfo) ? 'Kelas: ' . esc($walikelasInfo['kelas']) : 'Nama Guru' ?></p>
        </div>

        <!-- Male Students Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="text-blue-600 text-sm font-semibold bg-blue-50 px-3 py-1.5 rounded-full">♂</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?= $siswaLaki ?></h3>
            <p class="text-gray-500 text-sm sm:text-base">Siswa Laki-laki</p>
        </div>

        <!-- Female Students Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-pink-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="text-pink-600 text-sm font-semibold bg-pink-50 px-3 py-1.5 rounded-full">♀</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?= $siswaPerempuan ?></h3>
            <p class="text-gray-500 text-sm sm:text-base">Siswa Perempuan</p>
        </div>

        <!-- Daily Progress Card (Attendance + Habit Logs) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-green-600 text-sm font-semibold bg-green-50 px-3 py-1.5 rounded-full">Hari Ini</span>
            </div>
            <?php $dp = $dailyProgress ?? null; ?>
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">
                <?= $dp ? $dp['attendance_present'] : 0 ?>/<?= $dp ? $dp['total_students'] : 0 ?> Hadir
            </h3>
            <div class="space-y-3">
                <!-- Attendance Progress -->
                <div>
                    <div class="flex justify-between text-xs sm:text-sm text-gray-600 mb-1">
                        <span>Kehadiran</span>
                        <span><?= $dp ? $dp['attendance_percentage'] : 0 ?>%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-3 bg-green-500 rounded-full transition-all" style="width: <?= $dp && $dp['attendance_percentage']>0 ? $dp['attendance_percentage'] : 0 ?>%"></div>
                    </div>
                </div>
                <!-- Habit Log Progress -->
                <div>
                    <div class="flex justify-between text-xs sm:text-sm text-gray-600 mb-1">
                        <span>Kebiasaan</span>
                        <span><?= $dp ? $dp['habit_percentage'] : 0 ?>%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-3 bg-blue-500 rounded-full transition-all" style="width: <?= $dp && $dp['habit_percentage']>0 ? $dp['habit_percentage'] : 0 ?>%"></div>
                    </div>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500">Sudah input kebiasaan <?= $dp ? $dp['habit_logged'] : 0 ?> dari <?= $dp ? $dp['total_students'] : 0 ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Admin Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Total Teachers Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-green-600 text-sm font-semibold bg-green-50 px-3 py-1.5 rounded-full">Active</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?= $totalGuru ?></h3>
            <p class="text-gray-500 text-sm sm:text-base">Total Guru</p>
        </div>

        <!-- Total Siswa Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-green-600 text-sm font-semibold bg-green-50 px-3 py-1.5 rounded-full">Active</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?= $totalSiswa ?></h3>
            <p class="text-gray-500 text-sm sm:text-base">Total Siswa</p>
            <div class="flex items-center mt-2 space-x-4 text-sm sm:text-base">
                <span class="text-blue-600 font-semibold">♂ <?= $siswaLaki ?></span>
                <span class="text-pink-600 font-semibold">♀ <?= $siswaPerempuan ?></span>
            </div>
        </div>

        <!-- Total Wali Kelas Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-green-600 text-sm font-semibold bg-green-50 px-3 py-1.5 rounded-full">Active</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?= $totalWalikelas ?></h3>
            <p class="text-gray-500 text-sm sm:text-base">Total Wali Kelas</p>
        </div>

        <!-- Total Users Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-green-600 text-sm font-semibold bg-green-50 px-3 py-1.5 rounded-full">Active</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?= $totalUsers ?></h3>
            <p class="text-gray-500 text-sm sm:text-base">Total Users</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Charts Section - Enhanced Mobile -->
<div class="px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 lg:mb-12">
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 sm:gap-8">
        <!-- Attendance Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8 gap-4">
                <div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900">Kehadiran Mingguan</h3>
                    <p class="text-gray-500 text-base sm:text-lg mt-2">
                    <?php if (isset($attendanceData['period']) && isset($attendanceData['period']['week_start'])): ?>
                        <?= date('d M Y', strtotime($attendanceData['period']['week_start'])) ?> - <?= date('d M Y', strtotime($attendanceData['period']['week_end'])) ?>
                    <?php elseif (isset($attendanceData['period']) && isset($attendanceData['period']['start_date'])): ?>
                        <?= date('d M Y', strtotime($attendanceData['period']['start_date'])) ?> - <?= date('d M Y', strtotime($attendanceData['period']['end_date'])) ?>
                    <?php else: ?>
                        Minggu ini
                    <?php endif; ?>
                </p>
            </div>
                <div class="flex space-x-4 sm:space-x-6">
                    <button class="text-blue-600 text-base sm:text-lg font-semibold bg-blue-50 px-4 py-2 rounded-lg">Minggu</button>
                    <button class="text-gray-500 text-base sm:text-lg font-medium hover:text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50">Bulan</button>
                </div>
            </div>
            
            <!-- Real Chart with Data -->
            <div class="h-64 sm:h-80 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl flex items-end justify-center space-x-3 sm:space-x-4 p-6 sm:p-8" id="weeklyChart">
            <?php if (isset($attendanceData['weekly']) && !empty($attendanceData['weekly'])): ?>
                <?php foreach ($attendanceData['weekly'] as $day): ?>
                    <div class="flex flex-col items-center space-y-2">
                        <div class="bg-blue-500 rounded-t-md transition-all duration-300 hover:bg-blue-600" 
                             style="width: 32px; height: <?= max(8, ($day['percentage'] * 2)) ?>px;"
                             title="<?= $day['day'] ?>: <?= $day['hadir'] ?>/<?= $day['total'] ?> (<?= $day['percentage'] ?>%)">
                        </div>
                        <span class="text-xs text-gray-600 font-medium"><?= $day['day'] ?></span>
                        <span class="text-xs text-gray-500"><?= $day['percentage'] ?>%</span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback chart -->
                <div class="bg-blue-500 w-8 h-32 rounded-t-md"></div>
                <div class="bg-blue-400 w-8 h-24 rounded-t-md"></div>
                <div class="bg-blue-500 w-8 h-40 rounded-t-md"></div>
                <div class="bg-blue-300 w-8 h-20 rounded-t-md"></div>
                <div class="bg-blue-500 w-8 h-36 rounded-t-md"></div>
                <div class="bg-blue-400 w-8 h-28 rounded-t-md"></div>
                <div class="bg-blue-500 w-8 h-44 rounded-t-md"></div>
            <?php endif; ?>
        </div>
        
        <!-- Chart Legend -->
        <div class="mt-4 flex items-center justify-center space-x-6">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-sm text-gray-600">Kehadiran</span>
            </div>
            <?php if (isset($attendanceData['monthly'])): ?>
                <div class="text-sm text-gray-500">
                    Total bulan ini: <?= $attendanceData['monthly']['total_hadir'] ?>/<?= $attendanceData['monthly']['total_records'] ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Weekly Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <div>
                <h3 class="text-base md:text-lg font-semibold text-gray-900">Detail Kehadiran Minggu Ini</h3>
                <p class="text-gray-500 text-xs md:text-sm">Persentase per hari</p>
            </div>
        </div>
        
        <div class="space-y-4" id="weeklyDetails">
            <?php if (isset($attendanceData['weekly']) && !empty($attendanceData['weekly'])): ?>
                <?php foreach ($attendanceData['weekly'] as $day): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 w-8"><?= $day['day'] ?></span>
                        <div class="flex-1 mx-4">
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     style="width: <?= $day['percentage'] ?>%"></div>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 w-12 text-right"><?= $day['hadir'] ?>/<?= $day['total'] ?></span>
                        <span class="text-sm text-gray-500 w-12 text-right"><?= $day['percentage'] ?>%</span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback data -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">M</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">85</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">T</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">70</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">W</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">90</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">T</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">65</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">F</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">80</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">S</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">95</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">S</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 88%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-8 text-right">88</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary -->
        <?php if (isset($attendanceData['monthly'])): ?>
        <div class="mt-4 md:mt-6 p-3 md:p-4 bg-gray-50 rounded-lg">
            <h4 class="text-xs md:text-sm font-medium text-gray-900 mb-3">Ringkasan Bulan Ini</h4>
            <div class="grid grid-cols-2 gap-3 md:gap-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Hadir: <?= $attendanceData['monthly']['total_hadir'] ?></span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Sakit: <?= $attendanceData['monthly']['total_sakit'] ?></span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Izin: <?= $attendanceData['monthly']['total_izin'] ?></span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Alpha: <?= $attendanceData['monthly']['total_alpha'] ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-6">
    <!-- Recent Teachers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <h3 class="text-base md:text-lg font-semibold text-gray-900">Guru Terbaru</h3>
            <a href="/admin/data-guru" class="text-blue-600 text-xs md:text-sm font-medium hover:text-blue-700">Lihat Semua</a>
        </div>
        
        <div class="space-y-4">
            <?php if (!empty($recentGuru)): ?>
                <?php foreach ($recentGuru as $guru): ?>
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900"><?= esc($guru['nama']) ?></p>
                        <p class="text-xs text-gray-500">
                            <?= !empty($guru['nip']) ? 'NIP: ' . esc($guru['nip']) : '' ?>
                            <?= !empty($guru['jenis_ptk']) ? (!empty($guru['nip']) ? ' • ' : '') . esc($guru['jenis_ptk']) : '' ?>
                        </p>
                    </div>
                    <span class="text-xs text-gray-400"><?= date('d M', strtotime($guru['created_at'])) ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada data guru</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Students -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <h3 class="text-base md:text-lg font-semibold text-gray-900">Siswa Terbaru</h3>
            <a href="/admin/data-siswa" class="text-orange-600 text-xs md:text-sm font-medium hover:text-orange-700">Lihat Semua</a>
        </div>
        
        <div class="space-y-4">
            <?php if (!empty($recentSiswa)): ?>
                <?php foreach ($recentSiswa as $siswa): ?>
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900"><?= esc($siswa['nama']) ?></p>
                        <p class="text-xs text-gray-500">
                            <?= !empty($siswa['nisn']) ? 'NISN: ' . esc($siswa['nisn']) : '' ?>
                            <?= !empty($siswa['kelas']) ? ' • ' . esc($siswa['kelas']) : '' ?>
                            <?= !empty($siswa['jk']) ? ' • ' . ($siswa['jk'] == 'L' ? 'Laki-laki' : 'Perempuan') : '' ?>
                        </p>
                    </div>
                    <span class="text-xs text-gray-400"><?= date('d M', strtotime($siswa['created_at'])) ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada data siswa</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript for Dashboard Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refreshDashboard');
    const lastUpdatedSpan = document.getElementById('lastUpdated');
    
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            refreshDashboard();
        });
    }
    
    function refreshDashboard() {
        // Show loading state
        const originalContent = refreshBtn.innerHTML;
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = `
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memperbarui...</span>
        `;
        
        // Make AJAX request
        fetch('<?= base_url('admin/dashboard/refresh') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update last updated time
                if (lastUpdatedSpan) {
                    lastUpdatedSpan.textContent = data.data.lastUpdated;
                }
                
                // Update statistics cards
                updateStatisticsCards(data.data);
                
                // Update charts
                updateCharts(data.data);
                
                // Show success message
                showNotification('Data berhasil diperbarui!', 'success');
                
                // Reload page after short delay to show all updates
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } else {
                showNotification(data.message || 'Gagal memperbarui data', 'error');
            }
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            showNotification('Terjadi kesalahan saat memperbarui data', 'error');
        })
        .finally(() => {
            // Restore button state
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = originalContent;
        });
    }
    
    function updateStatisticsCards(data) {
        // Update card values if elements exist
        const updates = [
            { selector: '.text-2xl.font-bold.text-gray-900', index: 0, value: data.totalGuru },
            { selector: '.text-2xl.font-bold.text-gray-900', index: 1, value: data.totalSiswa },
            { selector: '.text-2xl.font-bold.text-gray-900', index: 2, value: data.totalWalikelas },
            { selector: '.text-2xl.font-bold.text-gray-900', index: 3, value: data.totalUsers }
        ];
        
        updates.forEach(update => {
            const elements = document.querySelectorAll(update.selector);
            if (elements[update.index]) {
                elements[update.index].textContent = update.value;
            }
        });
        
        // Update gender breakdown
        const genderElements = document.querySelectorAll('.text-blue-600.font-medium, .text-pink-600.font-medium');
        if (genderElements.length >= 2) {
            genderElements[0].textContent = `♂ ${data.siswaLaki}`;
            genderElements[1].textContent = `♀ ${data.siswaPerempuan}`;
        }
    }
    
    function updateCharts(data) {
        if (data.attendanceData && data.attendanceData.weekly) {
            updateWeeklyChart(data.attendanceData.weekly);
            updateWeeklyDetails(data.attendanceData.weekly);
        }
    }
    
    function updateWeeklyChart(weeklyData) {
        const chartContainer = document.getElementById('weeklyChart');
        if (!chartContainer) return;
        
        let chartHTML = '';
        weeklyData.forEach(day => {
            const height = Math.max(8, (day.percentage * 2));
            chartHTML += `
                <div class="flex flex-col items-center space-y-2">
                    <div class="bg-blue-500 rounded-t-md transition-all duration-300 hover:bg-blue-600" 
                         style="width: 32px; height: ${height}px;"
                         title="${day.day}: ${day.hadir}/${day.total} (${day.percentage}%)">
                    </div>
                    <span class="text-xs text-gray-600 font-medium">${day.day}</span>
                    <span class="text-xs text-gray-500">${day.percentage}%</span>
                </div>
            `;
        });
        
        chartContainer.innerHTML = chartHTML;
    }
    
    function updateWeeklyDetails(weeklyData) {
        const detailsContainer = document.getElementById('weeklyDetails');
        if (!detailsContainer) return;
        
        let detailsHTML = '';
        weeklyData.forEach(day => {
            detailsHTML += `
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 w-8">${day.day}</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: ${day.percentage}%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-12 text-right">${day.hadir}/${day.total}</span>
                    <span class="text-sm text-gray-500 w-12 text-right">${day.percentage}%</span>
                </div>
            `;
        });
        
        detailsContainer.innerHTML = detailsHTML;
    }
    
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        // Set colors based on type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        notification.className += ` ${colors[type] || colors.info}`;
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
    
    // Auto refresh every 5 minutes (optional)
    // setInterval(refreshDashboard, 300000);
});
</script>
<?= $this->endSection() ?>
