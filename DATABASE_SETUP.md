# Database Setup Instructions

## Prerequisites
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer

## Setup Steps

### 1. Install Dependencies
```bash
composer install
```

### 2. Create Environment Configuration
Copy the example environment file:
```bash
cp .env.example .env
```

### 3. Configure Database Connection
Edit `.env` file and update the database settings:
```env
# Database Configuration
database.default.hostname = localhost
database.default.database = manajemen_kelas
database.default.username = your_username
database.default.password = your_password
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Create Database
Create a MySQL/MariaDB database:
```sql
CREATE DATABASE manajemen_kelas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 5. Run Database Migrations
Execute the migrations to create all necessary tables:
```bash
php spark migrate
```

This will create the following tables:
- `berita` - News/announcements
- `nilai` - Student grades
- `walikelas` - Homeroom teachers
- `users` - System users (admin, teachers)
- `tb_guru` - Teacher information
- `tb_siswa` - Student information 
- `kalender_akademik` - Academic calendar/holidays
- `absensi` - Student attendance
- `profil_sekolah` - School profile information

### 6. Populate Sample Data (Optional)
Run the seeders to populate the database with sample data:
```bash
php spark db:seed DatabaseSeeder
```

This will create:
- 1 School profile record
- 8 Teacher records
- 5 Homeroom teacher records
- 9 User accounts (1 admin + teachers)
- 834 Student records

### 7. Default Login Credentials
After seeding, you can login with:
- **Admin**: username=`admin`, password=`123456`
- **Teachers**: username=`[NIP]`, password=`123456`

### 8. Start Development Server
```bash
php spark serve
```

The application will be available at `http://localhost:8080`

## Database Schema Overview

### Core Tables
- **users**: System authentication and user management
- **walikelas**: Homeroom teacher assignments
- **tb_guru**: Teacher master data
- **tb_siswa**: Student master data
- **profil_sekolah**: School information

### Functional Tables  
- **absensi**: Daily attendance tracking
- **nilai**: Student grades and assessments
- **berita**: News and announcements
- **kalender_akademik**: School calendar and holidays

### Key Relationships
- Users → Walikelas (teachers assigned to classes)
- Walikelas → tb_guru (teacher information)
- Absensi → tb_siswa (attendance records per student)
- Absensi → users (who recorded the attendance)

## Troubleshooting

### Migration Issues
If migrations fail, check:
1. Database connection in `.env`
2. Database user has CREATE/ALTER permissions
3. No duplicate migration files

### Seeder Issues  
If seeders fail, ensure:
1. Migrations have run successfully first
2. Foreign key constraints are satisfied
3. Required tables exist

### Model Connection Issues
If models can't connect:
1. Verify `.env` database configuration
2. Check database server is running
3. Ensure database exists and user has access