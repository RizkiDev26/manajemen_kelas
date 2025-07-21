# 📊 Sistem Nilai Mata Pelajaran IPAS - Dokumentasi Lengkap

## 🎯 Overview
Sistem nilai telah berhasil dibuat untuk mengelola penilaian siswa pada mata pelajaran **IPAS (Ilmu Pengetahuan Alam dan Sosial)** dengan tiga kategori penilaian:
- **🟢 Nilai Harian**: Penilaian sehari-hari dan tugas 
- **🔵 Nilai PTS**: Penilaian Tengah Semester
- **🟣 Nilai PAS**: Penilaian Akhir Semester

## ⚡ Fitur Utama yang Telah Diimplementasikan

### 1. 📋 Halaman Daftar Nilai (Index)
- **URL**: `/admin/nilai`
- **Fitur**:
  - ✅ Filter berdasarkan mata pelajaran dan kelas
  - ✅ Tampilan rekap nilai (harian, PTS, PAS, nilai akhir)  
  - ✅ Auto-submit filter saat seleksi berubah
  - ✅ Role-based access control (admin/wali kelas)
  - ✅ Responsive design dengan Tailwind CSS

### 2. ➕ Halaman Tambah Nilai (Create)
- **URL**: `/admin/nilai/create`
- **Fitur**:
  - ✅ Form input nilai dengan validasi lengkap
  - ✅ Dropdown siswa berdasarkan kelas terpilih
  - ✅ Pilihan jenis nilai: harian, PTS, PAS
  - ✅ **Textarea untuk TP/Materi** (sesuai permintaan)
  - ✅ Auto-refresh saat kelas/mapel berubah
  - ✅ Validation error handling

### 3. 👁️ Halaman Detail Nilai Siswa
- **URL**: `/admin/nilai/detail/{siswa_id}`
- **Fitur**:
  - ✅ Tampilan detail nilai per siswa
  - ✅ Dikelompokkan berdasarkan jenis (harian, PTS, PAS)
  - ✅ Ringkasan nilai dan aksi edit/hapus
  - ✅ Informasi siswa lengkap
  - ✅ Color-coded untuk setiap jenis nilai

### 4. ✏️ Halaman Edit Nilai
- **URL**: `/admin/nilai/edit/{id}`
- **Fitur**:
  - ✅ Form edit dengan data pre-filled
  - ✅ **Textarea untuk TP/Materi** (sesuai permintaan)
  - ✅ Validasi input
  - ✅ Informasi siswa read-only

### 5. 🗑️ Fungsi Hapus Nilai
- **URL**: `/admin/nilai/delete/{id}`
- **Fitur**:
  - ✅ Soft delete dengan konfirmasi
  - ✅ Role-based access control

## 🏗️ Arsitektur Sistem

### 📊 Database Schema
```sql
CREATE TABLE `nilai` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `siswa_id` int(11) unsigned NOT NULL,
    `mata_pelajaran` varchar(50) NOT NULL,
    `jenis_nilai` enum('harian','pts','pas') DEFAULT 'harian',
    `nilai` decimal(5,2) NOT NULL,
    `tp_materi` text,                -- Textarea untuk TP/Materi
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

### 🧮 Rumus Perhitungan Nilai Akhir
```php
// Bobot penilaian yang digunakan
$nilaiAkhir = ($rataHarian * 0.4) + ($nilaiPTS * 0.3) + ($nilaiPAS * 0.3);

// Penjelasan:
// - Nilai Harian: 40% (rata-rata dari semua nilai harian)
// - Nilai PTS: 30% (diambil nilai PTS terbaru)
// - Nilai PAS: 30% (diambil nilai PAS terbaru)
```

### 🎯 Access Control Rules
| Role | Akses | Keterangan |
|------|-------|------------|
| **Admin** | 🟢 Full Access | Dapat mengakses semua kelas |
| **Wali Kelas** | 🟡 Limited Access | Hanya dapat mengakses kelas yang diampu |
| **User Lain** | 🔴 No Access | Redirect ke halaman login |

## 📁 Struktur File

### 🎮 Controller
**File**: `app/Controllers/Admin/Nilai.php`
- ✅ `index()`: Daftar nilai dengan filter
- ✅ `create()`: Form tambah nilai
- ✅ `store()`: Simpan nilai baru
- ✅ `detail()`: Detail nilai siswa
- ✅ `edit()`: Form edit nilai
- ✅ `update()`: Update nilai
- ✅ `delete()`: Hapus nilai

### 🏷️ Model
**File**: `app/Models/NilaiModel.php`
- ✅ `getNilaiRekap()`: Hitung rata-rata nilai per jenis
- ✅ `getNilaiDetailSiswa()`: Detail nilai siswa per mapel
- ✅ `canAccessClass()`: Kontrol akses berdasarkan role
- ✅ `getMataPelajaranList()`: Daftar mata pelajaran
- ✅ `getJenisNilaiList()`: Daftar jenis nilai
- ✅ Validation rules lengkap

### 🎨 Views
1. **index.php** - Daftar nilai dengan filter
2. **create.php** - Form tambah nilai (dengan textarea TP/Materi)
3. **detail.php** - Detail nilai siswa
4. **edit.php** - Form edit nilai (dengan textarea TP/Materi)

### 🚏 Routes
```php
$routes->get('nilai', 'Admin\Nilai::index');
$routes->get('nilai/create', 'Admin\Nilai::create');
$routes->post('nilai/store', 'Admin\Nilai::store');
$routes->get('nilai/detail/(:num)', 'Admin\Nilai::detail/$1');
$routes->get('nilai/edit/(:num)', 'Admin\Nilai::edit/$1');
$routes->post('nilai/update/(:num)', 'Admin\Nilai::update/$1');
$routes->post('nilai/delete/(:num)', 'Admin\Nilai::delete/$1');
```

## 📱 User Interface

### 🎯 Design Highlights
- **Responsive Layout**: Menggunakan Tailwind CSS
- **Color-coded Values**: 
  - 🟢 Harian: Green theme
  - 🔵 PTS: Blue theme  
  - 🟣 PAS: Purple theme
- **Interactive Elements**: Auto-submit filters, confirmations
- **Flash Messages**: Success/error feedback
- **Icons**: Menggunakan Heroicons SVG

### 📝 Form TP/Materi (Textarea)
```html
<textarea name="tp_materi" rows="3"
          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
          placeholder="Contoh: TP 1.1 - Ciri-ciri Makhluk Hidup dan Benda Tak Hidup
Siswa mampu mengidentifikasi ciri-ciri makhluk hidup seperti bernapas, bergerak, berkembang biak, dll."></textarea>
```

## 🔒 Security Features

1. **CSRF Protection**: Semua form menggunakan `csrf_field()`
2. **Input Validation**: Validasi pada model dan controller
3. **Role-based Access**: Admin dan wali kelas dengan batasan akses
4. **SQL Injection Prevention**: Query builder dan parameterized queries
5. **Authentication Check**: Redirect ke login jika tidak login
6. **Data Sanitization**: Escape output untuk prevent XSS

## 🧪 Data Testing

### 👨‍🎓 Sample Students (Kelas 1)
1. **Ahmad Fauzan** (NIS: 2024001)
2. **Siti Aisyah** (NIS: 2024002)
3. **Rizki Pratama** (NIS: 2024003)

### 📊 Sample Grades
- **Nilai Harian**: 80.0 - 92.5 (berbagai TP/Materi)
- **Nilai PTS**: 88.0 - 91.0 (penilaian tengah semester)
- **Nilai PAS**: (akan ditambahkan untuk demo)

## 🚀 Testing Workflow

### 1. **Login ke Sistem**
```
URL: http://localhost:8080/admin/nilai
```

### 2. **Pilih Filter**
- Mata Pelajaran: IPAS
- Kelas: 1 (untuk admin) / otomatis (untuk wali kelas)

### 3. **Lihat Rekap Nilai**
- Nilai harian (rata-rata)
- Nilai PTS
- Nilai PAS  
- Nilai akhir (otomatis terhitung)

### 4. **Tambah Nilai Baru**
- Klik "Tambah Nilai"
- Pilih siswa
- Pilih jenis nilai
- Masukkan nilai (0-100)
- Isi TP/Materi (textarea)
- Simpan

### 5. **Detail Nilai Siswa**
- Klik icon mata di baris siswa
- Lihat semua nilai per jenis
- Edit/hapus nilai individual

## 🛠️ Teknologi yang Digunakan

- **🚀 Backend**: CodeIgniter 4.6.1
- **🗄️ Database**: MySQL
- **🎨 Frontend**: Tailwind CSS
- **⚡ JavaScript**: Vanilla JS untuk interaktivitas
- **🎯 Icons**: Heroicons (SVG)
- **📱 Responsive**: Mobile-first design

## 🎉 Status Implementasi

### ✅ Completed Features
- [x] Database schema dan migration
- [x] Model dengan perhitungan nilai akhir
- [x] Controller dengan access control
- [x] Views dengan responsive design
- [x] Routes configuration
- [x] Navigation menu integration
- [x] **Textarea untuk TP/Materi** ✨
- [x] Data seeding untuk testing
- [x] Form validation
- [x] Error handling
- [x] Flash messages
- [x] Role-based access control

### 🔄 Currently Working
- [x] Mata pelajaran IPAS (selesai)
- [ ] Mata pelajaran lainnya (menunggu persetujuan desain)

### 🎯 Next Steps (Opsional)
1. **Export Excel**: Rekap nilai dalam format Excel
2. **Import Data**: Bulk import nilai dari file
3. **Grafik Nilai**: Visualisasi perkembangan nilai
4. **Notifikasi**: Email/SMS ke orangtua
5. **Mata Pelajaran Lain**: Ekspansi ke mapel lain

## 📞 Support & Maintenance

Sistem ini telah diimplementasikan dengan:
- ✅ **Code yang clean dan documented**
- ✅ **Architecture yang scalable**
- ✅ **Security best practices**
- ✅ **User-friendly interface**
- ✅ **Comprehensive error handling**

---

**🎯 Sistem Nilai IPAS siap digunakan dan telah memenuhi semua requirement yang diminta!**

*Dokumentasi ini akan diupdate seiring pengembangan sistem untuk mata pelajaran lainnya.*
