# GymForge — Workout Program & Progressive Overload Tracker

Aplikasi Laravel fullstack untuk program latihan (gym/rumah) dengan dumbbell & resistance band.
Tema: flat, tegas, hitam-kuning, tanpa gradient/neon.

## Yang Sudah Dibuat
- Login khusus Google SSO (Laravel Socialite)
- Onboarding/profiling wajib di awal (gender, usia, tinggi, berat, level aktivitas, lokasi & alat latihan, pengalaman, cedera, target) — bisa diubah lagi di halaman Profil
- 5 Program Preset siap pakai: Kecilin Perut, Bentuk Bokong, Full Body Muscle, Menguruskan Badan, Membesarkan Badan (Bulking)
- User bisa juga bikin Program Custom sendiri (pilih gerakan, set, rep per hari)
- 28 Gerakan (dumbbell, resistance band, bodyweight) sudah diisi dengan deskripsi & cara melakukan (admin nanti bisa tambah ilustrasi gambar)
- Tracking tiap sesi latihan: gerakan, beban (KG / level resistance band), repetisi, set, RPE
- Mesin saran Progressive Overload otomatis: jika semua set di sesi sebelumnya capai target reps → disarankan naikkan beban; jika belum → disarankan pertahankan beban dulu
- Halaman riwayat & progress per gerakan
- Area Admin (role-based) untuk CRUD Gerakan (termasuk upload ilustrasi) dan CRUD Program Preset

## Cara Menjalankan (Local)

1. **Clone/extract project ini**, lalu masuk ke foldernya.

2. **Install dependency PHP** (butuh koneksi internet ke Packagist):
   ```bash
   composer install
   ```

3. **Copy environment file**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setting MySQL** di `.env`:
   ```
   DB_DATABASE=gymforge
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Buat database `gymforge` di MySQL kamu.

5. **Setting Google OAuth** (untuk login SSO):
   - Buka https://console.cloud.google.com/ → buat project → APIs & Services → Credentials
   - Buat OAuth Client ID tipe "Web application"
   - Authorized redirect URI: `http://localhost:8000/auth/google/callback`
   - Copy Client ID & Secret ke `.env`:
     ```
     GOOGLE_CLIENT_ID=...
     GOOGLE_CLIENT_SECRET=...
     GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
     ```

6. **Install package Socialite** (sudah ada di composer.json, otomatis terpasang saat composer install).

7. **Migrate & seed database**:
   ```bash
   php artisan migrate
   php artisan db:seed
   php artisan storage:link
   ```
   > Seeder akan otomatis mengisi 8 muscle group, 28 gerakan, dan 5 program preset.

8. **Jadikan akun kamu sebagai Admin**:
   - Buka `database/seeders/DatabaseSeeder.php`, ganti email `admin@gymforge.test` dengan email Google kamu sendiri, lalu jalankan ulang:
     ```bash
     php artisan db:seed --class=DatabaseSeeder
     ```
   - Atau, setelah login pertama kali via Google, update manual kolom `role` user kamu di tabel `users` menjadi `admin`.

9. **Jalankan server**:
   ```bash
   php artisan serve
   ```
   Buka `http://localhost:8000`

## Struktur Penting
- `app/Http/Controllers/WorkoutController.php` → berisi logika progressive overload (`suggestNextLoad()`)
- `database/seeders/ExerciseSeeder.php` → katalog 28 gerakan + deskripsi (bisa ditambah admin lewat UI)
- `database/seeders/ProgramSeeder.php` → 5 program preset goal-based
- `resources/views/layouts/app.blade.php` → tema warna kuning (#FFD500) & hitam (#0A0A0A), flat, no-gradient

## Catatan
- Belum ada build step npm/Vite — Tailwind dipakai lewat CDN langsung di layout, jadi tidak perlu `npm install` untuk styling dasar. Kalau ingin upgrade ke Tailwind build lokal nanti, bisa ditambahkan `package.json` + `vite.config.js` standar Laravel.
- Ilustrasi gerakan saat ini placeholder kosong (`illustration_path = null`) — admin bisa upload gambar lewat halaman Admin > Gerakan > Edit.
- Storage untuk upload gambar gerakan pakai disk `public`, jangan lupa `php artisan storage:link`.
