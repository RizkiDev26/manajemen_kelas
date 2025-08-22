<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Sistem Manajemen Kelas</title>
    
        <!-- Tailwind CSS (CDN) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <?php // Precompute section flags early so x-data can use them
            $isKaihSection = strpos(current_url(), 'habits') !== false; 
            $isKaihMonthly = strpos(current_url(), 'monthly-report') !== false; 
        ?>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Enhancements -->
    <style>
        :root { font-size: clamp(14px,0.85vw + 11px,17px); }
        body { font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height:100vh; background:radial-gradient(at 20% 20%,#eef2ff,transparent 60%), radial-gradient(at 90% 10%,#ffe8f5,transparent 55%), linear-gradient(135deg,#f5f7fa 0%,#d9e2ef 50%,#c3cfe2 100%); }
        [x-cloak]{display:none!important;}
        .sidebar-scroll::-webkit-scrollbar{width:6px;} .sidebar-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.35);border-radius:9999px;}
        .glass { backdrop-filter: blur(14px) saturate(1.2); }
    </style>
    </head>
<body x-data="{nav:false, openKaih: <?= $isKaihSection ? 'true':'false' ?>}">
    <!-- Mobile Overlay -->
    <div x-cloak x-show="nav" @click="nav=false" class="fixed inset-0 bg-slate-900/60 glass md:hidden z-40"></div>
    <!-- Sidebar -->
    <aside :class="nav ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed inset-y-0 left-0 w-72 md:w-64 bg-gradient-to-b from-indigo-700 via-violet-700 to-fuchsia-700 text-white shadow-2xl flex flex-col transition-transform duration-300 ease-out z-50 rounded-r-3xl md:rounded-none">
        <div class="px-6 pt-6 pb-4 flex items-center gap-3 border-b border-white/15">
            <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center shadow-inner">
                <i class="fas fa-graduation-cap text-xl"></i>
            </div>
            <div class="min-w-0">
                <h1 class="text-lg font-semibold tracking-wide leading-tight">Portal Siswa</h1>
                <?php $nm = session('student_name') ?? 'Siswa'; ?>
                <p class="text-[11px] leading-snug uppercase tracking-wider text-white/70 truncate max-w-[140px]"><?= esc($nm) ?></p>
            </div>
            <button @click="nav=false" class="md:hidden ml-auto text-white/70 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/40 rounded-lg p-1"><i class="fas fa-times text-sm"></i></button>
        </div>
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-4 py-5 space-y-2 text-[15px]">
            <a href="<?= base_url('siswa') ?>" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/40 <?= current_url()==base_url('siswa') ? 'bg-white/20 text-white shadow-inner' : 'text-white/80 hover:text-white hover:bg-white/10 active:scale-[0.97]' ?>">
                <span class="w-9 h-9 rounded-lg flex items-center justify-center bg-white/10 group-hover:bg-white/15 shadow-inner"><i class="fas fa-home text-base"></i></span>
                <span class="truncate">Dashboard</span>
            </a>
            <div class="border-t border-white/10 pt-4 mt-2"></div>
            <div>
                <button type="button" @click="openKaih=!openKaih" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/40 <?= $isKaihSection ? 'bg-white/20 text-white shadow-inner' : 'text-white/80 hover:text-white hover:bg-white/10' ?>">
                    <span class="w-9 h-9 rounded-lg flex items-center justify-center bg-white/10"><i class="fas fa-star text-base"></i></span>
                    <span class="flex-1 text-left">7 KAIH</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="openKaih ? 'rotate-180' : ''"></i>
                </button>
                <div x-cloak x-show="openKaih" x-transition.opacity.duration.150ms class="mt-2 pl-4 space-y-1 border-l border-white/10">
                    <a href="<?= base_url('siswa/habits') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors <?= ($isKaihSection && !$isKaihMonthly) ? 'bg-white/25 text-white shadow-inner' : 'text-white/70 hover:text-white hover:bg-white/10' ?>">
                        <i class="fas fa-edit w-4"></i><span class="truncate">Input</span>
                    </a>
                    <a href="<?= base_url('siswa/habits/monthly-report') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors <?= $isKaihMonthly ? 'bg-white/25 text-white shadow-inner' : 'text-white/70 hover:text-white hover:bg-white/10' ?>">
                        <i class="fas fa-calendar w-4"></i><span class="truncate">Rekap Bulanan</span>
                    </a>
                </div>
            </div>
            <a href="<?= base_url('siswa/profile') ?>" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/40 <?= strpos(current_url(),'profile')!==false ? 'bg-white/20 text-white shadow-inner' : 'text-white/80 hover:text-white hover:bg-white/10 active:scale-[0.97]' ?>">
                <span class="w-9 h-9 rounded-lg flex items-center justify-center bg-white/10 group-hover:bg-white/15"><i class="fas fa-user text-base"></i></span>
                <span class="truncate">Profil Saya</span>
            </a>
            <div class="border-t border-white/10 pt-4 mt-4"></div>
            <a href="<?= base_url('logout') ?>" class="group flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-200 text-white/80 hover:text-white hover:bg-rose-500/25 focus:outline-none focus:ring-2 focus:ring-white/40">
                <span class="w-9 h-9 rounded-lg flex items-center justify-center bg-white/10 group-hover:bg-rose-500/40"><i class="fas fa-sign-out-alt text-base"></i></span>
                <span class="truncate">Keluar</span>
            </a>
        </nav>
        <div class="px-6 pb-5 pt-3 text-[11px] text-white/50 tracking-wide">&copy; <?= date('Y') ?> SMK | Versi Siswa</div>
    </aside>

    <!-- Main Section -->
    <div class="min-h-screen flex flex-col md:pl-64 transition-[padding] duration-300">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 z-30 bg-white/80 glass backdrop-blur border-b border-slate-200/60 px-4 md:px-8 py-3 flex items-center gap-4 shadow-sm">
            <button @click="nav=!nav" class="md:hidden inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-fuchsia-600 text-white shadow hover:shadow-lg transition focus:outline-none focus:ring-4 focus:ring-indigo-400/40">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex-1 min-w-0">
                <h2 class="text-lg md:text-xl font-semibold tracking-tight text-slate-800 flex items-center gap-2">
                    <i class="fas fa-layer-group text-indigo-600"></i>
                    <span class="truncate"><?= $this->renderSection('title') ?></span>
                </h2>
            </div>
            <?php $displayName = session('student_name') ?? session('username') ?? 'Siswa'; $initial = strtoupper(substr($displayName,0,1)); ?>
            <div class="hidden sm:flex flex-col items-end leading-tight mr-2">
                <span class="text-[11px] uppercase tracking-wide text-slate-400">Siswa</span>
                <span class="text-sm font-medium text-slate-700 max-w-[180px] truncate" title="<?= esc($displayName) ?>"><?= esc($displayName) ?></span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-fuchsia-600 text-white flex items-center justify-center font-semibold shadow ring-1 ring-white/40">
                <?= esc($initial) ?>
            </div>
        </header>
        <!-- Page Content Area -->
        <main class="flex-1 w-full mx-auto max-w-[1600px] px-4 md:px-8 py-6 md:py-10 space-y-8">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Optional place for future interactive scripts.
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
