# 📊 Fitur Rekap Bulanan 7 Kebiasaan Anak Indonesia Hebat

## 🎯 Deskripsi
Fitur Rekap Bulanan adalah halaman yang menampilkan progress kebiasaan siswa dalam bentuk tabel komprehensif dengan format:
- **Baris**: 31 hari dalam sebulan
- **Kolom**: 7 kebiasaan (Bangun Pagi, Beribadah, Berolahraga, Makan Sehat, Gemar Belajar, Bermasyarakat, Tidur Cepat)

## ✨ Fitur Utama

### 1. 📅 Pemilih Bulan
- Selector bulan dengan format YYYY-MM
- Tombol "Bulan Ini" untuk kembali ke bulan saat ini
- Validasi untuk mencegah pemilihan bulan yang akan datang

### 2. 📈 Statistik Ringkasan
- **Total Hari Aktif**: Jumlah hari dimana ada aktivitas yang tercatat
- **Rata-rata Penyelesaian**: Persentase kebiasaan yang diselesaikan
- **Hari Sempurna (7/7)**: Jumlah hari dengan semua kebiasaan terpenuhi
- **Tingkat Konsistensi**: Persentase hari aktif dari total hari

### 3. 📊 Statistik Per Kebiasaan
- Progress bar untuk setiap kebiasaan
- Tingkat keberhasilan dalam persentase
- Visual yang menarik dengan warna-warna berbeda

### 4. 📋 Tabel Rekap Bulanan
- **Format Tabel**: 31 baris (tanggal) x 7 kolom (kebiasaan)
- **Indikator Visual**:
  - ✅ **Hijau**: Kebiasaan selesai
  - ❌ **Merah**: Kebiasaan belum selesai
  - ➖ **Abu-abu**: Tidak ada data
  - **Kosong**: Tanggal yang akan datang
- **Interaksi**: Klik sel untuk melihat detail
- **Responsif**: Optimized untuk desktop dan mobile

### 5. 📱 Modal Detail
- Detail lengkap ketika mengklik sel tabel
- Informasi meliputi:
  - Status kebiasaan (Selesai/Belum Selesai)
  - Waktu (untuk Bangun Pagi dan Tidur Cepat)
  - Durasi (untuk Olahraga)
  - Catatan tambahan
  - Tips khusus untuk setiap kebiasaan

### 6. 💾 Fitur Export
- **Print**: Cetak atau simpan sebagai PDF melalui browser
- **Export CSV**: Download data dalam format Excel/CSV
- **Export PDF**: Akan tersedia dalam pengembangan selanjutnya

### 7. 🎨 Design Modern
- Gradient background dan card design
- Icon emojis untuk setiap kebiasaan
- Animasi hover dan transitions
- Color coding yang intuitif
- Responsive design untuk semua ukuran layar

## 🔧 Implementasi Teknis

### 1. File yang Dibuat/Dimodifikasi

#### **Views**
- `app/Views/siswa/habits/monthly_report.php` - Halaman rekap bulanan

#### **Controller**
- `app/Controllers/Siswa/HabitController.php` - Menambah methods:
  - `monthlyReport()` - Menampilkan halaman
  - `monthlyData()` - API untuk data bulanan

#### **Routes**
- `app/Config/Routes.php` - Menambah rute:
  - `/siswa/habits/monthly-report` - Halaman rekap
  - `/siswa/habits/monthly-data` - API data

### 2. Database Query
```sql
SELECT 
    DATE(hl.log_date) as log_date,
    hl.habit_id,
    hl.value_bool as completed,
    hl.value_time as time,
    hl.value_number as duration,
    hl.notes,
    h.name as habit_name
FROM habit_logs hl
JOIN habits h ON h.id = hl.habit_id
WHERE hl.student_id = ? 
    AND DATE(hl.log_date) BETWEEN ? AND ?
ORDER BY hl.log_date ASC, hl.habit_id ASC
```

### 3. Frontend Technology
- **Alpine.js** untuk reactive components
- **CSS3** dengan modern gradients dan animations
- **Responsive Grid** untuk layout
- **Modal** untuk detail view

## 📱 Penggunaan

### 1. Akses Halaman
1. Login sebagai siswa
2. Buka halaman 7 Kebiasaan (`/siswa/habits`)
3. Klik tab "📋 Rekap Tabel"

### 2. Navigasi
- Pilih bulan menggunakan month selector
- Klik "Bulan Ini" untuk kembali ke bulan saat ini
- Scroll horizontal pada tabel untuk device mobile

### 3. Melihat Detail
- Klik pada sel tabel untuk melihat detail kebiasaan
- Modal akan menampilkan informasi lengkap dan tips

### 4. Export Data
- Klik "Export CSV" untuk download spreadsheet
- Klik "Print" untuk cetak atau save as PDF

## 🎨 Color Scheme

### Kebiasaan Colors
- **Bangun Pagi**: Blue (#4299e1)
- **Beribadah**: Orange (#ed8936) 
- **Berolahraga**: Green (#48bb78)
- **Makan Sehat**: Pink (#ed64a6)
- **Gemar Belajar**: Teal (#4fd1c7)
- **Bermasyarakat**: Purple (#9f7aea)
- **Tidur Cepat**: Gray (#718096)

### Status Colors
- **Completed**: Green (#dcfce7)
- **Not Completed**: Red (#fef2f2)
- **No Data**: Gray (#f8fafc)
- **Future**: Light Gray (#f8fafc)

## 📊 Contoh Data

| Tanggal | Bangun Pagi | Beribadah | Olahraga | Makan Sehat | Belajar | Sosial | Tidur |
|---------|-------------|-----------|----------|-------------|---------|--------|-------|
| 1 Sen   | ✅          | ✅        | ✅       | ✅          | ✅      | ✅     | ✅    |
| 2 Sel   | ✅          | ✅        | ❌       | ✅          | ✅      | ❌     | ✅    |
| 3 Rab   | ❌          | ✅        | ✅       | ✅          | ✅      | ✅     | ✅    |
| ...     | ...         | ...       | ...      | ...         | ...     | ...    | ...   |

## 🚀 Pengembangan Selanjutnya

### 1. Fitur Tambahan
- [ ] Export PDF dengan template yang lebih menarik
- [ ] Filter berdasarkan kebiasaan tertentu
- [ ] Perbandingan antar bulan
- [ ] Grafik trend bulanan
- [ ] Reminder otomatis untuk kebiasaan yang terlewat

### 2. Optimasi
- [ ] Caching data bulanan
- [ ] Lazy loading untuk bulan-bulan lama
- [ ] Optimasi query database
- [ ] PWA support untuk offline access

### 3. Analytics
- [ ] Insight AI untuk pola kebiasaan
- [ ] Rekomendasi personal berdasarkan data
- [ ] Perbandingan dengan rata-rata kelas
- [ ] Achievement badges

## 📞 Dukungan
Fitur ini terintegrasi penuh dengan sistem 7 Kebiasaan yang sudah ada dan mendukung:
- Semua jenis data kebiasaan (time, duration, notes)
- Multi-religion support (khususnya Islamic prayers)
- Role-based access (hanya siswa yang bisa akses)
- Session management yang aman

## 🎉 Kesimpulan
Fitur Rekap Bulanan memberikan visualisasi komprehensif tentang progress kebiasaan siswa dalam format tabel yang mudah dipahami. Dengan statistik yang detail dan interaksi yang intuitif, siswa dapat dengan mudah memonitor perkembangan karakter mereka melalui 7 Kebiasaan Anak Indonesia Hebat.
