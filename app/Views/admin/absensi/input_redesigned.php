<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Modern Attendance System with Tailwind CSS -->
<div class="max-w-7xl mx-auto p-6 space-y-6">

    <?php 
    // Define Indonesian day names
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

    <?php if ($selectedKelas && !empty($students)): ?>
    <!-- Compact Header Section -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-4 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Header Info -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-clipboard-list text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Absensi Kelas <?= $selectedKelas ?></h1>
                    <p class="text-white/90 text-sm"><?= $indonesianDay ?>, <?= $formattedDate ?></p>
                </div>
            </div>
            
            <!-- Compact Stats -->
            <div class="flex gap-3">
                <div class="bg-white/15 backdrop-blur-sm rounded-lg px-3 py-2 text-center min-w-[70px]">
                    <div class="text-xl font-bold"><?= count($students) ?></div>
                    <div class="text-xs text-white/80">Total</div>
                </div>
                <div class="bg-white/15 backdrop-blur-sm rounded-lg px-3 py-2 text-center min-w-[70px]">
                    <div class="text-xl font-bold text-green-300" id="attendedCount">0</div>
                    <div class="text-xs text-white/80">Hadir</div>
                </div>
                <div class="bg-white/15 backdrop-blur-sm rounded-lg px-3 py-2 text-center min-w-[70px]">
                    <div class="text-xl font-bold text-yellow-300" id="pendingCount"><?= count($students) ?></div>
                    <div class="text-xs text-white/80">Pending</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex gap-2">
                <button type="button" id="markAllPresent" class="bg-green-500 hover:bg-green-600 px-3 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 text-sm">
                    <i class="fas fa-check-circle"></i>
                    <span class="hidden sm:inline">Hadir Semua</span>
                </button>
                <button type="button" id="exportData" class="bg-gray-600 hover:bg-gray-700 px-3 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 text-sm">
                    <i class="fas fa-download"></i>
                    <span class="hidden sm:inline">Export</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Filter Controls -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200" id="filterSection">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-filter text-indigo-500"></i>
                Filter & Navigasi
            </h3>
            <button type="button" class="lg:hidden bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-colors" id="filterToggle">
                <i class="fas fa-chevron-up" id="filterToggleIcon"></i>
            </button>
        </div>
        
        <div class="p-4" id="filterContent">
            <form id="filterForm" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <!-- Date Navigation -->
                    <div class="flex items-end gap-2">
                        <button type="button" class="w-10 h-10 bg-gray-100 hover:bg-indigo-100 border border-gray-300 hover:border-indigo-300 rounded-lg flex items-center justify-center transition-colors" id="prevDay">
                            <i class="fas fa-chevron-left text-gray-600"></i>
                        </button>
                        
                        <div class="flex-1">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" id="tanggal" name="tanggal" value="<?= $selectedDate ?>" required>
                        </div>
                        
                        <button type="button" class="w-10 h-10 bg-gray-100 hover:bg-indigo-100 border border-gray-300 hover:border-indigo-300 rounded-lg flex items-center justify-center transition-colors" id="nextDay">
                            <i class="fas fa-chevron-right text-gray-600"></i>
                        </button>
                    </div>
                    
                    <!-- Class Selection -->
                    <?php if ($userRole === 'admin'): ?>
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" id="kelas" name="kelas" required>
                            <option value="">Pilih Kelas</option>
                            <?php foreach ($allKelas as $kelas): ?>
                            <option value="<?= $kelas['kelas'] ?>" <?= $selectedKelas === $kelas['kelas'] ? 'selected' : '' ?>>
                                <?= $kelas['kelas'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="kelas" value="<?= $userKelas ?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                            <?= $userKelas ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Filter Status -->
                    <div>
                        <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" id="filterStatus">
                            <option value="all">Semua</option>
                            <option value="hadir">Hadir</option>
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                            <option value="alpha">Alpha</option>
                            <option value="belum">Belum Absen</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden">
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
            <span class="ml-3 text-gray-600">Memuat data...</span>
        </div>
    </div>

    <!-- Modern Students Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4" id="studentsGrid">
        <?php foreach ($students as $index => $student): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 student-card" 
             data-siswa-id="<?= $student['siswa_id'] ?>" 
             data-student-name="<?= strtolower($student['nama']) ?>"
             data-student-nisn="<?= $student['nisn'] ?? '' ?>">
            
            <!-- Student Header -->
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="relative flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs" style="width: 2rem; height: 2rem; min-width: 2rem; min-height: 2rem; max-width: 2rem; max-height: 2rem; border-radius: 50% !important; flex-shrink: 0; aspect-ratio: 1/1; object-fit: cover;">
                            <?= strtoupper(substr($student['nama'], 0, 2)) ?>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white status-indicator" 
                             data-status="<?= $student['status'] ?? 'none' ?>" style="width: 0.75rem; height: 0.75rem; border-radius: 50%; aspect-ratio: 1/1;"></div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 text-sm leading-tight mb-1"><?= $student['nama'] ?></h3>
                        <div class="space-y-0.5">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-id-card w-3 text-center mr-1.5"></i>
                                <span><?= $student['nisn'] ?? '-' ?></span>
                            </div>
                            <?php if (isset($student['jk'])): ?>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-<?= $student['jk'] == 'L' ? 'mars' : 'venus' ?> w-3 text-center mr-1.5 <?= $student['jk'] == 'L' ? 'text-blue-500' : 'text-pink-500' ?>"></i>
                                <span><?= $student['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 text-center">
                        <div class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded-full">
                            #<?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Attendance Buttons -->
            <div class="p-3">
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <button type="button" class="btn-attendance hadir <?= $student['status']==='hadir' ? 'active' : '' ?>" 
                            data-status="hadir">
                        <i class="fas fa-check text-sm"></i>
                        <span class="text-xs font-medium">Hadir</span>
                    </button>
                    
                    <button type="button" class="btn-attendance sakit <?= $student['status']==='sakit' ? 'active' : '' ?>" 
                            data-status="sakit">
                        <i class="fas fa-thermometer-half text-sm"></i>
                        <span class="text-xs font-medium">Sakit</span>
                    </button>
                    
                    <button type="button" class="btn-attendance izin <?= $student['status']==='izin' ? 'active' : '' ?>" 
                            data-status="izin">
                        <i class="fas fa-hand-paper text-sm"></i>
                        <span class="text-xs font-medium">Izin</span>
                    </button>
                    
                    <button type="button" class="btn-attendance alpha <?= $student['status']==='alpha' ? 'active' : '' ?>" 
                            data-status="alpha">
                        <i class="fas fa-times text-sm"></i>
                        <span class="text-xs font-medium">Alpha</span>
                    </button>
                </div>
                
                <!-- Notes Section -->
                <button type="button" class="w-full text-xs text-gray-500 hover:text-gray-700 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors btn-notes" onclick="toggleNotes(this)">
                    <i class="fas fa-sticky-note mr-1"></i>
                    Catatan
                </button>
                <div class="notes-input mt-2" style="display: none;">
                    <textarea class="w-full px-2 py-1 text-xs border border-gray-300 rounded-md resize-none student-keterangan" 
                              rows="2" placeholder="Tambahkan catatan..."><?= $student['keterangan'] ?? '' ?></textarea>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50">
        <button type="button" id="saveAll" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-3 font-semibold relative">
            <i class="fas fa-paper-plane"></i>
            <span>Kirim Daftar Hadir</span>
            <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-6 h-6 rounded-full flex items-center justify-center font-bold" id="fabBadge" style="display: none;">0</div>
        </button>
    </div>

    <?php elseif ($selectedKelas): ?>
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-users text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada siswa ditemukan</h3>
        <p class="text-gray-600 mb-6">Kelas <?= $selectedKelas ?> belum memiliki siswa yang terdaftar atau semua siswa sedang tidak aktif.</p>
        <button type="button" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-user-plus mr-2"></i>
            Kelola Data Siswa
        </button>
    </div>
    <?php else: ?>
    <!-- Initial State -->
    <div class="text-center py-12">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calendar-alt text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Mulai Input Absensi</h3>
        <p class="text-gray-600 mb-6">Pilih tanggal dan kelas pada filter di atas untuk memulai proses input absensi siswa.</p>
        <div class="flex gap-4 justify-center">
            <button type="button" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors" onclick="document.getElementById('tanggal').focus()">
                <i class="fas fa-calendar mr-2"></i>
                Pilih Tanggal
            </button>
            <?php if ($userRole === 'admin'): ?>
            <button type="button" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors" onclick="document.getElementById('kelas').focus()">
                <i class="fas fa-school mr-2"></i>
                Pilih Kelas
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<style>
/* Modern Attendance System Styles */
.status-indicator[data-status="hadir"] { background: #10b981; }
.status-indicator[data-status="sakit"] { background: #f59e0b; }
.status-indicator[data-status="izin"] { background: #3b82f6; }
.status-indicator[data-status="alpha"] { background: #ef4444; }
.status-indicator[data-status="none"] { background: #9ca3af; }

.btn-attendance {
    padding: 8px;
    border: 1px solid;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
    min-height: 50px;
}

.btn-attendance.hadir {
    border-color: rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.btn-attendance.hadir:hover,
.btn-attendance.hadir.active {
    border-color: #10b981;
    background: #10b981;
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    transform: translateY(-1px);
}

.btn-attendance.sakit {
    border-color: rgba(245, 158, 11, 0.3);
    color: #f59e0b;
}

.btn-attendance.sakit:hover,
.btn-attendance.sakit.active {
    border-color: #f59e0b;
    background: #f59e0b;
    color: white;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    transform: translateY(-1px);
}

.btn-attendance.izin {
    border-color: rgba(59, 130, 246, 0.3);
    color: #3b82f6;
}

.btn-attendance.izin:hover,
.btn-attendance.izin.active {
    border-color: #3b82f6;
    background: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    transform: translateY(-1px);
}

.btn-attendance.alpha {
    border-color: rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.btn-attendance.alpha:hover,
.btn-attendance.alpha.active {
    border-color: #ef4444;
    background: #ef4444;
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    transform: translateY(-1px);
}

/* Mobile Responsive Improvements for Attendance Buttons */
@media (max-width: 768px) {
    .btn-attendance {
        padding: 12px 8px;
        min-height: 60px;
        font-size: 14px;
        gap: 6px;
    }
    
    .btn-attendance i {
        font-size: 16px !important;
    }
    
    .btn-attendance span {
        font-size: 12px !important;
        font-weight: 600;
    }
    
    /* Increase touch target area */
    .student-card .grid {
        gap: 8px;
    }
    
    /* Better spacing for mobile */
    .student-card .p-3 {
        padding: 16px;
    }
    
    /* Avatar circle fixes for mobile */
    .student-card .relative.flex-shrink-0 .w-8.h-8 {
        width: 1.5rem !important;
        height: 1.5rem !important;
        min-width: 1.5rem !important;
        min-height: 1.5rem !important;
        max-width: 1.5rem !important;
        max-height: 1.5rem !important;
        border-radius: 50% !important;
        aspect-ratio: 1/1 !important;
        flex-shrink: 0 !important;
        object-fit: cover !important;
    }
}

@media (max-width: 480px) {
    .btn-attendance {
        padding: 14px 6px;
        min-height: 65px;
        font-size: 15px;
        gap: 8px;
    }
    
    .btn-attendance i {
        font-size: 18px !important;
    }
    
    .btn-attendance span {
        font-size: 13px !important;
        font-weight: 700;
    }
    
    /* Avatar circle fixes for very small mobile screens */
    .student-card .relative.flex-shrink-0 .w-8.h-8 {
        width: 1.375rem !important;
        height: 1.375rem !important;
        min-width: 1.375rem !important;
        min-height: 1.375rem !important;
        max-width: 1.375rem !important;
        max-height: 1.375rem !important;
        border-radius: 50% !important;
        aspect-ratio: 1/1 !important;
        flex-shrink: 0 !important;
        object-fit: cover !important;
    }
    }
    
    /* Even larger touch targets for very small screens */
    .student-card .grid {
        gap: 10px;
    }
    
    .student-card .p-3 {
        padding: 20px;
    }
}

/* Professional Student Card Improvements */
.student-card {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.student-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Status indicator improvements */
.status-indicator {
    transition: all 0.2s ease;
}

/* Better text hierarchy */
.student-card h3 {
    line-height: 1.2;
    font-weight: 600;
}

/* Icon alignment improvements */
.student-card .fas {
    vertical-align: middle;
}

/* Number badge styling */
.student-card .bg-gray-50 {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
}

/* Responsive text sizing */
@media (max-width: 640px) {
    .student-card h3 {
        font-size: 0.875rem;
        line-height: 1.25;
    }
    
    .student-card .text-xs {
        font-size: 0.75rem;
    }
}

/* Avatar Circle Fix - Ensure perfect circle on all screen sizes */
.student-card .relative.flex-shrink-0 .w-8.h-8,
.student-card .w-8.h-8.bg-gradient-to-br {
    width: 2rem !important;
    height: 2rem !important;
    flex-shrink: 0 !important;
    border-radius: 50% !important;
    min-width: 2rem !important;
    max-width: 2rem !important;
    min-height: 2rem !important;
    max-height: 2rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    overflow: hidden !important;
    aspect-ratio: 1 / 1 !important;
    box-sizing: border-box !important;
}

/* Force circular shape on mobile devices with higher specificity */
@media (max-width: 768px) {
    .student-card .relative.flex-shrink-0 .w-8.h-8,
    .student-card .w-8.h-8.bg-gradient-to-br {
        width: 1.5rem !important;
        height: 1.5rem !important;
        min-width: 1.5rem !important;
        max-width: 1.5rem !important;
        min-height: 1.5rem !important;
        max-height: 1.5rem !important;
        font-size: 0.65rem !important;
        border-radius: 50% !important;
        aspect-ratio: 1 / 1 !important;
        flex: 0 0 1.5rem !important;
        flex-shrink: 0 !important;
        flex-grow: 0 !important;
    }
}

@media (max-width: 480px) {
    .student-card .relative.flex-shrink-0 .w-8.h-8,
    .student-card .w-8.h-8.bg-gradient-to-br {
        width: 1.375rem !important;
        height: 1.375rem !important;
        min-width: 1.375rem !important;
        max-width: 1.375rem !important;
        min-height: 1.375rem !important;
        max-height: 1.375rem !important;
        font-size: 0.6rem !important;
        border-radius: 50% !important;
        aspect-ratio: 1 / 1 !important;
        flex: 0 0 1.375rem !important;
        flex-shrink: 0 !important;
        flex-grow: 0 !important;
    }
}

/* Status indicator positioning fix with higher specificity */
.student-card .status-indicator,
.student-card .absolute.w-3.h-3 {
    width: 0.75rem !important;
    height: 0.75rem !important;
    border-radius: 50% !important;
    position: absolute !important;
    bottom: -2px !important;
    right: -2px !important;
    border: 2px solid white !important;
    flex-shrink: 0 !important;
    aspect-ratio: 1 / 1 !important;
}

/* Responsive status indicator adjustments */
@media (max-width: 768px) {
    .student-card .status-indicator,
    .student-card .absolute.w-3.h-3 {
        width: 0.5rem !important;
        height: 0.5rem !important;
        bottom: -1px !important;
        right: -1px !important;
        border-width: 1.5px !important;
        aspect-ratio: 1 / 1 !important;
    }
}

@media (max-width: 480px) {
    .student-card .status-indicator,
    .student-card .absolute.w-3.h-3 {
        width: 0.4rem !important;
        height: 0.4rem !important;
        bottom: 0px !important;
        right: 0px !important;
        border-width: 1px !important;
        aspect-ratio: 1 / 1 !important;
    }
}

.student-card.filtered-out {
    opacity: 0.3;
    transform: scale(0.95);
    pointer-events: none;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4.\\32xl\\:grid-cols-5 {
        grid-template-columns: repeat(1, 1fr);
    }
}

@media (min-width: 640px) and (max-width: 1023px) {
    .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4.\\32xl\\:grid-cols-5 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) and (max-width: 1279px) {
    .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4.\\32xl\\:grid-cols-5 {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1280px) and (max-width: 1535px) {
    .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4.\\32xl\\:grid-cols-5 {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1536px) {
    .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4.\\32xl\\:grid-cols-5 {
        grid-template-columns: repeat(5, 1fr);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize attendance tracking
    let attendanceData = {};
    let totalStudents = document.querySelectorAll('.student-card').length;
    
    // Filter Toggle Functionality
    const filterToggle = document.getElementById('filterToggle');
    const filterContent = document.getElementById('filterContent');
    const filterSection = document.getElementById('filterSection');
    
    if (filterToggle && filterContent && filterSection) {
        const toggleIcon = document.getElementById('filterToggleIcon');
        
        function updateFilterToggleUI(isCollapsed) {
            if (isCollapsed) {
                filterContent.style.display = 'none';
                filterSection.classList.add('collapsed');
                if (toggleIcon) toggleIcon.className = 'fas fa-chevron-down';
            } else {
                filterContent.style.display = 'block';
                filterSection.classList.remove('collapsed');
                if (toggleIcon) toggleIcon.className = 'fas fa-chevron-up';
            }
        }
        
        filterToggle.addEventListener('click', function() {
            const isCollapsed = filterContent.style.display === 'none';
            updateFilterToggleUI(!isCollapsed);
            localStorage.setItem('filter-collapsed', !isCollapsed);
        });
        
        // Restore filter state
        const savedFilterState = localStorage.getItem('filter-collapsed');
        if (savedFilterState === 'true') {
            updateFilterToggleUI(true);
        }
    }
    
    // Initialize from existing data
    document.querySelectorAll('.student-card').forEach(card => {
        const siswaId = card.dataset.siswaId;
        const activeButton = card.querySelector('.btn-attendance.active');
        if (activeButton) {
            attendanceData[siswaId] = {
                status: activeButton.dataset.status,
                keterangan: card.querySelector('.student-keterangan').value || ''
            };
        }
    });
    
    // Fix avatar circle on mobile
    function fixAvatarCircles() {
        const avatars = document.querySelectorAll('.student-card .w-8.h-8');
        const indicators = document.querySelectorAll('.student-card .status-indicator');
        
        avatars.forEach(avatar => {
            if (window.innerWidth <= 480) {
                avatar.style.cssText = 'width: 1.375rem !important; height: 1.375rem !important; min-width: 1.375rem !important; min-height: 1.375rem !important; max-width: 1.375rem !important; max-height: 1.375rem !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important; object-fit: cover !important; font-size: 0.6rem !important;';
            } else if (window.innerWidth <= 768) {
                avatar.style.cssText = 'width: 1.5rem !important; height: 1.5rem !important; min-width: 1.5rem !important; min-height: 1.5rem !important; max-width: 1.5rem !important; max-height: 1.5rem !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important; object-fit: cover !important; font-size: 0.65rem !important;';
            } else {
                avatar.style.cssText = 'width: 2rem !important; height: 2rem !important; min-width: 2rem !important; min-height: 2rem !important; max-width: 2rem !important; max-height: 2rem !important; border-radius: 50% !important; flex-shrink: 0 !important; aspect-ratio: 1/1 !important; object-fit: cover !important;';
            }
        });
        
        indicators.forEach(indicator => {
            if (window.innerWidth <= 480) {
                indicator.style.cssText = 'width: 0.4rem !important; height: 0.4rem !important; border-radius: 50% !important; aspect-ratio: 1/1 !important; border-width: 1px !important;';
            } else if (window.innerWidth <= 768) {
                indicator.style.cssText = 'width: 0.5rem !important; height: 0.5rem !important; border-radius: 50% !important; aspect-ratio: 1/1 !important; border-width: 1.5px !important;';
            } else {
                indicator.style.cssText = 'width: 0.75rem !important; height: 0.75rem !important; border-radius: 50% !important; aspect-ratio: 1/1 !important; border-width: 2px !important;';
            }
        });
    }
    
    // Apply on load and resize
    fixAvatarCircles();
    window.addEventListener('resize', fixAvatarCircles);
    
    // Update counters
    function updateCounters() {
        const attendedCount = Object.keys(attendanceData).length;
        const pendingCount = totalStudents - attendedCount;
        
        const attendedCountEl = document.getElementById('attendedCount');
        const pendingCountEl = document.getElementById('pendingCount');
        const fabBadge = document.getElementById('fabBadge');
        
        if (attendedCountEl) attendedCountEl.textContent = attendedCount;
        if (pendingCountEl) pendingCountEl.textContent = pendingCount;
        
        if (fabBadge) {
            if (attendedCount > 0) {
                fabBadge.textContent = attendedCount;
                fabBadge.style.display = 'flex';
            } else {
                fabBadge.style.display = 'none';
            }
        }
        
        // Update status indicators
        document.querySelectorAll('.student-card').forEach(card => {
            const siswaId = card.dataset.siswaId;
            const indicator = card.querySelector('.status-indicator');
            if (indicator && attendanceData[siswaId]) {
                indicator.dataset.status = attendanceData[siswaId].status;
            } else if (indicator) {
                indicator.dataset.status = 'none';
            }
        });
    }
    
    // Handle attendance button clicks
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-attendance, .btn-attendance *')) {
            const button = e.target.closest('.btn-attendance');
            const card = button.closest('.student-card');
            const siswaId = card.dataset.siswaId;
            const status = button.dataset.status;
            
            // Remove active state from all buttons in this card
            card.querySelectorAll('.btn-attendance').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active state to clicked button
            button.classList.add('active');
            
            // Update attendance data
            attendanceData[siswaId] = {
                status: status,
                keterangan: card.querySelector('.student-keterangan').value || ''
            };
            
            // Visual feedback
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                button.style.transform = '';
            }, 150);
            
            updateCounters();
        }
    });
    
    // Mark All Present
    document.getElementById('markAllPresent')?.addEventListener('click', function() {
        const button = this;
        const originalHTML = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-spinner spinner"></i> Memproses...';
        button.disabled = true;
        
        document.querySelectorAll('.student-card').forEach((card, index) => {
            setTimeout(() => {
                const siswaId = card.dataset.siswaId;
                const hadirButton = card.querySelector('[data-status="hadir"]');
                
                card.querySelectorAll('.btn-attendance').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                hadirButton.classList.add('active');
                
                attendanceData[siswaId] = {
                    status: 'hadir',
                    keterangan: ''
                };
                
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 200);
                
                if (index === document.querySelectorAll('.student-card').length - 1) {
                    updateCounters();
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.disabled = false;
                        showNotification('Semua siswa telah ditandai hadir!', 'success');
                    }, 300);
                }
            }, index * 50);
        });
    });
    
    // Filter functionality
    document.getElementById('filterStatus')?.addEventListener('change', function() {
        const filter = this.value;
        
        document.querySelectorAll('.student-card').forEach(card => {
            const siswaId = card.dataset.siswaId;
            const hasAttendance = attendanceData[siswaId];
            
            let show = false;
            
            if (filter === 'all') {
                show = true;
            } else if (filter === 'belum') {
                show = !hasAttendance;
            } else {
                show = hasAttendance && attendanceData[siswaId].status === filter;
            }
            
            if (show) {
                card.classList.remove('filtered-out');
            } else {
                card.classList.add('filtered-out');
            }
        });
    });
    
    // Date navigation
    document.getElementById('prevDay')?.addEventListener('click', function() {
        const dateInput = document.getElementById('tanggal');
        const currentDate = new Date(dateInput.value);
        currentDate.setDate(currentDate.getDate() - 1);
        dateInput.value = currentDate.toISOString().split('T')[0];
        loadAttendanceData();
    });
    
    document.getElementById('nextDay')?.addEventListener('click', function() {
        const dateInput = document.getElementById('tanggal');
        const currentDate = new Date(dateInput.value);
        currentDate.setDate(currentDate.getDate() + 1);
        dateInput.value = currentDate.toISOString().split('T')[0];
        loadAttendanceData();
    });
    
    // AJAX form submission for date/class changes
    function loadAttendanceData() {
        const filterForm = document.getElementById('filterForm');
        if (!filterForm) return;
        
        const formData = new FormData(filterForm);
        const loadingState = document.getElementById('loadingState');
        const studentsGrid = document.getElementById('studentsGrid');
        
        if (loadingState) loadingState.classList.remove('hidden');
        if (studentsGrid) studentsGrid.style.opacity = '0.5';
        
        fetch('<?= base_url('admin/absensi/input') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response =>
