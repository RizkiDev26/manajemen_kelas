# 🚀 PANDUAN LENGKAP DEPLOY KE INFINITYFREE.COM

## 📋 PERSIAPAN YANG SUDAH SELESAI ✅
- ✅ Folder deployment sudah dibuat
- ✅ File konfigurasi production sudah dibuat
- ✅ Database sudah di-export (707 KB)
- ✅ ZIP file deployment sudah dibuat (1.6 MB)
- ✅ Struktur file sudah disesuaikan untuk InfinityFree

## 🔧 LANGKAH-LANGKAH DEPLOYMENT

### STEP 1: SETUP ACCOUNT INFINITYFREE
1. **Daftar account di https://infinityfree.net**
2. **Buat website baru:**
   - Pilih subdomain gratis (contoh: `yoursite.infinityfreeapp.com`)
   - Atau gunakan domain sendiri jika punya
3. **Catat detail yang diberikan:**
   - FTP hostname: `files.infinityfree.net`
   - FTP username: `if0_XXXXXXX`
   - FTP password: (yang Anda buat)
   - Website URL: `https://yoursite.infinityfreeapp.com`

### STEP 2: SETUP DATABASE MYSQL
1. **Masuk ke Control Panel InfinityFree**
2. **Buat MySQL Database:**
   - Klik "MySQL Databases"
   - Buat database baru
   - Catat detail database:
     ```
     Database Server: sql200.infinityfree.com
     Database Name: if0_XXXXXXX_sdngu09
     Username: if0_XXXXXXX
     Password: (yang Anda buat)
     ```

### STEP 3: UPDATE KONFIGURASI
Edit file di folder `deployment/`:

#### A. Edit `deployment/app/Config/Database.php`:
```php
'hostname'     => 'sql200.infinityfree.com',  // Server database
'username'     => 'if0_XXXXXXX',              // Username database Anda
'password'     => 'PASSWORD_ANDA',            // Password database Anda
'database'     => 'if0_XXXXXXX_sdngu09',      // Nama database Anda
```

#### B. Edit `deployment/app/Config/App.php`:
```php
public string $baseURL = 'https://yoursite.infinityfreeapp.com/';
```

#### C. Edit `deployment/.env`:
```
app.baseURL = 'https://yoursite.infinityfreeapp.com/'
database.default.hostname = sql200.infinityfree.com
database.default.database = if0_XXXXXXX_sdngu09
database.default.username = if0_XXXXXXX
database.default.password = PASSWORD_ANDA
```

### STEP 4: IMPORT DATABASE
1. **Masuk ke phpMyAdmin via Control Panel InfinityFree**
2. **Pilih database yang sudah dibuat**
3. **Klik Import**
4. **Upload file `deployment/database_export.sql`**
5. **Klik Go untuk import**
6. **Verifikasi semua tabel ter-import:**
   - absensi (132 records)
   - users (9 records)
   - tb_siswa (844 records)
   - walikelas (5 records)
   - dll.

### STEP 5: UPLOAD FILES
#### Opsi A: Upload ZIP (Mudah)
1. **Extract `ci4_infinityfree_deployment.zip`**
2. **Upload via File Manager di Control Panel:**
   - Upload semua file dari `htdocs/` ke folder `htdocs/`
   - Upload folder `app/` ke root directory
   - Upload folder `vendor/` ke root directory
   - Upload folder `writable/` ke root directory
   - Upload file `.env` ke root directory

#### Opsi B: Upload Manual via FTP
1. **Gunakan FTP client (FileZilla):**
   - Host: `files.infinityfree.net`
   - Username: `if0_XXXXXXX`
   - Password: (FTP password Anda)
2. **Upload struktur file:**
   ```
   /htdocs/
   ├── index.php
   ├── .htaccess
   ├── css/
   ├── favicon.ico
   └── robots.txt
   
   /app/ (di root)
   /vendor/ (di root)
   /writable/ (di root)
   /.env (di root)
   ```

### STEP 6: SET PERMISSIONS
Set permissions untuk folder writable:
- `writable/`: 755
- `writable/cache/`: 755
- `writable/logs/`: 755
- `writable/session/`: 755
- `writable/uploads/`: 755

### STEP 7: TESTING WEBSITE
1. **Akses website:** `https://yoursite.infinityfreeapp.com`
2. **Test halaman utama:** Harus load tanpa error
3. **Test login admin:**
   - URL: `https://yoursite.infinityfreeapp.com/login`
   - Username: `admin`
   - Password: `admin123` (sesuai database)
4. **Test login walikelas:**
   - Username: `199303292019031...`
   - Password: `admin123`
5. **Test fitur absensi:**
   - Input Absensi: `https://yoursite.infinityfreeapp.com/admin/absensi/input`
   - Rekap Absensi: `https://yoursite.infinityfreeapp.com/admin/absensi/rekap`

### STEP 8: TROUBLESHOOTING UMUM

#### Error "Database connection failed"
- Periksa detail database di `app/Config/Database.php`
- Pastikan database sudah dibuat di InfinityFree
- Cek apakah file `.env` sudah di-upload

#### Error "404 Not Found"
- Pastikan file `.htaccess` ada di folder `htdocs/`
- Cek `app/Config/App.php` untuk baseURL yang benar

#### Error "Writable folder not writable"
- Set permissions folder `writable/` ke 755
- Pastikan folder `writable/cache/`, `writable/logs/`, dll ada

## 🎯 DATA LOGIN DEFAULT

### Admin
- **Username:** `admin`
- **Password:** `admin123`
- **Role:** `admin`
- **Akses:** Semua fitur

### Walikelas (Contoh)
- **Username:** `199303292019031...` (Rizki)
- **Password:** `admin123`
- **Role:** `walikelas`
- **Kelas:** `Kelas 5 A`

## 📊 STATISTIK DEPLOYMENT
- **Total Files:** ~1.6 MB (compressed)
- **Database Size:** 707 KB
- **Tables:** 8 tables
- **Records:** 1,024 total records
- **PHP Version:** Compatible dengan PHP 8.1
- **Framework:** CodeIgniter 4.6.1

## 🚨 CATATAN PENTING INFINITYFREE
- **Storage Limit:** 5 GB
- **Bandwidth:** 20 GB/month
- **MySQL:** 400 MB max, 10k queries/hour
- **No SSH access**
- **No composer install** (harus upload vendor/)
- **No cron jobs** (kecuali premium)
- **Max execution time:** 30 seconds

## 🔧 OPTIMASI UNTUK INFINITYFREE
- Database queries sudah dioptimasi
- File cache menggunakan file system
- Session menggunakan files
- Error reporting dimatikan untuk production
- HTTPS enforced untuk security

## 📞 SUPPORT
Jika ada masalah deployment:
1. Cek error logs di Control Panel InfinityFree
2. Verifikasi semua file sudah ter-upload
3. Pastikan permissions folder sudah benar
4. Test koneksi database via phpMyAdmin

**🎉 Selamat! Website absensi siswa Anda siap digunakan di InfinityFree!**
