<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorize Test Payment</title>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #fff; color: #000; margin: 0; }
        .wrap { max-width: 520px; margin: 48px auto; padding: 24px; border: 1px solid #ddd; border-radius: 10px; }
        h1 { margin: 0 0 16px; font-size: 28px; }
        p { margin: 8px 0; }
        .amount { font-size: 36px; font-weight: 700; color: #FFCC00; margin: 10px 0 18px; }
        .actions { display: flex; gap: 10px; margin-top: 18px; }
        button, a { text-decoration: none; border: none; padding: 12px 16px; border-radius: 0; font-weight: 700; cursor: pointer; }
        .primary { background: #000; color: #fff; }
        .secondary { background: #f0f0f0; color: #000; }
        .meta { margin-top: 16px; font-size: 12px; color: #666; word-break: break-all; }
    </style>
</head>
<body>
    <main class="wrap">
        <h1>Authorize Test Payment</h1>
        <p>Seller Subscription</p>
        <div class="amount">₱ <?= esc($amount) ?> / Per Month</div>
        <p>Currency: <?= esc($currency) ?></p>

        <div class="actions">
            <button type="button" class="primary" id="authorizeBtn">AUTHORIZE TEST PAYMENT</button>
            <a class="secondary" href="<?= esc($cancelUrl) ?>">CANCEL</a>
        </div>

        <p class="meta">PayMongo checkout reference: <?= esc($checkoutReference) ?></p>
    </main>

    <script>
        document.getElementById('authorizeBtn')?.addEventListener('click', function () {
            const button = this;
            button.disabled = true;
            button.textContent = 'PROCESSING...';

            fetch('<?= base_url('payment/seller-subscription/mark-paid') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url || '<?= base_url('subscription') ?>';
                    return;
                }
                alert(data.message || 'Failed to authorize test payment.');
                button.disabled = false;
                button.textContent = 'AUTHORIZE TEST PAYMENT';
            })
            .catch(() => {
                alert('Error authorizing test payment.');
                button.disabled = false;
                button.textContent = 'AUTHORIZE TEST PAYMENT';
            });
        });
    </script>
</body>
</html>
