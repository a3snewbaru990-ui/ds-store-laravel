@php
    $steps = [
        [
            'icon' => 'target',
            'title' => 'Pilih paket dan bintang',
            'description' => 'Tentukan rank yang ingin dinaikkan, lalu hitung jumlah bintang di card selector.',
        ],
        [
            'icon' => 'gamepad',
            'title' => 'Isi data akun',
            'description' => 'Masukkan nama in-game, User ID, Server ID, dan nomor WhatsApp yang aktif.',
        ],
        [
            'icon' => 'message',
            'title' => 'Konfirmasi admin',
            'description' => 'Admin mengecek data, memberi estimasi, dan mengirim detail pembayaran lewat WhatsApp.',
        ],
        [
            'icon' => 'eye',
            'title' => 'Pantau progres',
            'description' => 'Buka dashboard untuk melihat status terbaru sampai pesanan selesai.',
        ],
    ];
@endphp

<section class="section section--process" id="cara-order" aria-labelledby="process-title">
    <div class="container">
        <div class="section-heading section-heading--split">
            <div>
                <p class="eyebrow">Cara order</p>
                <h2 id="process-title">Empat langkah, tanpa muter-muter.</h2>
                <p class="section-lead">Alur pesanan dibuat pendek supaya kamu cepat mulai dan tetap tahu apa yang sedang terjadi.</p>
            </div>
            <a href="{{ route('home') }}#pricelist" class="btn btn--ghost">
                Mulai dari pricelist
                <x-icon name="arrow-right" />
            </a>
        </div>

        <ol class="process-list">
            @foreach ($steps as $step)
                <li class="process-step">
                    <span class="process-step__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="process-step__icon"><x-icon :name="$step['icon']" /></span>
                    <h3>{{ $step['title'] }}</h3>
                    <p>{{ $step['description'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="process-note">
            <span class="process-note__icon"><x-icon name="lock" /></span>
            <p><strong>Jangan kirim password di form order.</strong> Admin akan mengarahkan langkah pengamanan akun lewat WhatsApp setelah pesanan dikonfirmasi.</p>
        </div>
    </div>
</section>
