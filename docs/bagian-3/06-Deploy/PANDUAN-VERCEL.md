# DEPLOY DS STORE KE VERCEL + NEON POSTGRES

Repository: `https://github.com/a3snewbaru990-ui/ds-store-laravel`

## Mengapa tidak SQLite?

Vercel memakai filesystem read-only pada serverless function, jadi file `database/database.sqlite` tidak bisa ditulis. Untuk deploy, aplikasi memakai **PostgreSQL Neon** (gratis) melalui environment variable `DB_URL`. Laravel sudah disiapkan untuk membaca `DATABASE_URL`/`DB_URL` pada koneksi `pgsql`.

## 1. Buat database Neon (gratis)

1. Buka https://neon.tech dan daftar/login (bisa pakai GitHub).
2. Buat project baru, misalnya `ds-store`.
3. Pilih region terdekat, lalu buat database `neondb` (default).
4. Di halaman **Dashboard → Connection Details**, salin **Connection string** yang sudah termasuk password.
   - Biasanya berbentuk: `postgresql://user:password@ep-xxx.region.aws.neon.tech/neondb?sslmode=require`
5. Simpan string ini; akan dipakai sebagai `DB_URL`.

## 2. Import repository ke Vercel

1. Buka https://vercel.com dan daftar/login dengan GitHub.
2. Klik **Add New → Project**, lalu pilih repository `ds-store-laravel`.
3. Vercel akan mendeteksi `vercel.json`. Jangan ubah **Build Command** dan **Install Command** kecuali diminta.
4. Sebelum klik **Deploy**, isi environment variables di bawah ini.

## 3. Environment variables di Vercel

Masukkan di **Project Settings → Environment Variables** (untuk Production/Preview):

| Nama | Nilai | Keterangan |
| --- | --- | --- |
| `APP_KEY` | `base64:...` | Generate dengan `php artisan key:generate` di lokal, lalu salin nilai `.env` |
| `APP_URL` | `https://<project>.vercel.app` | URL final setelah deploy pertama |
| `DB_URL` | connection string Neon | RAHASIA, jangan taruh di `vercel.json` |
| `DB_CONNECTION` | `pgsql` | Sudah ada di `vercel.json`, boleh ditambahkan eksplisit |

Variabel non-rahasia (`APP_ENV`, `APP_DEBUG`, cache ke `/tmp`, `SESSION_DRIVER=cookie`, `LOG_CHANNEL=stderr`, `DB_CONNECTION=pgsql`) sudah ada di `vercel.json`.

## 4. Deploy

1. Klik **Deploy**.
2. Saat build:
   - `npm ci` + `npm run build` menghasilkan aset CSS/JS.
   - Runtime `vercel-php@0.7.4` (PHP 8.3) meng-install dependency Composer.
   - Composer script `vercel` menjalankan `php artisan migrate --force` dan `php artisan db:seed --force`.
3. Setelah selesai, buka URL Vercel.
4. Jika `APP_URL` belum sesuai, ubah di Environment Variables lalu **Redeploy**.

## 5. Uji setelah deploy

| Halaman | URL |
| --- | --- |
| Beranda | `/` |
| Pricelist | `/#pricelist` |
| Login | `/login` |
| Lacak pesanan | `/lacak` |
| Dashboard pelanggan | `/dashboard` |
| Admin | `/admin` |

Akun admin di database produksi:

| Peran | Email |
| --- | --- |
| Admin | `a3snewbaru990@gmail.com` |

Tidak ada akun demo. Jika perlu membuat/reset admin, jalankan perintah berikut secara lokal dengan env `DATABASE_URL` produksi lalu deploy ulang:

```powershell
php artisan admin:create a3snewbaru990@gmail.com --name="Admin DS Store"
```

## 6. Troubleshooting

| Gejala | Penyebab | Solusi |
| --- | --- | --- |
| 500 "No application encryption key" | `APP_KEY` belum diisi | Isi `APP_KEY` di Vercel, redeploy |
| 500 database connection | `DB_URL` salah / belum diisi | Salin ulang connection string Neon, pastikan `?sslmode=require` |
| CSS/JS/gambar 404 | Routes statis tidak ter-build | Pastikan `npm run build` sukses dan folder `public/build` tidak ter-exclude |
| Halaman login redirect ke localhost | `APP_URL` masih default | Set `APP_URL` ke URL Vercel lalu redeploy |
| Perubahan kode tidak muncul | Cache Vercel | Redeploy atau clear cache di dashboard Vercel |
| Seed ganda | Deploy ulang | Aman: seeder memakai `updateOrCreate` dan pengecekan order |
| `Undefined table` saat build/runtime | DDL Postgres through pooler gagal di dalam transaksi | Semua migration sudah memakai `public $withinTransaction = false;` |
| Ingin migrate ulang DB produksi | Database sudah berisi data lama | Jalankan `php artisan db:wipe --force` lalu `php artisan migrate --force && php artisan db:seed --force` dengan env `DATABASE_URL` produksi dan ekstensi `pdo_pgsql` aktif |

## 7. Catatan penting

- Database lokal tetap memakai SQLite. Deployment memakai PostgreSQL. Keduanya memakai migration dan seeder yang sama.
- Jangan menyimpan `DB_URL` atau `APP_KEY` di GitHub.
- File `vercel.json`, `api/index.php`, dan `.vercelignore` sudah disiapkan di repository.
- Untuk update konten (harga, FAQ, IG), edit `config/site.php` atau seeder lalu push ke GitHub; Vercel akan deploy otomatis.
