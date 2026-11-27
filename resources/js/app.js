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
    const pauseButton = $('[data-hero-pause]', root);
    const progressBar = $('[data-hero-progress]', root);
    const counter = $('[data-hero-current]', root);
    const duration = Math.max(3000, Number(root.dataset.autoplay) || 5200);

    let index = Math.max(0, slides.findIndex((slide) => slide.classList.contains('is-active')));
    let elapsed = 0;
    let lastFrame = 0;
    let animationFrame = 0;
    let hovered = false;
    let userPaused = false;
    let started = false;

    const canAutoplay = () =>
        slides.length > 1 &&
        !userPaused &&
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

    if (pauseButton) {
        pauseButton.addEventListener('click', () => {
            userPaused = !userPaused;
            pauseButton.setAttribute('aria-pressed', String(userPaused));
            pauseButton.setAttribute(
                'aria-label',
                userPaused ? 'Lanjutkan tayangan otomatis' : 'Jeda tayangan otomatis'
            );
            root.classList.toggle('is-paused', userPaused);

            if (userPaused) {
                stop();
            } else {
                start();
            }
        });
    }

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
