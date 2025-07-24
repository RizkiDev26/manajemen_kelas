<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Enhanced Attendance System with Modern Design -->
<div class="attendance-container">

<!-- Debug Info (remove later) -->
<?php if (ENVIRONMENT === 'development'): ?>
<div class="debug-info">
    <strong>Debug:</strong> 
    Kelas: <?= $selectedKelas ? $selectedKelas : 'NULL' ?> | 
    Siswa: <?= count($students) ?> | 
    Role: <?= $userRole ?>
</div>
<?php endif; ?>

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
<!-- Enhanced Header Section -->
<div class="page-header">
    <div class="header-content">
        <div class="header-info">
            <div class="header-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="header-text">
                <h1>Absensi Kelas <?= $selectedKelas ?></h1>
                <p class="date-info"><?= $indonesianDay ?>, <?= $formattedDate ?></p>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= count($students) ?></div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="attendedCount">0</div>
                <div class="stat-label">Sudah Absen</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingCount"><?= count($students) ?></div>
                <div class="stat-label">Belum Absen</div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <button type="button" id="markAllPresent" class="btn btn-success">
                <i class="fas fa-check-circle"></i>
                Hadir Semua
            </button>
            <button type="button" id="exportData" class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>
</div>

<!-- Enhanced Filter Controls -->
<div class="filter-section">
    <div class="filter-header">
        <h3><i class="fas fa-filter"></i> Filter & Navigasi</h3>
        <button type="button" class="filter-toggle" id="filterToggle" title="Tampilkan/Sembunyikan Menu Filter">
            <i class="fas fa-chevron-up"></i>
            <span class="toggle-text">Sembunyikan Menu</span>
        </button>
    </div>
    
    <form id="filterForm" method="GET" class="filter-form">
        <div class="filter-grid">
            <!-- Date Navigation -->
            <div class="date-navigation">
                <button type="button" class="btn-nav" id="prevDay" title="Hari sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="date-input-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                           value="<?= $selectedDate ?>" required>
                </div>
                
                <button type="button" class="btn-nav" id="nextDay" title="Hari selanjutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Class Selection -->
            <?php if ($userRole === 'admin'): ?>
            <div class="input-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" id="kelas" name="kelas" required>
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($allKelas as $kelas): ?>
                    <option value="<?= $kelas['kelas'] ?>" 
                            <?= $selectedKelas === $kelas['kelas'] ? 'selected' : '' ?>>
                        <?= $kelas['kelas'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
            <input type="hidden" name="kelas" value="<?= $userKelas ?>">
            <?php endif; ?>
            
            <!-- Filter Status -->
            <div class="input-group">
                <label for="filterStatus">Filter Status</label>
                <select class="form-control" id="filterStatus">
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

<!-- Floating Filter Button (shows when filter is collapsed) -->
<div class="floating-filter-btn" id="floatingFilterBtn" style="display: none;">
    <button type="button" class="btn-float-filter" title="Buka Menu Filter">
        <i class="fas fa-filter"></i>
        <span>Filter</span>
    </button>
</div>

<!-- Enhanced Students Grid -->
<div class="students-grid" id="studentsGrid">
    <?php foreach ($students as $index => $student): ?>
    <div class="student-card" data-siswa-id="<?= $student['siswa_id'] ?>" 
         data-student-name="<?= strtolower($student['nama']) ?>"
         data-student-nisn="<?= $student['nisn'] ?? '' ?>">
        
        <!-- Student Header -->
        <div class="student-header">
            <div class="student-avatar">
                <div class="avatar-circle">
                    <span><?= strtoupper(substr($student['nama'], 0, 2)) ?></span>
                </div>
                <div class="status-indicator" data-status="<?= $student['status'] ?? 'none' ?>"></div>
            </div>
            
            <div class="student-info">
                <h3 class="student-name"><?= $student['nama'] ?></h3>
                <div class="student-details">
                    <div class="detail-item">
                        <i class="fas fa-id-card"></i>
                        <span>NISN: <?= $student['nisn'] ?? '-' ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-<?= isset($student['jk']) && $student['jk'] == 'L' ? 'mars' : 'venus' ?>"></i>
                        <span class="gender-badge <?= isset($student['jk']) && $student['jk'] == 'L' ? 'male' : 'female' ?>">
                            <?= isset($student['jk']) ? ($student['jk'] == 'L' ? 'Laki-laki' : 'Perempuan') : '-' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Buttons -->
        <div class="attendance-buttons">
            <button type="button" class="btn-attendance hadir <?= $student['status']==='hadir' ? 'active' : '' ?>" 
                    data-status="hadir" title="Hadir">
                <i class="fas fa-check"></i>
                <span>Hadir</span>
            </button>
            
            <button type="button" class="btn-attendance sakit <?= $student['status']==='sakit' ? 'active' : '' ?>" 
                    data-status="sakit" title="Sakit">
                <i class="fas fa-thermometer-half"></i>
                <span>Sakit</span>
            </button>
            
            <button type="button" class="btn-attendance izin <?= $student['status']==='izin' ? 'active' : '' ?>" 
                    data-status="izin" title="Izin">
                <i class="fas fa-hand-paper"></i>
                <span>Izin</span>
            </button>
            
            <button type="button" class="btn-attendance alpha <?= $student['status']==='alpha' ? 'active' : '' ?>" 
                    data-status="alpha" title="Alpha">
                <i class="fas fa-times"></i>
                <span>Alpha</span>
            </button>
        </div>
        
        <!-- Notes Section -->
        <div class="notes-section">
            <button type="button" class="btn-notes" onclick="toggleNotes(this)">
                <i class="fas fa-sticky-note"></i>
                Catatan
            </button>
            <div class="notes-input" style="display: none;">
                <textarea class="student-keterangan" placeholder="Tambahkan catatan..."><?= $student['keterangan'] ?? '' ?></textarea>
            </div>
        </div>
        
        <div class="card-number">#<?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Floating Action Button -->
<div class="floating-action">
    <button type="button" id="saveAll" class="fab">
        <i class="fas fa-paper-plane"></i>
        <span>Kirim Daftar Hadir</span>
        <div class="fab-badge" id="fabBadge" style="display: none;">0</div>
    </button>
</div>

<?php elseif ($selectedKelas): ?>
<!-- Empty State -->
<div class="empty-state">
    <div class="empty-icon">
        <i class="fas fa-users"></i>
    </div>
    <h3>Tidak ada siswa ditemukan</h3>
    <p>Kelas <?= $selectedKelas ?> belum memiliki siswa yang terdaftar atau semua siswa sedang tidak aktif.</p>
    <button type="button" class="btn btn-primary">
        <i class="fas fa-user-plus"></i>
        Kelola Data Siswa
    </button>
</div>
<?php else: ?>
<!-- Initial State -->
<div class="initial-state">
    <div class="initial-icon">
        <i class="fas fa-calendar-alt"></i>
    </div>
    <h3>Mulai Input Absensi</h3>
    <p>Pilih tanggal dan kelas pada filter di atas untuk memulai proses input absensi siswa.</p>
    <div class="initial-actions">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('tanggal').focus()">
            <i class="fas fa-calendar"></i>
            Pilih Tanggal
        </button>
        <button type="button" class="btn btn-outline" onclick="document.getElementById('kelas').focus()">
            <i class="fas fa-school"></i>
            Pilih Kelas
        </button>
    </div>
</div>
<?php endif; ?>

</div>

<style>
/**
 * ==========================================
 * ENHANCED ATTENDANCE SYSTEM STYLES
 * ==========================================
 * Modern, consistent design system
 * Author: Manus AI
 * Version: 2.0
 */

/* CSS Custom Properties for Design System */
:root {
    /* Colors */
    --primary-color: #667eea;
    --primary-dark: #5a67d8;
    --secondary-color: #764ba2;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
    
    /* Grays */
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
    
    /* Spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    
    /* Border Radius */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    
    /* Transitions */
    --transition-fast: 150ms ease-in-out;
    --transition-normal: 300ms ease-in-out;
    --transition-slow: 500ms ease-in-out;
}

/* Global Improvements */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html, body {
    height: 100%;
    overflow-x: hidden;
}

/* Fix untuk menghilangkan area putih */
.content-area {
    background-color: #f8fafc !important;
    min-height: 100vh;
}

/* Pastikan tidak ada gap atau spacing yang berlebihan */
.attendance-container > * {
    margin-bottom: 0;
}

.attendance-container > *:not(:last-child) {
    margin-bottom: var(--spacing-xl);
}

/* Debug untuk area putih (hapus setelah masalah teratasi) */
.page-header, .filter-section, .students-grid, .empty-state, .initial-state {
    border: 2px solid transparent;
}

/* Pastikan floating button tidak mengganggu layout */
.floating-action {
    position: fixed;
    bottom: var(--spacing-xl);
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    pointer-events: none;
}

.floating-action .fab {
    pointer-events: all;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: var(--gray-700);
    background-color: #f8fafc !important;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
}

.attendance-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--spacing-lg);
    padding-bottom: 120px; /* Space for floating button */
    background-color: transparent;
    min-height: calc(100vh - 144px); /* Adjust for header and padding */
    width: 100%;
    position: relative;
}

/* Debug Info */
.debug-info {
    background: var(--info-color);
    color: white;
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
    font-size: 0.875rem;
    box-shadow: var(--shadow-md);
}

/* Enhanced Page Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: var(--radius-2xl);
    padding: var(--spacing-2xl);
    margin-bottom: var(--spacing-xl);
    box-shadow: var(--shadow-xl);
    color: white;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/><circle cx="10" cy="90" r="1" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.header-content {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: var(--spacing-xl);
    align-items: center;
}

.header-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
}

.header-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.header-text h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.date-info {
    margin: var(--spacing-xs) 0 0 0;
    opacity: 0.9;
    font-size: 1.1rem;
    font-weight: 500;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-md);
}

.stat-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    text-align: center;
    transition: var(--transition-normal);
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: var(--spacing-xs);
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    font-weight: 500;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

/* Enhanced Filter Section */
.filter-section {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    margin: 0 0 var(--spacing-xl) 0;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    transition: var(--transition-normal);
    width: 100%;
}

.filter-section.collapsed {
    padding-bottom: var(--spacing-lg);
    border-color: var(--primary-color);
    box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.1), 0 2px 4px -1px rgba(102, 126, 234, 0.06);
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--gray-200);
}

.filter-header h3 {
    margin: 0;
    color: var(--gray-800);
    font-size: 1.25rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.filter-toggle {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    background: linear-gradient(135deg, var(--gray-50), white);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: var(--transition-fast);
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
    box-shadow: var(--shadow-sm);
}

.filter-toggle:hover {
    background: linear-gradient(135deg, var(--gray-100), var(--gray-50));
    border-color: var(--primary-color);
    color: var(--primary-color);
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}

.filter-toggle:active {
    transform: translateY(0);
    box-shadow: var(--shadow-sm);
}

.filter-toggle i {
    transition: var(--transition-normal);
    font-size: 0.875rem;
}

.filter-toggle.collapsed i {
    transform: rotate(180deg);
    color: var(--primary-color);
}

.filter-toggle.collapsed {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(102, 126, 234, 0.05));
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.filter-form {
    transition: all var(--transition-normal) ease-in-out;
    overflow: hidden;
    max-height: 1000px; /* Set a reasonable max height */
    opacity: 1;
    transform: translateY(0);
}

.filter-form.collapsed {
    max-height: 0;
    opacity: 0;
    margin-bottom: calc(-1 * var(--spacing-lg));
    padding-top: 0;
    padding-bottom: 0;
    transform: translateY(-10px);
}

/* Responsive adjustments for toggle */
@media (max-width: 768px) {
    .filter-toggle .toggle-text {
        display: none;
    }
    
    .filter-toggle {
        padding: var(--spacing-sm);
        min-width: 44px;
        justify-content: center;
    }
}

.auto-reload-status {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 0.875rem;
    color: var(--gray-600);
    background: var(--gray-50);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
}

.status-dot {
    width: 8px;
    height: 8px;
    background: var(--success-color);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.filter-grid {
    display: grid;
    grid-template-columns: auto 1fr 1fr;
    gap: var(--spacing-lg);
    align-items: end;
}

.date-navigation {
    display: flex;
    align-items: end;
    gap: var(--spacing-sm);
}

.date-input-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.input-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
}

.form-control {
    padding: var(--spacing-md);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    font-size: 0.875rem;
    transition: var(--transition-fast);
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-nav {
    width: 44px;
    height: 44px;
    border: 2px solid var(--gray-300);
    background: white;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-fast);
    color: var(--gray-600);
}

.btn-nav:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: var(--gray-50);
}

.quick-filters {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--gray-200);
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-right: var(--spacing-sm);
}

.filter-btn {
    padding: var(--spacing-sm) var(--spacing-md);
    border: 1px solid var(--gray-300);
    background: white;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-fast);
    color: var(--gray-600);
}

.filter-btn:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
}

.filter-btn.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

/* Enhanced Students Grid */
.students-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-md);
    margin: 0 0 var(--spacing-lg) 0;
    width: 100%;
}

.student-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition-normal);
    position: relative;
    min-width: 0;
}

.student-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.student-card.filtered-out {
    opacity: 0.3;
    transform: scale(0.95);
    pointer-events: none;
}

.student-header {
    padding: var(--spacing-md);
    background: linear-gradient(135deg, var(--gray-50), white);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.student-avatar {
    position: relative;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: var(--shadow-sm);
}

.status-indicator {
    position: absolute;
    bottom: -1px;
    right: -1px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: var(--shadow-sm);
}

.status-indicator[data-status="hadir"] { background: var(--success-color); }
.status-indicator[data-status="sakit"] { background: var(--warning-color); }
.status-indicator[data-status="izin"] { background: var(--info-color); }
.status-indicator[data-status="alpha"] { background: var(--danger-color); }
.status-indicator[data-status="none"] { background: var(--gray-300); }

.student-info {
    flex: 1;
    min-width: 0;
}

.student-name {
    margin: 0 0 var(--spacing-xs) 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
}

.student-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: 0.75rem;
    color: var(--gray-600);
}

.detail-item i {
    width: 16px;
    color: var(--gray-400);
}

.gender-badge {
    padding: 2px 8px;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
}

.gender-badge.male {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info-color);
}

.gender-badge.female {
    background: rgba(236, 72, 153, 0.1);
    color: #ec4899;
}

/* Enhanced Attendance Buttons */
.attendance-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-xs);
    padding: var(--spacing-md);
}

.btn-attendance {
    padding: var(--spacing-xs);
    border: 1px solid;
    border-radius: var(--radius-sm);
    background: white;
    cursor: pointer;
    transition: var(--transition-normal);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1px;
    position: relative;
    overflow: hidden;
    min-height: 35px;
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

.btn-attendance i {
    font-size: 0.85rem;
}

.btn-attendance.hadir {
    border-color: rgba(16, 185, 129, 0.3);
    color: var(--success-color);
}

.btn-attendance.hadir:hover,
.btn-attendance.hadir.active {
    border-color: var(--success-color);
    background: var(--success-color);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-attendance.sakit {
    border-color: rgba(245, 158, 11, 0.3);
    color: var(--warning-color);
}

.btn-attendance.sakit:hover,
.btn-attendance.sakit.active {
    border-color: var(--warning-color);
    background: var(--warning-color);
    color: white;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-attendance.izin {
    border-color: rgba(59, 130, 246, 0.3);
    color: var(--info-color);
}

.btn-attendance.izin:hover,
.btn-attendance.izin.active {
    border-color: var(--info-color);
    background: var(--info-color);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-attendance.alpha {
    border-color: rgba(239, 68, 68, 0.3);
    color: var(--danger-color);
}

.btn-attendance.alpha:hover,
.btn-attendance.alpha.active {
    border-color: var(--danger-color);
    background: var(--danger-color);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Notes Section */
.notes-section {
    padding: 0 var(--spacing-md) var(--spacing-md);
}

.btn-notes {
    width: 100%;
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--gray-300);
    background: var(--gray-50);
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    color: var(--gray-600);
    cursor: pointer;
    transition: var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
}

.btn-notes:hover {
    background: var(--gray-100);
    border-color: var(--gray-400);
}

.notes-input {
    margin-top: var(--spacing-sm);
}

.student-keterangan {
    width: 100%;
    padding: var(--spacing-xs);
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    resize: vertical;
    min-height: 40px;
}

.card-number {
    position: absolute;
    top: var(--spacing-xs);
    right: var(--spacing-xs);
    background: var(--gray-100);
    color: var(--gray-500);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
}

/* Floating Filter Button */
.floating-filter-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1001;
    animation: slideInRight 0.3s ease-out;
}

.btn-float-filter {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border: none;
    color: white;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 100px;
    justify-content: center;
}

.btn-float-filter:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
    background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
}

.btn-float-filter:active {
    transform: translateY(0);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100px);
    }
}

.fab {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border: none;
    color: white;
    padding: var(--spacing-lg) var(--spacing-2xl);
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-xl);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    position: relative;
    min-width: 240px;
    justify-content: center;
}

.fab:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.fab-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--danger-color);
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

/* Button Styles */
.btn {
    padding: var(--spacing-md) var(--spacing-lg);
    border: none;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-fast);
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    text-decoration: none;
    font-size: 0.875rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-secondary {
    background: var(--gray-600);
    color: white;
}

.btn-secondary:hover {
    background: var(--gray-700);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--gray-300);
    color: var(--gray-700);
}

.btn-outline:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Empty States */
.empty-state,
.initial-state {
    text-align: center;
    padding: var(--spacing-2xl);
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
}

.empty-icon,
.initial-icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: var(--spacing-lg);
}

.empty-state h3,
.initial-state h3 {
    margin: 0 0 var(--spacing-md) 0;
    color: var(--gray-800);
    font-size: 1.5rem;
    font-weight: 600;
}

.empty-state p,
.initial-state p {
    color: var(--gray-600);
    margin-bottom: var(--spacing-xl);
    font-size: 1rem;
}

.initial-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .students-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 1024px) {
    .header-content {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
        text-align: center;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .date-navigation {
        justify-content: center;
    }
    
    .students-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .attendance-container {
        padding: var(--spacing-md);
    }
    
    .page-header {
        padding: var(--spacing-lg);
    }
    
    .header-info {
        flex-direction: column;
        text-align: center;
        gap: var(--spacing-md);
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-sm);
    }
    
    .students-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-sm);
    }
    
    .fab {
        padding: var(--spacing-md) var(--spacing-lg);
        font-size: 0.875rem;
        min-width: 200px;
    }
    
    .initial-actions {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .students-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-sm);
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.6s ease-out;
}

.slide-up {
    animation: slideUp 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize attendance tracking
    let attendanceData = {};
    let totalStudents = document.querySelectorAll('.student-card').length;
    
    // Filter Toggle Functionality
    const filterToggle = document.getElementById('filterToggle');
    const filterForm = document.querySelector('.filter-form');
    const filterSection = document.querySelector('.filter-section');
    const toggleIcon = filterToggle.querySelector('i');
    const toggleText = filterToggle.querySelector('.toggle-text');
    const floatingFilterBtn = document.getElementById('floatingFilterBtn');
    
    function updateFilterToggleUI(isCollapsed) {
        if (isCollapsed) {
            // Collapsed state
            filterForm.classList.add('collapsed');
            filterSection.classList.add('collapsed');
            filterToggle.classList.add('collapsed');
            toggleIcon.className = 'fas fa-chevron-down';
            if (toggleText) toggleText.textContent = 'Tampilkan Menu';
            
            // Show floating button
            floatingFilterBtn.style.display = 'block';
            floatingFilterBtn.style.animation = 'slideInRight 0.3s ease-out';
        } else {
            // Expanded state
            filterForm.classList.remove('collapsed');
            filterSection.classList.remove('collapsed');
            filterToggle.classList.remove('collapsed');
            toggleIcon.className = 'fas fa-chevron-up';
            if (toggleText) toggleText.textContent = 'Sembunyikan Menu';
            
            // Hide floating button
            floatingFilterBtn.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                floatingFilterBtn.style.display = 'none';
            }, 300);
        }
    }
    
    filterToggle.addEventListener('click', function() {
        const isCollapsed = filterForm.classList.contains('collapsed');
        updateFilterToggleUI(!isCollapsed);
        
        // Save state to localStorage
        localStorage.setItem('filter-collapsed', !isCollapsed);
    });
    
    // Floating filter button click handler
    floatingFilterBtn?.querySelector('.btn-float-filter').addEventListener('click', function() {
        updateFilterToggleUI(false);
        localStorage.setItem('filter-collapsed', false);
    });
    
    // Restore filter state from localStorage
    const savedFilterState = localStorage.getItem('filter-collapsed');
    if (savedFilterState === 'true') {
        updateFilterToggleUI(true);
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
    
    // Update counters
    function updateCounters() {
        const attendedCount = Object.keys(attendanceData).length;
        const pendingCount = totalStudents - attendedCount;
        
        document.getElementById('attendedCount').textContent = attendedCount;
        document.getElementById('pendingCount').textContent = pendingCount;
        
        const fabBadge = document.getElementById('fabBadge');
        if (attendedCount > 0) {
            fabBadge.textContent = attendedCount;
            fabBadge.style.display = 'flex';
        } else {
            fabBadge.style.display = 'none';
        }
        
        // Update status indicators
        document.querySelectorAll('.student-card').forEach(card => {
            const siswaId = card.dataset.siswaId;
            const indicator = card.querySelector('.status-indicator');
            if (attendanceData[siswaId]) {
                indicator.dataset.status = attendanceData[siswaId].status;
            } else {
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
            
            // Update counters
            updateCounters();
            
            console.log('Updated attendance:', attendanceData);
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
                
                // Reset all buttons
                card.querySelectorAll('.btn-attendance').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Activate hadir button
                hadirButton.classList.add('active');
                
                // Update data
                attendanceData[siswaId] = {
                    status: 'hadir',
                    keterangan: ''
                };
                
                // Animation
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 200);
                
                // Update counters on last iteration
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
    
    // Filter functionality with dropdown
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
        dateInput.dispatchEvent(new Event('change'));
    });
    
    document.getElementById('nextDay')?.addEventListener('click', function() {
        const dateInput = document.getElementById('tanggal');
        const currentDate = new Date(dateInput.value);
        currentDate.setDate(currentDate.getDate() + 1);
        dateInput.value = currentDate.toISOString().split('T')[0];
        dateInput.dispatchEvent(new Event('change'));
    });
    
    // Auto-submit on change
    document.getElementById('tanggal')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    document.getElementById('kelas')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Save All functionality
    document.getElementById('saveAll')?.addEventListener('click', function() {
        const button = this;
        const originalHTML = button.innerHTML;
        
        if (Object.keys(attendanceData).length === 0) {
            showNotification('Silakan pilih status kehadiran untuk setidaknya satu siswa', 'warning');
            return;
        }
        
        if (Object.keys(attendanceData).length < totalStudents) {
            const remaining = totalStudents - Object.keys(attendanceData).length;
            if (!confirm(`Masih ada ${remaining} siswa yang belum dipilih. Lanjutkan?`)) {
                return;
            }
        }
        
        button.innerHTML = '<i class="fas fa-spinner spinner"></i> Menyimpan...';
        button.disabled = true;
        
        // Prepare data
        const formData = new FormData();
        formData.append('tanggal', document.getElementById('tanggal').value);
        formData.append('kelas', document.getElementById('kelas')?.value || '<?= $userKelas ?? '' ?>');
        
        const attendanceArray = Object.keys(attendanceData).map(siswaId => ({
            siswa_id: siswaId,
            status: attendanceData[siswaId].status,
            keterangan: attendanceData[siswaId].keterangan || ''
        }));
        
        formData.append('attendance_data', JSON.stringify(attendanceArray));
        
        // Submit
        fetch('<?= base_url('admin/absensi/save_all') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Data absensi berhasil disimpan!', 'success');
                
                // Success animation
                document.querySelectorAll('.student-card').forEach((card, index) => {
                    setTimeout(() => {
                        card.style.background = 'linear-gradient(135deg, #ecfdf5, #d1fae5)';
                        setTimeout(() => {
                            card.style.background = '';
                        }, 1000);
                    }, index * 30);
                });
                
                // Reset data
                attendanceData = {};
                updateCounters();
            } else {
                showNotification(data.message || 'Gagal menyimpan data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Gagal menyimpan data: ' + error.message, 'error');
        })
        .finally(() => {
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }, 1000);
        });
    });
    
    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            transform: translateX(400px);
            transition: all 0.3s ease;
            max-width: 400px;
            display: flex;
            align-items: center;
            gap: 12px;
        `;
        
        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
            info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
        };
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        notification.style.background = colors[type] || colors.info;
        
        notification.innerHTML = `
            <i class="fas ${icons[type] || icons.info}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px; padding: 0;">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // Initialize
    updateCounters();
    
    // Add fade-in animation to cards
    document.querySelectorAll('.student-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
});

// Toggle notes function
function toggleNotes(button) {
    const notesInput = button.parentElement.querySelector('.notes-input');
    const isVisible = notesInput.style.display !== 'none';
    
    if (isVisible) {
        notesInput.style.display = 'none';
        button.innerHTML = '<i class="fas fa-sticky-note"></i> Catatan';
    } else {
        notesInput.style.display = 'block';
        button.innerHTML = '<i class="fas fa-sticky-note"></i> Tutup';
        notesInput.querySelector('textarea').focus();
    }
}
</script>

<?= $this->endSection() ?>

