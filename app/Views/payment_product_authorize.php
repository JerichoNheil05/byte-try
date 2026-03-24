<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorize Product Payment</title>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #fff; color: #111; margin: 0; }
        .wrap { max-width: 560px; margin: 48px auto; padding: 24px; border: 1px solid #ddd; border-radius: 10px; }
        h1 { margin: 0 0 16px; font-size: 28px; }
        p { margin: 8px 0; }
        .amount { font-size: 36px; font-weight: 700; color: #1f7ae0; margin: 10px 0 18px; }
        .order { font-size: 14px; color: #666; margin-bottom: 12px; }
        .actions { display: flex; gap: 10px; margin-top: 18px; }
        button, a { text-decoration: none; border: none; padding: 12px 16px; border-radius: 0; font-weight: 700; cursor: pointer; }
        .primary { background: #000; color: #fff; }
        .secondary { background: #f0f0f0; color: #000; }
    </style>
</head>
<body>
    <main class="wrap">
        <h1>Authorize Product Payment</h1>
        <p class="order">Order: <?= esc($orderNumber ?? '') ?></p>
        <div class="amount">₱ <?= esc($amount ?? '0.00') ?></div>
        <p>Currency: <?= esc($currency ?? 'PHP') ?></p>

        <div class="actions">
            <button type="button" class="primary" id="authorizeBtn">AUTHORIZE PAYMENT</button>
            <a class="secondary" href="<?= esc($cancelUrl ?? base_url('orders')) ?>">CANCEL</a>
        </div>
    </main>

    <script>
        (() => {
            const csrfToken = '<?= csrf_token() ?>';
            let csrfHash = '<?= csrf_hash() ?>';
            const authorizeBtn = document.getElementById('authorizeBtn');

            authorizeBtn?.addEventListener('click', function () {
                authorizeBtn.disabled = true;
                authorizeBtn.textContent = 'PROCESSING...';

                const body = new URLSearchParams();
                body.append(csrfToken, csrfHash);

                fetch('<?= esc($markPaidUrl ?? base_url('cart/payment-mark-paid')) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: body.toString(),
                })
                .then((response) => response.text())
                .then((text) => {
                    let data = null;
                    try {
                        data = JSON.parse(text);
                    } catch (error) {
                        throw new Error('Unexpected response while authorizing payment.');
                    }

                    if (data && typeof data.csrfHash === 'string' && data.csrfHash !== '') {
                        csrfHash = data.csrfHash;
                    }

                    if (data && data.success) {
                        window.location.href = data.redirect_url || '<?= base_url('orders') ?>';
                        return;
                    }

                    throw new Error((data && data.message) ? data.message : 'Failed to authorize payment.');
                })
                .catch((error) => {
                    alert(error.message || 'Error authorizing payment.');
                    authorizeBtn.disabled = false;
                    authorizeBtn.textContent = 'AUTHORIZE PAYMENT';
                });
            });
        })();
    </script>
</body>
</html>
