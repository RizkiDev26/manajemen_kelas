# Sistem Absensi Siswa - Documentation

## Overview
Sistem absensi siswa telah berhasil diimplementasikan dengan fitur input harian dan rekap laporan. Sistem ini mendukung access control berdasarkan role pengguna.

## Features Implemented

### 1. Input Absensi Harian (`/admin/absensi/input`)
- **Interface**: Mirip dengan UI yang diminta (seperti gambar 1)
- **Features**:
  - Filter tanggal dan kelas
  - Tabel daftar siswa dengan status kehadiran
  - Status: Hadir, Izin, Sakit, Belum Absen
  - Field keterangan untuk detail tambahan
  - Auto-save saat mengubah status
  - Tombol "Semua Hadir" untuk quick action
  - Tombol "Simpan Semua" untuk batch save
  - Real-time statistics (counters)
  - Notifikasi sukses/error

- **Access Control**:
  - **Admin**: Dapat mengakses semua kelas
  - **Wali Kelas**: Hanya dapat mengakses kelas yang dipegang

### 2. Rekap Absensi (`/admin/absensi/rekap`)
- **Interface**: Mirip dengan UI yang diminta (seperti gambar 2)
- **Features**:
  - Filter bulan dan kelas
  - Tabel summary dengan statistik kehadiran
  - Perhitungan persentase kehadiran
  - Color-coded badges (hijau: ≥90%, kuning: ≥75%, merah: <75%)
  - Summary statistics cards
  - Detail view modal untuk melihat absensi harian
  - Export ke CSV
  - Responsive design

- **Access Control**:
  - **Admin**: Dapat melihat rekap semua kelas
  - **Wali Kelas**: Hanya dapat melihat rekap kelas yang dipegang

## Database Structure

### Table: `absensi`
```sql
- id (PK)
- siswa_id (FK to tb_siswa.id)
- tanggal (DATE)
- status (ENUM: 'hadir', 'izin', 'sakit')
- keterangan (TEXT, optional)
- created_by (FK to users.id)
- created_at, updated_at
- UNIQUE KEY (siswa_id, tanggal) -- One attendance per student per day
```

### Foreign Keys
- `siswa_id` references `tb_siswa.id`
- `created_by` references `users.id`

## Models

### 1. AbsensiModel (`app/Models/AbsensiModel.php`)
- **Methods**:
  - `getStudentsWithAttendance()` - Get students with attendance status
  - `saveAttendance()` - Save/update attendance record
  - `getAttendanceRecap()` - Get attendance data for date range
  - `getAttendanceSummary()` - Get monthly attendance summary
  - `canAccessClass()` - Check user access to class

### 2. TbSiswaModel (`app/Models/TbSiswaModel.php`)
- **Methods**:
  - `getActiveClasses()` - Get all active classes
  - `getStudentsByClass()` - Get students by class

## Controllers

### AbsensiController (`app/Controllers/Admin/Absensi.php`)
- **Methods**:
  - `input()` - Daily attendance input page
  - `save()` - AJAX save attendance
  - `rekap()` - Attendance recap page
  - `getDetailData()` - AJAX get detailed attendance data
  - `export()` - Export attendance to CSV

## Routes
```php
$routes->get('absensi/input', 'Admin\Absensi::input');
$routes->get('absensi/rekap', 'Admin\Absensi::rekap');
$routes->post('absensi/save', 'Admin\Absensi::save');
$routes->post('absensi/getDetailData', 'Admin\Absensi::getDetailData');
$routes->get('absensi/export', 'Admin\Absensi::export');
```

## Views

### 1. Input View (`app/Views/admin/absensi/input.php`)
- Responsive table with student list
- Dropdown untuk status kehadiran
- Auto-save functionality
- Real-time statistics
- Beautiful UI dengan cards dan statistics

### 2. Recap View (`app/Views/admin/absensi/rekap.php`)
- Summary table dengan statistics
- Modal untuk detail view
- Export functionality
- Filter controls
- DataTables integration untuk sorting/searching

## Menu Integration
- Added to admin sidebar dengan submenu:
  - Input Harian (`/admin/absensi/input`)
  - Rekap Absensi (`/admin/absensi/rekap`)

## Test Data
Sample attendance data telah ditambahkan untuk Kelas 2 A dengan berbagai status kehadiran untuk testing.

## Key Features

### Security & Access Control
- Role-based access (admin vs wali kelas)
- CSRF protection
- Input validation
- SQL injection protection

### User Experience
- Auto-save functionality
- Real-time feedback
- Responsive design
- Intuitive navigation
- Clear status indicators

### Data Integrity
- Unique constraint (one attendance per student per day)
- Foreign key constraints
- Soft delete support
- Audit trail (created_by, timestamps)

## Usage Instructions

### For Wali Kelas:
1. Login ke sistem
2. Akses menu "Absensi Siswa" > "Input Harian"
3. Pilih tanggal (default: hari ini)
4. Sistem otomatis menampilkan kelas yang dipegang
5. Update status kehadiran siswa
6. Data tersimpan otomatis atau gunakan "Simpan Semua"
7. Lihat rekap di menu "Rekap Absensi"

### For Admin:
1. Login ke sistem
2. Akses menu "Absensi Siswa" > "Input Harian"
3. Pilih tanggal dan kelas
4. Input absensi siswa
5. Lihat rekap semua kelas di "Rekap Absensi"
6. Export data untuk analisis lebih lanjut

## Files Created/Modified

### New Files:
- `app/Models/AbsensiModel.php`
- `app/Models/TbSiswaModel.php`
- `app/Controllers/Admin/Absensi.php`
- `app/Views/admin/absensi/input.php`
- `app/Views/admin/absensi/rekap.php`
- `app/Database/Migrations/2025-07-03-160000_CreateAbsensi.php`

### Modified Files:
- `app/Config/Routes.php` - Added absensi routes
- `app/Views/admin/layout.php` - Added menu items

### Test Files:
- `test_absensi_data.php` - Sample data generator
- `check_table_structure.php` - Database verification

## Status: ✅ COMPLETED
Sistem absensi siswa telah berhasil diimplementasikan sesuai dengan requirements dan gambar referensi yang diberikan.
