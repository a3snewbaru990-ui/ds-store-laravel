"""Generate the DS Store presentation (.pptx) for Bagian 3."""

from pathlib import Path

from PIL import Image
from pptx import Presentation
from pptx.dml.color import RGBColor
from pptx.enum.shapes import MSO_SHAPE
from pptx.enum.text import MSO_ANCHOR, PP_ALIGN
from pptx.util import Inches, Pt

ROOT = Path(__file__).resolve().parents[1]
SHOTS = ROOT / "docs" / "bagian-3" / "03-Screenshots"
OUT = ROOT / "docs" / "bagian-3" / "02-Presentasi" / "Presentasi-DS-Store.pptx"
LOGO = ROOT / "public" / "images" / "logo-mark.png"

BG = RGBColor(17, 22, 26)
PANEL = RGBColor(26, 32, 38)
CYAN = RGBColor(154, 165, 173)
CYAN_SOFT = RGBColor(227, 232, 236)
GOLD = RGBColor(201, 162, 39)
GOLD_SOFT = RGBColor(240, 214, 150)
WHITE = RGBColor(242, 244, 246)
MUTED = RGBColor(154, 165, 173)
LINE = RGBColor(58, 67, 75)

W, H = 13.333, 7.5


def rect(slide, x, y, w, h, fill, *, line=None, rounded=False):
    kind = MSO_SHAPE.ROUNDED_RECTANGLE if rounded else MSO_SHAPE.RECTANGLE
    shape = slide.shapes.add_shape(kind, Inches(x), Inches(y), Inches(w), Inches(h))
    shape.fill.solid()
    shape.fill.fore_color.rgb = fill
    if line is None:
        shape.line.fill.background()
    else:
        shape.line.color.rgb = line
        shape.line.width = Pt(1)
    return shape


def text(slide, x, y, w, h, content, *, size=18, color=WHITE, bold=False,
         align=PP_ALIGN.LEFT, font="Aptos", space=6, valign=MSO_ANCHOR.TOP):
    box = slide.shapes.add_textbox(Inches(x), Inches(y), Inches(w), Inches(h))
    tf = box.text_frame
    tf.word_wrap = True
    tf.margin_left = tf.margin_right = Inches(0.02)
    tf.margin_top = tf.margin_bottom = Inches(0.02)
    tf.vertical_anchor = valign
    for i, line_text in enumerate(content.split("\n")):
        p = tf.paragraphs[0] if i == 0 else tf.add_paragraph()
        p.text = line_text
        p.alignment = align
        p.space_after = Pt(space)
        for run in p.runs:
            run.font.size = Pt(size)
            run.font.bold = bold
            run.font.color.rgb = color
            run.font.name = font
    return box


def fit_picture(slide, name, x, y, w, h):
    path = SHOTS / name
    if not path.exists():
        return
    iw, ih = Image.open(path).size
    scale = min(w / iw, h / ih)
    nw, nh = iw * scale, ih * scale
    slide.shapes.add_picture(
        str(path),
        Inches(x + (w - nw) / 2),
        Inches(y + (h - nh) / 2),
        width=Inches(nw),
        height=Inches(nh),
    )


def base_slide(prs, title, kicker="DS STORE · LAPORAN PROJECT", number=None):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    slide.background.fill.solid()
    slide.background.fill.fore_color.rgb = BG
    rect(slide, 0, 0, W, 0.07, CYAN)
    rect(slide, 0.55, 0.42, 0.06, 0.55, GOLD)
    text(slide, 0.78, 0.36, 9.6, 0.32, kicker, size=10, color=CYAN_SOFT, bold=True)
    text(slide, 0.78, 0.68, 11.8, 0.62, title, size=28, color=WHITE, bold=True)
    rect(slide, 0.55, 7.05, 12.25, 0.012, LINE)
    text(slide, 0.55, 7.1, 8.0, 0.25, "DS Store — Joki Akun Mobile Legends Season 42",
         size=8, color=MUTED)
    if number is not None:
        text(slide, 12.2, 7.1, 0.6, 0.25, f"{number:02d}", size=8, color=MUTED, align=PP_ALIGN.RIGHT)
    return slide


def panel(slide, x, y, w, h, title, body, *, accent=CYAN, body_size=14):
    rect(slide, x, y, w, h, PANEL, line=LINE, rounded=True)
    rect(slide, x, y, 0.055, h, accent)
    text(slide, x + 0.28, y + 0.22, w - 0.5, 0.4, title, size=15, color=accent, bold=True)
    text(slide, x + 0.28, y + 0.72, w - 0.5, h - 0.9, body, size=body_size, color=WHITE, space=5)


def main():
    OUT.parent.mkdir(parents=True, exist_ok=True)
    prs = Presentation()
    prs.slide_width = Inches(W)
    prs.slide_height = Inches(H)

    # 01 — Cover
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    slide.background.fill.solid()
    slide.background.fill.fore_color.rgb = BG
    rect(slide, 0, 0, W, 0.09, CYAN)
    rect(slide, 8.9, 0, 4.43, H, RGBColor(7, 17, 32))
    fit_picture(slide, "01-home-desktop.png", 8.95, 0.1, 4.3, 7.3)
    if LOGO.exists():
        slide.shapes.add_picture(str(LOGO), Inches(0.75), Inches(0.7), height=Inches(0.9))
    text(slide, 0.75, 1.95, 7.7, 1.8,
         "DS STORE\nJOKI AKUN MOBILE LEGENDS",
         size=38, color=WHITE, bold=True, space=2)
    text(slide, 0.75, 3.85, 7.5, 0.5,
         "Laporan & Presentasi — Pengumpulan Bagian 3",
         size=19, color=CYAN_SOFT, bold=True)
    text(slide, 0.75, 4.55, 7.5, 1.1,
         "Framework Laravel 12 · SQLite · Blade · Vite\n"
         "Respon Cepat, Progres Kilat & 100% Amanah",
         size=14, color=MUTED, space=8)
    rect(slide, 0.75, 6.05, 3.2, 0.03, GOLD)
    text(slide, 0.75, 6.25, 7.4, 0.8,
         "[Nama Kelompok]  ·  [Nama Anggota 1], [Nama Anggota 2], [Nama Anggota 3]\n"
         "[Mata Kuliah]  ·  [Nama Dosen]  ·  [Tanggal]",
         size=11, color=WHITE, space=5)

    # 02 — Agenda
    slide = base_slide(prs, "Agenda Presentasi", number=2)
    items = [
        ("01", "Latar belakang & tujuan", "Mengapa website ini dibangun"),
        ("02", "Solusi & fitur utama", "Apa yang disediakan DS Store"),
        ("03", "Arsitektur & Laravel", "MVC, Eloquent, Blade, middleware"),
        ("04", "Tampilan & alur order", "Dari pricelist sampai dashboard"),
        ("05", "Pengujian & QA", "18 test, perbaikan nyata, responsif"),
        ("06", "Pengumpulan & demo", "Struktur folder, admin, video"),
    ]
    for i, (num, heading, sub) in enumerate(items):
        y = 1.55 + i * 0.84
        rect(slide, 0.65, y, 12.0, 0.68, PANEL, line=LINE, rounded=True)
        text(slide, 0.9, y + 0.14, 0.7, 0.4, num, size=17, color=GOLD, bold=True)
        text(slide, 1.65, y + 0.1, 5.0, 0.45, heading, size=16, color=WHITE, bold=True)
        text(slide, 6.8, y + 0.13, 5.4, 0.4, sub, size=12, color=MUTED)

    # 03 — Background
    slide = base_slide(prs, "Latar Belakang & Tujuan", number=3)
    panel(slide, 0.65, 1.55, 5.85, 2.25, "Masalah",
          "• Harga joki tersebar di media sosial\n"
          "• Calon pelanggan menanyakan paket satu per satu\n"
          "• Status pesanan sulit dipantau\n"
          "• Admin berulang kali menjawab pertanyaan yang sama",
          accent=GOLD, body_size=14)
    panel(slide, 6.8, 1.55, 5.85, 2.25, "Tujuan",
          "• Pricelist resmi Season 42 dalam satu halaman\n"
          "• Estimasi total otomatis per rank dan bintang\n"
          "• Alur order + pelacakan yang jelas\n"
          "• Dashboard untuk pelanggan dan admin",
          accent=CYAN, body_size=14)
    panel(slide, 0.65, 4.05, 12.0, 2.25, "Ruang Lingkup",
          "Halaman publik (beranda, pricelist, lacak, login, register) · Area pelanggan (dashboard, "
          "detail pesanan, profil) · Area admin (dashboard, pesanan, paket, pelanggan). "
          "Pembayaran masih dikonfirmasi manual lewat WhatsApp; password akun game tidak disimpan di website.",
          accent=WHITE, body_size=14)

    # 04 — Solution features
    slide = base_slide(prs, "Solusi DS Store", number=4)
    features = [
        ("Hero carousel", "3 slide promo, autoplay 5,2 detik, arrow, dot, hover pause, swipe."),
        ("Pricelist transparan", "Mulai dari GM Rp5.000/bintang, bukan langsung Mythic. Sampai Mythic Glory Rp20.000."),
        ("Order tervalidasi", "Form Request mengecek User ID, Server ID, WhatsApp, dan metode login."),
        ("Dashboard", "Ringkasan pesanan, timeline progres, profil, dan statistik untuk admin."),
        ("Lacak tanpa login", "Kode pesanan + WhatsApp menampilkan status terkini."),
        ("Admin workspace", "Filter pesanan, update status/pembayaran, kelola paket, lihat pelanggan."),
        ("Chatbot ringan", "Tanya harga, cara order, lacak, dan lanjut ke admin lewat WhatsApp."),
    ]
    for i, (title, body) in enumerate(features):
        col, row = i % 3, i // 3
        panel(slide, 0.65 + col * 4.1, 1.55 + row * 2.45, 3.85, 2.15,
              title, body, accent=CYAN if i % 2 == 0 else GOLD, body_size=13)

    # 05 — Architecture
    slide = base_slide(prs, "Arsitektur & Alur Sistem", number=5)
    rect(slide, 0.65, 1.6, 12.0, 2.0, PANEL, line=LINE, rounded=True)
    text(slide, 0.95, 1.85, 11.4, 0.4, "Model – View – Controller (Laravel 12)",
         size=17, color=CYAN_SOFT, bold=True)
    blocks = [
        (1.0, "Browser", "Blade + CSS + JS"),
        (4.1, "Route + Middleware", "auth, guest, admin, throttle"),
        (7.2, "Controller", "validasi + logika bisnis"),
        (10.3, "Eloquent + SQLite", "Model & tabel"),
    ]
    for x, title, sub in blocks:
        rect(slide, x, 2.45, 2.5, 0.85, BG, line=CYAN, rounded=True)
        text(slide, x + 0.12, 2.55, 2.26, 0.32, title, size=13, color=WHITE, bold=True, align=PP_ALIGN.CENTER)
        text(slide, x + 0.12, 2.9, 2.26, 0.28, sub, size=9, color=MUTED, align=PP_ALIGN.CENTER)
    panel(slide, 0.65, 3.9, 5.85, 2.4, "Alur Pelanggan",
          "Pilih paket → hitung bintang → login → isi data akun → sistem buat kode order "
          "→ admin konfirmasi → timeline diperbarui → pelanggan memantau.",
          accent=CYAN, body_size=14)
    panel(slide, 6.8, 3.9, 5.85, 2.4, "Alur Admin",
          "Buka dashboard → filter/cari pesanan → periksa data → ubah status & pembayaran "
          "→ tambah catatan → OrderEvent tercatat otomatis.",
          accent=GOLD, body_size=14)

    # 06 — Laravel features
    slide = base_slide(prs, "Fitur Laravel yang Digunakan", number=6)
    laravel = [
        "Routing (26 rute, group auth/admin)",
        "Controller (publik, auth, dashboard, admin)",
        "Eloquent ORM + relasi & accessor",
        "Migration + Seeder (paket & promosi)",
        "Blade layout, partial, component",
        "Middleware admin & throttle",
        "Form Request + validasi Bahasa Indonesia",
        "Auth, session regenerate, rate limiting",
        "CSRF, hash password, password toggle",
        "Vite build untuk CSS & JavaScript",
        "Config terpusat di config/site.php",
        "Testing: 18 test, 57 assertion",
    ]
    for i, item in enumerate(laravel):
        col, row = i % 3, i // 3
        x, y = 0.65 + col * 4.1, 1.55 + row * 1.25
        rect(slide, x, y, 3.85, 1.0, PANEL, line=LINE, rounded=True)
        rect(slide, x, y, 0.05, 1.0, CYAN if i % 2 == 0 else GOLD)
        text(slide, x + 0.25, y + 0.18, 3.4, 0.65, item, size=13, color=WHITE, bold=True)

    # 07 — Database & routes
    slide = base_slide(prs, "Database & Rute", number=7)
    panel(slide, 0.65, 1.55, 6.0, 4.8, "Tabel Utama",
          "users — akun pelanggan/admin\n"
          "service_packages — paket & harga\n"
          "orders — kode order, bintang, total, status\n"
          "order_events — riwayat perubahan status\n"
          "hero_slides — konten carousel promosi\n\n"
          "Relasi: orders.user_id → users.id\n"
          "orders.service_package_id → service_packages.id",
          accent=CYAN, body_size=14)
    panel(slide, 6.9, 1.55, 5.75, 4.8, "Grup Rute",
          "GET / — beranda\n"
          "GET/POST /lacak — pelacakan\n"
          "GET/POST /login, /register — autentikasi\n"
          "POST /logout — keluar\n"
          "GET /order/{package}, POST /orders — order\n"
          "GET /dashboard… — area pelanggan\n"
          "GET /admin, resource orders/packages — admin",
          accent=GOLD, body_size=14)

    # 08 — Home
    slide = base_slide(prs, "Tampilan Beranda", number=8)
    fit_picture(slide, "01-home-desktop.png", 0.65, 1.5, 12.0, 5.15)
    text(slide, 0.65, 6.72, 12.0, 0.3,
         "Hero 16:9, kalkulator pricelist, dan kartu paket transparan pada satu halaman.",
         size=11, color=MUTED, align=PP_ALIGN.CENTER)

    # 09 — Order
    slide = base_slide(prs, "Pricelist & Form Order", number=9)
    fit_picture(slide, "05-order-form.png", 0.65, 1.5, 8.1, 5.2)
    panel(slide, 9.0, 1.6, 3.6, 2.3, "Kalkulator",
          "Total dihitung otomatis di browser dan diverifikasi ulang di server.\n\n"
          "Mythic: Rp12.000 × 10 = Rp120.000",
          accent=CYAN, body_size=12)
    panel(slide, 9.0, 4.1, 3.6, 2.2, "Validasi",
          "User ID 5–20 digit, Server ID 1–6 digit, WhatsApp Indonesia, metode login tertentu.",
          accent=GOLD, body_size=12)

    # 10 — Dashboards
    slide = base_slide(prs, "Dashboard Pelanggan & Admin", number=10)
    fit_picture(slide, "07-customer-dashboard.png", 0.65, 1.5, 6.0, 4.2)
    fit_picture(slide, "09-admin-dashboard.png", 6.9, 1.5, 5.75, 4.2)
    text(slide, 0.65, 5.85, 6.0, 0.3, "Pelanggan: riwayat, status, total transaksi",
         size=11, color=CYAN_SOFT, align=PP_ALIGN.CENTER)
    text(slide, 6.9, 5.85, 5.75, 0.3, "Admin: metrik, antrean, paket terlaris",
         size=11, color=GOLD_SOFT, align=PP_ALIGN.CENTER)
    rect(slide, 0.65, 6.35, 12.0, 0.55, PANEL, line=LINE, rounded=True)
    text(slide, 0.9, 6.46, 11.5, 0.35,
         "Tombol Keluar tersedia di header publik, sidebar workspace, dan drawer mobile.",
         size=12, color=WHITE, bold=True, align=PP_ALIGN.CENTER)

    # 11 — Tracking & QA
    slide = base_slide(prs, "Pelacakan & Perbaikan QA", number=11)
    fit_picture(slide, "06-tracking-result.png", 0.65, 1.5, 7.2, 5.2)
    panel(slide, 8.15, 1.55, 4.5, 2.4, "Perbaikan penting",
          "• Akun demo dihapus → admin:create dengan email pemilik\n"
          "• Logout tersembunyi → tombol di header & drawer\n"
          "• Anchor tertutup header → scroll-padding",
          accent=CYAN, body_size=12)
    panel(slide, 8.15, 4.15, 4.5, 2.2, "Perbaikan lain",
          "• Header 'glitch' → tinggi dibuat konstan\n"
          "• Menu mobile tembus → background solid\n"
          "• Carousel tidak lanjut → state fokus dihapus\n"
          "• Emblem rank → background dihapus",
          accent=GOLD, body_size=12)

    # 12 — Testing
    slide = base_slide(prs, "Pengujian", number=12)
    rect(slide, 0.65, 1.55, 3.8, 2.0, PANEL, line=CYAN, rounded=True)
    text(slide, 0.9, 1.8, 3.3, 0.8, "18", size=52, color=CYAN, bold=True, align=PP_ALIGN.CENTER)
    text(slide, 0.9, 2.85, 3.3, 0.4, "test otomatis lulus", size=14, color=WHITE, align=PP_ALIGN.CENTER)
    rect(slide, 4.7, 1.55, 3.8, 2.0, PANEL, line=GOLD, rounded=True)
    text(slide, 4.95, 1.8, 3.3, 0.8, "58", size=52, color=GOLD, bold=True, align=PP_ALIGN.CENTER)
    text(slide, 4.95, 2.85, 3.3, 0.4, "assertion", size=14, color=WHITE, align=PP_ALIGN.CENTER)
    rect(slide, 8.75, 1.55, 3.9, 2.0, PANEL, line=WHITE, rounded=True)
    text(slide, 9.0, 1.8, 3.4, 0.8, "0", size=52, color=WHITE, bold=True, align=PP_ALIGN.CENTER)
    text(slide, 9.0, 2.85, 3.4, 0.4, "error konsol / 4xx", size=14, color=WHITE, align=PP_ALIGN.CENTER)
    panel(slide, 0.65, 3.85, 12.0, 2.4, "Cakupan pengujian",
          "Beranda & pricelist · validasi order · total server-side · otorisasi pelanggan/admin · "
          "login & logout admin · registrasi · pelacakan · update status admin · perlindungan paket · "
          "responsif 390/768/1024/1440 px.",
          accent=CYAN, body_size=14)

    # 13 — Cara menjalankan
    slide = base_slide(prs, "Cara Menjalankan & Akun Admin", number=13)
    panel(slide, 0.65, 1.55, 6.6, 4.8, "Langkah singkat",
          "composer install && npm install\n"
          "cp .env.example .env && php artisan key:generate\n"
          "php artisan migrate:fresh --seed --force\n"
          "php artisan admin:create a3snewbaru990@gmail.com\n"
          "npm run build\n"
          "php artisan serve --host=127.0.0.1 --port=8000",
          accent=CYAN, body_size=13)
    panel(slide, 7.5, 1.55, 5.15, 4.8, "Akun admin",
          "Email\n"
          "a3snewbaru990@gmail.com\n\n"
          "Password\n"
          "Dibuat interaktif saat admin:create\n\n"
          "Catatan\n"
          "Tidak ada akun demo. Pelanggan\nmendaftar mandiri lewat /register.",
          accent=GOLD, body_size=13)

    # 14 — Submission
    slide = base_slide(prs, "Kelengkapan Pengumpulan Bagian 3", number=14)
    folders = [
        ("01-Laporan", "Laporan-DS-Store.docx + .md"),
        ("02-Presentasi", "Presentasi-DS-Store.pptx + script video"),
        ("03-Screenshots", "12 screenshot desktop & mobile"),
        ("04-Source-Code", "DS-STORE-Source.zip + panduan"),
        ("05-Video", "LINK-YOUTUBE.txt + checklist"),
        ("DATA-KELOMPOK.md", "Isi nama, NIM, kelas, dosen, link video"),
    ]
    for i, (name, desc) in enumerate(folders):
        y = 1.55 + i * 0.83
        rect(slide, 0.65, y, 12.0, 0.66, PANEL, line=LINE, rounded=True)
        rect(slide, 0.65, y, 0.05, 0.66, CYAN if i % 2 == 0 else GOLD)
        text(slide, 0.95, y + 0.14, 3.6, 0.38, name, size=15, color=WHITE, bold=True)
        text(slide, 4.6, y + 0.16, 7.6, 0.35, desc, size=12, color=MUTED)

    # 15 — Closing
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    slide.background.fill.solid()
    slide.background.fill.fore_color.rgb = BG
    rect(slide, 0, 0, W, 0.09, CYAN)
    if LOGO.exists():
        slide.shapes.add_picture(str(LOGO), Inches(5.95), Inches(1.1), height=Inches(1.0))
    text(slide, 0.8, 2.35, 11.7, 0.9, "Terima kasih.",
         size=44, color=WHITE, bold=True, align=PP_ALIGN.CENTER)
    text(slide, 0.8, 3.35, 11.7, 0.5,
         "DS Store — Respon Cepat, Progres Kilat & 100% Amanah",
         size=18, color=CYAN_SOFT, align=PP_ALIGN.CENTER)
    rect(slide, 4.4, 4.2, 4.5, 0.03, GOLD)
    text(slide, 0.8, 4.55, 11.7, 1.2,
         "WhatsApp: 0818-0728-3181\nInstagram: @dsstore_official\n"
         "Link video presentasi: [tempel di DATA-KELOMPOK.md]",
         size=14, color=WHITE, align=PP_ALIGN.CENTER, space=10)
    text(slide, 0.8, 6.7, 11.7, 0.3, "Terakhir diperbarui: 26 November 2026",
         size=10, color=MUTED, align=PP_ALIGN.CENTER)

    prs.core_properties.title = "Presentasi Project DS Store — Laravel 12"
    prs.core_properties.author = "[Nama Kelompok]"
    prs.save(OUT)
    print(f"{OUT.name}: {OUT.stat().st_size // 1024} KiB, {len(prs.slides)} slides")


if __name__ == "__main__":
    main()
