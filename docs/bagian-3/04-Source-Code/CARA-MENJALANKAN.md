# CARA MENJALANKAN SOURCE CODE DS STORE

## Kebutuhan

- PHP 8.3 (disarankan Laragon/XAMPP) dengan ekstensi: `openssl`, `pdo_sqlite`, `sqlite3`, `mbstring`, `gd`, `fileinfo`, `curl`, `zip`.
- Composer 2.x
- Node.js 20+ dan npm
- Web server lokal (`php artisan serve` sudah cukup)

## Langkah instalasi

```powershell
# 1. Ekstrak DS-STORE-Source.zip ke folder kosong
cd DS-STORE-Source

# 2. Install dependency PHP
composer install

# 3. Install dependency frontend
npm install

# 4. Siapkan environment
copy .env.example .env
php artisan key:generate

# 5. Database SQLite sudah disertakan. Jika ingin dibuat ulang:
#    (pastikan folder database ada)
if not exist database\database.sqlite type nul > database\database.sqlite
php artisan migrate:fresh --seed --force

# 6. Build aset CSS/JS
npm run build

# 7. Jalankan server
php artisan serve --host=127.0.0.1 --port=8000
```

Buka `http://127.0.0.1:8000` di browser.

## Akun admin

Tidak disediakan akun demo. Buat akun admin sendiri setelah database siap:

```powershell
php artisan admin:create a3snewbaru990@gmail.com --name="Admin DS Store"
```

Perintah meminta password secara interaktif. Untuk kebutuhan otomatis bisa memakai opsi `--password=...`.

Pelanggan mendaftar sendiri lewat halaman `/register`.

Jika ingin mengulang dari nol:

```powershell
php artisan migrate:fresh --seed --force
npm run build
php artisan view:clear
php artisan route:clear
```

## Struktur penting

```text
app/Http/Controllers/        Controller publik, auth, dashboard, admin
app/Http/Requests/           StoreOrderRequest (validasi order)
app/Http/Middleware/         EnsureUserIsAdmin
app/Models/                  User, ServicePackage, Order, OrderEvent, HeroSlide
config/site.php              Nama brand, WhatsApp, Instagram, FAQ
database/migrations/         Skema tabel
database/seeders/            Data awal paket & slide promosi
resources/views/             Blade layout, component, halaman
resources/css/               base, home, workspace, responsive
resources/js/app.js          Carousel, kalkulator, drawer, toggle password
public/images/               Banner, logo, emblem rank transparan
public/build/                Hasil Vite (sudah disertakan)
scripts/                     Script generator laporan, slide, screenshot, emblem
tests/Feature/               18 pengujian otomatis
```

## Pengujian

```powershell
vendor/bin/phpunit
```

Hasil terakhir: **18 tests passed, 57 assertions**.

## Troubleshooting singkat

| Masalah | Solusi |
| --- | --- |
| `could not find driver` SQLite | Aktifkan ekstensi `pdo_sqlite` di `php.ini` |
| Halaman putih / CSS belum ada | Jalankan `npm run build`, lalu `php artisan view:clear` |
| Admin tidak bisa login | Buat ulang dengan `php artisan admin:create a3snewbaru990@gmail.com` |
| Error 500 setelah ganti rute | Jalankan `php artisan route:clear` dan `php artisan config:clear` |
| Port 8000 dipakai | Gunakan port lain: `php artisan serve --port=8001` |

## Catatan lisensi aset

Gambar karakter, logo, dan emblem rank berasal dari aset yang diberikan untuk tugas ini. Emblem rank telah diproses menjadi transparan oleh `scripts/process-rank-emblems.py`.
