<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto py-12">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-100 flex items-center justify-center">
            <i class="fas fa-tools text-yellow-600 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Dalam Proses Pengembangan</h1>
        <p class="text-gray-600 mb-6">Menu ini masih dalam proses pengembangan. Silakan kembali lagi nanti.</p>
        <a href="/dashboard" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
<?= $this->endSection() ?>
