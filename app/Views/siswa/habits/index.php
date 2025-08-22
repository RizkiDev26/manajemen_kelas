<?= $this->extend('layouts/siswa_layout') ?>

<?= $this->section('title') ?>7 Kebiasaan Anak Indonesia Hebat<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
/* Modern Design System - Improved */
body {
  background: #f1f5f9;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  min-height: 100vh;
  color: #1e293b;
}

/* Custom Circular Loader */
.custom-loader {
  width:50px;
  height:50px;
  border-radius:50%;
  border:8px solid;
  border-color:#E4E4ED;
  border-right-color:#766DF4;
  animation:s2 1s infinite linear;
}
@keyframes s2 {to{transform: rotate(1turn)}}

.habits-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 1rem;
}

@media (min-width: 768px) {
  .habits-container {
    padding: 1.5rem;
  }
}

@media (min-width: 1024px) {
  .habits-container {
    padding: 2rem;
  }
}

/* Modern Header Section */
.app-header {
  text-align: center;
  margin-bottom: 2rem;
  padding: 2rem 1rem;
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  border-radius: 20px;
  color: white;
  box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
}

.app-header h1 {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.app-header p {
  font-size: 1.1rem;
  opacity: 0.9;
  margin: 0;
}

/* Navigation Tabs */
.nav-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
  background: white;
  padding: 0.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.nav-tab {
  flex: 1;
  padding: 1rem;
  text-align: center;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
  background: transparent;
  border: none;
  color: #64748b;
}

.nav-tab.active {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.nav-tab:hover:not(.active) {
  background: #f1f5f9;
  color: #475569;
}

/* Grid Responsif Modern */
.habits-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

@media (min-width: 640px) {
  .habits-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
  }
}

@media (min-width: 1280px) {
  .habits-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1536px) {
  .habits-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* Modal styles - Simple and direct */
.modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.modal-overlay.show {
  display: flex !important;
}

.modal-content {
  background: white;
  border-radius: 15px;
  padding: 30px;
  max-width: 500px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* Blur background content when modal is open */
.content-blur {
  filter: blur(5px);
  transition: filter 0.3s ease;
}

.modal-close {
  position: absolute;
  top: 15px;
  right: 15px;
  background: #f3f4f6;
  border: none;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  background: #e5e7eb;
}

.habit-card {
  position: relative;
  border-radius: 16px;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  width: 100%;
  overflow: hidden;
  background: white;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  display: block;
  backdrop-filter: blur(10px);
}

@media (min-width: 768px) {
  .habit-card {
    padding: 2rem;
  }
}

.habit-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  border-color: #c7d2fe;
}

.habit-card:active {
  transform: translateY(-4px) scale(1.01);
}

.habit-card.completed {
  background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
  border-color: #22c55e;
  box-shadow: 0 8px 25px rgba(34, 197, 94, 0.2);
}

.habit-card.completed::before {
  content: "✓";
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 32px;
  height: 32px;
  background: #22c55e;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: bold;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
  z-index: 2;
}

.habit-card.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}

.habit-card.disabled:hover {
  transform: none !important;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Modern Icon Badge */
.habit-card::before {
  content: '';
  position: absolute;
  right: 12px;
  top: 12px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  opacity: 0.8;
}

/* Kategori Warna Modern - Solid Colors */
.card-wake-up {
  background: #ffffff;
  border-left: 4px solid #4299e1;
}
.card-wake-up::before {
  background: #4299e1;
}

.card-worship {
  background: #ffffff;
  border-left: 4px solid #ed8936;
}
.card-worship::before {
  background: #ed8936;
}

.card-exercise {
  background: #ffffff;
  border-left: 4px solid #48bb78;
}
.card-exercise::before {
  background: #48bb78;
}

.card-healthy-food {
  background: #ffffff;
  border-left: 4px solid #ed64a6;
}
.card-healthy-food::before {
  background: #ed64a6;
}

.card-learning {
  background: #ffffff;
  border-left: 4px solid #4fd1c7;
}
.card-learning::before {
  background: #4fd1c7;
}

.card-social {
  background: #ffffff;
  border-left: 4px solid #9f7aea;
}
.card-social::before {
  background: #9f7aea;
}

.card-sleep {
  background: #ffffff;
  border-left: 4px solid #718096;
}
.card-sleep::before {
  background: #718096;
}

/* Konten Kartu - Text Wrapping Layout */
.habit-content {
  display: block;
  position: relative;
  min-height: 50px; /* minimum space untuk text wrapping - diperkecil */
  padding: 0;
}

/* Icon Container dengan Float untuk Text Wrapping - Compact Size */
.habit-image {
  float: left;
  width: 3rem;
  height: 3rem;
  margin: 0 0.75rem 0.25rem 0;
  border-radius: 8px;
  background: transparent;
  box-shadow: none;
  border: none;
  transition: transform 0.2s ease;
  flex-shrink: 0;
}

.habit-card:hover .habit-image {
  transform: scale(1.1);
}

.habit-image img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

/* Text Content - Compact and Clean */
.habit-title {
  font-size: 0.9rem;
  font-weight: 600;
  letter-spacing: -0.025em;
  color: #2d3748;
  margin: 0 0 0.25rem 0;
  line-height: 1.2;
  /* Text akan wrap naturally di sekitar float */
}

@media (min-width: 768px) {
  .habit-title {
    font-size: 1rem;
  }
}

.habit-description {
  margin: 0;
  font-size: 0.7rem;
  line-height: 1.3;
  color: #718096;
  text-align: justify;
  /* Text akan wrap naturally di sekitar float */
}

/* Status Area dengan Clear Float - Modern and Compact */
.habit-status {
  clear: both; /* penting untuk membersihkan float */
  padding: 0.5rem;
  background: #f7fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin-top: 0.5rem;
  position: relative;
  z-index: 5;
}

/* Badge Modern - Colorful */
.tag {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  border-radius: 16px;
  padding: 0.25rem 0.75rem;
  font-size: 0.7rem;
  font-weight: 500;
  background: #e6fffa;
  color: #234e52;
  border: 1px solid #81e6d9;
}

/* Focus States untuk Aksesibilitas */
.habit-card:focus-visible {
  outline: 2px solid #4299e1;
  outline-offset: 2px;
}

/* Past Date Overlay */
.past-date-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 1rem;
  backdrop-filter: blur(2px);
}

.habit-card.disabled:hover {
  transform: none;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.habit-icon {
  font-size: 2rem;
  margin-bottom: 10px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
  display: none; /* Hide emoji icons when using images */
}

/* Button Styles */
.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  font-size: 0.8rem;
  letter-spacing: 0.025em;
}

.btn-primary {
  background: #4299e1;
  color: white;
}

.btn-primary:hover {
  background: #3182ce;
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(66, 153, 225, 0.3);
}

.btn-success {
  background: #48bb78;
  color: white;
}

.btn-success:hover {
  background: #38a169;
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(72, 187, 120, 0.3);
}

.btn-secondary {
  background: #718096;
  color: white;
}

.btn-secondary:hover {
  background: #4a5568;
  transform: translateY(-1px);
}

.input-field {
  width: 100%;
  padding: 15px;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  font-size: 16px;
  margin-bottom: 15px;
  transition: border-color 0.3s ease;
}

.input-field:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.tag {
  display: inline-block;
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  color: #1e40af;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  margin: 3px;
  font-weight: 500;
  border: 1px solid #93c5fd;
}

.progress-section {
  background: white;
  border-radius: 8px;
  padding: 0.75rem;
  margin-bottom: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  border: 1px solid #e2e8f0;
}

.progress-card {
  background: #ffffff;
  border-radius: 6px;
  padding: 0.5rem 0.75rem;
  text-align: center;
  border: 1px solid #e2e8f0;
  transition: all 0.2s ease;
}

.progress-card:hover {
  transform: translateY(-1px);
  box-shadow: 0 3px 8px rgba(0,0,0,0.06);
}

.progress-percentage {
  border-left: 3px solid #4299e1;
}

.progress-completed {
  border-left: 3px solid #48bb78;
}

/* Responsive design for mobile */
@media (max-width: 1024px) {
  .habit-card {
    grid-column: span 1;
  }
}

@media (max-width: 768px) {
  /* Habit Cards Grid */
  div[style*="grid-template-columns: repeat(3, 1fr)"] {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 15px !important;
  }
  
  .habit-card {
    padding: 18px 20px;
    min-height: 140px;
    margin: 8px;
  }
  
  .habit-image {
    height: 100px;
  }
  
  .habit-title {
    font-size: 1.1rem;
  }
  
  .habit-description {
    font-size: 0.75rem;
  }
  
  .habit-status {
    font-size: 0.7rem;
  }
}

@media (max-width: 480px) {
  /* Habit Cards Grid */
  .habits-grid {
    grid-template-columns: 1fr !important;
    gap: 12px !important;
  }
  
  .habit-card {
    padding: 0.75rem;
    min-height: 80px;
    margin: 3px;
  }
  
  .habit-content {
    min-height: 40px;
  }
  
  .habit-image {
    width: 7.5rem;
    height: 7.5rem;
    margin: 0 0.5rem 0.25rem 0;
  }
  
  .habit-image img {
    width: 100%;
    height: 100%;
  }
  
  .habit-title {
    font-size: 0.8rem;
    margin-bottom: 2px;
  }
  
  .habit-description {
    font-size: 0.65rem;
    line-height: 1.2;
  }
  
  .habit-status {
    padding: 0.4rem;
    margin-top: 0.4rem;
  }
  
  /* Progress Section Mobile - Ultra Compact */
  .progress-section {
    padding: 0.5rem;
    margin-bottom: 0.75rem;
  }
  
  .progress-card {
    padding: 0.4rem 0.5rem;
  }
  
  .progress-card h3 {
    font-size: 0.65rem !important;
    margin-bottom: 2px !important;
  }
  
  .progress-card p {
    font-size: 1rem !important;
  }
  
  /* Header Mobile */
  .progress-section h1 {
    font-size: 1.4em !important;
    margin-bottom: 5px !important;
  }
  
  .progress-section p {
    font-size: 0.8rem !important;
    margin-bottom: 10px !important;
  }
}

/* Additional Utility Classes */
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }
.text-slate-600 { color: #475569; }
.text-sm { font-size: 0.875rem; }
.text-2xl { font-size: 1.5rem; }
.italic { font-style: italic; }

/* Card Content Layout */
.text-content {
  flex: 1;
}

.text-content p:last-child {
  margin-bottom: 0;
}

/* Card Animations */
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

.habit-card {
  animation: slideInUp 0.6s ease-out;
}

.habit-card:nth-child(1) { animation-delay: 0.1s; }
.habit-card:nth-child(2) { animation-delay: 0.2s; }
.habit-card:nth-child(3) { animation-delay: 0.3s; }
.habit-card:nth-child(4) { animation-delay: 0.4s; }
.habit-card:nth-child(5) { animation-delay: 0.5s; }
.habit-card:nth-child(6) { animation-delay: 0.6s; }
.habit-card:nth-child(7) { animation-delay: 0.7s; }
  
  .habit-content {
    width: 100%;
    padding-left: 0;
  }
  
  .habit-title {
    font-size: 1rem;
  }
  
  .habit-description {
    font-size: 0.8rem;
  }
}

/* Prayer checkbox styling */
input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
}

.prayer-section {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  border-left: 4px solid #3b82f6;
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 15px;
}

.prayer-item {
  background: white;
  border: 1px solid #e0f2fe;
  border-radius: 6px;
  padding: 8px;
  transition: all 0.2s ease;
}

.prayer-item:hover {
  background: #f8fafc;
  border-color: #3b82f6;
}

.other-worship-item {
  background: #e0f2fe;
  border-radius: 6px;
  padding: 8px;
  margin-bottom: 5px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.remove-btn {
  background: #ef4444;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 2px 8px;
  font-size: 12px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.remove-btn:hover {
  background: #dc2626;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .habit-card {
    grid-template-columns: 1fr;
    grid-template-rows: auto auto;
    text-align: left;
    min-height: 160px;
  }
  
  .habit-content {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding-left: 0;
    gap: 10px;
  }
  
  .habit-image {
    margin-right: 0;
    margin-bottom: 10px;
  }
  
  .habit-image img {
    width:1000px;
    height:1000px;
  }
  
  .habit-text {
    text-align: center;
  }
  
  .habit-status {
    grid-column: 1;
    grid-row: 2;
    margin-top: 15px;
  }
}
</style>

<div id="habitApp" x-data="habitApp()" x-init="init()">
  <!-- Simplified Loading Overlay -->
  <template x-if="dateLoading">
    <div style="position:fixed; inset:0; background:rgba(255,255,255,0.92); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:14px; z-index:9999; font-family:Inter,system-ui,sans-serif;">
      <div class="custom-loader" aria-label="Memuat data"></div>
      <div style="font-size:0.9rem; color:#334155; font-weight:600;" x-text="'Memuat data ' + selectedDate"></div>
      <div style="font-size:0.75rem; color:#64748b; letter-spacing:.5px;" x-text="dateLoadingProgress + '%'" aria-live="polite"></div>
    </div>
  </template>
  <!-- Modern Header -->
  <div class="app-header">
    <h1>7 Kebiasaan Anak Indonesia Hebat</h1>
    <p>Bangun karakter hebat melalui kebiasaan positif setiap hari</p>
  </div>

  <!-- Tabs removed: focusing only on daily view -->

  <!-- Daily View -->
  <div x-show="currentView === 'daily'" class="daily-view">
    <h1 style="margin: 0 0 8px 0; font-size: 1.8em; color: #1f2937; text-align: center;">7 Kebiasaan Anak Indonesia Hebat</h1>
    <p style="margin: 0 0 15px 0; color: #6b7280; text-align: center; font-size: 0.9rem;">Bangun karakter hebat melalui kebiasaan positif setiap hari</p>
    
    <!-- Date Selector - Modern -->
    <div style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
      <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <label style="font-weight: 600; color: #1e293b; font-size: 1rem;">Pilih Tanggal:</label>
        <input type="date" 
               x-model="selectedDate" 
               :max="getCurrentDate()"
               @change="console.log('📅 Date changed to:', selectedDate); loadDataForDate()"
               style="padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; cursor: pointer; transition: border-color 0.3s ease;">
        <button @click="goToToday()" 
                style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer; transition: transform 0.2s ease;"
                onmouseover="this.style.transform='scale(1.05)'"
                onmouseout="this.style.transform='scale(1)'">
          Hari Ini
        </button>
      </div>
      <div style="text-align: center; margin-top: 1rem;">
        <span style="color: #64748b; font-size: 1rem; font-weight: 500;" x-text="getDateDisplayText()"></span>
      </div>
    </div>
    
    <!-- Progress Summary - Modern Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
      <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);">
        <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; font-weight: 600; opacity: 0.9;">Progress Hari Ini</h3>
        <p style="margin: 0; font-size: 2rem; font-weight: 700;" x-text="getProgressPercentage() + '%'"></p>
      </div>
      <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);">
        <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; font-weight: 600; opacity: 0.9;">Kegiatan Selesai</h3>
        <p style="margin: 0; font-size: 2rem; font-weight: 700;" x-text="getCompletedCount() + ' dari ' + getTotalCount()"></p>
      </div>
    </div>
  </div>

  <!-- Habit Cards Grid -->
  <div id="main-content" class="habits-container">
    <div class="habits-grid">
    
    <!-- 1. Bangun Pagi -->
    <div class="habit-card card-wake-up" 
         :class="[habits.wakeUp.completed ? 'completed' : '']" 
         @click="openWakeUpModal()">
      <!-- Konten Kartu Modern -->
      <div class="habit-content">
        <!-- Icon Container Modern -->
        <div class="habit-image">
          <img src="<?= base_url('assets/images/habits/bangun_pagi.png') ?>" alt="Bangun Pagi">
        </div>
        
        <!-- Text langsung wrap di sekitar float -->
        <h3 class="habit-title">Bangun Pagi</h3>
        <p class="habit-description">
          Memulai hari dengan semangat dan disiplin. Kebiasaan bangun pagi membantu menciptakan rutinitas yang sehat.
        </p>
        <p class="habit-description">
          Waktu pagi adalah momen terbaik untuk mempersiapkan diri menghadapi hari yang produktif dan penuh prestasi.
        </p>
      </div>
      
      <!-- Status Area Modern -->
      <div class="habit-status">
        <div x-show="habits.wakeUp.time">
          <span class="tag">✅ Bangun jam: <span x-text="habits.wakeUp.time"></span></span>
        </div>
        <div x-show="!habits.wakeUp.time">
          <span style="color: #64748b; font-size: 0.875rem; font-style: italic;">Klik untuk set waktu bangun pagi</span>
        </div>
      </div>
    </div>

    <!-- 2. Beribadah -->
    <div class="habit-card card-worship" 
         :class="[habits.worship.completed ? 'completed' : '']" 
         @click="openWorshipModal()">
      <!-- Konten Kartu Modern -->
      <div class="habit-content">
        <!-- Icon Container Modern -->
        <div class="habit-image">
          <img src="<?= base_url('assets/images/habits/rajin_beribadah.png') ?>" alt="Beribadah">
        </div>
        
        <!-- Text langsung wrap di sekitar float -->
        <h3 class="habit-title">Beribadah</h3>
        <p class="habit-description">
          Membentuk pribadi yang memiliki nilai spiritual kuat. Kebiasaan beribadah secara rutin membantu mengembangkan karakter yang baik.
        </p>
        <p class="habit-description">
          Memberikan ketenangan batin dan memperkuat hubungan dengan Tuhan Yang Maha Esa dalam kehidupan sehari-hari.
        </p>
      </div>
      
      <!-- Status Area Modern -->
      <div class="habit-status">
        <div x-show="habits.worship.completed">
          <span class="tag">✅ Sudah beribadah hari ini</span>
          <div x-show="habits.worship.activities.length > 0" style="margin-top: 10px;">
            <template x-for="activity in habits.worship.activities" :key="activity">
              <div style="color: #374151; font-size: 0.875rem; margin: 3px 0; font-weight: 500;" x-text="activity"></div>
            </template>
          </div>
        </div>
        <div x-show="!habits.worship.completed">
          <span style="color: #64748b; font-size: 0.875rem; font-style: italic;">Klik untuk mencatat ibadah</span>
        </div>
      </div>
    </div>

    <!-- 3. Berolahraga -->
    <div class="habit-card card-exercise" 
         :class="[habits.exercise.completed ? 'completed' : '']" 
         @click="openExerciseModal()">
      <!-- Row 1: Konten dengan text wrapping -->
      <div class="habit-content">
        <div class="habit-image">
          <img src="<?= base_url('assets/images/habits/berolahraga.png') ?>" alt="Berolahraga">
        </div>
        
        <!-- Text langsung wrap di sekitar float -->
        <h3 class="habit-title">Berolahraga</h3>
        <p class="habit-description">Mendorong kebugaran fisik dan kesehatan mental. Aktivitas fisik yang teratur membantu menjaga stamina tubuh, meningkatkan daya tahan, dan memberikan efek positif bagi kesehatan mental serta emosional dalam kehidupan sehari-hari.</p>
      </div>
      
      <!-- Row 2: Status/Input Area -->
      <div class="habit-status">
        <div x-show="habits.exercise.completed">
          <span class="tag" x-text="'Olahraga: ' + habits.exercise.duration + ' menit'"></span>
          <div x-show="habits.exercise.activities && habits.exercise.activities.length > 0" style="margin-top: 10px;">
            <template x-for="activity in habits.exercise.activities" :key="activity">
              <div style="color: #374151; font-size: 0.9rem; margin: 3px 0; font-weight: 500;" x-text="activity"></div>
            </template>
          </div>
        </div>
        <div x-show="!habits.exercise.completed">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat olahraga</span>
        </div>
      </div>
    </div>

    <!-- 4. Makan Sehat dan Bergizi -->
    <div class="habit-card card-healthy-food" 
         :class="[habits.healthyFood.completed ? 'completed' : '']" 
         @click="openHealthyFoodModal()">
      <!-- Row 1: Konten dengan text wrapping -->
      <div class="habit-content">
        <div class="habit-image">
          <img src="<?= base_url('assets/images/habits/makan_bergizi.png') ?>" alt="Makan Sehat dan Bergizi">
        </div>
        
        <!-- Text langsung wrap di sekitar float -->
        <h3 class="habit-title">Makan Sehat dan Bergizi</h3>
        <p class="habit-description">Menunjang pertumbuhan dan kecerdasan. Pola makan yang sehat dan bergizi sangat penting untuk mendukung perkembangan fisik dan mental yang optimal, serta memberikan energi yang diperlukan untuk beraktivitas sehari-hari dengan optimal.</p>
      </div>
      
      <!-- Row 2: Status/Input Area -->
      <div class="habit-status">
        <div x-show="habits.healthyFood.items.length > 0">
          <span class="tag">✅ Sudah makan sehat hari ini</span>
          <div style="margin-top: 10px;">
            <template x-for="item in habits.healthyFood.items" :key="item">
              <div style="color: #374151; font-size: 0.9rem; margin: 3px 0; font-weight: 500;" x-text="item"></div>
            </template>
          </div>
        </div>
        <div x-show="habits.healthyFood.items.length === 0">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat makanan sehat</span>
        </div>
      </div>
    </div>

    <!-- 5. Gemar Belajar -->
    <div class="habit-card card-learning" 
         :class="[habits.learning.completed ? 'completed' : '']" 
         @click="openLearningModal()">
      <!-- Row 1: Konten dengan text wrapping -->
      <div class="habit-content">
        <div class="habit-image">
          <img src="<?= base_url('assets/images/habits/gemar_belajar.png') ?>" alt="Gemar Belajar">
        </div>
        
        <!-- Text langsung wrap di sekitar float -->
        <h3 class="habit-title">Gemar Belajar</h3>
        <p class="habit-description">Menumbuhkan rasa ingin tahu dan kreativitas. Kebiasaan belajar yang konsisten membantu mengembangkan potensi diri, memperluas wawasan, dan meningkatkan kemampuan berpikir kritis untuk mencapai prestasi akademik yang optimal.</p>
      </div>
      
      <!-- Row 2: Status/Input Area -->
      <div class="habit-status">
        <div x-show="habits.learning.items.length > 0">
          <span class="tag">✅ Sudah belajar hari ini</span>
          <div style="margin-top: 10px;">
            <template x-for="item in habits.learning.items" :key="item">
              <div style="color: #374151; font-size: 0.9rem; margin: 3px 0; font-weight: 500;" x-text="item"></div>
            </template>
          </div>
        </div>
        <div x-show="habits.learning.items.length === 0">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat pembelajaran hari ini</span>
        </div>
      </div>
    </div>

    <!-- 6. Bermasyarakat -->
    <div class="habit-card card-social" 
         :class="[habits.social.completed ? 'completed' : '']" 
         @click="openSocialModal()">
      <!-- Row 1: Konten dengan text wrapping -->
      <div class="habit-content">
        <div class="habit-image">
          <img src="<?= base_url('assets/images/habits/bermasyarakat.png') ?>" alt="Bermasyarakat">
        </div>
        
        <!-- Text langsung wrap di sekitar float -->
        <h3 class="habit-title">Bermasyarakat</h3>
        <p class="habit-description">Mengajarkan kepedulian dan tanggung jawab sosial. Kegiatan sosial membantu mengembangkan empati, kerjasama, dan rasa tanggung jawab terhadap lingkungan sekitar sehingga menjadi pribadi yang berguna bagi masyarakat.</p>
      </div>
      
      <!-- Row 2: Status/Input Area -->
      <div class="habit-status">
        <div x-show="habits.social.items.length > 0">
          <span class="tag">✅ Sudah berkegiatan sosial hari ini</span>
          <div style="margin-top: 10px;">
            <template x-for="item in habits.social.items" :key="item">
              <div style="color: #374151; font-size: 0.9rem; margin: 3px 0; font-weight: 500;" x-text="item"></div>
            </template>
          </div>
        </div>
        <div x-show="habits.social.items.length === 0">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat kegiatan sosial</span>
        </div>
      </div>
    </div>

    <!-- 7. Tidur Cepat -->
    <div class="habit-card card-sleep" 
         :class="[habits.sleep.completed ? 'completed' : '']" 
         @click="openSleepModal()">
      <!-- Row 1: Konten dengan text wrapping -->
      <div class="habit-content">
        <div class="habit-image">
          <img src="<?= base_url('assets/images/habits/tidur_cepat.png') ?>" alt="Tidur Cepat">
        </div>
        
        <!-- Text langsung wrap di sekitar float -->
        <h3 class="habit-title">Tidur Cepat</h3>
        <p class="habit-description">Memastikan kualitas istirahat yang baik. Tidur yang cukup dan berkualitas sangat penting untuk pemulihan tubuh, konsentrasi belajar, dan pertumbuhan optimal sehingga siap menghadapi aktivitas keesokan harinya dengan prima.</p>
      </div>
      
      <!-- Row 2: Status/Input Area -->
      <div class="habit-status">
        <div x-show="habits.sleep.time">
          <span class="tag" x-text="'Tidur jam: ' + habits.sleep.time"></span>
        </div>
        <div x-show="!habits.sleep.time">
          <span style="color: #6b7280; font-style: italic;">Klik untuk set waktu tidur</span>
        </div>
      </div>
    </div>

    </div> <!-- End habits-grid -->
  </div> <!-- End habits-container -->

  <!-- Wake Up Modal -->
  <div id="wakeUpModal" class="modal-overlay" :class="showWakeUpModal ? 'show' : ''" tabindex="-1">
    <div class="modal-content">
      <button class="modal-close" @click="closeWakeUpModal()">✕</button>
      <h2 style="margin: 0 0 20px 0; color: #1f2937;">🌅 Bangun Pagi</h2>
      <p style="margin: 0 0 20px 0; color: #6b7280;">Jam berapa kamu bangun pagi ini?</p>
      
      <input type="time" 
             class="input-field" 
             x-model="wakeUpInput"
             style="font-size: 18px; text-align: center;">
      
      <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #0ea5e9;">
        <p style="margin: 0; color: #0c4a6e; font-size: 14px;">☀️ Target: Bangun sebelum jam 06:00 untuk memulai hari dengan semangat!</p>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button class="btn btn-secondary" @click="closeWakeUpModal()">Tutup</button>
        <button class="btn btn-success" @click="saveWakeUp()" x-show="wakeUpInput">Catat Waktu Bangun</button>
      </div>
    </div>
  </div>

  <!-- Learning Modal -->
  <div id="learningModal" class="modal-overlay" :class="showLearningModal ? 'show' : ''" tabindex="-1">
    <div class="modal-content">
      <button class="modal-close" @click="closeLearningModal()">✕</button>
      <h2 style="margin: 0 0 20px 0; color: #1f2937;">📚 Gemar Belajar</h2>
      <p style="margin: 0 0 20px 0; color: #6b7280;">Apa yang kamu pelajari hari ini?</p>
      
      <input type="text" 
             class="input-field" 
             placeholder="Contoh: Matematika, Bahasa Indonesia, IPA..." 
             x-model="learningInput"
             @keydown.enter="addLearning()">
      
      <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button class="btn btn-primary" @click="addLearning()">Tambah</button>
      </div>

      <div x-show="habits.learning.items.length > 0">
        <h4 style="margin: 0 0 10px 0; color: #374151;">Pembelajaran Hari Ini:</h4>
        <div style="margin-bottom: 20px;">
          <template x-for="(item, index) in habits.learning.items" :key="index">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 5px;">
              <span x-text="item"></span>
              <button @click="removeLearning(index)" style="background: #ef4444; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer;">Hapus</button>
            </div>
          </template>
        </div>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button class="btn btn-secondary" @click="closeLearningModal()">Tutup</button>
        <button class="btn btn-success" @click="saveLearning()" x-show="habits.learning.items.length > 0">Selesai Belajar</button>
      </div>
    </div>
  </div>

  <!-- Worship Modal -->
  <div id="worshipModal" class="modal-overlay" :class="showWorshipModal ? 'show' : ''" tabindex="-1">
    <div class="modal-content">
      <button class="modal-close" @click="closeWorshipModal()">✕</button>
      <h2 style="margin: 0 0 20px 0; color: #1f2937;">🤲 Beribadah</h2>
      <p style="margin: 0 0 20px 0; color: #6b7280;">Catat ibadah yang sudah kamu lakukan hari ini</p>
      
      <?php if ($isIslam): ?>
      <!-- Sholat 5 Waktu untuk Muslim -->
      <div style="margin-bottom: 20px;">
        <h4 style="margin: 0 0 15px 0; color: #1f2937; font-size: 16px;">🕌 Sholat 5 Waktu</h4>
        <div class="prayer-section">
          <label class="prayer-item" style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 8px;">
            <input type="checkbox" x-model="prayerTimes.subuh" style="accent-color: #3b82f6;">
            <span>🌅 Subuh</span>
          </label>
          <label class="prayer-item" style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 8px;">
            <input type="checkbox" x-model="prayerTimes.dzuhur" style="accent-color: #3b82f6;">
            <span>🌞 Dzuhur</span>
          </label>
          <label class="prayer-item" style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 8px;">
            <input type="checkbox" x-model="prayerTimes.ashar" style="accent-color: #3b82f6;">
            <span>🌤️ Ashar</span>
          </label>
          <label class="prayer-item" style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 8px;">
            <input type="checkbox" x-model="prayerTimes.maghrib" style="accent-color: #3b82f6;">
            <span>🌅 Maghrib</span>
          </label>
          <label class="prayer-item" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
            <input type="checkbox" x-model="prayerTimes.isya" style="accent-color: #3b82f6;">
            <span>🌙 Isya</span>
          </label>
        </div>
      </div>
      <?php endif; ?>

      <!-- Ibadah Lainnya -->
      <div style="margin-bottom: 20px;">
        <h4 style="margin: 0 0 15px 0; color: #1f2937; font-size: 16px;">📿 Ibadah Lainnya</h4>
        <div style="display: grid; gap: 10px;">
          <label style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f9fafb; border-radius: 8px; cursor: pointer;">
            <input type="checkbox" x-model="worshipActivities.reading">
            <span>📖 Baca Kitab Suci</span>
          </label>
          <label style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f9fafb; border-radius: 8px; cursor: pointer;">
            <input type="checkbox" x-model="worshipActivities.charity">
            <span>💰 Sedekah / Amal</span>
          </label>
          <label style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #f9fafb; border-radius: 8px; cursor: pointer;">
            <input type="checkbox" x-model="worshipActivities.dua">
            <span>🤲 Berdoa</span>
          </label>
        </div>

        <!-- Input untuk ibadah lainnya -->
        <div style="margin-top: 15px;">
          <input type="text" 
                 class="input-field" 
                 placeholder="Tambah ibadah lainnya..." 
                 x-model="otherWorshipInput"
                 @keydown.enter="addOtherWorship()"
                 style="margin-bottom: 10px;">
          <button class="btn btn-sm" @click="addOtherWorship()" style="background: #3b82f6; color: white; padding: 5px 15px; border-radius: 6px; border: none; font-size: 14px;">Tambah</button>
        </div>

        <!-- Daftar ibadah lainnya yang ditambahkan -->
        <div x-show="otherWorshipList.length > 0" style="margin-top: 15px;">
          <template x-for="(item, index) in otherWorshipList" :key="index">
            <div class="other-worship-item">
              <span x-text="item" style="color: #1f2937;"></span>
              <button @click="removeOtherWorship(index)" class="remove-btn">✕</button>
            </div>
          </template>
        </div>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button class="btn btn-secondary" @click="closeWorshipModal()">Tutup</button>
        <button class="btn btn-success" @click="saveWorship()">Catat Ibadah</button>
      </div>
    </div>
  </div>

  <!-- Social Modal -->
  <div id="socialModal" class="modal-overlay" :class="showSocialModal ? 'show' : ''" tabindex="-1">
    <div class="modal-content">
      <button class="modal-close" @click="closeSocialModal()">✕</button>
      <h2 style="margin: 0 0 20px 0; color: #1f2937;">🤝 Bermasyarakat</h2>
      <p style="margin: 0 0 20px 0; color: #6b7280;">Kegiatan sosial apa yang kamu lakukan?</p>
      
      <input type="text" 
             class="input-field" 
             placeholder="Contoh: Membantu teman, gotong royong, dll..." 
             x-model="socialInput"
             @keydown.enter="addSocial()">
      
      <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button class="btn btn-primary" @click="addSocial()">Tambah</button>
      </div>

      <div x-show="habits.social.items.length > 0">
        <h4 style="margin: 0 0 10px 0; color: #374151;">Kegiatan Sosial Hari Ini:</h4>
        <div style="margin-bottom: 20px;">
          <template x-for="(item, index) in habits.social.items" :key="index">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 5px;">
              <span x-text="item"></span>
              <button @click="removeSocial(index)" style="background: #ef4444; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer;">Hapus</button>
            </div>
          </template>
        </div>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button class="btn btn-secondary" @click="closeSocialModal()">Tutup</button>
        <button class="btn btn-success" @click="saveSocial()" x-show="habits.social.items.length > 0">Selesai Kegiatan</button>
      </div>
    </div>
  </div>

  <!-- Exercise Modal -->
  <div id="exerciseModal" class="modal-overlay" :class="showExerciseModal ? 'show' : ''" tabindex="-1">
    <div class="modal-content">
      <button class="modal-close" @click="closeExerciseModal()">✕</button>
      <h2 style="margin: 0 0 20px 0; color: #1f2937;">⚽ Berolahraga</h2>
      <p style="margin: 0 0 20px 0; color: #6b7280;">Catat aktivitas olahraga yang kamu lakukan hari ini</p>
      
      <!-- Input untuk aktivitas olahraga -->
      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; color: #1f2937; font-weight: 500;">🏃‍♂️ Aktivitas Olahraga</label>
        <input type="text" 
               class="input-field" 
               placeholder="Contoh: Lari pagi, Push up, Sepak bola, Senam, dll..." 
               x-model="exerciseActivityInput"
               @keydown.enter="addExerciseActivity()"
               style="margin-bottom: 10px;">
        <button class="btn btn-sm" @click="addExerciseActivity()" style="background: #059669; color: white; padding: 5px 15px; border-radius: 6px; border: none; font-size: 14px;">Tambah Aktivitas</button>
      </div>

      <!-- Daftar aktivitas yang ditambahkan -->
      <div x-show="exerciseActivities.length > 0" style="margin-bottom: 20px;">
        <h4 style="margin: 0 0 10px 0; color: #1f2937; font-size: 16px;">📝 Aktivitas Hari Ini:</h4>
        <template x-for="(activity, index) in exerciseActivities" :key="index">
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: #f0fdf4; border-radius: 6px; margin-bottom: 5px; border-left: 3px solid #059669;">
            <span x-text="activity" style="color: #1f2937;"></span>
            <button @click="removeExerciseActivity(index)" style="background: #ef4444; color: white; border: none; border-radius: 4px; padding: 2px 8px; font-size: 12px; cursor: pointer;">✕</button>
          </div>
        </template>
      </div>

      <!-- Input durasi -->
      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; color: #1f2937; font-weight: 500;">⏱️ Durasi Total</label>
        <input type="number" 
               class="input-field" 
               placeholder="Durasi dalam menit (contoh: 30)" 
               x-model="exerciseInput"
               min="1"
               max="300">
      </div>
      
      <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #0ea5e9;">
        <p style="margin: 0; color: #0c4a6e; font-size: 14px;">⚡ Target: Minimal 30 menit olahraga setiap hari!</p>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button class="btn btn-secondary" @click="closeExerciseModal()">Tutup</button>
        <button class="btn btn-success" @click="saveExercise()" x-show="(exerciseActivities.length > 0 || exerciseInput) && exerciseInput && exerciseInput > 0">Catat Olahraga</button>
      </div>
    </div>
  </div>

  <!-- Healthy Food Modal -->
  <div id="healthyFoodModal" class="modal-overlay" :class="showHealthyFoodModal ? 'show' : ''" tabindex="-1">
    <div class="modal-content">
      <button class="modal-close" @click="closeHealthyFoodModal()">✕</button>
      <h2 style="margin: 0 0 20px 0; color: #1f2937;">🥗 Makan Sehat</h2>
      <p style="margin: 0 0 20px 0; color: #6b7280;">Makanan sehat apa yang kamu konsumsi hari ini?</p>
      
      <input type="text" 
             class="input-field" 
             placeholder="Contoh: Sayur bayam, buah apel, susu..." 
             x-model="healthyFoodInput"
             @keydown.enter="addHealthyFood()">
      
      <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button class="btn btn-primary" @click="addHealthyFood()">Tambah</button>
      </div>

      <div x-show="habits.healthyFood.items.length > 0">
        <h4 style="margin: 0 0 10px 0; color: #374151;">Makanan Sehat Hari Ini:</h4>
        <div style="margin-bottom: 20px;">
          <template x-for="(item, index) in habits.healthyFood.items" :key="index">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: #f9fafb; border-radius: 6px; margin-bottom: 5px;">
              <span x-text="item"></span>
              <button @click="removeHealthyFood(index)" style="background: #ef4444; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer;">Hapus</button>
            </div>
          </template>
        </div>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button class="btn btn-secondary" @click="closeHealthyFoodModal()">Tutup</button>
        <button class="btn btn-success" @click="saveHealthyFood()" x-show="habits.healthyFood.items.length > 0">Selesai Catat</button>
      </div>
    </div>
  </div>

  <!-- Sleep Modal -->
  <div id="sleepModal" class="modal-overlay" :class="showSleepModal ? 'show' : ''" tabindex="-1">
    <div class="modal-content">
      <button class="modal-close" @click="closeSleepModal()">✕</button>
      <h2 style="margin: 0 0 20px 0; color: #1f2937;">🌙 Tidur Cepat</h2>
      <p style="margin: 0 0 20px 0; color: #6b7280;">Jam berapa kamu tidur malam ini?</p>
      
      <input type="time" 
             class="input-field" 
             x-model="sleepInput"
             style="font-size: 18px; text-align: center;">
      
      <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #0ea5e9;">
        <p style="margin: 0; color: #0c4a6e; font-size: 14px;">💡 Target: Tidur sebelum jam 21:00 untuk kesehatan optimal!</p>
      </div>

      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button class="btn btn-secondary" @click="closeSleepModal()">Tutup</button>
        <button class="btn btn-success" @click="saveSleep()" x-show="sleepInput">Catat Waktu Tidur</button>
      </div>
    </div>
  </div>
  </div> <!-- End Daily View -->

  <!-- Monthly Report View -->
  <div x-show="currentView === 'monthly'" class="monthly-view" style="display: none;">
    <!-- Month Selector -->
    <div style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
      <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <label style="font-weight: 600; color: #1e293b; font-size: 1rem;">Pilih Bulan:</label>
        <input type="month" 
               x-model="selectedMonth" 
               :max="getCurrentMonth()"
               @change="loadMonthlyReport()"
               style="padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; cursor: pointer;">
        <button @click="goToCurrentMonth()" 
                style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer;">
          Bulan Ini
        </button>
      </div>
    </div>

    <!-- Monthly Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
      <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);">
        <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600; opacity: 0.9;">Total Hari Aktif</h3>
        <p style="margin: 0; font-size: 2rem; font-weight: 700;" x-text="monthlyStats.activeDays + ' hari'"></p>
      </div>
      <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);">
        <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600; opacity: 0.9;">Rata-rata Harian</h3>
        <p style="margin: 0; font-size: 2rem; font-weight: 700;" x-text="monthlyStats.averageCompletion + '%'"></p>
      </div>
      <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 1.5rem; border-radius: 16px; box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);">
        <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600; opacity: 0.9;">Streak Terpanjang</h3>
        <p style="margin: 0; font-size: 2rem; font-weight: 700;" x-text="monthlyStats.longestStreak + ' hari'"></p>
      </div>
    </div>

    <!-- Journal-style Daily Records -->
    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
      <h2 style="margin: 0 0 1.5rem 0; color: #1e293b; font-size: 1.5rem; font-weight: 700; text-align: center;">📖 Jurnal Harian Kebiasaan</h2>
      
      <div x-show="Object.keys(monthlyData).length === 0" style="text-align: center; color: #64748b; padding: 2rem;">
        <p style="font-size: 1.1rem;">📝 Belum ada data untuk bulan ini</p>
        <p>Mulai catat kebiasaan harian Anda!</p>
      </div>

      <div class="journal-entries" style="max-height: 600px; overflow-y: auto;">
        <template x-for="(dayData, date) in monthlyData" :key="date">
          <div class="journal-entry" style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; background: #fafafa;">
            <!-- Date Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">
              <h3 style="margin: 0; color: #1e293b; font-size: 1.1rem; font-weight: 600;" x-text="formatDateForJournal(date)"></h3>
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;" x-text="getCompletionPercentage(dayData) + '%'"></span>
                <span style="color: #64748b; font-size: 0.9rem;" x-text="getCompletedCountForDay(dayData) + '/7'"></span>
              </div>
            </div>

            <!-- Habit Details -->
            <div class="habit-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
              <!-- Wake Up -->
              <div x-show="dayData.wakeUp && dayData.wakeUp.completed" class="habit-item" style="background: white; padding: 1rem; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1.2rem;">🌅</span>
                  <strong style="color: #1e293b;">Bangun Pagi</strong>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.9rem;" x-text="'Bangun jam: ' + (dayData.wakeUp.time || 'Tidak dicatat')"></p>
              </div>

              <!-- Worship -->
              <div x-show="dayData.worship && dayData.worship.completed" class="habit-item" style="background: white; padding: 1rem; border-radius: 8px; border-left: 4px solid #10b981;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1.2rem;">🤲</span>
                  <strong style="color: #1e293b;">Beribadah</strong>
                </div>
                <div style="font-size: 0.9rem; color: #64748b;">
                  <div x-show="dayData.worship.activities && Object.values(dayData.worship.activities).some(v => v)" style="margin-bottom: 0.25rem;">
                    <span>Aktivitas: </span>
                    <span x-text="getWorshipActivitiesText(dayData.worship)"></span>
                  </div>
                  <div x-show="dayData.worship.prayers && Object.values(dayData.worship.prayers).some(v => v)" style="margin-bottom: 0.25rem;">
                    <span>Sholat: </span>
                    <span x-text="getPrayerTimesText(dayData.worship)"></span>
                  </div>
                  <div x-show="dayData.worship.otherActivities && dayData.worship.otherActivities.length > 0">
                    <span>Lainnya: </span>
                    <span x-text="dayData.worship.otherActivities.join(', ')"></span>
                  </div>
                </div>
              </div>

              <!-- Exercise -->
              <div x-show="dayData.exercise && dayData.exercise.completed" class="habit-item" style="background: white; padding: 1rem; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1.2rem;">⚽</span>
                  <strong style="color: #1e293b;">Olahraga</strong>
                </div>
                <div style="font-size: 0.9rem; color: #64748b;">
                  <div x-show="dayData.exercise.duration" x-text="'Durasi: ' + dayData.exercise.duration + ' menit'"></div>
                  <div x-show="dayData.exercise.activities && dayData.exercise.activities.length > 0" x-text="'Aktivitas: ' + dayData.exercise.activities.join(', ')"></div>
                </div>
              </div>

              <!-- Healthy Food -->
              <div x-show="dayData.healthyFood && dayData.healthyFood.completed" class="habit-item" style="background: white; padding: 1rem; border-radius: 8px; border-left: 4px solid #22c55e;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1.2rem;">🥗</span>
                  <strong style="color: #1e293b;">Makanan Sehat</strong>
                </div>
                <div x-show="dayData.healthyFood.items && dayData.healthyFood.items.length > 0" style="font-size: 0.9rem; color: #64748b;" x-text="dayData.healthyFood.items.join(', ')"></div>
              </div>

              <!-- Learning -->
              <div x-show="dayData.learning && dayData.learning.completed" class="habit-item" style="background: white; padding: 1rem; border-radius: 8px; border-left: 4px solid #8b5cf6;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1.2rem;">📚</span>
                  <strong style="color: #1e293b;">Belajar</strong>
                </div>
                <div x-show="dayData.learning.items && dayData.learning.items.length > 0" style="font-size: 0.9rem; color: #64748b;" x-text="dayData.learning.items.join(', ')"></div>
              </div>

              <!-- Social -->
              <div x-show="dayData.social && dayData.social.completed" class="habit-item" style="background: white; padding: 1rem; border-radius: 8px; border-left: 4px solid #ec4899;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1.2rem;">🤝</span>
                  <strong style="color: #1e293b;">Bersosialisasi</strong>
                </div>
                <div x-show="dayData.social.items && dayData.social.items.length > 0" style="font-size: 0.9rem; color: #64748b;" x-text="dayData.social.items.join(', ')"></div>
              </div>

              <!-- Sleep -->
              <div x-show="dayData.sleep && dayData.sleep.completed" class="habit-item" style="background: white; padding: 1rem; border-radius: 8px; border-left: 4px solid #6366f1;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1.2rem;">🌙</span>
                  <strong style="color: #1e293b;">Tidur Teratur</strong>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.9rem;" x-text="'Tidur jam: ' + (dayData.sleep.time || 'Tidak dicatat')"></p>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div> <!-- End Monthly View -->

</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function habitApp() {
  return {
    // UI State
    currentView: 'daily',
  // Loading state for date change
  dateLoading: false,
  dateLoadingProgress: 0,
  _dateLoadingInterval: null,
  _dotsInterval: null,
    
    // Date management
    selectedDate: '',
    selectedMonth: '',
    
    // Monthly Report Data
    monthlyData: {},
    monthlyStats: {
      activeDays: 0,
      averageCompletion: 0,
      longestStreak: 0
    },
    
    // Modal states
    showWakeUpModal: false,
    showLearningModal: false,
    showWorshipModal: false,
    showSocialModal: false,
    showExerciseModal: false,
    showHealthyFoodModal: false,
    showSleepModal: false,
    
    // Input states
    wakeUpInput: '',
    learningInput: '',
    socialInput: '',
    exerciseInput: '',
    exerciseActivityInput: '',
    exerciseActivities: [],
    healthyFoodInput: '',
    sleepInput: '',
    
    // Worship activities
    worshipActivities: {
      reading: false,
      charity: false,
  dua: false
    },
    
    // Prayer times for Muslim students
    prayerTimes: {
      subuh: false,
      dzuhur: false,
      ashar: false,
      maghrib: false,
      isya: false
    },
    
    // Other worship activities
    otherWorshipInput: '',
    otherWorshipList: [],
    
    // Habits data - 7 Kebiasaan Anak Indonesia Hebat (Official Order)
    habits: {
      wakeUp: {
        completed: false,
        time: ''
      },
      worship: {
        completed: false,
        activities: []
      },
      exercise: {
        completed: false,
        duration: '',
        activities: []
      },
      healthyFood: {
        completed: false,
        items: []
      },
      learning: {
        completed: false,
        items: []
      },
      social: {
        completed: false,
        items: []
      },
      sleep: {
        completed: false,
        time: ''
      }
    },
    
    init() {
      console.log('🚀 7 Kebiasaan Anak Indonesia Hebat - App initialized (Official Version)');
      this.selectedDate = this.getCurrentDate();
      this.selectedMonth = this.getCurrentMonth();
      
      console.log(`📅 Initial date set to: ${this.selectedDate}`);
      this.loadDataForDate();
      
      // Bind escape key handler to this context
      this.handleEscapeKey = this.handleEscapeKey.bind(this);
    },
    
    // Date management functions
    getCurrentDate() {
      const today = new Date();
      return today.toISOString().split('T')[0];
    },
    
    goToToday() {
      const todayDate = this.getCurrentDate();
      console.log(`📅 Going to today: ${todayDate} (was: ${this.selectedDate})`);
      this.selectedDate = todayDate;
      this.loadDataForDate();
    },
    
    getDateDisplayText() {
      if (!this.selectedDate) return '';
      
      const selected = new Date(this.selectedDate);
      const today = new Date();
      
      // Reset time to compare dates only
      selected.setHours(0, 0, 0, 0);
      today.setHours(0, 0, 0, 0);
      
      if (selected.getTime() === today.getTime()) {
        return 'Kebiasaan Hari Ini';
      } else if (selected.getTime() < today.getTime()) {
        return 'Riwayat Kebiasaan - ' + this.formatDate(this.selectedDate);
      }
      return 'Tanggal: ' + this.formatDate(this.selectedDate);
    },
    
    formatDate(dateString) {
      const date = new Date(dateString);
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      };
      return date.toLocaleDateString('id-ID', options);
    },
    
    isToday() {
      return this.selectedDate === this.getCurrentDate();
    },
    
    // Modal Helper Functions
    addModalBlur(modalId) {
      this.$nextTick(() => {
        // Add blur effect to main content
        document.getElementById('main-content').classList.add('content-blur');
        
        // Focus on modal for accessibility
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.focus();
          // Focus on first input in modal if exists
          const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
          if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
          }
        }
        
        // Add escape key listener
        this.addEscapeListener();
      });
    },
    
    removeModalBlur() {
      // Remove blur effect from main content
      document.getElementById('main-content').classList.remove('content-blur');
      
      // Remove escape key listener
      this.removeEscapeListener();
    },
    
    addEscapeListener() {
      document.addEventListener('keydown', this.handleEscapeKey);
    },
    
    removeEscapeListener() {
      document.removeEventListener('keydown', this.handleEscapeKey);
    },
    
    handleEscapeKey(event) {
      if (event.key === 'Escape') {
        // Close any open modal
        if (this.showWakeUpModal) this.closeWakeUpModal();
        if (this.showLearningModal) this.closeLearningModal();
        if (this.showWorshipModal) this.closeWorshipModal();
        if (this.showSocialModal) this.closeSocialModal();
        if (this.showExerciseModal) this.closeExerciseModal();
        if (this.showHealthyFoodModal) this.closeHealthyFoodModal();
        if (this.showSleepModal) this.closeSleepModal();
      }
    },
    
    // Wake Up Modal Functions
    openWakeUpModal() {
      console.log('🌅 Opening wake up modal');
      this.showWakeUpModal = true;
      this.wakeUpInput = this.habits.wakeUp.time || '';
      this.addModalBlur('wakeUpModal');
    },
    
    closeWakeUpModal() {
      this.showWakeUpModal = false;
      this.removeModalBlur();
    },
    
    saveWakeUp() {
      if (this.wakeUpInput) {
        this.habits.wakeUp.time = this.wakeUpInput;
        this.habits.wakeUp.completed = true;
        this.closeWakeUpModal();
        this.saveData();
        console.log('✅ Wake up time saved:', this.wakeUpInput);
      }
    },
    
    // Learning Modal Functions
    openLearningModal() {
      console.log('📚 Opening learning modal');
      this.showLearningModal = true;
      this.learningInput = '';
      this.addModalBlur('learningModal');
    },
    
    closeLearningModal() {
      this.showLearningModal = false;
      this.removeModalBlur();
    },
    
    addLearning() {
      if (this.learningInput.trim()) {
        this.habits.learning.items.push(this.learningInput.trim());
        this.learningInput = '';
        console.log('📚 Added learning item:', this.habits.learning.items);
      }
    },
    
    removeLearning(index) {
      this.habits.learning.items.splice(index, 1);
    },
    
    saveLearning() {
      this.habits.learning.completed = this.habits.learning.items.length > 0;
      this.closeLearningModal();
      this.saveData();
      console.log('✅ Learning saved');
    },
    
    // Worship Modal Functions
    openWorshipModal() {
      console.log('🤲 Opening worship modal');
      this.showWorshipModal = true;
      
      // Load existing data instead of resetting
      this.loadExistingWorshipData();
      
      this.otherWorshipInput = '';
      this.addModalBlur('worshipModal');
    },
    
    loadExistingWorshipData() {
      // Reset first
      this.worshipActivities = {
        reading: false,
        charity: false,
  dua: false
      };
      <?php if ($isIslam): ?>
      this.prayerTimes = {
        subuh: false,
        dzuhur: false,
        ashar: false,
        maghrib: false,
        isya: false
      };
      <?php endif; ?>
      this.otherWorshipList = [];
      
      // Load existing data from habits.worship.activities
      if (this.habits.worship.activities && this.habits.worship.activities.length > 0) {
        this.habits.worship.activities.forEach(activity => {
          <?php if ($isIslam): ?>
          if (activity.startsWith('Sholat: ')) {
            // Split tokens after label
            const tokens = activity.replace('Sholat: ', '').split(',').map(t => t.trim()).filter(Boolean);
            tokens.forEach(token => {
              const lower = token.toLowerCase();
              if (['subuh','dzuhur','zuhur','ashar','asar','maghrib','isya','isha'].includes(lower)) {
                switch(true){
                  case lower === 'subuh': this.prayerTimes.subuh = true; break;
                  case lower === 'dzuhur' || lower === 'zuhur': this.prayerTimes.dzuhur = true; break;
                  case lower === 'ashar' || lower === 'asar': this.prayerTimes.ashar = true; break;
                  case lower === 'maghrib': this.prayerTimes.maghrib = true; break;
                  case lower === 'isya' || lower === 'isha': this.prayerTimes.isya = true; break;
                }
              } else {
                // Non-prayer token embedded in Sholat line → treat as worship activity or custom
                if (lower.includes('sedekah') || lower.includes('amal')) this.worshipActivities.charity = true;
                else if (lower.includes('baca') && lower.includes('kitab')) this.worshipActivities.reading = true;
                else if (lower.includes('berdoa')) this.worshipActivities.dua = true;
                else if (lower.includes('menolong')) {
                  if (!this.otherWorshipList.includes('Menolong orang')) this.otherWorshipList.push('Menolong orang');
                } else if (!this.otherWorshipList.includes(token)) {
                  this.otherWorshipList.push(token);
                }
              }
            });
            return; // Done with this combined string
          }
          <?php endif; ?>
          if (activity === 'Baca Kitab Suci') {
            this.worshipActivities.reading = true;
          } else if (activity === 'Sedekah/Amal') {
            this.worshipActivities.charity = true;
          } else if (activity === 'Berdoa') {
            this.worshipActivities.dua = true;
          } else if (activity === 'Menolong Orang' || activity.toLowerCase().includes('menolong')) {
            if (!this.otherWorshipList.includes('Menolong orang')) this.otherWorshipList.push('Menolong orang');
          } else if (!activity.startsWith('Sholat: ')) {
            this.otherWorshipList.push(activity);
          }
        });
      }
    },
    
    closeWorshipModal() {
      this.showWorshipModal = false;
      this.removeModalBlur();
    },

    addOtherWorship() {
      if (this.otherWorshipInput.trim()) {
        this.otherWorshipList.push(this.otherWorshipInput.trim());
        this.otherWorshipInput = '';
      }
    },

    removeOtherWorship(index) {
      this.otherWorshipList.splice(index, 1);
    },
    
    saveWorship() {
      const activities = [];
      
      // Add prayer times for Muslim students
      <?php if ($isIslam): ?>
      const completedPrayers = [];
      if (this.prayerTimes.subuh) completedPrayers.push('Subuh');
      if (this.prayerTimes.dzuhur) completedPrayers.push('Dzuhur');
      if (this.prayerTimes.ashar) completedPrayers.push('Ashar');
      if (this.prayerTimes.maghrib) completedPrayers.push('Maghrib');
      if (this.prayerTimes.isya) completedPrayers.push('Isya');
      
      if (completedPrayers.length > 0) {
        activities.push(`Sholat: ${completedPrayers.join(', ')}`);
      }
      <?php endif; ?>
      
      // Add other worship activities
      if (this.worshipActivities.reading) activities.push('Baca Kitab Suci');
      if (this.worshipActivities.charity) activities.push('Sedekah/Amal');
      if (this.worshipActivities.dua) activities.push('Berdoa');
      
      // Add custom worship activities
      this.otherWorshipList.forEach(item => {
        activities.push(item);
      });
      
      this.habits.worship.activities = activities;
      this.habits.worship.completed = activities.length > 0;
      this.closeWorshipModal();
      this.saveData();
      console.log('✅ Worship saved');
    },
    
    // Social Modal Functions
    openSocialModal() {
      console.log('🤝 Opening social modal');
      this.showSocialModal = true;
      this.socialInput = '';
      this.addModalBlur('socialModal');
    },
    
    closeSocialModal() {
      this.showSocialModal = false;
      this.removeModalBlur();
    },
    
    addSocial() {
      if (this.socialInput.trim()) {
        this.habits.social.items.push(this.socialInput.trim());
        this.socialInput = '';
        console.log('🤝 Added social item:', this.habits.social.items);
      }
    },
    
    removeSocial(index) {
      this.habits.social.items.splice(index, 1);
    },
    
    saveSocial() {
      this.habits.social.completed = this.habits.social.items.length > 0;
      this.closeSocialModal();
      this.saveData();
      console.log('✅ Social saved');
    },
    
    // Exercise Modal Functions
    openExerciseModal() {
      console.log('⚽ Opening exercise modal');
      this.showExerciseModal = true;
      this.exerciseInput = this.habits.exercise.duration || '';
      this.exerciseActivityInput = '';
      
      // Load existing activities
      this.exerciseActivities = [...(this.habits.exercise.activities || [])];
      this.addModalBlur('exerciseModal');
    },
    
    closeExerciseModal() {
      this.showExerciseModal = false;
      this.removeModalBlur();
    },

    addExerciseActivity() {
      if (this.exerciseActivityInput.trim()) {
        this.exerciseActivities.push(this.exerciseActivityInput.trim());
        this.exerciseActivityInput = '';
        console.log('⚽ Added exercise activity:', this.exerciseActivities);
      }
    },

    removeExerciseActivity(index) {
      this.exerciseActivities.splice(index, 1);
    },
    
    saveExercise() {
      if (this.exerciseInput && this.exerciseInput > 0) {
        this.habits.exercise.duration = this.exerciseInput;
        this.habits.exercise.activities = [...this.exerciseActivities];
        this.habits.exercise.completed = true;
        this.closeExerciseModal();
        this.saveData();
        console.log('✅ Exercise saved:', {
          duration: this.exerciseInput + ' minutes',
          activities: this.exerciseActivities
        });
      }
    },
    
    // Healthy Food Modal Functions
    openHealthyFoodModal() {
      console.log('🥗 Opening healthy food modal');
      this.showHealthyFoodModal = true;
      this.healthyFoodInput = '';
      this.addModalBlur('healthyFoodModal');
    },
    
    closeHealthyFoodModal() {
      this.showHealthyFoodModal = false;
      this.removeModalBlur();
    },
    
    addHealthyFood() {
      if (this.healthyFoodInput.trim()) {
        this.habits.healthyFood.items.push(this.healthyFoodInput.trim());
        this.healthyFoodInput = '';
        console.log('🥗 Added healthy food item:', this.habits.healthyFood.items);
      }
    },
    
    removeHealthyFood(index) {
      this.habits.healthyFood.items.splice(index, 1);
    },
    
    saveHealthyFood() {
      this.habits.healthyFood.completed = this.habits.healthyFood.items.length > 0;
      this.closeHealthyFoodModal();
      this.saveData();
      console.log('✅ Healthy food saved');
    },
    
    // Sleep Modal Functions
    openSleepModal() {
      console.log('🌙 Opening sleep modal');
      this.showSleepModal = true;
      this.sleepInput = this.habits.sleep.time || '';
      this.addModalBlur('sleepModal');
    },
    
    closeSleepModal() {
      this.showSleepModal = false;
      this.removeModalBlur();
    },
    
    saveSleep() {
      if (this.sleepInput) {
        this.habits.sleep.time = this.sleepInput;
        this.habits.sleep.completed = true;
        this.closeSleepModal();
        this.saveData();
        console.log('✅ Sleep time saved:', this.sleepInput);
      }
    },
    
    // Progress Functions
    getProgressPercentage() {
      const completed = this.getCompletedCount();
      const total = this.getTotalCount();
      return total > 0 ? Math.round((completed / total) * 100) : 0;
    },
    
    getCompletedCount() {
      let count = 0;
      if (this.habits.wakeUp.completed) count++;
      if (this.habits.worship.completed) count++;
      if (this.habits.exercise.completed) count++;
      if (this.habits.healthyFood.completed) count++;
      if (this.habits.learning.completed) count++;
      if (this.habits.social.completed) count++;
      if (this.habits.sleep.completed) count++;
      return count;
    },
    
    getTotalCount() {
      return 7; // 7 Kebiasaan Anak Indonesia Hebat (Official)
    },
    
    // Data persistence per date
    saveData() {
      const storageKey = `siswa-habits-7-kebiasaan-${this.selectedDate}`;
      localStorage.setItem(storageKey, JSON.stringify(this.habits));
      console.log(`✅ Data saved locally for ${this.selectedDate}:`, this.habits);
      
      // Always try to save to server for any date
      this.saveToServer();
    },
    
    // Check if date is recent (within last 7 days)
    isRecentDate() {
      const selectedDate = new Date(this.selectedDate);
      const today = new Date();
      const diffTime = today - selectedDate;
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays >= 0 && diffDays <= 7;
    },
    
    // Save data to server
    async saveToServer() {
      try {
        const payload = this.prepareServerPayload();
        
        const response = await fetch('<?= base_url('siswa/logs') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            date: this.selectedDate,
            habits: payload
          })
        });
        
        if (response.ok) {
          const ct = (response.headers.get('content-type') || '').toLowerCase();
          if (!ct.includes('application/json')) {
            // Likely redirected to login (HTML returned)
            console.warn('⚠️ Server returned non-JSON (likely login page). Please login and try again.');
            return; // Do not throw; keep UX smooth
          }
          const result = await response.json();
          console.log('✅ Data saved to server:', result.message || result);
        } else {
          console.log('❌ Failed to save to server:', response.status, response.statusText);
        }
      } catch (error) {
        console.log('❌ Error saving to server:', error);
      }
    },
    
    // Prepare data payload for server
    prepareServerPayload() {
      const payload = {};
      
      // 1. Wake Up (Bangun Pagi)
      if (this.habits.wakeUp.completed) {
        payload[1] = {
          bool: true,
          time: this.habits.wakeUp.time || null
        };
      }
      
      // 2. Worship (Beribadah)
      if (this.habits.worship.completed) {
        payload[2] = {
          bool: true,
          notes: this.habits.worship.activities.join(', ') || null
        };
      }
      
      // 3. Exercise (Olahraga)
      if (this.habits.exercise.completed) {
        payload[3] = {
          bool: true,
          number: this.habits.exercise.duration ? parseFloat(this.habits.exercise.duration) : null,
          notes: this.habits.exercise.activities ? this.habits.exercise.activities.join(', ') : null
        };
      }
      
      // 4. Healthy Food (Makan Sehat)
      if (this.habits.healthyFood.completed) {
        payload[4] = {
          bool: true,
          notes: this.habits.healthyFood.items.join(', ') || null
        };
      }
      
      // 5. Learning (Gemar Belajar)
      if (this.habits.learning.completed) {
        payload[5] = {
          bool: true,
          notes: this.habits.learning.items.join(', ') || null
        };
      }
      
      // 6. Social (Bermasyarakat)
      if (this.habits.social.completed) {
        payload[6] = {
          bool: true,
          notes: this.habits.social.items.join(', ') || null
        };
      }
      
      // 7. Sleep (Tidur Cepat)
      if (this.habits.sleep.completed) {
        payload[7] = {
          bool: true,
          time: this.habits.sleep.time || null
        };
      }
      
      return payload;
    },
    
    loadDataForDate() {
      console.log(`🔄 Loading data for ${this.selectedDate}`);
      this.startDateLoading();
      // Slight delay to let overlay paint
      setTimeout(() => {
        this.loadFromServer().then((hasServerData) => {
          if (!hasServerData) {
            console.log(`📊 No server data, falling back to localStorage for ${this.selectedDate}`);
            this.loadFromLocalStorage();
          } else {
            console.log(`📊 Using server data for ${this.selectedDate}`);
          }
        }).catch((error) => {
          console.log('⚠️ Server load failed, falling back to localStorage:', error);
          this.loadFromLocalStorage();
        }).finally(() => {
          this.finishDateLoading();
        });
      }, 50);
    },

    startDateLoading() {
      // Reset progress
      this.dateLoading = true;
      this.dateLoadingProgress = 0;
      if (this._dateLoadingInterval) clearInterval(this._dateLoadingInterval);
      // Progress simulation (fast to 70%, then slower)
      this._dateLoadingInterval = setInterval(() => {
        if (this.dateLoadingProgress < 92) {
          this.dateLoadingProgress += Math.random() * 8 + 4; // 4-12%
        } else if (this.dateLoadingProgress < 98) {
          this.dateLoadingProgress += 1; // finalize
        }
        if (this.dateLoadingProgress > 98) this.dateLoadingProgress = 98;
      }, 300);
    },

    finishDateLoading() {
      // Complete progress smoothly
      setTimeout(() => {
        this.dateLoadingProgress = 100;
        setTimeout(() => {
          this.dateLoading = false;
          if (this._dateLoadingInterval) clearInterval(this._dateLoadingInterval);
          this._dateLoadingInterval = null;
        }, 300);
      }, 150);
    },
    
    // Load data from server
    async loadFromServer() {
      try {
        const response = await fetch(`<?= base_url('siswa/summary') ?>?date=${this.selectedDate}`);
        
        if (response.ok) {
          const ct = (response.headers.get('content-type') || '').toLowerCase();
          if (!ct.includes('application/json')) {
            // Got HTML instead of JSON (most likely login page due to missing session)
            console.warn('⚠️ Not authenticated or non-JSON response. Falling back to local data. Please login.');
            return false;
          }
          const result = await response.json();
          if (result.data && result.data.length > 0) {
            this.mapServerDataToHabits(result.data);
            // Force Alpine.js to update by triggering a re-render
            this.$nextTick(() => {
              console.log(`📊 UI updated after loading ${result.data.length} records for ${this.selectedDate}`);
            });
            console.log(`📊 Loaded ${result.data.length} records from server for ${this.selectedDate}`);
            return true; // Found server data
          }
        }
        
        console.log(`📅 No server data found for ${this.selectedDate}`);
        return false; // No server data
      } catch (error) {
        console.log('❌ Error loading from server:', error);
        throw error; // Re-throw to be caught by loadDataForDate
      }
    },
    
    // Load data from localStorage (fallback)
    loadFromLocalStorage() {
      const storageKey = `siswa-habits-7-kebiasaan-${this.selectedDate}`;
      const saved = localStorage.getItem(storageKey);
      
      if (saved) {
        try {
          const loadedData = JSON.parse(saved);
          this.habits = loadedData;
          console.log(`📊 Loaded data from localStorage for ${this.selectedDate}:`, loadedData);
          return true;
        } catch (e) {
          console.log('❌ Error loading localStorage data:', e);
          this.resetHabits();
          return false;
        }
      } else {
        console.log(`📅 No localStorage data found for ${this.selectedDate}, resetting habits`);
        this.resetHabits();
        return false;
      }
    },
    
    // Map server data to habits object
    mapServerDataToHabits(serverData) {
      this.resetHabits();
      
      console.log('🔍 Raw server data:', serverData);
      
      serverData.forEach(item => {
        console.log(`🔍 Processing habit_id ${item.habit_id}:`, item);
        
        // Convert habit_id to integer for comparison
        const habitId = parseInt(item.habit_id);
        
        switch (habitId) {
          case 1: // Wake Up - uses value_time
            if (item.value_time && item.value_time.trim() !== '') {
              this.habits.wakeUp.completed = true;
              this.habits.wakeUp.time = item.value_time;
              console.log('✅ Wake Up set to completed with time:', item.value_time);
            } else {
              console.log('❌ Wake Up - no valid value_time');
            }
            break;
            
          case 2: // Worship - uses value_bool, notes, and value_json
            const worshipBool = item.value_bool; // ensure defined
            if (worshipBool == 1 || worshipBool === '1' || worshipBool === true || worshipBool === 'true') {
              this.habits.worship.completed = true;
              
              // Parse value_json for complex data (prayers, activities)
              if (item.value_json) {
                try {
                  const jsonData = JSON.parse(item.value_json);
                  if (jsonData.prayers && Array.isArray(jsonData.prayers)) {
                    this.habits.worship.activities = [...jsonData.prayers];
                  }
                  if (jsonData.activities && Array.isArray(jsonData.activities)) {
                    this.habits.worship.activities = [...this.habits.worship.activities, ...jsonData.activities];
                  }
                  console.log('✅ Parsed worship JSON data:', jsonData);
                } catch (e) {
                  console.log('⚠️ Failed to parse worship JSON:', e);
                }
              } else if (item.notes) {
                // Fallback to notes if no JSON data
                this.habits.worship.activities = [item.notes];
              }
              console.log('✅ Worship set to completed with activities:', this.habits.worship.activities);
            } else {
              console.log('❌ Worship - value_bool not truthy:', worshipBool);
            }
            break;
            
          case 3: // Exercise - uses value_number, notes, and value_json
            const exerciseNum = parseFloat(item.value_number);
            if (!isNaN(exerciseNum) && exerciseNum > 0) {
              this.habits.exercise.completed = true;
              this.habits.exercise.duration = exerciseNum.toString();
              
              // Parse value_json for activities
              if (item.value_json) {
                try {
                  const jsonData = JSON.parse(item.value_json);
                  if (jsonData.activities && Array.isArray(jsonData.activities)) {
                    this.habits.exercise.activities = [...jsonData.activities];
                  }
                  console.log('✅ Parsed exercise JSON data:', jsonData);
                } catch (e) {
                  console.log('⚠️ Failed to parse exercise JSON:', e);
                }
              } else if (item.notes) {
                this.habits.exercise.activities = [item.notes];
              }
              console.log('✅ Exercise set to completed with duration:', exerciseNum);
            } else {
              console.log('❌ Exercise - invalid value_number:', item.value_number);
            }
            break;
            
          case 4: // Healthy Food - uses value_bool and notes
            const healthyBool = item.value_bool;
            if (healthyBool == 1 || healthyBool === '1' || healthyBool === true || healthyBool === 'true') {
              this.habits.healthyFood.completed = true;
              if (item.notes) {
                this.habits.healthyFood.items = [item.notes]; // Store as array
              }
              console.log('✅ Healthy Food set to completed with value_bool:', healthyBool);
            } else {
              console.log('❌ Healthy Food - value_bool not truthy:', healthyBool);
            }
            break;
            
          case 5: // Learning - uses value_bool and notes
            const learningBool = item.value_bool;
            if (learningBool == 1 || learningBool === '1' || learningBool === true || learningBool === 'true') {
              this.habits.learning.completed = true;
              if (item.notes) {
                this.habits.learning.items = [item.notes]; // Store as array
              }
              console.log('✅ Learning set to completed with value_bool:', learningBool);
            } else {
              console.log('❌ Learning - value_bool not truthy:', learningBool);
            }
            break;
            
          case 6: // Social - uses value_bool and notes
            const socialBool = item.value_bool;
            if (socialBool == 1 || socialBool === '1' || socialBool === true || socialBool === 'true') {
              this.habits.social.completed = true;
              if (item.notes) {
                this.habits.social.items = [item.notes]; // Store as array
              }
              console.log('✅ Social set to completed with value_bool:', socialBool);
            } else {
              console.log('❌ Social - value_bool not truthy:', socialBool);
            }
            break;
            
          case 7: // Sleep - uses value_time
            if (item.value_time && item.value_time.trim() !== '') {
              this.habits.sleep.completed = true;
              this.habits.sleep.time = item.value_time;
              console.log('✅ Sleep set to completed with time:', item.value_time);
            } else {
              console.log('❌ Sleep - no valid value_time');
            }
            break;
            
          default:
            console.log('❌ Unknown habit_id:', habitId);
        }
      });
      
      console.log('📊 Mapped server data to habits:', this.habits);
      console.log('🔍 Final habits state after mapping:');
      console.log('- wakeUp.completed:', this.habits.wakeUp.completed);
      console.log('- worship.completed:', this.habits.worship.completed);
      console.log('- exercise.completed:', this.habits.exercise.completed);
      console.log('- healthyFood.completed:', this.habits.healthyFood.completed);
      console.log('- learning.completed:', this.habits.learning.completed);
      console.log('- social.completed:', this.habits.social.completed);
      console.log('- sleep.completed:', this.habits.sleep.completed);
      console.log('🔢 Progress calculation:', this.getCompletedCount(), 'of', this.getTotalCount());
      
      // Parse activities to UI elements
      this.parseActivitiesFromData();
      
      // Force reactivity by triggering Alpine.js update
      this.$el.dispatchEvent(new CustomEvent('habits-updated'));
      
      // Also manually save to ensure persistence
      this.saveData();
    },
    
    // Check if any habits are completed
    hasAnyCompletedHabits() {
      return this.habits.wakeUp.completed || 
             this.habits.worship.completed || 
             this.habits.exercise.completed || 
             this.habits.healthyFood.completed || 
             this.habits.learning.completed || 
             this.habits.social.completed || 
             this.habits.sleep.completed;
    },
    
    resetHabits() {
      this.habits = {
        wakeUp: {
          completed: false,
          time: ''
        },
        worship: {
          completed: false,
          activities: []
        },
        exercise: {
          completed: false,
          duration: '',
          activities: []
        },
        healthyFood: {
          completed: false,
          items: []
        },
        learning: {
          completed: false,
          items: []
        },
        social: {
          completed: false,
          items: []
        },
        sleep: {
          completed: false,
          time: ''
        }
      };
    },
    
    // Get all saved dates (for future features like calendar view)
    getAllSavedDates() {
      const savedDates = [];
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && key.startsWith('siswa-habits-7-kebiasaan-')) {
          const date = key.replace('siswa-habits-7-kebiasaan-', '');
          savedDates.push(date);
        }
      }
      return savedDates.sort();
    },
    
    // Monthly Report Functions
    getCurrentMonth() {
      const today = new Date();
      return today.toISOString().slice(0, 7); // YYYY-MM format
    },

    goToCurrentMonth() {
      this.selectedMonth = this.getCurrentMonth();
      this.loadMonthlyReport();
    },

    loadMonthlyReport() {
      if (!this.selectedMonth) {
        this.selectedMonth = this.getCurrentMonth();
      }
      
      console.log('📊 Loading monthly report for:', this.selectedMonth);
      
      // Clear previous data
      this.monthlyData = {};
      this.monthlyStats = {
        activeDays: 0,
        averageCompletion: 0,
        longestStreak: 0
      };

      // Get all days in the selected month
      const year = parseInt(this.selectedMonth.split('-')[0]);
      const month = parseInt(this.selectedMonth.split('-')[1]);
      const daysInMonth = new Date(year, month, 0).getDate();

      let totalDays = 0;
      let totalCompletion = 0;
      let currentStreak = 0;
      let longestStreak = 0;
      let activeDays = 0;

      // Load data for each day of the month
      for (let day = 1; day <= daysInMonth; day++) {
        const date = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        const key = `siswa-habits-7-kebiasaan-${date}`;
        const dayData = localStorage.getItem(key);

        if (dayData) {
          try {
            const parsedData = JSON.parse(dayData);
            this.monthlyData[date] = parsedData;
            
            const completedCount = this.getCompletedCountForDay(parsedData);
            const percentage = Math.round((completedCount / 7) * 100);
            
            totalDays++;
            totalCompletion += percentage;
            activeDays++;

            // Calculate streak
            if (completedCount > 0) {
              currentStreak++;
              longestStreak = Math.max(longestStreak, currentStreak);
            } else {
              currentStreak = 0;
            }
          } catch (e) {
            console.error('Error parsing data for date:', date, e);
          }
        } else {
          currentStreak = 0;
        }
      }

      // Calculate statistics
      this.monthlyStats.activeDays = activeDays;
      this.monthlyStats.averageCompletion = totalDays > 0 ? Math.round(totalCompletion / totalDays) : 0;
      this.monthlyStats.longestStreak = longestStreak;

      console.log('📊 Monthly stats calculated:', this.monthlyStats);
    },

    // Helper functions for monthly report
    getCompletedCountForDay(dayData) {
      let completed = 0;
      if (dayData.wakeUp && dayData.wakeUp.completed) completed++;
      if (dayData.worship && dayData.worship.completed) completed++;
      if (dayData.exercise && dayData.exercise.completed) completed++;
      if (dayData.healthyFood && dayData.healthyFood.completed) completed++;
      if (dayData.learning && dayData.learning.completed) completed++;
      if (dayData.social && dayData.social.completed) completed++;
      if (dayData.sleep && dayData.sleep.completed) completed++;
      return completed;
    },

    getCompletionPercentage(dayData) {
      const completed = this.getCompletedCountForDay(dayData);
      return Math.round((completed / 7) * 100);
    },

    formatDateForJournal(dateString) {
      const date = new Date(dateString + 'T00:00:00');
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      };
      return date.toLocaleDateString('id-ID', options);
    },

    getWorshipActivitiesText(worship) {
      const activities = [];
      if (worship.activities) {
        if (worship.activities.reading) activities.push('Baca kitab suci');
        if (worship.activities.charity) activities.push('Sedekah');
        if (worship.activities.dua) activities.push('Berdoa');
      }
      return activities.join(', ') || 'Tidak ada aktivitas';
    },

    getPrayerTimesText(worship) {
      const prayers = [];
      if (worship.prayers) {
        if (worship.prayers.subuh) prayers.push('Subuh');
        if (worship.prayers.dzuhur) prayers.push('Dzuhur');
        if (worship.prayers.ashar) prayers.push('Ashar');
        if (worship.prayers.maghrib) prayers.push('Maghrib');
        if (worship.prayers.isya) prayers.push('Isya');
      }
      return prayers.join(', ') || 'Tidak ada sholat';
    },

    // Parse loaded activities data to UI elements (checkboxes)
    parseActivitiesFromData() {
      console.log('🔄 Parsing activities from loaded data...');
      
      // Parse worship activities to prayer times checkboxes
      if (this.habits.worship.activities && this.habits.worship.activities.length > 0) {
        console.log('🔍 Worship activities found:', this.habits.worship.activities);
        this.habits.worship.activities.forEach(activity => {
          console.log('📋 Processing worship activity:', activity);
          // Check for prayer times (stored as notes in database)
          if (activity.includes('Sholat') || activity.includes('sholat')) {
            // Parse prayer names from the activity string
            const prayers = activity.split(/[,;]/); // Split by comma or semicolon
            prayers.forEach(prayer => {
              const cleanPrayer = prayer.trim().toLowerCase();
              if (cleanPrayer.includes('subuh')) {
                this.prayerTimes.subuh = true;
                console.log('✅ Set Subuh prayer to true');
              }
              if (cleanPrayer.includes('dzuhur') || cleanPrayer.includes('zuhur')) {
                this.prayerTimes.dzuhur = true;
                console.log('✅ Set Dzuhur prayer to true');
              }
              if (cleanPrayer.includes('ashar') || cleanPrayer.includes('asar')) {
                this.prayerTimes.ashar = true;
                console.log('✅ Set Ashar prayer to true');
              }
              if (cleanPrayer.includes('maghrib')) {
                this.prayerTimes.maghrib = true;
                console.log('✅ Set Maghrib prayer to true');
              }
              if (cleanPrayer.includes('isya') || cleanPrayer.includes('isha')) {
                this.prayerTimes.isya = true;
                console.log('✅ Set Isya prayer to true');
              }
            });
          }
          
          // Check for other worship activities
          if (activity.includes('Baca Kitab Suci') || activity.includes('baca kitab')) {
            this.worshipActivities.reading = true;
            console.log('✅ Set reading worship to true');
          }
          if (activity.includes('Sedekah') || activity.includes('sedekah') || activity.includes('Amal')) {
            this.worshipActivities.charity = true;
            console.log('✅ Set charity worship to true');
          }
          if (activity.includes('Berdoa') || activity.includes('berdoa')) {
            this.worshipActivities.dua = true;
            console.log('✅ Set prayer worship to true');
          }
          if (activity.includes('Menolong') || activity.includes('menolong')) {
            if (!this.otherWorshipList.includes('Menolong Orang')) {
              this.otherWorshipList.push('Menolong Orang');
              console.log('✅ Added Menolong Orang to otherWorshipList');
            }
          }
        });
      }
      
      // Parse exercise activities
      if (this.habits.exercise.activities && this.habits.exercise.activities.length > 0) {
        console.log('🔍 Exercise activities found:', this.habits.exercise.activities);
        // Add exercise activities to the list
        this.exerciseList = [...this.habits.exercise.activities];
      }
      
      // Parse healthy food items
      if (this.habits.healthyFood.items && this.habits.healthyFood.items.length > 0) {
        console.log('🔍 Healthy food items found:', this.habits.healthyFood.items);
        // Add food items to the list
        this.healthyFoodList = [...this.habits.healthyFood.items];
      }
      
      // Parse learning items
      if (this.habits.learning.items && this.habits.learning.items.length > 0) {
        console.log('🔍 Learning items found:', this.habits.learning.items);
        // Add learning items to the list
        this.learningList = [...this.habits.learning.items];
      }
      
      // Parse social items
      if (this.habits.social.items && this.habits.social.items.length > 0) {
        console.log('🔍 Social items found:', this.habits.social.items);
        // Add social items to the list
        this.socialList = [...this.habits.social.items];
      }
      
      console.log('✅ Activities parsing completed');
    }
  }
}
</script>

<?= $this->endSection() ?>
