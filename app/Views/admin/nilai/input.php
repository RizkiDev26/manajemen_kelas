<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Page Header - Mobile Optimized -->
<div class="mb-6 sm:mb-8 lg:mb-10 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                <i class="fas fa-edit text-green-600 mr-3 sm:mr-4 text-2xl sm:text-3xl lg:text-4xl"></i>Data Nilai Murid • Penilaian Harian
            </h1>
            <p class="text-base sm:text-lg text-gray-600">Pilih kelas dan mapel, lalu input nilai melalui modal</p>
        </div>
        
        <!-- Quick Actions - Mobile Optimized -->
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button 
                onclick="importFromExcel()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base touch-manipulation">
                <i class="fas fa-file-excel"></i>
                <span>Import Excel</span>
            </button>
        </div>
    </div>
</div>

<!-- Filter Section - Mobile Optimized -->
<div class="px-4 sm:px-6 lg:px-8 mb-6 sm:mb-8">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <input type="hidden" name="open" id="openModalFlag" value="0">
            <!-- Kelas Filter -->
            <div>
                <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                <select name="kelas" id="kelas" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base">
                    <option value="">Pilih Kelas</option>
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
                <select name="mapel" id="mapel" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base">
                    <option value="">Pilih Mapel</option>
                    <?php $selMapel = $_GET['mapel'] ?? ''; ?>
                    <option value="Matematika" <?= ($selMapel==='Matematika'?'selected':'') ?>>Matematika</option>
                    <option value="Bahasa Indonesia" <?= ($selMapel==='Bahasa Indonesia'?'selected':'') ?>>Bahasa Indonesia</option>
                    <option value="IPA" <?= ($selMapel==='IPA'?'selected':'') ?>>IPA</option>
                    <option value="IPS" <?= ($selMapel==='IPS'?'selected':'') ?>>IPS</option>
                    <option value="PKn" <?= ($selMapel==='PKn'?'selected':'') ?>>PKn</option>
                    <option value="Bahasa Inggris" <?= ($selMapel==='Bahasa Inggris'?'selected':'') ?>>Bahasa Inggris</option>
                    <option value="Agama" <?= ($selMapel==='Agama'?'selected':'') ?>>Agama</option>
                    <option value="Olahraga" <?= ($selMapel==='Olahraga'?'selected':'') ?>>Olahraga</option>
                    <option value="Seni Budaya" <?= ($selMapel==='Seni Budaya'?'selected':'') ?>>Seni Budaya</option>
                </select>
            </div>

            

            <!-- Action Buttons -->
            <div class="sm:col-span-2 lg:col-span-2 flex flex-col sm:flex-row gap-2 sm:gap-3 sm:items-end">
                <button type="button" id="openInputHarian" onclick="handleOpenInputHarianClick()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl text-sm sm:text-base touch-manipulation">
                    <i class="fas fa-pen"></i>
                    <span>Input Penilaian Harian</span>
                </button>
                
                <a href="/admin/nilai/input" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl text-sm sm:text-base touch-manipulation">
                    <i class="fas fa-times"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Rekap Nilai Harian (PH) di bawah filter) -->
<div class="px-4 sm:px-6 lg:px-8 mb-8">
    <?php if (!isset($_GET['kelas']) || !isset($_GET['mapel'])): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
            <i class="fas fa-edit text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Pilih Kelas dan Mata Pelajaran</h3>
            <p class="text-gray-600 mb-6">Gunakan filter di atas untuk memilih kelas dan mata pelajaran yang ingin dinilai</p>
        </div>
    <?php elseif (!empty($harianMatrix) && !empty($harianMatrix['students'])): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-x-auto">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Nilai Harian Tersimpan</h3>
                    <p class="text-sm text-gray-500">Kelas <?= esc($_GET['kelas']) ?> • Mapel <?= esc($_GET['mapel']) ?></p>
                </div>
                <div class="flex gap-2">
                    <button onclick="openStudentModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-plus mr-2"></i>Input Nilai Harian
                    </button>
                    <button onclick="openEditModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-edit mr-2"></i>Edit Penilaian Harian
                    </button>
                </div>
            </div>
            <?php 
                // Render exactly as many PH columns as existing assessments
                $headers = $harianMatrix['headers'] ?? []; 
            ?>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">NISN</th>
                        <?php foreach ($headers as $h): ?>
                            <th class="px-2 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider"><?= esc($h['label']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php $rowNo = 1; foreach ($harianMatrix['students'] as $stu): ?>
                        <tr>
                            <td class="px-4 sm:px-6 py-3 text-sm text-gray-500 w-12"><?= $rowNo++ ?></td>
                            <td class="px-4 sm:px-6 py-3 text-sm text-gray-900 font-medium"><?= esc($stu['nama']) ?></td>
                            <td class="px-4 sm:px-6 py-3 text-sm text-gray-500"><?= esc($stu['nisn']) ?></td>
                            <?php $vals = $harianMatrix['values'][$stu['id']] ?? []; ?>
                            <?php foreach ($headers as $idx => $h): ?>
                                <?php $col = $idx + 1; $v = $vals[$col] ?? ''; ?>
                                <td class="px-2 py-3 text-center text-sm <?= $v!=='' ? 'text-gray-900' : 'text-gray-300' ?>"><?= $v!=='' ? esc($v) : '-' ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
            <i class="fas fa-info-circle text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-600">Belum ada nilai harian tersimpan untuk pilihan ini.</p>
            <div class="mt-4">
                <button onclick="openStudentModal()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-semibold">
                    <i class="fas fa-plus mr-2"></i>Input Nilai Harian
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $hasSelection = isset($_GET['kelas']) && isset($_GET['mapel']); ?>
<div id="studentInputModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Input Nilai Harian</h3>
                <?php if ($hasSelection): ?>
                    <p class="text-sm text-gray-500 mt-1">Kelas <?= esc($_GET['kelas']) ?> • Mapel <?= esc($_GET['mapel']) ?></p>
                <?php endif; ?>
            </div>
            <button onclick="closeStudentModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Form atas -->
            <form id="bulkInputForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label for="deskripsi_penilaian" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Penilaian</label>
                        <textarea id="deskripsi_penilaian" name="deskripsi_penilaian" rows="2" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Masukan deskripsi penilaian mis:  Materi atau tujuan pembelajaran yang diujikan"></textarea>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Nilai</label>
                        <input type="text" value="Harian" disabled class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-700">
                    </div>
                </div>
            </form>

            <!-- Tabel Siswa -->
            <div class="overflow-x-auto border border-gray-100 rounded-xl">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">NISN</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Nilai</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($hasSelection && !empty($students)): ?>
                            <?php foreach ($students as $index => $student): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($student['nisn']) ?></td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium"><?= esc($student['nama']) ?></td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                    <input type="number" min="0" max="100" step="1"
                                           name="nilai[<?= $student['id'] ?>]"
                                           data-siswa-id="<?= $student['id'] ?>"
                                           onchange="updateGrade(this, 'grade-<?= $student['id'] ?>')"
                                           class="w-20 px-2 py-1.5 text-center border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="0-100">
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                    <span id="grade-<?= $student['id'] ?>" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 sm:px-6 py-8 text-center text-gray-500">Tidak ada siswa ditemukan atau belum memilih kelas & mapel</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <button onclick="saveAllGrades()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 flex-1">
                    <i class="fas fa-save"></i>
                    <span>Simpan Semua Nilai</span>
                </button>
                <button onclick="clearAllGrades()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 flex-1">
                    <i class="fas fa-eraser"></i>
                    <span>Hapus Semua</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editHarianModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Edit Penilaian Harian</h3>
                <?php if ($hasSelection): ?>
                    <p class="text-sm text-gray-500 mt-1">Kelas <?= esc($_GET['kelas']) ?> • Mapel <?= esc($_GET['mapel']) ?></p>
                <?php endif; ?>
            </div>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih PH</label>
                    <?php $headersJson = json_encode($harianMatrix['headers'] ?? []); ?>
                    <select id="editPhSelect" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Penilaian Harian</option>
                        <?php if (!empty($harianMatrix['headers'])): foreach ($harianMatrix['headers'] as $i => $h): ?>
                            <option value="<?= $i+1 ?>"><?= esc($h['label']) ?><?= $h['date'] ? ' - ' . esc($h['date']) : '' ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="editTanggal" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <input type="text" id="editDeskripsi" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl" placeholder="Deskripsi / TP">
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-100 rounded-xl">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">NISN</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 sm:px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($hasSelection && !empty($students)): ?>
                            <?php foreach ($students as $index => $student): ?>
                            <tr>
                                <td class="px-4 sm:px-6 py-3 text-sm text-gray-900"><?= $index + 1 ?></td>
                                <td class="px-4 sm:px-6 py-3 text-sm text-gray-600"><?= esc($student['nisn']) ?></td>
                                <td class="px-4 sm:px-6 py-3 text-sm text-gray-900 font-medium"><?= esc($student['nama']) ?></td>
                                <td class="px-4 sm:px-6 py-3 text-center">
                                    <input type="number" min="0" max="100" step="1" data-edit-siswa-id="<?= $student['id'] ?>" class="w-20 px-2 py-1.5 text-center border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="0-100">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="px-4 sm:px-6 py-8 text-center text-gray-500">Tidak ada siswa</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex gap-3">
                <button onclick="saveEditedGrades()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold">Simpan Perubahan</button>
                <button onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
function handleOpenInputHarianClick() {
    const kelas = document.getElementById('kelas')?.value || '';
    const mapel = document.getElementById('mapel')?.value || '';
    if (!kelas || !mapel) {
        alert('Pilih kelas dan mata pelajaran terlebih dahulu.');
        return;
    }
    // If selection present, open the modal directly
    openStudentModal();
}

function openStudentModal() {
    const modal = document.getElementById('studentInputModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeStudentModal() {
    const modal = document.getElementById('studentInputModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function importFromExcel() {
    alert('Fitur import Excel akan segera tersedia');
}

// Edit modal handlers
function openEditModal() {
    const modal = document.getElementById('editHarianModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}
function closeEditModal() {
    const modal = document.getElementById('editHarianModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Prefill edit values when PH selection changes
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'editPhSelect') {
        const colIndex = parseInt(e.target.value, 10); // 1-based
        if (!colIndex) return;
        try {
            const matrix = <?= json_encode($harianMatrix ?? []) ?>;
            const headers = matrix.headers || [];
            const students = matrix.students || [];
            const values = matrix.values || {};
            const header = headers[colIndex-1] || null;
            if (header) {
                document.getElementById('editTanggal').value = header.date || '';
                document.getElementById('editDeskripsi').value = header.tp || '';
            }
            // Set student values
            const inputs = document.querySelectorAll('input[data-edit-siswa-id]');
            inputs.forEach(inp => {
                const sid = parseInt(inp.getAttribute('data-edit-siswa-id'), 10);
                const rowVals = values[sid] || {};
                inp.value = (rowVals[colIndex] !== undefined && rowVals[colIndex] !== null) ? rowVals[colIndex] : '';
            });
        } catch (err) {
            console.error(err);
        }
    }
});

async function saveEditedGrades() {
    const phSel = document.getElementById('editPhSelect');
    const tanggal = document.getElementById('editTanggal').value;
    const deskripsi = document.getElementById('editDeskripsi').value.trim();
    const colIndex = parseInt(phSel.value || '0', 10);
    if (!colIndex) { alert('Pilih Penilaian Harian dulu'); return; }
    if (!tanggal) { alert('Tanggal wajib diisi'); return; }
    const kelas = '<?= isset($_GET['kelas']) ? esc($_GET['kelas']) : '' ?>';
    const mapel = '<?= isset($_GET['mapel']) ? esc($_GET['mapel']) : '' ?>';
    const grades = [];
    document.querySelectorAll('input[data-edit-siswa-id]').forEach(inp => {
        if (inp.value !== '' && inp.value !== null) {
            grades.push({ siswa_id: parseInt(inp.getAttribute('data-edit-siswa-id'), 10), nilai: parseFloat(inp.value) });
        }
    });
    if (!grades.length) { alert('Isi minimal satu nilai'); return; }
    if (!confirm('Simpan perubahan untuk '+grades.length+' siswa?')) return;
    try {
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';
        const resp = await fetch('<?= base_url('admin/nilai/update-bulk-harian') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfHash
            },
            body: JSON.stringify({
                [csrfName]: csrfHash,
                kelas,
                mapel,
                tanggal,
                deskripsi,
                colIndex,
                grades
            })
        });
        const data = await resp.json();
        if (resp.ok && data.status === 'ok') {
            alert('Perubahan tersimpan');
            const params = new URLSearchParams(window.location.search);
            window.location.href = `${window.location.pathname}?kelas=${encodeURIComponent(kelas)}&mapel=${encodeURIComponent(mapel)}`;
        } else {
            alert(data.message || 'Gagal menyimpan perubahan');
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat menyimpan');
    }
}

// Update grade display function
function updateGrade(input, gradeId) {
    const value = parseInt(input.value);
    const gradeSpan = document.getElementById(gradeId);
    
    if (value >= 80) {
        gradeSpan.textContent = 'A';
        gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
    } else if (value >= 70) {
        gradeSpan.textContent = 'B';
        gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
    } else if (value >= 60) {
        gradeSpan.textContent = 'C';
        gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
    } else if (value > 0) {
        gradeSpan.textContent = 'D';
        gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
    } else {
        gradeSpan.textContent = '-';
        gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
    }
}

async function saveAllGrades() {
    // Validate fields
    const tanggalEl = document.getElementById('tanggal');
    const tanggal = tanggalEl ? tanggalEl.value : '';
    const deskripsi = (document.getElementById('deskripsi_penilaian') || { value: '' }).value.trim();
    if (!tanggal) {
        alert('Silakan pilih tanggal terlebih dahulu');
        return;
    }
    
    // Collect all grades
    const grades = [];
    const inputs = document.querySelectorAll('input[type="number"][data-siswa-id]');
    inputs.forEach((input) => {
        if (input.value !== '' && input.value !== null) {
            grades.push({
                siswa_id: parseInt(input.getAttribute('data-siswa-id'), 10),
                nilai: parseFloat(input.value)
            });
        }
    });
    
    if (grades.length === 0) {
        alert('Silakan input minimal satu nilai');
        return;
    }
    
    const kelas = '<?= isset($_GET['kelas']) ? esc($_GET['kelas']) : '' ?>';
    const mapel = '<?= isset($_GET['mapel']) ? esc($_GET['mapel']) : '' ?>';
    if (!confirm(`Simpan ${grades.length} nilai harian untuk Kelas ${kelas} - ${mapel}?`)) return;

    try {
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';
        const resp = await fetch('<?= base_url('admin/nilai/store-bulk-harian') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfHash
            },
            body: JSON.stringify({
                [csrfName]: csrfHash,
                kelas,
                mapel,
                tanggal,
                deskripsi,
                grades
            })
        });
        const data = await resp.json();
        if (resp.ok && data.status === 'ok') {
            alert(data.message || 'Nilai berhasil disimpan!');
            // Reload page to refresh recap and show new PH column if needed
            const params = new URLSearchParams(window.location.search);
            window.location.href = `${window.location.pathname}?kelas=${encodeURIComponent(kelas)}&mapel=${encodeURIComponent(mapel)}`;
        } else {
            alert(data.message || 'Gagal menyimpan nilai');
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat menyimpan');
    }
}

function clearAllGrades() {
    if (confirm('Hapus semua input nilai?')) {
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.value = '';
        });
        document.querySelectorAll('[id^="grade-"]').forEach(span => {
            span.textContent = '-';
            span.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
        });
    }
}

// Auto-update grade display when value changes
document.addEventListener('input', function(e) {
    if (e.target.type === 'number') {
        const value = parseInt(e.target.value);
        const row = e.target.closest('tr');
        const gradeSpan = row.querySelector('[id^="grade-"]');
        
        if (value >= 80) {
            gradeSpan.textContent = 'A';
            gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
        } else if (value >= 70) {
            gradeSpan.textContent = 'B';
            gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
        } else if (value >= 60) {
            gradeSpan.textContent = 'C';
            gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
        } else if (value > 0) {
            gradeSpan.textContent = 'D';
            gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
        } else {
            gradeSpan.textContent = '-';
            gradeSpan.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
        }
    }
});

// Auto-open modal when kelas & mapel ada di query
document.addEventListener('DOMContentLoaded', function () {
    const hasSelection = <?= isset($_GET['kelas']) && isset($_GET['mapel']) ? 'true' : 'false' ?>;
    // If URL contains open=1 and selection exists, open input modal automatically
    const params = new URLSearchParams(window.location.search);
    if (hasSelection && params.get('open') === '1') {
        openStudentModal();
    }

    // Auto-submit when both filters selected
    const form = document.querySelector('form[method="GET"]');
    const kelasSel = document.getElementById('kelas');
    const mapelSel = document.getElementById('mapel');
    function trySubmit() {
        if (form && kelasSel && mapelSel && kelasSel.value && mapelSel.value) {
            form.requestSubmit ? form.requestSubmit() : form.submit();
        }
    }
    if (kelasSel) kelasSel.addEventListener('change', trySubmit);
    if (mapelSel) mapelSel.addEventListener('change', trySubmit);
});

// Close modal when clicking backdrop
document.getElementById('studentInputModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeStudentModal();
});
</script>

<?= $this->endSection() ?>
