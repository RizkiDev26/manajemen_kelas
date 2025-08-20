<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        <i class="fas fa-school text-blue-600 mr-3"></i>Profil Sekolah
    </h1>
    <p class="text-gray-600">Kelola informasi profil sekolah dengan mudah dan terorganisir</p>
</div>

<!-- Success/Error Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-xl shadow-sm" id="successAlert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-green-800 font-medium"><?= session()->getFlashdata('success') ?></p>
            </div>
            <div class="ml-auto pl-3">
                <button type="button" onclick="closeAlert('successAlert')" class="text-green-500 hover:text-green-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-xl shadow-sm" id="errorAlert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-red-800 font-medium"><?= session()->getFlashdata('error') ?></p>
            </div>
            <div class="ml-auto pl-3">
                <button type="button" onclick="closeAlert('errorAlert')" class="text-red-500 hover:text-red-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Main Form Container -->
<form id="profilSekolahForm" method="POST" action="<?= base_url('admin/profil-sekolah/save') ?>" novalidate>
    <?= csrf_field() ?>
    
    <!-- Section 1: Informasi Dasar Sekolah -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-building mr-3"></i>
                Informasi Dasar Sekolah
            </h2>
            <p class="text-blue-100 text-sm mt-1">Data identitas utama sekolah</p>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Nama Sekolah -->
                <div class="lg:col-span-2">
                    <label for="nama_sekolah" class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-school text-blue-500 mr-2"></i>
                        Nama Sekolah <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="nama_sekolah" 
                            name="nama_sekolah" 
                            value="<?= $profilSekolah['nama_sekolah'] ?? '' ?>"
                            class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 placeholder-gray-400 hover:border-gray-300"
                            placeholder="Masukkan nama sekolah lengkap"
                            required>
                        <div class="absolute left-4 top-4 text-gray-400">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center" id="nama_sekolah_error">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>

                <!-- NPSN -->
                <div>
                    <label for="npsn" class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-id-card text-blue-500 mr-2"></i>
                        NPSN (Nomor Pokok Sekolah Nasional)
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="npsn" 
                            name="npsn" 
                            value="<?= $profilSekolah['npsn'] ?? '' ?>"
                            class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 placeholder-gray-400 hover:border-gray-300"
                            placeholder="8-10 digit angka"
                            pattern="[0-9]{8,10}">
                        <div class="absolute left-4 top-4 text-gray-400">
                            <i class="fas fa-hashtag"></i>
                        </div>
                    </div>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center" id="npsn_error">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>

                <!-- Tahun Pelajaran -->
                <div>
                    <label for="tahun_pelajaran" class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Tahun Pelajaran
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="tahun_pelajaran" 
                            name="tahun_pelajaran" 
                            value="<?= $profilSekolah['tahun_pelajaran'] ?? '' ?>"
                            class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 placeholder-gray-400 hover:border-gray-300"
                            placeholder="2024/2025"
                            pattern="[0-9]{4}/[0-9]{4}">
                        <div class="absolute left-4 top-4 text-gray-400">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center" id="tahun_pelajaran_error">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>

                <!-- Kurikulum -->
                <div class="lg:col-span-2">
                    <label for="kurikulum" class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-book text-blue-500 mr-2"></i>
                        Kurikulum yang Digunakan
                    </label>
                    <div class="relative">
                        <select 
                            id="kurikulum" 
                            name="kurikulum"
                            class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 bg-white appearance-none">
                            <option value="">Pilih Kurikulum</option>
                            <option value="Kurikulum Merdeka" <?= ($profilSekolah['kurikulum'] ?? '') === 'Kurikulum Merdeka' ? 'selected' : '' ?>>Kurikulum Merdeka</option>
                            <option value="Kurikulum 2013" <?= ($profilSekolah['kurikulum'] ?? '') === 'Kurikulum 2013' ? 'selected' : '' ?>>Kurikulum 2013</option>
                            <option value="KTSP" <?= ($profilSekolah['kurikulum'] ?? '') === 'KTSP' ? 'selected' : '' ?>>KTSP</option>
                        </select>
                        <div class="absolute left-4 top-4 text-gray-400">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="absolute right-4 top-4 text-gray-400 pointer-events-none">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Alamat dan Lokasi -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-map-marker-alt mr-3"></i>
                Alamat dan Lokasi
            </h2>
            <p class="text-green-100 text-sm mt-1">Informasi lokasi sekolah</p>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 gap-6">
                <!-- Alamat Lengkap Sekolah -->
                <div>
                    <label for="alamat_sekolah" class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-home text-green-500 mr-2"></i>
                        Alamat Lengkap Sekolah
                    </label>
                    <div class="relative">
                        <textarea 
                            id="alamat_sekolah" 
                            name="alamat_sekolah" 
                            rows="4"
                            class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-green-100 focus:border-green-500 transition-all duration-300 text-gray-900 placeholder-gray-400 hover:border-gray-300 resize-none"
                            placeholder="Masukkan alamat lengkap sekolah..."><?= $profilSekolah['alamat_sekolah'] ?? '' ?></textarea>
                        <div class="absolute left-4 top-4 text-gray-400">
                            <i class="fas fa-map-pin"></i>
                        </div>
                    </div>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center" id="alamat_sekolah_error">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Informasi Kepala Sekolah -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-user-tie mr-3"></i>
                Informasi Kepala Sekolah
            </h2>
            <p class="text-purple-100 text-sm mt-1">Data kepala sekolah</p>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Nama Kepala Sekolah -->
                <div>
                    <label for="nama_kepala_sekolah" class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-user text-purple-500 mr-2"></i>
                        Nama Kepala Sekolah
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="nama_kepala_sekolah" 
                            name="nama_kepala_sekolah" 
                            value="<?= $profilSekolah['nama_kepala_sekolah'] ?? '' ?>"
                            class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-purple-100 focus:border-purple-500 transition-all duration-300 text-gray-900 placeholder-gray-400 hover:border-gray-300"
                            placeholder="Nama kepala sekolah">
                        <div class="absolute left-4 top-4 text-gray-400">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>

                <!-- NIP Kepala Sekolah -->
                <div>
                    <label for="nip_kepala_sekolah" class="block text-sm font-semibold text-gray-800 mb-3">
                        <i class="fas fa-id-badge text-purple-500 mr-2"></i>
                        NIP Kepala Sekolah
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="nip_kepala_sekolah" 
                            name="nip_kepala_sekolah" 
                            value="<?= $profilSekolah['nip_kepala_sekolah'] ?? '' ?>"
                            class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-purple-100 focus:border-purple-500 transition-all duration-300 text-gray-900 placeholder-gray-400 hover:border-gray-300"
                            placeholder="18 digit angka"
                            pattern="[0-9]{18}">
                        <div class="absolute left-4 top-4 text-gray-400">
                            <i class="fas fa-hashtag"></i>
                        </div>
                    </div>
                    <div class="error-message text-red-500 text-sm mt-2 hidden flex items-center" id="nip_kepala_sekolah_error">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Tindakan -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-cog mr-3"></i>
                Tindakan
            </h2>
            <p class="text-gray-200 text-sm mt-1">Simpan atau reset formulir</p>
        </div>
        
        <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">
                <!-- Info Text -->
                <div class="flex items-center text-gray-600">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">Pastikan Data Benar</p>
                        <p class="text-sm text-gray-500">Periksa kembali semua informasi sebelum menyimpan</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button 
                        type="button" 
                        onclick="resetForm()"
                        class="group px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-3 focus:ring-gray-200 focus:ring-offset-2 transition-all duration-300 font-semibold flex items-center justify-center">
                        <i class="fas fa-undo mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                        Reset Form
                    </button>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="group px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-3 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-300 font-semibold disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                        <i class="fas fa-save mr-2 group-hover:scale-110 transition-transform duration-300" id="submitIcon"></i>
                        <span id="submitText">Simpan Profil Sekolah</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
/* Clean and modern styles */
.progress-indicator {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
    z-index: 50;
    transition: width 0.3s ease;
    width: 0%;
}

/* Form animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-animation {
    animation: slideInUp 0.6s ease-out both;
}

.card-1 { animation-delay: 0.1s; }
.card-2 { animation-delay: 0.2s; }
.card-3 { animation-delay: 0.3s; }
.card-4 { animation-delay: 0.4s; }

/* Input focus styles */
input:focus, textarea:focus, select:focus {
    outline: none !important;
    transform: none !important;
}

/* Error and success states */
.error {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    background-color: #fef2f2 !important;
}

.success {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
    background-color: #f0fdf4 !important;
}

/* Loading spinner */
.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Custom select styling */
select {
    background-image: none;
}

/* Remove conflicting animations */
* {
    transform: none !important;
}

input, textarea, select, button {
    transform: none !important;
}

button:hover {
    transform: translateY(-1px) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profilSekolahForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitIcon = document.getElementById('submitIcon');
    
    // Add card animations
    const cards = document.querySelectorAll('.bg-white.rounded-xl');
    cards.forEach((card, index) => {
        card.classList.add('card-animation', `card-${index + 1}`);
    });
    
    // Form validation
    const validationRules = {
        nama_sekolah: {
            required: true,
            minLength: 3,
            message: 'Nama sekolah harus diisi minimal 3 karakter'
        },
        npsn: {
            pattern: /^[0-9]{8,10}$/,
            message: 'NPSN harus berupa 8-10 digit angka'
        },
        tahun_pelajaran: {
            pattern: /^[0-9]{4}\/[0-9]{4}$/,
            message: 'Format tahun pelajaran harus YYYY/YYYY'
        },
        nip_kepala_sekolah: {
            pattern: /^[0-9]{18}$/,
            message: 'NIP harus berupa 18 digit angka'
        }
    };
    
    function validateField(fieldName, value) {
        const rules = validationRules[fieldName];
        if (!rules) return { isValid: true };
        
        if (rules.required && (!value || value.trim() === '')) {
            return { isValid: false, message: rules.message };
        }
        
        if (rules.minLength && value && value.trim().length < rules.minLength) {
            return { isValid: false, message: rules.message };
        }
        
        if (rules.pattern && value && !rules.pattern.test(value.trim())) {
            return { isValid: false, message: rules.message };
        }
        
        return { isValid: true };
    }
    
    function showError(fieldName, message) {
        const field = document.getElementById(fieldName);
        const errorElement = document.getElementById(fieldName + '_error');
        
        if (field && errorElement) {
            field.classList.add('error');
            field.classList.remove('success');
            
            const errorSpan = errorElement.querySelector('span');
            if (errorSpan) errorSpan.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }
    
    function hideError(fieldName) {
        const field = document.getElementById(fieldName);
        const errorElement = document.getElementById(fieldName + '_error');
        
        if (field && errorElement) {
            field.classList.remove('error');
            field.classList.add('success');
            errorElement.classList.add('hidden');
            
            setTimeout(() => field.classList.remove('success'), 2000);
        }
    }
    
    // Real-time validation
    Object.keys(validationRules).forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('blur', function() {
                const validation = validateField(fieldName, this.value);
                if (!validation.isValid) {
                    showError(fieldName, validation.message);
                } else {
                    hideError(fieldName);
                }
            });
            
            field.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    this.classList.remove('error');
                    const errorElement = document.getElementById(fieldName + '_error');
                    if (errorElement) errorElement.classList.add('hidden');
                }
            });
        }
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isFormValid = true;
        const formData = new FormData(form);
        
        Object.keys(validationRules).forEach(fieldName => {
            const value = formData.get(fieldName) || '';
            const validation = validateField(fieldName, value);
            
            if (!validation.isValid) {
                showError(fieldName, validation.message);
                isFormValid = false;
            } else {
                hideError(fieldName);
            }
        });
        
        if (isFormValid) {
            submitBtn.disabled = true;
            submitIcon.className = 'spinner mr-2';
            submitText.textContent = 'Menyimpan Data...';
            
            setTimeout(() => form.submit(), 500);
        } else {
            const firstError = document.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => firstError.focus(), 300);
            }
        }
    });
    
    // Auto-hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('[id$="Alert"]');
        alerts.forEach(alert => {
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(-20px)';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
});

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua data?')) {
        const form = document.getElementById('profilSekolahForm');
        
        form.reset();
        
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
        });
        
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.classList.remove('error', 'success');
        });
        
        const firstField = form.querySelector('input');
        if (firstField) firstField.focus();
    }
}

function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(-20px)';
        setTimeout(() => alert.remove(), 300);
    }
}
</script>

<?= $this->endSection() ?>
