<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>
Buku Kasus Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Buku Kasus Siswa</h1>
        <nav class="mt-2 text-sm text-gray-500">
            <ol class="flex items-center space-x-2">
                <li><a href="<?= base_url('admin') ?>" class="text-indigo-600 hover:underline">Dashboard</a></li>
                <li>/</li>
                <li class="text-gray-700">Buku Kasus</li>
            </ol>
        </nav>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200 text-green-800">
            <div class="flex">
                <span class="mr-2"><i class="fa-solid fa-circle-check"></i></span>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200 text-red-800">
            <div class="flex">
                <span class="mr-2"><i class="fa-solid fa-triangle-exclamation"></i></span>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Card 1: Header + Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4 overflow-visible relative">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center text-gray-700 font-medium">
                <i class="fa-solid fa-book mr-2 text-indigo-600"></i>
                <span>Data Kasus Siswa</span>
            </div>
            <a href="<?= base_url('buku-kasus/tambah') ?>" class="a11y-focus inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Kasus
            </a>
        </div>
        <div class="px-6 py-5">
            <!-- Filter Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative z-50">
                <div>
                    <label for="filterKelas" class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
                    <select id="filterKelas" name="filterKelas" class="a11y-focus relative z-50 block w-full rounded-md border border-gray-300 text-sm focus:border-indigo-600 focus:ring-0">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelasList as $kelas): ?>
                            <option value="<?= $kelas['nama'] ?>" <?= ($selectedKelas == $kelas['nama']) ? 'selected' : '' ?>>
                                <?= $kelas['nama'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                    <select id="filterStatus" name="filterStatus" class="a11y-focus relative z-50 block w-full rounded-md border border-gray-300 text-sm focus:border-indigo-600 focus:ring-0">
                        <option value="">Semua Status</option>
                        <option value="belum_ditangani" <?= ($selectedStatus == 'belum_ditangani') ? 'selected' : '' ?>>Belum Ditangani</option>
                        <option value="dalam_proses" <?= ($selectedStatus == 'dalam_proses') ? 'selected' : '' ?>>Dalam Proses</option>
                        <option value="selesai" <?= ($selectedStatus == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="button" id="btnFilter" class="a11y-focus inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-gray-700 hover:bg-gray-800">
                        <i class="fa-solid fa-filter mr-2"></i> Filter
                    </button>
                    <button type="button" id="btnReset" class="a11y-focus inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                        <i class="fa-solid fa-rotate-right mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Table only -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-visible">
        <div class="px-6 py-5">
            <div class="relative z-0 overflow-x-auto overflow-y-visible">
                <table class="min-w-full divide-y divide-gray-200" id="datatablesSimple">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">No</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Nama Siswa</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Kelas</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Jenis Kasus</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Pelapor</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <?php if (!empty($kasusList)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($kasusList as $kasus): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-sm text-gray-700"><?= $no++ ?></td>
                                    <td class="px-3 py-2 text-sm text-gray-700"><?= date('d/m/Y', strtotime($kasus['tanggal_kejadian'])) ?></td>
                                    <td class="px-3 py-2 text-sm font-medium text-gray-800"><?= esc($kasus['nama_siswa']) ?></td>
                                    <td class="px-3 py-2 text-sm"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-800"><?= esc($kasus['kelas']) ?></span></td>
                                    <td class="px-3 py-2 text-sm text-gray-700"><?= esc($kasus['jenis_kasus']) ?></td>
                                    <td class="px-3 py-2 text-sm">
                                        <?php
                                        $statusClass = match($kasus['status']) {
                                            'belum_ditangani' => 'bg-red-100 text-red-800',
                                            'dalam_proses' => 'bg-amber-100 text-amber-800',
                                            'selesai' => 'bg-emerald-100 text-emerald-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $statusText = match($kasus['status']) {
                                            'belum_ditangani' => 'Belum Ditangani',
                                            'dalam_proses' => 'Dalam Proses',
                                            'selesai' => 'Selesai',
                                            default => 'Unknown'
                                        };
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-700"><?= esc($kasus['nama_guru']) ?></td>
                                    <td class="px-3 py-2 text-sm">
                                        <div class="flex items-center gap-2">
                                            <a href="<?= base_url('buku-kasus/detail/' . $kasus['id']) ?>" 
                                               class="a11y-focus inline-flex items-center px-2.5 py-1.5 rounded-md text-white bg-sky-600 hover:bg-sky-700" title="Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('buku-kasus/edit/' . $kasus['id']) ?>" 
                                               class="a11y-focus inline-flex items-center px-2.5 py-1.5 rounded-md text-white bg-amber-500 hover:bg-amber-600" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?= base_url('buku-kasus/cetak/' . $kasus['id']) ?>" 
                                               class="a11y-focus inline-flex items-center px-2.5 py-1.5 rounded-md text-white bg-emerald-600 hover:bg-emerald-700" title="Cetak PDF" target="_blank">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                            <button type="button" class="a11y-focus inline-flex items-center px-2.5 py-1.5 rounded-md text-white bg-rose-600 hover:bg-rose-700"
                                                    onclick="confirmDelete(<?= $kasus['id'] ?>)" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-3 py-6 text-center text-gray-500 text-sm">Tidak ada data kasus</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kasus ini? Tindakan ini tidak dapat dibatalkan.')) {
        window.location.href = '<?= base_url('buku-kasus/hapus/') ?>' + id;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const btnFilter = document.getElementById('btnFilter');
    const btnReset = document.getElementById('btnReset');
    
    btnFilter.addEventListener('click', function() {
        const kelas = document.getElementById('filterKelas').value;
        const status = document.getElementById('filterStatus').value;
        
        let url = '<?= base_url('buku-kasus') ?>';
        const params = new URLSearchParams();
        
        if (kelas) params.append('kelas', kelas);
        if (status) params.append('status', status);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        window.location.href = url;
    });
    
    btnReset.addEventListener('click', function() {
        window.location.href = '<?= base_url('buku-kasus') ?>';
    });
});
</script>
<?= $this->endSection() ?>
