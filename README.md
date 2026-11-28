# DS Store — Joki Akun Mobile Legends

Website jasa joki akun Mobile Legends berbasis **Laravel 12**. Hadir dengan pricelist Season 42, kalkulator estimasi biaya, form pemesanan, pelacakan pesanan tanpa login, dashboard pelanggan, dan workspace admin.

Tagline: **Respon Cepat, Progres Kilat & 100% Amanah**.

## Fitur utama

- Hero carousel 3 slide: autoplay 5,2 detik, panah, dot indikator, jeda saat hover, swipe, keyboard, reduced-motion.
- Pricelist resmi: GM Rp5.000, Epic Rp6.000, Legend Rp7.000, Mythic Rp12.000, Mythic Honor Rp15.000, Mythic Glory Rp20.000 per bintang, serta Full Grading Mythic Rp150.000 / 10 match.
- Kalkulator estimasi total dengan perhitungan ulang di server.
- Autentikasi pelanggan & admin, rate limiting login, session regenerate, CSRF.
- Order dengan validasi `StoreOrderRequest` (User ID, Server ID, WhatsApp, metode login).
- Riwayat progres pesanan (`order_events`) pada dashboard pelanggan dan admin.
- Pelacakan pesanan tanpa login memakai kode order + nomor WhatsApp.
- Admin: dashboard metrik, filter pesanan, update status/pembayaran, kelola paket, daftar pelanggan.
- Desain responsif dengan palet dark slate, cyan, dan gold; emblem rank transparan.

## Menjalankan project

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed --force
npm run build
php artisan serve --host=127.0.0.1 --port=8000
```

Buka `http://127.0.0.1:8000`.

### Akun admin

Tidak ada akun demo. Buat akun admin milik sendiri dengan perintah:

```powershell
php artisan admin:create a3snewbaru990@gmail.com --name="Admin DS Store"
```

Perintah akan meminta password secara interaktif (opsi `--password=` juga tersedia untuk kebutuhan otomatis). Akun admin tersebut dipakai untuk login di `/login` dan mengakses `/admin`.

Pelanggan mendaftar sendiri melalui halaman `/register`.

## Pengujian

```powershell
vendor/bin/phpunit
```

Hasil terakhir: **18 test lulus, 57 assertion**.

## Dokumentasi pengumpulan (Bagian 3)

Seluruh kebutuhan pengumpulan ada di [`docs/bagian-3/`](docs/bagian-3/):

- `00-PANDUAN-PENGUMPULAN.md` — checklist dan struktur folder
- `DATA-KELOMPOK.md` — placeholder identitas kelompok & link video
- `01-Laporan/` — laporan DOCX + Markdown
- `02-Presentasi/` — slide PPTX (15 slide) + naskah video
- `03-Screenshots/` — 12 screenshot desktop & mobile
- `04-Source-Code/` — ZIP source tanpa `vendor/` dan `node_modules/`
- `05-Video/` — link YouTube, checklist, dan script narasi

## Deploy gratis (Vercel + Neon Postgres)

Source code sudah disiapkan untuk deploy serverless:

- `vercel.json` — konfigurasi build, runtime PHP 8.3, route aset statis, dan env non-rahasia
- `api/index.php` — entrypoint serverless yang me-render `public/index.php`
- `.vercelignore` — mengecualikan `vendor`, `node_modules`, `docs`, dll
- Composer script `vercel` — menjalankan `migrate` + `seed` saat build

Panduan lengkap: [`docs/bagian-3/06-Deploy/PANDUAN-VERCEL.md`](docs/bagian-3/06-Deploy/PANDUAN-VERCEL.md)

Repository: `https://github.com/a3snewbaru990-ui/ds-store-laravel`

## Script utility

```text
scripts/process-rank-emblems.py   hapus background emblem rank -> WebP transparan
scripts/build-report.py           generate laporan DOCX
scripts/export-report-md.py       ekspor DOCX -> Markdown
scripts/build-presentation.py     generate slide PPTX
scripts/capture-screenshots.py    ambil 12 screenshot via Playwright
scripts/build-source-zip.py       kemas source code untuk pengumpulan
```

## Teknologi

PHP 8.3 · Laravel 12.73 · SQLite · Blade · Vite 7 · CSS vanilla · JavaScript vanilla · Playwright (QA).
