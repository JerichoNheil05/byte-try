<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Byte Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f9fafb;
            color: #111;
            padding-top: 88px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        .page-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 60px 80px;
            position: relative;
            overflow: hidden;
        }

        /* === Decorative background === */
        .deco-circle {
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #dde8f7;
            top: 36px;
            right: 120px;
            pointer-events: none;
        }

        .deco-stripes {
            position: absolute;
            top: 60px;
            right: -20px;
            width: 180px;
            height: 260px;
            pointer-events: none;
            opacity: 0.45;
        }

        .deco-stripes-left {
            position: absolute;
            bottom: 140px;
            left: 30px;
            width: 140px;
            height: 180px;
            pointer-events: none;
            opacity: 0.30;
        }

        .deco-stripe-line {
            height: 10px;
            background: #c5d8b8;
            border-radius: 6px;
            margin-bottom: 14px;
            transform: rotate(-30deg);
        }

        /* === Title === */
        .page-title {
            font-size: 60px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .page-title .word-my    { color: #249e2f; }
        .page-title .word-ord   { color: #308BE5; }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 36px;
        }

        /* === Filter bar === */
    

        /* === Product Grid === */
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }

        /* === Card === */
        .order-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            transition: box-shadow 0.25s ease, transform 0.25s ease;
            display: flex;
            flex-direction: column;
        }

        .order-card:hover {
            box-shadow: 0 8px 28px rgba(0,0,0,0.13);
            transform: translateY(-4px);
        }

        .card-thumb {
            width: 100%;
            height: 190px;
            overflow: hidden;
            background: #e8e8e8;
            flex-shrink: 0;
            position: relative;
        }

        .card-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .card-thumb-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8e8e8 0%, #d4d4d4 100%);
            font-size: 52px;
        }

        .card-body {
            padding: 14px 16px 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: #111;
            line-height: 1.4;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-status {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .card-status-completed {
            color: #24a13f;
        }

        .card-status-pending {
            color: #3b8fe4;
        }

        .card-seller-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 10px;
        }

        .card-seller {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #374151;
            font-weight: 500;
        }

        .seller-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            background: #308BE5;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .seller-badge svg {
            width: 9px;
            height: 9px;
            fill: #fff;
        }

        .card-download {
            width: 32px;
            height: 32px;
            background: #f3f4f6;
            border: none;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
            flex-shrink: 0;
            text-decoration: none;
            color: #374151;
        }

        .card-download:hover { background: #308BE5; color: #fff; }

        .card-download svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .card-visit-link {
            width: 32px;
            height: 32px;
            background: #e6f4ea;
            border: none;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
            flex-shrink: 0;
            text-decoration: none;
            color: #16a34a;
        }

        .card-visit-link:hover { background: #16a34a; color: #fff; }

        .card-visit-link svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .card-actions {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .card-refund {
            height: 32px;
            padding: 0 10px;
            border-radius: 7px;
            border: 1px solid #f59e0b;
            background: #fffbeb;
            color: #b45309;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
        }

        .card-refund:hover {
            background: #fef3c7;
        }

        .refund-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(15, 23, 42, 0.45);
            z-index: 1100;
            padding: 20px;
        }

        .refund-modal.active {
            display: flex;
        }

        .refund-modal-card {
            width: min(760px, 100%);
            max-height: calc(100vh - 40px);
            overflow: auto;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.22);
            padding: 16px;
        }

        .refund-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .refund-modal-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .refund-modal-product {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .refund-modal-close {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #374151;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        .refund-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .refund-field {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 11px;
            color: #4b5563;
        }

        .refund-field input,
        .refund-field select,
        .refund-field textarea {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 6px 8px;
            font-size: 12px;
            font-family: 'Poppins', Arial, sans-serif;
            background: #fff;
        }

        .refund-field.refund-field-wide {
            grid-column: 1 / -1;
        }

        .refund-field.refund-main {
            grid-column: 1 / -1;
        }

        .refund-checkbox {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #374151;
        }

        .refund-submit {
            margin-top: 10px;
            border: none;
            background: #b45309;
            color: #fff;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .refund-submit:hover {
            background: #92400e;
        }

        .refund-result {
            margin-bottom: 16px;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            color: #1f2937;
        }

        .refund-result.bad {
            border-color: #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        .refund-result.good {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .toast-stack {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 1400;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: min(360px, calc(100vw - 24px));
        }

        .toast-card {
            border-radius: 10px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: #1e3a8a;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
            padding: 12px 14px;
            opacity: 0;
            animation: toast-fade-in 3s ease forwards;
            cursor: pointer;
        }

        .toast-card:hover {
            border-color: #93c5fd;
            background: #dbeafe;
        }

        .toast-title {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 12px;
            line-height: 1.45;
            color: #1f2937;
        }

        .toast-close {
            margin-top: 8px;
            border: 1px solid #c7d2fe;
            background: #ffffff;
            color: #1e3a8a;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            cursor: pointer;
        }

        @keyframes toast-fade-in {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* === Empty state === */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state-icon { font-size: 64px; margin-bottom: 20px; }

        .empty-state-title {
            font-size: 22px;
            font-weight: 700;
            color: #111;
            margin-bottom: 10px;
        }

        .empty-state-sub {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 28px;
        }

        .empty-cta {
            display: inline-block;
            background: #308BE5;
            color: #fff;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .empty-cta:hover {
            background: #2670b8;
            transform: translateY(-2px);
        }

        /* === Responsive === */
        @media (max-width: 900px) {
            .page-wrap { padding: 24px 20px 60px; }
            .page-title { font-size: 42px; }
        }

        @media (max-width: 600px) {
            .page-title { font-size: 32px; }
            .orders-grid { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; }
            .card-thumb { height: 140px; }
            .refund-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<main class="page-wrap">

    <!-- Decorative elements -->
    <div class="deco-circle" aria-hidden="true"></div>
    <div class="deco-stripes" aria-hidden="true">
        <div class="deco-stripe-line"></div>
        <div class="deco-stripe-line"></div>
        <div class="deco-stripe-line"></div>
        <div class="deco-stripe-line"></div>
    </div>
    <div class="deco-stripes-left" aria-hidden="true">
        <div class="deco-stripe-line"></div>
        <div class="deco-stripe-line"></div>
        <div class="deco-stripe-line"></div>
    </div>

    <!-- Title -->
    <h1 class="page-title">
        <span class="word-my">My</span>&nbsp;<span class="word-ord">Orders</span>
    </h1>
    <p class="page-subtitle">Hi <?= esc($buyerDisplayName ?? 'Buyer') ?>, here are your purchases</p>

    <?php if (session()->has('success')): ?>
        <div class="refund-result good">
            <?= esc((string) session('success')) ?>
        </div>
    <?php endif; ?>

    <?php $refundResult = session()->getFlashdata('refund_result'); ?>
    <?php if (!empty($refundResult) && is_array($refundResult)): ?>
        <?php $isRejected = strtoupper((string) ($refundResult['status'] ?? '')) === 'REJECTED'; ?>
        <div class="refund-result <?= $isRejected ? 'bad' : 'good' ?>">
            <strong>Refund Request Result:</strong>
            <?= esc((string) ($refundResult['message'] ?? 'Request processed.')) ?><br>
            Product: <?= esc((string) ($refundResult['product_name'] ?? 'Product')) ?> |
            Decision: <?= esc((string) ($refundResult['refund_decision'] ?? 'DENIED')) ?> |
            Status: <?= esc((string) ($refundResult['status'] ?? 'REJECTED')) ?> |
            Rule: <?= esc((string) ($refundResult['decision_rule'] ?? 'NO_MATCH')) ?>
        </div>
    <?php endif; ?>

    <!-- Cards grid -->
    <?php if (!empty($orders)): ?>
        <div class="orders-grid">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <!-- Thumbnail -->
                    <div class="card-thumb">
                        <?php if (!empty($order['product_image'])): ?>
                            <img src="<?= esc($order['product_image']) ?>"
                                 alt="<?= esc($order['product_name'] ?? 'Product') ?>"
                                 loading="lazy"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <div class="card-thumb-placeholder" style="display:none;">📦</div>
                        <?php else: ?>
                            <div class="card-thumb-placeholder">📦</div>
                        <?php endif; ?>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <p class="card-title"><?= esc($order['product_name'] ?? 'Product') ?></p>

                        <div class="card-seller-row">
                            <span class="card-seller">
                                <span class="seller-badge" aria-hidden="true">
                                    <svg viewBox="0 0 10 8"><polyline points="1,4 4,7 9,1"/></svg>
                                </span>
                                <?= esc($order['seller_name'] ?? 'Seller') ?>
                            </span>

                            <div class="card-actions">
                                <?php if (!empty($order['redirect_url'])): ?>
                                <a class="card-visit-link"
                                   href="<?= esc($order['redirect_url'], 'attr') ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="Visit link">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <polyline points="15,3 21,3 21,9"/>
                                        <line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </a>
                                <?php endif; ?>

                                <?php if (!empty($order['has_file'])): ?>
                                <a class="card-download"
                                   href="<?= base_url('my-orders/download/' . (int)($order['product_id'] ?? 0)) ?>"
                                   title="Download product">
                                    <svg viewBox="0 0 24 24">
                                        <line x1="12" y1="3"  x2="12" y2="15"/>
                                        <polyline points="6,10 12,16 18,10"/>
                                        <line x1="4" y1="21" x2="20" y2="21"/>
                                    </svg>
                                </a>
                                <?php endif; ?>

                                <?php if ((int) ($order['product_id'] ?? 0) > 0): ?>
                                    <button
                                        type="button"
                                        class="card-refund"
                                        onclick="openRefundModal(this)"
                                        data-order-id="<?= (int) ($order['order_id'] ?? 0) ?>"
                                        data-product-id="<?= (int) ($order['product_id'] ?? 0) ?>"
                                        data-product-name="<?= esc((string) ($order['product_name'] ?? 'Product'), 'attr') ?>"
                                        title="Request refund"
                                    >
                                        Refund
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">🛍️</div>
            <p class="empty-state-title">No orders yet</p>
            <p class="empty-state-sub">You don't have any completed orders yet. Start shopping!</p>
                <a href="<?= base_url('home') ?>" class="empty-cta">Continue Shopping</a>
        </div>
    <?php endif; ?>

</main>

<div class="toast-stack" id="refundToastStack" aria-live="polite" aria-atomic="false"></div>

<div class="refund-modal" id="refundModal" aria-hidden="true">
    <div class="refund-modal-card" role="dialog" aria-modal="true" aria-labelledby="refundModalTitle">
        <div class="refund-modal-head">
            <div>
                <div class="refund-modal-title" id="refundModalTitle">Request Refund</div>
                <div class="refund-modal-product" id="refundModalProduct">Product</div>
            </div>
            <button type="button" class="refund-modal-close" id="refundModalClose" aria-label="Close refund modal">×</button>
        </div>

        <form method="post" action="<?= base_url('my-orders/refund') ?>" id="refundModalForm">
            <?= csrf_field() ?>
            <input type="hidden" name="order_id" id="refundOrderId" value="0">
            <input type="hidden" name="product_id" id="refundProductId" value="0">

            <div class="refund-grid">
                <label class="refund-field refund-main">
                    Refund Request Type
                    <select name="refund_case" required>
                        <option value="not_delivered_technical_error_verified">Not delivered + technical error verified</option>
                        <option value="corrupted_or_inaccessible_replacement_unavailable">Corrupted/Inaccessible and replacement unavailable</option>
                        <option value="product_mismatch_description">Product does not match description</option>
                        <option value="change_of_mind">Change of mind</option>
                        <option value="accidental_purchase">Accidental purchase</option>
                        <option value="lack_of_technical_knowledge">Lack of technical knowledge</option>
                        <option value="did_not_read_description">Did not read description</option>
                        <option value="user_side_issue">Issue from user device / connection / software</option>
                        <option value="other" selected>Other</option>
                    </select>
                </label>

                <label class="refund-field refund-field-wide">
                    Additional Notes
                    <textarea name="buyer_notes" rows="2" placeholder="Briefly describe your issue."></textarea>
                </label>

                <label class="refund-checkbox refund-field-wide">
                    <input type="checkbox" name="proof_of_issue" value="1">
                    I can provide proof of issue
                </label>
            </div>

            <button type="submit" class="refund-submit">Submit Refund Request</button>
        </form>
    </div>
</div>

<script>
    const refundResultsEndpoint = '<?= base_url('my-orders/refund/results') ?>';
    const notificationsPageBase = '<?= base_url('notifications') ?>';
    const refundModal = document.getElementById('refundModal');
    const refundModalClose = document.getElementById('refundModalClose');
    const refundOrderId = document.getElementById('refundOrderId');
    const refundProductId = document.getElementById('refundProductId');
    const refundModalProduct = document.getElementById('refundModalProduct');
    const refundToastStack = document.getElementById('refundToastStack');

    const seenToastStorageKey = 'bm_seen_refund_result_notification_ids';
    const seenToastIds = new Set();

    function loadSeenToastIds() {
        try {
            const raw = window.sessionStorage.getItem(seenToastStorageKey);
            if (!raw) {
                return;
            }
            const parsed = JSON.parse(raw);
            if (!Array.isArray(parsed)) {
                return;
            }
            parsed.forEach(function (id) {
                const value = Number(id);
                if (Number.isInteger(value) && value > 0) {
                    seenToastIds.add(value);
                }
            });
        } catch (error) {
            // Ignore storage issues.
        }
    }

    function persistSeenToastIds() {
        try {
            window.sessionStorage.setItem(seenToastStorageKey, JSON.stringify(Array.from(seenToastIds)));
        } catch (error) {
            // Ignore storage issues.
        }
    }

    function showRefundToast(result) {
        if (!refundToastStack || !result) {
            return;
        }

        const notificationId = Number(result.id || 0);
        const card = document.createElement('div');
        card.className = 'toast-card';
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');

        const title = document.createElement('div');
        title.className = 'toast-title';
        title.textContent = 'Refund Result Available';

        const message = document.createElement('div');
        message.className = 'toast-message';
        message.textContent = String(result.message || 'Your refund result has been posted.');

        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'toast-close';
        closeButton.textContent = 'Dismiss';
        closeButton.addEventListener('click', function (event) {
            event.stopPropagation();
            card.remove();
        });

        function openNotification() {
            window.location.href = notificationId > 0
                ? notificationsPageBase + '?open=' + String(notificationId)
                : notificationsPageBase;
        }

        card.addEventListener('click', openNotification);
        card.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openNotification();
            }
        });

        card.appendChild(title);
        card.appendChild(message);
        card.appendChild(closeButton);
        refundToastStack.appendChild(card);

        window.setTimeout(function () {
            card.remove();
        }, 9000);
    }

    async function pollRefundResultToasts() {
        try {
            const response = await fetch(refundResultsEndpoint, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            if (!payload || payload.success !== true || !Array.isArray(payload.results)) {
                return;
            }

            const newResults = payload.results.slice().reverse();
            newResults.forEach(function (result) {
                const id = Number(result.id || 0);
                if (!Number.isInteger(id) || id <= 0 || seenToastIds.has(id)) {
                    return;
                }

                seenToastIds.add(id);
                showRefundToast(result);
            });

            persistSeenToastIds();
        } catch (error) {
            // Silent failure for polling.
        }
    }

    loadSeenToastIds();
    window.setTimeout(pollRefundResultToasts, 4000);
    window.setInterval(pollRefundResultToasts, 2000);

    function openRefundModal(button) {
        if (!refundModal || !button) {
            return;
        }

        const orderId = button.getAttribute('data-order-id') || '0';
        const productId = button.getAttribute('data-product-id') || '0';
        const productName = button.getAttribute('data-product-name') || 'Product';

        if (refundOrderId) {
            refundOrderId.value = orderId;
        }
        if (refundProductId) {
            refundProductId.value = productId;
        }
        if (refundModalProduct) {
            refundModalProduct.textContent = productName;
        }

        refundModal.classList.add('active');
        refundModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeRefundModal() {
        if (!refundModal) {
            return;
        }

        refundModal.classList.remove('active');
        refundModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    if (refundModalClose) {
        refundModalClose.addEventListener('click', closeRefundModal);
    }

    if (refundModal) {
        refundModal.addEventListener('click', function (event) {
            if (event.target === refundModal) {
                closeRefundModal();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeRefundModal();
        }
    });
</script>

    <?= view('footer') ?>
</body>
</html>