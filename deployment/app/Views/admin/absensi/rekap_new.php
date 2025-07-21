<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Add margin to prevent header being too close to sidebar -->
<div style="margin-left: 20px; margin-right: 20px;">

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-chart-bar"></i> <?= $title ?>
    </h1>
    <div>
        <?php if (!empty($attendanceData) && !empty($attendanceData['students'])): ?>
        <a href="<?= base_url('admin/absensi/export') ?>?<?= http_build_query(['kelas' => $filterKelas, 'start_date' => $filterBulan . '-01', 'end_date' => date('Y-m-t', strtotime($filterBulan . '-01'))]) ?>" 
           class="btn btn-success btn-sm">
            <i class="fas fa-download"></i> Export CSV
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filters Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
    </div>
    <div class="card-body">
        <form id="filterForm" method="GET">
            <div class="row">
                <?php if ($userRole === 'admin'): ?>
                <div class="col-md-6">
                    <label for="kelas" class="form-label">Kelas:</label>
                    <select class="form-control" id="kelas" name="kelas" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($allKelas as $kelas): ?>
                        <option value="<?= $kelas['kelas'] ?>" 
                                <?= $filterKelas === $kelas['kelas'] ? 'selected' : '' ?>>
                            Kelas <?= $kelas['kelas'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                <div class="col-md-6">
                    <label for="kelas" class="form-label">Kelas:</label>
                    <input type="text" class="form-control" value="Kelas <?= $userKelas ?>" readonly>
                    <input type="hidden" name="kelas" value="<?= $userKelas ?>">
                </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <label for="bulan" class="form-label">Bulan:</label>
                    <input type="month" class="form-control" id="bulan" name="bulan" 
                           value="<?= $filterBulan ?>" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($attendanceData) && !empty($attendanceData['students'])): ?>
<!-- Attendance Recap Table Card -->
<div class="card shadow mb-4">
    <div class="card-body">
        <!-- Header Info -->
        <div class="attendance-recap-header">
            <h4>DAFTAR HADIR PESERTA DIDIK KELAS <?= strtoupper($attendanceData['kelas']) ?></h4>
            <p>TAHUN PELAJARAN <?= $attendanceData['year'] ?>/<?= (int)$attendanceData['year'] + 1 ?></p>
            <div class="month-indicator">
                <?php 
                $monthNames = [
                    '01' => 'JANUARI', '02' => 'FEBRUARI', '03' => 'MARET', '04' => 'APRIL',
                    '05' => 'MEI', '06' => 'JUNI', '07' => 'JULI', '08' => 'AGUSTUS',
                    '09' => 'SEPTEMBER', '10' => 'OKTOBER', '11' => 'NOVEMBER', '12' => 'DESEMBER'
                ];
                $monthNum = explode('-', $attendanceData['month'])[1];
                echo $monthNames[$monthNum];
                ?>
            </div>
            <div class="summary-info">
                <div class="legend">
                    <span class="legend-item hadir-legend"></span> Hadir
                    <span class="legend-item sakit-legend">s</span> Sakit
                    <span class="legend-item izin-legend">i</span> Izin
                    <span class="legend-item alpha-legend">a</span> Alpha
                </div>
                <div class="hbe-info">
                    <strong>HBE</strong><br>
                    <span class="hbe-count"><?= $attendanceData['daysInMonth'] ?></span><br>
                    <small>JUMLAH</small>
                </div>
            </div>
        </div>
        
        <!-- Attendance Table -->
        <div class="table-responsive">
            <table class="attendance-recap-table">
                <thead>
                    <tr>
                        <th rowspan="2" class="no-col">N<br>o</th>
                        <th rowspan="2" class="nama-col">NAMA SISWA</th>
                        <?php for ($day = 1; $day <= $attendanceData['daysInMonth']; $day++): ?>
                            <th class="day-col"><?= $day ?></th>
                        <?php endfor; ?>
                        <th rowspan="2" class="summary-col">S</th>
                        <th rowspan="2" class="summary-col">I</th>
                        <th rowspan="2" class="summary-col">A</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalSakit = 0;
                    $totalIzin = 0;
                    $totalAlpha = 0;
                    ?>
                    <?php foreach ($attendanceData['students'] as $index => $student): ?>
                    <tr>
                        <td class="no-cell"><?= $index + 1 ?></td>
                        <td class="nama-cell"><?= $student['nama'] ?></td>
                        <?php for ($day = 1; $day <= $attendanceData['daysInMonth']; $day++): ?>
                            <?php 
                            $status = $student['days'][$day];
                            $cellClass = '';
                            $cellContent = '';
                            
                            if ($status === 'hadir') {
                                $cellClass = 'hadir-cell';
                                $cellContent = '';
                            } elseif ($status === 'sakit') {
                                $cellClass = 'sakit-cell';
                                $cellContent = 's';
                            } elseif ($status === 'izin') {
                                $cellClass = 'izin-cell';
                                $cellContent = 'i';
                            } elseif ($status === 'alpha') {
                                $cellClass = 'alpha-cell';
                                $cellContent = 'a';
                            } else {
                                $cellClass = '';
                                $cellContent = '';
                            }
                            ?>
                            <td class="day-cell <?= $cellClass ?>"><?= $cellContent ?></td>
                        <?php endfor; ?>
                        <td class="summary-cell"><?= $student['summary']['sakit'] ?></td>
                        <td class="summary-cell"><?= $student['summary']['izin'] ?></td>
                        <td class="summary-cell"><?= $student['summary']['alpha'] ?></td>
                    </tr>
                    <?php 
                    $totalSakit += $student['summary']['sakit'];
                    $totalIzin += $student['summary']['izin'];
                    $totalAlpha += $student['summary']['alpha'];
                    ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="totals-row">
                        <td colspan="<?= 2 + $attendanceData['daysInMonth'] ?>"><strong>Total</strong></td>
                        <td class="total-cell"><strong><?= $totalSakit ?></strong></td>
                        <td class="total-cell"><strong><?= $totalIzin ?></strong></td>
                        <td class="total-cell"><strong><?= $totalAlpha ?></strong></td>
                    </tr>
                    <tr class="percentage-row">
                        <td colspan="<?= 2 + $attendanceData['daysInMonth'] ?>"><strong>Persentase Kehadiran</strong></td>
                        <td colspan="3" class="percentage-cell">
                            <?php 
                            $totalStudents = count($attendanceData['students']);
                            $totalDays = $attendanceData['daysInMonth'];
                            $totalPossible = $totalStudents * $totalDays;
                            $totalAbsent = $totalSakit + $totalIzin + $totalAlpha;
                            $totalPresent = $totalPossible - $totalAbsent;
                            $percentage = $totalPossible > 0 ? ($totalPresent / $totalPossible) * 100 : 0;
                            ?>
                            <strong><?= number_format($percentage, 2) ?>%</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php elseif ($filterKelas && $filterBulan): ?>
<!-- No Data Message -->
<div class="card shadow mb-4">
    <div class="card-body text-center">
        <div class="my-4">
            <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
            <h5 class="text-gray-600">Tidak ada data kehadiran</h5>
            <p class="text-gray-500">Tidak ada data kehadiran untuk kelas <?= $filterKelas ?> pada <?= date('F Y', strtotime($filterBulan . '-01')) ?>.</p>
        </div>
    </div>
</div>

<?php else: ?>
<!-- Filter Selection Message -->
<div class="card shadow mb-4">
    <div class="card-body text-center">
        <div class="my-4">
            <i class="fas fa-filter fa-3x text-gray-300 mb-3"></i>
            <h5 class="text-gray-600">Pilih kelas dan bulan untuk melihat rekap kehadiran</h5>
            <p class="text-gray-500">Gunakan filter di atas untuk memilih kelas dan bulan yang ingin ditampilkan.</p>
        </div>
    </div>
</div>

<?php endif; ?>

</div> <!-- Close the margin wrapper -->

<style>
/* Attendance Recap Header */
.attendance-recap-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 2px solid #e5e7eb;
}

.attendance-recap-header h4 {
    font-size: 18px;
    font-weight: 800;
    color: #1f2937;
    margin: 0 0 5px 0;
    letter-spacing: 1px;
}

.attendance-recap-header p {
    font-size: 16px;
    color: #6b7280;
    margin: 0 0 15px 0;
    font-weight: 600;
}

.month-indicator {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 24px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 2px;
    display: inline-block;
    margin-bottom: 20px;
}

.summary-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 600px;
    margin: 0 auto;
}

.legend {
    display: flex;
    gap: 20px;
    align-items: center;
}

.legend-item {
    display: inline-block;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    font-size: 12px;
    font-weight: bold;
    margin-right: 5px;
    border: 1px solid #ccc;
}

.hadir-legend {
    background: #10b981;
}

.sakit-legend {
    background: #ef4444;
    color: white;
}

.izin-legend {
    background: #f59e0b;
    color: white;
}

.alpha-legend {
    background: #6b7280;
    color: white;
}

.hbe-info {
    text-align: center;
    background: #f8fafc;
    padding: 10px 15px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
}

.hbe-count {
    font-size: 24px;
    font-weight: 800;
    color: #1f2937;
}

/* Attendance Recap Table */
.attendance-recap-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    background: white;
    margin: 0 auto;
}

.attendance-recap-table th,
.attendance-recap-table td {
    border: 1px solid #374151;
    padding: 4px 2px;
    text-align: center;
    vertical-align: middle;
}

.attendance-recap-table th {
    background: #e5e7eb;
    font-weight: 700;
    color: #374151;
    font-size: 11px;
}

.no-col, .no-cell {
    width: 40px;
    background: #f3f4f6;
    font-weight: 600;
}

.nama-col {
    width: 250px;
    text-align: center !important;
}

.nama-cell {
    text-align: left !important;
    padding-left: 8px !important;
    font-weight: 600;
    color: #1f2937;
}

.day-col, .day-cell {
    width: 25px;
    min-width: 25px;
}

.summary-col, .summary-cell {
    width: 30px;
    background: #f9fafb;
    font-weight: 700;
    color: #374151;
}

/* Attendance Status Cells */
.hadir-cell {
    background: #10b981 !important;
}

.sakit-cell {
    background: #ef4444 !important;
    color: white;
    font-weight: bold;
}

.izin-cell {
    background: #f59e0b !important;
    color: white;
    font-weight: bold;
}

.alpha-cell {
    background: #6b7280 !important;
    color: white;
    font-weight: bold;
}

/* Footer Rows */
.totals-row td {
    background: #f1f5f9;
    font-weight: 700;
    color: #1f2937;
    border-top: 2px solid #374151;
}

.total-cell {
    background: #e2e8f0 !important;
    font-weight: 800;
}

.percentage-row td {
    background: #667eea;
    color: white;
    font-weight: 700;
    font-size: 14px;
}

.percentage-cell {
    text-align: center !important;
    font-size: 16px !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .attendance-recap-header h4 {
        font-size: 16px;
    }
    
    .summary-info {
        flex-direction: column;
        gap: 15px;
    }
    
    .legend {
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    
    .attendance-recap-table {
        font-size: 10px;
    }
    
    .attendance-recap-table th,
    .attendance-recap-table td {
        padding: 2px 1px;
    }
    
    .day-col, .day-cell {
        width: 20px;
        min-width: 20px;
    }
    
    .nama-col {
        width: 180px;
    }
}

@media (max-width: 480px) {
    .attendance-recap-table {
        font-size: 9px;
    }
    
    .day-col, .day-cell {
        width: 18px;
        min-width: 18px;
    }
    
    .nama-col {
        width: 150px;
    }
    
    .nama-cell {
        font-size: 10px;
    }
}
</style>

<?= $this->endSection() ?>
