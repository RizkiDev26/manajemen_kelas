<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Tambah Kasus Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Kasus Baru</h1>
        <nav class="mt-2 text-sm text-gray-500">
            <ol class="flex items-center space-x-2">
                <li><a href="<?= base_url('admin') ?>" class="text-indigo-600 hover:underline">Dashboard</a></li>
                <li>/</li>
                <li><a href="<?= base_url('buku-kasus') ?>" class="text-indigo-600 hover:underline">Buku Kasus</a></li>
                <li>/</li>
                <li class="text-gray-700">Tambah</li>
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
            <h2 class="text-gray-800 font-medium"><i class="fa-solid fa-plus-circle text-indigo-600 mr-2"></i>Form Tambah Kasus</h2>
        </div>
        <div class="px-6 py-5">
            <form action="<?= base_url('buku-kasus/simpan') ?>" method="POST" id="formTambahKasus" class="space-y-6">
                <?= csrf_field() ?>

                <!-- Quick guide -->
                <div class="rounded-md border border-indigo-100 bg-indigo-50 text-indigo-800 p-3 text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <div>
                        <p class="font-medium">Panduan singkat</p>
                        <ul class="list-disc ml-5 mt-1 space-y-1">
                            <li>Pilih Kelas untuk menampilkan daftar Nama Siswa.</li>
                            <li>Kolom bertanda <span class="text-rose-600">*</span> wajib diisi.</li>
                        </ul>
                    </div>
                </div>

                <!-- Informasi Kejadian -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-center gap-2 text-gray-800 font-medium mb-3">
                        <i class="fa-solid fa-calendar-day text-indigo-600"></i>
                        <span>Informasi Kejadian</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700">Tanggal Kejadian <span class="text-rose-600">*</span></label>
                            <input type="date" id="tanggal_kejadian" name="tanggal_kejadian" value="<?= old('tanggal_kejadian', date('Y-m-d')) ?>" required
                                   class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm" />
                            <p class="text-xs text-gray-500 mt-1">Isi tanggal terjadinya kasus.</p>
                        </div>
                        <div>
                            <label for="jenis_kasus" class="block text-sm font-medium text-gray-700">Jenis Kasus <span class="text-rose-600">*</span></label>
                            <select id="jenis_kasus" name="jenis_kasus" required
                                    class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                                <option value="">Pilih Jenis Kasus</option>
                                <option value="Kedisiplinan" <?= old('jenis_kasus') == 'Kedisiplinan' ? 'selected' : '' ?>>Kedisiplinan</option>
                                <option value="Akademik" <?= old('jenis_kasus') == 'Akademik' ? 'selected' : '' ?>>Akademik</option>
                                <option value="Perilaku" <?= old('jenis_kasus') == 'Perilaku' ? 'selected' : '' ?>>Perilaku</option>
                                <option value="Kesehatan" <?= old('jenis_kasus') == 'Kesehatan' ? 'selected' : '' ?>>Kesehatan</option>
                                <option value="Sosial" <?= old('jenis_kasus') == 'Sosial' ? 'selected' : '' ?>>Sosial</option>
                                <option value="Lainnya" <?= old('jenis_kasus') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Kategori utama kasus yang dicatat.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div>
                            <label for="tingkat_keparahan" class="block text-sm font-medium text-gray-700">Tingkat Keparahan <span class="text-rose-600">*</span></label>
                            <select id="tingkat_keparahan" name="tingkat_keparahan" required
                                    class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                                <option value="">Pilih Tingkat</option>
                                <option value="Ringan" <?= old('tingkat_keparahan') == 'Ringan' ? 'selected' : '' ?>>Ringan</option>
                                <option value="Sedang" <?= old('tingkat_keparahan') == 'Sedang' ? 'selected' : '' ?>>Sedang</option>
                                <option value="Berat" <?= old('tingkat_keparahan') == 'Berat' ? 'selected' : '' ?>>Berat</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Perkiraan dampak kasus terhadap siswa/kelas.</p>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-rose-600">*</span></label>
                            <select id="status" name="status" required
                                    class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                                <option value="belum_ditangani" <?= old('status', 'belum_ditangani') == 'belum_ditangani' ? 'selected' : '' ?>>Belum Ditangani</option>
                                <option value="dalam_proses" <?= old('status') == 'dalam_proses' ? 'selected' : '' ?>>Dalam Proses</option>
                                <option value="selesai" <?= old('status') == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Progres penanganan kasus saat ini.</p>
                        </div>
                    </div>
                </div>

                <!-- Identitas Siswa -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-center gap-2 text-gray-800 font-medium mb-3">
                        <i class="fa-solid fa-user-graduate text-indigo-600"></i>
                        <span>Identitas Siswa</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="kelas_nama" class="block text-sm font-medium text-gray-700">Kelas <span class="text-rose-600">*</span></label>
                            <select id="kelas_nama" name="kelas_nama" required
                                    class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                                <option value="">Pilih Kelas</option>
                                <?php foreach ($kelasList as $kelas): ?>
                                    <option value="<?= $kelas['nama'] ?>" <?= old('kelas_nama') == $kelas['nama'] ? 'selected' : '' ?>>
                                        <?= $kelas['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih kelas terlebih dahulu untuk memunculkan daftar siswa.</p>
                        </div>
                        <div>
                            <label for="siswa_id" class="block text-sm font-medium text-gray-700">Nama Siswa <span class="text-rose-600">*</span></label>
                            <select id="siswa_id" name="siswa_id" required disabled
                                    class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm">
                                <option value="">Pilih kelas terlebih dahulu</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Daftar siswa akan otomatis terisi sesuai kelas.</p>
                        </div>
                    </div>
                </div>

                <!-- Rincian Kasus -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-center gap-2 text-gray-800 font-medium mb-3">
                        <i class="fa-solid fa-file-lines text-indigo-600"></i>
                        <span>Rincian Kasus</span>
                    </div>
                    <div>
                        <label for="deskripsi_kasus" class="block text-sm font-medium text-gray-700">Deskripsi Kasus <span class="text-rose-600">*</span></label>
                        <textarea id="deskripsi_kasus" name="deskripsi_kasus" rows="4" required
                                  placeholder="Jelaskan secara detail kejadian yang terjadi..."
                                  class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm"><?= old('deskripsi_kasus') ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Sertakan kronologi singkat dan pihak yang terlibat (jika ada).</p>
                    </div>
                </div>

                <!-- Tindakan & Catatan -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-center gap-2 text-gray-800 font-medium mb-3">
                        <i class="fa-solid fa-clipboard-check text-indigo-600"></i>
                        <span>Tindakan & Catatan</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="tindakan_yang_diambil" class="block text-sm font-medium text-gray-700">Tindakan yang Diambil</label>
                            <textarea id="tindakan_yang_diambil" name="tindakan_yang_diambil" rows="3"
                                      placeholder="Uraikan tindakan yang telah diambil untuk menangani kasus ini..."
                                      class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm"><?= old('tindakan_yang_diambil') ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Misal: Pemanggilan orang tua, konseling, teguran lisan.</p>
                        </div>
                        <div>
                            <label for="catatan_guru" class="block text-sm font-medium text-gray-700">Catatan Guru</label>
                            <textarea id="catatan_guru" name="catatan_guru" rows="3"
                                      placeholder="Catatan tambahan dari guru..."
                                      class="a11y-focus mt-1 block w-full rounded-md border-gray-300 text-sm"><?= old('catatan_guru') ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Tambahkan observasi atau rencana tindak lanjut.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="<?= base_url('buku-kasus') ?>" class="a11y-focus inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="a11y-focus inline-flex items-center px-4 py-2 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Kasus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.getElementById('kelas_nama');
    const siswaSelect = document.getElementById('siswa_id');

    kelasSelect.addEventListener('change', function() {
        const kelasNama = this.value;
        
        // Reset siswa dropdown
        siswaSelect.innerHTML = '<option value="">Loading...</option>';
        siswaSelect.disabled = true;

        if (kelasNama) {
            // Fetch siswa data using AJAX
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

    // Simple client validation hint styles using Tailwind
    document.getElementById('formTambahKasus').addEventListener('submit', function(e) {
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
