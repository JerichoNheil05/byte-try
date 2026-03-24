<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byte Market - Your Marketplace for Digital Goods</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: #000000;
            --blue: #308BE5;
            --blue-dark: #2670B8;
            --green: #249E2F;
            --white: #FFFFFF;
            --gray-bg: #F5F7FA;
            --gray-dark: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--gray-bg);
            color: var(--black);
            line-height: 1.6;
        }

        /* === MAIN CONTAINER === */
        .landing-container {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin-top: 0;
        }

        .landing-content {
            max-width: 1400px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        /* === LEFT COLUMN === */
        .left-section {
            padding: 20px;
        }

        .main-heading {
            font-size: 40px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 24px;
            color: var(--black);
        }

        .description {
            font-size: 18px;
            font-weight: 400;
            line-height: 1.7;
            margin-bottom: 32px;
            color: #444;
        }

        .btn {
            display: inline-block;
            padding: 16px 40px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            outline: none;
            letter-spacing: 0.5px;
        }

        .btn-join {
            background: var(--black);
            color: var(--white);
        }

        .btn-join:hover,
        .btn-join:focus {
            background: var(--gray-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-buy {
            background: var(--blue);
            color: var(--white);
        }

        .btn-buy:hover,
        .btn-buy:focus {
            background: var(--blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(48, 139, 229, 0.4);
        }

        /* === RIGHT COLUMN === */
        .right-section {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-showcase {
            position: relative;
            background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
            border-radius: 32px;
            padding: 60px 40px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Decorative shapes */
        .showcase-bg-shape {
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            top: -50px;
            left: -50px;
            z-index: 0;
        }

        /* Phone graphic */
        .phone-graphic {
            position: relative;
            width: 200px;
            height: 360px;
            background: linear-gradient(145deg, #f0f0f0, #ffffff);
            border-radius: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            margin-bottom: 32px;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 8px solid #2a2a2a;
        }

        .phone-notch {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 20px;
            background: #2a2a2a;
            border-radius: 10px;
        }

        .phone-button {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 4px;
            background: #ccc;
            border-radius: 2px;
        }

        /* Shopping bag icon */
        .shopping-icon {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .bag-body {
            width: 100px;
            height: 80px;
            background: linear-gradient(to bottom, var(--blue) 0%, var(--blue) 60%, var(--green) 60%, var(--green) 100%);
            border-radius: 12px;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .bag-handle {
            width: 60px;
            height: 40px;
            border: 8px solid var(--green);
            border-bottom: none;
            border-radius: 30px 30px 0 0;
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .download-arrow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
            border-top: 24px solid var(--white);
            z-index: 2;
        }

        .download-arrow::before {
            content: '';
            position: absolute;
            width: 6px;
            height: 30px;
            background: var(--white);
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Text content */
        .showcase-content {
            z-index: 1;
        }

        .showcase-title {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .showcase-title .digital {
            color: var(--blue);
        }

        .showcase-title .products,
        .showcase-title .real {
            color: var(--white);
        }

        .showcase-title .results {
            color: var(--green);
        }

        .showcase-description {
            font-size: 15px;
            font-weight: 400;
            line-height: 1.6;
            color: #d0d0d0;
            margin-bottom: 24px;
        }

        /* Carousel indicators */
        .carousel-indicators {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 24px;
        }

        .top-products-section {
            width: 100%;
            margin: 0 0 0;
            padding: 56px 0 64px;
            background: #1a1a1a;
            margin-bottom: 0;
        }

        /* Landing-only footer override so black background reaches footer */
        .bm-footer {
            margin-top: 0;
        }

        .top-products-inner {
            max-width: 980px;
            margin: 0 auto;
            padding: 0 28px;
        }

        .top-products-header {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            margin-bottom: 28px;
        }

        .top-products-title {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            color: #ffffff;
            margin: 0 0 10px;
            text-align: center;
        }

        .top-products-title span {
            color: var(--green);
        }

        .top-products-subtitle {
            font-size: 14px;
            color: #aaaaaa;
            margin: 0;
            text-align: center;
        }

        .top-products-copy {
            width: 100%;
            text-align: center;
        }

        .top-products-note {
            font-size: 12px;
            font-weight: 600;
            color: #9ee2a4;
            white-space: nowrap;
        }

        .top-products-stage {
            overflow: visible;
        }

        .product-card {
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .product-card.card-swap-out {
            animation: cardOut 0.35s ease forwards;
        }

        .product-card.card-swap-in {
            animation: cardIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardOut {
            from { opacity: 1; transform: scale(1) translateY(0); }
            to   { opacity: 0; transform: scale(0.94) translateY(6px); }
        }

        @keyframes cardIn {
            from { opacity: 0; transform: scale(0.94) translateY(10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .top-products-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .top-products-indicators,
        .top-products-dot,
        .top-products-note { display: none; }

        .product-card {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.09);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .product-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.14);
            transform: translateY(-2px);
        }

        .product-card-thumb {
            aspect-ratio: 16 / 9;
            background: linear-gradient(135deg, #dde8f7 0%, #eef3f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-card-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-card-fallback {
            font-size: 13px;
            font-weight: 600;
            color: #5b6572;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .product-card-body {
            padding: 8px 10px 10px;
        }

        .product-card-category {
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: #888888;
            margin-bottom: 5px;
        }

        .product-card-title {
            font-size: 11px;
            font-weight: 700;
            line-height: 1.35;
            color: #111111;
            margin-bottom: 3px;
        }

        .product-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
        }

        .product-card-seller {
            font-size: 11px;
            color: #555555;
            display: flex;
            align-items: center;
            gap: 4px;
            min-width: 0;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .product-card-seller::after {
            content: '';
            display: inline-block;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: var(--blue);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M2.5 6l2.5 2.5 4.5-5' stroke='white' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-size: 10px 10px;
            background-repeat: no-repeat;
            background-position: center;
            flex-shrink: 0;
        }

        .product-card-rating {
            font-size: 11px;
            font-weight: 600;
            color: #f59e0b;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .product-card-rating strong {
            color: #f59e0b;
        }

        .top-products-indicators {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 18px;
        }

        .top-products-indicators,
        .top-products-dot,
        .top-products-note { display: none; }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: var(--white);
            width: 16px;
            height: 16px;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 1024px) {
            .landing-content {
                gap: 40px;
            }

            .main-heading {
                font-size: 36px;
            }

            .showcase-title {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .landing-content {
                grid-template-columns: 1fr;
                gap: 60px;
            }

            .top-products-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .top-products-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .left-section,
            .right-section {
                text-align: center;
            }

            .main-heading {
                font-size: 32px;
            }

            .description {
                font-size: 16px;
            }

            .product-showcase {
                max-width: 100%;
            }

            .showcase-title {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .landing-container {
                padding: 20px 16px;
            }

            .top-products-section {
                padding: 40px 0 48px;
            }

            .top-products-inner {
                padding: 0 16px;
            }

            .top-products-title {
                font-size: 26px;
            }

            .main-heading {
                font-size: 28px;
            }

            .description {
                font-size: 15px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .phone-graphic {
                width: 160px;
                height: 300px;
            }

            .shopping-icon {
                width: 100px;
                height: 100px;
            }

            .bag-body {
                width: 80px;
                height: 64px;
            }

            .bag-handle {
                width: 48px;
                height: 32px;
                border-width: 6px;
            }

            .showcase-title {
                font-size: 20px;
            }

            .showcase-description {
                font-size: 14px;
            }
        }

        /* === ACCESSIBILITY === */
        .btn:focus-visible,
        .indicator:focus-visible {
            outline: 3px solid var(--blue);
            outline-offset: 4px;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?= view('header', ['hideLandingHomeButton' => true]) ?>

    <main class="landing-container">
        <div class="landing-content">
            <!-- LEFT SECTION -->
            <section class="left-section">
                <h1 class="main-heading">Your marketplace for digital goods.</h1>
                <p class="description">
                    Explore a growing collection of digital products designed for convenience and speed. 
                    No shipping, just instant access with Byte Market.
                </p>
                <a href="#" class="btn btn-join" role="button" aria-label="Join Byte Market now" onclick="openLoginModal(); return false;">
                    Join Now
                </a>
            </section>

            <!-- RIGHT SECTION -->
            <section class="right-section">
                <div class="product-showcase">
                    <div class="showcase-bg-shape" aria-hidden="true"></div>
                    
                    <!-- Phone with shopping bag icon -->
                    <div class="phone-graphic" role="img" aria-label="Smartphone displaying digital marketplace">
                        <div class="phone-notch" aria-hidden="true"></div>
                        <div class="shopping-icon">
                            <div class="bag-handle" aria-hidden="true"></div>
                            <div class="bag-body" aria-hidden="true"></div>
                            <div class="download-arrow" aria-hidden="true"></div>
                        </div>
                        <div class="phone-button" aria-hidden="true"></div>
                    </div>

                    <!-- Content -->
                    <div class="showcase-content">
                        <h2 class="showcase-title">
                            <span class="digital">Digital</span> 
                            <span class="products">Products</span><br>
                            <span class="real">Real</span> 
                            <span class="results">Results</span>
                        </h2>
                        <p class="showcase-description">
                            Discover, purchase, and access digital products anytime. 
                            Byte Market keeps everything fast, secure, and simple.
                        </p>
                        <a href="#" class="btn btn-buy" role="button" aria-label="Buy and download digital products" onclick="openLoginModal(); return false;">
                            Buy & Download
                        </a>
                    </div>

                    <!-- Carousel indicators -->
                    <div class="carousel-indicators" role="group" aria-label="Slide indicators">
                        <span class="indicator" role="button" tabindex="0" aria-label="Go to slide 1"></span>
                        <span class="indicator active" role="button" tabindex="0" aria-label="Go to slide 2" aria-current="true"></span>
                        <span class="indicator" role="button" tabindex="0" aria-label="Go to slide 3"></span>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <section class="top-products-section" aria-labelledby="topProductsTitle">
        <div class="top-products-inner">
            <div class="top-products-header">
                <div class="top-products-copy">
                    <h2 class="top-products-title" id="topProductsTitle">Featured <span>Digital</span> Products</h2>
                    <p class="top-products-subtitle">Handpicked downloads from the <span style="color: var(--green); font-weight: 600;">Byte Market</span> collection.</p>
                </div>
            </div>

            <?php
                $allProducts = $topProducts ?? [];
                $visibleSlots = 10;
                $initial = array_slice($allProducts, 0, $visibleSlots);
                $pool = count($allProducts) > $visibleSlots ? array_slice($allProducts, $visibleSlots) : [];
            ?>

            <div class="top-products-grid" id="topProductsGrid">
                <?php foreach ($initial as $slotIndex => $product): ?>
                    <article class="product-card" data-slot="<?= $slotIndex ?>">
                        <div class="product-card-thumb">
                            <?php if (!empty($product['thumbnail_url'])): ?>
                                <img src="<?= esc($product['thumbnail_url']) ?>" alt="<?= esc($product['title'] ?? 'Product preview') ?>" loading="lazy">
                            <?php else: ?>
                                <div class="product-card-fallback">ByteMarket</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-body">
                            <div class="product-card-title"><?= esc($product['title'] ?? 'Untitled Product') ?></div>
                            <div class="product-card-category"><?= esc($product['category'] ?? 'Digital Product') ?></div>
                            <div class="product-card-meta">
                                <div class="product-card-seller"><?= esc($product['seller'] ?? 'ByteMarket Seller') ?></div>
                                <div class="product-card-rating">&#9733; <?= number_format((float)($product['rating'] ?? 0), 1) ?>(<?= (int)($product['reviews'] ?? 0) ?>)</div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
                <?php if (empty($initial)): ?>
                    <article class="product-card">
                        <div class="product-card-body">
                            <div class="product-card-title">No products available yet</div>
                        </div>
                    </article>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?= view('footer', ['footerFlushTop' => true]) ?>
    <script>
    (function () {
        // All products pool passed from PHP
        const pool = <?= json_encode(array_values(array_map(function($p) {
            return [
                'title'         => $p['title'] ?? 'Untitled Product',
                'category'      => $p['category'] ?? 'Digital Product',
                'seller'        => $p['seller'] ?? 'ByteMarket Seller',
                'rating'        => number_format((float)($p['rating'] ?? 0), 1),
                'reviews'       => (int)($p['reviews'] ?? 0),
                'thumbnail_url' => $p['thumbnail_url'] ?? null,
            ];
        }, $allProducts)), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        if (pool.length <= 10) return; // nothing to rotate if short pool

        const cards = Array.from(document.querySelectorAll('#topProductsGrid .product-card'));
        // Track which product index is currently shown in each slot
        const slotIndex = cards.map((_, i) => i);
        // Indices already visible on load
        const inUse = new Set(slotIndex);

        function buildCardHTML(p) {
            const thumb = p.thumbnail_url
                ? `<img src="${p.thumbnail_url.replace(/"/g,'&quot;')}" alt="" loading="lazy">`
                : `<div class="product-card-fallback">ByteMarket</div>`;
            return `
                <div class="product-card-thumb">${thumb}</div>
                <div class="product-card-body">
                    <div class="product-card-title">${p.title}</div>
                    <div class="product-card-category">${p.category}</div>
                    <div class="product-card-meta">
                        <div class="product-card-seller">${p.seller}</div>
                        <div class="product-card-rating">&#9733; ${p.rating}(${p.reviews})</div>
                    </div>
                </div>`;
        }

        function pickNext(excludeSet) {
            // Pick a random product not currently visible
            const available = pool.map((_, i) => i).filter(i => !excludeSet.has(i));
            if (!available.length) return null;
            return available[Math.floor(Math.random() * available.length)];
        }

        function scheduleSlot(card, slot) {
            // Each slot gets a unique random interval: 7s–14s
            const delay = 7000 + Math.floor(Math.random() * 7000);
            setTimeout(() => swapSlot(card, slot), delay);
        }

        function swapSlot(card, slot) {
            const nextIdx = pickNext(inUse);
            if (nextIdx === null) { scheduleSlot(card, slot); return; }

            card.classList.add('card-swap-out');

            setTimeout(() => {
                // Update pool tracking
                inUse.delete(slotIndex[slot]);
                slotIndex[slot] = nextIdx;
                inUse.add(nextIdx);

                // Inject new content
                card.innerHTML = buildCardHTML(pool[nextIdx]);
                card.classList.remove('card-swap-out');
                card.classList.add('card-swap-in');

                setTimeout(() => card.classList.remove('card-swap-in'), 550);

                // Schedule next swap for this slot independently
                scheduleSlot(card, slot);
            }, 360);
        }

        // Kick off each slot on its own independent random schedule
        cards.forEach((card, slot) => scheduleSlot(card, slot));
    })();
    </script>
</body>
</html>
