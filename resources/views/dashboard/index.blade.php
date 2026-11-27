@extends('layouts.workspace')

@section('title', 'Dashboard | DS Store')
@section('page-kicker', 'Player workspace')
@section('page-title', 'Dashboard')

@php
    $user = auth()->user();
@endphp

@section('content')
    <section class="workspace-hero">
        <div>
            <p class="eyebrow">Halo, {{ explode(' ', trim($user->name))[0] }}</p>
            <h2>Rank berikutnya menunggu.</h2>
            <p>Semua pesanan dan progres joki kamu ada di sini. Mulai order baru atau cek status pesanan yang sedang berjalan.</p>
        </div>
        <div class="workspace-hero__actions">
            <a href="{{ route('home') }}#pricelist" class="btn btn--primary">
                Order baru
                <x-icon name="arrow-right" />
            </a>
            <a href="{{ route('track.index') }}" class="btn btn--ghost">
                <x-icon name="search" />
                Lacak pesanan
            </a>
        </div>
        <span class="workspace-hero__watermark" aria-hidden="true">42</span>
    </section>

    <section class="stats-grid" aria-label="Ringkasan pesanan">
        <article class="stat-card">
            <span class="stat-card__icon"><x-icon name="receipt" /></span>
            <p>Total pesanan</p>
            <strong>{{ $totalOrders }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-card__icon stat-card__icon--cyan"><x-icon name="bolt" /></span>
            <p>Sedang berjalan</p>
            <strong>{{ $activeOrders }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-card__icon stat-card__icon--gold"><x-icon name="trophy" /></span>
            <p>Selesai</p>
            <strong>{{ $completedOrders }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-card__icon"><x-icon name="wallet" /></span>
            <p>Total transaksi selesai</p>
            <strong class="stat-card__money">Rp {{ number_format($totalSpent, 0, ',', '.') }}</strong>
        </article>
    </section>

    <section class="workspace-section" id="pesanan-terbaru" aria-labelledby="recent-orders-title">
        <div class="workspace-section__head">
            <div>
                <p class="eyebrow">Riwayat</p>
                <h2 id="recent-orders-title">Pesanan terbaru</h2>
            </div>
            @if ($orders->isNotEmpty())
                <a href="{{ route('home') }}#pricelist" class="text-link">Buat pesanan lagi <x-icon name="arrow-right" /></a>
            @endif
        </div>

        @if ($orders->isEmpty())
            <x-empty-state
                icon="receipt"
                title="Belum ada pesanan"
                description="Pilih paket joki pertamamu dan progresnya akan muncul di dashboard ini."
                action-label="Lihat pricelist"
                :action-url="route('home') . '#pricelist'"
            />
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Paket</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <span class="table-code">{{ $order->order_code }}</span>
                                    <small>{{ $order->created_at->translatedFormat('d M Y') }}</small>
                                </td>
                                <td>{{ $order->package_name }}</td>
                                <td>{{ $order->stars }} {{ $order->package_unit === 'star' ? 'bintang' : 'match' }}</td>
                                <td class="table-money">{{ $order->total_price_label }}</td>
                                <td><x-status-badge :tone="$order->status_tone" :label="$order->status_label" /></td>
                                <td class="table-action">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn--ghost btn--xs">
                                        Detail
                                        <x-icon name="arrow-right" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="workspace-grid" aria-label="Informasi akun">
        <article class="info-card">
            <span class="info-card__icon"><x-icon name="shield-check" /></span>
            <div>
                <h3>Akunmu aman</h3>
                <p>Website tidak menyimpan password game. Detail akun hanya dibahas lewat admin WhatsApp.</p>
            </div>
        </article>
        <article class="info-card">
            <span class="info-card__icon info-card__icon--gold"><x-icon name="clock" /></span>
            <div>
                <h3>Butuh update cepat?</h3>
                <p>Hubungi admin dan sertakan kode pesanan. Admin akan mengecek antrean terbaru.</p>
            </div>
        </article>
    </section>
@endsection
