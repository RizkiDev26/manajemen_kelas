<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Edit Kasus
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Kasus</h1>
        <nav class="mt-2 text-sm text-gray-500">
            <ol class="flex items-center space-x-2">
                <li><a href="<?= base_url('admin') ?>" class="text-indigo-600 hover:underline">Dashboard</a></li>
                <li>/</li>
                <li><a href="<?= base_url('buku-kasus') ?>" class="text-indigo-600 hover:underline">Buku Kasus</a></li>
                <li>/</li>
                <li class="text-gray-700">Edit</li>
            </ol>
        </nav>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200 text-red-800">
            <div class="flex"><i class="fa-solid fa-triangle-exclamation mr-2"></i><?= session()->getFlashdata('error') ?></div>
        </div>
    <?php endif; ?>

    <!-- Validation Errors -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200 text-red-800">
            <div class="font-medium">Terdapat kesalahan:</div>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-gray-800 font-medium"><i class="fa-solid fa-pen-to-square text-indigo-600 mr-2"></i>Form Edit Kasus</h2>
        </div>
        <div class="px-6 py-5">
            <form action="<?= base_url('buku-kasus/update/' . $kasus['id']) ?>" method="POST" id="formEditKasus" class="space-y-5">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700">Tanggal Kejadian <span class="text-rose-600">*</span></label>
                        <input type="date" id="tanggal_kejadian" name="tanggal_kejadian" value="<?= old('tanggal_kejadian', $kasus['tanggal_kejadian']) ?>" required
                               class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm" />
                    </div>
                    <div>
                        <label for="jenis_kasus" class="block text-sm font-medium text-gray-700">Jenis Kasus <span class="text-rose-600">*</span></label>
                        <select id="jenis_kasus" name="jenis_kasus" required
                                class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <?php $jenisOptions = ['Kedisiplinan','Akademik','Perilaku','Kesehatan','Sosial','Lainnya']; ?>
                            <?php foreach ($jenisOptions as $opt): ?>
                                <option value="<?= $opt ?>" <?= old('jenis_kasus', $kasus['jenis_kasus']) == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="kelas_nama" class="block text-sm font-medium text-gray-700">Kelas <span class="text-rose-600">*</span></label>
                        <select id="kelas_nama" name="kelas_nama" required
                                class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['nama'] ?>" <?= old('kelas_nama', $kelasDipilih) == $kelas['nama'] ? 'selected' : '' ?>><?= $kelas['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700">Nama Siswa <span class="text-rose-600">*</span></label>
                        <select id="siswa_id" name="siswa_id" required
                                class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <?php foreach ($siswaList as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= old('siswa_id', $kasus['siswa_id']) == $s['id'] ? 'selected' : '' ?>><?= esc($s['nama']) ?> (<?= esc($s['nis']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="deskripsi_kasus" class="block text-sm font-medium text-gray-700">Deskripsi Kasus <span class="text-rose-600">*</span></label>
                    <textarea id="deskripsi_kasus" name="deskripsi_kasus" rows="4" required
                              class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm"><?= old('deskripsi_kasus', $kasus['deskripsi_kasus']) ?></textarea>
                </div>

                <div>
                    <label for="tindakan_yang_diambil" class="block text-sm font-medium text-gray-700">Tindakan yang Diambil</label>
                    <textarea id="tindakan_yang_diambil" name="tindakan_yang_diambil" rows="3"
                              class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm"><?= old('tindakan_yang_diambil', $kasus['tindakan_yang_diambil']) ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-rose-600">*</span></label>
                        <select id="status" name="status" required
                                class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <?php $statusOptions = ['belum_ditangani' => 'Belum Ditangani', 'dalam_proses' => 'Dalam Proses', 'selesai' => 'Selesai']; ?>
                            <?php foreach ($statusOptions as $val => $label): ?>
                                <option value="<?= $val ?>" <?= old('status', $kasus['status']) == $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="tingkat_keparahan" class="block text-sm font-medium text-gray-700">Tingkat Keparahan <span class="text-rose-600">*</span></label>
                        <select id="tingkat_keparahan" name="tingkat_keparahan" required
                                class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <?php foreach (['Ringan','Sedang','Berat'] as $tk): ?>
                                <option value="<?= $tk ?>" <?= old('tingkat_keparahan', $kasus['tingkat_keparahan']) == $tk ? 'selected' : '' ?>><?= $tk ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="catatan_guru" class="block text-sm font-medium text-gray-700">Catatan Guru</label>
                    <textarea id="catatan_guru" name="catatan_guru" rows="3"
                              class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm"><?= old('catatan_guru', $kasus['catatan_guru']) ?></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <a href="<?= base_url('buku-kasus') ?>" class="a11y-focus inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <div class="space-x-2">
                        <a href="<?= base_url('buku-kasus/detail/' . $kasus['id']) ?>" class="a11y-focus inline-flex items-center px-3 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fa-solid fa-eye mr-2"></i> Lihat
                        </a>
                        <button type="submit" class="a11y-focus inline-flex items-center px-4 py-2 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.getElementById('kelas_nama');
    const siswaSelect = document.getElementById('siswa_id');
    const currentSiswaId = '<?= $kasus['siswa_id'] ?>';

    kelasSelect.addEventListener('change', function() {
        const kelasNama = this.value;
        
        if (kelasNama !== '<?= $kasus['kelas'] ?>') {
            siswaSelect.innerHTML = '<option value="">Loading...</option>';
            siswaSelect.disabled = true;
        }

        if (kelasNama) {
            fetch('<?= base_url('buku-kasus/get-siswa-by-kelas') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'kelas_nama=' + encodeURIComponent(kelasNama) + '&<?= csrf_token() ?>=' + encodeURIComponent('<?= csrf_hash() ?>')
            })
            .then(response => response.json())
            .then(data => {
                siswaSelect.innerHTML = '<option value="">Pilih Siswa</option>';
                
                if (data && data.length > 0) {
                    data.forEach(siswa => {
                        const option = document.createElement('option');
                        option.value = siswa.id;
                        option.textContent = siswa.nama;
                        if (siswa.id == currentSiswaId) option.selected = true;
                        siswaSelect.appendChild(option);
                    });
                    siswaSelect.disabled = false;
                } else {
                    siswaSelect.innerHTML = '<option value="">Tidak ada siswa di kelas ini</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                siswaSelect.innerHTML = '<option value="">Error memuat data siswa</option>';
            });
        } else {
            siswaSelect.innerHTML = '<option value="">Pilih kelas terlebih dahulu</option>';
            siswaSelect.disabled = true;
        }
    });

    document.getElementById('formEditKasus').addEventListener('submit', function(e) {
        const requiredFields = ['tanggal_kejadian', 'jenis_kasus', 'kelas_nama', 'siswa_id', 'deskripsi_kasus', 'status', 'tingkat_keparahan'];
        let isValid = true;

        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('ring-1','ring-rose-500');
                isValid = false;
            } else {
                field.classList.remove('ring-1','ring-rose-500');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi (bertanda *)');
        }
    });
});
</script>
<?= $this->endSection() ?>
