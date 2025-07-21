<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Modern User Management Interface - Full Width -->
<div class="w-full min-h-screen">
    <!-- Header Section with Gradient Background -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 p-8 mb-8 shadow-xl">
        <div class="absolute inset-0 bg-white opacity-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white rounded-full opacity-20 blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2 flex items-center gap-3">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        Kelola User
                    </h1>
                    <p class="text-white/80 text-lg">Manajemen pengguna sistem sekolah</p>
                </div>
                
                <button onclick="window.location.href='<?= base_url('admin/users/create') ?>'" 
                        class="group relative px-8 py-4 bg-white text-blue-600 font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah User Baru
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                </button>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Total Users</p>
                            <p class="text-3xl font-bold text-white"><?= count($users) ?></p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Active Users</p>
                            <p class="text-3xl font-bold text-white"><?= count(array_filter($users, fn($u) => $u['is_active'])) ?></p>
                        </div>
                        <div class="p-3 bg-green-400/20 rounded-xl">
                            <svg class="w-6 h-6 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Admin Users</p>
                            <p class="text-3xl font-bold text-white"><?= count(array_filter($users, fn($u) => $u['role'] === 'admin')) ?></p>
                        </div>
                        <div class="p-3 bg-yellow-400/20 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 transform transition-all duration-500 animate-slideDown">
            <div class="bg-green-50 border-l-4 border-green-500 rounded-xl p-4 shadow-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800 font-medium"><?= session()->getFlashdata('success') ?></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-6 transform transition-all duration-500 animate-slideDown">
            <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4 shadow-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800 font-medium"><?= session()->getFlashdata('error') ?></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari nama, username, atau email..." 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="flex gap-2">
                <select id="roleFilter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="walikelas">Wali Kelas</option>
                </select>
                
                <select id="statusFilter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
                
                <button onclick="resetFilters()" class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    <div id="usersContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php if (empty($users)): ?>
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum ada data user</h3>
                    <p class="text-gray-500">Klik tombol "Tambah User Baru" untuk menambahkan user pertama</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($users as $index => $user): ?>
                <div class="user-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group"
                     data-name="<?= strtolower(esc($user['nama'])) ?>"
                     data-username="<?= strtolower(esc($user['username'])) ?>"
                     data-email="<?= strtolower(esc($user['email'] ?? '')) ?>"
                     data-role="<?= $user['role'] ?>"
                     data-status="<?= $user['is_active'] ? 'active' : 'inactive' ?>">
                    
                    <!-- Card Header with Gradient -->
                    <div class="relative h-32 bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 p-6">
                        <div class="absolute inset-0 bg-black opacity-10"></div>
                        <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-white rounded-full opacity-10"></div>
                        
                        <!-- User Avatar -->
                        <div class="relative z-10 flex justify-center">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-2xl font-bold text-blue-600 shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                <?= strtoupper(substr($user['nama'], 0, 2)) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-6 pt-12">
                        <h3 class="text-xl font-bold text-gray-800 text-center mb-1"><?= esc($user['nama']) ?></h3>
                        <p class="text-gray-500 text-center text-sm mb-4">@<?= esc($user['username']) ?></p>
                        
                        <!-- User Info -->
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600 truncate"><?= $user['email'] ? esc($user['email']) : 'No email' ?></span>
                            </div>
                            
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $user['role'] === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                </span>
                            </div>
                            
                            <?php if (in_array($user['role'], ['walikelas', 'wali_kelas']) && !empty($user['kelas'])): ?>
                                <div class="flex items-center gap-3 text-sm">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="text-gray-600">Kelas <?= esc($user['kelas']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">Status</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" 
                                       id="status_<?= $user['id'] ?>"
                                       <?= $user['is_active'] ? 'checked' : '' ?>
                                       <?= $user['id'] == session('user_id') ? 'disabled' : '' ?>
                                       onchange="toggleUserStatus(<?= $user['id'] ?>)">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-blue-600"></div>
                                <span class="ml-3 text-sm font-medium <?= $user['is_active'] ? 'text-green-600' : 'text-gray-400' ?>">
                                    <?= $user['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </label>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" 
                               class="flex-1 py-2 px-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-center rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 font-medium text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <?php if ($user['id'] != session('user_id')): ?>
                                <button onclick="deleteUser(<?= $user['id'] ?>, '<?= esc($user['nama']) ?>')"
                                        class="flex-1 py-2 px-4 bg-gradient-to-r from-red-500 to-red-600 text-white text-center rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300 font-medium text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 text-center mb-6">
                Apakah Anda yakin ingin menghapus user <span class="font-semibold text-gray-800" id="deleteUserName"></span>?
            </p>
            
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-sm text-red-800 flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tindakan ini tidak dapat dibatalkan. Semua data user akan dihapus permanen.
                </p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 py-3 px-4 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors duration-300 font-medium">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300 font-medium">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-4"></div>

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.5s ease-out;
    }
    
    .animate-slideUp {
        animation: slideUp 0.5s ease-out;
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<script>
// Search and Filter Functionality
const searchInput = document.getElementById('searchInput');
const roleFilter = document.getElementById('roleFilter');
const statusFilter = document.getElementById('statusFilter');
const userCards = document.querySelectorAll('.user-card');

function filterUsers() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedRole = roleFilter.value;
    const selectedStatus = statusFilter.value;
    
    userCards.forEach(card => {
        const name = card.dataset.name;
        const username = card.dataset.username;
        const email = card.dataset.email;
        const role = card.dataset.role;
        const status = card.dataset.status;
        
        const matchesSearch = name.includes(searchTerm) || 
                            username.includes(searchTerm) || 
                            email.includes(searchTerm);
        const matchesRole = !selectedRole || role === selectedRole;
        const matchesStatus = !selectedStatus || status === selectedStatus;
        
        if (matchesSearch && matchesRole && matchesStatus) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 10);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
    
    // Update no results message
    const visibleCards = Array.from(userCards).filter(card => card.style.display !== 'none');
    const container = document.getElementById('usersContainer');
    const noResultsMsg = document.getElementById('noResultsMessage');
    
    if (visibleCards.length === 0 && userCards.length > 0) {
        if (!noResultsMsg) {
            const message = document.createElement('div');
            message.id = 'noResultsMessage';
            message.className = 'col-span-full animate-fadeIn';
            message.innerHTML = `
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak ada hasil yang ditemukan</h3>
                    <p class="text-gray-500">Coba ubah kata kunci pencarian atau filter</p>
                </div>
            `;
            container.appendChild(message);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
}

// Event listeners
searchInput.addEventListener('input', filterUsers);
roleFilter.addEventListener('change', filterUsers);
statusFilter.addEventListener('change', filterUsers);

// Reset filters
function resetFilters() {
    searchInput.value = '';
    roleFilter.value = '';
    statusFilter.value = '';
    filterUsers();
}

// Toggle user status
function toggleUserStatus(userId) {
    const checkbox = document.getElementById(`status_${userId}`);
    const card = checkbox.closest('.user-card');
    const statusText = card.querySelector('label[for="status_' + userId + '"] span:last-child');
    
    fetch(`<?= base_url('admin/users/toggle-status/') ?>${userId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status text and color
            if (data.newStatus === 1) {
                statusText.textContent = 'Aktif';
                statusText.className = 'ml-3 text-sm font-medium text-green-600';
                card.dataset.status = 'active';
            } else {
                statusText.textContent = 'Nonaktif';
                statusText.className = 'ml-3 text-sm font-medium text-gray-400';
                card.dataset.status = 'inactive';
            }
            
            // Show success toast
            showToast(data.message, 'success');
            
            // Update stats
            updateStats();
        } else {
            // Revert checkbox state
            checkbox.checked = !checkbox.checked;
            showToast(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert checkbox state
        checkbox.checked = !checkbox.checked;
        showToast('Terjadi kesalahan saat mengubah status', 'error');
    });
}

// Delete user
function deleteUser(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `<?= base_url('admin/users/delete/') ?>${userId}`;
    
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Close delete modal
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

// Toast notification
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const id = 'toast-' + Date.now();
    
    toast.id = id;
    toast.className = `transform transition-all duration-500 ease-out translate-x-full`;
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 
                 type === 'error' ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 
                 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    
    toast.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl p-4 flex items-center space-x-3 min-w-[300px]">
            <div class="${bgColor} text-white rounded-full p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}"></path>
                </svg>
            </div>
            <p class="text-gray-800 font-medium flex-1">${message}</p>
            <button onclick="removeToast('${id}')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        removeToast(id);
    }, 5000);
}

function removeToast(id) {
    const toast = document.getElementById(id);
    if (toast) {
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
        
        setTimeout(() => {
            toast.remove();
        }, 500);
    }
}

// Update statistics
function updateStats() {
    const activeUsers = document.querySelectorAll('.user-card[data-status="active"]').length;
    const totalUsers = userCards.length;
    const adminUsers = document.querySelectorAll('.user-card[data-role="admin"]').length;
    
    // Update stat cards if they exist
    const statCards = document.querySelectorAll('.stat-value');
    if (statCards[0]) statCards[0].textContent = totalUsers;
    if (statCards[1]) statCards[1].textContent = activeUsers;
    if (statCards[2]) statCards[2].textContent = adminUsers;
}

// Close modal on outside click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Add transition styles to user cards
    userCards.forEach(card => {
        card.style.transition = 'all 0.3s ease-out';
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        searchInput.focus();
    }
    
    // Escape to close modal
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<?= $this->endSection() ?>
