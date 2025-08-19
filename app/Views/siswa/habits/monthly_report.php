<?= $this->extend('layouts/siswa_layout') ?>

<?= $this->section('title') ?>Rekap Bulanan 7 Kebiasaan<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
/* Modern Design System */
body {
  background: #f1f5f9;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  color: #1e293b;
}

.monthly-container {
  max-width: 1600px;
  margin: 0 auto;
  padding: 1rem;
}

/* Header */
.monthly-header {
  text-align: center;
  margin-bottom: 2rem;
  padding: 2rem 1rem;
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  border-radius: 20px;
  color: white;
  box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
}

.monthly-header h1 {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
}

.monthly-header p {
  font-size: 1.1rem;
  opacity: 0.9;
  margin: 0;
}

/* Month Selector */
.month-selector {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.month-selector-content {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.month-selector label {
  font-weight: 600;
  color: #1e293b;
  font-size: 1rem;
}

.month-selector input[type="month"] {
  padding: 0.75rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  font-size: 1rem;
  cursor: pointer;
  transition: border-color 0.3s ease;
}

.month-selector input[type="month"]:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.btn-current-month {
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  border: none;
  border-radius: 10px;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.btn-current-month:hover {
  transform: scale(1.05);
}

/* Summary Stats */
.summary-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  padding: 1.5rem;
  border-radius: 16px;
  color: white;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card.total-days {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.stat-card.average-completion {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-card.perfect-days {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-card.consistency {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.stat-card h3 {
  margin: 0 0 0.5rem 0;
  font-size: 1rem;
  font-weight: 600;
  opacity: 0.9;
}

.stat-card .stat-value {
  margin: 0;
  font-size: 2rem;
  font-weight: 700;
}

/* Table Container */
.table-container {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  overflow-x: auto;
}

.table-title {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #1e293b;
  font-size: 1.5rem;
  font-weight: 700;
}

/* Monthly Report Table */
.monthly-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.85rem;
  min-width: 900px;
}

.monthly-table th,
.monthly-table td {
  border: 1px solid #e2e8f0;
  padding: 0.6rem 0.4rem;
  text-align: center;
  vertical-align: middle;
}

.monthly-table th {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  font-weight: 600;
  color: #475569;
  position: sticky;
  top: 0;
  z-index: 10;
}

.monthly-table th.date-header {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  color: white;
  min-width: 90px;
  font-size: 0.8rem;
}

.monthly-table th.habit-header {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  min-width: 100px;
  font-size: 0.75rem;
  line-height: 1.2;
  padding: 0.5rem 0.3rem;
}

/* Date column */
.date-cell {
  font-weight: 600;
  background: #f8fafc;
  color: #475569;
  min-width: 90px;
  font-size: 0.8rem;
  line-height: 1.2;
}

.date-cell.weekend {
  background: #fef2f2;
  color: #dc2626;
}

.date-cell.today {
  background: #dbeafe;
  color: #1d4ed8;
  font-weight: 700;
}

/* Habit cells */
.habit-cell {
  position: relative;
  cursor: pointer;
  transition: all 0.2s ease;
  min-width: 55px;
  height: 45px;
  font-size: 1rem;
}

.habit-cell:hover {
  background: #f1f5f9;
  transform: scale(1.08);
  z-index: 5;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.habit-cell.completed {
  background: #dcfce7;
  color: #166534;
}

.habit-cell.completed::after {
  content: "✓";
  font-size: 1.1rem;
  font-weight: bold;
  color: #059669;
}

.habit-cell.not-completed {
  background: #fef2f2;
  color: #991b1b;
}

.habit-cell.not-completed::after {
  content: "✗";
  font-size: 1.1rem;
  font-weight: bold;
  color: #dc2626;
}

.habit-cell.no-data {
  background: #f8fafc;
  color: #94a3b8;
}

.habit-cell.no-data::after {
  content: "-";
  font-size: 1.1rem;
  color: #94a3b8;
}

/* Future dates */
.habit-cell.future {
  background: #f8fafc;
  color: #cbd5e1;
  cursor: not-allowed;
}

.habit-cell.future::after {
  content: "";
}

/* Legend */
.legend {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin-top: 1.5rem;
  flex-wrap: wrap;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.legend-color {
  width: 20px;
  height: 20px;
  border-radius: 4px;
  border: 1px solid #e2e8f0;
}

.legend-color.completed {
  background: #dcfce7;
}

.legend-color.not-completed {
  background: #fef2f2;
}

.legend-color.no-data {
  background: #f8fafc;
}

/* Responsive Design */
@media (max-width: 768px) {
  .monthly-container {
    padding: 0.5rem;
  }
  
  .monthly-header h1 {
    font-size: 1.8rem;
  }
  
  .monthly-header p {
    font-size: 1rem;
  }
  
  .table-container {
    padding: 1rem;
    margin: 0 -0.5rem;
    border-radius: 8px;
  }
  
  .monthly-table {
    font-size: 0.75rem;
    min-width: 700px;
  }
  
  .monthly-table th,
  .monthly-table td {
    padding: 0.4rem 0.2rem;
  }
  
  .monthly-table th.date-header {
    min-width: 70px;
    font-size: 0.7rem;
  }
  
  .monthly-table th.habit-header {
    min-width: 80px;
    font-size: 0.65rem;
    padding: 0.4rem 0.2rem;
  }
  
  .date-cell {
    min-width: 70px;
    font-size: 0.7rem;
  }
  
  .habit-cell {
    min-width: 40px;
    height: 40px;
    font-size: 0.9rem;
  }
  
  .habit-cell:hover {
    transform: scale(1.15);
  }
  
  .legend {
    gap: 0.5rem;
  }
  
  .legend-item {
    font-size: 0.8rem;
  }
  
  .summary-stats {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }
  
  .stat-card {
    padding: 1rem;
  }
  
  .stat-card .stat-value {
    font-size: 1.5rem;
  }
  
  .export-buttons {
    gap: 0.5rem;
  }
  
  .btn-export {
    padding: 0.6rem 1rem;
    font-size: 0.8rem;
  }
  
  .habit-detail-content {
    padding: 1.5rem;
    margin: 1rem;
  }
  
  .habit-detail-title {
    font-size: 1.2rem;
  }
}

@media (max-width: 480px) {
  .monthly-header h1 {
    font-size: 1.5rem;
  }
  
  .monthly-table {
    font-size: 0.7rem;
    min-width: 600px;
  }
  
  .monthly-table th.habit-header {
    min-width: 70px;
    font-size: 0.6rem;
  }
  
  .date-cell {
    min-width: 60px;
    font-size: 0.65rem;
  }
  
  .habit-cell {
    min-width: 35px;
    height: 35px;
    font-size: 0.8rem;
  }
  
  .summary-stats {
    grid-template-columns: 1fr;
  }
  
  .export-buttons {
    flex-direction: column;
    align-items: center;
  }
  
  .btn-export {
    width: 100%;
    max-width: 200px;
  }
  
  .habit-detail-content {
    margin: 0.5rem;
    padding: 1rem;
  }
}

/* Print Styles */
@media print {
  .month-selector,
  .btn-current-month {
    display: none;
  }
  
  .table-container {
    box-shadow: none;
    border: 1px solid #e2e8f0;
  }
  
  .monthly-table th.habit-header,
  .monthly-table th.date-header {
    background: #f8fafc !important;
    color: #475569 !important;
  }
}

/* Loading State */
.loading {
  text-align: center;
  padding: 3rem;
  color: #64748b;
}

.loading-spinner {
  display: inline-block;
  width: 40px;
  height: 40px;
  border: 4px solid #e2e8f0;
  border-radius: 50%;
  border-top-color: #6366f1;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Export Buttons */
.export-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.btn-export {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.btn-export.pdf {
  background: #dc2626;
  color: white;
}

.btn-export.excel {
  background: #059669;
  color: white;
}

.btn-export.print {
  background: #6366f1;
  color: white;
}

.btn-export:hover {
  transform: translateY(-2px);
}

/* Habit Icons */
.habit-icon {
  font-size: 0.8rem;
  margin-right: 0.25rem;
}

/* Modal for habit details */
.habit-detail-modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(8px);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}

.habit-detail-modal.show {
  display: flex !important;
}

.habit-detail-content {
  background: white;
  border-radius: 15px;
  padding: 2rem;
  max-width: 500px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  animation: modalSlideIn 0.3s ease-out;
}

.habit-detail-close {
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
  font-size: 1.2rem;
}

.habit-detail-close:hover {
  background: #e5e7eb;
}

.habit-detail-header {
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #e2e8f0;
}

.habit-detail-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.habit-detail-date {
  color: #64748b;
  font-size: 1rem;
}

.habit-detail-status {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.habit-detail-status.completed {
  background: #dcfce7;
  color: #166534;
}

.habit-detail-status.not-completed {
  background: #fef2f2;
  color: #991b1b;
}

.habit-detail-status.no-data {
  background: #f8fafc;
  color: #64748b;
}

.habit-detail-info {
  background: #f8fafc;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
}

.habit-detail-info h4 {
  margin: 0 0 0.5rem 0;
  color: #1e293b;
  font-size: 1rem;
}

.habit-detail-info p {
  margin: 0.25rem 0;
  color: #475569;
  font-size: 0.9rem;
}
</style>

<div id="monthlyReportApp" x-data="monthlyReportApp()" x-init="init()">
  <!-- Header -->
  <div class="monthly-header">
    <h1>📊 Rekap Bulanan 7 Kebiasaan</h1>
    <p>Laporan Progress Kebiasaan Harian dalam Bentuk Tabel</p>
    <div style="margin-top: 1rem;">
      <a href="<?= base_url('siswa/habits') ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; color: white; text-decoration: none; background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 8px; backdrop-filter: blur(10px);">
        ← Kembali ke Habits
      </a>
    </div>
  </div>

  <!-- Month Selector -->
  <div class="month-selector">
    <div class="month-selector-content">
      <label>Pilih Bulan:</label>
      <input type="month" 
             x-model="selectedMonth" 
             :max="getCurrentMonth()"
             @change="loadMonthlyData()">
      <button class="btn-current-month" @click="goToCurrentMonth()">
        Bulan Ini
      </button>
    </div>
  </div>

  <!-- Summary Statistics -->
  <div class="summary-stats">
    <div class="stat-card total-days">
      <h3>Total Hari Aktif</h3>
      <p class="stat-value" x-text="stats.totalActiveDays + ' hari'"></p>
    </div>
    <div class="stat-card average-completion">
      <h3>Rata-rata Penyelesaian</h3>
      <p class="stat-value" x-text="stats.averageCompletion + '%'"></p>
    </div>
    <div class="stat-card perfect-days">
      <h3>Hari Sempurna (7/7)</h3>
      <p class="stat-value" x-text="stats.perfectDays + ' hari'"></p>
    </div>
    <div class="stat-card consistency">
      <h3>Tingkat Konsistensi</h3>
      <p class="stat-value" x-text="stats.consistency + '%'"></p>
    </div>
  </div>

  <!-- Habit Statistics -->
  <div style="background: white; border-radius: 16px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
    <h3 style="text-align: center; margin-bottom: 1.5rem; color: #1e293b; font-size: 1.3rem; font-weight: 700;">📈 Statistik Per Kebiasaan</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
      <template x-for="(habit, index) in habits" :key="index">
        <div style="background: #f8fafc; border-radius: 12px; padding: 1rem; border-left: 4px solid #6366f1;">
          <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <span style="font-size: 1.2rem;" x-text="habit.icon"></span>
            <h4 style="margin: 0; color: #1e293b; font-size: 0.9rem; font-weight: 600;" x-text="habit.name"></h4>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="color: #64748b; font-size: 0.8rem;">Tingkat Keberhasilan</span>
            <span style="color: #059669; font-weight: 700; font-size: 1rem;" x-text="getHabitSuccessRate(habit.id) + '%'"></span>
          </div>
          <div style="background: #e2e8f0; height: 6px; border-radius: 3px; margin-top: 0.5rem; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); height: 100%; border-radius: 3px; transition: width 0.5s ease;" :style="`width: ${getHabitSuccessRate(habit.id)}%`"></div>
          </div>
        </div>
      </template>
    </div>
  </div>

  <!-- Export Buttons -->
  <div class="export-buttons">
    <button class="btn-export pdf" @click="exportToPDF()">
      📄 Export PDF
    </button>
    <button class="btn-export excel" @click="exportToExcel()">
      📊 Export Excel
    </button>
    <button class="btn-export print" @click="printReport()">
      🖨️ Print
    </button>
  </div>

  <!-- Loading State -->
  <div x-show="loading" class="loading">
    <div class="loading-spinner"></div>
    <p>Memuat data rekap bulanan...</p>
  </div>

  <!-- Table Container -->
  <div x-show="!loading" class="table-container">
    <h2 class="table-title" x-text="getTableTitle()"></h2>
    
    <div style="overflow-x: auto;">
      <table class="monthly-table" id="monthlyTable">
        <thead>
          <tr>
            <th class="date-header">Tanggal</th>
            <th class="habit-header">
              <span class="habit-icon">🌅</span>
              Bangun Pagi
            </th>
            <th class="habit-header">
              <span class="habit-icon">🤲</span>
              Beribadah
            </th>
            <th class="habit-header">
              <span class="habit-icon">⚽</span>
              Berolahraga
            </th>
            <th class="habit-header">
              <span class="habit-icon">🥗</span>
              Makan Sehat
            </th>
            <th class="habit-header">
              <span class="habit-icon">📚</span>
              Gemar Belajar
            </th>
            <th class="habit-header">
              <span class="habit-icon">🤝</span>
              Bermasyarakat
            </th>
            <th class="habit-header">
              <span class="habit-icon">🌙</span>
              Tidur Cepat
            </th>
          </tr>
        </thead>
        <tbody>
          <template x-for="day in daysInMonth" :key="day">
            <tr>
              <!-- Date Column -->
              <td class="date-cell" 
                  :class="[
                    isWeekend(day) ? 'weekend' : '',
                    isToday(day) ? 'today' : ''
                  ]"
                  x-text="formatDateForTable(day)">
              </td>
              
              <!-- Habit Columns -->
              <template x-for="(habit, habitIndex) in habits" :key="habitIndex">
                <td class="habit-cell" 
                    :class="getHabitCellClass(day, habitIndex + 1)"
                    :title="getHabitCellTitle(day, habitIndex + 1)"
                    @click="showHabitDetail(day, habitIndex + 1)">
                </td>
              </template>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <!-- Legend -->
    <div class="legend">
      <div class="legend-item">
        <div class="legend-color completed"></div>
        <span>Selesai ✓</span>
      </div>
      <div class="legend-item">
        <div class="legend-color not-completed"></div>
        <span>Belum Selesai ✗</span>
      </div>
      <div class="legend-item">
        <div class="legend-color no-data"></div>
        <span>Tidak Ada Data -</span>
      </div>
    </div>
  </div>

  <!-- Habit Detail Modal -->
  <div class="habit-detail-modal" :class="showDetailModal ? 'show' : ''" @click.self="closeDetailModal()">
    <div class="habit-detail-content">
      <button class="habit-detail-close" @click="closeDetailModal()">✕</button>
      
      <div class="habit-detail-header">
        <h2 class="habit-detail-title" x-text="selectedHabitDetail.habitName"></h2>
        <p class="habit-detail-date" x-text="selectedHabitDetail.dateText"></p>
      </div>

      <div class="habit-detail-status" :class="selectedHabitDetail.statusClass">
        <span x-text="selectedHabitDetail.statusIcon"></span>
        <span x-text="selectedHabitDetail.statusText"></span>
      </div>

      <div x-show="selectedHabitDetail.hasData" class="habit-detail-info">
        <h4>📋 Detail Aktivitas</h4>
        <div x-show="selectedHabitDetail.time">
          <p><strong>⏰ Waktu:</strong> <span x-text="selectedHabitDetail.time"></span></p>
        </div>
        <div x-show="selectedHabitDetail.duration">
          <p><strong>⏱️ Durasi:</strong> <span x-text="selectedHabitDetail.duration + ' menit'"></span></p>
        </div>
        <div x-show="selectedHabitDetail.notes">
          <p><strong>📝 Catatan:</strong> <span x-text="selectedHabitDetail.notes"></span></p>
        </div>
      </div>

      <div x-show="!selectedHabitDetail.hasData" class="habit-detail-info">
        <h4>ℹ️ Informasi</h4>
        <p x-show="selectedHabitDetail.isFuture">Tanggal ini belum tiba. Data belum bisa diinput.</p>
        <p x-show="!selectedHabitDetail.isFuture && !selectedHabitDetail.hasData">Tidak ada data yang tercatat untuk tanggal ini. Mulai catat kebiasaan Anda untuk membangun karakter yang lebih baik!</p>
      </div>

      <!-- Tips untuk setiap kebiasaan -->
      <div class="habit-detail-info" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b;">
        <h4>💡 Tips</h4>
        <div x-show="selectedHabitDetail.habitName === 'Bangun Pagi'">
          <p>Bangun pagi membantu memulai hari dengan semangat. Cobalah tidur lebih awal dan siapkan alarm.</p>
        </div>
        <div x-show="selectedHabitDetail.habitName === 'Beribadah'">
          <p>Beribadah secara rutin membantu memperkuat karakter spiritual dan memberikan ketenangan batin.</p>
        </div>
        <div x-show="selectedHabitDetail.habitName === 'Berolahraga'">
          <p>Olahraga minimal 30 menit sehari dapat meningkatkan kesehatan fisik dan mental Anda.</p>
        </div>
        <div x-show="selectedHabitDetail.habitName === 'Makan Sehat'">
          <p>Konsumsi makanan bergizi seimbang dengan sayur, buah, dan protein untuk tumbuh kembang optimal.</p>
        </div>
        <div x-show="selectedHabitDetail.habitName === 'Gemar Belajar'">
          <p>Belajar hal baru setiap hari membantu mengembangkan potensi diri dan memperluas wawasan.</p>
        </div>
        <div x-show="selectedHabitDetail.habitName === 'Bermasyarakat'">
          <p>Berinteraksi dan membantu sesama membangun rasa empati dan keterampilan sosial yang baik.</p>
        </div>
        <div x-show="selectedHabitDetail.habitName === 'Tidur Cepat'">
          <p>Tidur cukup dan teratur penting untuk pemulihan tubuh dan konsentrasi belajar keesokan harinya.</p>
        </div>
      </div>

      <div style="text-align: center; margin-top: 1.5rem;">
        <button @click="closeDetailModal()" style="padding: 0.75rem 2rem; background: #6366f1; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function monthlyReportApp() {
  return {
    // Data properties
    selectedMonth: '',
    monthlyData: {},
    loading: false,
    daysInMonth: [],
    habits: [
      { id: 1, name: 'Bangun Pagi', icon: '🌅' },
      { id: 2, name: 'Beribadah', icon: '🤲' },
      { id: 3, name: 'Berolahraga', icon: '⚽' },
      { id: 4, name: 'Makan Sehat', icon: '🥗' },
      { id: 5, name: 'Gemar Belajar', icon: '📚' },
      { id: 6, name: 'Bermasyarakat', icon: '🤝' },
      { id: 7, name: 'Tidur Cepat', icon: '🌙' }
    ],
    
    // Statistics
    stats: {
      totalActiveDays: 0,
      averageCompletion: 0,
      perfectDays: 0,
      consistency: 0
    },
    
    // Modal for habit details
    showDetailModal: false,
    selectedHabitDetail: {
      habitName: '',
      dateText: '',
      statusClass: '',
      statusIcon: '',
      statusText: '',
      hasData: false,
      isFuture: false,
      time: '',
      duration: '',
      notes: ''
    },
    
    // Initialize
    init() {
      console.log('📊 Monthly Report App initialized');
      this.selectedMonth = this.getCurrentMonth();
      this.loadMonthlyData();
    },
    
    // Get current month in YYYY-MM format
    getCurrentMonth() {
      const now = new Date();
      return now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
    },
    
    // Navigate to current month
    goToCurrentMonth() {
      this.selectedMonth = this.getCurrentMonth();
      this.loadMonthlyData();
    },
    
    // Generate table title
    getTableTitle() {
      if (!this.selectedMonth) return 'Rekap Bulanan';
      
      const [year, month] = this.selectedMonth.split('-');
      const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      
      return `Rekap Kebiasaan ${monthNames[parseInt(month) - 1]} ${year}`;
    },
    
    // Load monthly data
    async loadMonthlyData() {
      if (!this.selectedMonth) return;
      
      this.loading = true;
      
      try {
        // Generate days for the month
        this.generateDaysInMonth();
        
        // Fetch data from server
        const response = await fetch(`<?= base_url('siswa/habits/monthly-data') ?>?month=${this.selectedMonth}`);
        
        if (!response.ok) {
          throw new Error('Failed to fetch monthly data');
        }
        
        const result = await response.json();
        this.monthlyData = result.data || {};
        
        console.log('Monthly data loaded:', this.monthlyData);
        console.log('Data keys:', Object.keys(this.monthlyData));
        
        // Calculate statistics
        this.calculateStatistics();
        
      } catch (error) {
        console.error('Error loading monthly data:', error);
        // Only generate mock data in development mode
        console.log('Failed to load real data, using empty dataset');
        this.monthlyData = {};
        this.calculateStatistics();
      } finally {
        this.loading = false;
      }
    },
    
    // Generate days in selected month
    generateDaysInMonth() {
      if (!this.selectedMonth) return;
      
      const [year, month] = this.selectedMonth.split('-');
      const daysInMonth = new Date(year, month, 0).getDate();
      
      this.daysInMonth = [];
      for (let day = 1; day <= daysInMonth; day++) {
        this.daysInMonth.push(day);
      }
    },
    
    // Check if day is weekend
    isWeekend(day) {
      const [year, month] = this.selectedMonth.split('-');
      const date = new Date(year, month - 1, day);
      const dayOfWeek = date.getDay();
      return dayOfWeek === 0 || dayOfWeek === 6; // Sunday or Saturday
    },
    
    // Check if day is today
    isToday(day) {
      const [year, month] = this.selectedMonth.split('-');
      const today = new Date();
      
      return today.getFullYear() == year && 
             (today.getMonth() + 1) == month && 
             today.getDate() == day;
    },
    
    // Check if day is in future
    isFuture(day) {
      const [year, month] = this.selectedMonth.split('-');
      const date = new Date(year, month - 1, day);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      return date > today;
    },
    
    // Format date for table display
    formatDateForTable(day) {
      const [year, month] = this.selectedMonth.split('-');
      const date = new Date(year, month - 1, day);
      const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
      
      return `${day}\n${dayNames[date.getDay()]}`;
    },
    
    // Get habit cell CSS class
    getHabitCellClass(day, habitId) {
      if (this.isFuture(day)) {
        return 'future';
      }
      
      const dateKey = this.getDateKey(day);
      const dayData = this.monthlyData[dateKey];
      
      if (!dayData) {
        return 'no-data';
      }
      
      const habitData = dayData[`habit_${habitId}`];
      
      if (!habitData) {
        return 'no-data';
      }
      
      return habitData.completed ? 'completed' : 'not-completed';
    },
    
    // Get habit cell title (tooltip)
    getHabitCellTitle(day, habitId) {
      const habitName = this.habits[habitId - 1]?.name || 'Kebiasaan';
      
      if (this.isFuture(day)) {
        return `${habitName} - Belum tiba`;
      }
      
      const dateKey = this.getDateKey(day);
      const dayData = this.monthlyData[dateKey];
      
      if (!dayData) {
        return `${habitName} - Tidak ada data`;
      }
      
      const habitData = dayData[`habit_${habitId}`];
      
      if (!habitData) {
        return `${habitName} - Tidak ada data`;
      }
      
      const status = habitData.completed ? 'Selesai' : 'Belum selesai';
      let details = '';
      
      if (habitData.time) {
        details += ` (${habitData.time})`;
      }
      if (habitData.duration) {
        details += ` (${habitData.duration} menit)`;
      }
      if (habitData.notes) {
        details += ` - ${habitData.notes}`;
      }
      
      return `${habitName} - ${status}${details}`;
    },
    
    // Get date key for data lookup
    getDateKey(day) {
      const [year, month] = this.selectedMonth.split('-');
      return `${year}-${month}-${String(day).padStart(2, '0')}`;
    },
    
    // Show habit detail (enhanced with modal)
    showHabitDetail(day, habitId) {
      if (this.isFuture(day)) {
        this.selectedHabitDetail = {
          habitName: this.habits[habitId - 1]?.name || 'Kebiasaan',
          dateText: this.formatDateForDetail(day),
          statusClass: 'no-data',
          statusIcon: '📅',
          statusText: 'Belum Tiba',
          hasData: false,
          isFuture: true,
          time: '',
          duration: '',
          notes: ''
        };
        this.showDetailModal = true;
        return;
      }
      
      const dateKey = this.getDateKey(day);
      const dayData = this.monthlyData[dateKey];
      const habitName = this.habits[habitId - 1]?.name || 'Kebiasaan';
      
      if (!dayData || !dayData[`habit_${habitId}`]) {
        this.selectedHabitDetail = {
          habitName: habitName,
          dateText: this.formatDateForDetail(day),
          statusClass: 'no-data',
          statusIcon: '❌',
          statusText: 'Tidak Ada Data',
          hasData: false,
          isFuture: false,
          time: '',
          duration: '',
          notes: ''
        };
        this.showDetailModal = true;
        return;
      }
      
      const habitData = dayData[`habit_${habitId}`];
      
      this.selectedHabitDetail = {
        habitName: habitName,
        dateText: this.formatDateForDetail(day),
        statusClass: habitData.completed ? 'completed' : 'not-completed',
        statusIcon: habitData.completed ? '✅' : '❌',
        statusText: habitData.completed ? 'Selesai' : 'Belum Selesai',
        hasData: true,
        isFuture: false,
        time: habitData.time || '',
        duration: habitData.duration || '',
        notes: habitData.notes || ''
      };
      
      this.showDetailModal = true;
    },
    
    // Format date for detail modal
    formatDateForDetail(day) {
      const [year, month] = this.selectedMonth.split('-');
      const date = new Date(year, month - 1, day);
      const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      
      return `${dayNames[date.getDay()]}, ${day} ${monthNames[parseInt(month) - 1]} ${year}`;
    },
    
    // Close detail modal
    closeDetailModal() {
      this.showDetailModal = false;
    },
    
    // Calculate statistics
    calculateStatistics() {
      let totalDays = 0;
      let activeDays = 0;
      let perfectDays = 0;
      let totalCompletions = 0;
      let totalPossibleCompletions = 0;
      
      this.daysInMonth.forEach(day => {
        if (this.isFuture(day)) return;
        
        totalDays++;
        const dateKey = this.getDateKey(day);
        const dayData = this.monthlyData[dateKey];
        
        if (!dayData) return;
        
        let dayCompletions = 0;
        let dayHasData = false;
        
        for (let habitId = 1; habitId <= 7; habitId++) {
          const habitData = dayData[`habit_${habitId}`];
          if (habitData) {
            dayHasData = true;
            totalPossibleCompletions++;
            if (habitData.completed) {
              dayCompletions++;
              totalCompletions++;
            }
          }
        }
        
        if (dayHasData) {
          activeDays++;
          if (dayCompletions === 7) {
            perfectDays++;
          }
        }
      });
      
      this.stats = {
        totalActiveDays: activeDays,
        averageCompletion: totalPossibleCompletions > 0 ? Math.round((totalCompletions / totalPossibleCompletions) * 100) : 0,
        perfectDays: perfectDays,
        consistency: totalDays > 0 ? Math.round((activeDays / totalDays) * 100) : 0
      };
    },
    
    // Get habit success rate
    getHabitSuccessRate(habitId) {
      let completedCount = 0;
      let totalCount = 0;
      
      this.daysInMonth.forEach(day => {
        if (this.isFuture(day)) return;
        
        const dateKey = this.getDateKey(day);
        const dayData = this.monthlyData[dateKey];
        
        if (dayData && dayData[`habit_${habitId}`]) {
          totalCount++;
          if (dayData[`habit_${habitId}`].completed) {
            completedCount++;
          }
        }
      });
      
      return totalCount > 0 ? Math.round((completedCount / totalCount) * 100) : 0;
    },
    
    // Generate mock data for demonstration
    generateMockData() {
      this.monthlyData = {};
      
      this.daysInMonth.forEach(day => {
        if (this.isFuture(day)) return;
        
        // Simulate some days having data
        if (Math.random() > 0.3) {
          const dateKey = this.getDateKey(day);
          this.monthlyData[dateKey] = {};
          
          // Simulate habit completions
          for (let habitId = 1; habitId <= 7; habitId++) {
            const completed = Math.random() > 0.3;
            this.monthlyData[dateKey][`habit_${habitId}`] = {
              completed: completed,
              time: habitId === 1 || habitId === 7 ? '06:00' : null,
              duration: habitId === 3 ? '30' : null,
              notes: completed ? 'Tercatat' : null
            };
          }
        }
      });
    },
    
    // Export functions
    async exportToPDF() {
      if (Object.keys(this.monthlyData).length === 0) {
        alert('Tidak ada data untuk diekspor');
        return;
      }
      alert('Fitur export PDF akan segera tersedia.\n\nUntuk sementara, gunakan Print untuk menyimpan sebagai PDF.');
    },
    
    async exportToExcel() {
      if (Object.keys(this.monthlyData).length === 0) {
        alert('Tidak ada data untuk diekspor');
        return;
      }
      
      // Prepare data for Excel export
      const excelData = [];
      
      // Add headers
      const headers = ['Tanggal', 'Bangun Pagi', 'Beribadah', 'Berolahraga', 'Makan Sehat', 'Gemar Belajar', 'Bermasyarakat', 'Tidur Cepat'];
      excelData.push(headers);
      
      // Add data rows
      this.daysInMonth.forEach(day => {
        if (this.isFuture(day)) return;
        
        const row = [this.formatDateForTable(day).replace('\n', ' ')];
        
        for (let habitId = 1; habitId <= 7; habitId++) {
          const dateKey = this.getDateKey(day);
          const dayData = this.monthlyData[dateKey];
          
          if (!dayData || !dayData[`habit_${habitId}`]) {
            row.push('-');
          } else {
            const habitData = dayData[`habit_${habitId}`];
            row.push(habitData.completed ? '✓' : '✗');
          }
        }
        
        excelData.push(row);
      });
      
      // Create CSV content
      const csvContent = excelData.map(row => 
        row.map(cell => `"${cell}"`).join(',')
      ).join('\n');
      
      // Download CSV file
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);
      link.setAttribute('href', url);
      link.setAttribute('download', `rekap_habits_${this.selectedMonth}.csv`);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    
    printReport() {
      // Hide export buttons and modal before printing
      const exportButtons = document.querySelector('.export-buttons');
      const modal = document.querySelector('.habit-detail-modal');
      
      if (exportButtons) exportButtons.style.display = 'none';
      if (modal) modal.style.display = 'none';
      
      window.print();
      
      // Restore buttons after printing
      setTimeout(() => {
        if (exportButtons) exportButtons.style.display = 'flex';
        if (modal && this.showDetailModal) modal.style.display = 'flex';
      }, 1000);
    }
  };
}
</script>

<?= $this->endSection() ?>
