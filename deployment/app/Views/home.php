<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDN GrogoL Utara 09</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0052cc',
                        secondary: '#00b894',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(90deg, #0052cc 0%, #00b894 100%);
        }
        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .stat-item {
            text-align: center;
            color: white;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9rem;
        }
        .icon-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            transition: all 0.3s;
        }
        .icon-menu:hover {
            transform: translateY(-5px);
        }
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            background-color: #f0f9ff;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo dan Brand -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bb/Tut_Wuri_Handayani_logo.svg/1200px-Tut_Wuri_Handayani_logo.svg.png" alt="Logo" class="h-10 w-10 mr-2">
                        <h1 class="text-lg font-bold text-gray-800">SDN Grogol Utara 09</h1>
                    </div>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Home</a>
                    <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Profil <i class="fas fa-chevron-down text-xs ml-1"></i></a>
                    <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Direktori <i class="fas fa-chevron-down text-xs ml-1"></i></a>
                    <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Publikasi <i class="fas fa-chevron-down text-xs ml-1"></i></a>
                    <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Ekskul <i class="fas fa-chevron-down text-xs ml-1"></i></a>
                    <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">PPDB <i class="fas fa-chevron-down text-xs ml-1"></i></a>
                    <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Unduhan <i class="fas fa-chevron-down text-xs ml-1"></i></a>
                    
                    <a href="#" class="text-primary hover:text-blue-700 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ml-4">
                        <i class="fas fa-user mr-1"></i> Login
                    </a>
                    <a href="#" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 flex items-center">
                        Kontak <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-primary focus:outline-none focus:text-primary">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Profil</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Direktori</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Publikasi</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Ekskul</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">PPDB</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Unduhan</a>
                <a href="#" class="text-primary hover:text-blue-700 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                <a href="#" class="bg-primary text-white block px-3 py-2 rounded-md text-base font-medium mt-2">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <div class="bg-white text-primary inline-block px-4 py-2 rounded-md mb-4">
                        Selamat Datang di Website
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">
                        SDN GrogoL Utara 09
                    </h1>
                    <p class="text-xl mb-8">
                        Jl. Kemandoran I Rt. 004 Rw.005 Kel. Grogol Utara Kec. Kebayoran Lama Jakarta Selatan
                    </p>
                    
                    <!-- Search Bar -->
                    <div class="mb-8">
                        <div class="flex items-center">
                            <div class="relative w-full mr-2">
                                <input type="text" placeholder="Masukan Kata Kunci.." class="w-full px-4 py-3 rounded-l-md text-gray-700 focus:outline-none">
                                <div class="absolute right-0 top-0 flex items-center h-full">
                                    <select class="h-full bg-gray-100 text-gray-700 rounded-none border-l px-4 focus:outline-none">
                                        <option>Berita</option>
                                    </select>
                                </div>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-3 rounded-r-md hover:bg-blue-700">
                                <i class="fas fa-search"></i> Pencarian
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="grid grid-cols-4 gap-4 mb-8">
                        <div class="stat-item">
                            <div class="stat-number">6</div>
                            <div class="stat-label">Jumlah Guru</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">6</div>
                            <div class="stat-label">Jumlah Siswa</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">6</div>
                            <div class="stat-label">Komite Sekolah</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4</div>
                            <div class="stat-label">Total Pegawai</div>
                        </div>
                    </div>
                </div>
                
                <!-- Illustration -->
                <div class="relative">
                    <img src="https://img.freepik.com/free-vector/teacher-students-wearing-face-masks_52683-38400.jpg" alt="School Illustration" class="rounded-full bg-white p-2 w-full max-w-md mx-auto">
                    
                    <!-- Go Digital Badge -->
                    <div class="absolute bottom-4 right-4 md:bottom-8 md:right-8 bg-white rounded-lg shadow-lg p-3 flex items-center">
                        <div class="bg-blue-600 rounded-full p-2 mr-2">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">Go Digital</div>
                            <div class="text-sm text-gray-600">Sekolahku</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Icon Navigation -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="#" class="icon-menu">
                    <div class="icon-circle">
                        <i class="fas fa-building text-blue-600 text-2xl"></i>
                    </div>
                    <span class="font-medium text-gray-800">Profil</span>
                </a>
                <a href="#" class="icon-menu">
                    <div class="icon-circle">
                        <i class="fas fa-sitemap text-blue-600 text-2xl"></i>
                    </div>
                    <span class="font-medium text-gray-800">Struktur</span>
                </a>
                <a href="#" class="icon-menu">
                    <div class="icon-circle">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <span class="font-medium text-gray-800">Komite</span>
                </a>
                <a href="#" class="icon-menu">
                    <div class="icon-circle">
                        <i class="fas fa-chalkboard-teacher text-blue-600 text-2xl"></i>
                    </div>
                    <span class="font-medium text-gray-800">Guru</span>
                </a>
                <a href="#" class="icon-menu">
                    <div class="icon-circle">
                        <i class="fas fa-user-graduate text-blue-600 text-2xl"></i>
                    </div>
                    <span class="font-medium text-gray-800">Siswa</span>
                </a>
                <a href="#" class="icon-menu">
                    <div class="icon-circle">
                        <i class="fas fa-newspaper text-blue-600 text-2xl"></i>
                    </div>
                    <span class="font-medium text-gray-800">Publikasi</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Berita Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Berita Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php if (!empty($berita) && is_array($berita)): ?>
                    <?php foreach ($berita as $item): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <?php if (!empty($item['gambar'])): ?>
                                <img src="<?= esc($item['gambar']) ?>" alt="<?= esc($item['judul']) ?>" class="w-full h-48 object-cover">
                            <?php endif; ?>
                            <div class="p-4">
                                <h3 class="text-xl font-semibold mb-2"><?= esc($item['judul']) ?></h3>
                                <p class="text-gray-600 text-sm mb-2"><?= date('d M Y', strtotime($item['tanggal'])) ?></p>
                                <p class="text-gray-700"><?= esc(substr($item['isi'], 0, 100)) ?>...</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-600 col-span-3">Belum ada berita tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Pimpinan Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Pimpinan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <!-- Kepala Sekolah -->
                <div class="text-center">
                    <div class="mx-auto w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mb-4">
                        <span class="text-gray-400 text-6xl">👤</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700">Kepala Sekolah</h3>
                    <p class="text-gray-600 mt-1">Nama Kepala Sekolah</p>
                </div>
                <!-- Wakil Bidang Kurikulum -->
                <div class="text-center">
                    <div class="mx-auto w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mb-4">
                        <span class="text-gray-400 text-6xl">👤</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700">Wakil Bidang Kurikulum</h3>
                    <p class="text-gray-600 mt-1">Nama Wakil Kurikulum</p>
                </div>
                <!-- Wakil Bidang Kesiswaan -->
                <div class="text-center">
                    <div class="mx-auto w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mb-4">
                        <span class="text-gray-400 text-6xl">👤</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700">Wakil Bidang Kesiswaan</h3>
                    <p class="text-gray-600 mt-1">Nama Wakil Kesiswaan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-600">
                <p>&copy; 2025 SDN GrogoL Utara 09. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
