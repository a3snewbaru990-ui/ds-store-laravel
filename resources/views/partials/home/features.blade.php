@php
    $rankStrip = $starPackages->take(6);
@endphp

<section class="section section--features" id="keunggulan" aria-labelledby="features-title">
    <div class="container">
        <div class="feature-layout">
            <article class="feature-lead">
                <div class="feature-lead__head">
                    <p class="eyebrow">Standar layanan</p>
                    <h2 id="features-title">Amanah itu standar, bukan bonus.</h2>
                    <p>DS Store menjaga alur kerja yang jelas: dari konfirmasi paket, pengerjaan, sampai update status pesanan. Kamu tidak perlu menebak-nebak progres.</p>
                </div>

                <div class="feature-lead__stamp" aria-hidden="true">
                    <img src="{{ asset('images/logo-mark.png') }}" alt="" width="180" height="155" loading="lazy">
                </div>

                <ul class="feature-checklist">
                    <li><x-icon name="check" /> Data akun hanya diurus lewat admin</li>
                    <li><x-icon name="check" /> Riwayat pesanan tersimpan di akun</li>
                    <li><x-icon name="check" /> Admin menjelaskan estimasi sebelum mulai</li>
                </ul>

                <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener" class="text-link">
                    Tanya standar layanan
                    <x-icon name="arrow-up-right" />
                </a>
            </article>

            <div class="feature-stack">
                <article class="feature-card">
                    <span class="feature-card__icon"><x-icon name="shield-check" /></span>
                    <div>
                        <h3>Data akun dijaga</h3>
                        <p>Form order tidak meminta password. Detail akun dibahas langsung dengan admin lewat WhatsApp.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="feature-card__icon feature-card__icon--gold"><x-icon name="eye" /></span>
                    <div>
                        <h3>Progres bisa dicek</h3>
                        <p>Setiap perubahan status muncul di dashboard, dari konfirmasi sampai pesanan selesai.</p>
                    </div>
                </article>
                <article class="feature-card">
                    <span class="feature-card__icon"><x-icon name="clock" /></span>
                    <div>
                        <h3>Admin online 24 jam</h3>
                        <p>Ada pertanyaan soal paket atau rank? Admin bisa dihubungi lewat WhatsApp kapan saja.</p>
                    </div>
                </article>
            </div>
        </div>

        <div class="rank-strip" aria-labelledby="rank-strip-title">
            <div class="rank-strip__head">
                <p class="eyebrow">Rank yang dilayani</p>
                <h3 id="rank-strip-title">Dari Grandmaster sampai Mythic Glory.</h3>
            </div>
            <ol class="rank-line">
                @foreach ($rankStrip as $package)
                    <li class="rank-line__item">
                        <span class="rank-line__emblem">
                            <img src="{{ $package->emblem_url }}" alt="" width="56" height="56" loading="lazy">
                        </span>
                        <span class="rank-line__name">{{ $package->short_name }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
