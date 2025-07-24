<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Link to external CSS -->
<link rel="stylesheet" href="<?= base_url('css/rekap-absensi-clean.css') ?>?v=20250107-compact-layout">

<style>
/* Remove admin-specific header since we'll use standard layout */
.professional-header {
    background: linear-gradient(135deg, #4472C4, #5a67d8);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    text-align: center;
    box-shadow: 0 4px 16px rgba(68, 114, 196, 0.2);
}

.professional-header h2,
.professional-header h3,
.professional-header h4,
.professional-header h5 {
    margin-bottom: 8px;
    font-weight: bold;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

/* HBE Display Styles */
.hbe-display {
    margin: 15px 0;
}

.hbe-box {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    display: inline-block;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
    border: 2px solid #c0392b;
}

.hbe-box strong {
    font-size: 18px;
    display: block;
    margin-bottom: 4px;
}

.hbe-box small {
    font-size: 12px;
    opacity: 0.9;
}

/* Enhanced Filter Section */
.filter-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center text-white">
        <div class="loading-spinner"></div>
        <p class="mt-3">Memproses data...</p>
    </div>
</div>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                Rekap Absensi Siswa
            </h1>
            <p class="text-gray-600">
                KELAS: <?= strtoupper($kelas ?? '5A') ?> | 
                BULAN: <?= strtoupper($bulan_nama ?? 'JULI') ?> <?= $tahun ?? 2025 ?> |
                TAHUN PELAJARAN: <?= ($tahun ?? 2025) ?>/<?= ($tahun ?? 2025) + 1 ?>
            </p>
        </div>
        <div class="flex space-x-3">
            <button type="button" id="downloadExcel" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
                    <?= empty($attendanceData) || empty($attendanceData['students']) ? 'disabled' : '' ?>>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Download Excel</span>
            </button>
        </div>
    </div>
</div>

<!-- Enhanced Filter Section -->
<div class="filter-section">
    <form id="filterForm" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-school mr-2 text-blue-600"></i>Kelas
                </label>
                <?php if ($userRole === 'admin'): ?>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        id="kelas" name="kelas" required>
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($allKelas as $kelas): ?>
                    <option value="<?= $kelas['kelas'] ?>" 
                            <?= $filterKelas === $kelas['kelas'] ? 'selected' : '' ?>>
                        Kelas <?= $kelas['kelas'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php else: ?>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" 
                       value="Kelas <?= $userKelas ?>" readonly>
                <input type="hidden" name="kelas" value="<?= $userKelas ?>">
                <?php endif; ?>
            </div>
            
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Bulan & Tahun
                </label>
                <input type="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       id="bulan" name="bulan" value="<?= $filterBulan ?>" required>
            </div>
            
            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-search"></i>
                    <span>Tampilkan</span>
                </button>
            </div>
            
            <div>
                <!-- Auto-reload toggle moved to header actions -->
            </div>
        </div>
    </form>
</div>

    <?php if (!empty($attendanceData) && !empty($attendanceData['students'])): ?>

    <!-- Professional Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="text-center professional-header">
            <h2 class="header-title">DAFTAR HADIR PESERTA DIDIK</h2>
            <h3 class="school-name">SDN GROGOL UTARA 09</h3>
            <h4 class="class-title">KELAS <?= strtoupper($attendanceData['kelas'] ?? 'KELAS 2 A') ?></h4>
            <h5 class="academic-year">TAHUN PELAJARAN <?= date('Y') ?>/<?= (date('Y') + 1) ?></h5>
            <h4 class="month-title">BULAN <?= strtoupper(date('F Y', mktime(0, 0, 0, (int)$attendanceData['month'], 1, (int)$attendanceData['year']))) ?></h4>
            
            <!-- HBE (Effective Days) Display in Red Box -->
            <div class="hbe-display mt-3">
                <div class="hbe-box">
                    <strong>HBE: <?= $attendanceData['effective_days'] ?? $attendanceData['total_days'] ?></strong>
                    <small class="d-block">Hari Efektif = <?= $attendanceData['total_days'] ?? count($attendanceData['days']) ?> hari - <?= count($attendanceData['holidays'] ?? []) ?> hari libur</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekap Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Data Kehadiran Siswa</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="excel-style-table min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th class="student-name" style="width: 150px;">Nama Siswa</th>
                            
                            <!-- Daily columns -->
                            <?php foreach ($attendanceData['days'] as $day): ?>
                            <?php 
                            $dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);
                            $currentDate = $attendanceData['year'] . '-' . str_pad($attendanceData['month'], 2, '0', STR_PAD_LEFT) . '-' . $dayStr;
                            $dayOfWeek = date('w', strtotime($currentDate));
                            $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                            $isHoliday = isset($attendanceData['holidays']) && in_array($currentDate, $attendanceData['holidays']);
                            
                            $headerClass = 'day-header';
                            if ($isHoliday) {
                                $headerClass .= ' holiday';
                            } elseif ($isWeekend) {
                                $headerClass .= ' weekend';
                            }
                            ?>
                            <th class="<?= $headerClass ?>"><?= $day ?></th>
                            <?php endforeach; ?>
                            
                            <!-- Summary columns with consistent styling -->
                            <th class="summary-header">S</th>
                            <th class="summary-header">I</th>
                            <th class="summary-header">A</th>
                            
                            <!-- Total column only (removed percentage column) -->
                            <th class="total-header">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($attendanceData['students'] as $student): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="student-name" title="<?= $student['nama'] ?>"><?= $student['nama'] ?></td>
                            
                            <!-- Daily attendance -->
                            <?php foreach ($attendanceData['days'] as $day): ?>
                            <?php 
                            $dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);
                            $currentDate = $attendanceData['year'] . '-' . str_pad($attendanceData['month'], 2, '0', STR_PAD_LEFT) . '-' . $dayStr;
                            $dayOfWeek = date('w', strtotime($currentDate));
                            $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                            $isHoliday = isset($attendanceData['holidays']) && in_array($currentDate, $attendanceData['holidays']);
                            
                            $cellClass = '';
                            if ($isHoliday) {
                                $cellClass = 'holiday-cell';
                            } elseif ($isWeekend) {
                                $cellClass = 'weekend-cell';
                            }
                            
                            $status = $student['daily'][$dayStr] ?? '';
                            $mark = '';
                            $markClass = '';
                            
                            if ($isHoliday || $isWeekend) {
                                $mark = '■';
                                $markClass = '';
                            } else {
                                switch ($status) {
                                    case 'hadir':
                                        $mark = '✓';
                                        $markClass = 'hadir';
                                        break;
                                    case 'izin':
                                        $mark = 'I';
                                        $markClass = 'izin';
                                        break;
                                    case 'sakit':
                                        $mark = 'S';
                                        $markClass = 'sakit';
                                        break;
                                    case 'alpha':
                                        $mark = 'A';
                                        $markClass = 'alpha';
                                        break;
                                    default:
                                        if (strtotime($currentDate) <= time()) {
                                            $mark = 'A';
                                            $markClass = 'alpha';
                                        } else {
                                            $mark = '-';
                                            $markClass = '';
                                        }
                                }
                            }
                            ?>
                            <td class="<?= $cellClass ?>">
                                <span class="attendance-mark <?= $markClass ?>"><?= $mark ?></span>
                            </td>
                            <?php endforeach; ?>
                            
                            <!-- Summary columns -->
                            <td class="summary-number"><?= $student['summary']['sakit'] ?></td>
                            <td class="summary-number"><?= $student['summary']['izin'] ?></td>
                            <td class="summary-number"><?= $student['summary']['alpha'] ?></td>
                            
                            <!-- Total only (removed percentage column) -->
                            <td class="summary-number"><?= $student['summary']['hadir'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php
                        // Calculate proper totals
                        $finalTotalSakit = 0;
                        $finalTotalIzin = 0;
                        $finalTotalAlpha = 0;
                        
                        foreach ($attendanceData['students'] as $student) {
                            $finalTotalSakit += $student['summary']['sakit'];
                            $finalTotalIzin += $student['summary']['izin'];
                            $finalTotalAlpha += $student['summary']['alpha'];
                        }
                        
                        $totalStudents = count($attendanceData['students']);
                        $effectiveDays = $attendanceData['effective_days'] ?? count($attendanceData['days']);
                        $totalPossibleAttendance = $totalStudents * $effectiveDays;
                        
                        // Use correct formula: Total / (effective days * total students) * 100%
                        $percentageSakit = $totalPossibleAttendance > 0 ? ($finalTotalSakit / $totalPossibleAttendance) * 100 : 0;
                        $percentageIzin = $totalPossibleAttendance > 0 ? ($finalTotalIzin / $totalPossibleAttendance) * 100 : 0;
                        $percentageAlpha = $totalPossibleAttendance > 0 ? ($finalTotalAlpha / $totalPossibleAttendance) * 100 : 0;
                        ?>
                        
                        <!-- Total Row -->
                        <tr class="summary-row" style="border-top: 2px solid #333;">
                            <td colspan="<?= (2 + count($attendanceData['days'])) ?>" class="merged-summary-cell">Total</td>
                            <!-- Summary totals aligned under S, I, A columns -->
                            <td class="summary-total"><?= $finalTotalSakit ?></td>
                            <td class="summary-total"><?= $finalTotalIzin ?></td>
                            <td class="summary-total"><?= $finalTotalAlpha ?></td>
                            <!-- Empty cell for Total column only -->
                            <td></td>
                        </tr>
                        
                        <!-- Percentage Row -->
                        <tr class="percentage-row">
                            <td colspan="<?= (2 + count($attendanceData['days'])) ?>" class="merged-percentage-cell">Persentase (Rumus: Total / (<?= $effectiveDays ?> hari efektif × <?= $totalStudents ?> siswa) × 100%)</td>
                            <!-- Percentage values aligned under S, I, A columns -->
                            <td class="percentage-value" style="color: #1976d2;"><?= number_format($percentageSakit, 1) ?>%</td>
                            <td class="percentage-value" style="color: #1976d2;"><?= number_format($percentageIzin, 1) ?>%</td>
                            <td class="percentage-value" style="color: #1976d2;"><?= number_format($percentageAlpha, 1) ?>%</td>
                            <!-- Empty cell for Total column only -->
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- No Data Message -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
        <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data</h3>
            <p class="text-gray-500">
                <?php if (!$filterKelas): ?>
                    Silakan pilih kelas terlebih dahulu untuk menampilkan data absensi.
                <?php else: ?>
                    Tidak ada data absensi untuk kelas <?= $filterKelas ?> pada bulan <?= isset($filterBulan) ? date('F Y', strtotime($filterBulan . '-01')) : date('F Y') ?>.
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced form submission with loading animation
    const filterForm = document.getElementById('filterForm');
    const kelasSelect = document.getElementById('kelas');
    const bulanInput = document.getElementById('bulan');
    const submitBtn = filterForm.querySelector('button[type="submit"]');
    
    // Store original button content
    const originalBtnContent = submitBtn.innerHTML;
    
    // Loading state function
    function showLoading() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat Data...';
        submitBtn.classList.add('btn-secondary');
        submitBtn.classList.remove('btn-primary');
    }
    
    function hideLoading() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnContent;
        submitBtn.classList.remove('btn-secondary');
        submitBtn.classList.add('btn-primary');
    }
    
    // Auto submit with loading animation
    function autoSubmit() {
        const hasKelas = kelasSelect?.value || '<?= $userKelas ?>';
        const hasBulan = bulanInput?.value;
        
        if (hasKelas && hasBulan) {
            showLoading();
            
            // Add small delay for better UX
            setTimeout(() => {
                filterForm.submit();
            }, 300);
        }
    }
    
    // Auto submit when filters change
    if (kelasSelect) {
        kelasSelect.addEventListener('change', autoSubmit);
    }
    
    if (bulanInput) {
        bulanInput.addEventListener('change', autoSubmit);
    }
    
    // Manual submit with loading
    filterForm.addEventListener('submit', function(e) {
        showLoading();
    });
    
    // Download Excel functionality
    const downloadBtn = document.getElementById('downloadExcel');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            if (!this.disabled) {
                // Show downloading state
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Download...';
                this.disabled = true;
                
                // Redirect to XLSX export URL using AbsensiEnhanced controller
                const exportUrl = '<?= base_url('admin/absensi-enhanced/export-excel') ?>?' + 
                    new URLSearchParams({
                        kelas: '<?= $filterKelas ?>',
                        bulan: '<?= date('n', strtotime($filterBulan . '-01')) ?>',
                        tahun: '<?= date('Y', strtotime($filterBulan . '-01')) ?>'
                    });
                
                window.location.href = exportUrl;
                
                // Reset button after delay
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }, 2000);
            }
        });
    }
    
    // Add smooth animations to table on load
    const table = document.querySelector('.excel-style-table');
    if (table) {
        table.style.opacity = '0';
        table.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            table.style.transition = 'all 0.5s ease';
            table.style.opacity = '1';
            table.style.transform = 'translateY(0)';
        }, 100);
    }
    
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.excel-style-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'all 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>
