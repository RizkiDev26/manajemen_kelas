<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Page Header - Mobile Optimized -->
<div class="mb-6 sm:mb-8 lg:mb-10 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                <i class="fas fa-print text-purple-600 mr-3 sm:mr-4 text-2xl sm:text-3xl lg:text-4xl"></i>Cetak Nilai Siswa
            </h1>
            <p class="text-base sm:text-lg text-gray-600">Cetak laporan nilai siswa dalam berbagai format dan periode</p>
        </div>
        
        <!-- Quick Actions - Mobile Optimized -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button 
                onclick="openPrintModal()"
                class="bg-purple-600 hover:bg-purple-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base touch-manipulation">
                <i class="fas fa-print"></i>
                <span>Cetak Laporan</span>
            </button>
            
            <button 
                onclick="exportToExcel()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base touch-manipulation">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </button>
            
            <button 
                onclick="exportToPDF()"
                class="bg-red-600 hover:bg-red-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base touch-manipulation">
                <i class="fas fa-file-pdf"></i>
                <span>Export PDF</span>
            </button>
        </div>
    </div>
</div>

<!-- Filter Section - Mobile Optimized -->
<div class="px-4 sm:px-6 lg:px-8 mb-6 sm:mb-8">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Filter Laporan</h3>
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Kelas Filter -->
            <div>
                <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                <select name="kelas" id="kelas" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base">
                    <option value="">Semua Kelas</option>
                    <?php if ($userRole === 'admin'): ?>
                        <?php foreach ($availableClasses as $class): ?>
                            <option value="<?= $class['kelas'] ?>" <?= (isset($_GET['kelas']) && $_GET['kelas'] == $class['kelas']) ? 'selected' : '' ?>>
                                Kelas <?= $class['kelas'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="<?= $userKelas ?>" selected>Kelas <?= $userKelas ?></option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Mata Pelajaran Filter -->
            <div>
                <label for="mapel" class="block text-sm font-semibold text-gray-700 mb-2">Mata Pelajaran</label>
                <select name="mapel" id="mapel" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base">
                    <option value="">Semua Mapel</option>
                    <option value="Matematika">Matematika</option>
                    <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                    <option value="IPA">IPA</option>
                    <option value="IPS">IPS</option>
                    <option value="PKn">PKn</option>
                    <option value="Bahasa Inggris">Bahasa Inggris</option>
                    <option value="Agama">Agama</option>
                    <option value="Olahraga">Olahraga</option>
                    <option value="Seni Budaya">Seni Budaya</option>
                </select>
            </div>

            <!-- Periode -->
            <div>
                <label for="periode" class="block text-sm font-semibold text-gray-700 mb-2">Periode</label>
                <select name="periode" id="periode" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base">
                    <option value="">Pilih Periode</option>
                    <option value="semester1">Semester 1</option>
                    <option value="semester2">Semester 2</option>
                    <option value="tahun">Tahun Ajaran</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:items-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl text-sm sm:text-base touch-manipulation">
                    <i class="fas fa-search"></i>
                    <span>Preview</span>
                </button>
                
                <a href="/admin/nilai/cetak" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl text-sm sm:text-base touch-manipulation">
                    <i class="fas fa-times"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Report Types Selection -->
<div class="px-4 sm:px-6 lg:px-8 mb-6 sm:mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Rapor Siswa -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Rapor Siswa</h3>
                    <p class="text-sm text-gray-600">Laporan nilai per siswa</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Cetak rapor lengkap dengan semua nilai mata pelajaran untuk siswa tertentu</p>
            <button onclick="generateRapor()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200">
                Buat Rapor
            </button>
        </div>

        <!-- Nilai Per Mata Pelajaran -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-book text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Nilai Per Mapel</h3>
                    <p class="text-sm text-gray-600">Laporan per mata pelajaran</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Cetak daftar nilai semua siswa untuk mata pelajaran tertentu</p>
            <button onclick="generateNilaiMapel()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200">
                Buat Laporan
            </button>
        </div>

        <!-- Rekapitulasi Kelas -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Rekap Kelas</h3>
                    <p class="text-sm text-gray-600">Statistik kelas</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Cetak rekapitulasi nilai dengan statistik lengkap per kelas</p>
            <button onclick="generateRekapKelas()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200">
                Buat Rekap
            </button>
        </div>
    </div>
</div>

<!-- Preview Section -->
<div class="px-4 sm:px-6 lg:px-8 mb-8">
    <?php if (isset($_GET['kelas'])): ?>
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 sm:px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-white">Preview Laporan</h3>
                    <p class="text-purple-100 text-sm mt-1">
                        Kelas <?= esc($_GET['kelas']) ?> 
                        <?= isset($_GET['mapel']) && $_GET['mapel'] ? ' - ' . esc($_GET['mapel']) : '' ?>
                    </p>
                </div>
                <div class="flex gap-2 mt-3 sm:mt-0">
                    <button onclick="printPreview()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-print"></i>
                        <span>Print</span>
                    </button>
                    <button onclick="downloadPDF()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-download"></i>
                        <span>Download</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Print Preview Area -->
        <div id="printArea" class="p-6">
            <!-- Report Header -->
            <div class="text-center mb-6 pb-4 border-b-2 border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">LAPORAN NILAI SISWA</h1>
                <h2 class="text-lg font-semibold text-gray-700">SDN GU 09</h2>
                <p class="text-gray-600">
                    Kelas <?= esc($_GET['kelas']) ?> 
                    <?= isset($_GET['mapel']) && $_GET['mapel'] ? ' - ' . esc($_GET['mapel']) : '' ?>
                </p>
                <p class="text-sm text-gray-500">Tanggal: <?= date('d F Y') ?></p>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left font-bold">No</th>
                            <th class="border border-gray-300 px-4 py-2 text-left font-bold">NISN</th>
                            <th class="border border-gray-300 px-4 py-2 text-left font-bold">Nama Siswa</th>
                            <th class="border border-gray-300 px-4 py-2 text-center font-bold">UH 1</th>
                            <th class="border border-gray-300 px-4 py-2 text-center font-bold">UH 2</th>
                            <th class="border border-gray-300 px-4 py-2 text-center font-bold">UTS</th>
                            <th class="border border-gray-300 px-4 py-2 text-center font-bold">UAS</th>
                            <th class="border border-gray-300 px-4 py-2 text-center font-bold">Rata-rata</th>
                            <th class="border border-gray-300 px-4 py-2 text-center font-bold">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data -->
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">1</td>
                            <td class="border border-gray-300 px-4 py-2">1234567890</td>
                            <td class="border border-gray-300 px-4 py-2">Ahmad Rizki</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">85</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">88</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">82</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">87</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">85.5</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">A</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">2</td>
                            <td class="border border-gray-300 px-4 py-2">1234567891</td>
                            <td class="border border-gray-300 px-4 py-2">Siti Aminah</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">78</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">82</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">75</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">80</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">78.8</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">B</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">3</td>
                            <td class="border border-gray-300 px-4 py-2">1234567892</td>
                            <td class="border border-gray-300 px-4 py-2">Budi Santoso</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">92</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">89</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">91</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">93</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">91.3</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">A</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100">
                            <td colspan="7" class="border border-gray-300 px-4 py-2 text-right font-bold">Rata-rata Kelas:</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">85.2</td>
                            <td class="border border-gray-300 px-4 py-2 text-center font-bold">A</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Statistics -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-2">Distribusi Grade</h4>
                    <div class="space-y-1">
                        <div class="flex justify-between"><span>Grade A:</span> <span>2 siswa (67%)</span></div>
                        <div class="flex justify-between"><span>Grade B:</span> <span>1 siswa (33%)</span></div>
                        <div class="flex justify-between"><span>Grade C:</span> <span>0 siswa (0%)</span></div>
                        <div class="flex justify-between"><span>Grade D:</span> <span>0 siswa (0%)</span></div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-2">Statistik Nilai</h4>
                    <div class="space-y-1">
                        <div class="flex justify-between"><span>Nilai Tertinggi:</span> <span>91.3</span></div>
                        <div class="flex justify-between"><span>Nilai Terendah:</span> <span>78.8</span></div>
                        <div class="flex justify-between"><span>Rata-rata:</span> <span>85.2</span></div>
                        <div class="flex justify-between"><span>Jumlah Siswa:</span> <span>3</span></div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-2">Keterangan</h4>
                    <div class="space-y-1 text-sm">
                        <div>UH = Ulangan Harian</div>
                        <div>UTS = Ujian Tengah Semester</div>
                        <div>UAS = Ujian Akhir Semester</div>
                        <div class="mt-2 pt-2 border-t">
                            <div>A: 80-100 | B: 70-79</div>
                            <div>C: 60-69 | D: 0-59</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-4 border-t border-gray-200">
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-sm text-gray-600">Dicetak pada: <?= date('d F Y H:i:s') ?></p>
                        <p class="text-sm text-gray-600">Oleh: <?= esc($currentUser['nama']) ?></p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-8">Mengetahui,</p>
                        <p class="text-sm text-gray-900 font-semibold">Kepala Sekolah</p>
                        <div class="mt-8 border-b border-gray-400 w-32"></div>
                        <p class="text-sm text-gray-600 mt-1">NIP. _________________</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Initial State -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
        <i class="fas fa-print text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Pilih Kriteria Laporan</h3>
        <p class="text-gray-600 mb-6">Gunakan filter di atas untuk memilih data yang akan dicetak</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="document.getElementById('kelas').focus()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200">
                Pilih Kriteria
            </button>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Print Modal -->
<div id="printModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-screen overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Opsi Cetak</h3>
                <button onclick="closePrintModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Format Output</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="format" value="pdf" checked class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2">PDF</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="format" value="excel" class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2">Excel</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="format" value="print" class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2">Print Langsung</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Orientasi</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="orientation" value="portrait" checked class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2">Portrait</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="orientation" value="landscape" class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2">Landscape</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button onclick="processPrint()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 flex-1">
                    <i class="fas fa-print"></i>
                    <span>Proses</span>
                </button>
                <button onclick="closePrintModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
function openPrintModal() {
    document.getElementById('printModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePrintModal() {
    document.getElementById('printModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function processPrint() {
    const format = document.querySelector('input[name="format"]:checked').value;
    const orientation = document.querySelector('input[name="orientation"]:checked').value;
    
    if (format === 'print') {
        printPreview();
    } else if (format === 'pdf') {
        downloadPDF();
    } else if (format === 'excel') {
        exportToExcel();
    }
    
    closePrintModal();
}

function generateRapor() {
    alert('Fitur cetak rapor akan segera tersedia');
}

function generateNilaiMapel() {
    alert('Fitur cetak nilai per mapel akan segera tersedia');
}

function generateRekapKelas() {
    alert('Fitur cetak rekap kelas akan segera tersedia');
}

function exportToExcel() {
    alert('Export ke Excel akan segera tersedia');
}

function exportToPDF() {
    alert('Export ke PDF akan segera tersedia');
}

function printPreview() {
    const printArea = document.getElementById('printArea');
    if (printArea) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Laporan Nilai - SDN GU 09</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                    th { background-color: #f0f0f0; font-weight: bold; }
                    .text-center { text-align: center; }
                    .text-right { text-right; }
                    .font-bold { font-weight: bold; }
                    .mb-2 { margin-bottom: 8px; }
                    .mb-4 { margin-bottom: 16px; }
                    .mb-6 { margin-bottom: 24px; }
                    .mt-6 { margin-top: 24px; }
                    .mt-8 { margin-top: 32px; }
                    .pt-4 { padding-top: 16px; }
                    .pb-4 { padding-bottom: 16px; }
                    .border-t { border-top: 1px solid #ccc; }
                    .border-b-2 { border-bottom: 2px solid #ccc; }
                    .grid { display: grid; }
                    .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
                    .gap-4 { gap: 16px; }
                    .bg-gray-50 { background-color: #f9f9f9; }
                    .p-4 { padding: 16px; }
                    .rounded-lg { border-radius: 8px; }
                    .space-y-1 > * + * { margin-top: 4px; }
                    .flex { display: flex; }
                    .justify-between { justify-content: space-between; }
                    .items-end { align-items: flex-end; }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                ${printArea.innerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

function downloadPDF() {
    alert('Download PDF akan segera tersedia');
}

// Close modal when clicking outside
document.getElementById('printModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePrintModal();
    }
});
</script>

<style>
@media print {
    .no-print { display: none !important; }
    body { margin: 0; }
    #printArea { box-shadow: none; }
}
</style>

<?= $this->endSection() ?>
