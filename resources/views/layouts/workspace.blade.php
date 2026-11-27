@php
    $user = auth()->user();
    $isAdmin = request()->routeIs('admin.*');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard | DS Store')</title>
    <meta name="description" content="@yield('description', 'Dashboard DS Store.')">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0f172a">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preload" href="{{ asset('fonts/bai-jamjuree-700.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/manrope-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="workspace-body {{ $isAdmin ? 'is-admin' : 'is-customer' }}">
    <a class="skip-link" href="#workspace-main">Lewati ke konten</a>

    <div class="workspace-shell">
        <aside class="workspace-sidebar" id="workspace-sidebar" aria-label="Navigasi dashboard">
            <div class="workspace-sidebar__head">
                <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="brand brand--workspace">
                    <img src="{{ asset('images/logo-mark.png') }}" alt="" width="46" height="40" class="brand__mark">
                    <span class="brand__text">
                        <span class="brand__name">DS <strong>STORE</strong></span>
                        <span class="brand__sub">{{ $isAdmin ? 'Admin workspace' : 'Player workspace' }}</span>
                    </span>
                </a>
                <button type="button" class="workspace-sidebar__close" data-workspace-close aria-label="Tutup navigasi">
                    <x-icon name="close" />
                </button>
            </div>

            <div class="workspace-sidebar__body">
                <p class="workspace-label">{{ $isAdmin ? 'Kelola toko' : 'Akun saya' }}</p>
                <nav class="workspace-nav">
                    @if ($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                            <x-icon name="dashboard" />
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">
                            <x-icon name="receipt" />
                            <span>Pesanan</span>
                        </a>
                        <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages.*') ? 'is-active' : '' }}">
                            <x-icon name="package" />
                            <span>Paket joki</span>
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'is-active' : '' }}">
                            <x-icon name="users" />
                            <span>Pelanggan</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                            <x-icon name="dashboard" />
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('dashboard') }}#pesanan-terbaru" class="{{ request()->routeIs('orders.show') ? 'is-active' : '' }}">
                            <x-icon name="receipt" />
                            <span>Pesanan saya</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
                            <x-icon name="user" />
                            <span>Profil</span>
                        </a>
                    @endif

                    <a href="{{ route('track.index') }}" class="{{ request()->routeIs('track.*') ? 'is-active' : '' }}">
                        <x-icon name="search" />
                        <span>Lacak pesanan</span>
                    </a>
                    <a href="{{ route('home') }}#pricelist">
                        <x-icon name="sparkles" />
                        <span>{{ $isAdmin ? 'Lihat pricelist' : 'Order baru' }}</span>
                    </a>
                </nav>

                @if ($isAdmin)
                    <p class="workspace-label">Akses cepat</p>
                    <nav class="workspace-nav">
                        <a href="{{ route('home') }}">
                            <x-icon name="external" />
                            <span>Lihat situs</span>
                        </a>
                        @if (! $user->isAdmin())
                            <a href="{{ route('dashboard') }}">
                                <x-icon name="user" />
                                <span>Area pelanggan</span>
                            </a>
                        @endif
                    </nav>
                @endif
            </div>

            <div class="workspace-sidebar__foot">
                <div class="workspace-user">
                    <span class="workspace-user__avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    <span class="workspace-user__meta">
                        <strong>{{ $user->name }}</strong>
                        <small>{{ $user->isAdmin() ? 'Administrator' : 'Pelanggan' }}</small>
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="workspace-logout">
                        <x-icon name="logout" />
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="workspace-scrim" data-workspace-scrim hidden></div>

        <div class="workspace-main">
            <header class="workspace-topbar">
                <div class="workspace-topbar__left">
                    <button type="button" class="workspace-menu" data-workspace-open aria-expanded="false" aria-controls="workspace-sidebar">
                        <x-icon name="menu" />
                        <span class="sr-only">Buka navigasi dashboard</span>
                    </button>
                    <div>
                        <p class="eyebrow">@yield('page-kicker', $isAdmin ? 'Admin workspace' : 'Player workspace')</p>
                        <h1>@yield('page-title', 'Dashboard')</h1>
                    </div>
                </div>
                <div class="workspace-topbar__actions">
                    <a href="{{ route('home') }}" class="topbar-link">
                        <x-icon name="external" />
                        <span>Lihat situs</span>
                    </a>
                    @if ($isAdmin)
                        <span class="badge badge--gold">Admin</span>
                    @else
                        <a href="{{ route('home') }}#pricelist" class="btn btn--primary btn--sm">Order baru</a>
                    @endif
                </div>
            </header>

            <main class="workspace-content" id="workspace-main">
                <x-flash />
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
