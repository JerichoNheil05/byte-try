<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ByteMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #ececec;
            color: #101010;
        }

        body { padding-top: 80px; }

        /* === PAGE WRAPPER === */
        .cart-page {
            position: relative;
            min-height: calc(100vh - 80px);
            padding: 48px 20px 64px;
            overflow: hidden;
        }

        /* === DECORATIVE SHAPES === */
        .shape { position: absolute; z-index: 0; pointer-events: none; }

        .shape-tri-tl {
            top: 30px; left: -18px;
            width: 0; height: 0;
            border-left: 52px solid transparent;
            border-right: 52px solid transparent;
            border-bottom: 80px solid #c3d8c5;
            transform: rotate(-20deg);
        }

        .shape-tri-tr {
            top: 60px; right: 20px;
            width: 0; height: 0;
            border-left: 40px solid transparent;
            border-right: 40px solid transparent;
            border-bottom: 62px solid #c4d3e8;
            transform: rotate(15deg);
        }

        .shape-circle-ml {
            top: 38%; left: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(48,139,229,0.18);
        }

        .shape-line-br {
            bottom: 120px; right: -40px;
            width: 240px; height: 28px;
            background: #d3e0cf;
            transform: rotate(-30deg);
        }

        .shape-tri-br {
            bottom: 24px; right: 28px;
            width: 0; height: 0;
            border-left: 34px solid transparent;
            border-right: 34px solid transparent;
            border-top: 54px solid #c3c3c3;
            transform: rotate(-32deg);
        }

        /* === INNER CONTENT === */
        .cart-inner {
            position: relative;
            z-index: 1;
            max-width: 720px;
            margin: 0 auto;
        }

        /* === PAGE TITLE === */
        .cart-title {
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 700;
            margin-bottom: 32px;
        }

        .cart-title .word-shopping { color: #249E2F; }
        .cart-title .word-cart     { color: #308BE5; }

        /* === FLASH MESSAGES === */
        .flash-success {
            margin-bottom: 16px;
            padding: 12px 16px;
            border-radius: 8px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            font-size: 14px;
        }

        .flash-error {
            margin-bottom: 16px;
            padding: 12px 16px;
            border-radius: 8px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            font-size: 14px;
        }

        /* === CART CARD === */
        .cart-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* === EMPTY STATE === */
        .cart-empty {
            text-align: center;
            padding: 72px 24px;
        }

        .cart-empty i {
            font-size: 56px;
            color: #aaa;
            margin-bottom: 18px;
            display: block;
        }

        .cart-empty-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .cart-empty-sub {
            font-size: 14px;
            color: #666;
            margin-bottom: 24px;
        }

        .btn-browse {
            display: inline-block;
            padding: 11px 32px;
            background: #308BE5;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }

        .btn-browse:hover { background: #2670b8; }

        /* === CART ITEM LIST === */
        .cart-item-list { padding: 0; }

        .cart-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 20px;
            border-bottom: 1px solid #ebebeb;
            transition: background 0.15s;
        }

        .cart-row:last-child { border-bottom: none; }
        .cart-row:hover { background: #fafafa; }

        /* Checkbox */
        .cart-row-check {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .item-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            border: 2px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .item-checkbox:hover { border-color: #249E2F; }

        .item-checkbox:checked {
            background: #249E2F;
            border-color: #249E2F;
        }

        .item-checkbox:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 12px;
        }

        /* Product image */
        .cart-row-img {
            flex-shrink: 0;
            width: 130px;
            height: 90px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e4e4e4;
            background: #f5f5f5;
        }

        /* Product details */
        .cart-row-details {
            flex: 1;
            min-width: 0;
        }

        .cart-row-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-row-listed-price {
            font-size: 13px;
            color: #666;
        }

        /* Delete button */
        .btn-remove {
            flex-shrink: 0;
            background: none;
            border: none;
            color: #aaa;
            font-size: 20px;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            transition: color 0.15s, background 0.15s;
            line-height: 1;
        }

        .btn-remove:hover {
            color: #e53935;
            background: rgba(229,57,53,0.08);
        }

        /* === REMOVE CONFIRMATION MODAL === */
        .remove-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(16, 16, 16, 0.45);
            z-index: 2100;
        }

        .remove-modal.active {
            display: flex;
        }

        .remove-modal-card {
            width: min(100%, 420px);
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.22);
            border: 1px solid #EAEAEA;
            overflow: hidden;
        }

        .remove-modal-head {
            padding: 16px 20px;
            background: #1e293b;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .remove-modal-title {
            margin: 0;
            color: #FFFFFF;
            font-size: 18px;
            font-weight: 700;
        }

        .remove-modal-close {
            border: none;
            background: transparent;
            color: #FFFFFF;
            font-size: 20px;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            cursor: pointer;
        }

        .remove-modal-close:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .remove-modal-body {
            padding: 18px 20px 8px;
        }

        .remove-modal-title-text {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #101010;
        }

        .remove-modal-text {
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
            margin: 0 0 12px;
        }

        .remove-modal-product {
            font-weight: 600;
            color: #1F2937;
        }

        .remove-modal-note {
            border: 1px solid #E3E3E3;
            border-radius: 8px;
            background: #F8FAFC;
            color: #1F2937;
            font-size: 14px;
            padding: 10px 12px;
        }

        .remove-modal-actions {
            padding: 14px 20px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-btn {
            border-radius: 8px;
            padding: 12px 20px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.2px;
        }

        .modal-btn-cancel {
            background: #FFFFFF;
            color: #000000;
            border: 2px solid #000000;
        }

        .modal-btn-cancel:hover {
            background: #F5F5F5;
        }

        .modal-btn-confirm {
            border: none;
            background: #249E2F;
            color: #ffffff;
        }

        .modal-btn-confirm:hover {
            background: #1e7a27;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(36, 158, 47, 0.3);
        }

        /* Price on right */
        .cart-row-price {
            flex-shrink: 0;
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            min-width: 80px;
            text-align: right;
        }

        .cart-row-price .peso-sign {
            font-weight: 400;
            margin-right: 2px;
        }

        /* === CHECKOUT BUTTON === */
        .cart-checkout-wrap {
            padding: 28px 20px;
            display: flex;
            justify-content: center;
        }

        .btn-checkout {
            background: #249E2F;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px 48px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            letter-spacing: 0.2px;
        }

        .btn-checkout:hover:not(:disabled) {
            background: #1e7a27;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(36,158,47,0.28);
        }

        .btn-checkout:disabled {
            background: #bbb;
            cursor: not-allowed;
        }

        /* === RESPONSIVE === */
        @media (max-width: 600px) {
            .cart-page { padding: 28px 12px 48px; }

            .cart-row { padding: 14px 12px; gap: 10px; }

            .cart-row-img {
                width: 80px;
                height: 60px;
            }

            .cart-row-title { font-size: 13px; }
            .cart-row-price { font-size: 13px; min-width: 60px; }
            .btn-remove { font-size: 17px; }

            .btn-checkout { padding: 12px 32px; font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="cart-page">
        <!-- decorative background shapes -->
        <div class="shape shape-tri-tl"></div>
        <div class="shape shape-tri-tr"></div>
        <div class="shape shape-circle-ml"></div>
        <div class="shape shape-line-br"></div>
        <div class="shape shape-tri-br"></div>

        <div class="cart-inner">
            <h1 class="cart-title">
                <span class="word-shopping">Shopping</span>
                <span class="word-cart"> Cart</span>
            </h1>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <div class="cart-card">
                <?php if (empty($cart_items)): ?>
                    <div class="cart-empty">
                        <i class="fas fa-shopping-cart"></i>
                        <div class="cart-empty-title">Your cart is empty</div>
                        <div class="cart-empty-sub">Browse products and add them to your cart.</div>
                        <a href="<?= base_url('home') ?>" class="btn-browse">Browse Products</a>
                    </div>
                <?php else: ?>
                    <div class="cart-item-list" id="cartItemList">
                        <?php foreach ($cart_items as $item): ?>
                            <?php
                                $rawPreviewPath = trim((string) ($item['preview_path'] ?? ''));
                                $thumbnailPath = '';

                                if ($rawPreviewPath !== '') {
                                    $decodedPreviewPath = json_decode($rawPreviewPath, true);
                                    if (is_array($decodedPreviewPath)) {
                                        foreach ($decodedPreviewPath as $candidatePath) {
                                            $candidatePath = trim((string) $candidatePath);
                                            if ($candidatePath !== '') {
                                                $thumbnailPath = $candidatePath;
                                                break;
                                            }
                                        }
                                    } else {
                                        $thumbnailPath = $rawPreviewPath;
                                    }
                                }

                                if ($thumbnailPath === '') {
                                    $imgSrc = base_url('assets/images/placeholder-product.png');
                                } elseif (preg_match('/^https?:\/\//i', $thumbnailPath)) {
                                    $imgSrc = $thumbnailPath;
                                } elseif (strpos($thumbnailPath, 'uploads/') === 0) {
                                    $imgSrc = base_url(ltrim($thumbnailPath, '/'));
                                } else {
                                    $imgSrc = base_url('uploads/product-thumbnails/' . ltrim($thumbnailPath, '/'));
                                }
                            ?>
                            <div class="cart-row" id="row-<?= (int) $item['cart_item_id'] ?>">
                                <!-- Checkbox -->
                                <div class="cart-row-check">
                                    <input
                                        type="checkbox"
                                        class="item-checkbox"
                                        data-item-id="<?= (int) $item['cart_item_id'] ?>"
                                        data-price="<?= (float) $item['price'] ?>"
                                        <?= $item['is_selected'] ? 'checked' : '' ?>
                                        aria-label="Select <?= esc($item['title'] ?? 'item') ?>"
                                    >
                                </div>

                                <!-- Thumbnail -->
                                <img
                                    src="<?= $imgSrc ?>"
                                    alt="<?= esc($item['title'] ?? '') ?>"
                                    class="cart-row-img"
                                    onerror="this.src='<?= base_url('assets/images/placeholder-product.png') ?>'"
                                >

                                <!-- Details -->
                                <div class="cart-row-details">
                                    <div class="cart-row-title" title="<?= esc($item['title'] ?? '') ?>">
                                        <?= esc($item['title'] ?? 'Unknown Product') ?>
                                    </div>
                                    <div class="cart-row-listed-price">
                                        ₱<?= number_format((float) $item['price'], 2) ?>
                                    </div>
                                </div>

                                <!-- Remove button -->
                                <button
                                    class="btn-remove"
                                    data-item-id="<?= (int) $item['cart_item_id'] ?>"
                                    aria-label="Remove from cart"
                                    title="Remove from cart"
                                >
                                    <i class="fa-solid fa-delete-left"></i>
                                </button>

                                <!-- Price -->
                                <div class="cart-row-price">
                                    <span class="peso-sign">₱</span><?= number_format((float) $item['price'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Proceed to checkout -->
                    <div class="cart-checkout-wrap">
                        <button type="button" class="btn-checkout" id="checkoutBtn">
                            Proceed to checkout
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="remove-modal" id="removeModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="removeModalTitle">
        <div class="remove-modal-card">
            <div class="remove-modal-head">
                <h2 class="remove-modal-title" id="removeModalTitle">Remove product?</h2>
                <button type="button" class="remove-modal-close" id="removeModalClose" aria-label="Close remove confirmation">×</button>
            </div>
            <div class="remove-modal-body">
                <p class="remove-modal-text">
                    Are you sure you want to remove
                    <span class="remove-modal-product" id="removeModalProductName">this product</span>
                    from your cart?
                </p>
                <div class="remove-modal-note">This action will remove the item from your cart immediately.</div>
            </div>
            <div class="remove-modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" id="removeModalCancel">Cancel</button>
                <button type="button" class="modal-btn modal-btn-confirm" id="removeModalConfirm">Remove</button>
            </div>
        </div>
    </div>

    <script>
    (() => {
        const csrfToken  = '<?= csrf_token() ?>';
        let csrfHash   = '<?= csrf_hash() ?>';
        const removeModal = document.getElementById('removeModal');
        const removeModalCancel = document.getElementById('removeModalCancel');
        const removeModalConfirm = document.getElementById('removeModalConfirm');
        const removeModalClose = document.getElementById('removeModalClose');
        const removeModalProductName = document.getElementById('removeModalProductName');
        let pendingRemoveButton = null;

        function buildPostBody(values) {
            const body = new URLSearchParams();
            body.append(csrfToken, csrfHash);

            Object.entries(values).forEach(([key, value]) => {
                body.append(key, String(value));
            });

            return body;
        }

        function updateCsrfHash(data) {
            if (data && typeof data.csrfHash === 'string' && data.csrfHash !== '') {
                csrfHash = data.csrfHash;
            }
        }

        function parseJsonResponse(response) {
            return response.text().then(text => {
                try {
                    const data = JSON.parse(text);
                    updateCsrfHash(data);
                    return data;
                } catch (error) {
                    throw new Error('Failed to parse JSON string. Error: ' + error.message);
                }
            });
        }

        function openRemoveModal(button) {
            pendingRemoveButton = button;
            const row = button.closest('.cart-row');
            const titleElement = row ? row.querySelector('.cart-row-title') : null;
            removeModalProductName.textContent = titleElement ? titleElement.textContent.trim() : 'this product';
            removeModal.classList.add('active');
            removeModal.setAttribute('aria-hidden', 'false');
            removeModalConfirm.focus();
        }

        function closeRemoveModal() {
            removeModal.classList.remove('active');
            removeModal.setAttribute('aria-hidden', 'true');
            if (pendingRemoveButton) {
                pendingRemoveButton.focus();
            }
        }

        function removeCartItem(button) {
            const itemId = button.dataset.itemId;
            const row = document.getElementById('row-' + itemId);

            button.disabled = true;
            button.style.opacity = '0.5';
            removeModalConfirm.disabled = true;

            fetch('<?= base_url('cart/remove') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: buildPostBody({ item_id: parseInt(itemId, 10) }).toString()
            })
            .then(parseJsonResponse)
            .then(data => {
                if (data.success) {
                    closeRemoveModal();
                    row.style.transition = 'opacity 0.25s';
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        if (!document.querySelector('.cart-row')) {
                            location.reload();
                        }
                    }, 260);
                } else {
                    alert(data.message || 'Failed to remove item.');
                    button.disabled = false;
                    button.style.opacity = '1';
                }
            })
            .catch(() => {
                alert('Failed to remove item. Please try again.');
                button.disabled = false;
                button.style.opacity = '1';
            })
            .finally(() => {
                removeModalConfirm.disabled = false;
                if (!button.disabled) {
                    pendingRemoveButton = null;
                }
            });
        }

        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                openRemoveModal(this);
            });
        });

        if (removeModalCancel) {
            removeModalCancel.addEventListener('click', function () {
                pendingRemoveButton = null;
                closeRemoveModal();
            });
        }

        if (removeModalClose) {
            removeModalClose.addEventListener('click', function () {
                pendingRemoveButton = null;
                closeRemoveModal();
            });
        }

        if (removeModalConfirm) {
            removeModalConfirm.addEventListener('click', function () {
                if (pendingRemoveButton) {
                    removeCartItem(pendingRemoveButton);
                }
            });
        }

        if (removeModal) {
            removeModal.addEventListener('click', function (event) {
                if (event.target === removeModal) {
                    pendingRemoveButton = null;
                    closeRemoveModal();
                }
            });
        }

        // Selection change — persist to DB
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                fetch('<?= base_url('cart/update_selection') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: buildPostBody({
                        item_id: parseInt(this.dataset.itemId, 10),
                        selected: this.checked
                    }).toString()
                })
                .then(parseJsonResponse)
                .catch(() => {});
            });
        });

        // Checkout button
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function () {
                const anyChecked = [...document.querySelectorAll('.item-checkbox')].some(cb => cb.checked);
                if (!anyChecked) {
                    // Highlight all unchecked as reminder
                    document.querySelectorAll('.item-checkbox:not(:checked)').forEach(cb => {
                        cb.style.borderColor = '#e53935';
                        setTimeout(() => { cb.style.borderColor = ''; }, 1800);
                    });
                    return;
                }

                checkoutBtn.disabled = true;

                fetch('<?= base_url('cart/checkout') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: buildPostBody({ action: 'create_checkout_session' }).toString(),
                })
                .then(parseJsonResponse)
                .then((data) => {
                    if (!data.success || !data.data || !data.data.checkout_url) {
                        throw new Error(data.message || 'Unable to create checkout session.');
                    }

                    window.location.href = data.data.checkout_url;
                })
                .catch((error) => {
                    alert(error.message || 'Unable to proceed to checkout. Please try again.');
                    checkoutBtn.disabled = false;
                });
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && removeModal.classList.contains('active')) {
                pendingRemoveButton = null;
                closeRemoveModal();
            }
        });
    })();
    </script>

    <?= view('footer') ?>
</body>
</html>
