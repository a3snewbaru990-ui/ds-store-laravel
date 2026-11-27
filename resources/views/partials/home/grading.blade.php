@if ($featuredPackage)
    <section class="section section--grading" aria-labelledby="grading-title">
        <div class="container">
            <div class="grading-feature">
                <div class="grading-feature__media">
                    <img src="{{ asset('images/hero-art-3.webp') }}" alt="" width="960" height="1152" loading="lazy">
                </div>
                <div class="grading-feature__shade" aria-hidden="true"></div>

                <div class="grading-feature__content">
                    <p class="eyebrow eyebrow--gold">Paket unggulan · 10 match</p>
                    <h2 id="grading-title">Full grading, tanpa mikirin draft.</h2>
                    <p class="grading-feature__lead">Baru masuk Mythic di awal season? Paket Full Grading DS Store membantu menyelesaikan 10 match grading dengan ritme yang terarah.</p>

                    <ul class="grading-points">
                        <li><x-icon name="check" /> 10 match grading dalam satu paket</li>
                        <li><x-icon name="check" /> Cocok untuk akun yang baru masuk season</li>
                        <li><x-icon name="check" /> Status pengerjaan tercatat di dashboard</li>
                    </ul>

                    <div class="grading-feature__bottom">
                        <div class="grading-feature__price">
                            <span>Harga paket</span>
                            <strong>{{ $featuredPackage->price_label }}</strong>
                        </div>
                        <a href="{{ route('orders.create', $featuredPackage) }}" class="btn btn--gold">
                            Ambil full grading
                            <x-icon name="arrow-right" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
