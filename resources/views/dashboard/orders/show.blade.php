@extends('layouts.workspace')

@section('title', 'Pesanan ' . $order->order_code . ' | DS Store')
@section('page-kicker', 'Detail pesanan')
@section('page-title', $order->order_code)

@php
    $waMessage = 'Halo admin DS Store, saya mau tanya pesanan ' . $order->order_code . '.';
    $waUrl = 'https://wa.me/' . config('site.whatsapp') . '?text=' . rawurlencode($waMessage);
    $timeline = $order->events->sortBy('created_at');
@endphp

@section('content')
    <div class="workspace-toolbar">
        <a href="{{ route('dashboard') }}" class="back-link">
            <x-icon name="arrow-left" />
            Kembali ke dashboard
        </a>
        <x-status-badge :tone="$order->status_tone" :label="$order->status_label" />
    </div>

    <section class="order-overview">
        <div class="order-overview__main">
            <div class="order-overview__package">
                <span class="order-overview__emblem">
                    <img src="{{ $order->package->emblem_url }}" alt="" width="72" height="72">
                </span>
                <div>
                    <p class="eyebrow">Paket joki</p>
                    <h2>{{ $order->package_name }}</h2>
                    <p>{{ $order->stars }} {{ $order->package_unit === 'star' ? 'bintang' : 'match' }} · {{ $order->unit_price_label }} / {{ $order->package_unit === 'star' ? 'bintang' : 'paket' }}</p>
                </div>
            </div>
            <div class="order-overview__amount">
                <span>Total pesanan</span>
                <strong>{{ $order->total_price_label }}</strong>
                <x-status-badge tone="{{ $order->payment_status === 'paid' ? 'success' : 'neutral' }}" :label="$order->payment_status_label" />
            </div>
        </div>
        <p class="order-overview__status">{{ $order->status_description }}</p>
    </section>

    <div class="order-detail-layout">
        <div class="order-detail-main">
            <section class="detail-panel" aria-labelledby="account-info-title">
                <div class="detail-panel__head">
                    <h2 id="account-info-title">Data akun</h2>
                    <span class="badge badge--neutral">{{ $order->login_method }}</span>
                </div>
                <dl class="detail-list detail-list--grid">
                    <div>
                        <dt>Nama in-game</dt>
                        <dd>{{ $order->game_nickname }}</dd>
                    </div>
                    <div>
                        <dt>User ID</dt>
                        <dd>{{ $order->game_id }}</dd>
                    </div>
                    <div>
                        <dt>Server ID</dt>
                        <dd>{{ $order->server_id }}</dd>
                    </div>
                    <div>
                        <dt>WhatsApp</dt>
                        <dd>{{ $order->whatsapp }}</dd>
                    </div>
                </dl>
                @if ($order->notes)
                    <div class="detail-note">
                        <span>Catatan kamu</span>
                        <p>{{ $order->notes }}</p>
                    </div>
                @endif
            </section>

            <section class="detail-panel" aria-labelledby="timeline-title">
                <div class="detail-panel__head">
                    <h2 id="timeline-title">Riwayat progres</h2>
                    <span class="badge badge--neutral">{{ $timeline->count() }} update</span>
                </div>

                <ol class="timeline">
                    @foreach ($timeline as $event)
                        <li class="timeline__item {{ $loop->last ? 'is-current' : '' }}">
                            <span class="timeline__marker" aria-hidden="true"></span>
                            <div class="timeline__content">
                                <div class="timeline__head">
                                    <h3>{{ $event->title }}</h3>
                                    <time datetime="{{ $event->created_at->toIso8601String() }}">{{ $event->created_at->translatedFormat('d M Y, H:i') }}</time>
                                </div>
                                @if ($event->note)
                                    <p>{{ $event->note }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>

            @if ($order->admin_note)
                <section class="detail-panel detail-panel--gold" aria-labelledby="admin-note-title">
                    <div class="detail-panel__head">
                        <h2 id="admin-note-title">Catatan admin</h2>
                        <x-icon name="message" />
                    </div>
                    <p>{{ $order->admin_note }}</p>
                </section>
            @endif
        </div>

        <aside class="order-detail-side">
            <section class="summary-card">
                <p class="eyebrow">Informasi pesanan</p>
                <dl class="summary-list">
                    <div>
                        <dt>Kode pesanan</dt>
                        <dd class="mono">{{ $order->order_code }}</dd>
                    </div>
                    <div>
                        <dt>Dibuat</dt>
                        <dd>{{ $order->created_at->translatedFormat('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt>Pembayaran</dt>
                        <dd>{{ $order->payment_status_label }}</dd>
                    </div>
                    <div>
                        <dt>Metode</dt>
                        <dd>{{ $order->payment_method ?: 'Belum dipilih' }}</dd>
                    </div>
                    @if ($order->completed_at)
                        <div>
                            <dt>Selesai</dt>
                            <dd>{{ $order->completed_at->translatedFormat('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                </dl>
                <div class="summary-total">
                    <span>Total</span>
                    <strong>{{ $order->total_price_label }}</strong>
                </div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn--primary btn--block">
                    <x-icon name="whatsapp" />
                    Tanya admin
                </a>
                <p class="summary-note">Sertakan kode pesanan saat menghubungi admin supaya pengecekan lebih cepat.</p>
            </section>

            <section class="side-note">
                <x-icon name="shield-check" />
                <p>Jangan bagikan password game ke siapa pun selain admin resmi yang menghubungi dari nomor DS Store.</p>
            </section>
        </aside>
    </div>
@endsection
