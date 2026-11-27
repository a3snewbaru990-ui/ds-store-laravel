<section class="section section--faq" id="faq" aria-labelledby="faq-title">
    <div class="container">
        <div class="faq-layout">
            <div class="faq-intro">
                <p class="eyebrow">FAQ</p>
                <h2 id="faq-title">Yang sering ditanya sebelum order.</h2>
                <p class="section-lead">Kalau pertanyaanmu belum ada di sini, admin DS Store siap bantu lewat WhatsApp.</p>

                <a href="{{ config('site.whatsapp_url') }}" target="_blank" rel="noopener" class="faq-contact">
                    <span class="faq-contact__icon"><x-icon name="whatsapp" /></span>
                    <span>
                        <small>Butuh jawaban cepat?</small>
                        <strong>Chat admin sekarang</strong>
                    </span>
                    <x-icon name="arrow-up-right" class="faq-contact__arrow" />
                </a>
            </div>

            <div class="faq-list">
                @foreach ($faqs as $faq)
                    <details class="faq-item">
                        <summary>
                            <span>{{ $faq['question'] }}</span>
                            <span class="faq-item__toggle" aria-hidden="true">
                                <x-icon name="plus" class="faq-item__plus" />
                                <x-icon name="minus" class="faq-item__minus" />
                            </span>
                        </summary>
                        <p>{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>
