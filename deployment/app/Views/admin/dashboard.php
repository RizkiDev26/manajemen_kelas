<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Selamat datang kembali, <?= esc($currentUser['nama']) ?>! Berikut adalah ringkasan sekolah hari ini.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Teachers Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-green-500 text-sm font-medium bg-green-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1"><?= $totalGuru ?></h3>
            <p class="text-gray-500 text-sm">Total Guru</p>
        </div>

        <!-- Total Siswa Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-green-500 text-sm font-medium bg-green-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1"><?= $totalSiswa ?></h3>
            <p class="text-gray-500 text-sm">Total Siswa</p>
            <div class="flex items-center mt-2 space-x-4 text-xs">
                <span class="text-blue-600 font-medium">♂ <?= $siswaLaki ?></span>
                <span class="text-pink-600 font-medium">♀ <?= $siswaPerempuan ?></span>
            </div>
        </div>

        <!-- Total Wali Kelas Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-green-500 text-sm font-medium bg-green-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1"><?= $totalWalikelas ?></h3>
            <p class="text-gray-500 text-sm">Total Wali Kelas</p>
        </div>

        <!-- Total Users Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <span class="text-green-500 text-sm font-medium bg-green-50 px-2 py-1 rounded-full">Online</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1"><?= $totalUsers ?></h3>
            <p class="text-gray-500 text-sm">Total Pengguna</p>
        </div>

        <!-- Current User Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="text-blue-500 text-sm font-medium bg-blue-50 px-2 py-1 rounded-full capitalize"><?= esc($currentUser['role']) ?></span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1"><?= esc($currentUser['nama']) ?></h3>
            <p class="text-gray-500 text-sm"><?= !empty($currentUser['kelas']) ? 'Kelas: ' . esc($currentUser['kelas']) : 'Status: ' . ucfirst($currentUser['role']) ?></p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Attendance Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Total Attendance</h3>
                    <p class="text-gray-500 text-sm">12.04.2022 - 12.05.2022</p>
                </div>
                <div class="flex space-x-4">
                    <button class="text-blue-600 text-sm font-medium">Day</button>
                    <button class="text-gray-500 text-sm">Week</button>
                    <button class="text-gray-500 text-sm">Month</button>
                </div>
            </div>
            
            <!-- Simple Chart Placeholder -->
            <div class="h-64 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg flex items-end justify-center space-x-2 p-4">
                <div class="bg-blue-500 w-8 h-32 rounded-t-md"></div>
                <div class="bg-blue-400 w-8 h-24 rounded-t-md"></div>
                <div class="bg-blue-500 w-8 h-40 rounded-t-md"></div>
                <div class="bg-blue-300 w-8 h-20 rounded-t-md"></div>
                <div class="bg-blue-500 w-8 h-36 rounded-t-md"></div>
                <div class="bg-blue-400 w-8 h-28 rounded-t-md"></div>
                <div class="bg-blue-500 w-8 h-44 rounded-t-md"></div>
                <div class="bg-blue-300 w-8 h-16 rounded-t-md"></div>
                <div class="bg-blue-500 w-8 h-32 rounded-t-md"></div>
                <div class="bg-blue-400 w-8 h-40 rounded-t-md"></div>
            </div>
        </div>

        <!-- Weekly Performance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Attendance this week</h3>
                    <p class="text-gray-500 text-sm">This Week</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">M</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">85</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">T</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">70</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">W</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">90</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">T</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">65</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">F</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">80</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">S</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">95</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">S</span>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 88%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">88</span>
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                    <span class="text-sm text-gray-600">Present</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-cyan-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Absent</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Teachers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Guru Terbaru</h3>
                <a href="/admin/data-guru" class="text-blue-600 text-sm font-medium hover:text-blue-700">Lihat Semua</a>
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
                            <p class="text-sm font-medium text-gray-900"><?= esc($guru['nama_lengkap']) ?> <?= !empty($guru['gelar']) ? ', ' . esc($guru['gelar']) : '' ?></p>
                            <p class="text-xs text-gray-500">NIP: <?= esc($guru['nip']) ?> • <?= esc($guru['jabatan']) ?></p>
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Siswa Terbaru</h3>
                <a href="/admin/data-siswa" class="text-orange-600 text-sm font-medium hover:text-orange-700">Lihat Semua</a>
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
</div>
<?= $this->endSection() ?>
