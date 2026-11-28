LAPORAN PROJECT WEBSITE JOKI MOBILE LEGENDS

DS STORE — Laravel 12

Disusun sebagai salah satu syarat pengumpulan Bagian 3: Hasil Project, Laporan, dan Presentasi

| Keterangan | Data |
| --- | --- |
| Nama kelompok | [Nama Kelompok] |
| Anggota 1 / NIM | [Nama Anggota 1] / [NIM] |
| Anggota 2 / NIM | [Nama Anggota 2] / [NIM] |
| Anggota 3 / NIM | [Nama Anggota 3] / [NIM] |
| Anggota 4 / NIM (opsional) | [Nama Anggota 4] / [NIM] |
| Mata kuliah | [Mata Kuliah / Kode MK] |
| Dosen pengampu | [Nama Dosen] |
| Program studi / Universitas | [Prodi] / [Universitas] |
| Tanggal pengumpulan | [Tanggal] |

Isi semua data bertanda [ ... ] sebelum laporan dikumpulkan.

## LEMBAR PENGESAHAN

Laporan project website “DS Store — Jasa Joki Akun Mobile Legends” ini telah disusun dan diperiksa oleh tim penyusun sebagai bagian dari tugas kelompok.

| Disusun oleh | Tanda tangan |
| --- | --- |
| [Nama Anggota 1] | ................................ |
| [Nama Anggota 2] | ................................ |
| [Nama Anggota 3] | ................................ |

| Diperiksa oleh | Tanda tangan |
| --- | --- |
| [Nama Dosen Pengampu] | ................................ |

## KATA PENGANTAR

Puji syukur kami panjatkan atas selesainya penyusunan laporan project ini. Laporan ini mendokumentasikan proses perancangan, implementasi, dan pengujian website jasa joki akun Mobile Legends dengan nama DS Store.

Website dibangun menggunakan Laravel 12 dengan pendekatan Model–View–Controller, SQLite sebagai penyimpanan data, serta Vite untuk mengelola aset CSS dan JavaScript. Aplikasi menyediakan halaman promosi dengan carousel hero, pricelist interaktif, form pemesanan, pelacakan pesanan, dashboard pelanggan, dan dashboard admin.

Laporan ini juga menjadi catatan perbaikan kualitas selama proses quality assurance, termasuk perbaikan alur login/logout admin, navigasi anchor, tampilan drawer mobile, stabilitas carousel, dan penghapusan background pada emblem rank.

Kami mengucapkan terima kasih kepada dosen pengampu, rekan kelompok, dan semua pihak yang telah memberi masukan sehingga project ini dapat diselesaikan.

## DAFTAR ISI

Cover	i

Lembar Pengesahan	ii

Kata Pengantar	iii

Daftar Isi	iv

BAB I  PENDAHULUAN	1

1.1 Latar Belakang	1

1.2 Rumusan Masalah	2

1.3 Tujuan Penelitian	2

1.4 Manfaat	2

1.5 Ruang Lingkup	2

BAB II  LANDASAN TEORI	3

2.1 Laravel 12 dan Arsitektur MVC	3

2.2 Eloquent, Migration, dan Seeder	3

2.3 Blade, Component, dan Vite	4

2.4 Autentikasi, Otorisasi, dan Validasi	4

2.5 Keamanan Aplikasi Web	5

BAB III  ANALISIS DAN PERANCANGAN	6

3.1 Kebutuhan Fungsional dan Nonfungsional	6

3.2 Alur Pengguna	7

3.3 Perancangan Database	7

3.4 Rute Aplikasi	9

3.5 Desain Antarmuka	10

BAB IV  IMPLEMENTASI	11

4.1 Lingkungan Pengembangan	11

4.2 Instalasi dan Konfigurasi	11

4.3 Implementasi Fitur Utama	12

4.4 Penerapan Fitur Laravel	16

4.5 Perbaikan Kualitas dan QA	17

BAB V  PENGUJIAN	19

5.1 Strategi Pengujian	19

5.2 Pengujian Fungsional Manual	19

5.3 Pengujian Otomatis	21

5.4 Pengujian Responsif dan Browser	21

BAB VI  PENUTUP	23

DAFTAR PUSTAKA	24

LAMPIRAN	25

## BAB I  PENDAHULUAN

### 1.1 Latar Belakang

Mobile Legends: Bang Bang (MLBB) tetap menjadi salah satu game mobile kompetitif yang banyak dimainkan. Pemain yang memiliki keterbatasan waktu sering mencari jasa joki akun untuk membantu menaikkan rank. Namun, layanan sejenis umumnya masih menyampaikan informasi harga melalui media sosial sehingga calon pelanggan harus menanyakan satu per satu paket, jumlah bintang, dan status pesanan.

DS Store hadir sebagai website yang merapikan alur tersebut. Melalui website ini, pelanggan dapat melihat pricelist resmi Season 42, menghitung estimasi biaya secara mandiri, membuat pesanan, serta memantau progres tanpa harus terus-menerus menghubungi admin. Admin mendapat dashboard untuk mengelola pesanan dan paket joki.

### 1.2 Rumusan Masalah

Bagaimana merancang website joki MLBB dengan informasi harga yang transparan dan mudah dipahami?

Bagaimana menerapkan fitur Laravel—routing, Eloquent, migration, seeding, Blade, autentikasi, dan otorisasi—secara konsisten?

Bagaimana menyediakan alur pemesanan dan pelacakan status yang dapat dipertanggungjawabkan?

Bagaimana memastikan tampilan responsif dan alur login/logout bekerja tanpa kesalahan pada berbagai ukuran layar?

### 1.3 Tujuan Penelitian

Membangun website DS Store berbasis Laravel 12 dengan fitur promosi, pricelist, order, dan dashboard.

Menerapkan pola MVC dan fitur bawaan Laravel sesuai praktik pengembangan web modern.

Menyediakan pengalaman pengguna yang jelas, cepat, dan aman untuk pelanggan maupun admin.

Melakukan pengujian fungsional dan responsif sebagai bukti kelaikan aplikasi.

### 1.4 Manfaat

Bagi pelanggan: harga menjadi transparan, estimasi total dihitung otomatis, dan status pesanan bisa dipantau.

Bagi admin: pesanan dan paket dikelola dalam satu dashboard, sehingga mengurangi komunikasi berulang.

Bagi kelompok: menjadi media pembelajaran terpadu pengembangan aplikasi web dengan Laravel.

### 1.5 Ruang Lingkup

Project mencakup halaman publik (beranda, pricelist, lacak pesanan, login, dan register), area pelanggan (dashboard, detail pesanan, profil), serta area admin (dashboard, pesanan, paket, dan pelanggan). Pembayaran masih dilakukan manual melalui WhatsApp admin; website tidak memproses transaksi keuangan. Data akun game tidak disimpan sebagai password.

## BAB II  LANDASAN TEORI

### 2.1 Laravel 12 dan Arsitektur MVC

Laravel adalah framework PHP berbasis Model–View–Controller (Controller). Model mengelola data dan aturan bisnis, Controller menerima permintaan serta memilih respons, dan View menyajikan antarmuka. Pemisahan ini membuat kode lebih teratur dan mudah dikembangkan. Project ini menggunakan Laravel 12.73.0.

### 2.2 Eloquent, Migration, dan Seeder

Eloquent ORM memetakan tabel database ke kelas Model sehingga operasi data dapat ditulis dalam PHP. Migration berfungsi sebagai versi skema database yang dapat dijalankan berulang kali, sedangkan Seeder mengisi data awal seperti paket joki, slide promosi dan paket joki. Akun admin dibuat terpisah melalui perintah Artisan.

### 2.3 Blade, Component, dan Vite

Blade adalah template engine Laravel yang mendukung layout, partial, dan komponen. Project memakai komponen seperti <x-icon>, <x-input-error>, dan <x-flash>. Aset CSS/JS dikelola melalui Vite agar dapat dibangun menjadi berkas versi produksi.

### 2.4 Autentikasi, Otorisasi, dan Validasi

Autentikasi diimplementasikan dengan LoginController dan RegisterController. Otorisasi admin menggunakan middleware khusus EnsureUserIsAdmin. Validasi permintaan memakai Form Request untuk order dan aturan validate() pada controller lain.

// app/Http/Middleware/EnsureUserIsAdmin.php

if (! $request->user()?->isAdmin()) {

abort(403, 'Halaman ini hanya untuk admin DS Store.');

}

### 2.5 Keamanan Aplikasi Web

CSRF token pada seluruh form Laravel melalui direktif @csrf.

Password disimpan dengan hash Bcrypt/Argon melalui cast 'hashed'.

Rate limiter maksimal 5 percobaan login per email dan IP dalam 60 detik.

Throttle 10 permintaan per menit pada endpoint pelacakan pesanan.

Session di-regenerate setelah login dan logout untuk mencegah session fixation.

Password akun game tidak disimpan; detail akun hanya dibahas melalui WhatsApp admin.

## BAB III  ANALISIS DAN PERANCANGAN

### 3.1 Kebutuhan Fungsional dan Nonfungsional

| No | Kebutuhan Fungsional | Aktor |
| --- | --- | --- |
| F1 | Menampilkan hero carousel promosi dengan autoplay, arrow, dot, dan jeda saat hover | Pengunjung |
| F2 | Menampilkan pricelist resmi dan kalkulator estimasi total | Pengunjung |
| F3 | Membuat akun, login, dan logout | Pelanggan / Admin |
| F4 | Membuat pesanan dengan validasi data akun game | Pelanggan |
| F5 | Melihat riwayat dan detail status pesanan | Pelanggan |
| F6 | Mengubah profil dan password | Pelanggan |
| F7 | Melacak pesanan tanpa login dengan kode order dan WhatsApp | Pengunjung |
| F8 | Mengelola pesanan, paket, dan melihat data pelanggan | Admin |
| F9 | Membantu pengunjung lewat chatbot ringan untuk harga, order, dan lacak | Pengunjung |

| No | Kebutuhan Nonfungsional | Target |
| --- | --- | --- |
| N1 | Responsif | Tidak ada overflow horizontal pada 390–1440 px |
| N2 | Kinerja | Autoplay carousel 5,2 detik, transisi halus, tanpa error konsol |
| N3 | Keamanan | CSRF, hash password, otorisasi admin, rate limiting |
| N4 | Kejelasan | Harga per bintang dan total pesanan ditampilkan eksplisit |
| N5 | Aksesibilitas | Label form, skip link, status ARIA, kontras memadai |

### 3.2 Alur Pengguna

Pengunjung membuka beranda, memilih paket pada pricelist, lalu menentukan jumlah bintang.

Klik “Lanjutkan pesanan” menuju form order. Jika belum login, pengguna diarahkan ke halaman login.

Pengguna mengisi nickname, User ID, Server ID, metode login, WhatsApp, dan catatan opsional.

Sistem menghitung total_price di server, membuat kode order, dan menyimpan event awal “Pesanan dibuat”.

Admin membuka dashboard, memeriksa pesanan, lalu memperbarui status dan status pembayaran.

Setiap perubahan status menambah riwayat progres yang dilihat pelanggan pada detail pesanan.

### 3.3 Perancangan Database

Database SQLite terdiri dari enam tabel utama: users, hero_slides, service_packages, orders, order_events, serta tabel bawaan Laravel (cache, jobs). Relasi utama adalah orders.user_id → users.id dan orders.service_package_id → service_packages.id. Tabel order_events menyimpan jejak perubahan status pesanan.

| Tabel | Kolom Penting | Keterangan |
| --- | --- | --- |
| users | id, name, email, whatsapp, is_admin, password, last_login_at | Akun pelanggan dan admin |
| service_packages | id, slug, rank_key, price, unit, badge, is_active, is_featured | Paket dan harga joki |
| orders | id, order_code, user_id, service_package_id, stars, total_price, status, payment_status | Transaksi pesanan |
| order_events | id, order_id, status, title, note | Riwayat progres pesanan |
| hero_slides | id, title, description, image, price_note, sort_order, is_active | Konten carousel promosi |

### 3.4 Rute Aplikasi

Aplikasi terdaftar 26 rute. Rute utama dirangkum berikut:

| Grup | Rute | Fungsi |
| --- | --- | --- |
| Publik | GET / | Beranda + carousel + pricelist |
| Publik | GET/POST /lacak | Form dan proses pelacakan |
| Guest | GET/POST /login, /register | Autentikasi |
| Auth | POST /logout | Keluar dari akun |
| Auth | GET /order/{package}, POST /orders | Form dan penyimpanan pesanan |
| Auth | GET /dashboard, /dashboard/orders/{order}, /dashboard/profil | Area pelanggan |
| Admin | GET /admin | Dashboard admin |
| Admin | Resource /admin/orders | Kelola status dan pembayaran |
| Admin | Resource /admin/packages | Kelola paket joki |
| Admin | GET /admin/customers | Daftar pelanggan |

### 3.5 Desain Antarmuka

Model antarmuka yang dipakai adalah “Atelier”: permukaan charcoal dengan tekstur titik halus, aksen emas antik yang diambil langsung dari logo, dan tipografi serif untuk judul supaya tidak terasa seragam. Token warna didefinisikan dalam OKLCH dengan fallback hex. Susunannya sengaja tidak simetris: sudut kartu berbeda, kartu pricelist diberi offset vertikal, dan label memakai kalimat biasa, bukan uppercase. Hasilnya lebih mirip catatan editorial bengkel daripada templat dashboard generik.

| Peran warna | Nilai OKLCH | Hex fallback | Penggunaan |
| --- | --- | --- | --- |
| Accent / emas | oklch(0.728 0.138 89.7) | #C9A227 | Tombol utama, border aktif, price tag |
| Accent bright | oklch(0.843 0.119 91.5) | #E8C96B | Hover, kicker, ikon penting |
| Steel | oklch(0.716 0.017 239.2) | #9AA5AD | Teks sekunder, edge field |
| Charcoal | oklch(0.207 0.010 248.3) | #14181C | Latar halaman & panel |

Audit kontras menunjukkan teks tubuh 16,18:1, teks sekunder 11,68:1, teks muted 7,10:1, dan label tombol emas 7,52:1—seluruhnya di atas ambang WCAG AA 4,5:1.

| Elemen | Keputusan Desain |
| --- | --- |
| Hero | Banner lebar 16:9, scrim enam stop, headline putih, kicker emas |
| Pricelist | Kartu pelat silver dengan border emas saat dipilih + panel estimator |
| Dashboard | Sidebar charcoal, penanda aktif emas, panel metal |
| Mobile | Navigasi drawer charcoal dengan aksi utama emas |

## BAB IV  IMPLEMENTASI

### 4.1 Lingkungan Pengembangan

| Komponen | Versi / Keterangan |
| --- | --- |
| PHP | 8.3.37 (Laragon) |
| Laravel | 12.73.0 |
| Database | SQLite (database/database.sqlite) |
| Node & Vite | Node 20+, Vite 7.3.8 |
| Browser QA | Google Chrome / Playwright |
| Web server | php artisan serve pada 127.0.0.1:8000 |

### 4.2 Instalasi dan Konfigurasi

composer install

npm install

cp .env.example .env

php artisan key:generate

touch database/database.sqlite

php artisan migrate:fresh --seed --force

npm run build

php artisan serve --host=127.0.0.1 --port=8000

Konfigurasi merek, nomor WhatsApp, Instagram, dan FAQ ditempatkan di config/site.php agar tidak tersebar di banyak view.

### 4.3 Implementasi Fitur Utama

#### 4.3.1 Hero Carousel

Carousel memakai tiga slide dari tabel hero_slides. JavaScript memutar otomatis setiap 5,2 detik, menyediakan tombol prev/next, dot indikator, progress bar, jeda saat kursor berada di atas carousel, dukungan swipe, keyboard arrow, dan prefers-reduced-motion.

const canAutoplay = () =>

slides.length > 1 && !hovered &&

!document.hidden && !reduceMotion.matches;

Gambar 4.1 Halaman beranda dengan hero carousel dan pricelist

#### 4.3.2 Pricelist dan Kalkulator

Paket diambil dari ServicePackageSeeder. Panel estimator memperbarui nama paket, harga, emblem, dan total saat pengguna mengubah pilihan rank atau jumlah bintang. Total akhir tetap dihitung ulang di server agar tidak bergantung pada JavaScript.

$stars = $package->isStarPackage() ? $request->integer('stars') : 10;

$totalPrice = $package->isStarPackage()

? $package->price * $stars

: $package->price;

Gambar 4.2 Form pemesanan dengan ringkasan total

#### 4.3.3 Autentikasi dan Dashboard

Login berhasil mengarahkan admin ke /admin dan pelanggan ke /dashboard. Tombol keluar tersedia di header publik saat pengguna sudah login, pada sidebar workspace, dan pada drawer mobile. Seluruh form logout memakai method POST dan token CSRF.

Gambar 4.3 Halaman login DS Store

Gambar 4.4 Dashboard pelanggan

#### 4.3.4 Pelacakan dan Admin

Pelacakan mencocokkan kode pesanan dan nomor WhatsApp yang dinormalisasi ke format 62. Admin dapat mencari pesanan, menyaring status, mengubah status dan status pembayaran, serta menambah Catatan admin. Setiap perubahan status menulis OrderEvent baru.

Gambar 4.5 Hasil pelacakan pesanan tanpa login

Gambar 4.6 Dashboard admin

Gambar 4.7 Detail pesanan dan pembaruan status pada area admin

#### 4.3.5 Chatbot Publik

Chatbot ringan ditambahkan pada layout publik sebagai komponen Blade. Tombol mengambang membuka panel yang menerima pertanyaan bebas maupun empat tombol cepat. Mesin jawaban mengenali niat pengguna: harga, paket tertentu, jumlah bintang, cara order, pembayaran, durasi, keamanan, request hero, registrasi, pelacakan, sapaan, dan ucapan terima kasih. Harga dan FAQ diteruskan dari server lewat atribut data, sehingga bot tidak perlu menyimpan data duplikat. Jika dua kali berturut-turut tidak mengenali pertanyaan, bot berhenti mengulang jawaban generik dan langsung menawarkan sambungan ke admin via WhatsApp. Panel menutup dengan Escape atau klik di luar.

Gambar 4.8 Panel chatbot pada halaman publik

### 4.4 Penerapan Fitur Laravel

| Fitur Laravel | Implementasi di Project |
| --- | --- |
| Routing | 26 rute di routes/web.php, termasuk route group guest/auth/admin dan resource route |
| Controller | HomeController, OrderController, DashboardController, Admin\*Controller, Auth\*Controller |
| Model & Eloquent | User, ServicePackage, Order, OrderEvent, HeroSlide dengan relasi dan accessor |
| Migration | Skema users tambahan, hero_slides, service_packages, orders, order_events |
| Seeder | HeroSlideSeeder, ServicePackageSeeder |
| Blade & Component | Layout app/workspace/auth, partial home, komponen icon/flash/input-error |
| Middleware | auth, guest, admin (EnsureUserIsAdmin), throttle |
| Form Request | StoreOrderRequest dengan aturan dan pesan error Bahasa Indonesia |
| Validation | Login, register, profil, paket, dan pelacakan divalidasi server-side |
| Auth & Session | Auth::attempt, regenerate session, remember me, rate limiting |
| CSRF | @csrf seluruh form termasuk logout |
| Helper & Config | config/site.php diakses melalui config('site.*') di seluruh view |
| Testing | 18 feature/unit test dengan RefreshDatabase |

### 4.5 Perbaikan Kualitas dan QA

Selama quality assurance ditemukan dan diperbaiki beberapa masalah. Perbaikan ini dicatat agar tim memiliki jejak keputusan dan tidak mengulang kesalahan yang sama.

| Masalah | Akar Masalah | Perbaikan |
| --- | --- | --- |
| Akun demo tidak sesuai kebutuhan | Akun demo bersifat umum dan tidak mewakili pemilik toko | Akun demo dihapus; admin dibuat dengan perintah admin:create menggunakan email pemilik |
| Tombol login/logout sulit ditemukan | Logout hanya ada di sidebar workspace; header publik tidak punya aksi keluar | Ditambah tombol Keluar di header publik dan di drawer mobile |
| Judul bagian tertutup header saat klik menu anchor | Sticky header tidak memiliki scroll offset | html.scroll-padding-top = var(--header-offset) pada 128px desktop / 112px mobile |
| Header seolah 'glitch' saat scroll | Announcement bar menyusut dan menggeser konten secara tiba-tiba | Tinggi header dibuat konstan; hanya warna dan bayangan yang berubah |
| Teks hero tembus ke menu mobile | Background dropdown transparan 0.98 | Background dropdown dibuat solid #060E19 |
| Tombol jeda pada carousel terasa berlebih | Kontrol pause mengganggu visual hero | Tombol jeda dihapus; autoplay tetap berhenti saat hover dan lanjut saat kursor keluar |
| Emblem rank memiliki kotak putih | Aset sumber berbackground putih | Background dihapus dengan flood fill + edge decontamination menjadi WebP transparan |
| Hero carousel meluber pada lebar 861–1084px | aspect-ratio 16:9 berkelahi dengan min-height 610px | Ditambahkan width:100% pada .hero-carousel |

Setelah perbaikan, selenium/Playwright menunjukkan 0 console error, 0 page error, dan 0 respons HTTP ≥ 400 pada halaman yang diuji.

## BAB V  PENGUJIAN

### 5.1 Strategi Pengujian

Pengujian dilakukan pada tiga lapis: (1) unit/feature test Laravel dengan SQLite in-memory, (2) uji interaksi browser otomatis dengan Playwright, dan (3) uji visual manual pada beberapa lebar layar. Pengujian difokuskan pada alur kritis: autentikasi, order, otorisasi admin, pelacakan, dan navigasi.

### 5.2 Pengujian Fungsional Manual / Browser

| No | Skenario | Hasil |
| --- | --- | --- |
| 1 | Autoplay carousel berpindah slide setelah ±5,2 detik | Lulus |
| 2 | Tombol prev/next dan dot mengganti slide aktif | Lulus |
| 3 | Autoplay berhenti saat kursor di atas carousel dan lanjut saat kursor keluar | Lulus |
| 4 | Klik menu Pricelist mengarah ke #pricelist dengan judul tidak tertutup header | Lulus |
| 5 | Drawer mobile terbuka, tautan menutup drawer setelah diklik | Lulus |
| 6 | Login admin menuju /dashboard dan /admin | Lulus |
| 7 | Login admin menuju /admin | Lulus |
| 8 | Logout dari header publik, sidebar workspace, dan drawer mobile | Lulus |
| 9 | Kalkulator pricelist mengubah total sesuai rank dan bintang | Lulus |
| 10 | Form order menolak User ID bukan angka | Lulus |
| 11 | Total Full Grading Mythic tetap Rp150.000 walaupun stars=10 | Lulus |
| 12 | Pelacakan dengan kode order + WhatsApp yang benar menampilkan timeline | Lulus |
| 13 | Pelacakan dengan WhatsApp salah menampilkan error | Lulus |
| 14 | Pelanggan tidak dapat membuka detail order pelanggan lain (404) | Lulus |
| 15 | Pelanggan tidak dapat membuka /admin (403) | Lulus |
| 16 | Admin memperbarui status pesanan dan event timeline tercatat | Lulus |
| 17 | Paket yang sudah memiliki order tidak bisa dihapus | Lulus |
| 18 | Tidak ada overflow horizontal pada 390, 768, 1024, dan 1440 px | Lulus |

### 5.3 Pengujian Otomatis

Perintah: vendor/bin/phpunit

Tests:    18 passed (57 assertions)

Duration: ~20s

PASS  Tests\Unit\ExampleTest

PASS  Tests\Feature\AdminTest            (5 tests)

PASS  Tests\Feature\ExampleTest          (1 test)

PASS  Tests\Feature\StorefrontTest       (11 tests)

Pengujian otomatis mencakup halaman beranda, validasi order, perhitungan total server-side, perlindungan order antarpelanggan, autentikasi admin, registrasi, otorisasi admin, pembaruan status, pembuatan paket, dan perlindungan penghapusan paket.

### 5.4 Pengujian Responsif dan Browser

| Lebar | Header | Overflow | Posisi #pricelist setelah klik | Error |
| --- | --- | --- | --- | --- |
| 1440 px | 118 px | 0 | 128 px (sesuai scroll-padding) | Tidak ada |
| 1024 px | 112 px | 0 | 111,7 px | Tidak ada |
| 768 px | 112 px | 0 | 112 px | Tidak ada |
| 390 px | 108 px | 0 | 112 px | Tidak ada |

Pengujian browser juga memeriksa console error, page error, request gagal, dan respons HTTP 4xx/5xx. Seluruhnya bernilai nol pada alur yang diuji.

Gambar 5.1 Tampilan beranda pada lebar 390 px

Gambar 5.2 Navigasi drawer mobile

## BAB VI  PENUTUP

### 6.1 Kesimpulan

Website DS Store berhasil dibangun dengan Laravel 12 dan memenuhi kebutuhan utama: menampilkan promo Season 42, menyediakan pricelist resmi, mengelola pesanan, memberikan dashboard pelanggan dan admin, serta memungkinkan pelacakan tanpa login. Fitur Laravel digunakan secara konsisten mulai dari routing, MVC, Eloquent, migration, seeder, Blade, middleware, validasi, autentikasi, otorisasi, hingga pengujian otomatis.

Proses QA menemukan beberapa masalah nyata—tombol logout tersembunyi, anchor tertutup header, dropdown mobile tembus pandang, dan carousel tidak melanjutkan pada hero. Seluruh masalah telah diperbaiki dan diverifikasi ulang dengan pengujian otomatis maupun browser.

### 6.2 Saran

Menambahkan verifikasi pembayaran otomatis atau integrasi gateway jika layanan berkembang.

Menambahkan notifikasi WhatsApp otomatis saat status pesanan berubah.

Menyediakan upload bukti pembayaran di area pelanggan.

Menambahkan pengujian aksesibilitas otomatis dengan axe/Lighthouse sebagai bagian dari CI.

Mengganti gambar promosi dengan aset resmi MLBB bila tersedia agar konsistensi merek lebih kuat.

## DAFTAR PUSTAKA

Laravel Documentation. (2026). Laravel 12.x — Routing, Eloquent, Blade, Validation, Authentication. https://laravel.com/docs/12.x

Vite Documentation. (2026). Why Vite / Asset Handling. https://vite.dev/guide/

MDN Web Docs. (2026). CSS scroll-padding, prefers-reduced-motion, Web Animations API. https://developer.mozilla.org/

Mozilla / Google. (2026). Web Accessibility and Responsive Design guidance. https://web.dev/

Poizy Store. (2026). Referensi konsep layanan joki Mobile Legends. https://www.poizystore.id/id-id

Mobile Legends: Bang Bang. (2026). Informasi rank dan musim kompetitif.

## LAMPIRAN A  AKUN ADMIN

| Peran | Email | Cara membuat | Tujuan |
| --- | --- | --- | --- |
| Admin | a3snewbaru990@gmail.com | php artisan admin:create | Mengelola pesanan dan paket |
| Pelanggan | Dibuat mandiri | Halaman /register | Membuat dan memantau pesanan |

Tidak ada akun demo. Password admin dibuat interaktif melalui perintah Artisan dan tidak disimpan di dalam repository.

## LAMPIRAN B  STRUKTUR PENGUMPULAN

docs/bagian-3/

00-PANDUAN-PENGUMPULAN.md

01-Laporan/       -> Laporan-DS-Store.docx, Laporan-DS-Store.md

02-Presentasi/    -> Presentasi-DS-Store.pptx, Script-Video-Presentasi.md

03-Screenshots/   -> 01..12 screenshot

04-Source-Code/   -> DS-STORE-Source.zip, CARA-MENJALANKAN.md

05-Video/         -> LINK-YOUTUBE.txt, Checklist-Video.md

DATA-KELOMPOK.md

## LAMPIRAN C  DAFTAR SCREENSHOT

| File | Konten |
| --- | --- |
| 01-home-desktop.png | Beranda desktop: hero + pricelist |
| 02-home-mobile.png | Beranda mobile |
| 03-mobile-nav.png | Drawer navigasi mobile |
| 04-login.png | Halaman login DS Store (model Atelier) |
| 05-order-form.png | Form order dan ringkasan total |
| 06-tracking-result.png | Hasil pelacakan pesanan |
| 07-customer-dashboard.png | Dashboard pelanggan |
| 08-customer-order-detail.png | Detail pesanan + timeline |
| 09-admin-dashboard.png | Dashboard admin |
| 10-admin-orders.png | Daftar pesanan admin |
| 11-admin-packages.png | Kelola paket joki |
| 12-admin-order-detail.png | Detail pesanan dan update status admin |
| 13-chatbot.png | Panel chatbot dan jawaban harga |

## LAMPIRAN D  LINK PRESENTASI VIDEO

Link YouTube: [Tempel link video presentasi di sini]

Script narasi dan checklist upload tersedia pada folder 05-Video. Pastikan video bersifat unlisted/public sesuai instruksi dosen dan dimasukkan ke dalam folder kelompok.
