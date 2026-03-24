<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;

class Cart extends BaseController
{
    private const PAYMONGO_API_BASE_URL = 'https://api.paymongo.com/v1';
    private const PAYMENT_METHOD_TYPES = ['gcash', 'paymaya', 'grab_pay', 'card', 'qrph'];

    private CartModel $cartModel;
    private ProductModel $productModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->cartModel    = new CartModel();
        $this->productModel = new ProductModel();
    }

    private function currentUserId(): int
    {
        return (int) session()->get('userId');
    }

    private function getRequestJsonArray(): array
    {
        $contentType = strtolower((string) $this->request->getHeaderLine('Content-Type'));
        if (strpos($contentType, 'application/json') === false) {
            return [];
        }

        try {
            $json = $this->request->getJSON(true);
            return is_array($json) ? $json : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function markOrderAsCompleted(int $orderId, int $userId): void
    {
        if ($orderId <= 0 || $userId <= 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $db = \Config\Database::connect();

        $db->table('orders')
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->update([
                'payment_status' => 'completed',
                'status'         => 'completed',
                'updated_at'     => $now,
            ]);

        // order_items.item_status enum does not support "completed".
        // Use "delivered" as the completed-equivalent value for purchased digital items.
        $db->table('order_items')
            ->where('order_id', $orderId)
            ->groupStart()
                ->where('item_status', '')
                ->orWhere('item_status IS NULL', null, false)
                ->orWhere('item_status', 'pending')
                ->orWhere('item_status', 'confirmed')
                ->orWhere('item_status', 'shipped')
                ->orWhere('item_status', 'completed')
            ->groupEnd()
            ->update([
                'item_status' => 'delivered',
                'updated_at'  => $now,
            ]);
    }

    // ----------------------------------------------------------------
    // Display cart page
    // ----------------------------------------------------------------
    public function index()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return redirect()->to('auth/login')->with('error', 'Please login to view your cart.');
        }

        $db = \Config\Database::connect();
        $cartItems = $db->query(
            "SELECT ci.cart_item_id, ci.product_id, ci.price, ci.is_selected,
                    p.title, p.preview_path, p.category,
                    COALESCE(u.full_name, u.email, 'Unknown Seller') AS seller_name
             FROM cart_items ci
             LEFT JOIN products p ON p.id = ci.product_id
             LEFT JOIN users u ON u.id = ci.seller_id
             WHERE ci.user_id = ? AND ci.deleted_at IS NULL
             ORDER BY ci.created_at DESC",
            [$userId]
        )->getResultArray();

        return view('cart', ['cart_items' => $cartItems]);
    }

    // ----------------------------------------------------------------
    // Add product to cart (POST form or AJAX)
    // ----------------------------------------------------------------
    public function add($product_id = null)
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Login required.'])->setStatusCode(401);
            }
            return redirect()->to('auth/login')->with('error', 'Please login to add items to your cart.');
        }

        // Accept product_id from URL segment, POST body, or JSON
        if (!$product_id) {
            $json = $this->request->getJSON(true) ?? [];
            $product_id = $json['product_id'] ?? $this->request->getPost('product_id');
        }

        $product_id = (int) $product_id;
        if ($product_id <= 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid product.'])->setStatusCode(400);
            }
            return redirect()->back()->with('error', 'Invalid product.');
        }

        $product = $this->productModel->find($product_id);
        if (!$product || ($product['status'] ?? '') !== 'active') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Product not found or unavailable.'])->setStatusCode(404);
            }
            return redirect()->back()->with('error', 'Product not found or unavailable.');
        }

        // Sellers cannot add their own product
        if ((int) $product['seller_id'] === $userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'You cannot add your own product to cart.']);
            }
            return redirect()->back()->with('error', 'You cannot add your own product to cart.');
        }

        $cartItemId = $this->cartModel->addToCart(
            $userId,
            $product_id,
            (int) $product['seller_id'],
            (float) $product['price']
        );

        if ($cartItemId === null) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add to cart.'])->setStatusCode(500);
            }
            return redirect()->back()->with('error', 'Failed to add product to cart.');
        }

        $cartCount = $this->cartModel->getItemCount($userId);
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Added to cart.',
                'cart_count' => $cartCount,
                'csrfHash' => csrf_hash(),
            ]);
        }
        return redirect()->to('cart')->with('success', 'Product added to your cart.');
    }

    // ----------------------------------------------------------------
    // Remove item (AJAX)
    // ----------------------------------------------------------------
    public function remove()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Login required.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(401);
        }

        $json   = $this->getRequestJsonArray();
        $itemId = (int) ($this->request->getPost('item_id') ?? ($json['item_id'] ?? 0));
        if ($itemId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid item.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(400);
        }

        $success   = $this->cartModel->removeFromCart($itemId, $userId);
        $cartCount = $this->cartModel->getItemCount($userId);

        return $this->response->setJSON([
            'success'    => $success,
            'message'    => $success ? 'Item removed.' : 'Failed to remove item.',
            'cart_count' => $cartCount,
            'csrfHash'   => csrf_hash(),
        ]);
    }

    // ----------------------------------------------------------------
    // Toggle selection state (AJAX)
    // ----------------------------------------------------------------
    public function update_selection()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Login required.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(401);
        }

        $json     = $this->getRequestJsonArray();
        $itemId   = (int) ($this->request->getPost('item_id') ?? ($json['item_id'] ?? 0));
        $selectedRaw = $this->request->getPost('selected');
        $selected = $selectedRaw !== null
            ? filter_var($selectedRaw, FILTER_VALIDATE_BOOLEAN)
            : (bool) ($json['selected'] ?? false);

        if ($itemId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid item.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(400);
        }

        $success = $this->cartModel->updateSelection($itemId, $userId, $selected);
        return $this->response->setJSON([
            'success' => $success,
            'csrfHash' => csrf_hash(),
        ]);
    }

    // ----------------------------------------------------------------
    // Cart item count badge endpoint
    // ----------------------------------------------------------------
    public function get_count()
    {
        $userId = $this->currentUserId();
        $count  = $userId > 0 ? $this->cartModel->getItemCount($userId) : 0;
        return $this->response->setJSON([
            'success' => true,
            'count' => $count,
            'csrfHash' => csrf_hash(),
        ]);
    }

    // ----------------------------------------------------------------
    // Clear entire cart
    // ----------------------------------------------------------------
    public function clear()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Login required.',
                    'csrfHash' => csrf_hash(),
                ])->setStatusCode(401);
            }
            return redirect()->to('auth/login');
        }

        $this->cartModel->clearCart($userId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cart cleared.',
                'csrfHash' => csrf_hash(),
            ]);
        }
        return redirect()->to('cart')->with('success', 'Cart cleared.');
    }

    // ----------------------------------------------------------------
    // Proceed to checkout (POST from cart page)
    // ----------------------------------------------------------------
    public function checkout()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to checkout.',
                    'csrfHash' => csrf_hash(),
                ])->setStatusCode(401);
            }
            return redirect()->to('auth/login')->with('error', 'Please login to checkout.');
        }

        $db = \Config\Database::connect();
        $selectedItems = $db->query(
            "SELECT ci.cart_item_id, ci.product_id, ci.seller_id, ci.quantity, ci.price,
                    p.title, p.category
             FROM cart_items ci
             LEFT JOIN products p ON p.id = ci.product_id
             WHERE ci.user_id = ? AND ci.is_selected = 1 AND ci.deleted_at IS NULL",
            [$userId]
        )->getResultArray();

        if (empty($selectedItems)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please select at least one item to checkout.',
                    'csrfHash' => csrf_hash(),
                ])->setStatusCode(400);
            }
            return redirect()->to('cart')->with('error', 'Please select at least one item to checkout.');
        }

        $subtotal = 0.0;
        foreach ($selectedItems as $item) {
            $subtotal += ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 1));
        }

        $taxAmount = 0.0;
        $discountAmount = 0.0;
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        $orderNumber = 'ORD-' . time() . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        $now = date('Y-m-d H:i:s');

        $db->transStart();

        $db->table('orders')->insert([
            'order_number' => $orderNumber,
            'user_id' => $userId,
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'tax_rate' => 12.00,
            'discount_amount' => round($discountAmount, 2),
            'total_amount' => round($totalAmount, 2),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'created_at' => $now,
        ]);

        $orderId = (int) $db->insertID();

        foreach ($selectedItems as $item) {
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $unitPrice = (float) ($item['price'] ?? 0);

            $db->table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => (int) ($item['product_id'] ?? 0),
                'seller_id' => (int) ($item['seller_id'] ?? 0),
                'product_title' => (string) ($item['title'] ?? 'Unknown Product'),
                'product_category' => (string) ($item['category'] ?? ''),
                'quantity' => $quantity,
                'unit_price' => round($unitPrice, 2),
                'discount_per_item' => 0,
                'subtotal' => round($unitPrice * $quantity, 2),
                'item_status' => 'pending',
                'created_at' => $now,
            ]);
        }

        $cartItemIds = array_map(static fn(array $item): int => (int) ($item['cart_item_id'] ?? 0), $selectedItems);
        $cartItemIds = array_values(array_filter($cartItemIds));
        if (!empty($cartItemIds)) {
            $db->table('cart_items')
                ->where('user_id', $userId)
                ->whereIn('cart_item_id', $cartItemIds)
                ->delete();
        }

        $db->transComplete();

        if ($db->transStatus() === false || $orderId <= 0) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create your order. Please try again.',
                    'csrfHash' => csrf_hash(),
                ])->setStatusCode(500);
            }
            return redirect()->to('cart')->with('error', 'Failed to create your order. Please try again.');
        }

        $payMongoCheckout = $this->createPayMongoProductCheckoutSessionViaCurl(
            $userId,
            $orderId,
            $orderNumber,
            $selectedItems
        );

        if (!$payMongoCheckout || empty($payMongoCheckout['checkout_url'])) {
            // PayMongo unavailable (e.g. no API key in dev) — fall back to local test payment page
            log_message('info', 'PayMongo unavailable for order ' . $orderNumber . '. Using local authorize-test fallback.');
            $checkoutUrl       = base_url('cart/payment-authorize-test');
            $checkoutSessionId = 'local_' . bin2hex(random_bytes(6));
            $checkoutSource    = 'local_authorize_fallback';
        } else {
            $checkoutUrl       = (string) $payMongoCheckout['checkout_url'];
            $checkoutSessionId = $payMongoCheckout['id'] ?? null;
            $checkoutSource    = 'paymongo_checkout_session';
        }

        session()->set('pending_product_payment', [
            'user_id' => $userId,
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'checkout_session_id' => $checkoutSessionId,
            'checkout_url' => $checkoutUrl,
            'amount' => round($totalAmount, 2),
            'currency' => 'PHP',
            'status' => 'pending',
            'source' => $checkoutSource,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'PayMongo checkout session created. Redirecting to payment gateway...',
                'data' => [
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'checkout_session_id' => $checkoutSessionId,
                    'checkout_url' => $checkoutUrl,
                    'source' => $checkoutSource,
                    'currency' => 'PHP',
                    'amount' => round($totalAmount, 2),
                ],
                'csrfHash' => csrf_hash(),
            ]);
        }

        return redirect()->to($checkoutUrl);
    }

    public function payment_success()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return redirect()->to('auth/login')->with('error', 'Please login first.');
        }

        $pendingPayment = session()->get('pending_product_payment');
        if (!is_array($pendingPayment)) {
            $simulationPayment = session()->get('digital_product_payment_simulation');
            if (is_array($simulationPayment)) {
                $pendingPayment = [
                    'user_id' => (int) ($simulationPayment['user_id'] ?? 0),
                    'order_id' => (int) ($simulationPayment['order_id'] ?? 0),
                    'order_number' => (string) ($simulationPayment['order_number'] ?? $simulationPayment['reference'] ?? ''),
                    'amount' => (float) ($simulationPayment['amount'] ?? $simulationPayment['total_amount'] ?? 0),
                    'currency' => (string) ($simulationPayment['currency'] ?? 'PHP'),
                    'status' => (string) ($simulationPayment['status'] ?? 'pending'),
                    'source' => (string) ($simulationPayment['source'] ?? 'payment_simulation_digital_product'),
                ];
                session()->set('pending_product_payment', $pendingPayment);
            } else {
                return redirect()->to('orders')->with('warning', 'No pending product payment session found.');
            }
        }

        $orderId      = (int) ($pendingPayment['order_id'] ?? 0);
        $pendingUserId = (int) ($pendingPayment['user_id'] ?? 0);

        if ($pendingUserId !== $userId) {
            return redirect()->to('orders')->with('error', 'Invalid payment session context.');
        }

        $orderNumber = (string) ($pendingPayment['order_number'] ?? ($orderId > 0 ? '#' . $orderId : 'N/A'));

        if ($orderId > 0) {
            // Normal cart checkout — update the order in the database.
            $this->markOrderAsCompleted($orderId, $userId);

            $this->createBuyerPurchaseNotification($userId, $orderId, $orderNumber);
            $this->createSellerPurchaseNotifications($userId, $orderId, $orderNumber);
            $this->sendProductPurchaseReceiptEmail($userId, $orderId, $orderNumber, $pendingPayment);
        }
        // order_id == 0 means the session was created via PaymentSimulation (no DB order);
        // the payment is still acknowledged and the buyer is redirected normally.

        session()->remove('pending_product_payment');
        session()->remove('digital_product_payment_simulation');

        return redirect()->to('my-orders')->with('success', 'Payment successful. Order ' . $orderNumber . ' is now completed.');
    }

    public function payment_cancel()
    {
        $pendingPayment = session()->get('pending_product_payment');
        if (is_array($pendingPayment)) {
            $pendingPayment['status'] = 'cancelled';
            $pendingPayment['updated_at'] = date('Y-m-d H:i:s');
            session()->set('pending_product_payment', $pendingPayment);
            $orderNumber = (string) ($pendingPayment['order_number'] ?? '');
            return redirect()->to('orders')->with('warning', 'Payment was cancelled' . ($orderNumber !== '' ? ' for order ' . $orderNumber : '') . '.');
        }

        return redirect()->to('orders')->with('warning', 'Payment was cancelled.');
    }

    public function payment_authorize_test()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return redirect()->to('auth/login')->with('error', 'Please login first.');
        }

        $pendingPayment = session()->get('pending_product_payment');
        if (!is_array($pendingPayment)) {
            return redirect()->to('orders')->with('warning', 'No pending product payment session found.');
        }

        $pendingUserId = (int) ($pendingPayment['user_id'] ?? 0);
        if ($pendingUserId !== $userId) {
            return redirect()->to('orders')->with('error', 'Invalid payment session context.');
        }

        return view('payment_product_authorize', [
            'orderNumber' => (string) ($pendingPayment['order_number'] ?? ''),
            'amount' => number_format((float) ($pendingPayment['amount'] ?? 0), 2),
            'currency' => (string) ($pendingPayment['currency'] ?? 'PHP'),
            'cancelUrl' => base_url('cart/payment-cancel'),
            'markPaidUrl' => base_url('cart/payment-mark-paid'),
        ]);
    }

    public function mark_product_payment_paid()
    {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login first.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(401);
        }

        $pendingPayment = session()->get('pending_product_payment');
        if (!is_array($pendingPayment)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No pending product payment session found.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(404);
        }

        $orderId = (int) ($pendingPayment['order_id'] ?? 0);
        $pendingUserId = (int) ($pendingPayment['user_id'] ?? 0);
        if ($orderId <= 0 || $pendingUserId !== $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid payment session context.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(400);
        }

        $this->markOrderAsCompleted($orderId, $userId);

        $orderNumber = (string) ($pendingPayment['order_number'] ?? ('#' . $orderId));
        $this->createBuyerPurchaseNotification($userId, $orderId, $orderNumber);
        $this->createSellerPurchaseNotifications($userId, $orderId, $orderNumber);
        $this->sendProductPurchaseReceiptEmail($userId, $orderId, $orderNumber, $pendingPayment);

        session()->remove('pending_product_payment');

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Payment authorized successfully.',
            'redirect_url' => base_url('my-orders'),
            'csrfHash' => csrf_hash(),
        ]);
    }

    private function createBuyerPurchaseNotification(int $userId, int $orderId, string $orderNumber = ''): void
    {
        if ($userId <= 0 || $orderId <= 0) {
            return;
        }

        try {
            $db = \Config\Database::connect();

            $alreadyExists = (int) $db->table('notifications')
                ->where('user_id', $userId)
                ->where('type', 'purchase')
                ->where('related_id', $orderId)
                ->countAllResults();

            if ($alreadyExists > 0) {
                return;
            }

            $items = $db->table('order_items')
                ->select('product_title')
                ->where('order_id', $orderId)
                ->orderBy('order_item_id', 'ASC')
                ->get()
                ->getResultArray();

            $message = 'completed your purchase.';
            if (!empty($items)) {
                $firstTitle = trim((string) ($items[0]['product_title'] ?? '')); 
                if ($firstTitle !== '') {
                    $extraCount = max(0, count($items) - 1);
                    $message = 'bought ' . $firstTitle;
                    if ($extraCount > 0) {
                        $message .= ' and ' . $extraCount . ' more item' . ($extraCount > 1 ? 's' : '');
                    }
                    $message .= '.';
                }
            } elseif ($orderNumber !== '') {
                $message = 'completed order ' . $orderNumber . '.';
            }

            $db->table('notifications')->insert([
                'user_id' => $userId,
                'message' => $message,
                'type' => 'purchase',
                'read' => 0,
                'related_id' => $orderId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Failed to create buyer purchase notification: ' . $e->getMessage());
        }
    }

        private function sendProductPurchaseReceiptEmail(int $userId, int $orderId, string $orderNumber = '', array $paymentContext = []): void
        {
                if ($userId <= 0 || $orderId <= 0) {
                        return;
                }

                try {
                        $db = \Config\Database::connect();

                        $buyer = $db->table('users')
                                ->select('full_name, email')
                                ->where('id', $userId)
                                ->get()
                                ->getRowArray();

                        $emailTo = trim((string) ($buyer['email'] ?? ''));
                        if ($emailTo === '') {
                                return;
                        }

                        $order = $db->table('orders')
                                ->select('order_number, subtotal, tax_amount, discount_amount, total_amount, payment_method_type, completed_at, updated_at, created_at')
                                ->where('order_id', $orderId)
                                ->where('user_id', $userId)
                                ->get()
                                ->getRowArray();

                        if (empty($order)) {
                                return;
                        }

                        $items = $db->table('order_items oi')
                                ->select('oi.product_title, oi.quantity, oi.unit_price, oi.subtotal, COALESCE(u.full_name, u.email, "Seller") AS seller_name')
                                ->join('users u', 'u.id = oi.seller_id', 'left')
                                ->where('oi.order_id', $orderId)
                                ->orderBy('oi.order_item_id', 'ASC')
                                ->get()
                                ->getResultArray();

                        if (empty($items)) {
                                return;
                        }

                        $buyerName = esc(trim((string) ($buyer['full_name'] ?? 'ByteMarket Buyer')));
                        $safeOrderNumber = esc((string) ($order['order_number'] ?? $orderNumber));
                        $receiptNumber = 'BMP-' . strtoupper(substr(sha1((string) ($safeOrderNumber . $emailTo)), 0, 10));
                        $paymentMethod = esc((string) ($order['payment_method_type'] ?: ($paymentContext['source'] ?? 'online payment')));
                        $paidAt = esc((string) ($order['completed_at'] ?? $order['updated_at'] ?? $order['created_at'] ?? date('Y-m-d H:i:s')));
                        $subtotal = number_format((float) ($order['subtotal'] ?? 0), 2);
                        $taxAmount = number_format((float) ($order['tax_amount'] ?? 0), 2);
                        $discountAmount = number_format((float) ($order['discount_amount'] ?? 0), 2);
                        $totalAmount = number_format((float) ($order['total_amount'] ?? 0), 2);

                        $itemRows = '';
                        foreach ($items as $item) {
                                $title = esc((string) ($item['product_title'] ?? 'Product'));
                                $sellerName = esc((string) ($item['seller_name'] ?? 'Seller'));
                                $quantity = (int) ($item['quantity'] ?? 1);
                                $unitPrice = number_format((float) ($item['unit_price'] ?? 0), 2);
                                $lineTotal = number_format((float) ($item['subtotal'] ?? 0), 2);

                                $itemRows .= <<<HTML
<tr>
    <td style="padding:12px 0;border-bottom:1px solid #e9eef3;">
        <div style="font-size:14px;font-weight:600;color:#1c2b3a;">{$title}</div>
        <div style="font-size:12px;color:#6b7280;">Seller: {$sellerName}</div>
    </td>
    <td style="padding:12px 8px;border-bottom:1px solid #e9eef3;text-align:center;font-size:13px;color:#444;">{$quantity}</td>
    <td style="padding:12px 8px;border-bottom:1px solid #e9eef3;text-align:right;font-size:13px;color:#444;">PHP {$unitPrice}</td>
    <td style="padding:12px 0;border-bottom:1px solid #e9eef3;text-align:right;font-size:13px;font-weight:600;color:#1c2b3a;">PHP {$lineTotal}</td>
</tr>
HTML;
                        }

                        $siteUrl = base_url();
                        $year = date('Y');

                        $email = \Config\Services::email();
                        $email->setFrom('bytemarket730@gmail.com', 'Byte Market');
                        $email->setTo($emailTo);
                        $email->setSubject('ByteMarket Purchase Receipt ' . ($safeOrderNumber !== '' ? '- ' . strip_tags($safeOrderNumber) : ''));
                        $email->setMessage(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>ByteMarket Purchase Receipt</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:36px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:#1c2b3a;padding:28px 36px;text-align:center;">
                            <span style="color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;">ByteMarket</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:4px;background:linear-gradient(90deg,#2f80d0,#22a43a);"></td>
                    </tr>
                    <tr>
                        <td style="padding:34px 40px 24px;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:#2f80d0;">Purchase Receipt</p>
                            <h1 style="margin:0 0 14px;font-size:24px;line-height:1.25;color:#1c2b3a;">Thank you for your purchase</h1>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#444;">Hi {$buyerName}, your payment was successful. Here is your ByteMarket receipt.</p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:8px;padding:18px 20px;margin-bottom:20px;">
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Receipt No.</strong>{$receiptNumber}</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Order No.</strong>{$safeOrderNumber}</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Payment Method</strong>{$paymentMethod}</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Paid At</strong>{$paidAt}</td></tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px;">
                                <thead>
                                    <tr>
                                        <th style="padding:0 0 10px;text-align:left;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Item</th>
                                        <th style="padding:0 8px 10px;text-align:center;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Qty</th>
                                        <th style="padding:0 8px 10px;text-align:right;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Unit</th>
                                        <th style="padding:0 0 10px;text-align:right;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {$itemRows}
                                </tbody>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
                                <tr><td style="padding:4px 0;font-size:13px;color:#555;">Subtotal</td><td style="padding:4px 0;font-size:13px;color:#1c2b3a;text-align:right;">PHP {$subtotal}</td></tr>
                                <tr><td style="padding:4px 0;font-size:13px;color:#555;">Tax</td><td style="padding:4px 0;font-size:13px;color:#1c2b3a;text-align:right;">PHP {$taxAmount}</td></tr>
                                <tr><td style="padding:4px 0;font-size:13px;color:#555;">Discount</td><td style="padding:4px 0;font-size:13px;color:#1c2b3a;text-align:right;">PHP {$discountAmount}</td></tr>
                                <tr><td style="padding:10px 0 0;font-size:15px;font-weight:700;color:#1c2b3a;border-top:1px solid #e9eef3;">Grand Total</td><td style="padding:10px 0 0;font-size:15px;font-weight:700;color:#1c2b3a;text-align:right;border-top:1px solid #e9eef3;">PHP {$totalAmount}</td></tr>
                            </table>

                            <div style="text-align:center;">
                                <a href="{$siteUrl}my-orders" style="display:inline-block;background:#2f80d0;color:#fff;text-decoration:none;padding:13px 34px;border-radius:8px;font-size:14px;font-weight:600;letter-spacing:0.4px;">View My Orders</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#1c2b3a;padding:22px 36px;text-align:center;">
                            <p style="margin:0 0 6px;color:#8fa3b1;font-size:12px;">&copy; {$year} ByteMarket. All rights reserved.</p>
                            <p style="margin:0;font-size:12px;">
                                <a href="{$siteUrl}" style="color:#4a90d9;text-decoration:none;">Visit our website</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{$siteUrl}privacy" style="color:#4a90d9;text-decoration:none;">Privacy Policy</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{$siteUrl}terms" style="color:#4a90d9;text-decoration:none;">Terms of Service</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML);
                        $email->send(false);
                } catch (\Throwable $e) {
                        log_message('warning', 'Failed to send buyer purchase receipt email for order ' . $orderId . ': ' . $e->getMessage());
                }
        }

    private function createSellerPurchaseNotifications(int $buyerId, int $orderId, string $orderNumber = ''): void
    {
        if ($buyerId <= 0 || $orderId <= 0) {
            return;
        }

        try {
            $db = \Config\Database::connect();

            $buyerRow = $db->table('users')
                ->select('full_name')
                ->where('id', $buyerId)
                ->get()
                ->getRowArray();

            $buyerName = trim((string) ($buyerRow['full_name'] ?? ''));
            if ($buyerName === '') {
                $buyerName = 'A buyer';
            }

            $items = $db->table('order_items')
                ->select('seller_id, product_title')
                ->where('order_id', $orderId)
                ->orderBy('order_item_id', 'ASC')
                ->get()
                ->getResultArray();

            if (empty($items)) {
                return;
            }

            $itemsBySeller = [];
            foreach ($items as $item) {
                $sellerId = (int) ($item['seller_id'] ?? 0);
                if ($sellerId <= 0) {
                    continue;
                }

                if (!isset($itemsBySeller[$sellerId])) {
                    $itemsBySeller[$sellerId] = [];
                }

                $title = trim((string) ($item['product_title'] ?? ''));
                if ($title !== '') {
                    $itemsBySeller[$sellerId][] = $title;
                }
            }

            foreach ($itemsBySeller as $sellerId => $titles) {
                $alreadyExists = (int) $db->table('notifications')
                    ->where('user_id', (int) $sellerId)
                    ->where('type', 'sale')
                    ->where('related_id', $orderId)
                    ->countAllResults();

                if ($alreadyExists > 0) {
                    continue;
                }

                $message = $buyerName . ' bought your product.';
                if (!empty($titles)) {
                    $firstTitle = $titles[0];
                    $extraCount = max(0, count($titles) - 1);
                    $message = $buyerName . ' bought ' . $firstTitle;
                    if ($extraCount > 0) {
                        $message .= ' and ' . $extraCount . ' more item' . ($extraCount > 1 ? 's' : '');
                    }
                    $message .= '.';
                } elseif ($orderNumber !== '') {
                    $message = $buyerName . ' completed order ' . $orderNumber . '.';
                }

                $db->table('notifications')->insert([
                    'user_id' => (int) $sellerId,
                    'message' => $message,
                    'type' => 'sale',
                    'read' => 0,
                    'related_id' => $orderId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Failed to create seller purchase notifications: ' . $e->getMessage());
        }
    }

    private function createPayMongoProductCheckoutSessionViaCurl(int $userId, int $orderId, string $orderNumber, array $selectedItems): ?array
    {
        $secretKey = trim((string) (env('PAYMONGO_SECRET_KEY') ?? env('paymongo.secretKey') ?? ''));
        if ($secretKey === '') {
            log_message('warning', 'PAYMONGO_SECRET_KEY not set. Product checkout session cannot be created.');
            return null;
        }

        $lineItems = [];
        $computedTotal = 0.0;

        foreach ($selectedItems as $item) {
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $unitPrice = (float) ($item['price'] ?? 0);
            if ($unitPrice <= 0) {
                continue;
            }

            $rawTitle = trim((string) ($item['title'] ?? ''));
            if ($rawTitle === '') {
                $rawTitle = 'Product #' . (int) ($item['product_id'] ?? 0);
            }

            $productTitle = function_exists('mb_substr')
                ? mb_substr($rawTitle, 0, 120)
                : substr($rawTitle, 0, 120);

            $lineItems[] = [
                'currency' => 'PHP',
                'amount' => max(1, (int) round($unitPrice * 100)),
                'name' => $productTitle,
                'quantity' => $quantity,
            ];

            $computedTotal += ($unitPrice * $quantity);
        }

        if (empty($lineItems)) {
            $lineItems[] = [
                'currency' => 'PHP',
                'amount' => 100,
                'name' => 'ByteMarket Order ' . $orderNumber,
                'quantity' => 1,
            ];
            $computedTotal = 1.0;
        }

        $payload = [
            'data' => [
                'attributes' => [
                    'line_items' => $lineItems,
                    'payment_method_types' => self::PAYMENT_METHOD_TYPES,
                    'success_url' => base_url('cart/payment-success'),
                    'cancel_url' => base_url('cart/payment-cancel'),
                    'description' => 'Product order checkout session for ' . $orderNumber,
                    'metadata' => [
                        'user_id' => (string) $userId,
                        'order_id' => (string) $orderId,
                        'order_number' => $orderNumber,
                        'total_amount' => number_format($computedTotal, 2, '.', ''),
                        'item_count' => (string) count($lineItems),
                        'source' => 'cart_checkout',
                    ],
                ],
            ],
        ];

        $response = null;
        $httpCode = 0;

        if (\function_exists('curl_init')) {
            $curl = \curl_init(self::PAYMONGO_API_BASE_URL . '/checkout_sessions');
            \curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $secretKey . ':',
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Basic ' . base64_encode($secretKey . ':'),
                ],
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ]);

            $response = \curl_exec($curl);
            $httpCode = (int) \curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = \curl_error($curl);
            \curl_close($curl);

            if ($response === false || $curlError !== '') {
                log_message('error', 'PayMongo product checkout cURL error: ' . $curlError);
                return null;
            }
        } else {
            $jsonPayload = json_encode($payload);
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n"
                        . "Accept: application/json\r\n"
                        . 'Authorization: Basic ' . base64_encode($secretKey . ':') . "\r\n",
                    'content' => $jsonPayload,
                    'ignore_errors' => true,
                    'timeout' => 30,
                ],
            ]);

            $response = @file_get_contents(self::PAYMONGO_API_BASE_URL . '/checkout_sessions', false, $context);
            if ($response === false) {
                $error = error_get_last();
                log_message('error', 'PayMongo product checkout HTTP stream error: ' . ($error['message'] ?? 'Unknown error'));
                return null;
            }

            if (isset($http_response_header[0]) && preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches)) {
                $httpCode = (int) ($matches[1] ?? 0);
            }
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            log_message('error', 'PayMongo product checkout non-success status: ' . $httpCode . ' response: ' . $response);
            return null;
        }

        $decoded = json_decode((string) $response, true);
        if (!is_array($decoded) || !isset($decoded['data']['id'])) {
            log_message('error', 'Invalid PayMongo product checkout response shape: ' . $response);
            return null;
        }

        $checkoutUrl = $decoded['data']['attributes']['checkout_url'] ?? null;
        if (!$checkoutUrl) {
            log_message('error', 'PayMongo product checkout response missing checkout_url: ' . $response);
            return null;
        }

        return [
            'id' => (string) $decoded['data']['id'],
            'checkout_url' => (string) $checkoutUrl,
        ];
    }

}
