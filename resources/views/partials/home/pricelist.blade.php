@php
    $defaultPackage = $starPackages->firstWhere('is_featured', true) ?? $starPackages->first();
    $defaultStars = 10;
@endphp

<section class="section section--pricelist" id="pricelist" aria-labelledby="pricelist-title">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div>
                <p class="eyebrow">Pricelist Season 42</p>
                <h2 id="pricelist-title">Harga jelas dari GM sampai Mythic Glory.</h2>
                <p class="section-lead">Pilih rank, tentukan jumlah bintang, lalu lanjutkan ke form pesanan. Total dihitung otomatis dari harga resmi DS Store.</p>
            </div>
            <div class="section-note">
                <span class="status-dot" aria-hidden="true"></span>
                <span>Harga per bintang<br><strong>Diperbarui untuk Season 42</strong></span>
            </div>
        </div>

        <div class="pricelist-layout">
            <div class="pricelist-main">
                <form class="rank-selector" data-rank-selector aria-label="Pilih paket rank">
                    <fieldset>
                        <legend class="sr-only">Pilih paket rank Mobile Legends</legend>
                        <div class="rank-grid">
                            @foreach ($starPackages as $package)
                                <label class="rank-card {{ $package->is_featured ? 'is-featured' : '' }}">
                                    <input
                                        type="radio"
                                        name="rank_package"
                                        value="{{ $package->id }}"
                                        data-rank-input
                                        data-price="{{ $package->price }}"
                                        data-name="{{ $package->name }}"
                                        data-slug="{{ $package->slug }}"
                                        data-emblem="{{ $package->emblem_url }}"
                                        data-unit="{{ $package->unit }}"
                                        @checked($defaultPackage?->id === $package->id)
                                    >
                                    <span class="rank-card__emblem">
                                        <img src="{{ $package->emblem_url }}" alt="" width="96" height="96" loading="lazy">
                                    </span>
                                    <span class="rank-card__body">
                                        <span class="rank-card__meta">
                                            <span class="rank-card__name">{{ $package->name }}</span>
                                            @if ($package->badge)
                                                <span class="rank-card__badge">{{ $package->badge }}</span>
                                            @endif
                                        </span>
                                        <span class="rank-card__price">{{ $package->price_label }} <small>/ bintang</small></span>
                                    </span>
                                    <span class="rank-card__check" aria-hidden="true"><x-icon name="check" /></span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                </form>

                @if ($featuredPackage)
                    <article class="grading-card" id="paket-grading">
                        <div class="grading-card__media">
                            <img src="{{ asset('images/hero-art-3.webp') }}" alt="" width="960" height="1152" loading="lazy">
                        </div>
                        <div class="grading-card__shade" aria-hidden="true"></div>
                        <div class="grading-card__content">
                            <div class="grading-card__topline">
                                <span class="badge badge--gold">{{ $featuredPackage->badge ?? 'Paket lengkap' }}</span>
                                <span class="grading-card__season">{{ config('site.season') }}</span>
                            </div>
                            <h3>{{ $featuredPackage->name }}</h3>
                            <p>{{ $featuredPackage->description }}</p>
                            <div class="grading-card__bottom">
                                <p class="grading-card__price">
                                    {{ $featuredPackage->price_label }}
                                    <small>/ 10 match</small>
                                </p>
                                <a href="{{ route('orders.create', $featuredPackage) }}" class="btn btn--gold">
                                    Ambil paket
                                    <x-icon name="arrow-right" />
                                </a>
                            </div>
                        </div>
                    </article>
                @endif
            </div>

            <aside class="order-builder" data-order-builder aria-labelledby="builder-title">
                <div class="order-builder__head">
                    <p class="eyebrow">Estimasi pesanan</p>
                    <h3 id="builder-title">Hitung dulu, order kemudian.</h3>
                </div>

                <div class="builder-package">
                    <span class="builder-package__emblem">
                        <img
                            src="{{ $defaultPackage?->emblem_url ?? asset('images/rank-mythic.webp') }}"
                            alt=""
                            width="64"
                            height="64"
                            data-builder-emblem
                        >
                    </span>
                    <span class="builder-package__meta">
                        <small>Paket dipilih</small>
                        <strong data-builder-name>{{ $defaultPackage?->name ?? 'Pilih rank' }}</strong>
                        <span data-builder-price>{{ $defaultPackage?->price_label ?? 'Rp 0' }} / bintang</span>
                    </span>
                </div>

                <div class="builder-field">
                    <label for="builder-stars">Jumlah bintang</label>
                    <div class="stepper">
                        <button type="button" data-step="-1" aria-label="Kurangi jumlah bintang">
                            <x-icon name="minus" />
                        </button>
                        <input
                            id="builder-stars"
                            type="number"
                            inputmode="numeric"
                            min="1"
                            max="100"
                            value="{{ $defaultStars }}"
                            data-builder-stars
                            aria-describedby="builder-stars-help"
                        >
                        <button type="button" data-step="1" aria-label="Tambah jumlah bintang">
                            <x-icon name="plus" />
                        </button>
                    </div>
                    <p id="builder-stars-help" class="builder-help">Minimal 1 bintang, maksimal 100 bintang per pesanan.</p>
                </div>

                <div class="builder-total">
                    <span>Estimasi total</span>
                    <strong data-builder-total>
                        Rp {{ number_format(($defaultPackage?->price ?? 0) * $defaultStars, 0, ',', '.') }}
                    </strong>
                </div>

                <a
                    href="{{ $defaultPackage ? route('orders.create', $defaultPackage) . '?stars=' . $defaultStars : route('home') . '#pricelist' }}"
                    class="btn btn--primary btn--block"
                    data-builder-link
                >
                    Lanjutkan pesanan
                    <x-icon name="arrow-right" />
                </a>

                <p class="builder-note">
                    <x-icon name="info" />
                    Admin akan mengonfirmasi paket dan pembayaran lewat WhatsApp. Password akun tidak diminta di form ini.
                </p>
            </aside>
        </div>
    </div>
</section>
