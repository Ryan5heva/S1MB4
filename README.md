# SIMBA — Sistem Informasi Manajemen Bakorwil

Aplikasi web berbasis **Laravel** untuk pengelolaan sistem informasi di lingkungan Bakorwil.

---

## 🚀 Cara Instalasi

### Persyaratan
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <url-repo> SIMBA
cd SIMBA

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di file .env
#    DB_DATABASE=simba
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Jalankan migrasi & seeder
php artisan migrate --seed

# 8. Build assets
npm run dev
```

---

## 🔑 Akun Default (Setelah Seeder)

Setelah menjalankan `php artisan migrate --seed`, akun berikut akan tersedia:

| Nama               | Email                    | Password      | Role        |
|--------------------|--------------------------|---------------|-------------|
| Super Administrator | superadmin@simba.test   | `password123` | Super Admin |
| Administrator       | admin@simba.test        | `password123` | Admin       |
| Operator Satu      | operator1@simba.test     | `password123` | Operator    |
| Operator Dua       | operator2@simba.test     | `password123` | Operator    |
| Operator Tiga      | operator3@simba.test     | `password123` | Operator    |
| Operator Empat     | operator4@simba.test     | `password123` | Operator    |

> ⚠️ **Penting:** Segera ganti password default setelah login pertama kali di menu **Kelola Pengguna**.

---

## 🗂️ Fitur Utama

- **Autentikasi** — Login & logout pengguna
- **Manajemen Pengguna** — Tambah, edit, dan hapus akun pengguna *(khusus Super Admin)*
- **Berita / Informasi** — Kelola konten berita dan pengumuman
- **Video** — Kelola data video
- **Dashboard** — Ringkasan data dan statistik
- **Sistem Role** — Kontrol akses berbasis role (Super Admin, Admin, Operator)

---

## 🛠️ Teknologi

| Layer      | Teknologi         |
|------------|-------------------|
| Backend    | Laravel 12 (PHP)  |
| Frontend   | Blade + Bootstrap |
| Database   | MySQL / MariaDB   |
| Build Tool | Vite + NPM        |

---

## 📁 Struktur Direktori Penting

```
SIMBA/
├── app/
│   ├── Http/Controllers/   # Controller aplikasi
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Skema database
│   └── seeders/            # Data awal (termasuk akun admin)
├── resources/
│   └── views/              # Template Blade
└── routes/
    └── web.php             # Definisi routing
```

---

## 🔒 Keamanan

- Password disimpan dalam bentuk **hash** menggunakan `bcrypt`
- Pengguna tidak dapat menghapus akun dirinya sendiri
- Semua input divalidasi sebelum disimpan ke database
- **Sistem Role** — 3 tingkatan akses:
  - `Super Admin` — akses penuh termasuk kelola pengguna
  - `Admin` — CRUD berita & video, tidak bisa kelola pengguna
  - `Operator` — hanya dapat menambah & mengedit data (tidak bisa menghapus)

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan internal **Bakorwil**. Hak cipta dilindungi.
