# Aplikasi Visualisasi Data Penjualan - Laravel

Aplikasi ini dibangun menggunakan framework Laravel untuk menampilkan visualisasi data dan menghasilkan wawasan (insight) dari dataset yang diberikan.

## Prasyarat
Sebelum menjalankan aplikasi, pastikan sistem Anda telah terpasang:
1. PHP (versi 8.1 atau yang lebih baru)
2. Composer
3. XAMPP (Apache dan MySQL)

## Langkah Instalasi

### 1. Persiapan Proyek dan Database
1. Aktifkan modul Apache dan MySQL melalui XAMPP Control Panel.
2. Buka terminal di folder proyek ini.
3. Jalankan perintah untuk menginstal dependensi:
   ```bash
   composer install
   ```
4. Buat database baru dengan nama: `laravel_avd` di phpMyAdmin.

### 2. Konfigurasi Lingkungan
1. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
2. Generate kunci aplikasi:
   ```bash
   php artisan key:generate
   ```
3. Pastikan konfigurasi database pada file `.env` sudah sesuai:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_avd
   DB_USERNAME=root
   DB_PASSWORD=
   ```


### 3. Migrasi dan Seeding Data
Anda dapat memilih salah satu cara berikut untuk mengisi data:

**Cara A: Menggunakan Artisan (Otomatis)**
Gunakan perintah berikut untuk membuat struktur tabel dan mengimpor data secara otomatis:
```bash
php artisan migrate:fresh --seed
```

**Cara B: Menggunakan SQL Script (Manual di HeidiSQL/MySQL)**
1. Jalankan migrasi terlebih dahulu: `php artisan migrate`.
2. Buka HeidiSQL atau tool MySQL lainnya.
3. Import dan jalankan file script: `database/seeders/dataset.sql`.


### 4. Menjalankan Aplikasi
Jalankan server pengembangan Laravel dengan perintah:
```bash
php artisan serve
```
Setelah perintah dijalankan, akses aplikasi melalui peramban pada alamat: `http://127.0.0.1:8000`

## Fitur Utama
1. **Visualisasi Data**: Menampilkan Grafik Tren Penjualan, Pengaruh Kegiatan, dan Pengaruh Curah Hujan menggunakan Chart.js.
2. **Manajemen Data**: Menampilkan dataset dalam bentuk tabel yang dapat diurutkan (sorting).
3. **Ekspor Data**: Fitur untuk mengunduh data dalam format CSV.
4. **Desain Responsif**: Antarmuka yang bersih dan optimal untuk berbagai ukuran layar.
