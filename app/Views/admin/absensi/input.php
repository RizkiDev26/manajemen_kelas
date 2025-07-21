<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Add margin to prevent header being too close to sidebar -->
<div style="margin-left: 20px; margin-right: 20px;">

<!-- Debug Info (remove later) -->
<?php if (ENVIRONMENT === 'development'): ?>
<div class="alert alert-info small mb-3">
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

<!-- Students Grid Layout -->
<div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-32 animate-fade-in-up">
    <?php foreach ($students as $index => $student): ?>
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1" data-siswa-id="<?= $student['siswa_id'] ?>">
        <!-- Baris atas: Avatar dan Data Siswa -->
        <div class="flex items-center p-4 space-x-4">
            <!-- Kolom kiri: Avatar -->
            <div class="flex-shrink-0">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">
                        <?= strtoupper(substr($student['nama'], 0, 2)) ?>
                    </span>
                </div>
            </div>
            
            <!-- Kolom kanan: Data Siswa -->
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-semibold text-gray-900 truncate mb-1">
                    <?= $student['nama'] ?>
                </h3>
                <div class="space-y-1">
                    <p class="text-sm text-gray-600 truncate">
                        <span class="font-medium">NISN:</span> <?= $student['nisn'] ?? '-' ?>
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Gender:</span> 
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= isset($student['jk']) && $student['jk'] == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' ?>">
                            <?= isset($student['jk']) ? ($student['jk'] == 'L' ? 'Laki-laki' : 'Perempuan') : '-' ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Baris kedua: Tombol Status Kehadiran -->
        <div class="px-4 pb-4">
            <div class="grid grid-cols-2 gap-2">
                <button type="button" class="btn-attendance group relative overflow-hidden rounded-lg border-2 transition-all duration-300 <?= $student['status']==='hadir' ? 'border-green-500 bg-green-500 text-white shadow-lg' : 'border-green-200 bg-green-50 text-green-700 hover:border-green-300 hover:bg-green-100' ?>" data-status="hadir">
                    <div class="flex flex-col items-center justify-center py-3">
                        <i class="fas fa-check text-lg mb-1"></i>
                        <span class="text-xs font-semibold uppercase tracking-wider">Hadir</span>
                    </div>
                </button>
                
                <button type="button" class="btn-attendance group relative overflow-hidden rounded-lg border-2 transition-all duration-300 <?= $student['status']==='sakit' ? 'border-yellow-500 bg-yellow-500 text-white shadow-lg' : 'border-yellow-200 bg-yellow-50 text-yellow-700 hover:border-yellow-300 hover:bg-yellow-100' ?>" data-status="sakit">
                    <div class="flex flex-col items-center justify-center py-3">
                        <i class="fas fa-thermometer-half text-lg mb-1"></i>
                        <span class="text-xs font-semibold uppercase tracking-wider">Sakit</span>
                    </div>
                </button>
                
                <button type="button" class="btn-attendance group relative overflow-hidden rounded-lg border-2 transition-all duration-300 <?= $student['status']==='izin' ? 'border-blue-500 bg-blue-500 text-white shadow-lg' : 'border-blue-200 bg-blue-50 text-blue-700 hover:border-blue-300 hover:bg-blue-100' ?>" data-status="izin">
                    <div class="flex flex-col items-center justify-center py-3">
                        <i class="fas fa-hand-paper text-lg mb-1"></i>
                        <span class="text-xs font-semibold uppercase tracking-wider">Izin</span>
                    </div>
                </button>
                
                <button type="button" class="btn-attendance group relative overflow-hidden rounded-lg border-2 transition-all duration-300 <?= $student['status']==='alpha' ? 'border-red-500 bg-red-500 text-white shadow-lg' : 'border-red-200 bg-red-50 text-red-700 hover:border-red-300 hover:bg-red-100' ?>" data-status="alpha">
                    <div class="flex flex-col items-center justify-center py-3">
                        <i class="fas fa-times text-lg mb-1"></i>
                        <span class="text-xs font-semibold uppercase tracking-wider">Alpha</span>
                    </div>
                </button>
            </div>
        </div>
        
        <input type="hidden" class="student-keterangan" value="<?= $student['keterangan'] ?? '' ?>">
    </div>
    <?php endforeach; ?>
</div>

<!-- Bottom Action Bar -->
<div class="bottom-action-bar">
    <button type="button" class="btn-submit" id="saveAll">
        <i class="fas fa-paper-plane"></i>
        Kirim Daftar Hadir
        <span class="pending-badge" id="pendingCount" style="display: none;">0</span>
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
/* Additional custom styles for attendance system */
.btn-attendance {
    position: relative;
}

.btn-attendance::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s;
}

.btn-attendance:hover::before {
    left: 100%;
}

/* Pulse animation for pending submissions */
.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
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

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Header styling remains the same */
.attendance-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 16px;
    margin-bottom: 24px;
    padding: 24px;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
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
    position: relative;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #764ba2, #667eea);
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.4);
}

.pending-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ef4444;
    color: white;
    font-size: 12px;
    font-weight: 700;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
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
    
    .btn-submit {
        padding: 14px 32px;
        font-size: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Student attendance tracking
    let attendanceData = {};
    let pendingCount = 0;
    
    // Initialize attendance data from existing statuses
    document.querySelectorAll('[data-siswa-id]').forEach(card => {
        const siswaId = card.dataset.siswaId;
        const activeButton = card.querySelector('.btn-attendance.border-green-500, .btn-attendance.border-yellow-500, .btn-attendance.border-blue-500, .btn-attendance.border-red-500');
        if (activeButton) {
            const status = activeButton.dataset.status;
            attendanceData[siswaId] = {
                status: status,
                keterangan: card.querySelector('.student-keterangan').value || ''
            };
        }
    });
    
    // Update pending count display
    function updatePendingCount() {
        const count = Object.keys(attendanceData).length;
        const badge = document.getElementById('pendingCount');
        const button = document.getElementById('saveAll');
        
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
        
        if (button) {
            if (count > 0) {
                button.classList.add('pulse-animation');
            } else {
                button.classList.remove('pulse-animation');
            }
        }
    }
    
    // Handle attendance button clicks
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-attendance, .btn-attendance *')) {
            const button = e.target.closest('.btn-attendance');
            const card = button.closest('[data-siswa-id]');
            const siswaId = card.dataset.siswaId;
            const status = button.dataset.status;
            
            // Remove active states from all buttons in this card
            card.querySelectorAll('.btn-attendance').forEach(btn => {
                const btnStatus = btn.dataset.status;
                // Remove active classes and add inactive classes
                btn.className = btn.className.replace(/border-\w+-500|bg-\w+-500|text-white|shadow-lg/g, '');
                
                // Add inactive state classes based on status
                if (btnStatus === 'hadir') {
                    btn.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
                    btn.classList.remove('border-green-500', 'bg-green-500', 'text-white', 'shadow-lg');
                } else if (btnStatus === 'sakit') {
                    btn.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
                    btn.classList.remove('border-yellow-500', 'bg-yellow-500', 'text-white', 'shadow-lg');
                } else if (btnStatus === 'izin') {
                    btn.classList.add('border-blue-200', 'bg-blue-50', 'text-blue-700');
                    btn.classList.remove('border-blue-500', 'bg-blue-500', 'text-white', 'shadow-lg');
                } else if (btnStatus === 'alpha') {
                    btn.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
                    btn.classList.remove('border-red-500', 'bg-red-500', 'text-white', 'shadow-lg');
                }
            });
            
            // Add active state to clicked button
            if (status === 'hadir') {
                button.classList.remove('border-green-200', 'bg-green-50', 'text-green-700');
                button.classList.add('border-green-500', 'bg-green-500', 'text-white', 'shadow-lg');
            } else if (status === 'sakit') {
                button.classList.remove('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
                button.classList.add('border-yellow-500', 'bg-yellow-500', 'text-white', 'shadow-lg');
            } else if (status === 'izin') {
                button.classList.remove('border-blue-200', 'bg-blue-50', 'text-blue-700');
                button.classList.add('border-blue-500', 'bg-blue-500', 'text-white', 'shadow-lg');
            } else if (status === 'alpha') {
                button.classList.remove('border-red-200', 'bg-red-50', 'text-red-700');
                button.classList.add('border-red-500', 'bg-red-500', 'text-white', 'shadow-lg');
            }
            
            // Update attendance data
            attendanceData[siswaId] = {
                status: status,
                keterangan: card.querySelector('.student-keterangan').value || ''
            };
            
            // Update pending count
            updatePendingCount();
            
            // Add visual feedback
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                button.style.transform = '';
            }, 150);
            
            console.log('Updated attendance for student', siswaId, ':', attendanceData[siswaId]);
        }
    });
    
    // Mark All Present
    document.getElementById('markAllHadir')?.addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        button.disabled = true;
        
        document.querySelectorAll('[data-siswa-id]').forEach((card, index) => {
            setTimeout(() => {
                const siswaId = card.dataset.siswaId;
                const hadirButton = card.querySelector('[data-status="hadir"]');
                
                // Reset all buttons in card to inactive state
                card.querySelectorAll('.btn-attendance').forEach(btn => {
                    const btnStatus = btn.dataset.status;
                    btn.className = btn.className.replace(/border-\w+-500|bg-\w+-500|text-white|shadow-lg/g, '');
                    
                    if (btnStatus === 'hadir') {
                        btn.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
                    } else if (btnStatus === 'sakit') {
                        btn.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
                    } else if (btnStatus === 'izin') {
                        btn.classList.add('border-blue-200', 'bg-blue-50', 'text-blue-700');
                    } else if (btnStatus === 'alpha') {
                        btn.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
                    }
                });
                
                // Activate hadir button
                hadirButton.classList.remove('border-green-200', 'bg-green-50', 'text-green-700');
                hadirButton.classList.add('border-green-500', 'bg-green-500', 'text-white', 'shadow-lg');
                
                // Update data
                attendanceData[siswaId] = {
                    status: 'hadir',
                    keterangan: ''
                };
                
                // Add animation
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 200);
                
                // Update pending count on last card
                if (index === document.querySelectorAll('[data-siswa-id]').length - 1) {
                    updatePendingCount();
                    
                    // Restore button
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                        showNotification('Semua siswa telah ditandai hadir!', 'success');
                    }, 300);
                }
            }, index * 50); // Stagger animation
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
    
    // Auto-submit on date/class change
    document.getElementById('tanggal')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    const kelasSelect = document.getElementById('kelas');
    if (kelasSelect) {
        kelasSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    // Save All Attendance with enhanced UX
    document.getElementById('saveAll')?.addEventListener('click', function() {
        const button = this;
        const originalHTML = button.innerHTML;
        
        // Validate all selections
        const totalStudents = document.querySelectorAll('[data-siswa-id]').length;
        const selectedStudents = Object.keys(attendanceData).length;
        
        if (selectedStudents === 0) {
            showNotification('Silakan pilih status kehadiran untuk setidaknya satu siswa', 'warning');
            return;
        }
        
        if (selectedStudents < totalStudents) {
            const remaining = totalStudents - selectedStudents;
            if (!confirm(`Masih ada ${remaining} siswa yang belum dipilih status kehadirannya. Lanjutkan menyimpan?`)) {
                return;
            }
        }
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        button.disabled = true;
        button.classList.add('opacity-75');
        
        // Create progress indicator
        const progressBar = createProgressBar();
        
        // Prepare data for submission
        const formData = new FormData();
        formData.append('tanggal', document.getElementById('tanggal').value);
        formData.append('kelas', document.getElementById('kelas')?.value || '<?= $userKelas ?? '' ?>');
        
        // Convert attendanceData object to array format expected by controller
        const attendanceArray = Object.keys(attendanceData).map(siswaId => ({
            siswa_id: siswaId,
            status: attendanceData[siswaId].status,
            keterangan: attendanceData[siswaId].keterangan || ''
        }));
        
        formData.append('attendance_data', JSON.stringify(attendanceArray));
        // formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
        // Submit via fetch
        console.log('Submitting attendance data:', {
            tanggal: document.getElementById('tanggal').value,
            kelas: document.getElementById('kelas')?.value || '<?= $userKelas ?? '' ?>',
            attendanceArray: attendanceArray
        });
        
        fetch('<?= base_url('admin/absensi/save_all') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            progressBar.complete();
            
            if (data.success) {
                // Success animation
                document.querySelectorAll('[data-siswa-id]').forEach((card, index) => {
                    setTimeout(() => {
                        card.style.background = 'linear-gradient(135deg, #ecfdf5, #d1fae5)';
                        card.style.transform = 'scale(1.02)';
                        setTimeout(() => {
                            card.style.transform = '';
                        }, 200);
                    }, index * 30);
                });
                
                showNotification('Data absensi berhasil disimpan!', 'success');
                
                // Reset pending count
                attendanceData = {};
                updatePendingCount();
                
                // Confetti effect (if available)
                if (typeof confetti !== 'undefined') {
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 }
                    });
                }
            } else {
                showNotification(data.message || 'Gagal menyimpan data', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            progressBar.complete();
            showNotification('Gagal menyimpan data: ' + error.message, 'error');
        })
        .finally(() => {
            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.disabled = false;
                button.classList.remove('opacity-75');
            }, 1000);
        });
    });
    
    // Notification function
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
        
        // Set background based on type
        if (type === 'success') {
            notification.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        } else if (type === 'error') {
            notification.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        } else if (type === 'warning') {
            notification.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
        } else {
            notification.style.background = 'linear-gradient(135deg, #3b82f6, #2563eb)';
        }
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-times-circle' : 
                    type === 'warning' ? 'fa-exclamation-triangle' :
                    'fa-info-circle';
        
        notification.innerHTML = `
            <i class="fas ${icon}" style="font-size: 18px;"></i>
            <span style="flex: 1;">${message}</span>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px; padding: 0;">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
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
            progress += Math.random() * 20;
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
    
    // Initialize pending count
    updatePendingCount();
});
</script>

<?= $this->endSection() ?>
