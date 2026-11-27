@extends('layouts.workspace')

@section('title', 'Dashboard Admin | DS Store')
@section('page-kicker', 'Admin workspace')
@section('page-title', 'Dashboard admin')

@section('content')
    <section class="workspace-hero workspace-hero--admin">
        <div>
            <p class="eyebrow">Ringkasan toko</p>
            <h2>Semua pesanan, satu layar.</h2>
            <p>Pantau pesanan masuk, perbarui status, dan cek performa paket dari sini.</p>
        </div>
        <div class="workspace-hero__actions">
            <a href="{{ route('admin.orders.index') }}" class="btn btn--primary">
                Kelola pesanan
                <x-icon name="arrow-right" />
            </a>
            <a href="{{ route('admin.packages.index') }}" class="btn btn--ghost">
                <x-icon name="package" />
                Paket joki
            </a>
        </div>
        <span class="workspace-hero__watermark" aria-hidden="true">DS</span>
    </section>

    <section class="stats-grid" aria-label="Statistik toko">
        <article class="stat-card">
            <span class="stat-card__icon"><x-icon name="receipt" /></span>
            <p>Total pesanan</p>
            <strong>{{ $totalOrders }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-card__icon stat-card__icon--cyan"><x-icon name="bolt" /></span>
            <p>Sedang aktif</p>
            <strong>{{ $activeOrders }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-card__icon stat-card__icon--gold"><x-icon name="trophy" /></span>
            <p>Selesai</p>
            <strong>{{ $completedOrders }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-card__icon"><x-icon name="wallet" /></span>
            <p>Total pembayaran lunas</p>
            <strong class="stat-card__money">Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
        </article>
    </section>

    <div class="admin-dashboard-grid">
        <section class="workspace-section" aria-labelledby="status-breakdown-title">
            <div class="workspace-section__head">
                <div>
                    <p class="eyebrow">Status pesanan</p>
                    <h2 id="status-breakdown-title">Sebaran pesanan</h2>
                </div>
                <span class="badge badge--neutral">{{ $customerCount }} pelanggan</span>
            </div>

            <div class="status-breakdown">
                @foreach (\App\Models\Order::STATUSES as $key => $meta)
                    @php
                        $count = (int) ($statusCounts[$key] ?? 0);
                        $percent = $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0;
                    @endphp
                    <div class="status-breakdown__row">
                        <div class="status-breakdown__label">
                            <span class="status-breakdown__dot status-breakdown__dot--{{ $meta['tone'] }}" aria-hidden="true"></span>
                            <span>{{ $meta['label'] }}</span>
                            <strong>{{ $count }}</strong>
                        </div>
                        <div class="status-breakdown__track" role="img" aria-label="{{ $meta['label'] }}: {{ $count }} pesanan ({{ $percent }}%)">
                            <span class="status-breakdown__fill status-breakdown__fill--{{ $meta['tone'] }}" style="width: {{ $percent }}%"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="workspace-section" aria-labelledby="top-packages-title">
            <div class="workspace-section__head">
                <div>
                    <p class="eyebrow">Paket</p>
                    <h2 id="top-packages-title">Paling sering dipesan</h2>
                </div>
            </div>
            <ol class="top-package-list">
                @forelse ($topPackages as $package)
                    <li>
                        <span class="top-package-list__emblem">
                            <img src="{{ $package->emblem_url }}" alt="" width="44" height="44" loading="lazy">
                        </span>
                        <span class="top-package-list__meta">
                            <strong>{{ $package->name }}</strong>
                            <small>{{ $package->price_label }} / {{ $package->unit_label }}</small>
                        </span>
                        <span class="top-package-list__count">{{ $package->orders_count }} order</span>
                    </li>
                @empty
                    <li class="top-package-list__empty">Belum ada data pesanan.</li>
                @endforelse
            </ol>
        </section>
    </div>

    <section class="workspace-section" aria-labelledby="recent-admin-orders-title">
        <div class="workspace-section__head">
            <div>
                <p class="eyebrow">Antrean</p>
                <h2 id="recent-admin-orders-title">Pesanan terbaru</h2>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-link">Lihat semua <x-icon name="arrow-right" /></a>
        </div>

        @if ($recentOrders->isEmpty())
            <x-empty-state
                icon="receipt"
                title="Belum ada pesanan"
                description="Pesanan dari pelanggan akan muncul di sini."
            />
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Paket</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="table-code">{{ $order->order_code }}</span>
                                    <small>{{ $order->created_at->translatedFormat('d M Y') }}</small>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->package_name }}</td>
                                <td class="table-money">{{ $order->total_price_label }}</td>
                                <td><x-status-badge :tone="$order->status_tone" :label="$order->status_label" /></td>
                                <td class="table-action">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn--ghost btn--xs">
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
@endsection
