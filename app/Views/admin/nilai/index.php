<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<!-- Page Header with Breadcrumb -->
<div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4 mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?= base_url('/admin') ?>" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-home w-4 h-4"></i>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 w-4 h-4"></i>
                            <span class="ml-1 text-sm font-medium text-gray-700">Nilai Siswa</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">
                <i class="fas fa-calendar-alt mr-1"></i>
                <?= date('d F Y') ?>
            </span>
            <span class="text-sm text-gray-400">•</span>
            <span class="text-sm text-gray-500">
                <i class="fas fa-clock mr-1"></i>
                <?= date('H:i') ?> WIB
            </span>
        </div>
    </div>
</div>

<!-- Page Title Section -->
<div class="mb-8">
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                Manajemen Nilai Siswa
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Kelola dan monitor nilai siswa secara komprehensif
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="<?= base_url('/admin/nilai/create') ?>?kelas=<?= $selectedKelas ?>&mapel=<?= $selectedMapel ?>" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i>
                Tambah Nilai Baru
            </a>
        </div>
    </div>
</div>
        <!-- Advanced Filter Panel -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filter & Pencarian Data
                </h2>
            </div>
            
            <div class="p-6">
                <form method="GET" action="<?= base_url('/admin/nilai') ?>" class="space-y-6" id="filterForm">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        
                        <!-- Mata Pelajaran Selection -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-book text-blue-600 mr-1"></i>
                                Mata Pelajaran
                            </label>
                            <div class="relative">
                                <select name="mapel" class="w-full pl-4 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent rounded-lg shadow-sm transition-all duration-200 bg-white hover:border-gray-400">
                                    <?php foreach ($mataPelajaranList as $code => $name): ?>
                                        <option value="<?= $code ?>" <?= $selectedMapel === $code ? 'selected' : '' ?>>
                                            <?= $name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Kelas Selection (Admin Only) -->
                        <?php if ($userRole === 'admin'): ?>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-users text-green-600 mr-1"></i>
                                Kelas
                            </label>
                            <div class="relative">
                                <select name="kelas" class="w-full pl-4 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent rounded-lg shadow-sm transition-all duration-200 bg-white hover:border-gray-400">
                                    <option value="">Semua Kelas</option>
                                    <?php foreach ($allKelas as $kelas): ?>
                                        <option value="<?= $kelas ?>" <?= $selectedKelas === $kelas ? 'selected' : '' ?>>
                                            Kelas <?= $kelas ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Quick Stats -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-chart-bar text-purple-600 mr-1"></i>
                                Statistik Cepat
                            </label>
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-3 border border-purple-200">
                                <div class="text-sm text-purple-700">
                                    <div class="flex justify-between">
                                        <span>Total Siswa:</span>
                                        <span class="font-semibold"><?= count($nilaiRekap ?? []) ?></span>
                                    </div>
                                    <div class="flex justify-between mt-1">
                                        <span>Sudah Dinilai:</span>
                                        <span class="font-semibold"><?= count(array_filter($nilaiRekap ?? [], function($s) { return isset($s['nilai_akhir']) && $s['nilai_akhir'] !== null; })) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-cogs text-gray-600 mr-1"></i>
                                Aksi
                            </label>
                            <div class="flex flex-col space-y-2">
                                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 font-medium">
                                    <i class="fas fa-search mr-2"></i>
                                    Terapkan Filter
                                </button>
                                <button type="button" onclick="resetFilters()" class="w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 font-medium">
                                    <i class="fas fa-undo mr-2"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Section -->
        <?php if ($selectedKelas && !empty($nilaiRekap)): ?>
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-table text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Daftar Nilai Kelas <?= $selectedKelas ?>
                            </h3>
                            <p class="text-sm text-gray-500">
                                Mata Pelajaran: <?= $mataPelajaranList[$selectedMapel] ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-users mr-1"></i>
                            <?= count($nilaiRekap) ?> Siswa
                        </span>
                        <button class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                            <i class="fas fa-download mr-1"></i>
                            Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-hashtag text-blue-600"></i>
                                    <span>No</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-user text-green-600"></i>
                                    <span>Siswa</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="fas fa-calendar-day text-orange-600"></i>
                                    <span>Harian</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="fas fa-clipboard-check text-purple-600"></i>
                                    <span>PTS</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="fas fa-graduation-cap text-red-600"></i>
                                    <span>PAS</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="fas fa-trophy text-yellow-600"></i>
                                    <span>Akhir</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="fas fa-cogs text-gray-600"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $no = 1; ?>
                        <?php foreach ($nilaiRekap as $siswa): ?>
                        <tr class="hover:bg-blue-50 transition-colors duration-200 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-blue-100 to-blue-200 rounded-full group-hover:from-blue-200 group-hover:to-blue-300 transition-all duration-200">
                                    <span class="text-sm font-bold text-blue-800"><?= $no++ ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= isset($siswa['nama']) && !empty($siswa['nama']) ? $siswa['nama'] : 'Nama tidak tersedia' ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    NIPD: <?= isset($siswa['nipd']) && !empty($siswa['nipd']) ? $siswa['nipd'] : '-' ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold text-gray-900">
                                        <?= isset($siswa['nilai_harian']) && $siswa['nilai_harian'] !== null ? number_format($siswa['nilai_harian'], 1) : '-' ?>
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                        <?= isset($siswa['jumlah_harian']) ? $siswa['jumlah_harian'] : 0 ?> nilai
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-bold text-gray-900">
                                    <?= isset($siswa['nilai_pts']) && $siswa['nilai_pts'] !== null ? number_format($siswa['nilai_pts'], 1) : '-' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-bold text-gray-900">
                                    <?= isset($siswa['nilai_pas']) && $siswa['nilai_pas'] !== null ? number_format($siswa['nilai_pas'], 1) : '-' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center px-4 py-2 rounded-lg font-bold text-lg
                                    <?php 
                                    if (isset($siswa['nilai_akhir']) && $siswa['nilai_akhir'] !== null) {
                                        $nilai = $siswa['nilai_akhir'];
                                        if ($nilai >= 85) echo 'bg-green-100 text-green-800';
                                        elseif ($nilai >= 75) echo 'bg-blue-100 text-blue-800';
                                        elseif ($nilai >= 65) echo 'bg-yellow-100 text-yellow-800';
                                        else echo 'bg-red-100 text-red-800';
                                    } else {
                                        echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?= isset($siswa['nilai_akhir']) && $siswa['nilai_akhir'] !== null ? number_format($siswa['nilai_akhir'], 1) : '-' ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <?php if(isset($siswa['id'])): ?>
                                    <a href="<?= base_url('/admin/nilai/detail/' . $siswa['id']) ?>?mapel=<?= $selectedMapel ?>" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('/admin/nilai/create') ?>?kelas=<?= $selectedKelas ?>&mapel=<?= $selectedMapel ?>&siswa_id=<?= $siswa['id'] ?>" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200"
                                       title="Tambah/Edit Nilai">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle"></i>
                        <span>Menampilkan <?= count($nilaiRekap) ?> dari <?= count($nilaiRekap) ?> siswa</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>
                            Sebelumnya
                        </button>
                        <button class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Selanjutnya
                            <i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Empty States -->
        <?php elseif ($selectedKelas && empty($nilaiRekap)): ?>
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12">
            <div class="text-center max-w-md mx-auto">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 mb-6">
                    <i class="fas fa-chart-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Belum Ada Data Nilai</h3>
                <p class="text-gray-600 text-lg mb-8">
                    Belum ada nilai yang tercatat untuk kelas 
                    <span class="font-semibold text-blue-600"><?= $selectedKelas ?></span> 
                    pada mata pelajaran 
                    <span class="font-semibold text-green-600"><?= $mataPelajaranList[$selectedMapel] ?></span>
                </p>
                <div class="space-y-4">
                    <a href="<?= base_url('/admin/nilai/create') ?>?kelas=<?= $selectedKelas ?>&mapel=<?= $selectedMapel ?>" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Mulai Input Nilai
                    </a>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Data akan otomatis tersimpan setelah input pertama
                    </div>
                </div>
            </div>
        </div>
        
        <?php else: ?>
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12">
            <div class="text-center max-w-md mx-auto">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 mb-6">
                    <i class="fas fa-search text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pilih Filter untuk Memulai</h3>
                <p class="text-gray-600 text-lg mb-8">
                    Silakan pilih mata pelajaran dan kelas untuk melihat data nilai siswa
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-book text-blue-600"></i>
                        <span>Pilih Mata Pelajaran</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-users text-green-600"></i>
                        <span>Pilih Kelas</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-search text-purple-600"></i>
                        <span>Tekan Filter</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-chart-line text-orange-600"></i>
                        <span>Lihat Hasil</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
    <?php if (session()->getFlashdata('success')): ?>
    <div id="success-toast" class="bg-white rounded-lg shadow-lg border-l-4 border-green-400 p-4 max-w-md transform transition-all duration-300 translate-x-full">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                    <i class="fas fa-check text-green-600"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900">Berhasil!</p>
                <p class="text-sm text-gray-600"><?= session()->getFlashdata('success') ?></p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="hideToast('success-toast')" class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div id="error-toast" class="bg-white rounded-lg shadow-lg border-l-4 border-red-400 p-4 max-w-md transform transition-all duration-300 translate-x-full">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900">Error!</p>
                <p class="text-sm text-gray-600"><?= session()->getFlashdata('error') ?></p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="hideToast('error-toast')" class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Memuat data...</span>
    </div>
</div>

<script>
// Enhanced JavaScript for CI4 + Tailwind Integration
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize page
    initializeToasts();
    initializeFormHandlers();
    initializeTableEnhancements();
    
    // Toast Management
    function initializeToasts() {
        const toasts = document.querySelectorAll('[id$="-toast"]');
        toasts.forEach(toast => {
            // Slide in animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 100);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                hideToast(toast.id);
            }, 5000);
        });
    }
    
    window.hideToast = function(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.add('translate-x-full');
            toast.classList.remove('translate-x-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    };
    
    // Form Handlers
    function initializeFormHandlers() {
        const filterForm = document.getElementById('filterForm');
        const selects = filterForm.querySelectorAll('select');
        
        // Auto-submit on select change
        selects.forEach(select => {
            select.addEventListener('change', function() {
                showLoading();
                filterForm.submit();
            });
        });
        
        // Reset filters function
        window.resetFilters = function() {
            const selects = filterForm.querySelectorAll('select');
            selects.forEach(select => {
                select.selectedIndex = 0;
            });
            showLoading();
            filterForm.submit();
        };
    }
    
    // Table Enhancements
    function initializeTableEnhancements() {
        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(2px)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
        
        // Add click handlers for action buttons
        const actionButtons = document.querySelectorAll('a[title]');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const title = this.getAttribute('title');
                if (title.includes('Detail') || title.includes('Tambah')) {
                    showLoading();
                }
            });
        });
    }
    
    // Loading Overlay
    function showLoading() {
        document.getElementById('loading-overlay').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loading-overlay').classList.add('hidden');
    }
    
    // Hide loading on page load
    window.addEventListener('load', hideLoading);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus on search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('select[name="mapel"]').focus();
        }
        
        // Escape to reset filters
        if (e.key === 'Escape') {
            resetFilters();
        }
    });
    
    // Add fade-in animation to main content
    const mainContent = document.querySelector('.max-w-7xl');
    if (mainContent) {
        mainContent.style.opacity = '0';
        mainContent.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            mainContent.style.transition = 'all 0.6s ease-out';
            mainContent.style.opacity = '1';
            mainContent.style.transform = 'translateY(0)';
        }, 100);
    }
});

// Export functionality (placeholder)
window.exportData = function() {
    alert('Export functionality will be implemented here');
};

// Print functionality
window.printTable = function() {
    window.print();
};
</script>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    
    .print-section, .print-section * {
        visibility: visible;
    }
    
    .print-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    .no-print {
        display: none !important;
    }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Smooth transitions for all interactive elements */
* {
    transition: all 0.2s ease-in-out;
}

/* Focus styles for accessibility */
.focus\:ring-2:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}
</style>

<script>
function resetFilters() {
    // Reset all form fields to default values
    document.querySelector('select[name="mapel"]').value = 'IPAS';
    <?php if ($userRole === 'admin'): ?>
    document.querySelector('select[name="kelas"]').value = '';
    <?php endif; ?>
    
    // Submit the form to reload with default values
    document.getElementById('filterForm').submit();
}

// Auto-submit form when selections change
document.addEventListener('DOMContentLoaded', function() {
    const mapelSelect = document.querySelector('select[name="mapel"]');
    const kelasSelect = document.querySelector('select[name="kelas"]');
    
    if (mapelSelect) {
        mapelSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    if (kelasSelect) {
        kelasSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
});
</script>

<?= $this->endSection(); ?>
