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
        
        /* Fixed Layout Styles - Enhanced for Better Separation */
        .main-container {
            display: flex;
            min-height: 100vh;
            background-color: #f8fafc;
            position: relative;
            overflow: hidden; /* Prevent body scroll */
        }
        
        .sidebar {
            width: 288px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            /* Fixed sidebar - doesn't scroll with content */
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 30;
            overflow-y: auto;
            /* Ensure sidebar content is scrollable */
            display: flex;
            flex-direction: column;
        }
        
        /* Collapsed sidebar styles */
        .sidebar.collapsed {
            width: 80px;
        }
        
        /* Prevent any layout shifts during collapse */
        .sidebar.collapsed * {
            transition: all 0.3s ease;
        }
        
        /* Maintain navigation structure when collapsed */
        .sidebar.collapsed nav ul {
            margin: 0;
            padding: 0;
        }
        
        .sidebar.collapsed nav ul .space-y-2 {
            gap: 0.5rem;
        }
        
        /* Hide only text elements when collapsed */
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            transform: translateX(-20px);
            pointer-events: none;
            width: 0;
            overflow: hidden;
            /* Prevent layout shift */
            position: absolute;
            visibility: hidden;
        }
        
        .sidebar.collapsed .menu-text {
            opacity: 0;
            transform: translateX(-20px);
            pointer-events: none;
            width: 0;
            overflow: hidden;
            /* Prevent layout shift */
            position: absolute;
            visibility: hidden;
        }
        
        .sidebar.collapsed .menu-label {
            opacity: 0;
            transform: translateX(-20px);
            pointer-events: none;
            width: 0;
            overflow: hidden;
            /* Prevent layout shift */
            position: absolute;
            visibility: hidden;
        }
        
        /* Logo section adjustments when collapsed */
        .sidebar.collapsed .p-6 {
            padding: 1.5rem 1rem;
            /* Maintain consistent height */
            min-height: 88px;
            display: flex;
            align-items: center;
        }
        
        .sidebar.collapsed .flex.items-center.justify-between {
            justify-content: center;
            width: 100%;
        }
        
        .sidebar.collapsed .flex.items-center.space-x-3 {
            space-x: 0;
        }
        
        /* Ensure icons remain visible when collapsed */
        .sidebar.collapsed nav ul li a {
            justify-content: center;
            padding: 0.75rem;
            /* Maintain consistent height and positioning */
            min-height: 48px;
            display: flex;
            align-items: center;
        }
        
        .sidebar.collapsed nav ul li a svg {
            margin: 0;
            opacity: 1;
            display: block;
            /* Prevent icon movement */
            position: static;
            transform: none;
        }
        
        /* Hide active state indicators when collapsed */
        .sidebar.collapsed nav ul li a.bg-white\/10 {
            background: transparent !important;
            border-left: none !important;
        }
        
        .sidebar.collapsed nav ul li div.bg-white\/10 {
            background: transparent !important;
            border-left: none !important;
        }
        
        /* Reset all active state styling when collapsed */
        .sidebar.collapsed nav ul li a[class*="bg-white"],
        .sidebar.collapsed nav ul li div[class*="bg-white"] {
            background: transparent !important;
            border-left: none !important;
            border: none !important;
        }
        
        /* Specifically target active menu classes with border */
        .sidebar.collapsed nav ul li a[class*="border-l-4"],
        .sidebar.collapsed nav ul li div[class*="border-l-4"] {
            border-left: none !important;
            background: transparent !important;
        }
        
        /* Target text-white class to reset color */
        .sidebar.collapsed nav ul li a.text-white,
        .sidebar.collapsed nav ul li div.text-white {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        /* Hide submenu completely when collapsed */
        .sidebar.collapsed nav .ml-8 {
            display: none;
        }
        
        /* Adjust link spacing for collapsed state */
        .sidebar.collapsed nav ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            position: relative;
            /* Prevent layout shifts */
            min-height: 48px;
            margin: 0;
            box-sizing: border-box;
        }
        
        .sidebar.collapsed nav ul li div {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            position: relative;
            /* Prevent layout shifts */
            min-height: 48px;
            margin: 0;
            box-sizing: border-box;
        }
        
        /* Ensure consistent spacing for list items */
        .sidebar.collapsed nav ul li {
            margin: 0;
            padding: 0;
        }
        
        /* Maintain consistent navigation padding */
        .sidebar.collapsed nav {
            padding: 0 1rem;
        }
        
        /* Fix specific menu spacing issues */
        .sidebar.collapsed nav .mb-8 {
            margin-bottom: 1rem;
        }
        
        .sidebar.collapsed nav ul.space-y-2 > li {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }
        
        .sidebar.collapsed nav ul.space-y-2 > li:first-child {
            margin-top: 0;
        }
        
        /* Ensure divider doesn't cause layout shifts */
        .sidebar.collapsed .pt-4 {
            padding-top: 0;
        }
        
        /* Hide divider sections when collapsed */
        .sidebar.collapsed .border-t.border-white\/20 {
            display: none;
        }
        
        /* Sidebar toggle button */
        .sidebar-toggle-btn {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            font-size: 14px;
            backdrop-filter: blur(10px);
            /* Prevent affecting layout */
            flex-shrink: 0;
            position: relative;
        }
        
        .sidebar-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar-toggle-btn i {
            transition: transform 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-toggle-btn i {
            transform: rotate(180deg);
        }
        
        /* Sidebar show button in header */
        #sidebarShow {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateX(-10px); 
            }
            to { 
                opacity: 1; 
                transform: translateX(0); 
            }
        }
        
        /* Text transitions */
        .sidebar-text, .menu-text, .menu-label {
            transition: all 0.3s ease;
        }
        
        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed [title]:hover::after {
            content: attr(title);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            pointer-events: none;
            animation: fadeIn 0.2s ease-out;
        }
        
        /* Enhanced hover effects for collapsed sidebar */
        .sidebar.collapsed nav ul li a:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            transform: scale(1.05);
            border-left: none !important;
            border-radius: 8px;
            /* Prevent movement during hover */
            margin: 0;
        }
        
        .sidebar.collapsed nav ul li div:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            transform: scale(1.05);
            border-left: none !important;
            border-radius: 8px;
            /* Prevent movement during hover */
            margin: 0;
        }
        
        .sidebar.collapsed nav ul li a:hover svg {
            color: #ffffff;
            transform: scale(1.1);
        }
        
        .sidebar.collapsed nav ul li div:hover svg {
            color: #ffffff;
            transform: scale(1.1);
        }
        
        /* Remove any text color inheritance when collapsed */
        .sidebar.collapsed nav ul li a,
        .sidebar.collapsed nav ul li div {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        /* Optional: Subtle indicator for active menu when collapsed (tiny dot) */
        .sidebar.collapsed nav ul li a[class*="bg-white"]::before,
        .sidebar.collapsed nav ul li div[class*="bg-white"]::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            z-index: 1;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-50%) translateX(-5px); }
            to { opacity: 1; transform: translateY(-50%) translateX(0); }
        }
        
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            /* Add left margin to compensate for fixed sidebar */
            margin-left: 288px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background-color: #f8fafc;
            /* Ensure content wrapper takes full height */
            height: 100vh;
        }
        
        .content-wrapper.sidebar-collapsed {
            margin-left: 80px;
        }
        
        .fixed-header {
            position: fixed;
            top: 0;
            left: 288px;
            right: 0;
            z-index: 50;
            width: calc(100% - 288px);
            height: 72px;
            transition: all 0.3s ease;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .fixed-header.sidebar-collapsed {
            left: 80px;
            width: calc(100% - 80px);
        }
        
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            margin-top: 72px;
            min-height: calc(100vh - 72px);
            background-color: #f8fafc;
            /* Ensure proper scrolling behavior */
            height: calc(100vh - 72px);
            /* Custom scrollbar for better appearance */
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        /* Custom scrollbar styles for webkit browsers */
        .content-area::-webkit-scrollbar {
            width: 6px;
        }

        .content-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .content-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .content-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Ensure sidebar content doesn't overflow */
        .sidebar nav {
            flex: 1;
            overflow-y: auto;
            padding: 0;
            /* Custom scrollbar for sidebar */
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        /* Custom scrollbar for sidebar webkit browsers */
        .sidebar nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sidebar nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 50;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                width: 100%;
                height: 100vh;
            }
            
            .fixed-header {
                left: 0;
                width: 100%;
            }
            
            .content-area {
                height: calc(100vh - 72px);
            }
        }
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                width: 100%;
            }
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
        
        /* Mobile Sidebar - Remove duplicate rules */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 50;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                height: 100vh;
            }
            
            .content-area {
                height: calc(100vh - 72px);
            }
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>
</head>
<body class="main-container bg-gray-50">
    <!-- Sidebar -->
    <aside class="sidebar gradient-bg shadow-xl flex flex-col relative">
        <!-- Logo Section -->
        <div class="p-6 border-b border-white/20 relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4v12a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2zM9 11h6" />
                        </svg>
                    </div>
                    <div class="sidebar-text">
                        <h1 class="text-white text-xl font-bold">SDN GU 09</h1>
                        <p class="text-white/70 text-sm">Aplikasi Pengelolaan Sekolah</p>
                    </div>
                </div>
                <!-- Sidebar Toggle Button -->
                <button id="sidebarCollapse" class="sidebar-toggle-btn hidden lg:flex" title="Tutup/Buka Sidebar">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>

        <!-- Menu Section -->
        <nav class="flex-1 overflow-y-auto px-6 py-4">
            <div class="mb-8">
                <p class="menu-label text-white/60 text-xs font-semibold uppercase tracking-wider mb-4">MENU</p>
                <ul class="space-y-2">
                    <li>
                        <a href="/admin/dashboard" title="Dashboard" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/dashboard' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                            </svg>
                            <span class="menu-text font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/data-siswa" title="Data Siswa" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/data-siswa' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span class="menu-text font-medium">Data Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/guru" title="Data Guru" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/guru') !== false ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="menu-text font-medium">Data Guru</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/profil-sekolah" title="Profil Sekolah" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/profil-sekolah' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="menu-text font-medium">Profil Sekolah</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users" title="Kelola User" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/users' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span class="menu-text font-medium">Kelola User</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/naik-kelas" title="Naik Kelas" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= uri_string() === 'admin/naik-kelas' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span class="menu-text font-medium">Naik Kelas</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/kalender-akademik" title="Kalender Akademik" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/kalender-akademik') !== false ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="menu-text font-medium">Kalender Akademik</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/nilai" title="Nilai Siswa" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/nilai') !== false ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80 hover:bg-white/5 hover:text-white' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="menu-text font-medium">Nilai Siswa</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/absensi') !== false ? 'bg-white/10 text-white border-l-4 border-white' : 'text-white/80' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span class="menu-text font-medium">Absensi Siswa</span>
                        </div>
                        <!-- Submenu -->
                        <ul class="ml-8 mt-2 space-y-1">
                            <li>
                                <a href="/admin/absensi/input" title="Input Harian" class="flex items-center space-x-2 py-2 px-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/absensi/input') !== false ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="menu-text text-sm">Input Harian</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/absensi/rekap" title="Rekap Absensi" class="flex items-center space-x-2 py-2 px-3 rounded-lg transition-all duration-200 <?= strpos(uri_string(), 'admin/absensi/rekap') !== false ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span class="menu-text text-sm">Rekap Absensi</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Divider -->
                    <li class="pt-4 border-t border-white/20">
                        <div class="mb-3">
                            <p class="menu-label text-white/40 text-xs font-semibold uppercase tracking-wider">ACCOUNT</p>
                        </div>
                    </li>
                    
                    <!-- Profile -->
                    <li>
                        <a href="/admin/profile" title="Profile" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 text-white/80 hover:bg-white/5 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="menu-text font-medium">Profile</span>
                        </a>
                    </li>
                    
                    <!-- Settings -->
                    <li>
                        <a href="/admin/settings" title="Settings" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 text-white/80 hover:bg-white/5 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="menu-text font-medium">Settings</span>
                        </a>
                    </li>
                    
                    <!-- Logout -->
                    <li>
                        <a href="/logout" title="Logout" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-all duration-200 text-red-300 hover:bg-red-500/10 hover:text-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="menu-text font-medium">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Top bar -->
        <header class="bg-white shadow-sm border-b border-gray-200 fixed-header">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Sidebar Toggle -->
                    <button id="sidebarToggle" class="text-gray-600 focus:outline-none lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Desktop Sidebar Show Button (shows when sidebar is collapsed) -->
                    <button id="sidebarShow" class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 p-2 rounded-lg transition-colors focus:outline-none hidden lg:flex" style="display: none;" title="Tampilkan Sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

        <!-- Content area -->
        <main class="content-area bg-gray-50">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        // Enhanced sidebar toggle for mobile with overlay
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('aside');
        
        // Create overlay element
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            });
        }

        // Close sidebar when clicking overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });

        // Close sidebar on window resize if it's large screen
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            }
        });

        // Sidebar collapse functionality for desktop
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        const sidebarShow = document.getElementById('sidebarShow');
        const contentWrapper = document.querySelector('.content-wrapper');
        const fixedHeader = document.querySelector('.fixed-header');
        
        function updateSidebarState(isCollapsed) {
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                contentWrapper.classList.add('sidebar-collapsed');
                if (fixedHeader) {
                    fixedHeader.classList.add('sidebar-collapsed');
                }
                // Show the sidebar show button
                sidebarShow.style.display = 'flex';
            } else {
                sidebar.classList.remove('collapsed');
                contentWrapper.classList.remove('sidebar-collapsed');
                if (fixedHeader) {
                    fixedHeader.classList.remove('sidebar-collapsed');
                }
                // Hide the sidebar show button
                sidebarShow.style.display = 'none';
            }
        }
        
        if (sidebarCollapse) {
            sidebarCollapse.addEventListener('click', () => {
                const isCollapsed = !sidebar.classList.contains('collapsed');
                updateSidebarState(isCollapsed);
                
                // Save state to localStorage
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            });
        }
        
        if (sidebarShow) {
            sidebarShow.addEventListener('click', () => {
                updateSidebarState(false);
                localStorage.setItem('sidebar-collapsed', false);
            });
        }

        // Restore sidebar state from localStorage
        const savedState = localStorage.getItem('sidebar-collapsed');
        if (savedState === 'true') {
            updateSidebarState(true);
        }
    </script>
</body>
</html>
