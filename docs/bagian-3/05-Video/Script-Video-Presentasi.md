# SCRIPT VIDEO PRESENTASI — DS STORE

Durasi target: **±6 menit**. Bagi narasi ke 2–3 anggota agar tidak monoton.
Gunakan slide PPTX yang sesuai, lalu lanjut ke demo browser pada menit yang ditentukan.

---

## 00:00 — Cover & identitas (Slide 1)

**Narator 1:**
"Assalamu'alaikum, kami dari Kelompok `[Nama Kelompok]`. Bersama ini kami mempresentasikan hasil project website jasa joki akun Mobile Legends dengan nama **DS Store**, dibangun menggunakan Laravel 12. Mata kuliah `[Mata Kuliah]`, dosen pengampu `[Nama Dosen]`. Hadir juga anggota kami: `[Nama Anggota 1]`, `[Nama Anggota 2]`, dan `[Nama Anggota 3]`."

---

## 00:35 — Agenda (Slide 2)

**Narator 1:**
"Presentasi ini kami bagi menjadi enam bagian: latar belakang, solusi dan fitur, arsitektur serta penerapan Laravel, demonstrasi tampilan dan alur order, hasil pengujian dan perbaikan kualitas, lalu kelengkapan pengumpulan."

---

## 01:00 — Latar belakang & tujuan (Slide 3)

**Narator 2:**
"Masalah yang kami angkat adalah informasi harga joki yang masih tersebar di media sosial. Calon pelanggan harus menanyakan satu per satu paket, jumlah bintang, dan status pesanan. Di sisi admin, pertanyaan yang sama berulang kali muncul. Tujuan kami adalah membuat satu halaman yang menampilkan pricelist resmi Season 42, kalkulator biaya, alur pemesanan, dan pelacakan status yang jelas."

---

## 01:40 — Solusi & fitur (Slide 4)

**Narator 2:**
"Solusinya adalah DS Store. Fitur utamanya ada enam: carousel promo otomatis, pricelist transparan mulai Rp5.000 per bintang sampai Rp20.000 untuk Mythic Glory, form order tervalidasi, dashboard pelanggan dan admin, pelacakan tanpa login, serta workspace admin untuk mengelola pesanan dan paket. Tagline kami: Respon Cepat, Progres Kilat, dan 100% Amanah."

---

## 02:15 — Arsitektur & Laravel (Slide 5–6)

**Narator 1:**
"Aplikasi menggunakan pola Model–View–Controller. Browser menerima tampilan Blade, permintaan masuk lewat route dan middleware, controller memvalidasi serta memproses logika, lalu Eloquent berkomunikasi dengan SQLite. Fitur Laravel yang kami pakai antara lain routing—ada 26 rute—Controller, Eloquent relasi, migration, seeder, Blade component, middleware admin, Form Request, autentikasi, otorisasi, CSRF, rate limiting, dan pengujian otomatis."

---

## 03:00 — Demo beranda & pricelist (Slide 7–8, browser)

**Narator 3 (sambil demo):**
"Sekarang kami tunjukkan langsung. Di beranda terdapat banner promo Season 42. Carousel berjalan otomatis setiap lima detik, ada tombol panah, titik indikator, dan otomatis berhenti saat kursor di atas banner. Di bawahnya ada pricelist. Jika kami memilih Mythic dan mengubah jumlah bintang menjadi sepuluh, estimasi langsung berubah menjadi Rp120.000. Tombol 'Lanjutkan pesanan' membawa kita ke form order."

---

## 03:45 — Demo login & order (Slide 9, browser)

**Narator 2:**
"Karena membuat pesanan memerlukan akun, pengguna yang belum login diarahkan ke halaman masuk. Kami gunakan akun demo pelanggan: `demo@dsstore.id`, password `password`. Setelah masuk, form order meminta nickname, User ID, Server ID, metode login, dan WhatsApp. Sistem memvalidasi semua input. Total tetap dihitung ulang di server, jadi tidak bergantung pada JavaScript. Setelah disubmit, muncul kode pesanan dan halaman detail."

---

## 04:30 — Demo dashboard & pelacakan (Slide 10–11, browser)

**Narator 3:**
"Di dashboard pelanggan terlihat ringkasan pesanan, status berjalan, dan total transaksi. Setiap pesanan memiliki timeline progres. Untuk pelanggan yang tidak ingin login, tersedia halaman 'Lacak pesanan': cukup masukkan kode pesanan dan nomor WhatsApp. Di sisi admin, dashboard menampilkan jumlah pesanan, pendapatan lunas, status pesanan, dan paket terlaris. Admin juga bisa mengubah status pesanan; setiap perubahan otomatis tercatat sebagai riwayat progres."

---

## 05:15 — Pengujian & QA (Slide 12–13)

**Narator 1:**
"Kami menjalankan `vendor/bin/phpunit` dengan hasil **18 test lulus dan 58 assertion**. Pengujian mencakup beranda, validasi order, total server-side, otorisasi, login logout, registrasi, pelacakan, dan pengelolaan paket. Selama quality assurance kami juga menemukan dan memperbaiki beberapa masalah nyata: akun demo yang gagal login, tombol logout yang tersembunyi, judul bagian yang tertutup header saat klik menu, menu mobile yang tembus pandang, carousel yang tidak lanjut setelah tombol jeda, serta emblem rank yang masih memiliki background putih. Semua sudah diverifikasi ulang dengan 0 error konsol."

---

## 05:50 — Cara menjalankan & penutup (Slide 14–15)

**Narator 2:**
"Untuk menjalankan project, cukup install composer dan npm, salin `.env`, generate key, jalankan migrasi dan seeder, build aset, lalu `php artisan serve` pada port 8000. Kredensial demo tersedia di panduan. Kelengkapan pengumpulan Bagian 3 terdiri dari laporan Word, slide PowerPoint, dua belas screenshot, source code dalam ZIP, dan link video ini. Terima kasih atas perhatiannya."

---

## Catatan presenter

- Jika waktu kurang, potong demo dashboard admin dan perbanyak penjelasan testing.
- Jika waktu lebih, tunjukkan halaman register dan edit profil.
- Pastikan narator tidak membaca script kata per kata; gunakan poin kunci.
