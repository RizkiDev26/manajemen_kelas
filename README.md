# Sistem Manajemen Kelas

Aplikasi web untuk manajemen kelas dan absensi siswa menggunakan CodeIgniter 4.

## Deskripsi

Sistem Manajemen Kelas adalah aplikasi web yang dirancang untuk membantu sekolah dalam mengelola data siswa, absensi, dan administrasi kelas. Aplikasi ini dibangun menggunakan framework CodeIgniter 4 dengan antarmuka yang modern menggunakan Tailwind CSS.

## Fitur Utama

- **Manajemen User**: Kelola user admin dan wali kelas
- **Manajemen Siswa**: Data lengkap siswa per kelas
- **Sistem Absensi**: Pencatatan kehadiran siswa harian
- **Rekap Laporan**: Export data ke Excel dengan berbagai format
- **Manajemen Kelas**: Organisasi kelas dan wali kelas
- **Dashboard**: Overview data sistem
- **Sistem Libur**: Manajemen hari libur dan kalendar akademik

## Teknologi yang Digunakan

- **Backend**: CodeIgniter 4 (PHP Framework)
- **Frontend**: Tailwind CSS, Font Awesome
- **Database**: MySQL/MariaDB
- **Export**: PhpSpreadsheet untuk Excel export
- **Authentication**: Session-based authentication

## Persyaratan Sistem

- PHP 7.4 atau lebih baru
- MySQL 5.7+ atau MariaDB 10.3+
- Composer
- Web Server (Apache/Nginx)

## Instalasi

1. Clone repository ini:
```bash
git clone https://github.com/RizkiDev26/manajemen_kelas.git
cd manajemen_kelas
```

2. Install dependencies:
```bash
composer install
```

3. Konfigurasi database:
   - Buat database baru
   - Copy `.env.example` ke `.env`
   - Sesuaikan konfigurasi database di file `.env`

4. Jalankan migrasi dan seeder (jika ada):
```bash
php spark migrate
php spark db:seed
```

5. Jalankan aplikasi:
```bash
php spark serve
```

## Struktur Proyek

```
app/
├── Config/         # Konfigurasi aplikasi
├── Controllers/    # Controller aplikasi
├── Models/         # Model database
├── Views/          # Template views
└── ...
public/            # Assets dan entry point
writable/          # File yang dapat ditulis
vendor/            # Dependencies
```

## Role dan Akses

### Admin
- Kelola semua data user
- Kelola data siswa dan kelas
- Akses semua laporan dan rekap
- Manajemen sistem

### Wali Kelas
- Kelola absensi siswa di kelas yang dipegang
- Lihat data siswa di kelas yang dipegang
- Export laporan kelas
- Update profil pribadi

## Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -am 'Tambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## Kontak

- Developer: RizkiDev26
- Email: rizkigu09@gmail.com
- GitHub: [RizkiDev26](https://github.com/RizkiDev26)

## Changelog

### Versi 1.0.0
- Sistem dasar manajemen kelas
- Fitur absensi siswa
- Export laporan Excel
- Authentication dan authorization
- Dashboard admin dan wali kelas
