<footer class="site-footer">
    <div class="container">
        <div class="site-footer__top">
            <div class="site-footer__brand">
                <a href="{{ route('home') }}" class="brand brand--footer" aria-label="DS Store">
                    <img src="{{ asset('images/logo-mark.png') }}" alt="" width="62" height="53" class="brand__mark">
                    <span class="brand__text">
                        <span class="brand__name">DS <strong>STORE</strong></span>
                        <span class="brand__sub">Mobile Legends service</span>
                    </span>
                </a>
                <p>{{ config('site.tagline') }}. Joki akun Mobile Legends dengan alur pesanan yang jelas dan progres yang bisa dipantau.</p>
                <div class="social-row">
                    <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener" class="social-link" aria-label="WhatsApp DS Store">
                        <x-icon name="whatsapp" />
                    </a>
                    <a href="{{ config('site.instagram_url') }}" target="_blank" rel="noopener" class="social-link" aria-label="Instagram DS Store">
                        <x-icon name="instagram" />
                    </a>
                </div>
            </div>

            <div class="site-footer__column">
                <h2>Navigasi</h2>
                <a href="{{ route('home') }}#pricelist">Pricelist</a>
                <a href="{{ route('home') }}#keunggulan">Keunggulan</a>
                <a href="{{ route('home') }}#cara-order">Cara order</a>
                <a href="{{ route('home') }}#faq">FAQ</a>
            </div>

            <div class="site-footer__column">
                <h2>Layanan</h2>
                <a href="{{ route('track.index') }}">Lacak pesanan</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('profile.edit') }}">Profil</a>
                @else
                    <a href="{{ route('login') }}">Masuk akun</a>
                    <a href="{{ route('register') }}">Daftar akun</a>
                @endauth
                <a href="{{ route('home') }}#paket-grading">Full grading</a>
            </div>

            <div class="site-footer__column site-footer__contact">
                <h2>Kontak admin</h2>
                <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener" class="contact-line">
                    <span class="contact-line__icon"><x-icon name="whatsapp" /></span>
                    <span>
                        <small>WhatsApp</small>
                        {{ config('site.whatsapp_display') }}
                    </span>
                </a>
                <a href="{{ config('site.instagram_url') }}" target="_blank" rel="noopener" class="contact-line">
                    <span class="contact-line__icon"><x-icon name="instagram" /></span>
                    <span>
                        <small>Instagram</small>
                        &#64;{{ config('site.instagram') }}
                    </span>
                </a>
                <p class="footer-hours">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ config('site.open_hours') }}
                </p>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p>&copy; {{ now()->year }} DS Store. Semua hak dilindungi.</p>
            <p class="site-footer__meta">Dibangun dengan Laravel untuk project akademik.</p>
        </div>
    </div>
</footer>

<a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener" class="wa-float" aria-label="Chat admin DS Store lewat WhatsApp">
    <x-icon name="whatsapp" />
    <span>Chat admin</span>
</a>
