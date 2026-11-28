<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DS Store | Joki Akun Mobile Legends Season 42')</title>
    <meta name="description" content="@yield('description', config('site.description'))">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="theme-color" content="#14181C">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DS Store">
    <meta property="og:title" content="@yield('og_title', 'DS Store | Joki Akun Mobile Legends Season 42')">
    <meta property="og:description" content="@yield('og_description', config('site.description'))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/banner-2.jpg'))">
    <meta property="og:image:alt" content="DS Store, jasa joki akun Mobile Legends Season 42">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'DS Store | Joki Akun Mobile Legends Season 42')">
    <meta name="twitter:description" content="@yield('og_description', config('site.description'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/banner-2.jpg'))">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="preload" href="{{ asset('fonts/bai-jamjuree-700.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/manrope-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'DS Store',
            'url' => route('home'),
            'logo' => asset('images/logo-store.png'),
            'description' => config('site.description'),
            'sameAs' => [config('site.instagram_url')],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'telephone' => '+' . config('site.whatsapp'),
                'availableLanguage' => ['id'],
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Indonesia',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>
<body class="site-body">
    <a class="skip-link" href="#main">Lewati ke konten utama</a>
    <div class="ambient" aria-hidden="true">
        <span class="ambient__glow ambient__glow--cyan"></span>
        <span class="ambient__glow ambient__glow--gold"></span>
        <span class="ambient__grid"></span>
    </div>

    <x-site-header />

    <main id="main" class="site-main">
        <div class="container flash-stack">
            <x-flash />
        </div>
        @yield('content')
    </main>

    <x-site-footer />
    <x-site-chatbot />

    @stack('scripts')
</body>
</html>
