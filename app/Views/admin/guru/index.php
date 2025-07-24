<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<div class="px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-users"></i> Data Guru
                    </h2>
                    <p class="text-sm opacity-75">Kelola data guru sekolah</p>
                </div>
                <div class="space-x-2">
                    <a href="<?= base_url('admin/guru/create'); ?>" class="bg-white text-indigo-600 hover:bg-indigo-100 font-semibold px-4 py-2 rounded shadow">
                        <i class="fas fa-plus me-1"></i>Tambah Guru
                    </a>
                    <a href="<?= base_url('admin/guru/import'); ?>" onclick="return confirm('Apakah Anda yakin ingin mengimpor data dari file JSON?')"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow font-semibold">
                        <i class="fas fa-download me-1"></i>Import Data
                    </a>
                </div>
            </div>

            <div class="px-6 py-4">
                <?php if (session()->getFlashdata('pesan')): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                        <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('pesan'); ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <!-- Search Form -->
                <form method="get" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Pencarian</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="keyword" placeholder="Cari nama, NIP, atau NUPTK..."
                                       value="<?= esc($keyword ?? '') ?>"
                                       class="pl-10 pr-4 py-2 w-full border rounded focus:outline-none focus:ring focus:border-indigo-400">
                            </div>
                        </div>
                        <div>
                            <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                                <i class="fas fa-search me-1"></i>Cari
                            </button>
                        </div>
                        <?php if ($keyword): ?>
                            <div>
                                <a href="<?= base_url('admin/guru'); ?>"
                                   class="w-full inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded text-center">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded text-sm text-gray-700">
                        <thead class="bg-gray-100 text-gray-800 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">No</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">NIP</th>
                                <th class="px-4 py-3">NUPTK</th>
                                <th class="px-4 py-3 text-center">JK</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Tugas Mengajar</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($guru)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-8 text-gray-500">
                                        <i class="fas fa-users fa-2x mb-2"></i><br>
                                        Data guru tidak ditemukan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $currentPageNum = $pager ? $pager->getCurrentPage() : 1;
                                $nomor = 1 + (10 * ($currentPageNum - 1));
                                foreach ($guru as $g): ?>
                                    <tr class="border-t">
                                        <td class="text-center px-4 py-2 font-semibold"><?= $nomor++; ?></td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 flex items-center justify-center rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="font-semibold"><?= esc($g['nama']); ?></div>
                                                    <div class="text-xs text-gray-500"><?= esc($g['jenis_ptk'] ?? '-'); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2"><?= esc($g['nip']) ?: '-'; ?></td>
                                        <td class="px-4 py-2"><?= esc($g['nuptk']) ?: '-'; ?></td>
                                        <td class="px-4 py-2 text-center">
                                            <?php if ($g['jk'] == 'L'): ?>
                                                <span class="inline-block bg-blue-600 text-white rounded px-2 py-1 text-xs">L</span>
                                            <?php elseif ($g['jk'] == 'P'): ?>
                                                <span class="inline-block bg-pink-500 text-white rounded px-2 py-1 text-xs">P</span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-2">
                                            <?= $g['status_kepegawaian'] ? '<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">'.$g['status_kepegawaian'].'</span>' : '-' ?>
                                        </td>
                                        <td class="px-4 py-2 truncate max-w-xs" title="<?= esc($g['tugas_mengajar']); ?>">
                                            <?= esc($g['tugas_mengajar']) ?: '-'; ?>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="<?= base_url('admin/guru/detail/' . $g['id']); ?>"
                                                   class="text-blue-600 hover:text-blue-800" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('admin/guru/edit/' . $g['id']); ?>"
                                                   class="text-yellow-500 hover:text-yellow-600" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('admin/guru/delete/' . $g['id']); ?>"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                   class="text-red-600 hover:text-red-700" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 text-center">
                    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                        <div class="flex justify-center items-center space-x-1">
                            <?php if ($pager->getCurrentPage() > 1): ?>
                                <a href="?page=<?= $pager->getCurrentPage() - 1 ?><?= isset($keyword) && $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" 
                                   class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif ?>

                            <?php
                            $currentPage = $pager->getCurrentPage();
                            $totalPages = $pager->getPageCount();
                            $start = max(1, $currentPage - 2);
                            $end = min($totalPages, $currentPage + 2);
                            ?>

                            <?php if ($start > 1): ?>
                                <a href="?page=1<?= isset($keyword) && $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" 
                                   class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">1</a>
                                <?php if ($start > 2): ?>
                                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                <?php endif ?>
                            <?php endif ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <?php if ($i == $currentPage): ?>
                                    <span class="px-3 py-2 text-sm text-white bg-indigo-600 border border-indigo-600 rounded-md"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?page=<?= $i ?><?= isset($keyword) && $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" 
                                       class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"><?= $i ?></a>
                                <?php endif ?>
                            <?php endfor ?>

                            <?php if ($end < $totalPages): ?>
                                <?php if ($end < $totalPages - 1): ?>
                                    <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                <?php endif ?>
                                <a href="?page=<?= $totalPages ?><?= isset($keyword) && $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" 
                                   class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"><?= $totalPages ?></a>
                            <?php endif ?>

                            <?php if ($pager->getCurrentPage() < $pager->getPageCount()): ?>
                                <a href="?page=<?= $pager->getCurrentPage() + 1 ?><?= isset($keyword) && $keyword ? '&keyword=' . urlencode($keyword) : '' ?>" 
                                   class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Next
                                </a>
                            <?php endif ?>
                        </div>
                        
                        <!-- Page Info -->
                        <div class="mt-3 text-sm text-gray-600">
                            Halaman <?= $pager->getCurrentPage() ?> dari <?= $pager->getPageCount() ?>
                            <?php 
                            // Get total count safely
                            $db = \Config\Database::connect();
                            $builder = $db->table('guru');
                            if (isset($keyword) && $keyword) {
                                $builder->like('nama', $keyword)
                                       ->orLike('nip', $keyword)
                                       ->orLike('nuptk', $keyword);
                            }
                            $totalRecords = $builder->countAllResults();
                            ?>
                            (Total: <?= $totalRecords ?> data)
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
