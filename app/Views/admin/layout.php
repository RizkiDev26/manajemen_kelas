<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --sidebar-width-expanded: 220px;
            --sidebar-width-collapsed: 100px;
            --content-padding-expanded: 0;
            --content-padding-collapsed: 0;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        /* Disable all transitions globally */
        *, *::before, *::after {
            transition: none !important;
            animation: none !important;
        }
        
        /* Re-enable specific smooth transitions for sidebar and menu items */
        .sidebar { transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important; }
        .sidebar nav a, .sidebar nav div { transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important; }
        .content-wrapper, .fixed-header { transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important; }
        .sidebar nav .submenu { transition: max-height 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94), opacity 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important; }
        .sidebar nav .submenu-chevron { transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important; }
        .sidebar-text, .menu-text, .menu-label { transition: opacity 0.3s ease, visibility 0.3s ease !important; }
        
        /* Submenu functionality */
        .menu-item-with-submenu .submenu { max-height: 0; opacity: 0; overflow: hidden; }
        .menu-item-with-submenu.open .submenu { max-height: 200px; opacity: 1; }
        .menu-item-with-submenu.open .submenu-chevron { transform: rotate(180deg); }
        
    /* Enhanced menu item styles (no horizontal shift on hover) */
    .sidebar nav a:hover, .sidebar nav div:hover { transform: none !important; box-shadow: 0 4px 20px rgba(255, 255, 255, 0.1); }
        
        /* Badge animations */
        .sidebar nav .bg-blue-400\/30,
        .sidebar nav .bg-green-400\/30,
        .sidebar nav .bg-orange-400\/30 { transition: all 0.3s ease !important; }
        .sidebar nav a:hover .bg-blue-400\/30,
        .sidebar nav a:hover .bg-green-400\/30,
        .sidebar nav a:hover .bg-orange-400\/30 { transform: scale(1.1); box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2); }
        
    /* Updated to match siswa (student) purple sidebar gradient */
    .gradient-bg { background: linear-gradient(140deg, #4338CA 0%, #6D28D9 45%, #A21CAF 100%); box-shadow: 0 8px 32px rgba(109, 40, 217, 0.35); }
        
        /* Fixed Layout Styles */
        .main-container {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
            overflow-x: hidden;
            overflow-y: visible;
        }
        
        .sidebar {
            width: var(--sidebar-width-collapsed);
            flex-shrink: 0;
            background: linear-gradient(140deg, #4338CA 0%, #6D28D9 45%, #A21CAF 100%);
            position: fixed; top: 0; left: 0; height: 100vh; z-index: 110;
            overflow-y: auto; display: flex; flex-direction: column;
            box-shadow: 8px 0 32px rgba(109, 40, 217, 0.30); backdrop-filter: blur(15px);
        }
        /* Hover expanded appearance overlays content without pushing */
        .sidebar.expanded { width: var(--sidebar-width-expanded); }
        .sidebar.collapsed { width: var(--sidebar-width-collapsed); }
        .sidebar.collapsed .sidebar-text, .sidebar.collapsed .menu-text, .sidebar.collapsed .menu-label { opacity: 0; pointer-events: none; width: 0; overflow: hidden; position: absolute; visibility: hidden; }
    /* Hide chevron + active indicator when collapsed */
    .sidebar.collapsed .submenu-chevron { display: none !important; }
    .sidebar.collapsed .w-1.bg-white.rounded-full { display: none !important; }
        .sidebar .p-6 { background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%); backdrop-filter: blur(10px); }
    .sidebar.collapsed nav ul li a { justify-content: center; padding: 0.6rem; min-height: 48px; display: flex; align-items: center; gap:0 !important; }
    .sidebar.collapsed nav ul li a svg { margin: 0; opacity: 1; }
    /* Uniform icon container size expanded & collapsed */
    .sidebar nav a .flex-shrink-0 { width:46px; height:46px; }
    .sidebar.collapsed nav a .flex-shrink-0 { width:46px; height:46px; }
    /* Prevent lateral shift when collapsed */
    .sidebar.collapsed nav a:hover, .sidebar.collapsed nav div:hover { transform:none !important; }
    /* Hide text gap cleanly */
    .sidebar.collapsed nav a .menu-text { display:none !important; }
    /* Hide active vertical bar when collapsed */
    .sidebar.collapsed nav a > .w-1.h-8 { display:none !important; }
    /* Remove residual spacing from space-x-* utilities when collapsed */
    .sidebar.collapsed nav a.space-x-3 > :not(:first-child),
    .sidebar.collapsed nav div.space-x-3 > :not(:first-child) { margin-left:0 !important; }
    /* Ensure submenu toggle divs also centered */
    .sidebar.collapsed nav .submenu-toggle { justify-content:center; padding:0.6rem; }
    /* Square logo box */
    .logo-box { width:56px; height:56px; border-radius:14px; }
    @media (max-width:1023px){ .logo-box { width:54px; height:54px; } }
        
        .content-wrapper {
            flex: 1; display: flex; flex-direction: column; overflow-x: hidden;
            margin-left: var(--sidebar-width-collapsed);
            min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            height: 100vh; padding-left: 0; transition: margin-left .35s ease;
        }
        /* Content never shifts on hover; if we later allow pinned expand we can add a class to change margin */
        .content-wrapper.pinned-expanded { margin-left: var(--sidebar-width-expanded); }
        
        .fixed-header {
            position: fixed; top: 0; left: var(--sidebar-width-collapsed); right: 0; z-index: 50;
            width: calc(100% - var(--sidebar-width-collapsed)); height: 60px;
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }
        .fixed-header.pinned-expanded { left: var(--sidebar-width-expanded); width: calc(100% - var(--sidebar-width-expanded)); transition: left .35s ease,width .35s ease; }
        
        .content-area {
            flex: 1;
            overflow: visible;
            /* Reduced top padding to bring content closer to navbar */
            padding: 1.1rem 1.6rem 2rem; 
            margin-top: 60px;
            min-height: calc(100vh - 60px);
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            height: auto;
            scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
            margin-left: 20px;
        }
        /* Remove unintended extra top margin from first direct child */
        .content-area > *:first-child { margin-top: 0 !important; }
        .content-area::-webkit-scrollbar { width: 6px; }
        .content-area::-webkit-scrollbar-track { background: transparent; }
    .content-area::-webkit-scrollbar-thumb { background: linear-gradient(140deg, #4338CA 0%, #6D28D9 60%, #A21CAF 100%); border-radius: 3px; }
    .content-area::-webkit-scrollbar-thumb:hover { background: linear-gradient(140deg, #4f46e5 0%, #7c3aed 55%, #c026d3 100%); }
        
        /* Ensure sidebar content doesn't overflow */
        .sidebar nav { flex: 1; overflow-y: auto; padding: 0; scrollbar-width: thin; scrollbar-color: rgba(255, 255, 255, 0.3) transparent; }
        .sidebar nav::-webkit-scrollbar { width: 4px; }
        .sidebar nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar nav::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); border-radius: 2px; }
        .sidebar nav::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.5); }
        
        @media (max-width: 1023px) {
            .sidebar { position: fixed !important; top: 0 !important; left: 0 !important; width: 320px !important; height: 100vh !important; z-index: 1000 !important; transform: translateX(-100%); background: linear-gradient(140deg, #4338CA 0%, #6D28D9 45%, #A21CAF 100%) !important; backdrop-filter: blur(10px); box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15); overflow-y: auto; }
            .sidebar.open { transform: translateX(0); }
            .sidebar .p-4 { padding: 1.5rem !important; }
            .sidebar nav { padding: 1rem !important; }
            .sidebar .menu-text { font-size: 1.4rem !important; font-weight: 500 !important; }
            .sidebar .menu-label { font-size: 1.25rem !important; margin-bottom: 1rem !important; }
            .content-area { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 1rem !important; box-sizing: border-box; overflow-x: hidden; }
            .fixed-header { left: 0 !important; width: 100% !important; height: 60px !important; background: rgba(255, 255, 255, 0.95) !important; backdrop-filter: blur(15px) !important; }
            .content-wrapper { margin-left: 0 !important; width: 100% !important; max-width: 100% !important; padding: 0 !important; box-sizing: border-box; }
            .content-area .container, .content-area .max-w-7xl, .content-area .mx-auto { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 1rem !important; box-sizing: border-box; }
        }
        
        @media (max-width: 768px) {
            .content-area { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0.75rem !important; box-sizing: border-box; overflow-x: hidden; }
            .sidebar { width: 280px !important; }
            .fixed-header { padding: 0.5rem 1rem !important; }
            .fixed-header .text-xs { font-size: 1rem !important; }
            .fixed-header .text-sm { font-size: 1.1rem !important; }
            .fixed-header input { font-size: 1.1rem !important; }
            .fixed-header button { font-size: 1.1rem !important; }
            .content-wrapper { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; box-sizing: border-box; }
            .content-area > * { width: 100% !important; max-width: 100% !important; margin-left: 0 !important; margin-right: 0 !important; box-sizing: border-box; }
            .content-area .container, .content-area .max-w-7xl, .content-area .mx-auto { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0.75rem !important; box-sizing: border-box; }
        }
        
        @media (min-width: 1024px) {
            .content-area { margin-left: 0; padding: 0.5rem; max-width: none; overflow: visible !important; height: auto !important; }
            .content-wrapper:not(.sidebar-collapsed) { margin-left: 0 !important; }
            .content-wrapper.sidebar-collapsed { margin-left: 0 !important; }
            .fixed-header:not(.sidebar-collapsed) { left: var(--sidebar-width-expanded) !important; width: calc(100% - var(--sidebar-width-expanded)) !important; }
            .fixed-header.sidebar-collapsed { left: var(--sidebar-width-collapsed) !important; width: calc(100% - var(--sidebar-width-collapsed)) !important; }
        }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); z-index: 50; transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important; }
            .sidebar.open { transform: translateX(0); }
            .content-wrapper { margin-left: 0 !important; transition: none !important; }
            .fixed-header { left: 0 !important; width: 100% !important; transition: none !important; }
        }
        
        /* Enhanced hover and focus states */
    .sidebar nav a:focus, .sidebar nav div:focus { outline: none; transform: none !important; box-shadow: 0 4px 20px rgba(255, 255, 255, 0.15); }
    /* Override Tailwind hover:translate-x-1 from markup to keep items stationary */
    .sidebar nav a, .sidebar nav div { transition: background-color .3s, color .3s, box-shadow .3s; }
    .sidebar nav a:hover { --tw-translate-x:0 !important; }
    .sidebar nav div:hover { --tw-translate-x:0 !important; }
        
        /* Global accessible focus outline helper */
        .a11y-focus:focus, .a11y-focus:focus-visible {
            outline: 3px solid #6366f1 !important; /* indigo-500 */
            outline-offset: 2px !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
            border-color: #4f46e5 !important; /* indigo-600 */
        }
    </style>
</head>
<body class="main-container bg-gray-50">
    <!-- Sidebar -->
    <aside class="sidebar gradient-bg shadow-xl flex flex-col relative collapsed" id="mainSidebar">
        <!-- Logo Section -->
    <div class="p-6 border-b border-white/20 relative bg-gradient-to-r from-white/5 to-transparent">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
            <!-- Logo icon: changed to perfect square with slightly smaller radius to avoid elongated look -->
                    <div class="logo-box bg-white/15 flex items-center justify-center backdrop-blur-sm border border-white/30 shadow-lg">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div class="sidebar-text">
                        <h1 class="text-white text-xl font-bold tracking-tight">SDN GU 09</h1>
                        <p class="text-white/80 text-sm font-medium">Aplikasi Pengelolaan Sekolah</p>
                        <div class="flex items-center mt-1">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-white/60 text-xs">Online</span>
                        </div>
                    </div>
                </div>
                <!-- Sidebar Toggle Button -->
                <!-- Improved toggle button with dynamic double-arrow icon -->
                <button id="sidebarCollapse" class="sidebar-toggle-btn hidden" title="Sidebar hover expand only">
                    <i class="fas fa-angles-left text-white text-base"></i>
                </button>
            </div>
        </div>

        <!-- Menu Section -->
        <nav class="flex-1 overflow-y-auto px-4 py-4">
            <?php 
            $userRole = session()->get('role');
            // Base URL: walikelas and siswa use root routes, admin uses /admin
            $baseUrl = (in_array($userRole, ['walikelas','siswa'])) ? '' : '/admin';
            ?>
            
            <!-- Main Navigation -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4 py-2 px-2">
                    <h3 class="menu-label text-white/70 text-xs font-bold uppercase tracking-wider">Menu Utama</h3>
                    <div class="w-8 h-px bg-white/20"></div>
                </div>
                
                <ul class="space-y-2">
                    <?php if ($userRole !== 'siswa'): ?>
                    <!-- Dashboard -->
                    <li>
                        <a href="<?= $baseUrl ?>/dashboard" title="Dashboard" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= uri_string() === 'admin/dashboard' || uri_string() === 'dashboard' ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-chart-pie text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Dashboard</span>
                            </div>
                            <?php if(uri_string() === 'admin/dashboard' || uri_string() === 'dashboard'): ?>
                                <div class="w-1 h-8 bg-white rounded-full"></div>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <!-- Data Siswa -->
                    <li>
                        <a href="<?= $baseUrl ?>/data-siswa" title="Data Siswa" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= strpos(uri_string(), 'data-siswa') !== false ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-graduation-cap text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Data Siswa</span>
                            </div>
                        </a>
                    </li>

                    <!-- Absensi & Kehadiran / Daftar Hadir -->
                    <li class="menu-item-with-submenu">
                        <div class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= strpos(uri_string(), 'absensi') !== false || strpos(uri_string(), 'daftar-hadir') !== false ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 cursor-pointer submenu-toggle">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-clipboard-check text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">
                                    <?= ($userRole === 'walikelas') ? 'Daftar Hadir' : 'Absensi & Kehadiran' ?>
                                </span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300 submenu-chevron"></i>
                        </div>
                        
                        <!-- Submenu -->
                        <div class="submenu pl-4 mt-2 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                            <a href="<?= $baseUrl ?>/absensi/input" title="Input Harian" class="group flex items-center space-x-3 py-2 px-4 rounded-lg <?= strpos(uri_string(), 'absensi/input') !== false ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' ?> transition-all duration-200 transform hover:translate-x-1">
                                <div class="w-6 h-6 bg-white/15 rounded-md flex items-center justify-center">
                                    <i class="fas fa-plus text-xs"></i>
                                </div>
                                <div class="menu-text">
                                    <span class="text-sm font-medium">Input Harian</span>
                                </div>
                            </a>
                            
                            <a href="/admin/absensi/rekap" title="Rekap Absensi" class="group flex items-center space-x-3 py-2 px-4 rounded-lg <?= strpos(uri_string(), 'admin/absensi/rekap') !== false ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' ?> transition-all duration-200 transform hover:translate-x-1">
                                <div class="w-6 h-6 bg-white/15 rounded-md flex items-center justify-center">
                                    <i class="fas fa-chart-bar text-xs"></i>
                                </div>
                                <div class="menu-text">
                                    <span class="text-sm font-medium">Rekap Absensi</span>
                                </div>
                            </a>
                        </div>
                    </li>

                    <!-- Nilai Siswa / Data Nilai Murid -->
                    <?php $userRole = session()->get('role'); ?>
                    <?php if ($userRole !== 'walikelas' && $userRole !== 'siswa'): ?>
                    <li class="menu-item-with-submenu">
                        <div class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= strpos(uri_string(), 'nilai') !== false ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 cursor-pointer submenu-toggle">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-star text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Data Nilai Murid</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300 submenu-chevron"></i>
                        </div>
                        
                        <!-- Submenu -->
                        <div class="submenu pl-4 mt-2 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                            <a href="<?= $baseUrl ?>/nilai/input" title="Penilaian Harian" class="group flex items-center space-x-3 py-2 px-4 rounded-lg <?= strpos(uri_string(), 'nilai/input') !== false ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' ?> transition-all duration-200 transform hover:translate-x-1">
                                <div class="w-6 h-6 bg-white/15 rounded-md flex items-center justify-center">
                                    <i class="fas fa-clipboard-check text-xs"></i>
                                </div>
                                <div class="menu-text">
                                    <span class="text-sm font-medium">Penilaian Harian</span>
                                </div>
                            </a>
                            <a href="<?= $baseUrl ?>/nilai/pts" title="PTS" class="group flex items-center space-x-3 py-2 px-4 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 transform hover:translate-x-1">
                                <div class="w-6 h-6 bg-white/15 rounded-md flex items-center justify-center">
                                    <i class="fas fa-file-alt text-xs"></i>
                                </div>
                                <div class="menu-text">
                                    <span class="text-sm font-medium">PTS</span>
                                </div>
                            </a>
                            <a href="<?= $baseUrl ?>/nilai/pas" title="PAS" class="group flex items-center space-x-3 py-2 px-4 rounded-lg text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200 transform hover:translate-x-1">
                                <div class="w-6 h-6 bg-white/15 rounded-md flex items-center justify-center">
                                    <i class="fas fa-file-signature text-xs"></i>
                                </div>
                                <div class="menu-text">
                                    <span class="text-sm font-medium">PAS</span>
                                </div>
                            </a>
                            <a href="<?= $baseUrl ?>/nilai/cetak" title="Cetak" class="group flex items-center space-x-3 py-2 px-4 rounded-lg <?= strpos(uri_string(), 'nilai/cetak') !== false ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' ?> transition-all duration-200 transform hover:translate-x-1">
                                <div class="w-6 h-6 bg-white/15 rounded-md flex items-center justify-center">
                                    <i class="fas fa-print text-xs"></i>
                                </div>
                                <div class="menu-text">
                                    <span class="text-sm font-medium">Cetak</span>
                                </div>
                            </a>
                        </div>
                    </li>
                    <?php endif; ?>

                    <!-- Buku Kasus -->
                    <?php if ($userRole !== 'walikelas' && $userRole !== 'siswa'): ?>
                    <li>
                        <a href="/buku-kasus" title="Buku Kasus" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= strpos(uri_string(), 'buku-kasus') !== false ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-book text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Buku Kasus</span>
                                <span class="text-xs text-white/70">Catatan Masalah Siswa</span>
                            </div>
                            <?php if(strpos(uri_string(), 'buku-kasus') !== false): ?>
                                <div class="w-1 h-8 bg-white rounded-full"></div>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; // end if not siswa ?>

                    <!-- Menu Kebiasaan dihapus sesuai permintaan user -->
                    <!-- Rekap Bulanan 7 Kebiasaan (Admin & Walikelas) -->
                    <?php if ($userRole !== 'siswa'): ?>
                    <li>
                        <a href="<?= $baseUrl ?>/habits/monthly" title="Rekap 7 Kebiasaan" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= (strpos(uri_string(), 'habits/monthly') !== false) ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-calendar-check text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Rekap 7 Kebiasaan</span>
                                <span class="text-xs text-white/70">Bulanan</span>
                            </div>
                            <?php if(strpos(uri_string(), 'habits/monthly') !== false): ?>
                                <div class="w-1 h-8 bg-white rounded-full"></div>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Menu khusus Admin -->
            <?php 
            $userRole = session()->get('role');
            if (!isset($userRole) || ($userRole !== 'walikelas' && $userRole !== 'siswa')): 
            ?>
            <!-- Data Guru -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4 py-2 px-2">
                    <h3 class="menu-label text-white/70 text-xs font-bold uppercase tracking-wider">Data</h3>
                    <div class="w-8 h-px bg-white/20"></div>
                </div>
                
                <ul class="space-y-2">
                    <li>
                        <a href="/admin/guru" title="Data Guru" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= uri_string() === 'admin/guru' ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-chalkboard-teacher text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Data Guru</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Academic Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4 py-2 px-2">
                    <h3 class="menu-label text-white/70 text-xs font-bold uppercase tracking-wider">Akademik</h3>
                    <div class="w-8 h-px bg-white/20"></div>
                </div>
                
                <ul class="space-y-2">
                    <!-- Kalender Akademik -->
                    <li>
                        <a href="/admin/kalender-akademik" title="Kalender Akademik" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= uri_string() === 'admin/kalender-akademik' ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-calendar-alt text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Kalender Akademik</span>
                            </div>
                        </a>
                    </li>

                    <!-- Naik Kelas -->
                    <li>
                        <a href="/admin/naik-kelas" title="Naik Kelas" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= uri_string() === 'admin/naik-kelas' ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-level-up-alt text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Naik Kelas</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- School Management -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4 py-2 px-2">
                    <h3 class="menu-label text-white/70 text-xs font-bold uppercase tracking-wider">Sekolah</h3>
                    <div class="w-8 h-px bg-white/20"></div>
                </div>
                
                <ul class="space-y-2">
                    <!-- Profil Sekolah -->
                    <li>
                        <a href="/admin/profil-sekolah" title="Profil Sekolah" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= uri_string() === 'admin/profil-sekolah' ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-school text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Profil Sekolah</span>
                            </div>
                        </a>
                    </li>

                    <!-- Kelola User -->
                    <li>
                        <a href="/admin/users" title="Kelola User" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= uri_string() === 'admin/users' ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-users-cog text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Kelola User</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>

            <!-- System Settings -->
            <div class="mt-auto pt-6 border-t border-white/20">
                <div class="flex items-center justify-between mb-4 py-2 px-2">
                    <h3 class="menu-label text-white/70 text-xs font-bold uppercase tracking-wider">Sistem</h3>
                    <div class="w-8 h-px bg-white/20"></div>
                </div>
                
                <ul class="space-y-2">
                    <!-- Profile -->
                    <li>
                        <?php 
                        // Build profile URL per role
                        $profileUrl = '/admin/profile';
                        if ($userRole === 'walikelas') {
                            $profileUrl = '/profile';
                        } elseif ($userRole === 'siswa') {
                            $profileUrl = '/siswa/profile';
                        }
                        ?>
                        <a href="<?= $profileUrl ?>" title="Profile" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= strpos(uri_string(), 'profile') !== false ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-user-circle text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">
                                    <?php 
                                    $userRole = session()->get('role');
                                    echo ($userRole === 'walikelas') ? 'Profil Guru' : 'Profile';
                                    ?>
                                </span>
                            </div>
                        </a>
                    </li>

                    <!-- Settings - Only for Admin -->
                    <?php 
                    $userRole = session()->get('role');
                    if (isset($userRole) && $userRole === 'admin'): 
                    ?>
                    <li>
                        <a href="/admin/settings" title="Settings" class="group flex items-center space-x-3 py-3 px-4 rounded-xl <?= uri_string() === 'admin/settings' ? 'bg-white/20 text-white shadow-xl border border-white/30' : 'text-white/85 hover:bg-white/15 hover:text-white hover:shadow-lg' ?> transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-all duration-300">
                                <i class="fas fa-cog text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Settings</span>
                            </div>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Logout -->
                    <li class="pt-2 border-t border-white/10">
                        <a href="/logout" title="Logout" class="group flex items-center space-x-3 py-3 px-4 rounded-xl text-red-200 hover:bg-red-500/20 hover:text-red-100 transition-all duration-300 transform hover:translate-x-1">
                            <div class="flex-shrink-0 w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center group-hover:bg-red-500/30 transition-all duration-300">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                            </div>
                            <div class="menu-text flex-1">
                                <span class="font-semibold text-sm block">Logout</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="content-wrapper" id="contentWrapper">
        <!-- Fixed Header -->
    <header class="fixed-header" id="fixedHeader">
            <div class="flex items-center justify-between h-full px-4">
                <!-- Left side - Menu Toggle -->
                <div class="flex items-center space-x-2">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobileMenuToggle" class="lg:hidden w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-md flex items-center justify-center text-white shadow-sm hover:shadow-md transition-all duration-200">
                        <i class="fas fa-bars text-xs"></i>
                    </button>
                </div>

                <!-- Right side - Notifications and User -->
                <div class="flex items-center space-x-2">
                    <!-- Notifications -->
                    <button class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-md flex items-center justify-center text-white shadow-sm hover:shadow-md transition-all duration-200 relative">
                        <i class="fas fa-bell text-xs"></i>
                        <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full text-xs flex items-center justify-center text-white text-xs">3</span>
                    </button>

                    <!-- Messages -->
                    <button class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-md flex items-center justify-center text-white shadow-sm hover:shadow-md transition-all duration-200 relative">
                        <i class="fas fa-envelope text-xs"></i>
                        <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full text-xs flex items-center justify-center text-white text-xs">5</span>
                    </button>

                    <!-- User Profile -->
                    <?php 
                        $session = session();
                        $displayName = $session->get('nama') ?: 'Pengguna';
                        $roleKey = $session->get('role') ?: '';
                        $roleMap = [
                            'admin' => 'Administrator',
                            'walikelas' => 'Wali Kelas',
                            'guru' => 'Guru',
                            'siswa' => 'Siswa'
                        ];
                        $displayRole = $roleMap[$roleKey] ?? 'Pengguna';
                        $initial = strtoupper(mb_substr(trim($displayName), 0, 1, 'UTF-8')) ?: 'U';
                    ?>
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow-sm text-xs">
                            <?= esc($initial) ?>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-gray-900"><?= esc($displayName) ?></p>
                            <p class="text-xs text-gray-500"><?= esc($displayRole) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="content-area">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('mainSidebar');
            const contentWrapper = document.getElementById('contentWrapper');
            const fixedHeader = document.getElementById('fixedHeader');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapse');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            let hoverExpanded = false;

            function updateToggleIcon() {
                if (!sidebarCollapseBtn) return;
                const icon = sidebarCollapseBtn.querySelector('i');
                if (!icon) return;
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.remove('fa-angles-left');
                    icon.classList.add('fa-angles-right');
                } else {
                    icon.classList.remove('fa-angles-right');
                    icon.classList.add('fa-angles-left');
                }
            }

        // Desktop sidebar toggle
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', function() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            contentWrapper.classList.toggle('sidebar-collapsed', isCollapsed);
            fixedHeader.classList.toggle('sidebar-collapsed', isCollapsed);
            updateToggleIcon();
                });
            }

            // Hover expand/collapse (only on desktop widths)
            sidebar.addEventListener('mouseenter', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('collapsed');
                    sidebar.classList.add('expanded');
                }
            });
            sidebar.addEventListener('mouseleave', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('expanded');
                    sidebar.classList.add('collapsed');
                }
            });

            // Mobile menu toggle
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                });
            }

            // Submenu toggle functionality
            const submenuToggles = document.querySelectorAll('.submenu-toggle');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const menuItem = this.closest('.menu-item-with-submenu');
                    const isOpen = menuItem.classList.contains('open');
                    
                    // Close all other submenus
                    document.querySelectorAll('.menu-item-with-submenu').forEach(item => {
                        item.classList.remove('open');
                    });
                    
                    // Toggle current submenu
                    if (!isOpen) {
                        menuItem.classList.add('open');
                    }
                });
            });

            // Auto-open submenu if current page is a submenu item
            const currentPath = window.location.pathname;
            if (currentPath.includes('/admin/absensi/')) {
                const absensiMenu = document.querySelector('.menu-item-with-submenu');
                if (absensiMenu) {
                    absensiMenu.classList.add('open');
                }
            }
            
            if (currentPath.includes('/admin/nilai/')) {
                const nilaiMenus = document.querySelectorAll('.menu-item-with-submenu');
                nilaiMenus.forEach(menu => {
                    const nilaiSubmenu = menu.querySelector('a[href*="/admin/nilai/"]');
                    if (nilaiSubmenu) {
                        menu.classList.add('open');
                    }
                });
            }

            // Auto-open Kebiasaan submenu for /guru/* and /siswa pages
            if (currentPath.startsWith('/guru/')) {
                document.querySelectorAll('.menu-item-with-submenu').forEach(menu => {
                    const kebiasaanLink = menu.querySelector('a[href^="/guru/"]');
                    if (kebiasaanLink) {
                        menu.classList.add('open');
                    }
                });
            }
            if (currentPath === '/siswa' || currentPath.startsWith('/siswa/')) {
                document.querySelectorAll('.menu-item-with-submenu').forEach(menu => {
                    const siswaLink = menu.querySelector('a[href^="/siswa"]');
                    if (siswaLink) {
                        menu.classList.add('open');
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 1023) {
                    if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                        sidebar.classList.remove('open');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1023) sidebar.classList.remove('open');
            });

            // Ensure correct alignment on initial load (desktop)
            // On load we keep content static; no need to adjust wrapper
            if (window.innerWidth > 1023) { updateToggleIcon(); }

            // Add smooth scrolling for sidebar when menu item is clicked
            const menuLinks = document.querySelectorAll('.sidebar nav a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Add a subtle click animation
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });
    </script>
</body>
</html>
