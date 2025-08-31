<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Header removed per simplification (title, greeting, refresh, last updated) -->
<?php if (isset($dbError) && $dbError): ?>
<div class="mb-6 mx-4 sm:mx-6 lg:mx-8 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-xl p-4 shadow-sm">
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

<!-- Statistics Cards - Mobile Optimized -->
<div class="px-4 sm:px-6 lg:px-8 mb-6 sm:mb-8 lg:mb-10">
    <?php if (isset($isWalikelas) && $isWalikelas): ?>
    <!-- Walikelas Dashboard Cards -->
    <?php 
        $dp = $dailyProgress ?? [
            'attendance_present'=>0,'attendance_marked'=>0,'habit_logged'=>0,'total_students'=>$totalSiswa ?? 0,
            'attendance_percentage'=>0,'habit_percentage'=>0
        ];
    ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        <!-- Total Siswa -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1"><?= $totalSiswa ?? 0 ?></h3>
            <p class="text-gray-500 text-sm">Total Siswa</p>
        </div>
        <!-- Laki-laki -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1"><?= $siswaLaki ?? 0 ?></h3>
            <p class="text-gray-500 text-sm">Siswa Laki-laki</p>
        </div>
        <!-- Perempuan -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z"/></svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1"><?= $siswaPerempuan ?? 0 ?></h3>
            <p class="text-gray-500 text-sm">Siswa Perempuan</p>
        </div>
        <!-- Progress Kehadiran Hari Ini -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-gray-700">Progress Kehadiran</h4>
                <span class="text-xs text-gray-500">Hari ini</span>
            </div>
            <div class="mt-2">
                <div class="flex items-baseline gap-2 mb-1">
                    <span class="text-2xl font-bold text-gray-900"><?= $dp['attendance_present'] ?></span>
                    <span class="text-sm text-gray-500">/ <?= $dp['total_students'] ?></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-2 overflow-hidden">
                    <div class="bg-blue-600 h-2" style="width: <?= min(100,(int)$dp['attendance_percentage']) ?>%"></div>
                </div>
                <p class="text-xs text-gray-500"><?= $dp['attendance_percentage'] ?>% hadir</p>
            </div>
        </div>
        <!-- Progress 7 Kebiasaan -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-semibold text-gray-700">Progress 7 Kebiasaan</h4>
                <span class="text-xs text-gray-500">Hari ini</span>
            </div>
            <div class="mt-2">
                <div class="flex items-baseline gap-2 mb-1">
                    <span class="text-2xl font-bold text-gray-900"><?= $dp['habit_logged'] ?></span>
                    <span class="text-sm text-gray-500">/ <?= $dp['total_students'] ?></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-2 overflow-hidden">
                    <div class="bg-green-600 h-2" style="width: <?= min(100,(int)$dp['habit_percentage']) ?>%"></div>
                </div>
                <p class="text-xs text-gray-500"><?= $dp['habit_percentage'] ?>% sudah input</p>
            </div>
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

<!-- Recent activity sections removed -->

<!-- JavaScript for Dashboard Functionality -->
<script>
// All interactive refresh & chart scripts removed with dashboard simplification.
</script>
<?= $this->endSection() ?>
