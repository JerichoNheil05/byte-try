<?php
    $footerFlushTop = (bool) ($footerFlushTop ?? false);
    $footerMarginTop = $footerFlushTop ? '0' : '24px';
    $footerBaseMarginPx = $footerFlushTop ? 0 : 24;
?>

<style>
    .bm-footer {
        background: #e9e9e9;
        padding: 14px 22px;
        margin-top: <?= $footerMarginTop ?>;
        border-top: 1px solid #e3e3e3;
        width: 100%;
    }

    .bm-footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 14px;
    }

    .bm-footer-left {
        justify-self: start;
    }

    .bm-footer-title {
        font-family: 'Poppins', Arial, sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #131313;
        letter-spacing: 0.01em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .bm-footer-social {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bm-footer-social-link {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #070707;
        text-decoration: none;
        transition: transform 0.18s ease, color 0.18s ease;
    }

    .bm-footer-social-link:hover {
        transform: translateY(-2px);
        color: #308BE5;
    }

    .bm-footer-social-link svg {
        width: 22px;
        height: 22px;
        fill: currentColor;
    }

    .bm-footer-center {
        text-align: center;
        justify-self: center;
    }

    .bm-footer-logo-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .bm-footer-logo {
        width: 52px;
        max-width: none;
        height: auto;
        display: inline-block;
        margin-bottom: 0;
        object-fit: contain;
    }

    .bm-footer-wordmark {
        display: inline-flex;
        flex-direction: column;
        align-items: flex-start;
        line-height: 0.92;
    }

    .bm-footer-wordmark-byte,
    .bm-footer-wordmark-market {
        font-family: 'Poppins', Arial, sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.01em;
        text-transform: capitalize;
    }

    .bm-footer-wordmark-byte {
        color: #2f80d0;
    }

    .bm-footer-wordmark-market {
        color: #22a43a;
    }

    .bm-footer-copy {
        font-family: 'Poppins', Arial, sans-serif;
        font-size: 11px;
        color: #3f3f3f;
        margin: 0;
    }

    .bm-footer-right {
        justify-self: end;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .bm-footer-nav-link {
        font-family: 'Poppins', Arial, sans-serif;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.01em;
        color: #131313;
        text-decoration: none;
        text-transform: uppercase;
        transition: color 0.18s ease;
    }

    .bm-footer-nav-link:hover {
        color: #308BE5;
    }

    @media (max-width: 900px) {
        .bm-footer {
            padding: 12px 10px;
        }

        .bm-footer-inner {
            grid-template-columns: 1fr;
            justify-items: center;
            text-align: center;
            gap: 10px;
        }

        .bm-footer-left,
        .bm-footer-center,
        .bm-footer-right {
            justify-self: center;
        }

        .bm-footer-right {
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .bm-footer-nav-link,
        .bm-footer-title {
            font-size: 11px;
        }

        .bm-footer-copy {
            font-size: 9px;
        }

        .bm-footer-logo {
            width: 44px;
        }

        .bm-footer-wordmark-byte,
        .bm-footer-wordmark-market {
            font-size: 10px;
        }

        .bm-footer-logo-link {
            gap: 4px;
        }

        .bm-footer-wordmark-byte,
        .bm-footer-wordmark-market {
            font-size: 10px;
        }

        .bm-footer-social-link {
            width: 24px;
            height: 24px;
        }

        .bm-footer-social-link svg {
            width: 18px;
            height: 18px;
        }

        .bm-footer-right {
            gap: 12px;
        }
    }
</style>

<footer class="bm-footer" role="contentinfo" aria-label="Byte Market footer" data-base-margin="<?= $footerBaseMarginPx ?>">
    <div class="bm-footer-inner">
        <div class="bm-footer-left">
            <div class="bm-footer-title">Reach Us</div>
            <div class="bm-footer-social" aria-label="Social media links">
                <a href="#" class="bm-footer-social-link" aria-label="Facebook" title="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07C2 17.1 5.66 21.27 10.44 22v-7.05H7.9v-2.88h2.54V9.41c0-2.52 1.49-3.92 3.78-3.92 1.1 0 2.25.2 2.25.2v2.47h-1.27c-1.25 0-1.64.78-1.64 1.57v1.89h2.79l-.45 2.88h-2.34V22C18.34 21.27 22 17.1 22 12.07Z"></path>
                    </svg>
                </a>
                <a href="#" class="bm-footer-social-link" aria-label="Instagram" title="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 2A3.75 3.75 0 0 0 4 7.75v8.5A3.75 3.75 0 0 0 7.75 20h8.5A3.75 3.75 0 0 0 20 16.25v-8.5A3.75 3.75 0 0 0 16.25 4h-8.5Zm8.8 1.4a1.05 1.05 0 1 1 0 2.1 1.05 1.05 0 0 1 0-2.1ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"></path>
                    </svg>
                </a>
                <a href="#" class="bm-footer-social-link" aria-label="X (Twitter)" title="X (Twitter)">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M18.9 2H22l-6.78 7.75L23.2 22h-6.27l-4.91-6.43L6.4 22H3.3l7.25-8.3L1 2h6.43l4.44 5.87L18.9 2Zm-1.1 18h1.74L6.48 3.9H4.62L17.8 20Z"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="bm-footer-center">
            <a href="<?= base_url('home') ?>" aria-label="Byte Market Home" class="bm-footer-logo-link">
                <img src="<?= base_url('assets/images/LOGO (1).png') ?>" alt="Byte Market" class="bm-footer-logo">
                <span class="bm-footer-wordmark" aria-hidden="true">
                    <span class="bm-footer-wordmark-byte">Byte</span>
                    <span class="bm-footer-wordmark-market">Market</span>
                </span>
            </a>
            <p class="bm-footer-copy">Copyright @ 2026 all rights reserved</p>
        </div>

        <nav class="bm-footer-right" aria-label="Footer links">
            <a href="<?= base_url('header/contact') ?>" class="bm-footer-nav-link">Contact Us</a>
            <a href="<?= base_url('terms') ?>" class="bm-footer-nav-link">Terms</a>
            <a href="<?= base_url('privacy') ?>" class="bm-footer-nav-link">Privacy</a>
        </nav>
    </div>
</footer>

<script>
    (function () {
        const footer = document.querySelector('.bm-footer');
        if (!footer) {
            return;
        }

        const baseMargin = Number(footer.getAttribute('data-base-margin') || 0);
        let rafId = 0;

        function applyFooterBottomReach() {
            // Reset to base margin first, then compute extra gap to viewport bottom.
            footer.style.marginTop = baseMargin + 'px';
            const footerRect = footer.getBoundingClientRect();
            const gap = Math.max(0, window.innerHeight - footerRect.bottom);
            footer.style.marginTop = (baseMargin + gap) + 'px';
        }

        function scheduleApply() {
            if (rafId) {
                cancelAnimationFrame(rafId);
            }
            rafId = requestAnimationFrame(applyFooterBottomReach);
        }

        window.addEventListener('load', scheduleApply);
        window.addEventListener('resize', scheduleApply);
        window.addEventListener('pageshow', scheduleApply);

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(scheduleApply).catch(function () {});
        }

        scheduleApply();
    })();
</script>
