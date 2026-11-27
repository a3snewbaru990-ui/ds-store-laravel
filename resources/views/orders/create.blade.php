@extends('layouts.app')

@section('title', 'Order ' . $package->name . ' | DS Store')
@section('description', 'Form pemesanan joki Mobile Legends paket ' . $package->name . ' di DS Store.')
@section('robots', 'noindex, nofollow')

@php
    $isStarPackage = $package->isStarPackage();
    $initialStars = $isStarPackage ? (int) old('stars', request('stars', 10)) : 10;
    $initialStars = max(1, min(100, $initialStars));
    $initialTotal = $isStarPackage ? $package->price * $initialStars : $package->price;
@endphp

@section('content')
    <section class="page-hero page-hero--compact">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('home') }}#pricelist">Pricelist</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Order {{ $package->name }}</span>
            </nav>

            <div class="page-heading">
                <div>
                    <p class="eyebrow">Form pesanan</p>
                    <h1>Siapkan data akun, sisanya diatur admin.</h1>
                    <p>Isi data dengan benar supaya admin bisa langsung mengecek paket dan memberi estimasi pengerjaan.</p>
                </div>
                <a href="{{ route('home') }}#pricelist" class="btn btn--ghost btn--sm">
                    <x-icon name="arrow-left" />
                    Ubah paket
                </a>
            </div>
        </div>
    </section>

    <section class="section section--form">
        <div class="container">
            <form
                action="{{ route('orders.store') }}"
                method="POST"
                class="order-layout"
                data-order-form
                data-unit-price="{{ $package->price }}"
                data-is-star-package="{{ $isStarPackage ? '1' : '0' }}"
            >
                @csrf
                <input type="hidden" name="service_package_id" value="{{ $package->id }}">
                @if (! $isStarPackage)
                    <input type="hidden" name="stars" value="10">
                @endif

                <div class="order-form">
                    <section class="form-panel" aria-labelledby="account-data-title">
                        <div class="form-panel__head">
                            <span class="form-panel__step">01</span>
                            <div>
                                <h2 id="account-data-title">Data akun Mobile Legends</h2>
                                <p>Pastikan nama dan ID sesuai dengan akun yang akan dijoki.</p>
                            </div>
                        </div>

                        <div class="form-panel__body">
                            <div class="field">
                                <label for="game_nickname">Nama in-game</label>
                                <input
                                    type="text"
                                    id="game_nickname"
                                    name="game_nickname"
                                    value="{{ old('game_nickname') }}"
                                    autocomplete="off"
                                    placeholder="Contoh: DS Player"
                                    required
                                    @error('game_nickname') aria-invalid="true" aria-describedby="game_nickname-error" @enderror
                                >
                                <x-input-error name="game_nickname" />
                            </div>

                            <div class="field-grid">
                                <div class="field">
                                    <label for="game_id">User ID</label>
                                    <input
                                        type="text"
                                        id="game_id"
                                        name="game_id"
                                        value="{{ old('game_id') }}"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        placeholder="123456789"
                                        autocomplete="off"
                                        required
                                        aria-describedby="game_id-help @error('game_id') game_id-error @enderror"
                                        @error('game_id') aria-invalid="true" @enderror
                                    >
                                    <p class="field-help" id="game_id-help">Angka di bawah nickname, tanpa spasi.</p>
                                    <x-input-error name="game_id" />
                                </div>

                                <div class="field">
                                    <label for="server_id">Server ID</label>
                                    <input
                                        type="text"
                                        id="server_id"
                                        name="server_id"
                                        value="{{ old('server_id') }}"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        placeholder="2001"
                                        autocomplete="off"
                                        required
                                        aria-describedby="server_id-help @error('server_id') server_id-error @enderror"
                                        @error('server_id') aria-invalid="true" @enderror
                                    >
                                    <p class="field-help" id="server_id-help">Angka setelah tanda kurung.</p>
                                    <x-input-error name="server_id" />
                                </div>
                            </div>

                            <fieldset class="field field--fieldset">
                                <legend>Metode login akun</legend>
                                <div class="choice-grid choice-grid--four">
                                    @foreach (['Moonton', 'Facebook', 'Google', 'TikTok'] as $method)
                                        <label class="choice-card">
                                            <input type="radio" name="login_method" value="{{ $method }}" @checked(old('login_method', 'Moonton') === $method) required>
                                            <span>{{ $method }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error name="login_method" />
                            </fieldset>

                            <div class="form-divider"></div>

                            <div class="form-panel__head form-panel__head--sub">
                                <span class="form-panel__step">02</span>
                                <div>
                                    <h2>Kontak dan preferensi</h2>
                                    <p>Admin memakai nomor ini untuk konfirmasi pesanan.</p>
                                </div>
                            </div>

                            <div class="field-grid">
                                <div class="field">
                                    <label for="whatsapp">Nomor WhatsApp</label>
                                    <input
                                        type="tel"
                                        id="whatsapp"
                                        name="whatsapp"
                                        value="{{ old('whatsapp', $user->whatsapp) }}"
                                        inputmode="tel"
                                        autocomplete="tel"
                                        placeholder="081807283181"
                                        required
                                        aria-describedby="whatsapp-help @error('whatsapp') whatsapp-error @enderror"
                                        @error('whatsapp') aria-invalid="true" @enderror
                                    >
                                    <p class="field-help" id="whatsapp-help">Pastikan nomor aktif dan bisa menerima pesan.</p>
                                    <x-input-error name="whatsapp" />
                                </div>

                                <div class="field">
                                    <label for="payment_method">Metode pembayaran <span class="label-optional">(opsional)</span></label>
                                    <select
                                        id="payment_method"
                                        name="payment_method"
                                        aria-describedby="payment_method-help"
                                    >
                                        <option value="">Pilih nanti via WhatsApp</option>
                                        @foreach (['QRIS', 'Transfer bank', 'E-wallet'] as $method)
                                            <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ $method }}</option>
                                        @endforeach
                                    </select>
                                    <p class="field-help" id="payment_method-help">Detail pembayaran dikirim admin setelah konfirmasi.</p>
                                </div>
                            </div>

                            @if ($isStarPackage)
                                <div class="field">
                                    <label for="stars">Jumlah bintang</label>
                                    <div class="stepper stepper--form">
                                        <button type="button" data-order-step="-1" aria-label="Kurangi jumlah bintang">
                                            <x-icon name="minus" />
                                        </button>
                                        <input
                                            type="number"
                                            id="stars"
                                            name="stars"
                                            inputmode="numeric"
                                            min="1"
                                            max="100"
                                            value="{{ $initialStars }}"
                                            required
                                            data-order-stars
                                            aria-describedby="stars-help @error('stars') stars-error @enderror"
                                            @error('stars') aria-invalid="true" @enderror
                                        >
                                        <button type="button" data-order-step="1" aria-label="Tambah jumlah bintang">
                                            <x-icon name="plus" />
                                        </button>
                                    </div>
                                    <p class="field-help" id="stars-help">Satu bintang dihitung {{ $package->price_label }}.</p>
                                    <x-input-error name="stars" />
                                </div>
                            @else
                                <div class="fixed-quantity">
                                    <x-icon name="trophy" />
                                    <span>Paket ini mencakup <strong>10 match grading</strong>.</span>
                                </div>
                            @endif

                            <div class="field">
                                <label for="notes">Catatan untuk admin <span class="label-optional">(opsional)</span></label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    maxlength="500"
                                    placeholder="Contoh: prefer hero marksman, mohon update progres malam hari."
                                    aria-describedby="notes-help"
                                >{{ old('notes') }}</textarea>
                                <p class="field-help" id="notes-help">Maksimal 500 karakter. Jangan tulis password di sini.</p>
                                <x-input-error name="notes" />
                            </div>

                            <div class="security-note">
                                <span class="security-note__icon"><x-icon name="lock" /></span>
                                <p><strong>Keamanan akun.</strong> Form ini tidak meminta password. Detail login hanya dibahas dengan admin lewat WhatsApp resmi DS Store.</p>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="order-summary" aria-labelledby="summary-title">
                    <div class="order-summary__card">
                        <div class="order-summary__head">
                            <p class="eyebrow">Ringkasan</p>
                            <h2 id="summary-title">{{ $package->name }}</h2>
                        </div>

                        <div class="summary-package">
                            <span class="summary-package__emblem">
                                <img src="{{ $package->emblem_url }}" alt="" width="80" height="80">
                            </span>
                            <div>
                                <span>{{ $package->short_name }}</span>
                                <strong>{{ $package->price_label }} <small>/ {{ $package->unit_label }}</small></strong>
                            </div>
                        </div>

                        <dl class="summary-list">
                            <div>
                                <dt>Jenis paket</dt>
                                <dd>{{ $isStarPackage ? 'Joki per bintang' : 'Full grading' }}</dd>
                            </div>
                            <div>
                                <dt>Jumlah</dt>
                                <dd data-summary-stars>{{ $initialStars }} {{ $isStarPackage ? 'bintang' : 'match' }}</dd>
                            </div>
                            <div>
                                <dt>Pembayaran</dt>
                                <dd>Dikonfirmasi admin</dd>
                            </div>
                        </dl>

                        <div class="summary-total">
                            <span>Total</span>
                            <strong data-summary-total>Rp {{ number_format($initialTotal, 0, ',', '.') }}</strong>
                        </div>

                        <button type="submit" class="btn btn--primary btn--block">
                            Buat pesanan
                            <x-icon name="arrow-right" />
                        </button>

                        <p class="summary-note">Kode pesanan dibuat otomatis setelah kamu menekan tombol di atas.</p>
                    </div>

                    <div class="summary-help">
                        <x-icon name="message" />
                        <p>Butuh bantuan sebelum order? <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener">Chat admin</a>.</p>
                    </div>
                </aside>
            </form>
        </div>
    </section>
@endsection
