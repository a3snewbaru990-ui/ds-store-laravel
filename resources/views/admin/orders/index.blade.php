@extends('layouts.workspace')

@section('title', 'Pesanan | Admin DS Store')
@section('page-kicker', 'Admin workspace')
@section('page-title', 'Pesanan')

@section('content')
    <section class="workspace-section">
        <div class="workspace-section__head workspace-section__head--stack">
            <div>
                <p class="eyebrow">Antrean pesanan</p>
                <h2>Semua pesanan masuk</h2>
                <p class="workspace-section__lead">Cari berdasarkan kode, nama pelanggan, atau User ID. Filter status untuk melihat antrean tertentu.</p>
            </div>

            <form action="{{ route('admin.orders.index') }}" method="GET" class="filter-bar">
                <div class="filter-bar__field filter-bar__field--search">
                    <label for="q" class="sr-only">Cari pesanan</label>
                    <x-icon name="search" />
                    <input type="search" id="q" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, User ID...">
                </div>
                <div class="filter-bar__field">
                    <label for="status" class="sr-only">Filter status</label>
                    <select id="status" name="status">
                        <option value="">Semua status</option>
                        @foreach ($statuses as $key => $meta)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn--primary btn--sm">Terapkan</button>
                @if (request()->filled('q') || request()->filled('status'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn--ghost btn--sm">Reset</a>
                @endif
            </form>
        </div>

        @if ($orders->isEmpty())
            <x-empty-state
                icon="search"
                title="Pesanan tidak ditemukan"
                description="Coba kata kunci lain atau reset filter status."
                action-label="Reset filter"
                :action-url="route('admin.orders.index')"
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
                            <th scope="col">Pembayaran</th>
                            <th scope="col">Status</th>
                            <th scope="col"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <span class="table-code">{{ $order->order_code }}</span>
                                    <small>{{ $order->created_at->translatedFormat('d M Y, H:i') }}</small>
                                </td>
                                <td>
                                    <span>{{ $order->user->name }}</span>
                                    <small>{{ $order->game_nickname }} · {{ $order->game_id }}</small>
                                </td>
                                <td>{{ $order->package_name }}</td>
                                <td class="table-money">{{ $order->total_price_label }}</td>
                                <td>{{ $order->payment_status_label }}</td>
                                <td><x-status-badge :tone="$order->status_tone" :label="$order->status_label" /></td>
                                <td class="table-action">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn--ghost btn--xs">
                                        Kelola
                                        <x-icon name="arrow-right" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$orders" />
        @endif
    </section>
@endsection
