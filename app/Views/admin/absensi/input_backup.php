<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Fullscreen Container with Responsive Grid -->
<div class="w-full min-h-screen bg-gray-50">

<!-- Debug Info (remove later) -->
<?php if (ENVIRONMENT === 'development'): ?>
<div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6 text-sm text-blue-800">
    <strong>Debug Info:</strong> 
    selectedKelas = <?= $selectedKelas ? $selectedKelas : 'NULL' ?>, 
    students count = <?= count($students) ?>, 
    userRole = <?= $userRole ?>
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
$englishDay = date('l', strtotime($selectedDate));
$indonesianDay = $dayNames[$englishDay] ?? $englishDay;
?>

<?php if ($selectedKelas && !empty($students)): ?>
<!-- Header Section -->
<div class="attendance-header">
    <div class="header-content">
        <div class="header-title">
            <h2 class="mb-0">Absensi Kelas <?= $selectedKelas ?></h2>
            <p class="mb-0 date-display"><?= $indonesianDay ?>, <?= date('d M Y', strtotime($selectedDate)) ?></p>
        </div>
        
        <!-- All filter controls in one row -->
        <div class="filter-controls">
            <form id="filterForm" method="GET" class="filter-form">
                <!-- Previous day button -->
                <button type="button" class="btn btn-nav" id="prevDay" title="Hari sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <!-- Date input -->
                <input type="date" class="form-control" id="tanggal" name="tanggal" 
                       value="<?= $selectedDate ?>" required>
                
                <!-- Next day button -->
                <button type="button" class="btn btn-nav" id="nextDay" title="Hari selanjutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <?php if ($userRole === 'admin'): ?>
                <!-- Class dropdown -->
                <select class="form-control" id="kelas" name="kelas" required>
                    <option value="">Kelas</option>
                    <?php foreach ($allKelas as $kelas): ?>
                    <option value="<?= $kelas['kelas'] ?>" 
                            <?= $selectedKelas === $kelas['kelas'] ? 'selected' : '' ?>>
                        <?= $kelas['kelas'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php else: ?>
                <input type="hidden" name="kelas" value="<?= $userKelas ?>">
                <?php endif; ?>
                
                <!-- Apply filter button - Hidden since auto-reload is enabled -->
                <button type="submit" class="btn btn-apply" style="display: none;">
                    Terapkan
                </button>
                
                <!-- Auto-load indicator -->
                <span class="auto-reload-indicator">
                    <i class="fas fa-magic"></i> Auto-reload aktif
                </span>
                
                <!-- Mark all present button -->
                <button type="button" class="btn btn-mark-all" id="markAllHadir">
                    Hadir Semua
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Student Cards Grid -->
<div class="students-grid">
    <?php foreach ($students as $index => $student): ?>
    <div class="student-card" data-siswa-id="<?= $student['siswa_id'] ?>">
        <div class="student-card-layout">
            <!-- Kolom Kiri: Avatar -->
            <div class="student-avatar">
                <div class="avatar-circle">
                    <span class="avatar-initials">
                        <?= strtoupper(substr($student['nama'], 0, 2)) ?>
                    </span>
                </div>
            </div>
            
            <!-- Kolom Kanan: Informasi Siswa -->
            <div class="student-details">
                <div class="info-row">
                    <span class="info-label">NAMA:</span>
                    <span class="info-value"><?= $student['nama'] ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">JENIS KELAMIN:</span>
                    <span class="info-value"><?= isset($student['jk']) ? ($student['jk'] == 'L' ? 'Laki-laki' : 'Perempuan') : '-' ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">NISN:</span>
                    <span class="info-value"><?= $student['nisn'] ?? '-' ?></span>
                </div>
            </div>
        </div>
        
        <!-- Status Kehadiran (Full Width Below) -->
        <div class="attendance-section">
            <h6 class="attendance-title">STATUS KEHADIRAN</h6>
            <div class="attendance-buttons">
                <button type="button" class="btn-attendance btn-hadir <?= $student['status']==='hadir' ? 'active' : '' ?>" 
                        data-status="hadir">
                    HADIR
                </button>
                <button type="button" class="btn-attendance btn-sakit <?= $student['status']==='sakit' ? 'active' : '' ?>" 
                        data-status="sakit">
                    SAKIT
                </button>
                <button type="button" class="btn-attendance btn-izin <?= $student['status']==='izin' ? 'active' : '' ?>" 
                        data-status="izin">
                    IZIN
                </button>
                <button type="button" class="btn-attendance btn-alpha <?= $student['status']==='alpha' ? 'active' : '' ?>" 
                        data-status="alpha">
                    ALPH
                </button>
            </div>
        </div>
        <input type="hidden" class="student-keterangan" value="<?= $student['keterangan'] ?? '' ?>">
    </div>
    <?php endforeach; ?>
</div>

<!-- Bottom Action Bar -->
<div class="bottom-action-bar">
    <button type="button" class="btn btn-primary btn-submit" id="saveAll">
        Kirim Daftar Hadir
    </button>
</div>
<?php elseif ($selectedKelas): ?>
<div class="card shadow mb-4">
    <div class="card-body text-center">
        <div class="my-4">
            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
            <h5 class="text-gray-600">Tidak ada siswa ditemukan untuk kelas <?= $selectedKelas ?></h5>
            <p class="text-gray-500">Pastikan kelas yang dipilih memiliki siswa aktif.</p>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card shadow mb-4">
    <div class="card-body text-center">
        <div class="my-4">
            <i class="fas fa-calendar-alt fa-3x text-gray-300 mb-3"></i>
            <h5 class="text-gray-600">Pilih tanggal dan kelas untuk memulai input absensi</h5>
            <p class="text-gray-500">Gunakan filter di atas untuk memilih tanggal dan kelas.</p>
        </div>
    </div>
</div>
<?php endif; ?>

</div> <!-- Close the margin wrapper -->

<style>
/* Header Styles */
.attendance-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 32px;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
    position: relative;
    overflow: hidden;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-title h2 {
    color: white;
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.date-display {
    color: rgba(255,255,255,0.9);
    font-size: 16px;
    margin: 5px 0 0 0;
    font-weight: 500;
}

.filter-controls {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-form {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.form-control {
    padding: 10px 16px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    min-width: 140px;
}

.form-control:focus {
    background: white;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    transform: translateY(-1px);
    outline: none;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-nav {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    width: 40px;
    height: 40px;
    padding: 0;
    backdrop-filter: blur(10px);
}

.btn-nav:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-1px);
}

.btn-apply {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.8);
    color: white;
    backdrop-filter: blur(10px);
}

.btn-apply:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.btn-mark-all {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-mark-all:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

/* Students Grid */
.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 120px;
    padding: 0 8px;
}

/* Student Card */
.student-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.student-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.student-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    border-color: #d1d5db;
}

/* Two Column Layout */
.student-card-layout {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

/* Avatar Column (Left) */
.student-avatar {
    flex-shrink: 0;
}

.avatar-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 20px;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    border: 3px solid rgba(255,255,255,0.9);
}

.avatar-initials {
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

/* Student Details Column (Right) */
.student-details {
    flex: 1;
}

.info-row {
    display: flex;
    margin-bottom: 8px;
    align-items: center;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    font-weight: 700;
    color: #374151;
    min-width: 120px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-right: 8px;
}

.info-value {
    color: #6b7280;
    font-weight: 600;
    flex: 1;
    font-size: 14px;
}

/* Attendance Section */
.attendance-section {
    border-top: 2px solid #e5e7eb;
    padding-top: 20px;
}

.attendance-title {
    text-align: center;
    margin: 0 0 16px 0;
    font-size: 14px;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.attendance-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}

.btn-attendance {
    padding: 12px 8px;
    border: 2px solid;
    border-radius: 10px;
    font-weight: 700;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: center;
    background: white;
}

.btn-hadir {
    border-color: #10b981;
    color: #10b981;
}
.btn-hadir:hover, .btn-hadir.active {
    background: #10b981;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-sakit {
    border-color: #f59e0b;
    color: #f59e0b;
}
.btn-sakit:hover, .btn-sakit.active {
    background: #f59e0b;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-izin {
    border-color: #3b82f6;
    color: #3b82f6;
}
.btn-izin:hover, .btn-izin.active {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-alpha {
    border-color: #ef4444;
    color: #ef4444;
}
.btn-alpha:hover, .btn-alpha.active {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Bottom Action Bar */
.bottom-action-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,250,252,0.95));
    backdrop-filter: blur(20px);
    border-top: 1px solid rgba(148, 163, 184, 0.1);
    padding: 20px;
    box-shadow: 0 -8px 32px rgba(0,0,0,0.1);
    z-index: 1000;
    display: flex;
    justify-content: center;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    padding: 16px 40px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
    min-width: 200px;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #764ba2, #667eea);
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .filter-controls {
        justify-content: center;
        width: 100%;
    }
    
    .filter-form {
        flex-direction: column;
        width: 100%;
    }
    
    .form-control {
        width: 100%;
    }
    
    .students-grid {
        grid-template-columns: 1fr;
        gap: 20px;
        margin-bottom: 100px;
    }
    
    .student-card {
        padding: 20px;
    }
    
    .student-card-layout {
        gap: 16px;
    }
    
    .avatar-circle {
        width: 60px;
        height: 60px;
        font-size: 18px;
    }
    
    .info-label {
        min-width: 100px;
        font-size: 11px;
    }
    
    .attendance-buttons {
        gap: 6px;
    }
    
    .btn-attendance {
        padding: 10px 6px;
        font-size: 10px;
    }
}

@media (max-width: 480px) {
    .attendance-header {
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .header-title h2 {
        font-size: 24px;
    }
    
    .student-card-layout {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .student-details {
        width: 100%;
    }
    
    .info-row {
        justify-content: center;
    }
    
    .attendance-buttons {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    
    .btn-attendance {
        padding: 12px;
        font-size: 11px;
    }
    
    .btn-submit {
        padding: 14px 32px;
        font-size: 14px;
    }
}

/* Animation Keyframes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.students-grid {
    animation: fadeInUp 0.6s ease-out;
    position: relative;
}

/* Loading overlay styles */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    border-radius: 16px;
}

.loading-overlay .fa-spinner {
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

/* Auto-reload indicator styling */
.auto-reload-indicator {
    color: rgba(255,255,255,0.8);
    font-size: 12px;
    margin-left: 10px;
    background: rgba(255,255,255,0.1);
    padding: 4px 8px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.2);
}

.auto-reload-indicator i {
    margin-right: 4px;
    color: #10b981;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Previous/Next day navigation with auto-reload
    document.getElementById('prevDay')?.addEventListener('click', function() {
        const dateInput = document.getElementById('tanggal');
        const currentDate = new Date(dateInput.value);
        currentDate.setDate(currentDate.getDate() - 1);
        const newDate = currentDate.toISOString().split('T')[0];
        
        // Update date input
        dateInput.value = newDate;
        
        // Trigger the change event to use auto-reload system
        dateInput.dispatchEvent(new Event('change'));
    });
    
    document.getElementById('nextDay')?.addEventListener('click', function() {
        const dateInput = document.getElementById('tanggal');
        const currentDate = new Date(dateInput.value);
        currentDate.setDate(currentDate.getDate() + 1);
        const newDate = currentDate.toISOString().split('T')[0];
        
        // Update date input
        dateInput.value = newDate;
        
        // Trigger the change event to use auto-reload system
        dateInput.dispatchEvent(new Event('change'));
    });

    // Function to attach event listeners to attendance buttons
    function attachAttendanceButtonListeners() {
        document.querySelectorAll('.btn-attendance').forEach(function(btn) {
            btn.removeEventListener('click', handleAttendanceClick); // Remove existing listeners
            btn.addEventListener('click', handleAttendanceClick);
        });
    }

    // Attendance button click handler
    function handleAttendanceClick() {
        const card = this.closest('.student-card');
        const buttons = card.querySelectorAll('.btn-attendance');
        
        // Remove active class from all buttons in this card
        buttons.forEach(b => {
            b.classList.remove('active');
            b.style.transform = '';
        });
        
        // Add active class to clicked button with animation
        this.classList.add('active');
        
        // Add ripple effect
        addRippleEffect(this);
        
        // Haptic feedback simulation
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    }

    // Initial attachment of event listeners
    attachAttendanceButtonListeners();
    
    // Auto-load data when date changes - Reload with new parameters
    document.getElementById('tanggal')?.addEventListener('change', function() {
        const newDate = this.value;
        console.log('Date changed to:', newDate);
        
        // Try multiple selectors to find kelas value
        let kelas = '';
        const kelasSelect = document.getElementById('kelas');
        const kelasInput = document.querySelector('input[name="kelas"]');
        
        if (kelasSelect && kelasSelect.value) {
            kelas = kelasSelect.value;
        } else if (kelasInput && kelasInput.value) {
            kelas = kelasInput.value;
        }
        
        console.log('Found kelas:', kelas);
        
        if (kelas && newDate) {
            // Show loading indicator immediately
            const studentsGrid = document.querySelector('.students-grid');
            if (studentsGrid) {
                studentsGrid.style.opacity = '0.5';
                studentsGrid.style.pointerEvents = 'none';
                
                // Add loading overlay
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'loading-overlay';
                loadingDiv.innerHTML = '<div style="text-align: center; color: #667eea; font-weight: bold;"><i class="fas fa-spinner fa-spin"></i> Memuat data tanggal ' + newDate + '...</div>';
                studentsGrid.appendChild(loadingDiv);
            }
            
            // Create URL with parameters and reload page automatically
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('tanggal', newDate);
            currentUrl.searchParams.set('kelas', kelas);
            
            console.log('Navigating to:', currentUrl.toString());
            
            // Immediate redirect to maintain selected date
            window.location.href = currentUrl.toString();
        } else {
            console.log('Missing kelas or date for auto-load');
        }
    });
    
    // Auto-load data when class changes (for admin) - Reload with new parameters
    document.getElementById('kelas')?.addEventListener('change', function() {
        const newKelas = this.value;
        const date = document.getElementById('tanggal').value;
        
        console.log('Kelas changed to:', newKelas, 'Date:', date);
        
        if (newKelas && date) {
            // Show loading indicator immediately
            const studentsGrid = document.querySelector('.students-grid');
            if (studentsGrid) {
                studentsGrid.style.opacity = '0.5';
                studentsGrid.style.pointerEvents = 'none';
                
                // Add loading overlay
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'loading-overlay';
                loadingDiv.innerHTML = '<div style="text-align: center; color: #667eea; font-weight: bold;"><i class="fas fa-spinner fa-spin"></i> Memuat data kelas ' + newKelas + '...</div>';
                studentsGrid.appendChild(loadingDiv);
            }
            
            // Create URL with parameters and reload page automatically
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('tanggal', date);
            currentUrl.searchParams.set('kelas', newKelas);
            
            console.log('Navigating to:', currentUrl.toString());
            
            // Immediate redirect to maintain selected parameters
            window.location.href = currentUrl.toString();
        } else {
            console.log('Missing kelas or date for auto-load');
        }
    });

    // Enhanced mark all as hadir with smooth animations
    document.getElementById('markAllHadir')?.addEventListener('click', function() {
        const btn = this;
        
        // Add loading state
        btn.classList.add('loading');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menandai...';
        btn.disabled = true;
        
        // Animate cards one by one
        const cards = document.querySelectorAll('.student-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                const buttons = card.querySelectorAll('.btn-attendance');
                const hadirBtn = card.querySelector('.btn-hadir');
                
                buttons.forEach(b => b.classList.remove('active'));
                hadirBtn.classList.add('active');
                
                // Add success animation to card
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 200);
                
                // If this is the last card, restore button
                if (index === cards.length - 1) {
                    setTimeout(() => {
                        btn.classList.remove('loading');
                        btn.innerHTML = 'Hadir Semua';
                        btn.disabled = false;
                        showToast('Semua siswa telah ditandai hadir', 'success');
                    }, 300);
                }
            }, index * 100); // Stagger animation
        });
    });

    // Enhanced save all attendance with progress indication
    document.getElementById('saveAll')?.addEventListener('click', function() {
        const submitBtn = this;
        const cards = document.querySelectorAll('.student-card');
        let attendanceData = [];
        let hasError = false;

        // Validate all selections
        cards.forEach(card => {
            const siswaId = card.dataset.siswaId;
            const activeBtn = card.querySelector('.btn-attendance.active');
            
            if (!activeBtn) {
                hasError = true;
                // Highlight card with missing selection
                card.style.border = '2px solid #ef4444';
                setTimeout(() => {
                    card.style.border = '';
                }, 3000);
                return;
            }

            const status = activeBtn.dataset.status;
            const keterangan = card.querySelector('.student-keterangan').value;
            
            attendanceData.push({
                siswa_id: siswaId,
                status: status,
                keterangan: keterangan
            });
        });

        if (hasError) {
            showToast('Silakan pilih status kehadiran untuk semua siswa', 'error');
            // Shake effect for incomplete cards
            cards.forEach(card => {
                if (!card.querySelector('.btn-attendance.active')) {
                    card.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        card.style.animation = '';
                    }, 500);
                }
            });
            return;
        }

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        submitBtn.style.background = 'linear-gradient(135deg, #6b7280, #9ca3af)';

        // Create progress bar
        const progressBar = createProgressBar();
        
        // Save attendance data
        const tanggal = document.getElementById('tanggal').value;
        const kelas = document.querySelector('input[name="kelas"], select[name="kelas"]').value;

        const formData = new FormData();
        formData.append('tanggal', tanggal);
        formData.append('kelas', kelas);
        formData.append('attendance_data', JSON.stringify(attendanceData));

        fetch('<?= base_url('admin/absensi/save_all') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            progressBar.complete();
            
            if (data.success) {
                // Success animation
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('success-animation');
                        card.style.background = 'linear-gradient(135deg, #ecfdf5, #d1fae5)';
                    }, index * 50);
                });
                
                showToast(data.message, 'success');
                
                // Confetti effect
                if (typeof confetti !== 'undefined') {
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 }
                    });
                }
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            progressBar.complete();
            showToast('Terjadi kesalahan saat menyimpan data', 'error');
        })
        .finally(() => {
            // Restore button
            setTimeout(() => {
                submitBtn.innerHTML = 'Kirim Absen';
                submitBtn.disabled = false;
                submitBtn.style.background = '';
            }, 1000);
        });
    });

    // Ripple effect function
    function addRippleEffect(button) {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.6);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (rect.width / 2 - size / 2) + 'px';
        ripple.style.top = (rect.height / 2 - size / 2) + 'px';
        
        button.style.position = 'relative';
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    // Enhanced toast notifications
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
            transform: translateX(400px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 400px;
        `;
        
        if (type === 'success') {
            toast.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        } else if (type === 'error') {
            toast.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
            toast.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        } else {
            toast.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
            toast.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        }
        
        document.body.appendChild(toast);
        
        // Slide in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }, 4000);
    }

    // Progress bar function
    function createProgressBar() {
        const progressContainer = document.createElement('div');
        progressContainer.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(0,0,0,0.1);
            z-index: 10001;
        `;
        
        const progressBar = document.createElement('div');
        progressBar.style.cssText = `
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            width: 0%;
            transition: width 0.3s ease;
        `;
        
        progressContainer.appendChild(progressBar);
        document.body.appendChild(progressContainer);
        
        // Animate progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 200);
        
        return {
            complete: () => {
                clearInterval(interval);
                progressBar.style.width = '100%';
                setTimeout(() => {
                    progressContainer.remove();
                }, 500);
            }
        };
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
});
</script>

<?= $this->endSection() ?>
