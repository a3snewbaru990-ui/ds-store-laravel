# PANDUAN PENGUMPULAN — BAGIAN 3

Paket ini berisi **hasil project, laporan, dan presentasi** untuk website DS Store.

## Checklist sebelum dikumpulkan

- [ ] Isi `DATA-KELOMPOK.md` dengan nama, NIM, kelas, dosen, dan link video.
- [ ] Ganti placeholder pada cover `01-Laporan/Laporan-DS-Store.docx`.
- [ ] Ganti placeholder pada slide 1 dan slide terakhir `02-Presentasi/Presentasi-DS-Store.pptx`.
- [ ] Ekspor video presentasi ke YouTube dan isi `05-Video/LINK-YOUTUBE.txt`.
- [ ] Pastikan `05-Video/Script-Video-Presentasi.md` sudah dibaca oleh presenter.
- [ ] Pastikan folder `03-Screenshots` berisi 12 screenshot (tanpa error/blank).
- [ ] Pastikan `04-Source-Code/DS-STORE-Source.zip` tidak menyertakan `vendor/` dan `node_modules/`.
- [ ] (Opsional) Ikuti `06-Deploy/PANDUAN-VERCEL.md` agar website bisa diakses publik via URL Vercel.
- [ ] Rename folder kelompok mengikuti format yang diminta dosen, misalnya `Kelompok_01_NIM-Ketua_Bagian3`.
- [ ] Upload ke LMS / kumpulkan folder ke drive kelompok sesuai instruksi.

## Struktur folder

```text
bagian-3/
├── 00-PANDUAN-PENGUMPULAN.md      <- file ini
├── DATA-KELOMPOK.md               <- isi identitas kelompok
├── 01-Laporan/
│   ├── Laporan-DS-Store.docx      <- laporan utama (Word, bisa diekspor ke PDF)
│   └── Laporan-DS-Store.md        <- versi Markdown untuk edit cepat
├── 02-Presentasi/
│   ├── Presentasi-DS-Store.pptx   <- 15 slide, 16:9
│   └── Script-Video-Presentasi.md <- naskah narasi ±6 menit
├── 03-Screenshots/
│   ├── 01-home-desktop.png
│   ├── 02-home-mobile.png
│   ├── 03-mobile-nav.png
│   ├── 04-login.png
│   ├── 05-order-form.png
│   ├── 06-tracking-result.png
│   ├── 07-customer-dashboard.png
│   ├── 08-customer-order-detail.png
│   ├── 09-admin-dashboard.png
│   ├── 10-admin-orders.png
│   ├── 11-admin-packages.png
│   └── 12-admin-order-detail.png
├── 04-Source-Code/
│   ├── DS-STORE-Source.zip        <- source Laravel (tanpa vendor/node_modules)
│   └── CARA-MENJALANKAN.md        <- instruksi install & demo
├── 05-Video/
│   ├── LINK-YOUTUBE.txt
│   ├── Checklist-Video.md
│   └── Script-Video-Presentasi.md (duplicate cepat untuk presenter)
└── 06-Deploy/
    └── PANDUAN-VERCEL.md          <- deploy gratis Vercel + Neon Postgres
```

## Daftar isi laporan (ringkas)

1. Pendahuluan — latar belakang, rumusan masalah, tujuan, manfaat, ruang lingkup.
2. Landasan teori — Laravel 12, MVC, Eloquent, Blade, autentikasi, validasi, keamanan.
3. Analisis dan perancangan — kebutuhan, alur pengguna, database, rute, desain UI.
4. Implementasi — lingkungan, instalasi, fitur utama, pemetaan fitur Laravel, perbaikan QA.
5. Pengujian — 18 test otomatis, 18 skenario manual, responsif 390–1440 px.
6. Penutup — kesimpulan dan saran.
7. Lampiran — kredensial demo, struktur folder, daftar screenshot, link video.

## Isi presentasi (15 slide)

Cover → Agenda → Latar belakang → Solusi → Arsitektur → Fitur Laravel → Database & rute → Beranda → Order → Dashboard → Pelacakan & QA → Pengujian → Cara menjalankan → Kelengkapan pengumpulan → Penutup.

## Catatan penting

- Aplikasi **sudah terverifikasi** dengan `php artisan test`: **18 test, 58 assertion, lulus**.
- Akun demo: `demo@dsstore.id` / `password` dan `admin@dsstore.id` / `password`.
- Jika password demo tidak cocok setelah source dijalankan ulang, jalankan:
  `php artisan db:seed --class=DemoDataSeeder --force`.
- Seluruh screenshot diambil dari aplikasi yang berjalan pada `127.0.0.1:8000` setelah perbaikan QA terakhir.
