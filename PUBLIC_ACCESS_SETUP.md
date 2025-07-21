# Konfigurasi Akses Publik CodeIgniter 4

## 📋 Daftar Perubahan yang Telah Dilakukan

### 1. Konfigurasi Base URL
**File: `app/Config/App.php`**
```php
// Sebelum:
public string $baseURL = 'http://localhost:8080/';

// Sesudah:
public string $baseURL = 'http://0.0.0.0:8080/';
```

**File: `.env`**
```env
# Sebelum:
# app.baseURL = ''

# Sesudah:
app.baseURL = 'http://0.0.0.0:8080/'
```

### 2. Konfigurasi Allowed Hostnames
**File: `app/Config/App.php`**
```php
// Sebelum:
public array $allowedHostnames = [];

// Sesudah:
public array $allowedHostnames = ['*'];
```

### 3. Konfigurasi CORS (Cross-Origin Resource Sharing)
**File: `app/Config/Cors.php`**
```php
// Sebelum:
'allowedOrigins' => [],
'allowedHeaders' => [],
'allowedMethods' => [],

// Sesudah:
'allowedOrigins' => ['*'],
'allowedHeaders' => ['*'],
'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
```

## 🚀 Cara Menjalankan Server

### Opsi 1: Manual
```bash
php spark serve --host=0.0.0.0 --port=8080
```

### Opsi 2: Menggunakan Batch File
Jalankan file: `start-server-public.bat`

## 🔥 Konfigurasi Firewall

### Otomatis (Recommended)
Jalankan file: `configure-firewall.bat` sebagai Administrator

### Manual
Buka Command Prompt sebagai Administrator dan jalankan:
```bash
netsh advfirewall firewall add rule name="CodeIgniter 4 Development Server" dir=in action=allow protocol=TCP localport=8080
```

## 🌐 Cara Mengakses Aplikasi

### 1. Dari Komputer yang Sama
- **URL:** `http://localhost:8080`
- **URL Alternatif:** `http://127.0.0.1:8080`

### 2. Dari Komputer Lain di Jaringan yang Sama
- **URL:** `http://192.168.50.3:8080`
- **Ganti IP:** Sesuaikan dengan IP komputer Anda

### 3. Dari Internet (Jika Router Dikonfigurasi)
- **URL:** `http://[IP_PUBLIC]:8080`
- **Catatan:** Perlu konfigurasi port forwarding di router

## 📱 Cara Cek IP Address

### IP Lokal
```bash
ipconfig | findstr "IPv4"
```

### IP Publik
Buka browser dan kunjungi: `https://whatismyipaddress.com/`

## ⚠️ Peringatan Keamanan

### Risiko Keamanan
1. **Aplikasi dapat diakses siapa saja** yang mengetahui IP dan port
2. **Data sensitif** dapat terekspos
3. **Tidak ada enkripsi HTTPS** (menggunakan HTTP biasa)

### Rekomendasi Keamanan
1. **Gunakan hanya untuk testing/development**
2. **Jangan gunakan di production**
3. **Aktifkan authentication yang kuat**
4. **Gunakan HTTPS jika diperlukan**
5. **Batasi akses dengan firewall rule yang lebih spesifik**

## 🔒 Mengembalikan ke Mode Lokal

### Otomatis
Jalankan file: `restore-local-only.bat`

### Manual
1. **Edit `app/Config/App.php`:**
   ```php
   public string $baseURL = 'http://localhost:8080/';
   public array $allowedHostnames = [];
   ```

2. **Edit `.env`:**
   ```env
   app.baseURL = 'http://localhost:8080/'
   ```

3. **Edit `app/Config/Cors.php`:**
   ```php
   'allowedOrigins' => [],
   'allowedHeaders' => [],
   'allowedMethods' => [],
   ```

4. **Hapus Firewall Rule:**
   ```bash
   netsh advfirewall firewall delete rule name="CodeIgniter 4 Development Server"
   ```

## 🛠️ Troubleshooting

### Tidak Bisa Diakses dari Luar
1. **Cek Firewall:** Pastikan port 8080 terbuka
2. **Cek Antivirus:** Beberapa antivirus memblokir koneksi
3. **Cek Router:** Perlu port forwarding untuk akses internet
4. **Cek IP:** Pastikan menggunakan IP yang benar

### Error "Config\App::$baseURL is not a valid URL"
1. Pastikan baseURL berformat: `http://IP:PORT/`
2. Tidak boleh kosong atau hanya slash (`/`)

### Error 500 atau 404
1. Cek log error di `writable/logs/`
2. Pastikan file `.htaccess` ada di root
3. Cek konfigurasi database di `.env`

## 📝 File yang Telah Dibuat

1. `start-server-public.bat` - Script untuk menjalankan server
2. `configure-firewall.bat` - Script untuk konfigurasi firewall
3. `restore-local-only.bat` - Script untuk kembali ke mode lokal
4. `PUBLIC_ACCESS_SETUP.md` - Dokumentasi ini

## 📞 Status Saat Ini

✅ **Server berjalan di:** `http://0.0.0.0:8080`
✅ **Konfigurasi CORS:** Mengizinkan semua origin
✅ **Allowed Hostnames:** Mengizinkan semua hostname
✅ **IP Lokal:** `192.168.50.3`

### Cara Akses:
- **Lokal:** `http://localhost:8080`
- **Jaringan:** `http://192.168.50.3:8080`
- **Mobile (same network):** `http://192.168.50.3:8080`

**⚠️ PENTING:** Jalankan `configure-firewall.bat` sebagai Administrator jika ingin akses dari device lain!
