<?php

namespace App\Controllers;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\AuthModel;
use App\Models\ProductModel;

class Analytics extends BaseController
{
    private function resolveSalesBucket(array $row): string
    {
        $itemStatus = strtolower(trim((string) ($row['item_status'] ?? '')));
        $orderStatus = strtolower(trim((string) ($row['order_status'] ?? '')));
        $paymentStatus = strtolower(trim((string) ($row['payment_status'] ?? '')));

        $refundLike = ['refunded', 'returned', 'cancelled'];
        $completedLike = ['completed', 'delivered', 'paid', 'success', 'succeeded'];

        if (
            in_array($itemStatus, $refundLike, true)
            || in_array($orderStatus, $refundLike, true)
            || in_array($paymentStatus, $refundLike, true)
        ) {
            return 'refund';
        }

        if (
            in_array($itemStatus, $completedLike, true)
            || in_array($orderStatus, $completedLike, true)
            || in_array($paymentStatus, $completedLike, true)
        ) {
            return 'completed';
        }

        return 'pending';
    }

    private function getSellerOrderItemRows(int $sellerId): array
    {
        $db = \Config\Database::connect();
        return $db->table('order_items oi')
            ->select('oi.order_item_id, oi.order_id, oi.product_id, oi.product_title, oi.quantity, oi.subtotal, oi.item_status, o.status AS order_status, o.payment_status')
            ->join('orders o', 'o.order_id = oi.order_id', 'inner')
            ->where('oi.seller_id', $sellerId)
            ->orderBy('oi.order_item_id', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function isCancelledLikeStatus(string $status): bool
    {
        return in_array($status, ['cancelled', 'returned', 'refunded'], true);
    }

    private function normalizeItemStatus(string $status): string
    {
        $normalized = strtolower(trim($status));

        if (in_array($normalized, ['completed', 'delivered', 'paid', 'success', 'succeeded'], true)) {
            return 'completed';
        }

        if (in_array($normalized, ['processing', 'confirmed', 'shipped'], true)) {
            return 'processing';
        }

        if (in_array($normalized, ['cancelled', 'returned', 'refunded'], true)) {
            return 'cancelled';
        }

        return 'pending';
    }

    private function ensureSellerAccess(bool $forApi = false)
    {
        if (!session()->get('isLoggedIn')) {
            return $forApi
                ? $this->response->setJSON(['error' => 'Unauthorized'], 401)
                : redirect()->to('/auth/login');
        }

        $role = strtolower(trim((string) (session()->get('role') ?? 'buyer')));
        $accountType = strtolower(trim((string) (session()->get('account_type') ?? 'buyer')));
        if ($role !== 'seller' && $accountType !== 'seller') {
            return $forApi
                ? $this->response->setJSON(['error' => 'Seller access required'], 403)
                : redirect()->to('/home')->with('error', 'Access denied. Seller account required.');
        }

        return null;
    }

    private function currentSellerId(): int
    {
        return (int) (session()->get('userId') ?? session()->get('user_id') ?? 0);
    }

    private function getSellerProductSalesMap(int $sellerId): array
    {
        $items = $this->getSellerOrderItemRows($sellerId);

        $salesMap = [];
        foreach ($items as $item) {
            if ($this->resolveSalesBucket($item) !== 'completed') {
                continue;
            }

            $productId = (int) ($item['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            if (!isset($salesMap[$productId])) {
                $salesMap[$productId] = [
                    'quantity' => 0,
                    'total_sales' => 0.0,
                    'title' => (string) ($item['product_title'] ?? 'Unknown'),
                ];
            }

            $salesMap[$productId]['quantity'] += (int) ($item['quantity'] ?? 1);
            $salesMap[$productId]['total_sales'] += (float) ($item['subtotal'] ?? 0);
        }

        return $salesMap;
    }

    private function getSellerCatalogProducts(int $sellerId): array
    {
        $productModel = new ProductModel();
        $productRows = $productModel->getSellerProducts($sellerId);
        $salesMap = $this->getSellerProductSalesMap($sellerId);

        return array_map(static function (array $product) use ($salesMap): array {
            $productId = (int) ($product['id'] ?? 0);
            $createdAt = (string) ($product['created_at'] ?? '');
            $sales = $salesMap[$productId] ?? ['quantity' => 0, 'total_sales' => 0.0, 'title' => (string) ($product['title'] ?? 'Untitled Product')];

            return [
                'id' => $productId,
                'product_name' => (string) ($product['title'] ?? 'Untitled Product'),
                'category' => trim((string) ($product['category'] ?? '')) ?: 'General',
                'price' => (float) ($product['price'] ?? 0),
                'published_date' => $createdAt,
                'published_display' => $createdAt !== '' ? date('n/j/y', strtotime($createdAt)) : 'N/A',
                'quantity' => (int) ($sales['quantity'] ?? 0),
                'total_sales' => round((float) ($sales['total_sales'] ?? 0), 2),
            ];
        }, $productRows);
    }

    /**
     * Display the analytics dashboard.
     *
     * @return string|\CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        $guard = $this->ensureSellerAccess(false);
        if ($guard !== null) {
            return $guard;
        }

        $sellerId = $this->currentSellerId();
        try {
            // Get seller order items with parent order/payment statuses.
            $sellerItems = $this->getSellerOrderItemRows($sellerId);
            $products = $this->getSellerCatalogProducts($sellerId);
        } catch (\Exception $e) {
            log_message('error', 'Analytics index error: ' . $e->getMessage());
            $sellerItems = [];
            $products = [];
        }

        // Calculate analytics metrics
        $totalSales = 0;
        $orderIds = [];
        $productIds = [];

        foreach ($sellerItems as $item) {
            if ($this->resolveSalesBucket($item) !== 'completed') {
                continue;
            }

            $totalSales += (float) ($item['subtotal'] ?? 0);
            if (!in_array($item['order_id'], $orderIds, true)) {
                $orderIds[] = $item['order_id'];
            }
            if (!in_array($item['product_id'], $productIds, true)) {
                $productIds[] = $item['product_id'];
            }
        }

        $analytics = [
            'total_sales' => round($totalSales, 2),
            'total_orders' => count($orderIds),
            'product_count' => count($productIds),
        ];

        return view('analytics', [
            'analytics' => $analytics,
            'products' => $products,
        ]);
    }

    /**
     * Get sales data for chart.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function sales()
    {
        $guard = $this->ensureSellerAccess(true);
        if ($guard !== null) {
            return $guard;
        }

        try {
            $sellerId = $this->currentSellerId();
            $orderItemModel = new OrderItemModel();

            // Get recent seller items and aggregate them by week.
            $items = $orderItemModel
                ->where('seller_id', $sellerId)
                ->orderBy('created_at', 'DESC')
                ->limit(300)
                ->findAll();
            $weeklySales = [];

            foreach ($items as $item) {
                $status = $this->normalizeItemStatus((string) ($item['item_status'] ?? 'pending'));
                if ($this->isCancelledLikeStatus($status)) {
                    continue;
                }

                $timestamp = strtotime((string) ($item['created_at'] ?? 'now'));
                $weekStart = date('Y-m-d', strtotime('monday this week', $timestamp));
                if (!isset($weeklySales[$weekStart])) {
                    $weeklySales[$weekStart] = 0.0;
                }

                $weeklySales[$weekStart] += (float) ($item['subtotal'] ?? 0);
            }

            if (empty($weeklySales)) {
                return $this->response->setJSON([
                    'labels' => [],
                    'data' => [],
                ]);
            }

            ksort($weeklySales);
            if (count($weeklySales) > 8) {
                $weeklySales = array_slice($weeklySales, -8, null, true);
            }

            $labels = [];
            $data = [];
            foreach ($weeklySales as $weekStart => $amount) {
                $weekStartTs = strtotime($weekStart);
                $weekEndTs = strtotime('+6 days', $weekStartTs);
                $labels[] = date('M d', $weekStartTs) . ' - ' . date('M d', $weekEndTs);
                $data[] = round($amount, 2);
            }

            return $this->response->setJSON([
                'labels' => $labels,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Analytics sales chart error: ' . $e->getMessage());
            return $this->response->setJSON([
                'labels' => [],
                'data' => [],
                'error' => 'Failed to load sales data'
            ], 500);
        }
    }

    /**
     * Get orders data for chart.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function orders()
    {
        $guard = $this->ensureSellerAccess(true);
        if ($guard !== null) {
            return $guard;
        }

        try {
            $sellerId = $this->currentSellerId();
            $db = \Config\Database::connect();

            // Get order item statuses count for pending, completed, and refund buckets.
            $items = $db->table('order_items oi')
                ->select('oi.item_status, o.status AS order_status, o.payment_status')
                ->join('orders o', 'o.order_id = oi.order_id', 'inner')
                ->where('oi.seller_id', $sellerId)
                ->get()
                ->getResultArray();

            $statusCount = [
                'pending' => 0,
                'completed' => 0,
                'refund' => 0,
            ];

            foreach ($items as $item) {
                $rawStatus = strtolower(trim((string) ($item['item_status'] ?? 'pending')));
                $orderStatus = strtolower(trim((string) ($item['order_status'] ?? '')));
                $paymentStatus = strtolower(trim((string) ($item['payment_status'] ?? '')));

                $effectiveStatus = $rawStatus;
                if ($effectiveStatus === '') {
                    if ($orderStatus !== '') {
                        $effectiveStatus = $orderStatus;
                    } elseif ($paymentStatus !== '') {
                        $effectiveStatus = $paymentStatus;
                    }
                }

                if (in_array($effectiveStatus, ['refunded', 'returned', 'cancelled'], true)
                    || in_array($orderStatus, ['refunded', 'cancelled'], true)
                    || $paymentStatus === 'refunded') {
                    $statusCount['refund']++;
                } elseif (
                    in_array($effectiveStatus, ['completed', 'delivered', 'paid', 'success', 'succeeded'], true)
                    || in_array($orderStatus, ['completed'], true)
                    || in_array($paymentStatus, ['completed'], true)
                ) {
                    $statusCount['completed']++;
                } else {
                    $statusCount['pending']++;
                }
            }

            return $this->response->setJSON([
                'labels' => ['Pending', 'Completed', 'Refund'],
                'data' => [
                    $statusCount['pending'],
                    $statusCount['completed'],
                    $statusCount['refund'],
                ],
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Analytics orders chart error: ' . $e->getMessage());
            return $this->response->setJSON([
                'labels' => ['Pending', 'Completed', 'Refund'],
                'data' => [0, 0, 0],
                'error' => 'Failed to load orders data'
            ], 500);
        }
    }

    /**
     * Get products data for chart.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function products()
    {
        $guard = $this->ensureSellerAccess(true);
        if ($guard !== null) {
            return $guard;
        }

        try {
            $sellerId = $this->currentSellerId();
            $catalogProducts = $this->getSellerCatalogProducts($sellerId);

            if (empty($catalogProducts)) {
                return $this->response->setJSON([
                    'labels' => [],
                    'data' => [],
                    'topLabels' => [],
                    'topData' => [],
                    'tableProducts' => [],
                ]);
            }

            $productRows = $catalogProducts;

            // Sort by quantity and get top 5
            usort($productRows, function($a, $b) {
                return $b['quantity'] <=> $a['quantity'];
            });
            $topProducts = array_slice($productRows, 0, 5);
            $topThreeProducts = array_slice($productRows, 0, 3);

            $labels = [];
            $data = [];

            foreach ($topProducts as $product) {
                $title = (string) ($product['product_name'] ?? 'Unknown');
                $labels[] = strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title;
                $data[] = (int) ($product['quantity'] ?? 0);
            }

            $topLabels = [];
            $topData = [];
            foreach ($topThreeProducts as $product) {
                $title = (string) ($product['product_name'] ?? 'Unknown');
                $topLabels[] = strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title;
                $topData[] = (int) ($product['quantity'] ?? 0);
            }

            return $this->response->setJSON([
                'labels' => $labels,
                'data' => $data,
                'topLabels' => $topLabels,
                'topData' => $topData,
                'tableProducts' => array_map(static function (array $product): array {
                    return [
                        'product_name' => (string) ($product['product_name'] ?? 'N/A'),
                        'category' => (string) ($product['category'] ?? 'General'),
                        'published_display' => (string) ($product['published_display'] ?? 'N/A'),
                        'price' => (float) ($product['price'] ?? 0),
                    ];
                }, $catalogProducts),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Analytics products chart error: ' . $e->getMessage());
            return $this->response->setJSON([
                'labels' => [],
                'data' => [],
                'topLabels' => [],
                'topData' => [],
                'tableProducts' => [],
                'error' => 'Failed to load products data'
            ], 500);
        }
    }
}
