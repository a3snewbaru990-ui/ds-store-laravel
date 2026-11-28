<section class="hero-section" aria-labelledby="page-title">
    <div class="container">
        <h1 id="page-title" class="sr-only">DS Store, jasa joki akun Mobile Legends Season 42</h1>

        <div class="hero-carousel" data-hero-carousel data-autoplay="5200" aria-roledescription="carousel" aria-label="Promo dan paket DS Store">
            <div class="hero-slides">
                @foreach ($slides as $slide)
                    <article
                        class="hero-slide {{ $loop->first ? 'is-active' : '' }}"
                        data-slide="{{ $loop->index }}"
                        aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                        aria-roledescription="slide"
                        aria-label="{{ $loop->iteration }} dari {{ $slides->count() }}"
                    >
                        <div class="hero-slide__media">
                            <img
                                src="{{ asset($slide->image) }}"
                                alt=""
                                width="960"
                                height="1152"
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                fetchpriority="{{ $loop->first ? 'high' : 'low' }}"
                            >
                        </div>
                        <div class="hero-slide__shade" aria-hidden="true"></div>

                        <div class="hero-slide__content">
                            <p class="hero-kicker">
                                <span class="hero-kicker__line" aria-hidden="true"></span>
                                {{ $slide->kicker }}
                            </p>
                            <h2 class="hero-title">{{ $slide->title }}</h2>
                            <p class="hero-description">{{ $slide->description }}</p>

                            @if ($slide->price_note)
                                <p class="hero-price">
                                    <x-icon name="spark" />
                                    {{ $slide->price_note }}
                                </p>
                            @endif

                            <div class="hero-actions">
                                @if ($slide->primary_label)
                                    <a href="{{ $slide->primary_url }}" class="btn btn--primary">
                                        {{ $slide->primary_label }}
                                        <x-icon name="arrow-right" />
                                    </a>
                                @endif
                                @if ($slide->secondary_label)
                                    <a
                                        href="{{ $slide->secondary_url }}"
                                        class="btn btn--ghost"
                                        @if (str_starts_with($slide->secondary_url, 'http')) target="_blank" rel="noopener" @endif
                                    >
                                        @if (str_contains($slide->secondary_url, 'wa.me'))
                                            <x-icon name="whatsapp" />
                                        @else
                                            <x-icon name="arrow-up-right" />
                                        @endif
                                        {{ $slide->secondary_label }}
                                    </a>
                                @endif
                            </div>

                            <div class="hero-assurance">
                                <span><x-icon name="shield-check" /> Data akun dijaga</span>
                                <span><x-icon name="eye" /> Progres transparan</span>
                                <span><x-icon name="clock" /> Admin 24 jam</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="hero-arrows">
                <button type="button" class="hero-arrow" data-hero-prev aria-label="Slide sebelumnya">
                    <x-icon name="arrow-left" />
                </button>
                <button type="button" class="hero-arrow" data-hero-next aria-label="Slide berikutnya">
                    <x-icon name="arrow-right" />
                </button>
            </div>

            <div class="hero-controls">
                <div class="hero-dots" role="group" aria-label="Pilih slide">
                    @foreach ($slides as $slide)
                        <button
                            type="button"
                            class="hero-dot {{ $loop->first ? 'is-active' : '' }}"
                            data-hero-dot="{{ $loop->index }}"
                            aria-label="Tampilkan slide {{ $loop->iteration }}: {{ $slide->title }}"
                            aria-current="{{ $loop->first ? 'true' : 'false' }}"
                        ></button>
                    @endforeach
                </div>

                <div class="hero-controls__right">
                    <p class="hero-counter"><span data-hero-current>01</span><span aria-hidden="true">/</span>{{ str_pad($slides->count(), 2, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="hero-progress" aria-hidden="true">
                <span class="hero-progress__bar" data-hero-progress></span>
            </div>
        </div>

        <div class="trust-strip" aria-label="Keunggulan layanan DS Store">
            <div class="trust-item">
                <span class="trust-item__icon"><x-icon name="shield-check" /></span>
                <span><strong>100% amanah</strong><small>Data akun dijaga</small></span>
            </div>
            <div class="trust-item">
                <span class="trust-item__icon"><x-icon name="bolt" /></span>
                <span><strong>Progres kilat</strong><small>Admin responsif</small></span>
            </div>
            <div class="trust-item">
                <span class="trust-item__icon"><x-icon name="eye" /></span>
                <span><strong>Status jelas</strong><small>Update di dashboard</small></span>
            </div>
            <div class="trust-item">
                <span class="trust-item__icon"><x-icon name="clock" /></span>
                <span><strong>Online 24 jam</strong><small>Siap bantu kapan saja</small></span>
            </div>
        </div>
    </div>
</section>
