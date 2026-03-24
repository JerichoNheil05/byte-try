<?= view('header') ?>
<?php $analyticsBackUrl = previous_url() ?: base_url('dashboard'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - Byte Market Seller Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #FFFFFF;
            color: #000000;
            line-height: 1.6;
        }

        body {
            padding-top: 80px;
            background: #F9F9F9;
        }

        /* === MAIN CONTAINER === */
        .analytics-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
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

        /* === PAGE HEADER === */
        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 700;
            color: #000000;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .page-title .title-green {
            color: #249E2F;
        }

        .page-title .title-blue {
            color: #308BE5;
        }

        /* === METRICS SECTION === */
        .metrics-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .metric-card {
            padding: 24px;
            border-radius: 12px;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .metric-card.total-sales {
            background: linear-gradient(135deg, #249E2F 0%, #1d7a24 100%);
        }

        .metric-card.total-orders {
            background: linear-gradient(135deg, #308BE5 0%, #2568c2 100%);
        }

        .metric-card.products {
            background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%);
        }

        .metric-label {
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .metric-value {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .metric-icon {
            font-size: 24px;
            margin-bottom: 8px;
            opacity: 0.8;
        }

        /* === TABLE SECTION === */
        .table-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 20px;
        }

        .table-container {
            background: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table thead {
            background: #F5F5F5;
            border-bottom: 2px solid #EEEEEE;
        }

        .products-table th {
            padding: 16px 20px;
            text-align: left;
            font-size: 14px;
            font-weight: 500;
            color: #000000;
            text-transform: capitalize;
        }

        .products-table tbody tr {
            border-bottom: 1px solid #EEEEEE;
            transition: all 0.3s ease;
        }

        .products-table tbody tr:hover {
            background: #F9F9F9;
        }

        .products-table td {
            padding: 16px 20px;
            font-size: 14px;
            color: #000000;
        }

        .product-name {
            font-weight: 600;
        }

        .product-category {
            color: #666666;
        }

        .product-date {
            color: #999999;
        }

        .product-price {
            font-weight: 600;
            color: #249E2F;
        }

        .product-buyer {
            color: #666666;
        }

        /* === CHARTS SECTION === */
        .charts-section {
            margin-bottom: 40px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
        }

        .chart-card {
            background: #FFFFFF;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 280px;
            margin-bottom: 12px;
        }

        .chart-container canvas {
            max-height: 280px;
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .empty-state-icon {
            font-size: 48px;
            color: #CCCCCC;
            margin-bottom: 16px;
        }

        .empty-state-text {
            font-size: 16px;
            color: #666666;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 1024px) {
            .analytics-wrapper {
                padding: 24px 16px;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }

            .analytics-wrapper {
                padding: 20px 12px;
            }

            .page-title {
                font-size: 24px;
            }

            .metrics-section {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .metric-card {
                padding: 20px;
            }

            .metric-value {
                font-size: 28px;
            }

            .products-table th,
            .products-table td {
                padding: 12px;
                font-size: 13px;
            }

            .chart-container {
                height: 250px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding-top: 60px;
            }

            .analytics-wrapper {
                padding: 16px 8px;
            }

            .page-title {
                font-size: 20px;
            }

            .metrics-section {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .metric-card {
                padding: 16px;
            }

            .metric-label {
                font-size: 11px;
            }

            .metric-value {
                font-size: 24px;
            }

            .metric-icon {
                font-size: 20px;
            }

            .products-table th,
            .products-table td {
                padding: 10px;
                font-size: 12px;
            }

            .section-title {
                font-size: 16px;
            }

            .chart-card {
                padding: 16px;
            }

            .chart-title {
                font-size: 14px;
            }

            .chart-container {
                height: 200px;
            }

            .charts-grid {
                gap: 16px;
            }

            .empty-state-icon {
                font-size: 36px;
            }

            .empty-state-text {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="analytics-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <a href="<?= esc($analyticsBackUrl, 'attr') ?>" class="back-btn" aria-label="Go back">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
                Back
            </a>

            <h1 class="page-title">
                <span class="title-green">Analytics</span>
                <span class="title-blue">Dashboard</span>
            </h1>
        </div>

        <!-- Metrics Section -->
        <div class="metrics-section">
            <!-- Total Sales -->
            <div class="metric-card total-sales">
                <div class="metric-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="metric-label">Total Sales</div>
                <div class="metric-value">₱<?= number_format($analytics['total_sales'] ?? 0, 2) ?></div>
            </div>

            <!-- Total Orders -->
            <div class="metric-card total-orders">
                <div class="metric-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="metric-label">Total Orders</div>
                <div class="metric-value"><?= $analytics['total_orders'] ?? 0 ?></div>
            </div>

            <!-- Products Count -->
            <div class="metric-card products">
                <div class="metric-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="metric-label">Products</div>
                <div class="metric-value"><?= $analytics['product_count'] ?? 0 ?></div>
            </div>
        </div>

        <!-- Products Table Section -->
        <div class="table-section">
            <h2 class="section-title">Products</h2>
            <div class="table-container">
                <table class="products-table" role="table" aria-label="Product sales">
                    <thead>
                        <tr role="row">
                            <th role="columnheader">Products</th>
                            <th role="columnheader">Category</th>
                            <th role="columnheader">Published</th>
                            <th role="columnheader">Price</th>
                        </tr>
                    </thead>
                    <tbody id="analyticsProductsTableBody">
                        <?php if (!empty($products)): ?>
                            <?php foreach($products as $product): ?>
                                <tr role="row">
                                    <td class="product-name"><?= htmlspecialchars($product['product_name'] ?? 'N/A') ?></td>
                                    <td class="product-category"><?= htmlspecialchars($product['category'] ?? 'Uncategorized') ?></td>
                                    <td class="product-date"><?= htmlspecialchars($product['published_display'] ?? 'N/A') ?></td>
                                    <td class="product-price">₱<?= number_format((float) ($product['price'] ?? 0), 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr role="row">
                                <td colspan="4" class="empty-state-text" style="text-align:center; padding: 18px;">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="charts-grid">
                <!-- Total Sales Chart -->
                <div class="chart-card">
                    <h3 class="chart-title">Total sales</h3>
                    <div class="chart-container">
                        <canvas id="totalSalesChart" role="img" aria-label="Total sales line chart"></canvas>
                    </div>
                </div>

                <!-- Number of Orders Chart -->
                <div class="chart-card">
                    <h3 class="chart-title">Number of orders</h3>
                    <div class="chart-container">
                        <canvas id="ordersChart" role="img" aria-label="Number of orders multi-line chart"></canvas>
                    </div>
                </div>

                <!-- Purchased per Product Chart -->
                <div class="chart-card">
                    <h3 class="chart-title">Purchased per product</h3>
                    <div class="chart-container">
                        <canvas id="purchasedChart" role="img" aria-label="Purchased per product bar chart"></canvas>
                    </div>
                </div>

                <!-- Top 3 Products Chart -->
                <div class="chart-card">
                    <h3 class="chart-title">Top 3 Products</h3>
                    <div class="chart-container">
                        <canvas id="topProductsChart" role="img" aria-label="Top 3 products donut chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch and update charts with real backend data
        const baseUrl = '<?= base_url() ?>';
        
        // Initialize charts with fallback data
        let totalSalesChart, ordersChart, purchasedChart, topProductsChart;

        // === TOTAL SALES CHART ===
        function initTotalSalesChart(labels, data) {
            const totalSalesCtx = document.getElementById('totalSalesChart').getContext('2d');
            totalSalesChart = new Chart(totalSalesCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: data,
                        borderColor: '#249E2F',
                        backgroundColor: 'rgba(36, 158, 47, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#249E2F',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#999999',
                                font: { family: "'Poppins', Arial, sans-serif" }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                color: '#999999',
                                font: { family: "'Poppins', Arial, sans-serif" }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // === NUMBER OF ORDERS CHART ===
        function initOrdersChart(labels, data) {
            const ordersCtx = document.getElementById('ordersChart').getContext('2d');
            ordersChart = new Chart(ordersCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Orders',
                        data: data,
                        backgroundColor: [
                            'rgba(249, 115, 22, 0.70)',
                            'rgba(34, 197, 94, 0.70)',
                            'rgba(239, 68, 68, 0.70)'
                        ],
                        borderColor: [
                            '#f97316',
                            '#22c55e',
                            '#ef4444'
                        ],
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#999999',
                                font: { family: "'Poppins', Arial, sans-serif" }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: {
                                color: '#999999',
                                font: { family: "'Poppins', Arial, sans-serif" }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // === PURCHASED PER PRODUCT CHART ===
        function initPurchasedChart(labels, data) {
            const purchasedCtx = document.getElementById('purchasedChart').getContext('2d');
            purchasedChart = new Chart(purchasedCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Purchases',
                        data: data,
                        backgroundColor: [
                            'rgba(0, 201, 255, 0.6)',
                            'rgba(33, 150, 243, 0.6)',
                            'rgba(63, 81, 181, 0.6)',
                            'rgba(44, 47, 147, 0.6)',
                            'rgba(36, 158, 47, 0.6)'
                        ],
                        borderColor: [
                            '#00C9FF',
                            '#2196F3',
                            '#3F51B5',
                            '#2C2F93',
                            '#249E2F'
                        ],
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: '#999999',
                                font: { family: "'Poppins', Arial, sans-serif" }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        y: {
                            ticks: {
                                color: '#999999',
                                font: { family: "'Poppins', Arial, sans-serif" }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // === TOP 3 PRODUCTS CHART ===
        function initTopProductsChart(labels, data) {
            const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
            topProductsChart = new Chart(topProductsCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(36, 158, 47, 0.8)',
                            'rgba(48, 139, 229, 0.8)',
                            'rgba(100, 100, 100, 0.8)'
                        ],
                        borderColor: [
                            '#249E2F',
                            '#308BE5',
                            '#666666'
                        ],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#666666',
                                font: { family: "'Poppins', Arial, sans-serif", size: 12 },
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const all = context.dataset.data || [];
                                    const total = all.reduce((sum, value) => sum + Number(value || 0), 0);
                                    const value = Number(context.parsed || 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
                                    return `${context.label}: ${value} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderAnalyticsProductsTable(products) {
            const tableBody = document.getElementById('analyticsProductsTableBody');
            if (!tableBody) {
                return;
            }

            if (!Array.isArray(products) || products.length === 0) {
                tableBody.innerHTML = '<tr role="row"><td colspan="4" class="empty-state-text" style="text-align:center; padding: 18px;">No products found.</td></tr>';
                return;
            }

            tableBody.innerHTML = products.map((product) => `
                <tr role="row">
                    <td class="product-name">${escapeHtml(product.product_name || 'N/A')}</td>
                    <td class="product-category">${escapeHtml(product.category || 'General')}</td>
                    <td class="product-date">${escapeHtml(product.published_display || 'N/A')}</td>
                    <td class="product-price">₱${Number(product.price || 0).toFixed(2)}</td>
                </tr>
            `).join('');
        }

        // Load chart data from backend
        async function loadChartData() {
            try {
                // Load sales data
                const salesResponse = await fetch(baseUrl + 'analytics/sales');
                if (!salesResponse.ok) {
                    throw new Error('Failed to load sales data');
                }
                const salesData = await salesResponse.json();
                initTotalSalesChart(salesData.labels || [], salesData.data || []);

                // Load orders data
                const ordersResponse = await fetch(baseUrl + 'analytics/orders');
                if (!ordersResponse.ok) {
                    throw new Error('Failed to load orders data');
                }
                const ordersData = await ordersResponse.json();
                initOrdersChart(ordersData.labels || [], ordersData.data || []);

                // Load products data
                const productsResponse = await fetch(baseUrl + 'analytics/products');
                if (!productsResponse.ok) {
                    throw new Error('Failed to load products data');
                }
                const productsData = await productsResponse.json();
                renderAnalyticsProductsTable(productsData.tableProducts || []);
                initPurchasedChart(productsData.labels || [], productsData.data || []);
                
                // Top products (strictly top 3)
                initTopProductsChart(productsData.topLabels || [], productsData.topData || []);
            } catch (error) {
                console.error('Error loading chart data:', error);
                
                // Initialize empty charts if API fails.
                renderAnalyticsProductsTable([]);
                initTotalSalesChart([], []);
                initOrdersChart([], []);
                initPurchasedChart([], []);
                initTopProductsChart([], []);
            }
        }

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', loadChartData);

        // Responsive chart resize on window resize
        window.addEventListener('resize', function() {
            if (totalSalesChart) totalSalesChart.resize();
            if (ordersChart) ordersChart.resize();
            if (purchasedChart) purchasedChart.resize();
            if (topProductsChart) topProductsChart.resize();
        });
    </script>

    <?= view('footer') ?>
</body>
</html>
