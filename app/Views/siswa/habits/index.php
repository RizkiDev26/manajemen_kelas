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
  <!-- Modern Header -->
  <div class="app-header">
    <h1>7 Kebiasaan Anak Indonesia Hebat</h1>
    <p>Bangun karakter hebat melalui kebiasaan positif setiap hari</p>
  </div>

  <!-- Navigation Tabs -->
  <div class="nav-tabs">
    <button class="nav-tab" :class="currentView === 'daily' ? 'active' : ''" @click="currentView = 'daily'">
      📅 Harian
    </button>
    <button class="nav-tab" :class="currentView === 'monthly' ? 'active' : ''" @click="currentView = 'monthly'; loadMonthlyReport()">
      📊 Laporan Bulanan
    </button>
    <a href="<?= base_url('siswa/habits/monthly-report') ?>" class="nav-tab" style="text-decoration: none; color: inherit;">
      📋 Rekap Tabel
    </a>
  </div>

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
               @change="loadDataForDate()"
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
         :class="[habits.wakeUp.completed ? 'completed' : '', !isToday() ? 'disabled' : '']" 
         @click="isToday() ? openWakeUpModal() : showPastDateWarning()">
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
        <div x-show="!habits.wakeUp.time && isToday()">
          <span style="color: #64748b; font-size: 0.875rem; font-style: italic;">Klik untuk set waktu bangun pagi</span>
        </div>
        <div x-show="!habits.wakeUp.time && !isToday()">
          <span style="color: #94a3b8; font-size: 0.875rem;">Tidak ada data</span>
        </div>
      </div>
      
      <div x-show="!isToday()" class="past-date-overlay">
        <i class="fas fa-eye" style="font-size: 1.5rem; color: #64748b;"></i>
      </div>
    </div>

    <!-- 2. Beribadah -->
    <div class="habit-card card-worship" 
         :class="[habits.worship.completed ? 'completed' : '', !isToday() ? 'disabled' : '']" 
         @click="isToday() ? openWorshipModal() : showPastDateWarning()">
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
        <div x-show="!habits.worship.completed && isToday()">
          <span style="color: #64748b; font-size: 0.875rem; font-style: italic;">Klik untuk mencatat ibadah</span>
        </div>
        <div x-show="!habits.worship.completed && !isToday()">
          <span style="color: #94a3b8; font-size: 0.875rem;">Tidak ada data</span>
        </div>
      </div>
      
      <div x-show="!isToday()" class="past-date-overlay">
        <i class="fas fa-eye" style="font-size: 1.5rem; color: #64748b;"></i>
      </div>
    </div>

    <!-- 3. Berolahraga -->
    <div class="habit-card card-exercise" 
         :class="[habits.exercise.completed ? 'completed' : '', !isToday() ? 'disabled' : '']" 
         @click="isToday() ? openExerciseModal() : showPastDateWarning()">
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
        <div x-show="!habits.exercise.completed && isToday()">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat olahraga</span>
        </div>
        <div x-show="!habits.exercise.completed && !isToday()">
          <span style="color: #9ca3af;">Tidak ada data</span>
        </div>
      </div>
      
      <div x-show="!isToday()" class="past-date-overlay">
        <i class="fas fa-eye" style="font-size: 1.5rem; color: #6b7280;"></i>
      </div>
    </div>

    <!-- 4. Makan Sehat dan Bergizi -->
    <div class="habit-card card-healthy-food" 
         :class="[habits.healthyFood.completed ? 'completed' : '', !isToday() ? 'disabled' : '']" 
         @click="isToday() ? openHealthyFoodModal() : showPastDateWarning()">
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
        <div x-show="habits.healthyFood.items.length === 0 && isToday()">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat makanan sehat</span>
        </div>
        <div x-show="habits.healthyFood.items.length === 0 && !isToday()">
          <span style="color: #9ca3af;">Tidak ada data</span>
        </div>
      </div>
      
      <div x-show="!isToday()" class="past-date-overlay">
        <i class="fas fa-eye" style="font-size: 1.5rem; color: #6b7280;"></i>
      </div>
    </div>

    <!-- 5. Gemar Belajar -->
    <div class="habit-card card-learning" 
         :class="[habits.learning.completed ? 'completed' : '', !isToday() ? 'disabled' : '']" 
         @click="isToday() ? openLearningModal() : showPastDateWarning()">
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
        <div x-show="habits.learning.items.length === 0 && isToday()">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat pembelajaran hari ini</span>
        </div>
        <div x-show="habits.learning.items.length === 0 && !isToday()">
          <span style="color: #9ca3af;">Tidak ada data</span>
        </div>
      </div>
      
      <div x-show="!isToday()" class="past-date-overlay">
        <i class="fas fa-eye" style="font-size: 1.5rem; color: #6b7280;"></i>
      </div>
    </div>

    <!-- 6. Bermasyarakat -->
    <div class="habit-card card-social" 
         :class="[habits.social.completed ? 'completed' : '', !isToday() ? 'disabled' : '']" 
         @click="isToday() ? openSocialModal() : showPastDateWarning()">
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
        <div x-show="habits.social.items.length === 0 && isToday()">
          <span style="color: #6b7280; font-style: italic;">Klik untuk mencatat kegiatan sosial</span>
        </div>
        <div x-show="habits.social.items.length === 0 && !isToday()">
          <span style="color: #9ca3af;">Tidak ada data</span>
        </div>
      </div>
      
      <div x-show="!isToday()" class="past-date-overlay">
        <i class="fas fa-eye" style="font-size: 1.5rem; color: #6b7280;"></i>
      </div>
    </div>

    <!-- 7. Tidur Cepat -->
    <div class="habit-card card-sleep" 
         :class="[habits.sleep.completed ? 'completed' : '', !isToday() ? 'disabled' : '']" 
         @click="isToday() ? openSleepModal() : showPastDateWarning()">
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
        <div x-show="!habits.sleep.time && isToday()">
          <span style="color: #6b7280; font-style: italic;">Klik untuk set waktu tidur</span>
        </div>
        <div x-show="!habits.sleep.time && !isToday()">
          <span style="color: #9ca3af;">Tidak ada data</span>
        </div>
      </div>
      
      <div x-show="!isToday()" class="past-date-overlay">
        <i class="fas fa-eye" style="font-size: 1.5rem; color: #6b7280;"></i>
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
      this.selectedDate = this.getCurrentDate();
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
          // Check for prayer times
          <?php if ($isIslam): ?>
          if (activity.startsWith('Sholat: ')) {
            const prayers = activity.replace('Sholat: ', '').split(', ');
            prayers.forEach(prayer => {
              switch(prayer.trim()) {
                case 'Subuh':
                  this.prayerTimes.subuh = true;
                  break;
                case 'Dzuhur':
                  this.prayerTimes.dzuhur = true;
                  break;
                case 'Ashar':
                  this.prayerTimes.ashar = true;
                  break;
                case 'Maghrib':
                  this.prayerTimes.maghrib = true;
                  break;
                case 'Isya':
                  this.prayerTimes.isya = true;
                  break;
              }
            });
          } else 
          <?php endif; ?>
          if (activity === 'Baca Kitab Suci') {
            this.worshipActivities.reading = true;
          } else if (activity === 'Sedekah/Amal') {
            this.worshipActivities.charity = true;
          } else if (activity === 'Berdoa') {
            this.worshipActivities.dua = true;
          } else {
            // Custom worship activity
            if (!activity.startsWith('Sholat: ')) {
              this.otherWorshipList.push(activity);
            }
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
      console.log(`✅ Data saved for ${this.selectedDate}`);
    },
    
    loadDataForDate() {
      const storageKey = `siswa-habits-7-kebiasaan-${this.selectedDate}`;
      const saved = localStorage.getItem(storageKey);
      
      if (saved) {
        try {
          this.habits = JSON.parse(saved);
          console.log(`📊 Loaded data for ${this.selectedDate}:`, this.habits);
        } catch (e) {
          console.log('❌ Error loading data:', e);
          this.resetHabits();
        }
      } else {
        console.log(`📅 No data found for ${this.selectedDate}, starting fresh`);
        this.resetHabits();
      }
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
    
    // Warning for past dates
    showPastDateWarning() {
      if (!this.isToday()) {
        alert('🔒 Anda sedang melihat data kebiasaan masa lalu. Data hanya bisa diedit pada hari yang sama.');
      }
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
    }
  }
}
</script>

<?= $this->endSection() ?>
