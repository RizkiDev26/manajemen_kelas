# PANDUAN DEPLOY CI4 PROJECT KE INFINITYFREE.COM

## 🎯 PERSIAPAN DEPLOYMENT

### 1. STRUKTUR FILE UNTUK HOSTING
InfinityFree menggunakan folder `htdocs` sebagai public folder.
Kita perlu menyesuaikan struktur file:

```
htdocs/
├── index.php (dari public/)
├── .htaccess (dari public/)
├── css/
├── js/
├── assets/
├── uploads/
app/
├── Controllers/
├── Models/
├── Views/
├── Config/
vendor/
writable/
```

### 2. KONFIGURASI DATABASE
InfinityFree menyediakan MySQL database gratis dengan keterbatasan:
- Max 400 MB storage
- Max 10.000 queries/hour
- Prefix database: if0_XXXXXXX_

### 3. FILE KONFIGURASI YANG PERLU DIUBAH
- app/Config/Database.php (koneksi database)
- app/Config/App.php (baseURL)
- public/.htaccess (rewrite rules)

### 4. FILE YANG PERLU DI-UPLOAD
- Semua file kecuali:
  - .git/
  - tests/
  - *.md files
  - composer development files

## 🔧 LANGKAH-LANGKAH DEPLOYMENT

### STEP 1: Setup Account InfinityFree
1. Daftar di https://infinityfree.net
2. Buat subdomain atau gunakan domain sendiri
3. Catat detail database MySQL yang diberikan

### STEP 2: Konfigurasi Database
1. Export database lokal
2. Import ke database InfinityFree via phpMyAdmin
3. Update Config/Database.php

### STEP 3: Upload Files
1. Gunakan FileZilla atau File Manager
2. Upload dengan struktur yang benar
3. Set permissions yang tepat

### STEP 4: Testing
1. Test koneksi database
2. Test login admin/walikelas
3. Test fitur absensi

## 📝 CATATAN PENTING
- InfinityFree tidak support composer install
- Upload vendor/ folder yang sudah di-generate local
- Pastikan writable/ folder permissions 755
- Database prefix wajib menggunakan if0_XXXXXXX_

## 🚨 KETERBATASAN INFINITYFREE
- 5GB storage
- 20GB bandwidth/month
- PHP 8.1 max
- No SSH access
- Limited cron jobs
