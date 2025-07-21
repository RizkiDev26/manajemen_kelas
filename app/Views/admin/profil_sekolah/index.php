<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
        <i class="fas fa-school text-blue-600 mr-3"></i>Profil Sekolah
    </h1>
    <p class="text-gray-600">Kelola informasi profil sekolah dengan mudah</p>
</div>

<!-- Success/Error Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg" id="successAlert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-green-700"><?= session()->getFlashdata('success') ?></p>
            </div>
            <div class="ml-auto pl-3">
                <button type="button" onclick="closeAlert('successAlert')" class="text-green-400 hover:text-green-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg" id="errorAlert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-red-700"><?= session()->getFlashdata('error') ?></p>
            </div>
            <div class="ml-auto pl-3">
                <button type="button" onclick="closeAlert('errorAlert')" class="text-red-400 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Main Form Card - Full Width -->
<div class="w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <!-- Card Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
        <h2 class="text-lg font-semibold text-white flex items-center">
            <i class="fas fa-edit mr-3"></i>
            Data Profil Sekolah
        </h2>
        <p class="text-blue-100 text-sm mt-1">Lengkapi informasi sekolah di bawah ini</p>
    </div>

    <!-- Card Body -->
    <div class="p-8">
        <form id="profilSekolahForm" method="POST" action="<?= base_url('admin/profil-sekolah/save') ?>" novalidate>
            <?= csrf_field() ?>
            
            <!-- Form Grid - Responsive Full Width -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                
                <!-- Nama Sekolah -->
                <div class="xl:col-span-3">
                    <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building text-blue-500 mr-2"></i>
                        Nama Sekolah <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama_sekolah" 
                        name="nama_sekolah" 
                        value="<?= $profilSekolah['nama_sekolah'] ?? '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900"
                        placeholder="Masukkan nama sekolah lengkap"
                        required>
                    <div class="error-message text-red-500 text-sm mt-1 hidden" id="nama_sekolah_error"></div>
                    <?php if (isset(session('errors')['nama_sekolah'])): ?>
                        <div class="text-red-500 text-sm mt-1"><?= session('errors')['nama_sekolah'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- NPSN -->
                <div>
                    <label for="npsn" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-card text-blue-500 mr-2"></i>
                        NPSN
                    </label>
                    <input 
                        type="text" 
                        id="npsn" 
                        name="npsn" 
                        value="<?= $profilSekolah['npsn'] ?? '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900"
                        placeholder="Nomor Pokok Sekolah Nasional"
                        pattern="[0-9]{8,10}"
                        title="NPSN harus berupa 8-10 digit angka">
                    <div class="error-message text-red-500 text-sm mt-1 hidden" id="npsn_error"></div>
                    <?php if (isset(session('errors')['npsn'])): ?>
                        <div class="text-red-500 text-sm mt-1"><?= session('errors')['npsn'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Tahun Pelajaran -->
                <div>
                    <label for="tahun_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Tahun Pelajaran
                    </label>
                    <input 
                        type="text" 
                        id="tahun_pelajaran" 
                        name="tahun_pelajaran" 
                        value="<?= $profilSekolah['tahun_pelajaran'] ?? '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900"
                        placeholder="2024/2025"
                        pattern="[0-9]{4}/[0-9]{4}"
                        title="Format: YYYY/YYYY (contoh: 2024/2025)">
                    <div class="error-message text-red-500 text-sm mt-1 hidden" id="tahun_pelajaran_error"></div>
                    <?php if (isset(session('errors')['tahun_pelajaran'])): ?>
                        <div class="text-red-500 text-sm mt-1"><?= session('errors')['tahun_pelajaran'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Alamat Sekolah -->
                <div class="xl:col-span-3">
                    <label for="alamat_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                        Alamat Sekolah
                    </label>
                    <textarea 
                        id="alamat_sekolah" 
                        name="alamat_sekolah" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900 resize-none"
                        placeholder="Masukkan alamat lengkap sekolah"><?= $profilSekolah['alamat_sekolah'] ?? '' ?></textarea>
                    <div class="error-message text-red-500 text-sm mt-1 hidden" id="alamat_sekolah_error"></div>
                    <?php if (isset(session('errors')['alamat_sekolah'])): ?>
                        <div class="text-red-500 text-sm mt-1"><?= session('errors')['alamat_sekolah'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Kurikulum -->
                <div>
                    <label for="kurikulum" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book text-blue-500 mr-2"></i>
                        Kurikulum
                    </label>
                    <select 
                        id="kurikulum" 
                        name="kurikulum"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900 bg-white">
                        <option value="">Pilih Kurikulum</option>
                        <option value="Kurikulum Merdeka" <?= ($profilSekolah['kurikulum'] ?? '') === 'Kurikulum Merdeka' ? 'selected' : '' ?>>Kurikulum Merdeka</option>
                        <option value="Kurikulum 2013" <?= ($profilSekolah['kurikulum'] ?? '') === 'Kurikulum 2013' ? 'selected' : '' ?>>Kurikulum 2013</option>
                        <option value="KTSP" <?= ($profilSekolah['kurikulum'] ?? '') === 'KTSP' ? 'selected' : '' ?>>KTSP</option>
                    </select>
                    <div class="error-message text-red-500 text-sm mt-1 hidden" id="kurikulum_error"></div>
                    <?php if (isset(session('errors')['kurikulum'])): ?>
                        <div class="text-red-500 text-sm mt-1"><?= session('errors')['kurikulum'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Nama Kepala Sekolah -->
                <div>
                    <label for="nama_kepala_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tie text-blue-500 mr-2"></i>
                        Nama Kepala Sekolah
                    </label>
                    <input 
                        type="text" 
                        id="nama_kepala_sekolah" 
                        name="nama_kepala_sekolah" 
                        value="<?= $profilSekolah['nama_kepala_sekolah'] ?? '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900"
                        placeholder="Nama kepala sekolah">
                    <div class="error-message text-red-500 text-sm mt-1 hidden" id="nama_kepala_sekolah_error"></div>
                    <?php if (isset(session('errors')['nama_kepala_sekolah'])): ?>
                        <div class="text-red-500 text-sm mt-1"><?= session('errors')['nama_kepala_sekolah'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- NIP Kepala Sekolah -->
                <div class="xl:col-span-3">
                    <label for="nip_kepala_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-badge text-blue-500 mr-2"></i>
                        NIP Kepala Sekolah
                    </label>
                    <input 
                        type="text" 
                        id="nip_kepala_sekolah" 
                        name="nip_kepala_sekolah" 
                        value="<?= $profilSekolah['nip_kepala_sekolah'] ?? '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-900"
                        placeholder="Nomor Induk Pegawai kepala sekolah"
                        pattern="[0-9]{18}"
                        title="NIP harus berupa 18 digit angka">
                    <div class="error-message text-red-500 text-sm mt-1 hidden" id="nip_kepala_sekolah_error"></div>
                    <?php if (isset(session('errors')['nip_kepala_sekolah'])): ?>
                        <div class="text-red-500 text-sm mt-1"><?= session('errors')['nip_kepala_sekolah'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <!-- Info Text -->
                    <div class="flex items-center text-gray-500 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Pastikan semua data sudah benar sebelum menyimpan</span>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button 
                            type="button" 
                            onclick="resetForm()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                            <i class="fas fa-undo mr-2"></i>
                            Reset Form
                        </button>
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-save mr-2"></i>
                            <span id="submitText">Simpan Profil</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Custom Styles -->
<style>
/* Additional custom styles for better appearance */
.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input.error {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-input.success {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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

/* Card animation */
.card-animation {
    animation: slideUp 0.5s ease-out;
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

/* Mobile optimizations */
@media (max-width: 640px) {
    .grid {
        gap: 1rem;
    }
    
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-3 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
}
</style>

<!-- JavaScript for Form Validation and Interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profilSekolahForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    // Add card animation class
    document.querySelector('.bg-white.rounded-xl').classList.add('card-animation');
    
    // Form validation rules
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
            message: 'Format tahun pelajaran harus YYYY/YYYY (contoh: 2024/2025)'
        },
        nip_kepala_sekolah: {
            pattern: /^[0-9]{18}$/,
            message: 'NIP harus berupa 18 digit angka'
        }
    };
    
    // Validate individual field
    function validateField(fieldName, value) {
        const rules = validationRules[fieldName];
        if (!rules) return { isValid: true };
        
        // Required validation
        if (rules.required && (!value || value.trim() === '')) {
            return { isValid: false, message: rules.message };
        }
        
        // Min length validation
        if (rules.minLength && value.length < rules.minLength) {
            return { isValid: false, message: rules.message };
        }
        
        // Pattern validation
        if (rules.pattern && value && !rules.pattern.test(value)) {
            return { isValid: false, message: rules.message };
        }
        
        return { isValid: true };
    }
    
    // Show/hide error message
    function showError(fieldName, message) {
        const field = document.getElementById(fieldName);
        const errorElement = document.getElementById(fieldName + '_error');
        
        if (field && errorElement) {
            field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            field.classList.remove('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }
    
    function hideError(fieldName) {
        const field = document.getElementById(fieldName);
        const errorElement = document.getElementById(fieldName + '_error');
        
        if (field && errorElement) {
            field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
            errorElement.classList.add('hidden');
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
                if (this.classList.contains('border-red-500')) {
                    const validation = validateField(fieldName, this.value);
                    if (validation.isValid) {
                        hideError(fieldName);
                    }
                }
            });
        }
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isFormValid = true;
        const formData = new FormData(form);
        
        // Validate all fields
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
            // Show loading state
            submitBtn.disabled = true;
            submitText.innerHTML = '<span class="spinner mr-2"></span>Menyimpan...';
            
            // Submit form
            form.submit();
        } else {
            // Scroll to first error
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
    
    // Auto-hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('[id$="Alert"]');
        alerts.forEach(alert => {
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
});

// Reset form function
function resetForm() {
    const form = document.getElementById('profilSekolahForm');
    form.reset();
    
    // Hide all error messages
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Reset field styles
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
    });
}

// Close alert function
function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.transition = 'opacity 0.3s ease-out';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    }
}
</script>

<?= $this->endSection() ?>
