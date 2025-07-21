 <?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
    <h1 class="text-3xl font-bold mb-6">Daftar Berita</h1>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <a href="/admin/berita/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Tambah Berita</a>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Judul</th>
                <th class="py-2 px-4 border-b">Tanggal</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($berita) && is_array($berita)): ?>
                <?php foreach ($berita as $item): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?= esc($item['id']) ?></td>
                        <td class="py-2 px-4 border-b"><?= esc($item['judul']) ?></td>
                        <td class="py-2 px-4 border-b"><?= esc($item['tanggal']) ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="/admin/berita/edit/<?= esc($item['id']) ?>" class="text-blue-600 hover:underline mr-4">Edit</a>
                            <form action="/admin/berita/delete/<?= esc($item['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-4">Belum ada berita.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?= $this->endSection() ?>
