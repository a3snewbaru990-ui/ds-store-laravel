<section class="section section--cta" aria-labelledby="cta-title">
    <div class="container">
        <div class="cta-band">
            <div class="cta-band__glow" aria-hidden="true"></div>
            <img src="{{ asset('images/logo-mark.png') }}" alt="" width="150" height="129" class="cta-band__logo" loading="lazy">
            <div class="cta-band__content">
                <p class="eyebrow">Season 42 · Slot terbatas sesuai antrean</p>
                <h2 id="cta-title">Siap push rank bareng DS Store?</h2>
                <p>Pilih paketmu, isi data, dan biarkan admin mengurus langkah berikutnya.</p>
            </div>
            <div class="cta-band__actions">
                <a href="{{ route('home') }}#pricelist" class="btn btn--primary">
                    Lihat pricelist
                    <x-icon name="arrow-right" />
                </a>
                <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener" class="btn btn--ghost">
                    <x-icon name="whatsapp" />
                    Chat admin
                </a>
            </div>
        </div>
    </div>
</section>
