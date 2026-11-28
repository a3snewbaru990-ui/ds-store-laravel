"""Generate the DS Store assignment report (.docx) from one source of truth."""

from pathlib import Path

from docx import Document
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_BREAK
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Inches, Pt, RGBColor

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "docs" / "bagian-3" / "01-Laporan" / "Laporan-DS-Store.docx"
SHOTS = ROOT / "docs" / "bagian-3" / "03-Screenshots"
LOGO = ROOT / "public" / "images" / "logo-mark.png"

CYAN = RGBColor(6, 150, 178)
GOLD = RGBColor(191, 130, 16)
DARK = RGBColor(15, 23, 42)
MUTED = RGBColor(90, 104, 120)


def shade(cell, fill: str) -> None:
    tc_pr = cell._tc.get_or_add_tcPr()
    shd = OxmlElement("w:shd")
    shd.set(qn("w:val"), "clear")
    shd.set(qn("w:fill"), fill)
    tc_pr.append(shd)


def set_cell(cell, text: str, *, bold=False, color=None, size=9, align=None) -> None:
    cell.text = ""
    p = cell.paragraphs[0]
    if align is not None:
        p.alignment = align
    run = p.add_run(text)
    run.bold = bold
    run.font.size = Pt(size)
    run.font.name = "Calibri"
    if color is not None:
        run.font.color.rgb = color


def add_table(doc, headers, rows, widths=None):
    table = doc.add_table(rows=1, cols=len(headers))
    table.style = "Table Grid"
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    hdr = table.rows[0].cells
    for i, text in enumerate(headers):
        set_cell(hdr[i], text, bold=True, color=RGBColor(255, 255, 255), size=9)
        shade(hdr[i], "0F172A")
    for row in rows:
        cells = table.add_row().cells
        for i, value in enumerate(row):
            set_cell(cells[i], str(value), size=9)
    if widths:
        for row in table.rows:
            for i, width in enumerate(widths):
                row.cells[i].width = Inches(width)
    doc.add_paragraph()
    return table


def add_code(doc, text: str) -> None:
    for line in text.strip("\n").split("\n"):
        p = doc.add_paragraph()
        p.paragraph_format.space_after = Pt(0)
        p.paragraph_format.left_indent = Inches(0.25)
        run = p.add_run(line if line else " ")
        run.font.name = "Consolas"
        run.font.size = Pt(8.5)
        run.font.color.rgb = DARK
    doc.add_paragraph()


def add_bullets(doc, items, level=0):
    style = "List Bullet" if level == 0 else "List Bullet 2"
    for item in items:
        doc.add_paragraph(item, style=style)


def add_numbered(doc, items):
    for item in items:
        doc.add_paragraph(item, style="List Number")


def add_image(doc, name: str, caption: str, width=6.0) -> None:
    path = SHOTS / name
    if not path.exists():
        return
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.add_run().add_picture(str(path), width=Inches(width))
    cap = doc.add_paragraph()
    cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = cap.add_run(caption)
    run.italic = True
    run.font.size = Pt(8.5)
    run.font.color.rgb = MUTED


def add_page_field(paragraph) -> None:
    run = paragraph.add_run()
    fld_char1 = OxmlElement("w:fldChar")
    fld_char1.set(qn("w:fldCharType"), "begin")
    instr = OxmlElement("w:instrText")
    instr.set(qn("xml:space"), "preserve")
    instr.text = " PAGE "
    fld_char2 = OxmlElement("w:fldChar")
    fld_char2.set(qn("w:fldCharType"), "end")
    run._r.append(fld_char1)
    run._r.append(instr)
    run._r.append(fld_char2)


def configure(doc: Document) -> None:
    section = doc.sections[0]
    section.top_margin = Inches(0.85)
    section.bottom_margin = Inches(0.8)
    section.left_margin = Inches(0.9)
    section.right_margin = Inches(0.9)

    normal = doc.styles["Normal"]
    normal.font.name = "Calibri"
    normal.font.size = Pt(10.5)
    normal.paragraph_format.line_spacing = 1.35
    normal.paragraph_format.space_after = Pt(6)

    for name, size, color in [
        ("Title", 26, DARK),
        ("Heading 1", 16, CYAN),
        ("Heading 2", 13, DARK),
        ("Heading 3", 11, GOLD),
    ]:
        style = doc.styles[name]
        style.font.name = "Calibri"
        style.font.size = Pt(size)
        style.font.color.rgb = color
        style.font.bold = True

    footer = section.footer.paragraphs[0]
    footer.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r = footer.add_run("DS Store — Laporan Project  |  Halaman ")
    r.font.size = Pt(8)
    r.font.color.rgb = MUTED
    add_page_field(footer)


def cover(doc: Document) -> None:
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.add_run().add_picture(str(LOGO), width=Inches(1.1))

    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.paragraph_format.space_before = Pt(18)
    r = p.add_run("LAPORAN PROJECT WEBSITE JOKI MOBILE LEGENDS")
    r.bold = True
    r.font.size = Pt(20)
    r.font.color.rgb = DARK

    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r = p.add_run("DS STORE — Laravel 12")
    r.bold = True
    r.font.size = Pt(15)
    r.font.color.rgb = CYAN

    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r = p.add_run(
        "Disusun sebagai salah satu syarat pengumpulan Bagian 3: "
        "Hasil Project, Laporan, dan Presentasi"
    )
    r.italic = True
    r.font.size = Pt(10)
    r.font.color.rgb = MUTED

    doc.add_paragraph()
    add_table(
        doc,
        ["Keterangan", "Data"],
        [
            ["Nama kelompok", "[Nama Kelompok]"],
            ["Anggota 1 / NIM", "[Nama Anggota 1] / [NIM]"],
            ["Anggota 2 / NIM", "[Nama Anggota 2] / [NIM]"],
            ["Anggota 3 / NIM", "[Nama Anggota 3] / [NIM]"],
            ["Anggota 4 / NIM (opsional)", "[Nama Anggota 4] / [NIM]"],
            ["Mata kuliah", "[Mata Kuliah / Kode MK]"],
            ["Dosen pengampu", "[Nama Dosen]"],
            ["Program studi / Universitas", "[Prodi] / [Universitas]"],
            ["Tanggal pengumpulan", "[Tanggal]"],
        ],
        widths=[2.3, 4.3],
    )

    note = doc.add_paragraph()
    note.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r = note.add_run("Isi semua data bertanda [ ... ] sebelum laporan dikumpulkan.")
    r.font.size = Pt(9)
    r.italic = True
    r.font.color.rgb = GOLD

    doc.add_page_break()


def approval(doc: Document) -> None:
    doc.add_heading("LEMBAR PENGESAHAN", level=1)
    doc.add_paragraph(
        "Laporan project website “DS Store — Jasa Joki Akun Mobile Legends” ini "
        "telah disusun dan diperiksa oleh tim penyusun sebagai bagian dari tugas kelompok."
    )
    add_table(
        doc,
        ["Disusun oleh", "Tanda tangan"],
        [
            ["[Nama Anggota 1]", "................................"],
            ["[Nama Anggota 2]", "................................"],
            ["[Nama Anggota 3]", "................................"],
        ],
        widths=[3.2, 3.4],
    )
    doc.add_paragraph()
    add_table(
        doc,
        ["Diperiksa oleh", "Tanda tangan"],
        [["[Nama Dosen Pengampu]", "................................"]],
        widths=[3.2, 3.4],
    )
    doc.add_page_break()


def preface(doc: Document) -> None:
    doc.add_heading("KATA PENGANTAR", level=1)
    doc.add_paragraph(
        "Puji syukur kami panjatkan atas selesainya penyusunan laporan project ini. "
        "Laporan ini mendokumentasikan proses perancangan, implementasi, dan pengujian "
        "website jasa joki akun Mobile Legends dengan nama DS Store."
    )
    doc.add_paragraph(
        "Website dibangun menggunakan Laravel 12 dengan pendekatan Model–View–Controller, "
        "SQLite sebagai penyimpanan data, serta Vite untuk mengelola aset CSS dan JavaScript. "
        "Aplikasi menyediakan halaman promosi dengan carousel hero, pricelist interaktif, "
        "form pemesanan, pelacakan pesanan, dashboard pelanggan, dan dashboard admin."
    )
    doc.add_paragraph(
        "Laporan ini juga menjadi catatan perbaikan kualitas selama proses quality assurance, "
        "termasuk perbaikan alur login/logout admin, navigasi anchor, tampilan drawer mobile, "
        "stabilitas carousel, dan penghapusan background pada emblem rank."
    )
    doc.add_paragraph(
        "Kami mengucapkan terima kasih kepada dosen pengampu, rekan kelompok, dan semua pihak "
        "yang telah memberi masukan sehingga project ini dapat diselesaikan."
    )
    doc.add_page_break()


def toc(doc: Document) -> None:
    doc.add_heading("DAFTAR ISI", level=1)
    entries = [
        ("Cover", "i"),
        ("Lembar Pengesahan", "ii"),
        ("Kata Pengantar", "iii"),
        ("Daftar Isi", "iv"),
        ("BAB I  PENDAHULUAN", "1"),
        ("  1.1 Latar Belakang", "1"),
        ("  1.2 Rumusan Masalah", "2"),
        ("  1.3 Tujuan Penelitian", "2"),
        ("  1.4 Manfaat", "2"),
        ("  1.5 Ruang Lingkup", "2"),
        ("BAB II  LANDASAN TEORI", "3"),
        ("  2.1 Laravel 12 dan Arsitektur MVC", "3"),
        ("  2.2 Eloquent, Migration, dan Seeder", "3"),
        ("  2.3 Blade, Component, dan Vite", "4"),
        ("  2.4 Autentikasi, Otorisasi, dan Validasi", "4"),
        ("  2.5 Keamanan Aplikasi Web", "5"),
        ("BAB III  ANALISIS DAN PERANCANGAN", "6"),
        ("  3.1 Kebutuhan Fungsional dan Nonfungsional", "6"),
        ("  3.2 Alur Pengguna", "7"),
        ("  3.3 Perancangan Database", "7"),
        ("  3.4 Rute Aplikasi", "9"),
        ("  3.5 Desain Antarmuka", "10"),
        ("BAB IV  IMPLEMENTASI", "11"),
        ("  4.1 Lingkungan Pengembangan", "11"),
        ("  4.2 Instalasi dan Konfigurasi", "11"),
        ("  4.3 Implementasi Fitur Utama", "12"),
        ("  4.4 Penerapan Fitur Laravel", "16"),
        ("  4.5 Perbaikan Kualitas dan QA", "17"),
        ("BAB V  PENGUJIAN", "19"),
        ("  5.1 Strategi Pengujian", "19"),
        ("  5.2 Pengujian Fungsional Manual", "19"),
        ("  5.3 Pengujian Otomatis", "21"),
        ("  5.4 Pengujian Responsif dan Browser", "21"),
        ("BAB VI  PENUTUP", "23"),
        ("DAFTAR PUSTAKA", "24"),
        ("LAMPIRAN", "25"),
    ]
    for text, page in entries:
        p = doc.add_paragraph()
        p.paragraph_format.space_after = Pt(2)
        p.add_run(text)
        tab = p.add_run("\t" + page)
        tab.tab_stops = None
    doc.add_page_break()


def chapter1(doc: Document) -> None:
    doc.add_heading("BAB I  PENDAHULUAN", level=1)
    doc.add_heading("1.1 Latar Belakang", level=2)
    doc.add_paragraph(
        "Mobile Legends: Bang Bang (MLBB) tetap menjadi salah satu game mobile kompetitif "
        "yang banyak dimainkan. Pemain yang memiliki keterbatasan waktu sering mencari jasa "
        "joki akun untuk membantu menaikkan rank. Namun, layanan sejenis umumnya masih "
        "menyampaikan informasi harga melalui media sosial sehingga calon pelanggan harus "
        "menanyakan satu per satu paket, jumlah bintang, dan status pesanan."
    )
    doc.add_paragraph(
        "DS Store hadir sebagai website yang merapikan alur tersebut. Melalui website ini, "
        "pelanggan dapat melihat pricelist resmi Season 42, menghitung estimasi biaya secara "
        "mandiri, membuat pesanan, serta memantau progres tanpa harus terus-menerus "
        "menghubungi admin. Admin mendapat dashboard untuk mengelola pesanan dan paket joki."
    )

    doc.add_heading("1.2 Rumusan Masalah", level=2)
    add_numbered(
        doc,
        [
            "Bagaimana merancang website joki MLBB dengan informasi harga yang transparan dan mudah dipahami?",
            "Bagaimana menerapkan fitur Laravel—routing, Eloquent, migration, seeding, Blade, autentikasi, dan otorisasi—secara konsisten?",
            "Bagaimana menyediakan alur pemesanan dan pelacakan status yang dapat dipertanggungjawabkan?",
            "Bagaimana memastikan tampilan responsif dan alur login/logout bekerja tanpa kesalahan pada berbagai ukuran layar?",
        ],
    )

    doc.add_heading("1.3 Tujuan Penelitian", level=2)
    add_bullets(
        doc,
        [
            "Membangun website DS Store berbasis Laravel 12 dengan fitur promosi, pricelist, order, dan dashboard.",
            "Menerapkan pola MVC dan fitur bawaan Laravel sesuai praktik pengembangan web modern.",
            "Menyediakan pengalaman pengguna yang jelas, cepat, dan aman untuk pelanggan maupun admin.",
            "Melakukan pengujian fungsional dan responsif sebagai bukti kelaikan aplikasi.",
        ],
    )

    doc.add_heading("1.4 Manfaat", level=2)
    add_bullets(
        doc,
        [
            "Bagi pelanggan: harga menjadi transparan, estimasi total dihitung otomatis, dan status pesanan bisa dipantau.",
            "Bagi admin: pesanan dan paket dikelola dalam satu dashboard, sehingga mengurangi komunikasi berulang.",
            "Bagi kelompok: menjadi media pembelajaran terpadu pengembangan aplikasi web dengan Laravel.",
        ],
    )

    doc.add_heading("1.5 Ruang Lingkup", level=2)
    doc.add_paragraph(
        "Project mencakup halaman publik (beranda, pricelist, lacak pesanan, login, dan register), "
        "area pelanggan (dashboard, detail pesanan, profil), serta area admin (dashboard, pesanan, "
        "paket, dan pelanggan). Pembayaran masih dilakukan manual melalui WhatsApp admin; "
        "website tidak memproses transaksi keuangan. Data akun game tidak disimpan sebagai password."
    )
    doc.add_page_break()


def chapter2(doc: Document) -> None:
    doc.add_heading("BAB II  LANDASAN TEORI", level=1)

    doc.add_heading("2.1 Laravel 12 dan Arsitektur MVC", level=2)
    doc.add_paragraph(
        "Laravel adalah framework PHP berbasis Model–View–Controller (Controller). Model mengelola "
        "data dan aturan bisnis, Controller menerima permintaan serta memilih respons, dan View "
        "menyajikan antarmuka. Pemisahan ini membuat kode lebih teratur dan mudah dikembangkan. "
        "Project ini menggunakan Laravel 12.73.0."
    )

    doc.add_heading("2.2 Eloquent, Migration, dan Seeder", level=2)
    doc.add_paragraph(
        "Eloquent ORM memetakan tabel database ke kelas Model sehingga operasi data dapat "
        "ditulis dalam PHP. Migration berfungsi sebagai versi skema database yang dapat "
        "dijalankan berulang kali, sedangkan Seeder mengisi data awal seperti paket joki, "
        "slide promosi dan paket joki. Akun admin dibuat terpisah melalui perintah Artisan."
    )

    doc.add_heading("2.3 Blade, Component, dan Vite", level=2)
    doc.add_paragraph(
        "Blade adalah template engine Laravel yang mendukung layout, partial, dan komponen. "
        "Project memakai komponen seperti <x-icon>, <x-input-error>, dan <x-flash>. Aset CSS/JS "
        "dikelola melalui Vite agar dapat dibangun menjadi berkas versi produksi."
    )

    doc.add_heading("2.4 Autentikasi, Otorisasi, dan Validasi", level=2)
    doc.add_paragraph(
        "Autentikasi diimplementasikan dengan LoginController dan RegisterController. Otorisasi "
        "admin menggunakan middleware khusus EnsureUserIsAdmin. Validasi permintaan memakai "
        "Form Request untuk order dan aturan validate() pada controller lain."
    )
    add_code(
        doc,
        """
// app/Http/Middleware/EnsureUserIsAdmin.php
if (! $request->user()?->isAdmin()) {
    abort(403, 'Halaman ini hanya untuk admin DS Store.');
}
""",
    )

    doc.add_heading("2.5 Keamanan Aplikasi Web", level=2)
    add_bullets(
        doc,
        [
            "CSRF token pada seluruh form Laravel melalui direktif @csrf.",
            "Password disimpan dengan hash Bcrypt/Argon melalui cast 'hashed'.",
            "Rate limiter maksimal 5 percobaan login per email dan IP dalam 60 detik.",
            "Throttle 10 permintaan per menit pada endpoint pelacakan pesanan.",
            "Session di-regenerate setelah login dan logout untuk mencegah session fixation.",
            "Password akun game tidak disimpan; detail akun hanya dibahas melalui WhatsApp admin.",
        ],
    )
    doc.add_page_break()


def chapter3(doc: Document) -> None:
    doc.add_heading("BAB III  ANALISIS DAN PERANCANGAN", level=1)

    doc.add_heading("3.1 Kebutuhan Fungsional dan Nonfungsional", level=2)
    add_table(
        doc,
        ["No", "Kebutuhan Fungsional", "Aktor"],
        [
            ["F1", "Menampilkan hero carousel promosi dengan autoplay, arrow, dot, dan jeda saat hover", "Pengunjung"],
            ["F2", "Menampilkan pricelist resmi dan kalkulator estimasi total", "Pengunjung"],
            ["F3", "Membuat akun, login, dan logout", "Pelanggan / Admin"],
            ["F4", "Membuat pesanan dengan validasi data akun game", "Pelanggan"],
            ["F5", "Melihat riwayat dan detail status pesanan", "Pelanggan"],
            ["F6", "Mengubah profil dan password", "Pelanggan"],
            ["F7", "Melacak pesanan tanpa login dengan kode order dan WhatsApp", "Pengunjung"],
            ["F8", "Mengelola pesanan, paket, dan melihat data pelanggan", "Admin"],
            ["F9", "Membantu pengunjung lewat chatbot ringan untuk harga, order, dan lacak", "Pengunjung"],
        ],
        widths=[0.5, 4.9, 1.2],
    )
    add_table(
        doc,
        ["No", "Kebutuhan Nonfungsional", "Target"],
        [
            ["N1", "Responsif", "Tidak ada overflow horizontal pada 390–1440 px"],
            ["N2", "Kinerja", "Autoplay carousel 5,2 detik, transisi halus, tanpa error konsol"],
            ["N3", "Keamanan", "CSRF, hash password, otorisasi admin, rate limiting"],
            ["N4", "Kejelasan", "Harga per bintang dan total pesanan ditampilkan eksplisit"],
            ["N5", "Aksesibilitas", "Label form, skip link, status ARIA, kontras memadai"],
        ],
        widths=[0.5, 2.7, 3.4],
    )

    doc.add_heading("3.2 Alur Pengguna", level=2)
    add_numbered(
        doc,
        [
            "Pengunjung membuka beranda, memilih paket pada pricelist, lalu menentukan jumlah bintang.",
            "Klik “Lanjutkan pesanan” menuju form order. Jika belum login, pengguna diarahkan ke halaman login.",
            "Pengguna mengisi nickname, User ID, Server ID, metode login, WhatsApp, dan catatan opsional.",
            "Sistem menghitung total_price di server, membuat kode order, dan menyimpan event awal “Pesanan dibuat”.",
            "Admin membuka dashboard, memeriksa pesanan, lalu memperbarui status dan status pembayaran.",
            "Setiap perubahan status menambah riwayat progres yang dilihat pelanggan pada detail pesanan.",
        ],
    )

    doc.add_heading("3.3 Perancangan Database", level=2)
    doc.add_paragraph(
        "Database SQLite terdiri dari enam tabel utama: users, hero_slides, service_packages, "
        "orders, order_events, serta tabel bawaan Laravel (cache, jobs). Relasi utama adalah "
        "orders.user_id → users.id dan orders.service_package_id → service_packages.id. "
        "Tabel order_events menyimpan jejak perubahan status pesanan."
    )
    add_table(
        doc,
        ["Tabel", "Kolom Penting", "Keterangan"],
        [
            ["users", "id, name, email, whatsapp, is_admin, password, last_login_at", "Akun pelanggan dan admin"],
            ["service_packages", "id, slug, rank_key, price, unit, badge, is_active, is_featured", "Paket dan harga joki"],
            ["orders", "id, order_code, user_id, service_package_id, stars, total_price, status, payment_status", "Transaksi pesanan"],
            ["order_events", "id, order_id, status, title, note", "Riwayat progres pesanan"],
            ["hero_slides", "id, title, description, image, price_note, sort_order, is_active", "Konten carousel promosi"],
        ],
        widths=[1.2, 3.1, 2.3],
    )

    doc.add_heading("3.4 Rute Aplikasi", level=2)
    doc.add_paragraph("Aplikasi terdaftar 26 rute. Rute utama dirangkum berikut:")
    add_table(
        doc,
        ["Grup", "Rute", "Fungsi"],
        [
            ["Publik", "GET /", "Beranda + carousel + pricelist"],
            ["Publik", "GET/POST /lacak", "Form dan proses pelacakan"],
            ["Guest", "GET/POST /login, /register", "Autentikasi"],
            ["Auth", "POST /logout", "Keluar dari akun"],
            ["Auth", "GET /order/{package}, POST /orders", "Form dan penyimpanan pesanan"],
            ["Auth", "GET /dashboard, /dashboard/orders/{order}, /dashboard/profil", "Area pelanggan"],
            ["Admin", "GET /admin", "Dashboard admin"],
            ["Admin", "Resource /admin/orders", "Kelola status dan pembayaran"],
            ["Admin", "Resource /admin/packages", "Kelola paket joki"],
            ["Admin", "GET /admin/customers", "Daftar pelanggan"],
        ],
        widths=[1.0, 3.0, 2.6],
    )

    doc.add_heading("3.5 Desain Antarmuka", level=2)
    doc.add_paragraph(
        "Model antarmuka yang dipakai adalah “Atelier”: permukaan charcoal dengan tekstur titik halus, "
        "aksen emas antik yang diambil langsung dari logo, dan tipografi serif untuk judul supaya "
        "tidak terasa seragam. Token warna didefinisikan dalam OKLCH dengan fallback hex. "
        "Susunannya sengaja tidak simetris: sudut kartu berbeda, kartu pricelist diberi offset "
        "vertikal, dan label memakai kalimat biasa, bukan uppercase. Hasilnya lebih mirip "
        "catatan editorial bengkel daripada templat dashboard generik."
    )
    add_table(
        doc,
        ["Peran warna", "Nilai OKLCH", "Hex fallback", "Penggunaan"],
        [
            ["Accent / emas", "oklch(0.728 0.138 89.7)", "#C9A227", "Tombol utama, border aktif, price tag"],
            ["Accent bright", "oklch(0.843 0.119 91.5)", "#E8C96B", "Hover, kicker, ikon penting"],
            ["Steel", "oklch(0.716 0.017 239.2)", "#9AA5AD", "Teks sekunder, edge field"],
            ["Charcoal", "oklch(0.207 0.010 248.3)", "#14181C", "Latar halaman & panel"],
        ],
        widths=[1.3, 1.9, 1.2, 2.2],
    )
    doc.add_paragraph(
        "Audit kontras menunjukkan teks tubuh 16,18:1, teks sekunder 11,68:1, teks muted 7,10:1, "
        "dan label tombol emas 7,52:1—seluruhnya di atas ambang WCAG AA 4,5:1."
    )
    add_table(
        doc,
        ["Elemen", "Keputusan Desain"],
        [
            ["Hero", "Banner lebar 16:9, scrim enam stop, headline putih, kicker emas"],
            ["Pricelist", "Kartu pelat silver dengan border emas saat dipilih + panel estimator"],
            ["Dashboard", "Sidebar charcoal, penanda aktif emas, panel metal"],
            ["Mobile", "Navigasi drawer charcoal dengan aksi utama emas"],
        ],
        widths=[1.5, 5.1],
    )
    doc.add_page_break()


def chapter4(doc: Document) -> None:
    doc.add_heading("BAB IV  IMPLEMENTASI", level=1)

    doc.add_heading("4.1 Lingkungan Pengembangan", level=2)
    add_table(
        doc,
        ["Komponen", "Versi / Keterangan"],
        [
            ["PHP", "8.3.37 (Laragon)"],
            ["Laravel", "12.73.0"],
            ["Database", "SQLite (database/database.sqlite)"],
            ["Node & Vite", "Node 20+, Vite 7.3.8"],
            ["Browser QA", "Google Chrome / Playwright"],
            ["Web server", "php artisan serve pada 127.0.0.1:8000"],
        ],
        widths=[1.8, 4.8],
    )

    doc.add_heading("4.2 Instalasi dan Konfigurasi", level=2)
    add_code(
        doc,
        """
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed --force
npm run build
php artisan serve --host=127.0.0.1 --port=8000
""",
    )
    doc.add_paragraph(
        "Konfigurasi merek, nomor WhatsApp, Instagram, dan FAQ ditempatkan di config/site.php "
        "agar tidak tersebar di banyak view."
    )

    doc.add_heading("4.3 Implementasi Fitur Utama", level=2)
    doc.add_heading("4.3.1 Hero Carousel", level=3)
    doc.add_paragraph(
        "Carousel memakai tiga slide dari tabel hero_slides. JavaScript memutar otomatis setiap "
        "5,2 detik, menyediakan tombol prev/next, dot indikator, progress bar, jeda saat kursor "
        "berada di atas carousel, dukungan swipe, keyboard arrow, dan prefers-reduced-motion."
    )
    add_code(
        doc,
        """
const canAutoplay = () =>
    slides.length > 1 && !userPaused && !hovered &&
    !document.hidden && !reduceMotion.matches;
""",
    )
    add_image(doc, "01-home-desktop.png", "Gambar 4.1 Halaman beranda dengan hero carousel dan pricelist")

    doc.add_heading("4.3.2 Pricelist dan Kalkulator", level=3)
    doc.add_paragraph(
        "Paket diambil dari ServicePackageSeeder. Panel estimator memperbarui nama paket, harga, "
        "emblem, dan total saat pengguna mengubah pilihan rank atau jumlah bintang. Total akhir "
        "tetap dihitung ulang di server agar tidak bergantung pada JavaScript."
    )
    add_code(
        doc,
        """
$stars = $package->isStarPackage() ? $request->integer('stars') : 10;
$totalPrice = $package->isStarPackage()
    ? $package->price * $stars
    : $package->price;
""",
    )
    add_image(doc, "05-order-form.png", "Gambar 4.2 Form pemesanan dengan ringkasan total")

    doc.add_heading("4.3.3 Autentikasi dan Dashboard", level=3)
    doc.add_paragraph(
        "Login berhasil mengarahkan admin ke /admin dan pelanggan ke /dashboard. Tombol keluar "
        "tersedia di header publik saat pengguna sudah login, pada sidebar workspace, dan pada "
        "drawer mobile. Seluruh form logout memakai method POST dan token CSRF."
    )
    add_image(doc, "04-login.png", "Gambar 4.3 Halaman login DS Store")
    add_image(doc, "07-customer-dashboard.png", "Gambar 4.4 Dashboard pelanggan")

    doc.add_heading("4.3.4 Pelacakan dan Admin", level=3)
    doc.add_paragraph(
        "Pelacakan mencocokkan kode pesanan dan nomor WhatsApp yang dinormalisasi ke format 62. "
        "Admin dapat mencari pesanan, menyaring status, mengubah status dan status pembayaran, "
        "serta menambah Catatan admin. Setiap perubahan status menulis OrderEvent baru."
    )
    add_image(doc, "06-tracking-result.png", "Gambar 4.5 Hasil pelacakan pesanan tanpa login")
    add_image(doc, "09-admin-dashboard.png", "Gambar 4.6 Dashboard admin")
    add_image(doc, "12-admin-order-detail.png", "Gambar 4.7 Detail pesanan dan pembaruan status pada area admin")

    doc.add_heading("4.3.5 Chatbot Publik", level=3)
    doc.add_paragraph(
        "Chatbot ringan ditambahkan pada layout publik sebagai komponen Blade. Tombol mengambang "
        "membuka panel yang bisa menerima pertanyaan bebas atau memakai empat tombol cepat. "
        "Jawaban harga diambil langsung dari atribut data pada kartu pricelist, sehingga tidak "
        "duplikat dengan data seeder. Panel menutup dengan Escape atau klik di luar, serta "
        "menyediakan tautan lanjut ke admin via WhatsApp."
    )
    add_image(doc, "13-chatbot.png", "Gambar 4.8 Panel chatbot pada halaman publik")

    doc.add_heading("4.4 Penerapan Fitur Laravel", level=2)
    add_table(
        doc,
        ["Fitur Laravel", "Implementasi di Project"],
        [
            ["Routing", "26 rute di routes/web.php, termasuk route group guest/auth/admin dan resource route"],
            ["Controller", "HomeController, OrderController, DashboardController, Admin\\*Controller, Auth\\*Controller"],
            ["Model & Eloquent", "User, ServicePackage, Order, OrderEvent, HeroSlide dengan relasi dan accessor"],
            ["Migration", "Skema users tambahan, hero_slides, service_packages, orders, order_events"],
            ["Seeder", "HeroSlideSeeder, ServicePackageSeeder"],
            ["Blade & Component", "Layout app/workspace/auth, partial home, komponen icon/flash/input-error"],
            ["Middleware", "auth, guest, admin (EnsureUserIsAdmin), throttle"],
            ["Form Request", "StoreOrderRequest dengan aturan dan pesan error Bahasa Indonesia"],
            ["Validation", "Login, register, profil, paket, dan pelacakan divalidasi server-side"],
            ["Auth & Session", "Auth::attempt, regenerate session, remember me, rate limiting"],
            ["CSRF", "@csrf seluruh form termasuk logout"],
            ["Helper & Config", "config/site.php diakses melalui config('site.*') di seluruh view"],
            ["Testing", "18 feature/unit test dengan RefreshDatabase"],
        ],
        widths=[1.7, 4.9],
    )

    doc.add_heading("4.5 Perbaikan Kualitas dan QA", level=2)
    doc.add_paragraph(
        "Selama quality assurance ditemukan dan diperbaiki beberapa masalah. Perbaikan ini "
        "dicatat agar tim memiliki jejak keputusan dan tidak mengulang kesalahan yang sama."
    )
    add_table(
        doc,
        ["Masalah", "Akar Masalah", "Perbaikan"],
        [
            [
                "Akun demo tidak sesuai kebutuhan",
                "Akun demo bersifat umum dan tidak mewakili pemilik toko",
                "Akun demo dihapus; admin dibuat dengan perintah admin:create menggunakan email pemilik",
            ],
            [
                "Tombol login/logout sulit ditemukan",
                "Logout hanya ada di sidebar workspace; header publik tidak punya aksi keluar",
                "Ditambah tombol Keluar di header publik dan di drawer mobile",
            ],
            [
                "Judul bagian tertutup header saat klik menu anchor",
                "Sticky header tidak memiliki scroll offset",
                "html.scroll-padding-top = var(--header-offset) pada 128px desktop / 112px mobile",
            ],
            [
                "Header seolah 'glitch' saat scroll",
                "Announcement bar menyusut dan menggeser konten secara tiba-tiba",
                "Tinggi header dibuat konstan; hanya warna dan bayangan yang berubah",
            ],
            [
                "Teks hero tembus ke menu mobile",
                "Background dropdown transparan 0.98",
                "Background dropdown dibuat solid #060E19",
            ],
            [
                "Carousel tidak lanjut setelah tombol jeda ditekan",
                "Fokus tombol mengaktifkan state pause tambahan",
                "State fokus dihapus dari syarat autoplay; hover dan tombol jeda tetap berfungsi",
            ],
            [
                "Emblem rank memiliki kotak putih",
                "Aset sumber berbackground putih",
                "Background dihapus dengan flood fill + edge decontamination menjadi WebP transparan",
            ],
            [
                "Hero carousel meluber pada lebar 861–1084px",
                "aspect-ratio 16:9 berkelahi dengan min-height 610px",
                "Ditambahkan width:100% pada .hero-carousel",
            ],
        ],
        widths=[1.7, 2.4, 2.5],
    )
    doc.add_paragraph(
        "Setelah perbaikan, selenium/Playwright menunjukkan 0 console error, 0 page error, "
        "dan 0 respons HTTP ≥ 400 pada halaman yang diuji."
    )
    doc.add_page_break()


def chapter5(doc: Document) -> None:
    doc.add_heading("BAB V  PENGUJIAN", level=1)

    doc.add_heading("5.1 Strategi Pengujian", level=2)
    doc.add_paragraph(
        "Pengujian dilakukan pada tiga lapis: (1) unit/feature test Laravel dengan SQLite in-memory, "
        "(2) uji interaksi browser otomatis dengan Playwright, dan (3) uji visual manual pada "
        "beberapa lebar layar. Pengujian difokuskan pada alur kritis: autentikasi, order, "
        "otorisasi admin, pelacakan, dan navigasi."
    )

    doc.add_heading("5.2 Pengujian Fungsional Manual / Browser", level=2)
    add_table(
        doc,
        ["No", "Skenario", "Hasil"],
        [
            ["1", "Autoplay carousel berpindah slide setelah ±5,2 detik", "Lulus"],
            ["2", "Tombol prev/next dan dot mengganti slide aktif", "Lulus"],
            ["3", "Tombol jeda menahan slide; setelah dilepas dan kursor keluar, autoplay lanjut", "Lulus"],
            ["4", "Klik menu Pricelist mengarah ke #pricelist dengan judul tidak tertutup header", "Lulus"],
            ["5", "Drawer mobile terbuka, tautan menutup drawer setelah diklik", "Lulus"],
            ["6", "Login admin menuju /dashboard dan /admin", "Lulus"],
            ["7", "Login admin menuju /admin", "Lulus"],
            ["8", "Logout dari header publik, sidebar workspace, dan drawer mobile", "Lulus"],
            ["9", "Kalkulator pricelist mengubah total sesuai rank dan bintang", "Lulus"],
            ["10", "Form order menolak User ID bukan angka", "Lulus"],
            ["11", "Total Full Grading Mythic tetap Rp150.000 walaupun stars=10", "Lulus"],
            ["12", "Pelacakan dengan kode order + WhatsApp yang benar menampilkan timeline", "Lulus"],
            ["13", "Pelacakan dengan WhatsApp salah menampilkan error", "Lulus"],
            ["14", "Pelanggan tidak dapat membuka detail order pelanggan lain (404)", "Lulus"],
            ["15", "Pelanggan tidak dapat membuka /admin (403)", "Lulus"],
            ["16", "Admin memperbarui status pesanan dan event timeline tercatat", "Lulus"],
            ["17", "Paket yang sudah memiliki order tidak bisa dihapus", "Lulus"],
            ["18", "Tidak ada overflow horizontal pada 390, 768, 1024, dan 1440 px", "Lulus"],
        ],
        widths=[0.45, 5.15, 0.8],
    )

    doc.add_heading("5.3 Pengujian Otomatis", level=2)
    doc.add_paragraph("Perintah: vendor/bin/phpunit")
    add_code(
        doc,
        """
Tests:    18 passed (57 assertions)
Duration: ~20s

PASS  Tests\\Unit\\ExampleTest
PASS  Tests\\Feature\\AdminTest            (5 tests)
PASS  Tests\\Feature\\ExampleTest          (1 test)
PASS  Tests\\Feature\\StorefrontTest       (11 tests)
""",
    )
    doc.add_paragraph(
        "Pengujian otomatis mencakup halaman beranda, validasi order, perhitungan total server-side, "
        "perlindungan order antarpelanggan, autentikasi admin, registrasi, otorisasi admin, "
        "pembaruan status, pembuatan paket, dan perlindungan penghapusan paket."
    )

    doc.add_heading("5.4 Pengujian Responsif dan Browser", level=2)
    add_table(
        doc,
        ["Lebar", "Header", "Overflow", "Posisi #pricelist setelah klik", "Error"],
        [
            ["1440 px", "118 px", "0", "128 px (sesuai scroll-padding)", "Tidak ada"],
            ["1024 px", "112 px", "0", "111,7 px", "Tidak ada"],
            ["768 px", "112 px", "0", "112 px", "Tidak ada"],
            ["390 px", "108 px", "0", "112 px", "Tidak ada"],
        ],
        widths=[0.9, 1.0, 0.8, 2.6, 1.2],
    )
    doc.add_paragraph(
        "Pengujian browser juga memeriksa console error, page error, request gagal, dan respons "
        "HTTP 4xx/5xx. Seluruhnya bernilai nol pada alur yang diuji."
    )
    add_image(doc, "02-home-mobile.png", "Gambar 5.1 Tampilan beranda pada lebar 390 px", width=3.1)
    add_image(doc, "03-mobile-nav.png", "Gambar 5.2 Navigasi drawer mobile", width=3.1)
    doc.add_page_break()


def chapter6(doc: Document) -> None:
    doc.add_heading("BAB VI  PENUTUP", level=1)
    doc.add_heading("6.1 Kesimpulan", level=2)
    doc.add_paragraph(
        "Website DS Store berhasil dibangun dengan Laravel 12 dan memenuhi kebutuhan utama: "
        "menampilkan promo Season 42, menyediakan pricelist resmi, mengelola pesanan, "
        "memberikan dashboard pelanggan dan admin, serta memungkinkan pelacakan tanpa login. "
        "Fitur Laravel digunakan secara konsisten mulai dari routing, MVC, Eloquent, migration, "
        "seeder, Blade, middleware, validasi, autentikasi, otorisasi, hingga pengujian otomatis."
    )
    doc.add_paragraph(
        "Proses QA menemukan beberapa masalah nyata—tombol logout tersembunyi, "
        "anchor tertutup header, dropdown mobile tembus pandang, dan carousel tidak melanjutkan "
        "setelah jeda. Seluruh masalah telah diperbaiki dan diverifikasi ulang dengan pengujian "
        "otomatis maupun browser."
    )

    doc.add_heading("6.2 Saran", level=2)
    add_bullets(
        doc,
        [
            "Menambahkan verifikasi pembayaran otomatis atau integrasi gateway jika layanan berkembang.",
            "Menambahkan notifikasi WhatsApp otomatis saat status pesanan berubah.",
            "Menyediakan upload bukti pembayaran di area pelanggan.",
            "Menambahkan pengujian aksesibilitas otomatis dengan axe/Lighthouse sebagai bagian dari CI.",
            "Mengganti gambar promosi dengan aset resmi MLBB bila tersedia agar konsistensi merek lebih kuat.",
        ],
    )
    doc.add_page_break()


def references_and_appendix(doc: Document) -> None:
    doc.add_heading("DAFTAR PUSTAKA", level=1)
    add_bullets(
        doc,
        [
            "Laravel Documentation. (2026). Laravel 12.x — Routing, Eloquent, Blade, Validation, Authentication. https://laravel.com/docs/12.x",
            "Vite Documentation. (2026). Why Vite / Asset Handling. https://vite.dev/guide/",
            "MDN Web Docs. (2026). CSS scroll-padding, prefers-reduced-motion, Web Animations API. https://developer.mozilla.org/",
            "Mozilla / Google. (2026). Web Accessibility and Responsive Design guidance. https://web.dev/",
            "Poizy Store. (2026). Referensi konsep layanan joki Mobile Legends. https://www.poizystore.id/id-id",
            "Mobile Legends: Bang Bang. (2026). Informasi rank dan musim kompetitif.",
        ],
    )

    doc.add_heading("LAMPIRAN A  AKUN ADMIN", level=1)
    add_table(
        doc,
        ["Peran", "Email", "Cara membuat", "Tujuan"],
        [
            ["Admin", "a3snewbaru990@gmail.com", "php artisan admin:create", "Mengelola pesanan dan paket"],
            ["Pelanggan", "Dibuat mandiri", "Halaman /register", "Membuat dan memantau pesanan"],
        ],
        widths=[1.1, 2.2, 1.8, 2.3],
    )
    doc.add_paragraph(
        "Tidak ada akun demo. Password admin dibuat interaktif melalui perintah Artisan dan tidak disimpan di dalam repository."
    )

    doc.add_heading("LAMPIRAN B  STRUKTUR PENGUMPULAN", level=1)
    add_code(
        doc,
        """
docs/bagian-3/
  00-PANDUAN-PENGUMPULAN.md
  01-Laporan/       -> Laporan-DS-Store.docx, Laporan-DS-Store.md
  02-Presentasi/    -> Presentasi-DS-Store.pptx, Script-Video-Presentasi.md
  03-Screenshots/   -> 01..12 screenshot
  04-Source-Code/   -> DS-STORE-Source.zip, CARA-MENJALANKAN.md
  05-Video/         -> LINK-YOUTUBE.txt, Checklist-Video.md
  DATA-KELOMPOK.md
""",
    )

    doc.add_heading("LAMPIRAN C  DAFTAR SCREENSHOT", level=1)
    add_table(
        doc,
        ["File", "Konten"],
        [
            ["01-home-desktop.png", "Beranda desktop: hero + pricelist"],
            ["02-home-mobile.png", "Beranda mobile"],
            ["03-mobile-nav.png", "Drawer navigasi mobile"],
            ["04-login.png", "Halaman login DS Store (model Atelier)"],
            ["05-order-form.png", "Form order dan ringkasan total"],
            ["06-tracking-result.png", "Hasil pelacakan pesanan"],
            ["07-customer-dashboard.png", "Dashboard pelanggan"],
            ["08-customer-order-detail.png", "Detail pesanan + timeline"],
            ["09-admin-dashboard.png", "Dashboard admin"],
            ["10-admin-orders.png", "Daftar pesanan admin"],
            ["11-admin-packages.png", "Kelola paket joki"],
            ["12-admin-order-detail.png", "Detail pesanan dan update status admin"],
            ["13-chatbot.png", "Panel chatbot dan jawaban harga"],
        ],
        widths=[2.2, 4.4],
    )

    doc.add_heading("LAMPIRAN D  LINK PRESENTASI VIDEO", level=1)
    doc.add_paragraph("Link YouTube: [Tempel link video presentasi di sini]")
    doc.add_paragraph(
        "Script narasi dan checklist upload tersedia pada folder 05-Video. Pastikan video bersifat "
        "unlisted/public sesuai instruksi dosen dan dimasukkan ke dalam folder kelompok."
    )


def main() -> None:
    OUT.parent.mkdir(parents=True, exist_ok=True)
    doc = Document()
    configure(doc)
    cover(doc)
    approval(doc)
    preface(doc)
    toc(doc)
    chapter1(doc)
    chapter2(doc)
    chapter3(doc)
    chapter4(doc)
    chapter5(doc)
    chapter6(doc)
    references_and_appendix(doc)
    doc.core_properties.title = "Laporan Project DS Store — Laravel 12"
    doc.core_properties.subject = "Pengumpulan Bagian 3: Hasil Project, Laporan, dan Presentasi"
    doc.core_properties.author = "[Nama Kelompok]"
    doc.save(OUT)
    print(f"{OUT.name}: {OUT.stat().st_size // 1024} KiB")


if __name__ == "__main__":
    main()
