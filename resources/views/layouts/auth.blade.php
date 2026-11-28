<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk | DS Store')</title>
    <meta name="description" content="@yield('description', 'Masuk ke akun DS Store untuk membuat dan memantau pesanan joki Mobile Legends.')">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#14181C">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preload" href="{{ asset('fonts/bai-jamjuree-700.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/manrope-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
    <a class="skip-link" href="#auth-main">Lewati ke formulir</a>
    <main class="auth-shell" id="auth-main">
        <section class="auth-visual" aria-label="DS Store">
            <img src="{{ asset('images/hero-art-2.webp') }}" alt="" class="auth-visual__image">
            <div class="auth-visual__shade"></div>
            <div class="auth-visual__content">
                <a href="{{ route('home') }}" class="brand" aria-label="Kembali ke beranda DS Store">
                    <img src="{{ asset('images/logo-mark.png') }}" alt="" width="62" height="53" class="brand__mark">
                    <span class="brand__text">
                        <span class="brand__name">DS <strong>STORE</strong></span>
                        <span class="brand__sub">Mobile Legends service</span>
                    </span>
                </a>
                <div class="auth-visual__copy">
                    <p class="eyebrow">Season 42 · Mobile Legends</p>
                    <h1>Push rank tanpa drama.</h1>
                    <p>Pilih paket, pantau progres, dan simpan semua riwayat pesanan dalam satu dashboard.</p>
                </div>
                <div class="auth-visual__footer">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ config('site.open_hours') }}
                </div>
            </div>
        </section>

        <section class="auth-panel">
            <div class="auth-panel__top">
                <a href="{{ route('home') }}" class="auth-back">
                    <x-icon name="arrow-left" />
                    Kembali ke beranda
                </a>
            </div>

            <div class="auth-card">
                <div class="auth-card__brand">
                    <img src="{{ asset('images/logo-mark.png') }}" alt="" width="54" height="46">
                    <span>DS Store Account</span>
                </div>

                <div class="flash-stack flash-stack--auth">
                    <x-flash />
                </div>

                @yield('content')
            </div>

            <p class="auth-panel__footer">
                Butuh bantuan? <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener">Chat admin di WhatsApp</a>
            </p>
        </section>
    </main>
</body>
</html>
