@extends('layouts.workspace')

@section('title', 'Pelanggan | Admin DS Store')
@section('page-kicker', 'Admin workspace')
@section('page-title', 'Pelanggan')

@section('content')
    <section class="workspace-section">
        <div class="workspace-section__head workspace-section__head--stack">
            <div>
                <p class="eyebrow">Database pelanggan</p>
                <h2>Akun terdaftar</h2>
                <p class="workspace-section__lead">Lihat data kontak dan jumlah pesanan setiap pelanggan.</p>
            </div>

            <form action="{{ route('admin.customers.index') }}" method="GET" class="filter-bar">
                <div class="filter-bar__field filter-bar__field--search">
                    <label for="q" class="sr-only">Cari pelanggan</label>
                    <x-icon name="search" />
                    <input type="search" id="q" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, WhatsApp...">
                </div>
                <button type="submit" class="btn btn--primary btn--sm">Cari</button>
                @if (request()->filled('q'))
                    <a href="{{ route('admin.customers.index') }}" class="btn btn--ghost btn--sm">Reset</a>
                @endif
            </form>
        </div>

        @if ($customers->isEmpty())
            <x-empty-state
                icon="users"
                title="Pelanggan tidak ditemukan"
                description="Coba kata kunci lain."
                action-label="Reset pencarian"
                :action-url="route('admin.customers.index')"
            />
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Kontak</th>
                            <th scope="col">Total order</th>
                            <th scope="col">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <span class="table-person">
                                        <span class="table-person__avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                        <span>
                                            <strong>{{ $customer->name }}</strong>
                                            <small>{{ $customer->email }}</small>
                                        </span>
                                    </span>
                                </td>
                                <td>{{ $customer->whatsapp ?: '-' }}</td>
                                <td>{{ $customer->orders_count }}</td>
                                <td>{{ $customer->created_at->translatedFormat('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$customers" />
        @endif
    </section>
@endsection
