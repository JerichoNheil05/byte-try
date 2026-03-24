<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - ByteMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #308BE5;
            --green: #249E2F;
            --black: #000000;
            --white: #FFFFFF;
            --gray: #F5F5F5;
            --text-gray: #666666;
            --light-gray: #E8E8E8;
            --border-gray: #CCCCCC;
            --nav-hover: #CCCCCC;
            --bg-light: #fafbfc;
            --button-radius: 8px;
            --button-gap: 24px;
            --grid-padding: 32px;
            --shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 24px rgba(48, 139, 229, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--bg-light);
            color: #222;
            line-height: 1.4;
        }

        body {
            padding-top: 88px;
        }

        /* === NAVIGATION BAR === */
        .navbar {
            width: 100%;
            background: var(--black);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            min-height: 56px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-list {
            display: flex;
            gap: 32px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-item {
            margin: 0;
            padding: 0;
        }

        .nav-item a {
            display: inline-flex;
            align-items: center;
            height: 56px;
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.01em;
            transition: color 0.2s ease;
            padding: 0 8px;
            outline: none;
        }

        .nav-item a:focus,
        .nav-item a:hover {
            color: var(--nav-hover);
        }

        .nav-item a.active {
            color: var(--blue);
            border-bottom: 2px solid var(--blue);
        }

        /* === MAIN CONTAINER === */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 48px var(--grid-padding) 48px var(--grid-padding);
            min-height: 100vh;
        }

        .page-header {
            text-align: left;
            margin-bottom: 48px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 8px 0;
            color: var(--black);
        }

        .page-subtitle {
            font-size: 16px;
            font-weight: 400;
            color: var(--text-gray);
            margin: 0;
        }

        .page-subtitle .count {
            color: var(--green);
            font-weight: 600;
        }

        /* === NOTIFICATIONS SECTION === */
        .notifications-container {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .notification-section {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .notification-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--black);
            margin-bottom: 16px;
            padding-bottom: 8px;
        }

        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* === NOTIFICATION ITEM === */
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
            background: var(--white);
            border-bottom: 1px solid var(--light-gray);
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #fafbfc;
            box-shadow: var(--shadow);
        }

        .notification-item:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: -2px;
        }

        .notification-item.unread {
            background-color: rgba(48, 139, 229, 0.02);
        }

        .notification-item.unread:hover {
            background-color: rgba(48, 139, 229, 0.05);
        }

        /* === NOTIFICATION INDICATOR (DOT) === */
        .notification-indicator {
            width: 12px;
            height: 12px;
            min-width: 12px;
            min-height: 12px;
            border-radius: 50%;
            background: var(--blue);
            margin-top: 4px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .notification-item.unread .notification-indicator {
            opacity: 1;
        }

        /* === NOTIFICATION CONTENT === */
        .notification-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .notification-message {
            font-size: 16px;
            font-weight: 400;
            color: var(--black);
            line-height: 1.5;
        }

        .notification-status {
            color: var(--green);
            font-weight: 600;
        }

        .notification-time {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-gray);
        }

        .notification-timestamp {
            font-size: 12px;
            font-weight: 500;
            color: #8a8a8a;
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 60px 32px;
            background: var(--white);
            border-radius: var(--button-radius);
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--black);
            margin-bottom: 8px;
        }

        .empty-state-text {
            font-size: 14px;
            color: var(--text-gray);
            margin-bottom: 24px;
        }

        .refund-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(15, 23, 42, 0.45);
            z-index: 1200;
            padding: 20px;
        }

        .refund-modal.active {
            display: flex;
        }

        .refund-modal-card {
            width: min(720px, 100%);
            max-height: calc(100vh - 40px);
            overflow: auto;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
            padding: 18px;
        }

        .refund-modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .refund-modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .refund-modal-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
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
            flex-shrink: 0;
        }

        .refund-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .refund-detail-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 12px;
            background: #f8fafc;
        }

        .refund-detail-item.wide {
            grid-column: 1 / -1;
        }

        .refund-detail-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .refund-detail-value {
            font-size: 14px;
            color: #111827;
            line-height: 1.45;
            word-break: break-word;
        }

        .refund-product-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .refund-tracker {
            display: none;
            margin: 14px 0 18px;
            padding: 4px 2px 0;
        }

        .refund-tracker.visible {
            display: block;
        }

        .refund-tracker-steps {
            position: relative;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            align-items: start;
        }

        .refund-tracker-fill {
            display: none;
        }

        .refund-tracker-step {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .refund-tracker-step::after {
            content: '';
            position: absolute;
            top: 24px;
            left: calc(50% + 32px);
            width: calc(100% - 58px);
            height: 6px;
            border-radius: 999px;
            background: #c6c6c6;
            z-index: 0;
            pointer-events: none;
        }

        .refund-tracker-step:last-child::after {
            display: none;
        }

        .refund-tracker-dot {
            width: 56px;
            height: 56px;
            margin: 0 auto 10px;
            border-radius: 50%;
            background: #bdbdbd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 2;
        }

        .refund-tracker-label {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
            max-width: 130px;
            margin: 0 auto;
        }

        .refund-tracker-step.is-complete .refund-tracker-dot,
        .refund-tracker-step.is-active .refund-tracker-dot {
            background: #28a92f;
        }

        .refund-tracker-step.is-complete::after,
        .refund-tracker-step.is-active::after {
            background: #28a92f;
        }

        .refund-tracker-step.is-pending .refund-tracker-dot {
            background: #bdbdbd;
        }

        .refund-product-thumb {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            object-fit: cover;
            display: block;
        }

        .refund-product-thumb.is-hidden {
            display: none;
        }

        .refund-product-thumb-fallback {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .refund-product-thumb-fallback.is-hidden {
            display: none;
        }

        @media (max-width: 760px) {
            .refund-tracker-steps {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px 14px;
            }

            .refund-tracker-step::after {
                display: none;
            }

            .refund-tracker-dot {
                width: 50px;
                height: 50px;
                font-size: 21px;
            }
        }

        .refund-notify-seller-wrap {
            margin-top: 14px;
            display: none;
        }

        .refund-notify-seller-wrap.visible {
            display: block;
        }

        .btn-notify-seller {
            width: 100%;
            padding: 11px 16px;
            border-radius: 10px;
            border: none;
            background: #16a34a;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.18s, opacity 0.18s;
        }

        .btn-notify-seller:hover:not(:disabled) {
            background: #15803d;
        }

        .btn-notify-seller:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .btn-notify-seller.notified {
            background: #6b7280;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 768px) {
            .container {
                padding: calc(56px + 24px) 16px 24px 16px;
            }

            .page-title {
                font-size: 2rem;
            }

            .notification-item {
                padding: 16px;
            }

            .notification-message {
                font-size: 14px;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                min-height: auto;
            }

            .nav-list {
                gap: 16px;
                padding: 12px 16px;
            }

            .nav-item a {
                font-size: 12px;
                height: auto;
                padding: 8px 4px;
            }

            .container {
                padding: calc(56px + 16px) 12px 16px 12px;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .page-header {
                margin-bottom: 32px;
            }

            .notification-item {
                padding: 16px 12px;
                gap: 12px;
            }

            .notification-section-title {
                font-size: 16px;
                margin-bottom: 12px;
            }

            .notifications-container {
                gap: 32px;
            }

            .notification-message {
                font-size: 13px;
            }

            .empty-state {
                padding: 40px 24px;
            }
        }

        /* === ACCESSIBILITY === */
        .skiplink {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--black);
            color: var(--white);
            padding: 8px;
            text-decoration: none;
            z-index: 100;
        }

        .skiplink:focus {
            top: 0;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        /* === ANIMATIONS === */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-item {
            animation: fadeIn 0.3s ease-out;
        }

        .notification-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .notification-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .notification-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .notification-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .notification-item:nth-child(5) {
            animation-delay: 0.25s;
        }

        .notification-item:nth-child(n+6) {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <!-- SKIP TO CONTENT LINK -->
    <a href="#main-content" class="skiplink">Skip to main content</a>

    <!-- MAIN CONTENT -->
    <main class="container" id="main-content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1 class="page-title">Notifications</h1>
            <p class="page-subtitle">You have <span class="count"><?= isset($todayCount) ? $todayCount : 0 ?> notifications</span> today.</p>
        </div>

        <!-- SUCCESS/ERROR ALERTS -->
        <?php if (session()->has('success')): ?>
            <div style="padding: 16px; border-radius: 8px; margin-bottom: 24px; background: rgba(36, 158, 47, 0.1); border: 1px solid var(--green); color: var(--green); font-size: 14px;">
                <?= htmlspecialchars(session('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div style="padding: 16px; border-radius: 8px; margin-bottom: 24px; background: rgba(231, 76, 60, 0.1); border: 1px solid #e74c3c; color: #e74c3c; font-size: 14px;">
                <?= htmlspecialchars(session('error')) ?>
            </div>
        <?php endif; ?>

        <!-- NOTIFICATIONS CONTAINER -->
        <div class="notifications-container" role="main" aria-label="Notifications">

            <!-- TODAY SECTION -->
            <?php if (!empty($todayNotifications) || (isset($showPlaceholder) && $showPlaceholder)): ?>
                <section class="notification-section" aria-labelledby="today-section-title">
                    <h2 class="notification-section-title" id="today-section-title">Today</h2>
                    
                    <?php if (!empty($todayNotifications)): ?>
                        <ul class="notification-list" role="list">
                            <?php foreach ($todayNotifications as $notification): ?>
                                <li role="listitem">
                                                <a href="<?= base_url('notifications/read/' . ($notification['id'] ?? '#')) ?>"
                                       class="notification-item <?= (isset($notification['read']) && !$notification['read']) ? 'unread' : '' ?>"
                                                    aria-label="Notification: <?= htmlspecialchars($notification['message'] ?? '') ?>"
                                                    data-notification-id="<?= (int) ($notification['id'] ?? 0) ?>"
                                                    data-notification-type="<?= htmlspecialchars((string) ($notification['type'] ?? ''), ENT_QUOTES) ?>"
                                                    data-read-url="<?= base_url('notifications/read/' . ($notification['id'] ?? 0)) ?>"
                                                    <?php if (!empty($notification['refund_details']) && is_array($notification['refund_details'])): ?>data-refund='<?= htmlspecialchars((string) json_encode($notification['refund_details'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>'<?php endif; ?>
                                                    <?php if (!empty($notification['order_details']) && is_array($notification['order_details'])): ?>data-order='<?= htmlspecialchars((string) json_encode($notification['order_details'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>'<?php endif; ?>>
                                        
                                        <div class="notification-indicator" aria-hidden="true"></div>
                                        
                                        <div class="notification-content">
                                            <div class="notification-message">
                                                <span class="notification-status">Successfully</span> <?= htmlspecialchars($notification['message'] ?? '') ?>
                                            </div>
                                            <time class="notification-time" datetime="<?= htmlspecialchars($notification['created_at'] ?? '') ?>">
                                                <?= isset($notification['time_ago']) ? htmlspecialchars($notification['time_ago']) : 'Just now' ?>
                                            </time>
                                            <div class="notification-timestamp">
                                                <?= date('M j, Y g:i A', strtotime($notification['created_at'] ?? 'now')) ?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <h3 class="empty-state-title">No Notifications Today</h3>
                            <p class="empty-state-text">You're all caught up! Come back later for updates.</p>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

            <!-- THIS WEEK SECTION -->
            <?php if (!empty($weekNotifications) || (isset($showPlaceholder) && $showPlaceholder)): ?>
                <section class="notification-section" aria-labelledby="week-section-title">
                    <h2 class="notification-section-title" id="week-section-title">This Week</h2>
                    
                    <?php if (!empty($weekNotifications)): ?>
                        <ul class="notification-list" role="list">
                            <?php foreach ($weekNotifications as $notification): ?>
                                <li role="listitem">
                                                <a href="<?= base_url('notifications/read/' . ($notification['id'] ?? '#')) ?>"
                                       class="notification-item <?= (isset($notification['read']) && !$notification['read']) ? 'unread' : '' ?>"
                                                    aria-label="Notification: <?= htmlspecialchars($notification['message'] ?? '') ?>"
                                                    data-notification-id="<?= (int) ($notification['id'] ?? 0) ?>"
                                                    data-notification-type="<?= htmlspecialchars((string) ($notification['type'] ?? ''), ENT_QUOTES) ?>"
                                                    data-read-url="<?= base_url('notifications/read/' . ($notification['id'] ?? 0)) ?>"
                                                    <?php if (!empty($notification['refund_details']) && is_array($notification['refund_details'])): ?>data-refund='<?= htmlspecialchars((string) json_encode($notification['refund_details'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>'<?php endif; ?>
                                                    <?php if (!empty($notification['order_details']) && is_array($notification['order_details'])): ?>data-order='<?= htmlspecialchars((string) json_encode($notification['order_details'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>'<?php endif; ?>>
                                        
                                        <div class="notification-indicator" aria-hidden="true"></div>
                                        
                                        <div class="notification-content">
                                            <div class="notification-message">
                                                <span class="notification-status">Successfully</span> <?= htmlspecialchars($notification['message'] ?? '') ?>
                                            </div>
                                            <time class="notification-time" datetime="<?= htmlspecialchars($notification['created_at'] ?? '') ?>">
                                                <?= isset($notification['date_formatted']) ? htmlspecialchars($notification['date_formatted']) : date('F j', strtotime($notification['created_at'] ?? '')) ?>
                                            </time>
                                            <div class="notification-timestamp">
                                                <?= date('M j, Y g:i A', strtotime($notification['created_at'] ?? 'now')) ?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <h3 class="empty-state-title">No Notifications This Week</h3>
                            <p class="empty-state-text">Check back soon for activity updates.</p>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

            <!-- NO NOTIFICATIONS AT ALL -->
            <?php if (empty($todayNotifications) && empty($weekNotifications) && (!isset($showPlaceholder) || !$showPlaceholder)): ?>
                <section class="notification-section">
                    <div class="empty-state">
                        <div class="empty-state-icon">🔔</div>
                        <h2 class="empty-state-title">No Notifications Yet</h2>
                        <p class="empty-state-text">You don't have any notifications yet. Check back when you have completed actions or received updates.</p>
                    </div>
                </section>
            <?php endif; ?>

        </div>
    </main>

    <div class="refund-modal" id="refundDetailModal" aria-hidden="true">
        <div class="refund-modal-card" role="dialog" aria-modal="true" aria-labelledby="refundDetailModalTitle">
            <div class="refund-modal-head">
                <div>
                    <div class="refund-modal-title" id="refundDetailModalTitle">Notification Details</div>
                    <div class="refund-modal-subtitle" id="refundDetailModalSubtitle">Refund details</div>
                </div>
                <button type="button" class="refund-modal-close" id="refundDetailModalClose" aria-label="Close refund modal">×</button>
            </div>

            <div class="refund-product-preview">
                <img src="" alt="Product thumbnail" id="refundDetailThumb" class="refund-product-thumb is-hidden">
                <div id="refundDetailThumbFallback" class="refund-product-thumb-fallback">📦</div>
            </div>

            <div class="refund-detail-grid">
                <div class="refund-detail-item" id="refundDetailItemProduct">
                    <div class="refund-detail-label">Product</div>
                    <div class="refund-detail-value" id="refundDetailProduct">-</div>
                </div>
                <div class="refund-detail-item" id="refundDetailItemOrderId">
                    <div class="refund-detail-label">Order ID</div>
                    <div class="refund-detail-value" id="refundDetailOrderId">-</div>
                </div>
                <div class="refund-detail-item" id="refundDetailItemPaymentId">
                    <div class="refund-detail-label">Payment ID</div>
                    <div class="refund-detail-value" id="refundDetailPaymentId">-</div>
                </div>
                <div class="refund-detail-item" id="refundDetailItemDecision">
                    <div class="refund-detail-label">Decision</div>
                    <div class="refund-detail-value" id="refundDetailDecision">-</div>
                </div>
                <div class="refund-detail-item" id="refundDetailItemReason">
                    <div class="refund-detail-label">Reason</div>
                    <div class="refund-detail-value" id="refundDetailReason">-</div>
                </div>
                <div class="refund-detail-item" id="refundDetailItemCreatedAt">
                    <div class="refund-detail-label">Requested At</div>
                    <div class="refund-detail-value" id="refundDetailCreatedAt">-</div>
                </div>
                <div class="refund-detail-item wide" id="refundDetailItemNotes">
                    <div class="refund-detail-label">Buyer Notes</div>
                    <div class="refund-detail-value" id="refundDetailNotes">-</div>
                </div>
            </div>

            <div class="refund-tracker" id="refundProcessTracker">
                <div class="refund-tracker-steps">
                    <div class="refund-tracker-fill" id="refundTrackerFill"></div>
                    <div class="refund-tracker-step" id="refundTrackerStep1">
                        <div class="refund-tracker-dot">1</div>
                        <div class="refund-tracker-label">Request Refund</div>
                    </div>
                    <div class="refund-tracker-step" id="refundTrackerStep2">
                        <div class="refund-tracker-dot">2</div>
                        <div class="refund-tracker-label">Notify Seller</div>
                    </div>
                    <div class="refund-tracker-step" id="refundTrackerStep3">
                        <div class="refund-tracker-dot">3</div>
                        <div class="refund-tracker-label">Create Refund by Seller</div>
                    </div>
                    <div class="refund-tracker-step" id="refundTrackerStep4">
                        <div class="refund-tracker-dot">4</div>
                        <div class="refund-tracker-label">Refund Completed</div>
                    </div>
                </div>
            </div>

            <div class="refund-notify-seller-wrap" id="refundNotifySellerWrap">
                <button type="button" class="btn-notify-seller" id="refundNotifySellerBtn">
                    <span id="refundNotifySellerBtnText">&#128276; Notify Seller to Process Refund</span>
                </button>
            </div>

            <div class="refund-notify-seller-wrap" id="refundCreateRefundWrap">
                <a href="#" class="btn-notify-seller" id="refundCreateRefundBtn" style="text-decoration:none">
                    <span id="refundCreateRefundBtnText">&#128178; Create Refund</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        const refundDetailModal = document.getElementById('refundDetailModal');
        const refundDetailModalClose = document.getElementById('refundDetailModalClose');
        const openNotificationId = <?= (int) ($openNotificationId ?? 0) ?>;
        const notifySellerBaseUrl  = '<?= base_url('notifications/notify_seller') ?>';
        const createRefundBaseUrl  = '<?= base_url('orders/refund/process') ?>';
        const csrfHash = '<?= csrf_hash() ?>';
        let currentRefundId = 0;

        function formatReadableLabel(value) {
            const text = String(value || '').trim();
            if (!text) {
                return '-';
            }
            return text
                .replace(/_/g, ' ')
                .toLowerCase()
                .replace(/\b\w/g, function (char) { return char.toUpperCase(); });
        }

        function applyText(id, value) {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        }

        function setVisible(id, isVisible, displayValue = '') {
            const element = document.getElementById(id);
            if (!element) {
                return;
            }

            element.style.display = isVisible ? displayValue : 'none';
        }

        function formatDateTime(value) {
            const text = String(value || '').trim();
            if (!text) {
                return '-';
            }

            const date = new Date(text.replace(' ', 'T'));
            if (Number.isNaN(date.getTime())) {
                return text;
            }

            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit'
            });
        }

        function updateRefundTracker(details) {
            const tracker = document.getElementById('refundProcessTracker');
            const decision = String(details.refund_decision || '').toUpperCase();

            if (!tracker) {
                return;
            }

            if (decision !== 'APPROVED') {
                tracker.classList.remove('visible');
                return;
            }

            tracker.classList.add('visible');

            const sellerNotified = String(details.seller_reverse_notified_at || '').trim() !== ''
                || String(details.seller_refund_processed_at || '').trim() !== '';
            const refundProcessed = String(details.seller_refund_processed_at || '').trim() !== '';
            const refundedStatuses = ['refunded', 'refund'];
            const refundCompleted = refundedStatuses.includes(String(details.item_status || '').toLowerCase().trim())
                || refundedStatuses.includes(String(details.order_status || '').toLowerCase().trim())
                || refundedStatuses.includes(String(details.payment_status || '').toLowerCase().trim())
                || refundProcessed;

            const steps = [
                true,
                sellerNotified,
                refundProcessed,
                refundCompleted,
            ];

            let activeIndex = 0;
            for (let index = 0; index < steps.length; index += 1) {
                if (!steps[index]) {
                    activeIndex = index;
                    break;
                }
                activeIndex = index;
            }

            for (let index = 0; index < steps.length; index += 1) {
                const stepEl = document.getElementById('refundTrackerStep' + String(index + 1));
                if (!stepEl) {
                    continue;
                }

                stepEl.classList.remove('is-complete', 'is-active', 'is-pending');

                if (steps[index]) {
                    stepEl.classList.add('is-complete');
                } else if (index === activeIndex) {
                    stepEl.classList.add('is-active');
                } else {
                    stepEl.classList.add('is-pending');
                }
            }

        }

        function openRefundDetailModal(details) {
            if (!refundDetailModal || !details) {
                return;
            }

            const createdAtLabel = document.querySelector('#refundDetailItemCreatedAt .refund-detail-label');
            if (createdAtLabel) {
                createdAtLabel.textContent = 'Requested At';
            }

            applyText('refundDetailModalTitle', 'Refund Notification');
            setVisible('refundDetailItemPaymentId', true);
            setVisible('refundDetailItemDecision', true);
            setVisible('refundDetailItemReason', true);
            setVisible('refundDetailItemNotes', true);
            setVisible('refundProcessTracker', true, 'block');

            const thumb = document.getElementById('refundDetailThumb');
            const thumbFallback = document.getElementById('refundDetailThumbFallback');
            const thumbUrl = String(details.product_thumbnail_url || '').trim();

            if (thumb && thumbFallback) {
                if (thumbUrl !== '') {
                    thumb.src = thumbUrl;
                    thumb.classList.remove('is-hidden');
                    thumbFallback.classList.add('is-hidden');

                    thumb.onerror = function () {
                        thumb.classList.add('is-hidden');
                        thumbFallback.classList.remove('is-hidden');
                    };
                } else {
                    thumb.src = '';
                    thumb.classList.add('is-hidden');
                    thumbFallback.classList.remove('is-hidden');
                }
            }

            applyText(
                'refundDetailModalSubtitle',
                'Order #' + String(details.order_id || '-') + ' • Refund #' + String(details.refund_id || '-')
            );
            applyText('refundDetailProduct', String(details.product_title || 'Product'));
            applyText('refundDetailOrderId', String(details.order_id || '-'));
            applyText('refundDetailPaymentId', String(details.payment_id || details.order_number || details.order_id || '-'));
            applyText('refundDetailDecision', formatReadableLabel(details.refund_decision || '-'));
            applyText('refundDetailReason', formatReadableLabel(details.reason || '-'));
            applyText('refundDetailCreatedAt', formatDateTime(details.created_at || ''));
            applyText('refundDetailNotes', String(details.buyer_notes || '').trim() || 'No additional notes.');
            updateRefundTracker(details);

            // Notify-seller button (buyer-side) — only for approved refunds, buyer view.
            currentRefundId = Number(details.refund_id || 0);
            const notifyWrap = document.getElementById('refundNotifySellerWrap');
            const notifyBtn  = document.getElementById('refundNotifySellerBtn');
            const notifyText = document.getElementById('refundNotifySellerBtnText');

            // Create-refund button (seller-side) — only for refund_reverse_payment notifications.
            const createWrap = document.getElementById('refundCreateRefundWrap');
            const createBtn  = document.getElementById('refundCreateRefundBtn');
            const createText = document.getElementById('refundCreateRefundBtnText');

            if (notifyWrap) {
                notifyWrap.style.display = '';
            }
            if (createWrap) {
                createWrap.style.display = '';
            }

            const isSeller   = Boolean(details.is_seller_notification);
            const decision   = String(details.refund_decision || '').toUpperCase();

            if (notifyWrap && notifyBtn && notifyText) {
                const alreadySent = String(details.seller_reverse_notified_at || '').trim() !== '';
                const showNotify  = !isSeller && decision === 'APPROVED';

                if (showNotify) {
                    notifyWrap.classList.add('visible');
                    notifyBtn.disabled = alreadySent;
                    notifyBtn.classList.toggle('notified', alreadySent);
                    notifyText.textContent = alreadySent
                        ? '\u2713 Seller Already Notified'
                        : '\uD83D\uDD14 Notify Seller to Process Refund';
                } else {
                    notifyWrap.classList.remove('visible');
                }
            }

            if (createWrap && createBtn && createText) {
                const alreadyProcessed = String(details.seller_refund_processed_at || '').trim() !== '';

                if (isSeller && decision === 'APPROVED') {
                    createWrap.classList.add('visible');
                    createBtn.href = createRefundBaseUrl + '/' + currentRefundId;

                    if (alreadyProcessed) {
                        createBtn.classList.add('notified');
                        createBtn.style.pointerEvents = 'none';
                        createText.textContent = '\u2713 Refund Already Processed';
                    } else {
                        createBtn.classList.remove('notified');
                        createBtn.style.pointerEvents = '';
                        createText.textContent = '\uD83D\uDCB8 Create Refund';
                    }
                } else {
                    createWrap.classList.remove('visible');
                }
            }

            refundDetailModal.classList.add('active');
            refundDetailModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function openOrderDetailModal(details) {
            if (!refundDetailModal || !details) {
                return;
            }

            const thumb = document.getElementById('refundDetailThumb');
            const thumbFallback = document.getElementById('refundDetailThumbFallback');
            const thumbUrl = String(details.product_thumbnail_url || '').trim();

            if (thumb && thumbFallback) {
                if (thumbUrl !== '') {
                    thumb.src = thumbUrl;
                    thumb.classList.remove('is-hidden');
                    thumbFallback.classList.add('is-hidden');

                    thumb.onerror = function () {
                        thumb.classList.add('is-hidden');
                        thumbFallback.classList.remove('is-hidden');
                    };
                } else {
                    thumb.src = '';
                    thumb.classList.add('is-hidden');
                    thumbFallback.classList.remove('is-hidden');
                }
            }

            applyText('refundDetailModalTitle', 'Order Details');
            applyText('refundDetailModalSubtitle', 'Order information');
            applyText('refundDetailProduct', String(details.product_title || 'Product'));
            applyText('refundDetailOrderId', String(details.order_number || details.order_id || '-'));
            applyText('refundDetailCreatedAt', formatDateTime(details.bought_at || ''));

            const createdAtLabel = document.querySelector('#refundDetailItemCreatedAt .refund-detail-label');
            if (createdAtLabel) {
                createdAtLabel.textContent = 'Time Bought';
            }

            setVisible('refundDetailItemProduct', true);
            setVisible('refundDetailItemOrderId', true);
            setVisible('refundDetailItemCreatedAt', true);

            setVisible('refundDetailItemPaymentId', false);
            setVisible('refundDetailItemDecision', false);
            setVisible('refundDetailItemReason', false);
            setVisible('refundDetailItemNotes', false);
            setVisible('refundProcessTracker', false);

            const notifyWrap = document.getElementById('refundNotifySellerWrap');
            const createWrap = document.getElementById('refundCreateRefundWrap');
            if (notifyWrap) {
                notifyWrap.classList.remove('visible');
                notifyWrap.style.display = '';
            }
            if (createWrap) {
                createWrap.classList.remove('visible');
                createWrap.style.display = '';
            }

            refundDetailModal.classList.add('active');
            refundDetailModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeRefundDetailModal() {
            if (!refundDetailModal) {
                return;
            }

            refundDetailModal.classList.remove('active');
            refundDetailModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        async function notifySellerRefund() {
            if (currentRefundId <= 0) {
                return;
            }

            const notifyBtn  = document.getElementById('refundNotifySellerBtn');
            const notifyText = document.getElementById('refundNotifySellerBtnText');

            if (!notifyBtn || notifyBtn.disabled) {
                return;
            }

            notifyBtn.disabled = true;
            if (notifyText) {
                notifyText.textContent = 'Sending\u2026';
            }

            try {
                const resp = await fetch(notifySellerBaseUrl + '/' + currentRefundId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfHash
                    },
                    credentials: 'same-origin'
                });

                const data = await resp.json().catch(function() { return {}; });

                if (resp.ok && data.success) {
                    notifyBtn.classList.add('notified');
                    if (notifyText) {
                        notifyText.textContent = '\u2713 Seller Notified';
                    }
                } else {
                    const msg = String(data.message || 'Failed to notify seller. Please try again.');
                    if (notifyText) {
                        notifyText.textContent = '\uD83D\uDD14 Notify Seller to Process Refund';
                    }
                    notifyBtn.disabled = false;
                    alert(msg);
                }
            } catch (err) {
                if (notifyText) {
                    notifyText.textContent = '\uD83D\uDD14 Notify Seller to Process Refund';
                }
                notifyBtn.disabled = false;
                alert('Network error. Please check your connection and try again.');
            }
        }

        (function () {
            const btn = document.getElementById('refundNotifySellerBtn');
            if (btn) {
                btn.addEventListener('click', notifySellerRefund);
            }
        })();

        async function markNotificationRead(item) {
            const readUrl = item.getAttribute('data-read-url');
            if (!readUrl) {
                return;
            }

            try {
                await fetch(readUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
            } catch (error) {
                // Ignore mark-as-read failures in modal flow.
            }

            item.classList.remove('unread');
            item.querySelector('.notification-indicator')?.style.setProperty('opacity', '0');
        }

        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const refundPayload = this.getAttribute('data-refund');
                const orderPayload = this.getAttribute('data-order');
                if (!refundPayload && !orderPayload) {
                    return;
                }

                e.preventDefault();
                markNotificationRead(this);

                try {
                    if (refundPayload) {
                        openRefundDetailModal(JSON.parse(refundPayload));
                    } else {
                        openOrderDetailModal(JSON.parse(orderPayload));
                    }
                } catch (error) {
                    window.location.href = this.getAttribute('href') || '<?= base_url('notifications') ?>';
                }
            });
        });

        /**
         * Keyboard navigation for notification list
         */
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach((item, index) => {
            item.addEventListener('keydown', function(e) {
                let nextIndex;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    nextIndex = (index + 1) % notificationItems.length;
                    notificationItems[nextIndex].focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    nextIndex = (index - 1 + notificationItems.length) % notificationItems.length;
                    notificationItems[nextIndex].focus();
                } else if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        // Set initial focus on first notification if available
        if (notificationItems.length > 0) {
            notificationItems[0].setAttribute('tabindex', '0');
        }

        // Relative time updater (updates "X minutes ago" every minute)
        function updateRelativeTimes() {
            const timeElements = document.querySelectorAll('time');
            
            timeElements.forEach(timeEl => {
                const dateTime = timeEl.getAttribute('datetime');
                if (!dateTime) return;

                const date = new Date(dateTime);
                const now = new Date();
                const seconds = Math.floor((now - date) / 1000);

                let timeText = 'Just now';
                if (seconds < 60) {
                    timeText = 'Just now';
                } else if (seconds < 3600) {
                    const minutes = Math.floor(seconds / 60);
                    timeText = minutes === 1 ? '1 minute ago' : `${minutes} minutes ago`;
                } else if (seconds < 86400) {
                    const hours = Math.floor(seconds / 3600);
                    timeText = hours === 1 ? '1 hour ago' : `${hours} hours ago`;
                } else {
                    timeText = date.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
                }

                if (timeEl.textContent !== timeText) {
                    timeEl.textContent = timeText;
                }
            });
        }

        // Update relative times on page load
        updateRelativeTimes();

        // Update relative times every minute
        setInterval(updateRelativeTimes, 60000);

        if (refundDetailModalClose) {
            refundDetailModalClose.addEventListener('click', closeRefundDetailModal);
        }

        if (refundDetailModal) {
            refundDetailModal.addEventListener('click', function (event) {
                if (event.target === refundDetailModal) {
                    closeRefundDetailModal();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeRefundDetailModal();
            }
        });

        if (openNotificationId > 0) {
            const autoOpenItem = document.querySelector('.notification-item[data-notification-id="' + String(openNotificationId) + '"]');
            if (autoOpenItem) {
                window.setTimeout(function () {
                    autoOpenItem.click();
                }, 150);
            }
        }
    </script>
</body>
</html>
