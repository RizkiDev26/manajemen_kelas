<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Full Width Layout Styles */
        .full-width-content {
            width: 100% !important;
            max-width: none !important;
        }
        
        /* Responsive Grid Utilities */
        .responsive-grid-1 { grid-template-columns: repeat(1, 1fr); }
        .responsive-grid-2 { grid-template-columns: repeat(2, 1fr); }
        .responsive-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .responsive-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .responsive-grid-5 { grid-template-columns: repeat(5, 1fr); }
        
        @media (min-width: 640px) {
            .sm\:responsive-grid-2 { grid-template-columns: repeat(2, 1fr); }
            .sm\:responsive-grid-3 { grid-template-columns: repeat(3, 1fr); }
        }
        
        @media (min-width: 768px) {
            .md\:responsive-grid-2 { grid-template-columns: repeat(2, 1fr); }
            .md\:responsive-grid-3 { grid-template-columns: repeat(3, 1fr); }
            .md\:responsive-grid-4 { grid-template-columns: repeat(4, 1fr); }
        }
        
        @media (min-width: 1024px) {
            .lg\:responsive-grid-3 { grid-template-columns: repeat(3, 1fr); }
            .lg\:responsive-grid-4 { grid-template-columns: repeat(4, 1fr); }
            .lg\:responsive-grid-5 { grid-template-columns: repeat(5, 1fr); }
        }
        
        @media (min-width: 1280px) {
            .xl\:responsive-grid-4 { grid-template-columns: repeat(4, 1fr); }
            .xl\:responsive-grid-5 { grid-template-columns: repeat(5, 1fr); }
            .xl\:responsive-grid-6 { grid-template-columns: repeat(6, 1fr); }
        }
        
        @media (min-width: 1536px) {
            .\32xl\:responsive-grid-5 { grid-template-columns: repeat(5, 1fr); }
            .\32xl\:responsive-grid-6 { grid-template-columns: repeat(6, 1fr); }
            .\32xl\:responsive-grid-7 { grid-template-columns: repeat(7, 1fr); }
        }
        
        /* Override any max-width constraints */
        .container, .mx-auto {
            max-width: none !important;
            width: 100% !important;
        }
        
        /* Card responsive spacing */
        .card-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: 1fr;
        }
        
        @media (min-width: 640px) {
            .card-grid { 
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }
        
        @media (min-width: 768px) {
            .card-grid { 
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }
        
        @media (min-width: 1024px) {
            .card-grid { 
                grid-template-columns: repeat(4, 1fr);
                gap: 1.5rem;
            }
        }
        
        @media (min-width: 1280px) {
            .card-grid { 
                grid-template-columns: repeat(5, 1fr);
                gap: 1.75rem;
            }
        }
        
        @media (min-width: 1536px) {
            .card-grid { 
                grid-template-columns: repeat(6, 1fr);
                gap: 2rem;
            }
        }
    </style>
</head>
<body class="flex h-screen bg-gray-50 overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 gradient-bg shadow-xl flex flex-col relative">
        <!-- Logo Section -->
        <div class="p-6 border-b border-white/20">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4v12a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2zM9 11h6" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-white text-xl font-bold">TailAdmin</h1>
                    <p class="text-white/70 text-sm">School Management</p>
                </div>
            </div>
        </div>

        <!-- Menu Section -->
        <nav class="flex-1 p-6 overflow-y-auto">
            <div class="mb-8">
                <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-4">MENU</p>
                <ul class="space-y-2">
                    <li>
                        <a href="/admin/dashboard" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/dashboard' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/data-siswa" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/data-siswa' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span class="font-medium">Data Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/profil-sekolah" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/profil-sekolah' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-medium">Profil Sekolah</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/users' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span class="font-medium">Kelola User</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/naik-kelas" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/naik-kelas' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span class="font-medium">Naik Kelas</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/kalender-akademik" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/kalender-akademik') !== false ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">Kalender Akademik</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/nilai" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/nilai') !== false ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Nilai Siswa</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/absensi') !== false ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span class="font-medium">Absensi Siswa</span>
                        </div>
                        <!-- Submenu -->
                        <ul class="ml-8 mt-2 space-y-1">
                            <li>
                                <a href="/admin/absensi/input" class="flex items-center space-x-2 py-2 px-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/absensi/input') !== false ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="text-sm">Input Harian</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/absensi/rekap" class="flex items-center space-x-2 py-2 px-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/absensi/rekap') !== false ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span class="text-sm">Rekap Absensi</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Support Section at Bottom -->
        <div class="p-6 border-t border-white/20">
            <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-4">SUPPORT</p>
            <ul class="space-y-2">
                <li>
                    <a href="/admin/profile" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 text-white/80 hover:bg-white/5 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/settings" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 text-white/80 hover:bg-white/5 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="/logout" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 text-red-300 hover:bg-red-500/10 hover:text-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top bar -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="text-gray-600 focus:outline-none lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="relative">
                        <input type="text" placeholder="Type to search..." class="w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Settings Icon -->
                    <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                    
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute -top-1 -right-1 inline-block w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>

                    <!-- Messages -->
                    <button class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 inline-block w-4 h-4 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center">5</span>
                    </button>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3 cursor-pointer group">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700"><?= isset($currentUser) ? esc($currentUser['nama']) : session('nama') ?></p>
                            <p class="text-xs text-gray-500 capitalize"><?= isset($currentUser) ? esc($currentUser['role']) : session('role') ?><?= isset($currentUser) && !empty($currentUser['kelas']) ? ' - ' . esc($currentUser['kelas']) : '' ?></p>
                        </div>
                        <img src="https://i.pravatar.cc/40?img=1" alt="User Avatar" class="w-10 h-10 rounded-full border-2 border-gray-200 group-hover:border-blue-300 transition-colors" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content area - Full Width -->
        <main class="flex-1 overflow-auto bg-gray-50">
            <div class="w-full h-full px-4 py-6 lg:px-6 xl:px-8">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('aside');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
</body>
</html>
