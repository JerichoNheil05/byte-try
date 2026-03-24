<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail - Byte Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #ececec;
            color: #111111;
        }

        body { padding-top: 80px; }

        .top-categories {
            height: 56px;
            background: #000000;
            overflow-x: auto;
            scrollbar-width: none;
            position: sticky;
            top: 80px;
            z-index: 900;
        }

        .top-categories::-webkit-scrollbar { display: none; }

        .top-categories-list {
            list-style: none;
            display: flex;
            align-items: stretch;
            justify-content: center;
            min-width: max-content;
            height: 56px;
        }

        .top-categories-list li {
            border-right: 1px solid #2a2a2a;
            border-left: 1px solid #2a2a2a;
        }

        .top-cat-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-width: 98px;
            height: 56px;
            padding: 0 14px;
            color: #ffffff;
            text-decoration: none;
            font-size: 15px;
            line-height: 1.05;
            font-weight: 500;
        }

        .top-cat-link.active { font-weight: 600; }

        .detail-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 26px 18px 42px;
            position: relative;
            overflow: hidden;
        }

        .back-link {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            padding: 8px 14px;
            border-radius: 999px;
            background: #ffffff;
            color: #1f2937;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        .back-link:hover {
            background: #f8fafc;
        }

        .back-link svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            stroke-width: 2.2;
            fill: none;
        }

        .detail-decor {
            position: absolute;
            pointer-events: none;
            z-index: 0;
        }

        .decor-ribbon {
            top: 0;
            left: 0;
            width: 44px;
            height: 36px;
            background: #ffffff;
            clip-path: polygon(0 0, 100% 0, 100% 72%, 50% 100%, 0 72%);
            opacity: 0.95;
        }

        .decor-circle-top {
            top: 16px;
            right: 42px;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #c9dff4;
        }

        .decor-circle-bottom {
            left: 2px;
            bottom: 106px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #c9dff4;
        }

        .decor-triangle {
            right: 8px;
            bottom: 98px;
            width: 48px;
            height: 42px;
            background: #d8d8d8;
            clip-path: polygon(50% 0, 0 100%, 100% 100%);
            opacity: 0.85;
        }

        .gallery-track {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 6px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .gallery-frame {
            width: 100%;
            height: 300px;
            overflow: hidden;
            background: #dfe7f5;
        }

        .gallery-item {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            background: #dfe7f5;
        }

        .dots {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .dots.hidden { display: none; }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #d0d0d0;
            border: none;
            cursor: pointer;
        }

        .dot.active { background: #3e8fda; }

        .detail-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 178px;
            gap: 22px;
            align-items: start;
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 100%;
        }

        .price-row {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .price {
            font-size: clamp(28px, 2.8vw, 38px);
            line-height: 1;
            color: #308be5;
            font-weight: 700;
        }

        .rating-inline {
            font-size: clamp(11px, 1vw, 13px);
            color: #3f87ce;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .rating-inline i { color: #f2bc1a; font-size: 11px; }

        .title {
            margin-top: 6px;
            font-size: clamp(28px, 3.2vw, 40px);
            line-height: 1.08;
            font-weight: 700;
            color: #0e0e0e;
            max-width: 760px;
        }

        .seller {
            margin-top: 6px;
            font-size: clamp(15px, 1.6vw, 20px);
            line-height: 1;
            color: #29a13a;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .seller i { color: #3f87ce; font-size: 16px; }

        .cta-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 24px;
        }

        .btn {
            height: 34px;
            border: none;
            border-radius: 3px;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            cursor: pointer;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 120px;
        }

        .btn-cart { background: #3f8fd8; }
        .btn-buy { background: #21a52f; }

        .section-title {
            margin-top: 16px;
            font-size: clamp(16px, 1.6vw, 24px);
            line-height: 1;
            font-weight: 700;
        }

        .desc {
            margin-top: 8px;
            font-size: clamp(13px, 1.1vw, 16px);
            line-height: 1.5;
            color: #2c2c2c;
            max-width: 980px;
        }

        .feedback-title {
            margin-top: 28px;
            font-size: 44px;
            font-weight: 700;
        }

        .feedback-list {
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feedback-card {
            background: #f5f5f5;
            border: 1px solid #dddddd;
            border-radius: 26px;
            padding: 18px 22px;
        }

        .feedback-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .feedback-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #2ca03a;
        }

        .feedback-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #cfcfcf;
        }

        .feedback-date {
            font-size: 11px;
            color: #6e6e6e;
        }

        .feedback-stars {
            color: #f2bc1a;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .feedback-text {
            font-size: 12px;
            line-height: 1.45;
            color: #4a4a4a;
        }

        .more-feedback {
            margin-top: 8px;
            text-align: right;
            font-size: 11px;
            color: #23a236;
            font-weight: 600;
        }

        .divider {
            margin-top: 24px;
            border-top: 1px solid #d7d7d7;
        }

        .give-feedback {
            margin-top: 20px;
            max-width: 520px;
        }

        .give-feedback h4 {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .give-feedback .label {
            font-size: 10px;
            color: #747474;
            margin-bottom: 6px;
        }

        .give-feedback .stars {
            color: #f2bc1a;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .feedback-input {
            width: 100%;
            height: 70px;
            border: 1px solid #dedede;
            border-radius: 4px;
            background: #f5f5f5;
            font-family: inherit;
            font-size: 12px;
            padding: 8px 10px;
        }

        .feedback-actions {
            margin-top: 8px;
            display: flex;
            gap: 8px;
        }

        .post-btn,
        .cancel-btn {
            height: 20px;
            border: none;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            padding: 0 10px;
        }

        .post-btn {
            background: #24a232;
            color: #ffffff;
        }

        .cancel-btn {
            background: #4d95dd;
            color: #ffffff;
        }

        .feedback-locked {
            margin-top: 10px;
            font-size: 13px;
            color: #555555;
        }

        .feedback-message {
            margin-bottom: 14px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        .feedback-message.success {
            border: 1px solid #c3e6cb;
            background: #d4edda;
            color: #155724;
        }

        .feedback-message.error {
            border: 1px solid #f5c6cb;
            background: #f8d7da;
            color: #721c24;
        }

        .feedback-select {
            width: 100%;
            max-width: 180px;
            height: 36px;
            border: 1px solid #dedede;
            border-radius: 4px;
            padding: 0 10px;
            font-family: inherit;
            font-size: 13px;
            background: #ffffff;
            margin-bottom: 10px;
        }

        @media (max-width: 1024px) {
            .title { font-size: 30px; }
            .price { font-size: 30px; }
            .seller { font-size: 17px; }
            .section-title, .feedback-title { font-size: 24px; }
            .desc { font-size: 15px; }
            .gallery-frame { height: 250px; }
            .detail-main { grid-template-columns: 1fr; }
            .cta-box { flex-direction: row; }
        }

        @media (max-width: 680px) {
            body { padding-top: 72px; }
            .top-categories { top: 72px; }
            .gallery-track { grid-template-columns: 1fr; }
            .detail-page { padding: 18px 12px 24px; }
            .title { font-size: 24px; }
            .desc { font-size: 14px; }
            .gallery-frame { height: 230px; }
            .btn { width: auto; min-width: 120px; }
            .decor-circle-top,
            .decor-circle-bottom,
            .decor-triangle,
            .decor-ribbon { display: none; }
        }
    </style>
</head>
<body>
<?php
    $topCategories = [
        'templates' => 'Templates',
        'study-productivity' => 'Study & Productivity',
        'design-assets' => 'Design Assets',
        'e-books' => 'E-books',
        'printables' => 'Printables',
        'presentation-slides' => 'Presentation Slides',
        'marketing-materials' => 'Marketing Materials',
        'business-finance-tools' => 'Business & Finance Tools',
        'creative-packs' => 'Creative Packs',
    ];

    $previewUrls = $product['preview_urls'] ?? [];
    if (empty($previewUrls)) {
        $previewUrls[] = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3EProduct%20Preview%3C/text%3E%3C/svg%3E';
    }

    $carouselUrls = array_values($previewUrls);
    $isCarouselEnabled = count($carouselUrls) >= 2;

    $feedbackEntries = is_array($feedbackEntries ?? null) ? $feedbackEntries : [];
    $canLeaveFeedback = (bool) ($canLeaveFeedback ?? false);
    $userFeedback = is_array($userFeedback ?? null) ? $userFeedback : null;

    $activeCategory = 'presentation-slides';
?>
    <nav class="top-categories" aria-label="Main categories">
        <ul class="top-categories-list">
            <?php foreach ($topCategories as $slug => $label): ?>
                <li>
                    <a href="<?= base_url('home?top=' . urlencode($slug)) ?>" class="top-cat-link <?= $slug === $activeCategory ? 'active' : '' ?>">
                        <?= esc($label) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <main class="detail-page">
        <a href="<?= base_url('home') ?>" class="back-link" aria-label="Back to home">
            <svg viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6"></path>
                <path d="M21 12H9"></path>
            </svg>
            <span>Back</span>
        </a>

        <?php if (session()->getFlashdata('message')): ?>
            <div class="feedback-message success"><?= esc(session()->getFlashdata('message')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="feedback-message error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <span class="detail-decor decor-ribbon" aria-hidden="true"></span>
        <span class="detail-decor decor-circle-top" aria-hidden="true"></span>
        <span class="detail-decor decor-circle-bottom" aria-hidden="true"></span>
        <span class="detail-decor decor-triangle" aria-hidden="true"></span>

        <section class="gallery-track" id="galleryTrack">
            <?php if ($isCarouselEnabled): ?>
                <?php
                    $initialIndexes = [
                        (count($carouselUrls) - 1),
                        0,
                        (count($carouselUrls) > 1 ? 1 : 0),
                    ];
                ?>
                <?php foreach ($initialIndexes as $index): ?>
                    <div class="gallery-frame">
                        <img class="gallery-item" src="<?= esc($carouselUrls[$index]) ?>" alt="Preview <?= $index + 1 ?>" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3EPreview%3C/text%3E%3C/svg%3E';">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="gallery-frame">
                    <img class="gallery-item" src="<?= esc($carouselUrls[0]) ?>" alt="Preview 1" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3EPreview%3C/text%3E%3C/svg%3E';">
                </div>
            <?php endif; ?>
        </section>

        <div class="dots <?= $isCarouselEnabled ? '' : 'hidden' ?>" id="galleryDots" aria-hidden="true"></div>

        <section class="detail-main">
            <div>
                <div class="price-row">
                    <div class="price">₱<?= number_format((float) ($product['price'] ?? 0), 2) ?></div>
                    <div class="rating-inline"><?= number_format((float) ($product['rating'] ?? 4.5), 1) ?>/5 <i class="fas fa-star"></i></div>
                </div>

                <h1 class="title"><?= esc($product['title'] ?? 'Untitled Product') ?></h1>
                <div class="seller">by <?= esc($product['seller'] ?? 'MCreateArts') ?> <i class="fas fa-check-circle"></i></div>

                <div class="section-title">Description:</div>
                <p class="desc"><?= esc($product['description'] ?? 'No description provided.') ?></p>

                <?php if (!empty($product['product_feature'])): ?>
                    <div class="section-title">Product Feature:</div>
                    <p class="desc"><?= nl2br(esc($product['product_feature'])) ?></p>
                <?php endif; ?>

                <?php if (!empty($product['how_it_works'])): ?>
                    <div class="section-title">How it works:</div>
                    <p class="desc"><?= nl2br(esc($product['how_it_works'])) ?></p>
                <?php endif; ?>
            </div>

            <div class="cta-box">
                <?php if ((int) ($product['id'] ?? 0) > 0): ?>
                    <form method="post" action="<?= base_url('cart/add/' . (int) $product['id']) ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-cart">Add to Cart</button>
                    </form>
                <?php else: ?>
                    <button type="button" class="btn btn-cart">Add to Cart</button>
                <?php endif; ?>

                <?php if ((int) ($product['id'] ?? 0) > 0): ?>
                    <button type="button" class="btn btn-buy" id="buyNowBtn">Buy Now</button>
                <?php else: ?>
                    <a class="btn btn-buy" href="<?= base_url('cart') ?>">Buy Now</a>
                <?php endif; ?>
            </div>
        </section>

        <h3 class="section-title">Feedback</h3>
        <div class="feedback-list">
            <?php if (!empty($feedbackEntries)): ?>
                <?php foreach ($feedbackEntries as $entry): ?>
                    <?php $stars = max(1, min(5, (int) ($entry['rating'] ?? 5))); ?>
                    <div class="feedback-card">
                        <div class="feedback-head">
                            <div class="feedback-user">
                                <span class="feedback-avatar" aria-hidden="true"></span>
                                <span><?= esc($entry['full_name'] ?? 'Buyer') ?></span>
                            </div>
                            <span class="feedback-date"><?= !empty($entry['created_at']) ? esc(date('M j, Y', strtotime((string) $entry['created_at']))) : '' ?></span>
                        </div>
                        <div class="feedback-stars" aria-label="Rated <?= $stars ?> out of 5">
                            <?= str_repeat('★', $stars) . str_repeat('☆', 5 - $stars) ?>
                        </div>
                        <p class="feedback-text"><?= esc($entry['comment'] ?? '') ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="feedback-card">
                    <p class="feedback-text">No feedback yet for this product.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="give-feedback">
            <?php if (session()->get('isLoggedIn') && $canLeaveFeedback): ?>
                <h4><?= $userFeedback ? 'Update your feedback' : 'Leave your feedback' ?></h4>
                <div class="label">Only buyers who purchased this product can submit feedback.</div>
                <form method="post" action="<?= base_url('home/product/' . (int) ($product['id'] ?? 0) . '/feedback') ?>">
                    <?= csrf_field() ?>
                    <?php
                        $selectedRating = old('rating');
                        if ($selectedRating === null || $selectedRating === '') {
                            $selectedRating = (string) ($userFeedback['rating'] ?? '5');
                        }
                        $commentValue = old('comment');
                        if ($commentValue === null || $commentValue === '') {
                            $commentValue = (string) ($userFeedback['comment'] ?? '');
                        }
                    ?>
                    <select class="feedback-select" name="rating" required aria-label="Rating">
                        <?php for ($r = 5; $r >= 1; $r--): ?>
                            <option value="<?= $r ?>" <?= (string) $r === (string) $selectedRating ? 'selected' : '' ?>><?= $r ?> Star<?= $r > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                    <textarea class="feedback-input" name="comment" placeholder="Share your experience with this product" required><?= esc($commentValue) ?></textarea>
                    <div class="feedback-actions">
                        <button type="submit" class="post-btn"><?= $userFeedback ? 'Update Feedback' : 'Post Feedback' ?></button>
                    </div>
                </form>
            <?php elseif (session()->get('isLoggedIn')): ?>
                <p class="feedback-locked">You can add feedback only after purchasing this product.</p>
            <?php else: ?>
                <p class="feedback-locked">Please <a href="<?= base_url('auth/login') ?>">log in</a> and purchase this product to leave feedback.</p>
            <?php endif; ?>
        </div>
    </main>

    <script>
        const csrfTokenName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        const previewPool = <?= json_encode(array_values($carouselUrls), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const galleryTrack = document.getElementById('galleryTrack');
        const dotsWrap = document.getElementById('galleryDots');

        if (previewPool.length >= 2 && dotsWrap && galleryTrack) {
            let activeIndex = 0;

            const renderGallery = () => {
                const indexes = [
                    (activeIndex - 1 + previewPool.length) % previewPool.length,
                    activeIndex,
                    (activeIndex + 1) % previewPool.length,
                ];

                galleryTrack.innerHTML = indexes.map((index, slot) => {
                    const safeAlt = `Preview ${index + 1}`;
                    const safeSrc = previewPool[index];

                    return `<div class="gallery-frame" data-slot="${slot}"><img class="gallery-item" src="${safeSrc}" alt="${safeAlt}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3EPreview%3C/text%3E%3C/svg%3E';"></div>`;
                }).join('');
            };

            const renderDots = () => {
                dotsWrap.innerHTML = '';
                previewPool.forEach((_, index) => {
                    const dot = document.createElement('button');
                    dot.className = `dot ${index === activeIndex ? 'active' : ''}`;
                    dot.type = 'button';
                    dot.addEventListener('click', () => {
                        activeIndex = index;
                        renderGallery();
                        renderDots();
                    });
                    dotsWrap.appendChild(dot);
                });
            };

            renderGallery();
            renderDots();
        }

        const buyNowBtn = document.getElementById('buyNowBtn');
        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', function () {
                const payload = {
                    items: [{
                        product_id: <?= (int) ($product['id'] ?? 0) ?>,
                        name: <?= json_encode((string) ($product['title'] ?? 'Product'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                        amount: <?= (float) ($product['price'] ?? 0) ?>,
                        quantity: 1,
                        thumbnail_url: <?= json_encode((string) (($product['preview_urls'][0] ?? '') ?: ''), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                    }],
                };

                buyNowBtn.disabled = true;
                buyNowBtn.textContent = 'Processing...';

                fetch('<?= base_url('payment/digital-product/checkout-session') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfHash,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data && typeof data.csrfHash === 'string' && data.csrfHash !== '') {
                        csrfHash = data.csrfHash;
                    }

                    if (!data || !data.success || !data.data || !data.data.checkout_url) {
                        throw new Error((data && data.message) ? data.message : 'Unable to create checkout session.');
                    }

                    window.location.href = data.data.checkout_url;
                })
                .catch((error) => {
                    alert(error.message || 'Unable to proceed to checkout. Please try again.');
                    buyNowBtn.disabled = false;
                    buyNowBtn.textContent = 'Buy Now';
                });
            });
        }
    </script>
</body>
</html>
