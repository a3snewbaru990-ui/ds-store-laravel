@php
    $user = request()->user();
@endphp

<header class="site-header" data-site-header>
    <div class="announcement">
        <div class="container announcement__inner">
            <p>
                <span class="status-dot" aria-hidden="true"></span>
                {{ config('site.season') }} sedang berjalan
            </p>
            <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener">
                Admin online 24 jam
                <x-icon name="arrow-up-right" />
            </a>
        </div>
    </div>

    <div class="site-nav">
        <div class="container site-nav__inner">
            <a href="{{ route('home') }}" class="brand" aria-label="DS Store, kembali ke beranda">
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="52" height="45" class="brand__mark">
                <span class="brand__text">
                    <span class="brand__name">DS <strong>STORE</strong></span>
                    <span class="brand__sub">Mobile Legends service</span>
                </span>
            </a>

            <nav class="primary-nav" id="primary-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}#pricelist">Pricelist</a>
                <a href="{{ route('home') }}#keunggulan">Keunggulan</a>
                <a href="{{ route('home') }}#cara-order">Cara order</a>
                <a href="{{ route('home') }}#faq">FAQ</a>
                <a href="{{ route('track.index') }}">Lacak pesanan</a>

                <div class="primary-nav__mobile-actions">
                    @auth
                        <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('profile.edit') }}">Profil</a>
                        <a href="{{ route('home') }}#pricelist" class="is-primary">Order baru</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="mobile-logout">
                                <x-icon name="logout" />
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Masuk akun</a>
                        <a href="{{ route('register') }}" class="is-primary">Daftar akun</a>
                    @endauth
                </div>
            </nav>

            <div class="nav-actions">
                @auth
                    <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="nav-user">
                        <span class="nav-user__avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        <span class="nav-user__name">{{ $user->name }}</span>
                    </a>
                    <a href="{{ route('home') }}#pricelist" class="btn btn--primary btn--sm nav-cta">
                        Order joki
                        <x-icon name="arrow-right" />
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="nav-logout-form">
                        @csrf
                        <button type="submit" class="nav-logout" aria-label="Keluar dari akun">
                            <x-icon name="logout" />
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-login">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn--primary btn--sm nav-cta">
                        Daftar
                        <x-icon name="arrow-right" />
                    </a>
                @endauth

                <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" data-nav-toggle>
                    <span class="sr-only">Buka navigasi</span>
                    <x-icon name="menu" class="nav-toggle__open" />
                    <x-icon name="close" class="nav-toggle__close" />
                </button>
            </div>
        </div>
    </div>
</header>
