<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan - SDN Grogol Utara 09</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-gradient {
            background: linear-gradient(90deg, #0052cc 0%, #00b894 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bb/Tut_Wuri_Handayani_logo.svg/1200px-Tut_Wuri_Handayani_logo.svg.png" alt="Logo" class="h-8 w-8 mr-2">
                        <h1 class="text-lg font-bold text-gray-800">SDN Grogol Utara 09</h1>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            <!-- Error Icon -->
            <div>
                <div class="mx-auto h-24 w-24 text-red-500">
                    <i class="fas fa-exclamation-triangle text-6xl"></i>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Oops! Terjadi Kesalahan
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Kami sedang mengalami gangguan teknis. Tim kami telah diberitahu dan sedang bekerja untuk memperbaikinya.
                </p>
            </div>

            <!-- Error Details -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Apa yang dapat Anda lakukan:
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Coba muat ulang halaman dalam beberapa menit</li>
                                <li>Periksa koneksi internet Anda</li>
                                <li>Kembali ke halaman utama</li>
                                <li>Hubungi administrator jika masalah berlanjut</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="window.location.reload()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-redo mr-2"></i>
                    Coba Lagi
                </button>
                
                <a href="/" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Jika Anda terus mengalami masalah, silakan hubungi kami:
                </p>
                <div class="mt-2 flex justify-center space-x-6 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-1"></i>
                        admin@sdngrogolutara09.sch.id
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone mr-1"></i>
                        (021) 1234-5678
                    </div>
                </div>
            </div>

            <!-- Timestamp -->
            <div class="text-xs text-gray-400">
                Waktu kejadian: <?= date('d M Y H:i:s') ?> WIB
            </div>
        </div>
    </div>

    <!-- Auto reload after 30 seconds -->
    <script>
        // Auto reload after 30 seconds
        setTimeout(function() {
            if (confirm('Halaman akan dimuat ulang secara otomatis. Lanjutkan?')) {
                window.location.reload();
            }
        }, 30000);

        // Add some interactive feedback
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('button, a');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.add('opacity-75');
                    setTimeout(() => {
                        this.classList.remove('opacity-75');
                    }, 150);
                });
            });
        });
    </script>
</body>
</html>
