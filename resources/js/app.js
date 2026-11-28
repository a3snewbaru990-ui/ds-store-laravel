/* ==========================================================================
   DS Store — interface behaviour
   ========================================================================== */

const $ = (selector, context = document) => context.querySelector(selector);
const $$ = (selector, context = document) => Array.from(context.querySelectorAll(selector));
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

const formatRupiah = (value) => {
    const amount = Math.max(0, Math.round(Number(value) || 0));

    return `Rp ${amount.toLocaleString('id-ID')}`;
};

const clampNumber = (value, min, max, fallback = min) => {
    const parsed = Number.parseInt(value, 10);

    if (!Number.isFinite(parsed)) {
        return fallback;
    }

    return Math.min(max, Math.max(min, parsed));
};

/* --------------------------------------------------------------------------
   Header state
   -------------------------------------------------------------------------- */

const siteHeader = $('[data-site-header]');

if (siteHeader) {
    const syncHeader = () => {
        siteHeader.classList.toggle('is-scrolled', window.scrollY > 24);
    };

    syncHeader();
    window.addEventListener('scroll', syncHeader, { passive: true });
}

/* --------------------------------------------------------------------------
   Mobile navigation
   -------------------------------------------------------------------------- */

const navToggle = $('[data-nav-toggle]');
const primaryNav = navToggle ? document.getElementById(navToggle.getAttribute('aria-controls')) : null;

if (navToggle && primaryNav) {
    const navLabel = $('.sr-only', navToggle);

    const setNavOpen = (open) => {
        primaryNav.classList.toggle('is-open', open);
        navToggle.setAttribute('aria-expanded', String(open));
        document.body.classList.toggle('nav-open', open);

        if (navLabel) {
            navLabel.textContent = open ? 'Tutup navigasi' : 'Buka navigasi';
        }
    };

    navToggle.addEventListener('click', () => {
        setNavOpen(!primaryNav.classList.contains('is-open'));
    });

    $$('a', primaryNav).forEach((link) => {
        link.addEventListener('click', () => setNavOpen(false));
    });

    document.addEventListener('click', (event) => {
        if (!primaryNav.classList.contains('is-open')) {
            return;
        }

        if (!primaryNav.contains(event.target) && !navToggle.contains(event.target)) {
            setNavOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && primaryNav.classList.contains('is-open')) {
            setNavOpen(false);
            navToggle.focus();
        }
    });

    const desktopNav = window.matchMedia('(min-width: 1025px)');
    desktopNav.addEventListener?.('change', (event) => {
        if (event.matches) {
            setNavOpen(false);
        }
    });
}

/* --------------------------------------------------------------------------
   Hero carousel
   -------------------------------------------------------------------------- */

const initHeroCarousel = (root) => {
    const slides = $$('.hero-slide', root);

    if (slides.length === 0) {
        return;
    }

    const dots = $$('[data-hero-dot]', root);
    const prevButton = $('[data-hero-prev]', root);
    const nextButton = $('[data-hero-next]', root);
    const progressBar = $('[data-hero-progress]', root);
    const counter = $('[data-hero-current]', root);
    const duration = Math.max(3000, Number(root.dataset.autoplay) || 5200);

    let index = Math.max(0, slides.findIndex((slide) => slide.classList.contains('is-active')));
    let elapsed = 0;
    let lastFrame = 0;
    let animationFrame = 0;
    let hovered = false;
    let started = false;

    const canAutoplay = () =>
        slides.length > 1 &&
        !hovered &&
        !document.hidden &&
        !reduceMotion.matches;

    const paintProgress = () => {
        if (!progressBar) {
            return;
        }

        const ratio = Math.min(elapsed / duration, 1);
        progressBar.style.transform = `scaleX(${ratio})`;
    };

    const stop = () => {
        if (animationFrame) {
            window.cancelAnimationFrame(animationFrame);
        }

        animationFrame = 0;
        lastFrame = 0;
    };

    const tick = (timestamp) => {
        if (!lastFrame) {
            lastFrame = timestamp;
        }

        elapsed += timestamp - lastFrame;
        lastFrame = timestamp;
        paintProgress();

        if (elapsed >= duration) {
            elapsed = 0;
            show(index + 1, 1);
            return;
        }

        animationFrame = window.requestAnimationFrame(tick);
    };

    const start = () => {
        stop();

        if (!canAutoplay()) {
            return;
        }

        animationFrame = window.requestAnimationFrame(tick);
    };

    const show = (nextIndex, direction = 1) => {
        index = ((nextIndex % slides.length) + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            const active = slideIndex === index;

            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', active ? 'false' : 'true');

            if (active) {
                slide.removeAttribute('inert');
            } else {
                slide.setAttribute('inert', '');
            }
        });

        dots.forEach((dot, dotIndex) => {
            const active = dotIndex === index;
            dot.classList.toggle('is-active', active);
            dot.setAttribute('aria-current', active ? 'true' : 'false');
        });

        if (counter) {
            counter.textContent = String(index + 1).padStart(2, '0');
        }

        root.dataset.direction = direction > 0 ? 'next' : 'prev';
        elapsed = 0;
        paintProgress();

        if (started) {
            start();
        }
    };

    const go = (step) => {
        show(index + step, step);
    };

    show(index, 1);
    started = true;
    start();

    prevButton?.addEventListener('click', () => go(-1));
    nextButton?.addEventListener('click', () => go(1));

    dots.forEach((dot, dotIndex) => {
        dot.addEventListener('click', () => {
            show(dotIndex, dotIndex >= index ? 1 : -1);
        });
    });

    root.addEventListener('pointerenter', (event) => {
        if (event.pointerType === 'mouse') {
            hovered = true;
            stop();
        }
    });

    root.addEventListener('pointerleave', (event) => {
        if (event.pointerType === 'mouse') {
            hovered = false;
            start();
        }
    });

    root.addEventListener('keydown', (event) => {
        const tagName = event.target.tagName;

        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(tagName)) {
            return;
        }

        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            go(-1);
        }

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            go(1);
        }
    });

    let touchStartX = 0;
    let touchStartY = 0;
    let touching = false;

    root.addEventListener('pointerdown', (event) => {
        if (event.pointerType === 'mouse' && event.button !== 0) {
            return;
        }

        if (event.target.closest('a, button, input, textarea, select, summary')) {
            return;
        }

        touching = true;
        touchStartX = event.clientX;
        touchStartY = event.clientY;
    });

    root.addEventListener('pointerup', (event) => {
        if (!touching) {
            return;
        }

        touching = false;
        const deltaX = event.clientX - touchStartX;
        const deltaY = event.clientY - touchStartY;

        if (Math.abs(deltaX) > 50 && Math.abs(deltaX) > Math.abs(deltaY) * 1.4) {
            go(deltaX < 0 ? 1 : -1);
        }
    });

    root.addEventListener('pointercancel', () => {
        touching = false;
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stop();
        } else {
            start();
        }
    });

    reduceMotion.addEventListener?.('change', () => {
        if (reduceMotion.matches) {
            stop();
        } else {
            start();
        }
    });
};

$$('[data-hero-carousel]').forEach(initHeroCarousel);

/* --------------------------------------------------------------------------
   Pricelist rank selector + order builder
   -------------------------------------------------------------------------- */

const rankSelector = $('[data-rank-selector]');
const orderBuilder = $('[data-order-builder]');

if (rankSelector && orderBuilder) {
    const inputs = $$('[data-rank-input]', rankSelector);
    const starsInput = $('[data-builder-stars]', orderBuilder);
    const totalOutput = $('[data-builder-total]', orderBuilder);
    const nameOutput = $('[data-builder-name]', orderBuilder);
    const priceOutput = $('[data-builder-price]', orderBuilder);
    const emblemOutput = $('[data-builder-emblem]', orderBuilder);
    const builderLink = $('[data-builder-link]', orderBuilder);

    let selected = inputs.find((input) => input.checked) || inputs[0] || null;

    const initialHref = builderLink?.getAttribute('href') || '';
    const orderMarker = initialHref.indexOf('/order/');
    const orderBase = orderMarker >= 0 ? `${initialHref.slice(0, orderMarker)}/order/` : '/order/';

    const updateBuilder = () => {
        if (!selected) {
            return;
        }

        const price = Number(selected.dataset.price) || 0;
        const stars = starsInput ? clampNumber(starsInput.value, 1, 100) : 10;

        if (starsInput) {
            starsInput.value = String(stars);
        }

        if (nameOutput) {
            nameOutput.textContent = selected.dataset.name || 'Paket joki';
        }

        if (priceOutput) {
            priceOutput.textContent = `${formatRupiah(price)} / bintang`;
        }

        if (emblemOutput && selected.dataset.emblem) {
            emblemOutput.src = selected.dataset.emblem;
        }

        if (totalOutput) {
            totalOutput.textContent = formatRupiah(price * stars);
        }

        if (builderLink) {
            builderLink.href = selected.dataset.slug
                ? `${orderBase}${encodeURIComponent(selected.dataset.slug)}?stars=${stars}`
                : `${orderBase}#pricelist`;
        }
    };

    inputs.forEach((input) => {
        input.addEventListener('change', () => {
            if (!input.checked) {
                return;
            }

            selected = input;
            updateBuilder();
        });
    });

    starsInput?.addEventListener('input', updateBuilder);
    starsInput?.addEventListener('change', updateBuilder);

    $$('[data-step]', orderBuilder).forEach((button) => {
        button.addEventListener('click', () => {
            if (!starsInput) {
                return;
            }

            const step = Number(button.dataset.step) || 0;
            starsInput.value = String(clampNumber(starsInput.value, 1, 100) + step);
            updateBuilder();
        });
    });

    builderLink?.addEventListener('click', () => {
        if (starsInput) {
            starsInput.value = String(clampNumber(starsInput.value, 1, 100));
        }
    });

    updateBuilder();
}

/* --------------------------------------------------------------------------
   Order form summary
   -------------------------------------------------------------------------- */

const orderForm = $('[data-order-form]');

if (orderForm) {
    const unitPrice = Number(orderForm.dataset.unitPrice) || 0;
    const isStarPackage = orderForm.dataset.isStarPackage === '1';
    const starsInput = $('[data-order-stars]', orderForm);
    const summaryStars = $('[data-summary-stars]', orderForm);
    const summaryTotal = $('[data-summary-total]', orderForm);

    const updateOrderSummary = () => {
        const stars = isStarPackage
            ? clampNumber(starsInput?.value ?? 1, 1, 100)
            : 10;

        if (isStarPackage && starsInput) {
            starsInput.value = String(stars);
        }

        if (summaryStars) {
            summaryStars.textContent = `${stars} ${isStarPackage ? 'bintang' : 'match'}`;
        }

        if (summaryTotal) {
            summaryTotal.textContent = formatRupiah(isStarPackage ? unitPrice * stars : unitPrice);
        }
    };

    starsInput?.addEventListener('input', updateOrderSummary);
    starsInput?.addEventListener('change', updateOrderSummary);

    $$('[data-order-step]', orderForm).forEach((button) => {
        button.addEventListener('click', () => {
            if (!starsInput) {
                return;
            }

            const step = Number(button.dataset.orderStep) || 0;
            starsInput.value = String(clampNumber(starsInput.value, 1, 100) + step);
            updateOrderSummary();
        });
    });

    updateOrderSummary();

    orderForm.addEventListener('submit', () => {
        if (isStarPackage && starsInput) {
            starsInput.value = String(clampNumber(starsInput.value, 1, 100));
        }

        const submitButton = orderForm.querySelector('button[type="submit"]');

        if (submitButton && !submitButton.disabled) {
            submitButton.disabled = true;
            submitButton.textContent = 'Memproses pesanan...';
        }
    });
}

/* --------------------------------------------------------------------------
   Password visibility
   -------------------------------------------------------------------------- */

$$('[data-password-toggle]').forEach((button) => {
    const field = button.closest('.password-field');
    const input = field ? $('input', field) : null;

    if (!input) {
        return;
    }

    button.addEventListener('click', () => {
        const reveal = input.type === 'password';

        input.type = reveal ? 'text' : 'password';
        button.classList.toggle('is-visible', reveal);
        button.setAttribute('aria-label', reveal ? 'Sembunyikan password' : 'Tampilkan password');
        input.focus({ preventScroll: true });
    });
});

/* --------------------------------------------------------------------------
   Flash alerts
   -------------------------------------------------------------------------- */

$$('[data-dismiss-alert]').forEach((button) => {
    button.addEventListener('click', () => {
        const alert = button.closest('.alert');

        if (!alert) {
            return;
        }

        alert.classList.add('is-dismissing');
        window.setTimeout(() => alert.remove(), 220);
    });
});

/* --------------------------------------------------------------------------
   Workspace drawer
   -------------------------------------------------------------------------- */

const workspaceOpen = $('[data-workspace-open]');
const workspaceSidebar = $('#workspace-sidebar');
const workspaceScrim = $('[data-workspace-scrim]');
const workspaceClose = $('[data-workspace-close]');

if (workspaceOpen && workspaceSidebar && workspaceScrim) {
    const drawerIsOpen = () => workspaceSidebar.classList.contains('is-open');

    const openDrawer = () => {
        workspaceSidebar.classList.add('is-open');
        workspaceScrim.hidden = false;
        document.body.classList.add('drawer-open');
        workspaceOpen.setAttribute('aria-expanded', 'true');
        workspaceClose?.focus({ preventScroll: true });
    };

    const closeDrawer = (restoreFocus = true) => {
        workspaceSidebar.classList.remove('is-open');
        workspaceScrim.hidden = true;
        document.body.classList.remove('drawer-open');
        workspaceOpen.setAttribute('aria-expanded', 'false');

        if (restoreFocus) {
            workspaceOpen.focus({ preventScroll: true });
        }
    };

    workspaceOpen.addEventListener('click', () => {
        if (drawerIsOpen()) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    workspaceClose?.addEventListener('click', () => closeDrawer());
    workspaceScrim.addEventListener('click', () => closeDrawer());

    $$('a', workspaceSidebar).forEach((link) => {
        link.addEventListener('click', () => closeDrawer(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && drawerIsOpen()) {
            closeDrawer();
        }
    });

    workspaceSidebar.addEventListener('keydown', (event) => {
        if (event.key !== 'Tab' || !drawerIsOpen()) {
            return;
        }

        const focusable = $$(
            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled])',
            workspaceSidebar
        ).filter((element) => element.offsetParent !== null);

        if (focusable.length === 0) {
            return;
        }

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    const desktopWorkspace = window.matchMedia('(min-width: 1025px)');
    desktopWorkspace.addEventListener?.('change', (event) => {
        if (event.matches && drawerIsOpen()) {
            closeDrawer(false);
        }
    });
}

/* --------------------------------------------------------------------------
   Package delete confirmation
   -------------------------------------------------------------------------- */

const confirmDialog = $('[data-confirm-dialog]');

if (confirmDialog && typeof confirmDialog.showModal === 'function') {
    const confirmForm = $('[data-confirm-form]', confirmDialog);
    const confirmMessage = $('[data-confirm-message]', confirmDialog);
    const confirmCancel = $('[data-confirm-cancel]', confirmDialog);

    $$('[data-delete-package]').forEach((button) => {
        button.addEventListener('click', () => {
            if (confirmForm) {
                confirmForm.action = button.dataset.action || '';
            }

            if (confirmMessage) {
                confirmMessage.textContent = `Paket "${button.dataset.name || 'ini'}" akan dihapus dari pricelist.`;
            }

            confirmDialog.showModal();
        });
    });

    confirmCancel?.addEventListener('click', () => confirmDialog.close());

    confirmDialog.addEventListener('click', (event) => {
        if (event.target === confirmDialog) {
            confirmDialog.close();
        }
    });
}

/* --------------------------------------------------------------------------
   Admin package rank preview
   -------------------------------------------------------------------------- */

const rankSelect = $('[data-rank-select]');
const rankPreview = $('[data-rank-preview]');

if (rankSelect && rankPreview) {
    const syncRankPreview = () => {
        const option = rankSelect.selectedOptions[0];

        if (option?.dataset.image) {
            rankPreview.src = option.dataset.image;
        }
    };

    rankSelect.addEventListener('change', syncRankPreview);
    syncRankPreview();
}

/* --------------------------------------------------------------------------
   FAQ accordion — keep one answer open at a time
   -------------------------------------------------------------------------- */

$$('.faq-item').forEach((item) => {
    item.addEventListener('toggle', () => {
        if (!item.open) {
            return;
        }

        $$('.faq-item').forEach((other) => {
            if (other !== item) {
                other.open = false;
            }
        });
    });
});

/* --------------------------------------------------------------------------
   Site chatbot — small rule-based helper for prices and order flow
   -------------------------------------------------------------------------- */

const chatbot = $('[data-chatbot]');

if (chatbot) {
    const toggle = $('[data-chatbot-toggle]', chatbot);
    const panel = $('[data-chatbot-panel]', chatbot);
    const closeButton = $('[data-chatbot-close]', chatbot);
    const messages = $('[data-chatbot-messages]', chatbot);
    const form = $('[data-chatbot-form]', chatbot);
    const input = $('#chatbot-input', chatbot);
    const whatsappUrl = chatbot.dataset.whatsapp || '/lacak';

    let packages = [];
    let faqs = [];

    try {
        packages = JSON.parse(chatbot.dataset.packages || '[]');
    } catch (_) {
        packages = [];
    }

    try {
        faqs = JSON.parse(chatbot.dataset.faqs || '[]');
    } catch (_) {
        faqs = [];
    }

    const state = {
        rank: null,
        intent: null,
        unknown: 0,
        lastReply: '',
    };

    const normalize = (value) =>
        String(value || '')
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();

    const aliases = {
        gm: 'grandmaster',
        epic: 'epic',
        legend: 'legend',
        myt: 'mythic',
        myth: 'mythic',
        mythic: 'mythic',
        honor: 'mythic honor',
        'mythic honor': 'mythic honor',
        glory: 'mythic glory',
        'mythic glory': 'mythic glory',
        immortal: 'mythic immortal',
        'mythic immortal': 'mythic immortal',
    };

    const findRank = (text) => {
        const normalized = normalize(text);

        const ordered = [...packages].sort(
            (a, b) => normalize(b.name).length - normalize(a.name).length
        );

        for (const item of ordered) {
            const name = normalize(item.name);
            if (name && normalized.includes(name)) {
                return item;
            }
        }

        for (const [alias, name] of Object.entries(aliases)) {
            if (normalized.split(' ').includes(alias)) {
                return packages.find((item) => normalize(item.name) === name) || null;
            }
        }

        return null;
    };

    const findStars = (text) => {
        const normalized = normalize(text);
        const withUnit = normalized.match(/(\d{1,3})\s*(bintang|star)/);
        if (withUnit) {
            return clampNumber(withUnit[1], 1, 100);
        }

        if (/^\d{1,3}$/.test(normalized)) {
            return clampNumber(normalized, 1, 100);
        }

        const tokens = normalized.split(' ');
        const last = tokens[tokens.length - 1];
        if (/^\d{1,3}$/.test(last)) {
            return clampNumber(last, 1, 100);
        }

        const mention = normalized.match(/(\d{1,3})\s*(?:bintang|star)\b/);
        return mention ? clampNumber(mention[1], 1, 100) : null;
    };

    const priceFor = (item, stars) => {
        const price = Number(item?.price) || 0;
        return item?.unit === 'package' ? price : price * (stars || 1);
    };

    const allPrices = () =>
        packages
            .map((item) => `${item.name}: ${formatRupiah(item.price)}${item.unit === 'package' ? ' / paket' : ' / bintang'}`)
            .join('\n');

    const priceAnswer = (text) => {
        const rank = findRank(text) || state.rank;
        const stars = findStars(text);

        if (rank && stars) {
            state.rank = rank;
            return {
                text: `${rank.name} ${stars} bintang = ${formatRupiah(priceFor(rank, stars))}.`,
                suggestion: { label: 'Lanjut order', href: `/order/${rank.name.toLowerCase().replace(/\s+/g, '-')}` },
                intent: 'price',
            };
        }

        if (rank) {
            state.rank = rank;
            return {
                text: rank.unit === 'package'
                    ? `${rank.name} = ${formatRupiah(rank.price)} untuk 10 match.`
                    : `${rank.name} ${formatRupiah(rank.price)} per bintang. Mau berapa bintang?`,
                suggestion: { label: 'Lihat semua harga', href: '/#pricelist' },
                intent: 'price',
            };
        }

        if (stars) {
            const fallback = packages[0] || { name: 'Grandmaster', price: 5000, unit: 'star' };
            return {
                text: `${stars} bintang di ${fallback.name} = ${formatRupiah(priceFor(fallback, stars))}. Sebut rank lain kalau mau ganti.`,
                suggestion: { label: 'Pilih rank dulu', href: '/#pricelist' },
                intent: 'price',
            };
        }

        return {
            text: `Ini tarif sekarang:\n${allPrices() || 'Paket mulai Rp 5.000 per bintang.'}`,
            suggestion: { label: 'Buka pricelist', href: '/#pricelist' },
            intent: 'price',
        };
    };

    const faqAnswer = (text, index) => {
        const faq = faqs[index];
        if (!faq) {
            return null;
        }

        return {
            text: faq.answer,
            suggestion: { label: 'Tanya admin kalau perlu', href: whatsappUrl },
            intent: 'faq',
        };
    };

    const answerFor = (raw) => {
        const text = normalize(raw);

        if (/^(halo|hai|hi|p|permisi|selamat pagi|selamat siang|selamat sore|selamat malam)$/.test(text)) {
            return {
                text: 'Halo! Mau tanya harga, cara order, atau lacak pesanan?',
                suggestion: null,
                intent: 'greeting',
            };
        }

        if (/makasih|terima kasih|thanks|sip|oke|okey/.test(text)) {
            return {
                text: 'Sama-sama. Kalau ada yang lain, tanya saja ya.',
                suggestion: { label: 'Chat admin', href: whatsappUrl },
                intent: 'thanks',
            };
        }

        if (/admin|manusia|cs|customer service|whatsapp|wa|hubungi/.test(text)) {
            return {
                text: 'Aku sambungkan ke admin supaya jawabannya lebih lengkap.',
                suggestion: { label: 'Buka WhatsApp admin', href: whatsappUrl },
                intent: 'admin',
            };
        }

        if (/lacak|resi|kode order|nomor pesanan|status pesanan|progres/.test(text)) {
            return {
                text: 'Masukkan kode pesanan dan nomor WhatsApp yang dipakai saat order di halaman lacak.',
                suggestion: { label: 'Buka pelacakan', href: '/lacak' },
                intent: 'track',
            };
        }

        if (/bayar|pembayaran|qris|transfer|e wallet|ewallet|lunas/.test(text)) {
            const faq = faqAnswer(text, 3);
            if (faq) {
                return faq;
            }

            return {
                text: 'Pembayaran dikonfirmasi admin lewat WhatsApp setelah pesanan dibuat.',
                suggestion: { label: 'Chat admin', href: whatsappUrl },
                intent: 'payment',
            };
        }

        if (/aman|password|data akun|bocor|amanah/.test(text)) {
            const faq = faqAnswer(text, 0);
            if (faq) {
                return faq;
            }

            return {
                text: 'Password game tidak disimpan di website. Detail akun dibahas langsung dengan admin.',
                suggestion: null,
                intent: 'safety',
            };
        }

        if (/lama|durasi|berapa hari|kapan selesai|estimasi waktu|cepat/.test(text)) {
            const faq = faqAnswer(text, 1);
            if (faq) {
                return faq;
            }

            return {
                text: 'Estimasi tergantung jumlah bintang dan antrean. Admin akan kasih perkiraan setelah cek akun.',
                suggestion: { label: 'Tanya admin', href: whatsappUrl },
                intent: 'duration',
            };
        }

        if (/hero|role|request|milih hero/.test(text)) {
            const faq = faqAnswer(text, 2);
            return faq || {
                text: 'Bisa request hero atau role. Tulis di catatan saat mengisi form order.',
                suggestion: { label: 'Buat pesanan', href: '/#pricelist' },
                intent: 'hero',
            };
        }

        if (/daftar|register|akun baru|buat akun|login|masuk|sign in/.test(text)) {
            return {
                text: 'Pelanggan bisa daftar lewat halaman register. Setelah itu langsung bisa buat pesanan.',
                suggestion: { label: 'Buka halaman daftar', href: '/register' },
                intent: 'account',
            };
        }

        if (/order|pesan|beli|cara|gimana|how|langkah|proses/.test(text)) {
            return {
                text: 'Pilih paket di pricelist, tentukan bintang, isi data akun, lalu admin konfirmasi lewat WhatsApp.',
                suggestion: { label: 'Lihat cara order', href: '/#cara-order' },
                intent: 'order',
            };
        }

        if (/harga|price|biaya|tarif|rp|bintang|berapa|price list|pricelist/.test(text)) {
            return priceAnswer(text);
        }

        const rank = findRank(text);
        if (rank) {
            return priceAnswer(text);
        }

        const stars = findStars(text);
        if (stars) {
            return priceAnswer(String(stars));
        }

        state.unknown += 1;

        if (state.unknown >= 2) {
            return {
                text: 'Aku belum menemukan jawaban yang pas. Supaya tidak muter, aku sambungkan ke admin ya.',
                suggestion: { label: 'Chat admin sekarang', href: whatsappUrl },
                intent: 'admin',
            };
        }

        return {
            text: 'Belum aku tangkap maksudnya. Pilih salah satu ya: harga, cara order, lacak pesanan, atau pembayaran.',
            suggestion: null,
            intent: 'clarify',
        };
    };

    const appendMessage = (text, sender = 'bot', suggestion = null) => {
        if (!messages) {
            return;
        }

        const bubble = document.createElement('p');
        bubble.className = `chatbot-message chatbot-message--${sender}`;
        bubble.textContent = text;
        messages.appendChild(bubble);

        if (suggestion) {
            const link = document.createElement('a');
            link.className = 'chatbot-suggestion';
            link.href = suggestion.href;
            link.textContent = suggestion.label;
            bubble.appendChild(document.createElement('br'));
            bubble.appendChild(link);
        }

        messages.scrollTop = messages.scrollHeight;
    };

    const ask = (question, displayText = question) => {
        appendMessage(displayText, 'user');

        const answer = answerFor(question);
        const text = answer.text === state.lastReply && state.intent === answer.intent
            ? `${answer.text}\n\nAda lagi yang bisa dibantu?`
            : answer.text;

        appendMessage(text, 'bot', answer.suggestion);
        state.lastReply = text;
        state.intent = answer.intent;

        if (answer.intent !== 'clarify') {
            state.unknown = 0;
        }
    };

    const setOpen = (open) => {
        if (!panel || !toggle) {
            return;
        }

        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Tutup asisten DS Store' : 'Buka asisten DS Store');

        if (open) {
            input?.focus({ preventScroll: true });
        } else {
            toggle.focus({ preventScroll: true });
        }
    };

    toggle?.addEventListener('click', () => setOpen(panel?.hidden ?? true));
    closeButton?.addEventListener('click', () => setOpen(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && panel && !panel.hidden) {
            setOpen(false);
        }
    });

    document.addEventListener('click', (event) => {
        if (panel && !panel.hidden && !chatbot.contains(event.target)) {
            setOpen(false);
        }
    });

    $$('[data-chatbot-ask]', chatbot).forEach((button) => {
        button.addEventListener('click', () => {
            const question = button.dataset.chatbotAsk || '';
            const labels = {
                harga: 'Harga berapa?',
                cara: 'Order gimana?',
                lacak: 'Lacak order',
                admin: 'Ingin chat admin',
            };

            ask(question, labels[question] || question);
        });
    });

    form?.addEventListener('submit', (event) => {
        event.preventDefault();

        const value = input?.value.trim();
        if (!value) {
            return;
        }

        ask(value);
        if (input) {
            input.value = '';
        }
    });
}

/* --------------------------------------------------------------------------
   Gentle scroll reveal
   -------------------------------------------------------------------------- */

const revealTargets = $$(
    '.section-heading, .rank-strip, .grading-feature, .cta-band, .process-step, .feature-card, .stat-card, .workspace-section, .order-overview, .detail-panel, .summary-card'
);

if (revealTargets.length > 0 && 'IntersectionObserver' in window && !reduceMotion.matches) {
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -36px 0px' }
    );

    revealTargets.forEach((element, index) => {
        element.classList.add('reveal');
        element.style.setProperty('--reveal-delay', `${(index % 4) * 55}ms`);
        revealObserver.observe(element);
    });
}
