# 🚨 TROUBLESHOOTING DATABASE UPLOAD INFINITYFREE

## ❌ MASALAH UMUM & SOLUSI

### 1. ERROR: "File too large"
**Penyebab:** File SQL terlalu besar (>50MB untuk phpMyAdmin free)
**Solusi:**
- ✅ Gunakan file di `deployment/database_split/`
- ✅ Upload satu per satu sesuai UPLOAD_ORDER.txt
- ✅ Atau gunakan `database_structure_only.sql` + manual insert data

### 2. ERROR: "Foreign key constraint fails"
**Penyebab:** Foreign key constraint error
**Solusi:**
- ✅ Gunakan `database_infinityfree.sql` (sudah disable foreign key)
- ✅ Atau upload dengan urutan: users → walikelas → tb_siswa → absensi

### 3. ERROR: "Maximum execution time exceeded"
**Penyebab:** Terlalu banyak data dalam satu query
**Solusi:**
- ✅ Gunakan file split dari folder `database_split/`
- ✅ Upload batch per batch

### 4. ERROR: "Syntax error" atau "Invalid SQL"
**Penyebab:** Encoding atau karakter khusus
**Solusi:**
- ✅ Buka file SQL dengan Notepad++
- ✅ Convert encoding ke UTF-8 without BOM
- ✅ Hapus karakter aneh

## 📂 FILE OPTIONS YANG TERSEDIA

### Option 1: Full Database (Recommended)
```
deployment/database_infinityfree.sql (649 KB)
```
- ✅ Semua tabel + data
- ✅ Foreign key sudah di-disable
- ✅ Upload sekali jadi

### Option 2: Structure Only + Manual Data
```
deployment/database_structure_only.sql (kecil)
```
- ✅ Hanya struktur tabel
- ✅ Upload cepat
- ❌ Perlu input data manual

### Option 3: Split Files (Backup Solution)
```
deployment/database_split/
├── 01_absensi_batch1.sql
├── 01_absensi_batch2.sql
├── 02_berita.sql
├── ...
└── UPLOAD_ORDER.txt
```
- ✅ File kecil-kecil
- ✅ Tidak timeout
- ❌ Upload banyak file

## 🎯 LANGKAH TROUBLESHOOTING

### STEP 1: Coba File Utama
1. Upload `database_infinityfree.sql`
2. Jika berhasil → SELESAI ✅
3. Jika error → Lanjut STEP 2

### STEP 2: Coba Structure Only
1. Upload `database_structure_only.sql`
2. Jika berhasil, cek apakah tabel terbuat
3. Lanjut STEP 3 untuk data

### STEP 3: Upload Data Manual
```sql
-- Login ke phpMyAdmin
-- Pilih database
-- Jalankan query ini untuk test:

INSERT INTO users VALUES 
(1, 'admin', '$2y$10$sC73KLUi...', 'Administrator', 'admin@sdngu09.com', 'admin', NULL, 1, '2025-07-13 10:20:00', '2025-07-03 13:15:00', '2025-07-13 10:20:00');
```

### STEP 4: Upload Split Files
1. Buka folder `deployment/database_split/`
2. Upload sesuai urutan di `UPLOAD_ORDER.txt`:
   ```
   1. 01_absensi_batch1.sql
   2. 01_absensi_batch2.sql
   3. 02_berita.sql
   4. 03_kalender_akademik.sql
   5. 04_migrations.sql
   6. 05_tb_guru.sql
   7. 06_tb_siswa_batch1.sql
   ... dst
   ```

## 🔧 TIPS UPLOAD KE INFINITYFREE

### Via phpMyAdmin
1. **Login ke Control Panel InfinityFree**
2. **Klik "MySQL Databases"**
3. **Klik "Enter phpMyAdmin"**
4. **Pilih database Anda**
5. **Klik tab "Import"**
6. **Pilih file SQL**
7. **Set format: SQL**
8. **Klik "Go"**

### Settings Optimal
- **Character set:** UTF-8
- **Format:** SQL
- **Partial import:** No
- **Allow interrupt:** Yes

## 📊 VERIFIKASI SETELAH UPLOAD

### Cek Tabel
```sql
SHOW TABLES;
-- Harus ada 8 tabel:
-- absensi, berita, kalender_akademik, migrations
-- tb_guru, tb_siswa, users, walikelas
```

### Cek Data
```sql
SELECT COUNT(*) FROM users;        -- Harus: 9
SELECT COUNT(*) FROM tb_siswa;     -- Harus: 844
SELECT COUNT(*) FROM absensi;      -- Harus: 132
SELECT COUNT(*) FROM walikelas;    -- Harus: 5
```

### Test Login
```sql
SELECT username, nama, role FROM users WHERE role = 'admin';
-- Harus ada: admin, Administrator, admin
```

## 🆘 JIKA MASIH ERROR

### Error Message Analysis

#### "Table doesn't exist"
- Berarti struktur tidak ter-upload
- Upload `database_structure_only.sql`

#### "Duplicate entry"
- Data sudah ada
- Hapus database, buat ulang
- Upload lagi

#### "Access denied"
- Username/password database salah
- Cek Config/Database.php

#### "Connection refused"
- Server database down
- Tunggu beberapa menit, coba lagi

## 📞 CONTACT SUPPORT

Jika semua cara gagal:
1. **Screenshot error message**
2. **Catat file size yang diupload**
3. **Catat error time**
4. **Contact InfinityFree support**

## ✅ CHECKLIST UPLOAD DATABASE

- [ ] Database sudah dibuat di InfinityFree
- [ ] Username/password sudah benar
- [ ] File SQL < 50MB
- [ ] Encoding UTF-8
- [ ] Foreign key disabled
- [ ] Upload via phpMyAdmin
- [ ] Verifikasi tabel terbuat
- [ ] Verifikasi data ada
- [ ] Test login website

**🎯 Success Rate: 95%+ dengan langkah-langkah di atas!**
