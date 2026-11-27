@extends('layouts.workspace')

@section('title', 'Paket Joki | Admin DS Store')
@section('page-kicker', 'Admin workspace')
@section('page-title', 'Paket joki')

@section('content')
    <section class="workspace-section">
        <div class="workspace-section__head">
            <div>
                <p class="eyebrow">Pricelist</p>
                <h2>Kelola paket dan harga</h2>
                <p class="workspace-section__lead">Perubahan harga langsung dipakai di halaman pricelist dan form pesanan.</p>
            </div>
            <a href="{{ route('admin.packages.create') }}" class="btn btn--primary btn--sm">
                <x-icon name="plus" />
                Tambah paket
            </a>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Paket</th>
                        <th scope="col">Rank</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Order</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packages as $package)
                        <tr>
                            <td>
                                <span class="table-package">
                                    <span class="table-package__emblem">
                                        <img src="{{ $package->emblem_url }}" alt="" width="42" height="42" loading="lazy">
                                    </span>
                                    <span>
                                        <strong>{{ $package->name }}</strong>
                                        <small>{{ $package->short_name }} · {{ $package->badge ?: 'Tanpa badge' }}</small>
                                    </span>
                                </span>
                            </td>
                            <td>{{ $package->rank_key }}</td>
                            <td class="table-money">{{ $package->price_label }} <small>/ {{ $package->unit_label }}</small></td>
                            <td>{{ $package->orders_count }}</td>
                            <td>
                                <div class="badge-stack">
                                    <x-status-badge :tone="$package->is_active ? 'success' : 'neutral'" :label="$package->is_active ? 'Aktif' : 'Nonaktif'" />
                                    @if ($package->is_featured)
                                        <span class="badge badge--gold">Unggulan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="table-action">
                                <div class="table-actions">
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn--ghost btn--xs">
                                        <x-icon name="edit" />
                                        Edit
                                    </a>
                                    @if ($package->orders_count === 0)
                                        <button
                                            type="button"
                                            class="btn btn--danger-ghost btn--xs"
                                            data-delete-package
                                            data-name="{{ $package->name }}"
                                            data-action="{{ route('admin.packages.destroy', $package) }}"
                                        >
                                            <x-icon name="trash" />
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <dialog class="confirm-dialog" data-confirm-dialog aria-labelledby="delete-dialog-title">
        <form method="POST" data-confirm-form>
            @csrf
            @method('DELETE')
            <div class="confirm-dialog__icon"><x-icon name="trash" /></div>
            <h2 id="delete-dialog-title">Hapus paket?</h2>
            <p data-confirm-message>Paket ini akan dihapus dari pricelist.</p>
            <div class="confirm-dialog__actions">
                <button type="button" class="btn btn--ghost" data-confirm-cancel>Batal</button>
                <button type="submit" class="btn btn--danger">Hapus paket</button>
            </div>
        </form>
    </dialog>
@endsection
