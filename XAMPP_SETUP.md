# XAMPP Database Configuration

Panduan konfigurasi database untuk development lokal menggunakan XAMPP.

## ✅ Konfigurasi Saat Ini (XAMPP)

### Database Settings
```bash
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = sdngu09
database.default.username = root
database.default.password = 
database.default.port = 3306
```

### Status Database
- ✅ **Koneksi**: Berhasil terhubung ke XAMPP MySQL
- ✅ **Database**: `sdngu09` (16 tabel ditemukan)
- ✅ **Tables**: absensi, berita, buku_kasus, guru, habit_logs, habits, dll.

## 🔄 Switching Environment

### Gunakan Script Switcher

```bash
# Lihat status saat ini
php switch_environment.php status

# Switch ke XAMPP (development)
php switch_environment.php xampp

# Switch ke Hosting (production)
php switch_environment.php hosting
```

### Manual Configuration

#### XAMPP (Development)
```bash
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = localhost
database.default.database = sdngu09
database.default.username = root
database.default.password = 
```

#### Hosting (Production)
```bash
CI_ENVIRONMENT = production
app.baseURL = 'http://sdngu09.my.id/'
database.default.hostname = localhost
database.default.database = sdngu09_manajemen_kelas
database.default.username = sdngu09_user
database.default.password = [your_hosting_password]
```

## 🚀 Development Workflow

### Local Development (XAMPP)
1. Pastikan XAMPP Apache & MySQL running
2. Akses: `http://localhost:8080/`
3. Database: XAMPP phpMyAdmin
4. Debug mode: Enabled

### Production Deployment
1. Switch environment: `php switch_environment.php hosting`
2. Update password di `.env`
3. Upload files ke hosting
4. Run migrations di hosting

## 🛠️ XAMPP Tools

### Access Points
- **Application**: http://localhost:8080/
- **phpMyAdmin**: http://localhost/phpmyadmin/
- **XAMPP Control**: Start/Stop services

### Database Management
- **Import**: Via phpMyAdmin atau command line
- **Export**: Backup database untuk deployment
- **Reset**: Drop dan recreate database jika diperlukan

## 📝 Notes

- Database `sdngu09` sudah lengkap dengan 16 tabel
- Semua seeders sudah dijalankan
- Login default tersedia (admin/siswa)
- Environment switcher memudahkan perpindahan config

## 🔧 Troubleshooting

### XAMPP Issues
- Pastikan Apache dan MySQL service running
- Check port conflicts (80, 3306)
- Restart XAMPP jika diperlukan

### Database Issues
- Gunakan `php setup_hosting_database.php` untuk testing
- Check phpMyAdmin untuk verifikasi tabel
- Jalankan migrations jika tabel kosong

Current setup: **XAMPP Development Ready** ✅
