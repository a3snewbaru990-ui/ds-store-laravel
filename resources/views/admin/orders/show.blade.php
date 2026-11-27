@extends('layouts.workspace')

@section('title', 'Kelola ' . $order->order_code . ' | Admin DS Store')
@section('page-kicker', 'Kelola pesanan')
@section('page-title', $order->order_code)

@php
    $timeline = $order->events->sortBy('created_at');
    $waUrl = 'https://wa.me/' . $order->whatsapp . '?text=' . rawurlencode('Halo ' . $order->user->name . ', kami dari DS Store ingin mengonfirmasi pesanan ' . $order->order_code . '.');
@endphp

@section('content')
    <div class="workspace-toolbar">
        <a href="{{ route('admin.orders.index') }}" class="back-link">
            <x-icon name="arrow-left" />
            Kembali ke daftar pesanan
        </a>
        <div class="toolbar-actions">
            <x-status-badge :tone="$order->status_tone" :label="$order->status_label" />
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn--ghost btn--xs">
                <x-icon name="whatsapp" />
                Hubungi pelanggan
            </a>
        </div>
    </div>

    <section class="order-overview">
        <div class="order-overview__main">
            <div class="order-overview__package">
                <span class="order-overview__emblem">
                    <img src="{{ $order->package->emblem_url }}" alt="" width="72" height="72">
                </span>
                <div>
                    <p class="eyebrow">Pesanan pelanggan</p>
                    <h2>{{ $order->package_name }}</h2>
                    <p>{{ $order->stars }} {{ $order->package_unit === 'star' ? 'bintang' : 'match' }} · {{ $order->unit_price_label }} / {{ $order->package_unit === 'star' ? 'bintang' : 'paket' }}</p>
                </div>
            </div>
            <div class="order-overview__amount">
                <span>Total</span>
                <strong>{{ $order->total_price_label }}</strong>
                <x-status-badge tone="{{ $order->payment_status === 'paid' ? 'success' : 'neutral' }}" :label="$order->payment_status_label" />
            </div>
        </div>
        <p class="order-overview__status">{{ $order->status_description }}</p>
    </section>

    <div class="order-detail-layout">
        <div class="order-detail-main">
            <section class="detail-panel" aria-labelledby="customer-title">
                <div class="detail-panel__head">
                    <h2 id="customer-title">Data pelanggan</h2>
                    <span class="badge badge--neutral">{{ $order->user->orders()->count() }} order</span>
                </div>
                <dl class="detail-list detail-list--grid">
                    <div>
                        <dt>Nama akun</dt>
                        <dd>{{ $order->user->name }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>{{ $order->user->email }}</dd>
                    </div>
                    <div>
                        <dt>WhatsApp</dt>
                        <dd>{{ $order->whatsapp }}</dd>
                    </div>
                    <div>
                        <dt>Login game</dt>
                        <dd>{{ $order->login_method }}</dd>
                    </div>
                    <div>
                        <dt>Nama in-game</dt>
                        <dd>{{ $order->game_nickname }}</dd>
                    </div>
                    <div>
                        <dt>User ID / Server</dt>
                        <dd>{{ $order->game_id }} / {{ $order->server_id }}</dd>
                    </div>
                </dl>

                @if ($order->notes)
                    <div class="detail-note">
                        <span>Catatan pelanggan</span>
                        <p>{{ $order->notes }}</p>
                    </div>
                @endif
            </section>

            <section class="detail-panel" aria-labelledby="admin-timeline-title">
                <div class="detail-panel__head">
                    <h2 id="admin-timeline-title">Riwayat pesanan</h2>
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
        </div>

        <aside class="order-detail-side">
            <section class="summary-card summary-card--admin">
                <p class="eyebrow">Perbarui pesanan</p>
                <h2>Status & pembayaran</h2>

                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="form-stack">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="status">Status pesanan</label>
                        <select id="status" name="status" required>
                            @foreach ($statuses as $key => $meta)
                                <option value="{{ $key }}" @selected(old('status', $order->status) === $key)>{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                        <x-input-error name="status" />
                    </div>

                    <div class="field">
                        <label for="payment_status">Status pembayaran</label>
                        <select id="payment_status" name="payment_status" required>
                            @foreach ($paymentStatuses as $key => $label)
                                <option value="{{ $key }}" @selected(old('payment_status', $order->payment_status) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error name="payment_status" />
                    </div>

                    <div class="field">
                        <label for="admin_note">Catatan admin</label>
                        <textarea id="admin_note" name="admin_note" rows="4" maxlength="1000" placeholder="Update progres, kendala, atau info pembayaran...">{{ old('admin_note', $order->admin_note) }}</textarea>
                        <p class="field-help">Catatan ini terlihat oleh pelanggan.</p>
                        <x-input-error name="admin_note" />
                    </div>

                    <button type="submit" class="btn btn--primary btn--block">
                        Simpan perubahan
                        <x-icon name="check" />
                    </button>
                </form>
            </section>

            <section class="summary-card">
                <p class="eyebrow">Detail transaksi</p>
                <dl class="summary-list">
                    <div>
                        <dt>Dibuat</dt>
                        <dd>{{ $order->created_at->translatedFormat('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt>Metode bayar</dt>
                        <dd>{{ $order->payment_method ?: 'Belum dipilih' }}</dd>
                    </div>
                    <div>
                        <dt>Selesai</dt>
                        <dd>{{ $order->completed_at?->translatedFormat('d M Y, H:i') ?? '-' }}</dd>
                    </div>
                </dl>
                <div class="summary-total">
                    <span>Total</span>
                    <strong>{{ $order->total_price_label }}</strong>
                </div>
            </section>
        </aside>
    </div>
@endsection
