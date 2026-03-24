<?= view('header') ?>
<?php $ordersBackUrl = previous_url() ?: base_url('home'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Byte Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #ececec;
            color: #121212;
            padding-top: 88px;
        }

        .orders-page {
            max-width: 1080px;
            margin: 0 auto;
            padding: 28px 34px 60px;
            position: relative;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
            margin-bottom: 14px;
            padding: 8px 12px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #d9d9d9;
            color: #111111;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            width: fit-content;
        }

        .back-btn:hover {
            background: #f7f7f7;
            border-color: #c8c8c8;
        }

        .back-btn svg {
            width: 14px;
            height: 14px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .bg-line {
            position: absolute;
            width: 210px;
            height: 12px;
            background: #d9e7d8;
            opacity: 0.75;
            transform: rotate(-30deg);
            right: -8px;
            top: 196px;
            pointer-events: none;
        }

        .bg-circle {
            position: absolute;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #d7e2ee;
            right: 40px;
            top: 8px;
            pointer-events: none;
        }

        .orders-head {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 24px 0 28px;
        }

        .orders-title {
            font-size: 48px;
            line-height: 1;
            font-weight: 800;
            color: #0f0f0f;
        }

        .tab-btn {
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 700;
            font-size: 25px;
            line-height: 1;
            height: 52px;
            padding: 0 24px;
            cursor: default;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .tab-orders {
            background: #3fad49;
            box-shadow: 0 2px 5px rgba(20, 80, 30, 0.2);
        }

        .orders-topbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 14px;
        }

        .filter-form {
            margin: 0;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 8px 8px 18px;
        }

        .filter-form::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 50%;
            width: 236px;
            height: 16px;
            background: rgba(162, 205, 146, 0.34);
            transform: translateY(-50%) rotate(-33deg);
            transform-origin: center;
            z-index: 0;
            pointer-events: none;
        }

        .status-filter {
            position: relative;
            z-index: 1;
            border: none;
            background: #000;
            color: #fff;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 26px 4px 10px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: linear-gradient(45deg, transparent 50%, white 50%), linear-gradient(135deg, white 50%, transparent 50%);
            background-position: calc(100% - 12px) 55%, calc(100% - 7px) 55%;
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
            cursor: pointer;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table thead th {
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #202020;
            padding: 10px 10px;
            border-bottom: 1px solid #cfcfcf;
        }

        .orders-table tbody td {
            font-size: 14px;
            font-weight: 500;
            color: #202020;
            padding: 16px 10px;
            border-bottom: 1px solid #d3d3d3;
            vertical-align: middle;
        }

        .orders-table th.col-buyer,
        .orders-table td.col-buyer {
            width: 140px;
        }

        .orders-table th.col-product,
        .orders-table td.col-product {
            width: 300px;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .orders-table th.col-seller,
        .orders-table td.col-seller {
            width: 210px;
        }

        .orders-table th.col-date,
        .orders-table td.col-date {
            width: 160px;
        }

        .orders-table th.col-status,
        .orders-table td.col-status {
            width: 140px;
        }

        .orders-table tbody td.status-completed {
            color: #24a13f !important;
            font-weight: 600;
        }

        .orders-table tbody td.status-pending {
            color: #3b8fe4 !important;
            font-weight: 600;
        }

        .orders-table tbody td.status-refunded {
            color: #d97706 !important;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .orders-page {
                padding: 22px 14px 44px;
            }

            .orders-title {
                font-size: 36px;
            }

            .tab-btn {
                font-size: 18px;
                height: 42px;
                padding: 0 16px;
            }

            .bg-line,
            .bg-circle {
                display: none;
            }

            .orders-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .filter-form {
                padding-left: 10px;
            }

            .filter-form::before {
                width: 170px;
                left: -12px;
            }

            .back-btn {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <main class="orders-page">
        <span class="bg-circle" aria-hidden="true"></span>
        <span class="bg-line" aria-hidden="true"></span>

        <a href="<?= esc($ordersBackUrl, 'attr') ?>" class="back-btn" aria-label="Go back">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
            Back
        </a>

        <div class="orders-head" aria-label="Orders header">
            <h1 class="orders-title">Track</h1>
            <span class="tab-btn tab-orders" aria-current="page">Orders</span>
        </div>

        <div class="orders-topbar">
            <form method="GET" action="<?= base_url('orders') ?>" class="filter-form">
                <select class="status-filter" id="statusFilter" aria-label="Filter order status" name="status">
                    <option value="all" <?= (($currentFilter ?? 'all') === 'all') ? 'selected' : '' ?>>All</option>
                    <option value="completed" <?= (($currentFilter ?? 'all') === 'completed') ? 'selected' : '' ?>>Completed</option>
                    <option value="pending" <?= (($currentFilter ?? 'all') === 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="refunded" <?= (($currentFilter ?? 'all') === 'refunded') ? 'selected' : '' ?>>Refunded</option>
                </select>
            </form>
        </div>

        <table class="orders-table" role="table" aria-label="Orders table">
            <thead>
                <tr>
                    <th class="col-buyer">Buyer</th>
                    <th class="col-product">Products</th>
                    <th class="col-seller">Seller</th>
                    <th class="col-date">Order Date</th>
                    <th class="col-status">Status</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="col-buyer"><?= esc($order['buyer'] ?? '') ?></td>
                            <td class="col-product"><?= esc($order['product'] ?? '') ?></td>
                            <td class="col-seller"><?= esc($order['seller'] ?? '') ?></td>
                            <td class="col-date"><?= esc($order['order_date'] ?? '') ?></td>
                            <?php $statusValue = strtolower(trim((string) ($order['status'] ?? 'pending'))); ?>
                            <td class="col-status <?= in_array($statusValue, ['refunded', 'refund'], true) ? 'status-refunded' : (in_array($statusValue, ['complete', 'completed', 'paid', 'pail'], true) ? 'status-completed' : 'status-pending') ?>">
                                <?= esc($order['status'] ?? '') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding:20px 10px;text-align:center;color:#5f6368;font-size:14px;">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <script>
        const statusFilter = document.getElementById('statusFilter');
        const ordersTableBody = document.getElementById('ordersTableBody');

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderOrdersRows(orders) {
            if (!ordersTableBody) {
                return;
            }

            if (!Array.isArray(orders) || orders.length === 0) {
                ordersTableBody.innerHTML = '<tr><td colspan="5" style="padding:20px 10px;text-align:center;color:#5f6368;font-size:14px;">No orders found.</td></tr>';
                return;
            }

            ordersTableBody.innerHTML = orders.map((order) => {
                const buyer = escapeHtml(order.buyer || '');
                const product = escapeHtml(order.product || '');
                const seller = escapeHtml(order.seller || '');
                const orderDate = escapeHtml(order.order_date || '');
                const status = String(order.status || 'Pending');
                const normalizedStatus = status.toLowerCase().trim();
                const statusClass = ['refunded', 'refund'].includes(normalizedStatus)
                    ? 'status-refunded'
                    : (['complete', 'completed', 'paid', 'pail'].includes(normalizedStatus)
                        ? 'status-completed'
                        : 'status-pending');

                return `
                    <tr>
                        <td class="col-buyer">${buyer}</td>
                        <td class="col-product">${product}</td>
                        <td class="col-seller">${seller}</td>
                        <td class="col-date">${orderDate}</td>
                        <td class="col-status ${statusClass}">${escapeHtml(status)}</td>
                    </tr>
                `;
            }).join('');
        }

        async function fetchFilteredOrders(status) {
            if (!ordersTableBody) {
                return;
            }

            ordersTableBody.innerHTML = '<tr><td colspan="5" style="padding:20px 10px;text-align:center;color:#5f6368;font-size:14px;">Loading orders...</td></tr>';

            const requestUrl = new URL('<?= base_url('orders') ?>', window.location.origin);
            requestUrl.searchParams.set('status', status);

            try {
                const response = await fetch(requestUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch orders.');
                }

                const payload = await response.json();
                if (!payload || payload.success !== true || !payload.data) {
                    throw new Error('Invalid orders response.');
                }

                renderOrdersRows(payload.data.orders || []);

                const nextUrl = new URL(window.location.href);
                nextUrl.searchParams.set('status', payload.data.currentFilter || status || 'all');
                window.history.replaceState({}, '', nextUrl.toString());
            } catch (error) {
                const fallbackUrl = new URL('<?= base_url('orders') ?>', window.location.origin);
                fallbackUrl.searchParams.set('status', status);
                window.location.href = fallbackUrl.toString();
            }
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', (event) => {
                const selected = String(event.target.value || 'all');
                fetchFilteredOrders(selected);
            });
        }
    </script>

    <?= view('footer') ?>
</body>
</html>
