<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Refund - Byte Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #ececec;
            color: #121212;
            padding-top: 88px;
        }

        .page-wrap {
            max-width: 640px;
            margin: 0 auto;
            padding: 28px 20px 72px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 8px 12px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #d9d9d9;
            color: #111111;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.18s, border-color 0.18s;
        }
        .back-btn:hover { background: #f7f7f7; border-color: #c8c8c8; }
        .back-btn svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }

        .card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            padding: 28px 28px 32px;
        }

        .card-head {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #fef3c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            line-height: 1.3;
        }

        .card-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Product preview */
        .product-preview {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 20px;
        }

        .product-thumb {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            object-fit: cover;
            display: block;
            flex-shrink: 0;
        }

        .product-thumb-fallback {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .product-info-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .product-info-sub {
            font-size: 12px;
            color: #6b7280;
        }

        /* Detail grid */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 24px;
        }

        .detail-item {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 12px;
        }

        .detail-item.wide { grid-column: 1 / -1; }

        .detail-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 14px;
            color: #111827;
            line-height: 1.45;
            word-break: break-word;
        }

        /* Alert banners */
        .alert {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-warn { background: #fef9ec; border: 1px solid #f5d66d; color: #92400e; }
        .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; }
        .alert-error { background: #fef2f2; border: 1px solid #fca5a5; color: #b91c1c; }
        .alert-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }

        /* Confirmation form */
        .confirm-section {
            border-top: 1px solid #f0f0f0;
            padding-top: 22px;
        }

        .confirm-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 14px;
        }

        .confirm-checks {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .confirm-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: #374151;
            cursor: pointer;
        }

        .confirm-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #16a34a;
            flex-shrink: 0;
        }

        .btn-submit {
            width: 100%;
            padding: 13px 16px;
            border-radius: 12px;
            border: none;
            background: #16a34a;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.18s, opacity 0.18s;
            font-family: 'Poppins', Arial, sans-serif;
        }
        .btn-submit:hover:not(:disabled) { background: #15803d; }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Processed banner */
        .processed-banner {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .processed-icon { font-size: 28px; flex-shrink: 0; }

        .processed-text-title {
            font-size: 15px;
            font-weight: 700;
            color: #15803d;
            margin-bottom: 3px;
        }

        .processed-text-sub { font-size: 13px; color: #166534; }

        @media (max-width: 600px) {
            .page-wrap { padding: 20px 14px 60px; }
            .card { padding: 20px 16px 24px; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="page-wrap">
    <a href="<?= base_url('orders') ?>" class="back-btn">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Back to Orders
    </a>

    <div class="card">
        <div class="card-head">
            <div class="card-icon">💸</div>
            <div>
                <div class="card-title">Process Refund</div>
                <div class="card-subtitle">Order #<?= htmlspecialchars((string) ($refund['order_number'] ?? $refund['order_id'] ?? ''), ENT_QUOTES) ?></div>
            </div>
        </div>

        <?php // Flash messages ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><span class="alert-icon">✅</span><?= htmlspecialchars((string) session()->getFlashdata('success'), ENT_QUOTES) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><span class="alert-icon">⚠️</span><?= htmlspecialchars((string) session()->getFlashdata('error'), ENT_QUOTES) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-warn"><span class="alert-icon">ℹ️</span><?= htmlspecialchars((string) session()->getFlashdata('info'), ENT_QUOTES) ?></div>
        <?php endif; ?>

        <!-- Product preview -->
        <div class="product-preview">
            <?php if (!empty($thumbnailUrl)): ?>
                <img src="<?= htmlspecialchars($thumbnailUrl, ENT_QUOTES) ?>" alt="Product thumbnail" class="product-thumb"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="product-thumb-fallback" style="display:none">📦</div>
            <?php else: ?>
                <div class="product-thumb-fallback">📦</div>
            <?php endif; ?>
            <div>
                <div class="product-info-title"><?= htmlspecialchars((string) ($refund['product_title'] ?? 'Product'), ENT_QUOTES) ?></div>
                <div class="product-info-sub">
                    Buyer: <?= htmlspecialchars((string) ($refund['buyer_name'] ?? 'Unknown'), ENT_QUOTES) ?>
                    <?php if (!empty($refund['buyer_email'])): ?>
                        &mdash; <?= htmlspecialchars((string) $refund['buyer_email'], ENT_QUOTES) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Refund details -->
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Order ID</div>
                <div class="detail-value"><?= htmlspecialchars((string) ($refund['order_number'] ?? $refund['order_id'] ?? '-'), ENT_QUOTES) ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Decision</div>
                <div class="detail-value" style="color:#16a34a;font-weight:700">
                    <?= htmlspecialchars(ucfirst(strtolower((string) ($refund['refund_decision'] ?? '-'))), ENT_QUOTES) ?>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Reason</div>
                <div class="detail-value"><?= htmlspecialchars(str_replace('_', ' ', ucwords(strtolower((string) ($refund['reason'] ?? '-')), '_')), ENT_QUOTES) ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Requested At</div>
                <div class="detail-value"><?= !empty($refund['created_at']) ? date('M j, Y g:i A', strtotime((string) $refund['created_at'])) : '-' ?></div>
            </div>
            <?php if (!empty($refund['buyer_notes'])): ?>
                <div class="detail-item wide">
                    <div class="detail-label">Buyer Notes</div>
                    <div class="detail-value"><?= htmlspecialchars((string) $refund['buyer_notes'], ENT_QUOTES) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Already processed -->
        <?php if (!empty($refund['seller_refund_processed_at'])): ?>
            <div class="processed-banner">
                <div class="processed-icon">✅</div>
                <div>
                    <div class="processed-text-title">Refund Processed</div>
                    <div class="processed-text-sub">
                        You marked this refund as processed on
                        <?= date('M j, Y g:i A', strtotime((string) $refund['seller_refund_processed_at'])) ?>.
                        The buyer has been notified.
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Confirmation form -->
            <div class="confirm-section">
                <div class="confirm-title">Confirm Reverse Payment</div>
                <div class="alert alert-warn">
                    <span class="alert-icon">⚠️</span>
                    Please process the refund payment to the buyer through your payment provider before confirming below. This action cannot be undone.
                </div>

                <form method="POST" action="<?= base_url('orders/refund/process/' . $refundId) ?>" id="refundProcessForm">
                    <?= csrf_field() ?>
                    <div class="confirm-checks">
                        <label class="confirm-check">
                            <input type="checkbox" name="check_sent" id="checkSent" required>
                            <span>I have sent the reverse payment to the buyer.</span>
                        </label>
                        <label class="confirm-check">
                            <input type="checkbox" name="check_understand" id="checkUnderstand" required>
                            <span>I understand this will notify the buyer and cannot be reversed.</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit" id="submitRefundBtn" disabled>
                        ✅ Confirm &amp; Notify Buyer
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    (function () {
        const checkSent       = document.getElementById('checkSent');
        const checkUnderstand = document.getElementById('checkUnderstand');
        const submitBtn       = document.getElementById('submitRefundBtn');

        if (!checkSent || !checkUnderstand || !submitBtn) {
            return;
        }

        function updateBtn() {
            submitBtn.disabled = !(checkSent.checked && checkUnderstand.checked);
        }

        checkSent.addEventListener('change', updateBtn);
        checkUnderstand.addEventListener('change', updateBtn);

        document.getElementById('refundProcessForm')?.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing\u2026';
        });
    })();
</script>

</body>
</html>
