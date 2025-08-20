<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Siswa</h1>
                <p class="text-gray-600"><?= esc($siswa['nama']) ?></p>
            </div>
            <div class="flex space-x-3">
                <a href="/admin/data-siswa" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 font-bold text-2xl">
                            <?= strtoupper(substr($siswa['nama'], 0, 2)) ?>
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($siswa['nama']) ?></h3>
                    <div class="space-y-2">
                        <p class="text-gray-600">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?= esc($siswa['kelas']) ?>
                            </span>
                        </p>
                        <p class="text-gray-600">
                            <?php if ($siswa['jk'] == 'L'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    ♂ Laki-laki
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                                    ♀ Perempuan
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Informasi Kontak</h4>
                    <div class="space-y-2">
                        <?php if (!empty($siswa['hp'])): ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <?= esc($siswa['hp']) ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($siswa['email'])): ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <?= esc($siswa['email']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">NISN</label>
                        <p class="text-gray-900"><?= esc($siswa['nisn']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">NIPD</label>
                        <p class="text-gray-900"><?= esc($siswa['nipd']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">NIK</label>
                        <p class="text-gray-900"><?= esc($siswa['nik']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Agama</label>
                        <p class="text-gray-900"><?= esc($siswa['agama']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tempat Lahir</label>
                        <p class="text-gray-900"><?= esc($siswa['tempat_lahir']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                        <p class="text-gray-900"><?= !empty($siswa['tanggal_lahir']) ? date('d F Y', strtotime($siswa['tanggal_lahir'])) : '-' ?></p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Alamat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                        <p class="text-gray-900"><?= esc($siswa['alamat']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">RT</label>
                        <p class="text-gray-900"><?= esc($siswa['rt']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">RW</label>
                        <p class="text-gray-900"><?= esc($siswa['rw']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Kelurahan</label>
                        <p class="text-gray-900"><?= esc($siswa['kelurahan']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Kecamatan</label>
                        <p class="text-gray-900"><?= esc($siswa['kecamatan']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Kode Pos</label>
                        <p class="text-gray-900"><?= esc($siswa['kode_pos']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Tinggal</label>
                        <p class="text-gray-900"><?= esc($siswa['jenis_tinggal']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alat Transportasi</label>
                        <p class="text-gray-900"><?= esc($siswa['alat_transportasi']) ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Orang Tua</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Father Info -->
                    <div>
                        <h4 class="text-md font-medium text-gray-800 mb-3">Data Ayah</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                                <p class="text-gray-900"><?= esc($siswa['nama_ayah']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tahun Lahir</label>
                                <p class="text-gray-900"><?= esc($siswa['tahun_lahir_ayah']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Pendidikan</label>
                                <p class="text-gray-900"><?= esc($siswa['pendidikan_ayah']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Pekerjaan</label>
                                <p class="text-gray-900"><?= esc($siswa['pekerjaan_ayah']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Penghasilan</label>
                                <p class="text-gray-900"><?= esc($siswa['penghasilan_ayah']) ?: '-' ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Mother Info -->
                    <div>
                        <h4 class="text-md font-medium text-gray-800 mb-3">Data Ibu</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                                <p class="text-gray-900"><?= esc($siswa['nama_ibu']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tahun Lahir</label>
                                <p class="text-gray-900"><?= esc($siswa['tahun_lahir_ibu']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Pendidikan</label>
                                <p class="text-gray-900"><?= esc($siswa['pendidikan_ibu']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Pekerjaan</label>
                                <p class="text-gray-900"><?= esc($siswa['pekerjaan_ibu']) ?: '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Penghasilan</label>
                                <p class="text-gray-900"><?= esc($siswa['penghasilan_ibu']) ?: '-' ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guardian Info (if available) -->
                <?php if (!empty($siswa['nama_wali'])): ?>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-md font-medium text-gray-800 mb-3">Data Wali</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                            <p class="text-gray-900"><?= esc($siswa['nama_wali']) ?: '-' ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tahun Lahir</label>
                            <p class="text-gray-900"><?= esc($siswa['tahun_lahir_wali']) ?: '-' ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Pendidikan</label>
                            <p class="text-gray-900"><?= esc($siswa['pendidikan_wali']) ?: '-' ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Pekerjaan</label>
                            <p class="text-gray-900"><?= esc($siswa['pekerjaan_wali']) ?: '-' ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Penerima KPS</label>
                        <p class="text-gray-900"><?= esc($siswa['penerima_kps']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Penerima KIP</label>
                        <p class="text-gray-900"><?= esc($siswa['penerima_kip']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Anak Ke-</label>
                        <p class="text-gray-900"><?= esc($siswa['anak_ke_berapa']) ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Saudara Kandung</label>
                        <p class="text-gray-900"><?= esc($siswa['jml_saudara_kandung']) ?: '-' ?></p>
                    </div>
                    <?php if (!empty($siswa['berat_badan']) || !empty($siswa['tinggi_badan'])): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Berat Badan</label>
                        <p class="text-gray-900"><?= esc($siswa['berat_badan']) ? $siswa['berat_badan'] . ' kg' : '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tinggi Badan</label>
                        <p class="text-gray-900"><?= esc($siswa['tinggi_badan']) ? $siswa['tinggi_badan'] . ' cm' : '-' ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
