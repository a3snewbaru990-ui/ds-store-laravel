@extends('layouts.app')

@section('title', 'Lacak Pesanan | DS Store')
@section('description', 'Cek status pesanan joki Mobile Legends DS Store dengan kode pesanan dan nomor WhatsApp.')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="page-hero page-hero--compact">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Lacak pesanan</span>
            </nav>
            <div class="page-heading">
                <div>
                    <p class="eyebrow">Cek progres</p>
                    <h1>Lacak pesanan tanpa harus login.</h1>
                    <p>Masukkan kode pesanan dan nomor WhatsApp yang dipakai saat order. Data ini hanya menampilkan status pesanan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--track">
        <div class="container">
            <div class="track-layout">
                <form action="{{ route('track') }}" method="POST" class="track-form">
                    @csrf
                    <div class="form-panel__head">
                        <span class="form-panel__step"><x-icon name="search" /></span>
                        <div>
                            <h2>Cari pesanan</h2>
                            <p>Kode pesanan ada di halaman detail setelah order dibuat.</p>
                        </div>
                    </div>

                    <div class="form-panel__body">
                        <div class="field">
                            <label for="order_code">Kode pesanan</label>
                            <input
                                type="text"
                                id="order_code"
                                name="order_code"
                                value="{{ old('order_code', $order?->order_code) }}"
                                placeholder="DS-XXXXXX-XXXXX"
                                autocomplete="off"
                                required
                                aria-describedby="order_code-help @error('order_code') order_code-error @enderror"
                                @error('order_code') aria-invalid="true" @enderror
                            >
                            <p class="field-help" id="order_code-help">Contoh: DS-261128-XXXXX.</p>
                            <x-input-error name="order_code" />
                        </div>

                        <div class="field">
                            <label for="whatsapp">Nomor WhatsApp</label>
                            <input
                                type="tel"
                                id="whatsapp"
                                name="whatsapp"
                                value="{{ old('whatsapp', $whatsapp ?? '') }}"
                                inputmode="tel"
                                autocomplete="tel"
                                placeholder="081807283181"
                                required
                                aria-describedby="whatsapp-help @error('whatsapp') whatsapp-error @enderror"
                                @error('whatsapp') aria-invalid="true" @enderror
                            >
                            <p class="field-help" id="whatsapp-help">Nomor yang dipakai saat membuat pesanan.</p>
                            <x-input-error name="whatsapp" />
                        </div>

                        <button type="submit" class="btn btn--primary btn--block">
                            Cek status pesanan
                            <x-icon name="arrow-right" />
                        </button>
                    </div>
                </form>

                <div class="track-result" aria-live="polite">
                    @if ($order)
                        <div class="track-result__head">
                            <div>
                                <p class="eyebrow">Hasil pencarian</p>
                                <h2>{{ $order->order_code }}</h2>
                            </div>
                            <x-status-badge :tone="$order->status_tone" :label="$order->status_label" />
                        </div>

                        <div class="track-result__package">
                            <span class="track-result__emblem">
                                <img src="{{ $order->package->emblem_url }}" alt="" width="72" height="72">
                            </span>
                            <div>
                                <strong>{{ $order->package_name }}</strong>
                                <span>{{ $order->stars }} {{ $order->package_unit === 'star' ? 'bintang' : 'match' }} · {{ $order->total_price_label }}</span>
                            </div>
                        </div>

                        <p class="track-result__description">{{ $order->status_description }}</p>

                        <ol class="timeline timeline--compact">
                            @foreach ($order->events->sortBy('created_at') as $event)
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

                        <a href="{{ route('login') }}" class="btn btn--ghost btn--block">
                            Masuk untuk melihat detail lengkap
                            <x-icon name="arrow-right" />
                        </a>
                    @else
                        <div class="track-result__placeholder">
                            <span class="track-result__placeholder-icon"><x-icon name="receipt" /></span>
                            <h2>Status pesanan akan muncul di sini.</h2>
                            <p>Kalau kamu sudah punya akun, login ke dashboard untuk melihat riwayat dan detail pesanan.</p>
                            <a href="{{ route('login') }}" class="text-link">Masuk ke dashboard <x-icon name="arrow-right" /></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
