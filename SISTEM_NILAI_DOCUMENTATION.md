# Dokumentasi Sistem Nilai - Mata Pelajaran IPAS

## Overview
Sistem nilai telah dibuat untuk mengelola nilai siswa pada mata pelajaran IPAS dengan tiga jenis penilaian:
- **Nilai Harian**: Penilaian sehari-hari dan tugas
- **Nilai PTS**: Penilaian Tengah Semester
- **Nilai PAS**: Penilaian Akhir Semester

## Fitur Utama

### 1. Daftar Nilai (Index)
- **URL**: `/admin/nilai`
- **Fitur**:
  - Filter berdasarkan mata pelajaran dan kelas
  - Tampilan rekap nilai (harian, PTS, PAS, nilai akhir)
  - Auto-submit filter saat seleksi berubah
  - Akses kontrol berdasarkan role user

### 2. Tambah Nilai (Create)
- **URL**: `/admin/nilai/create`
- **Fitur**:
  - Form input nilai dengan validasi
  - Dropdown siswa berdasarkan kelas
  - Jenis nilai: harian, PTS, PAS
  - Field TP/Materi untuk deskripsi
  - Auto-refresh saat kelas/mapel berubah

### 3. Detail Nilai Siswa
- **URL**: `/admin/nilai/detail/{siswa_id}`
- **Fitur**:
  - Tampilan detail nilai per siswa
  - Dikelompokkan berdasarkan jenis (harian, PTS, PAS)
  - Ringkasan nilai dan aksi edit/hapus
  - Informasi siswa lengkap

### 4. Edit Nilai
- **URL**: `/admin/nilai/edit/{id}`
- **Fitur**:
  - Form edit nilai dengan data pre-filled
  - Validasi input
  - Informasi siswa read-only

### 5. Hapus Nilai
- **URL**: `/admin/nilai/delete/{id}`
- **Fitur**:
  - Soft delete dengan konfirmasi
  - Akses kontrol berdasarkan role

## Struktur Database

### Tabel `nilai`
```sql
CREATE TABLE `nilai` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `siswa_id` int(11) unsigned NOT NULL,
    `mata_pelajaran` varchar(50) NOT NULL,
    `jenis_nilai` enum('harian','pts','pas') DEFAULT 'harian',
    `nilai` decimal(5,2) NOT NULL,
    `tp_materi` text,
    `tanggal` date NOT NULL,
    `kelas` varchar(10) NOT NULL,
    `created_by` int(11) unsigned DEFAULT NULL,
    `updated_by` int(11) unsigned DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `siswa_id` (`siswa_id`),
    KEY `mata_pelajaran` (`mata_pelajaran`),
    KEY `jenis_nilai` (`jenis_nilai`),
    KEY `kelas` (`kelas`)
);
```

## Model Features

### NilaiModel
- **Lokasi**: `app/Models/NilaiModel.php`
- **Fitur Utama**:
  - `getNilaiRekap()`: Menghitung rata-rata nilai per jenis
  - `getNilaiDetailSiswa()`: Detail nilai siswa per mata pelajaran
  - `canAccessClass()`: Kontrol akses berdasarkan role
  - `getMataPelajaranList()`: Daftar mata pelajaran
  - `getJenisNilaiList()`: Daftar jenis nilai
  - Validation rules untuk input

### Perhitungan Nilai Akhir
```php
// Bobot penilaian
$nilaiAkhir = ($nilaiHarian * 0.4) + ($nilaiPTS * 0.3) + ($nilaiPAS * 0.3);
```

## Controller Features

### Nilai Controller
- **Lokasi**: `app/Controllers/Admin/Nilai.php`
- **Methods**:
  - `index()`: Daftar nilai dengan filter
  - `create()`: Form tambah nilai
  - `store()`: Simpan nilai baru
  - `detail()`: Detail nilai siswa
  - `edit()`: Form edit nilai
  - `update()`: Update nilai
  - `delete()`: Hapus nilai

### Access Control
- **Admin**: Akses penuh ke semua kelas
- **Wali Kelas**: Hanya akses ke kelas yang diampu
- **Authentication**: Redirect ke login jika tidak login

## Routes

```php
// Routes untuk sistem nilai
$routes->get('nilai', 'Admin\Nilai::index');
$routes->get('nilai/create', 'Admin\Nilai::create');
$routes->post('nilai/store', 'Admin\Nilai::store');
$routes->get('nilai/detail/(:num)', 'Admin\Nilai::detail/$1');
$routes->get('nilai/edit/(:num)', 'Admin\Nilai::edit/$1');
$routes->post('nilai/update/(:num)', 'Admin\Nilai::update/$1');
$routes->post('nilai/delete/(:num)', 'Admin\Nilai::delete/$1');
```

## Views

### 1. Index View
- **File**: `app/Views/admin/nilai/index.php`
- **Features**:
  - Filter mata pelajaran dan kelas
  - Tabel rekap nilai dengan nilai akhir
  - Tombol aksi (detail, tambah nilai)
  - Flash messages untuk feedback

### 2. Create View
- **File**: `app/Views/admin/nilai/create.php`
- **Features**:
  - Form input nilai dengan validasi
  - Dropdown siswa berdasarkan kelas
  - Auto-refresh saat filter berubah
  - Validation error handling

### 3. Detail View
- **File**: `app/Views/admin/nilai/detail.php`
- **Features**:
  - Informasi siswa lengkap
  - Ringkasan nilai per jenis
  - Tabel detail nilai dengan aksi edit/hapus
  - Berbeda warna untuk tiap jenis nilai

### 4. Edit View
- **File**: `app/Views/admin/nilai/edit.php`
- **Features**:
  - Form edit dengan data pre-filled
  - Informasi siswa read-only
  - Validation handling

## Navigation

Menu "Nilai Siswa" telah ditambahkan ke sidebar dengan:
- Icon yang sesuai (document/paper icon)
- Active state detection
- Posisi sebelum menu Absensi Siswa

## Security Features

1. **CSRF Protection**: Semua form menggunakan `csrf_field()`
2. **Input Validation**: Validasi pada model dan controller
3. **Access Control**: Role-based access untuk admin dan wali kelas
4. **SQL Injection Prevention**: Query builder dan parameterized queries
5. **Authentication Check**: Redirect ke login jika tidak terautentikasi

## Teknologi yang Digunakan

- **Backend**: CodeIgniter 4.6.1
- **Database**: MySQL
- **Frontend**: Tailwind CSS
- **JavaScript**: Vanilla JS untuk interaktivitas
- **Icons**: Heroicons (SVG)

## Instalasi dan Setup

1. **Database Migration**:
   ```bash
   php spark migrate
   ```

2. **Akses URL**: 
   - Buka browser ke `http://localhost:8080/admin/nilai`
   - Login dengan akun admin atau wali kelas

3. **Test Workflow**:
   - Pilih mata pelajaran dan kelas
   - Klik "Tambah Nilai"
   - Input nilai siswa
   - Lihat rekap dan detail nilai

## Kelebihan Sistem

1. **User-Friendly**: Interface yang intuitif dan responsif
2. **Flexible**: Mendukung multiple jenis nilai
3. **Secure**: Implementasi security best practices
4. **Scalable**: Mudah ditambahkan mata pelajaran baru
5. **Maintainable**: Code yang terstruktur dan terdokumentasi

## Pengembangan Selanjutnya

1. **Export Excel**: Tambahkan fitur export rekap nilai
2. **Import Data**: Bulk import nilai dari file Excel
3. **Grafik Nilai**: Visualisasi perkembangan nilai
4. **Notifikasi**: Email/SMS notifikasi nilai ke orangtua
5. **Mobile App**: Aplikasi mobile untuk akses nilai

---

*Sistem nilai ini telah diimplementasikan dengan lengkap dan siap digunakan untuk mengelola nilai mata pelajaran IPAS di sekolah.*
