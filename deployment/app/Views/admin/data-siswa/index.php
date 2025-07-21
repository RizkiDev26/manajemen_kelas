<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Data Siswa
                    <?php if (isset($userRole) && $userRole === 'walikelas' && isset($userWalikelasData) && !empty($userWalikelasData['kelas'])): ?>
                        <span class="text-lg font-normal text-blue-600">- Kelas <?= esc($userWalikelasData['kelas']) ?></span>
                    <?php endif; ?>
                </h1>
                <p class="text-gray-600">
                    <?php if (isset($userRole) && $userRole === 'walikelas'): ?>
                        Kelola data siswa kelas yang Anda wali
                    <?php else: ?>
                        Kelola data siswa sekolah
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="/admin/data-siswa/export" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>
                        Export CSV
                        <?php if (isset($userRole) && $userRole === 'walikelas' && isset($userWalikelasData) && !empty($userWalikelasData['kelas'])): ?>
                            (Kelas <?= esc($userWalikelasData['kelas']) ?>)
                        <?php endif; ?>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900"><?= number_format($totalSiswa) ?></h3>
                    <p class="text-gray-500 text-sm">Total Siswa</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-blue-600 font-bold text-lg">♂</span>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900"><?= number_format($siswaLaki) ?></h3>
                    <p class="text-gray-500 text-sm">Laki-laki</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-pink-600 font-bold text-lg">♀</span>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900"><?= number_format($siswaPerempuan) ?></h3>
                    <p class="text-gray-500 text-sm">Perempuan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-<?= (isset($userRole) && $userRole === 'walikelas') ? '3' : '4' ?> gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="<?= esc($search) ?>" 
                           placeholder="Nama, NISN, atau NIPD..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Class Filter - Only show for admin -->
            <?php if (!isset($userRole) || $userRole !== 'walikelas'): ?>
            <div>
                <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="kelas" name="kelas" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelasOptions as $kelasOption): ?>
                        <option value="<?= esc($kelasOption) ?>" <?= $selectedKelas == $kelasOption ? 'selected' : '' ?>>
                            <?= esc($kelasOption) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
            <!-- Hidden input for walikelas to maintain their class filter -->
            <input type="hidden" name="kelas" value="<?= esc($selectedKelas) ?>">
            <?php endif; ?>

            <!-- Gender Filter -->
            <div>
                <label for="jk" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                <select id="jk" name="jk" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="L" <?= $selectedJk == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= $selectedJk == 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>

            <div class="md:col-span-<?= (isset($userRole) && $userRole === 'walikelas') ? '3' : '4' ?> flex space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Filter
                </button>
                <a href="/admin/data-siswa" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Daftar Siswa 
                <?php if (isset($userRole) && $userRole === 'walikelas' && isset($userWalikelasData) && !empty($userWalikelasData['kelas'])): ?>
                    <span class="text-blue-600 font-normal">Kelas <?= esc($userWalikelasData['kelas']) ?></span>
                <?php endif; ?>
                <?php if (!empty($search) || (!empty($selectedKelas) && (!isset($userRole) || $userRole !== 'walikelas')) || !empty($selectedJk)): ?>
                    <span class="text-gray-500 font-normal">(Hasil Filter)</span>
                <?php endif; ?>
            </h3>
            <?php if (isset($userRole) && $userRole === 'walikelas'): ?>
                <p class="text-sm text-gray-500 mt-1">Anda hanya dapat melihat data siswa dari kelas yang Anda wali</p>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat, Tanggal Lahir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($siswa)): ?>
                        <?php 
                        $no = ($currentPage - 1) * $perPage + 1;
                        foreach ($siswa as $row): 
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            <?= strtoupper(substr($row['nama'], 0, 2)) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= esc($row['nama']) ?></div>
                                        <div class="text-sm text-gray-500">NIPD: <?= esc($row['nipd']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($row['nisn']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= esc($row['kelas']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($row['jk'] == 'L'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ♂ Laki-laki
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                        ♀ Perempuan
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div><?= esc($row['tempat_lahir']) ?></div>
                                <div class="text-gray-500"><?= !empty($row['tanggal_lahir']) ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-' ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="/admin/data-siswa/detail/<?= $row['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-gray-500">Tidak ada data siswa</p>
                                    <?php if (!empty($search) || !empty($selectedKelas) || !empty($selectedJk)): ?>
                                        <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= url_to('admin/data-siswa') . '?' . http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    <?php endif; ?>
                    <?php if ($currentPage < $pager->getPageCount()): ?>
                        <a href="<?= url_to('admin/data-siswa') . '?' . http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan 
                            <span class="font-medium"><?= ($currentPage - 1) * $perPage + 1 ?></span>
                            sampai 
                            <span class="font-medium"><?= min($currentPage * $perPage, $totalSiswa) ?></span>
                            dari 
                            <span class="font-medium"><?= number_format($totalSiswa) ?></span>
                            hasil
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?= $pager->links() ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
