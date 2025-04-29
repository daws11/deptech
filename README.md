# Deptech - Sistem Manajemen Karyawan

Deptech adalah sistem manajemen karyawan yang dibangun menggunakan Laravel dan Tailwind CSS. Sistem ini menyediakan fitur-fitur untuk mengelola data karyawan, cuti, dan administrasi perusahaan.

## Fitur Utama

### 1. Manajemen Karyawan
- Data lengkap karyawan (nama depan, nama belakang, email, no HP, alamat, jenis kelamin)
- Manajemen profil karyawan
- Riwayat cuti karyawan

### 2. Manajemen Cuti
- Pengajuan cuti
- Persetujuan cuti
- Riwayat cuti
- Jenis cuti:
  - Cuti Tahunan
  - Cuti Sakit
  - Cuti Melahirkan
  - Cuti Paternitas
  - Cuti Tanpa Gaji

### 3. Sistem Autentikasi
- Multi-level user (Admin, Super Admin, Karyawan)
- Login terpisah untuk admin dan karyawan
- Manajemen profil pengguna

## Teknologi yang Digunakan

- **Backend**: Laravel 10.x
- **Frontend**: 
  - Tailwind CSS
  - Alpine.js
  - Livewire
- **Database**: MySQL
- **Authentication**: Laravel Sanctum

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL >= 5.7
- Web Server (Apache/Nginx)

## Instalasi

1. Clone repository
```bash
git clone [repository-url]
cd deptech
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di file .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=departech
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi dan seeder
```bash
php artisan migrate --seed
```

6. Compile assets
```bash
npm run build
```

7. Jalankan server
```bash
php artisan serve
```

## Struktur Database

### Tabel Users
- id
- name
- email
- password
- role
- created_at
- updated_at

### Tabel Admins
- id
- first_name
- last_name
- email
- date_of_birth
- gender
- password
- remember_token
- created_at
- updated_at

### Tabel Employees
- id
- first_name
- last_name
- email
- phone_number
- address
- gender
- created_at
- updated_at

### Tabel Leaves
- id
- employee_id
- leave_type
- start_date
- end_date
- duration_days
- reason
- status
- approved_by
- approved_at
- created_at
- updated_at

## Penggunaan

### Admin
1. Login menggunakan akun admin
2. Akses dashboard admin
3. Kelola data karyawan
4. Persetujuan cuti
5. Lihat laporan

### Karyawan
1. Login menggunakan akun karyawan
2. Akses dashboard karyawan
3. Ajukan cuti
4. Lihat status cuti
5. Update profil

## Pengembangan

### Menjalankan Development Server
```bash
npm run dev
```

### Menjalankan Tests
```bash
php artisan test
```

## Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE.md](LICENSE.md) untuk detailnya.

## Kontak

Nama - [@your_twitter](https://twitter.com/your_twitter)
Email - your.email@example.com

Link Project: [https://github.com/yourusername/departech](https://github.com/yourusername/departech)
