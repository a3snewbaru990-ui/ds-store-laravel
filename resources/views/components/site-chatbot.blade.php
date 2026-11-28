@php
    $whatsappUrl = config('site.whatsapp_url');
    $botPackages = \App\Models\ServicePackage::active()
        ->orderBy('sort_order')
        ->get(['name', 'price', 'unit'])
        ->map(fn ($package) => [
            'name' => $package->name,
            'price' => $package->price,
            'unit' => $package->unit,
        ])
        ->all();
@endphp

<div
    class="site-chatbot"
    data-chatbot
    data-whatsapp="{{ $whatsappUrl }}"
    data-packages="{{ json_encode($botPackages) }}"
    data-faqs="{{ json_encode(config('site.faqs')) }}"
>
    <button
        type="button"
        class="chatbot-toggle"
        data-chatbot-toggle
        aria-expanded="false"
        aria-controls="chatbot-panel"
        aria-label="Buka asisten DS Store"
    >
        <x-icon name="chat" />
        <span class="chatbot-toggle__label">Tanya aku</span>
    </button>

    <div class="chatbot-panel" id="chatbot-panel" data-chatbot-panel hidden>
        <div class="chatbot-panel__head">
            <div>
                <p class="chatbot-panel__title"><span class="chatbot-panel__dot" aria-hidden="true"></span>Asisten DS Store</p>
                <p class="chatbot-panel__sub">Biasanya jawab langsung</p>
            </div>
            <button type="button" class="chatbot-close" data-chatbot-close aria-label="Tutup asisten">
                <x-icon name="close" />
            </button>
        </div>

        <div class="chatbot-messages" data-chatbot-messages aria-live="polite" aria-atomic="false">
            <p class="chatbot-message chatbot-message--bot">
                Halo! Aku bisa bantu soal harga, cara order, dan lacak pesanan. Mau tanya apa?
            </p>
        </div>

        <div class="chatbot-quick" data-chatbot-quick aria-label="Pertanyaan cepat">
            <button type="button" class="chatbot-quick__featured" data-chatbot-ask="harga">Harga berapa?</button>
            <button type="button" data-chatbot-ask="cara">Order gimana?</button>
            <button type="button" data-chatbot-ask="lacak">Lacak order</button>
            <button type="button" class="chatbot-quick__link" data-chatbot-ask="admin">Ingin chat admin</button>
        </div>

        <form class="chatbot-form" data-chatbot-form>
            <label class="sr-only" for="chatbot-input">Tulis pertanyaan</label>
            <input
                id="chatbot-input"
                type="text"
                name="message"
                placeholder="Tulis pertanyaanmu…"
                autocomplete="off"
                maxlength="160"
                required
            >
            <button type="submit" aria-label="Kirim pertanyaan">
                <x-icon name="arrow-right" />
            </button>
        </form>

        <a class="chatbot-admin" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
            Butuh jawaban dari admin? Buka WhatsApp
        </a>
    </div>
</div>
