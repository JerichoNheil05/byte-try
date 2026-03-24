<?php

namespace App\Controllers;

class PaymentSimulation extends BaseController
{
    private const SELLER_SUBSCRIPTION_CANCEL_URL = 'http://localhost/bytemarket/public/subscription';
    private const SELLER_SUBSCRIPTION_CHECKOUT_URL = 'https://checkout-v2.paymongo.com/75f628a14d2f8d242a3180e5';
    private const DIGITAL_PRODUCT_CHECKOUT_REFERENCE_URL = 'https://checkout.paymongo.com/89b11da51fb495a71a951b6f';
    private const PAYMONGO_API_BASE_URL = 'https://api.paymongo.com/v1';
    private const PAYMENT_METHOD_TYPES = ['gcash', 'paymaya', 'grab_pay', 'card', 'qrph'];

    public function createDigitalProductCheckoutSession()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(400);
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please log in to continue.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(401);
        }

        $payload = $this->request->getJSON(true);
        $rawItems = is_array($payload) && isset($payload['items']) && is_array($payload['items'])
            ? $payload['items']
            : [];

        if (empty($rawItems)) {
            $fallbackName = trim((string) ($payload['product_name'] ?? $this->request->getPost('product_name') ?? ''));
            $fallbackPrice = (float) ($payload['amount'] ?? $this->request->getPost('amount') ?? 0);
            $fallbackQty = max(1, (int) ($payload['quantity'] ?? $this->request->getPost('quantity') ?? 1));
            $fallbackThumbnail = trim((string) ($payload['thumbnail_url'] ?? $this->request->getPost('thumbnail_url') ?? ''));

            if ($fallbackName !== '' && $fallbackPrice > 0) {
                $rawItems = [[
                    'name' => $fallbackName,
                    'amount' => $fallbackPrice,
                    'quantity' => $fallbackQty,
                    'thumbnail_url' => $fallbackThumbnail,
                ]];
            }
        }

        $lineItems = [];
        $totalAmount = 0.0;

        foreach ($rawItems as $item) {
            $name = trim((string) ($item['name'] ?? $item['product_name'] ?? ''));
            $amount = (float) ($item['amount'] ?? $item['price'] ?? 0);
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $thumbnailUrl = $this->resolveCheckoutImageUrl(
                trim((string) ($item['thumbnail_url'] ?? $item['image_url'] ?? ''))
            );

            if ($name === '' || $amount <= 0) {
                continue;
            }

            $normalizedName = function_exists('mb_substr') ? mb_substr($name, 0, 120) : substr($name, 0, 120);
            $lineItem = [
                'currency' => 'PHP',
                'amount' => max(1, (int) round($amount * 100)),
                'name' => $normalizedName,
                'quantity' => $quantity,
            ];

            if ($thumbnailUrl !== '') {
                $lineItem['image_url'] = $thumbnailUrl;
            }

            $lineItems[] = $lineItem;

            $totalAmount += ($amount * $quantity);
        }

        if (empty($lineItems)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'At least one valid product with name and amount is required.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(400);
        }

        $reference = 'DPROD-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        // Create the order in the DB immediately so Cart::payment_success() can mark it complete.
        $orderId = 0;
        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('orders') && $db->tableExists('order_items')) {
                $productIds = [];
                $rawItemsByProductId = [];
                foreach ($rawItems as $item) {
                    $pid = (int) ($item['product_id'] ?? 0);
                    if ($pid > 0 && !isset($rawItemsByProductId[$pid])) {
                        $productIds[] = $pid;
                        $rawItemsByProductId[$pid] = $item;
                    }
                }

                $dbOrderItems = [];
                if (!empty($productIds)) {
                    $products = $db->table('products')
                        ->select('id, title, category, seller_id')
                        ->whereIn('id', $productIds)
                        ->get()->getResultArray();

                    foreach ($products as $prod) {
                        $pid = (int) ($prod['id'] ?? 0);
                        $rawItem = $rawItemsByProductId[$pid] ?? [];
                        $qty = max(1, (int) ($rawItem['quantity'] ?? 1));
                        $price = (float) ($rawItem['amount'] ?? $rawItem['price'] ?? 0);
                        if ($pid <= 0 || $price <= 0) {
                            continue;
                        }
                        $dbOrderItems[] = [
                            'product_id'       => $pid,
                            'seller_id'        => (int) ($prod['seller_id'] ?? 0),
                            'product_title'    => trim((string) ($prod['title'] ?? 'Product')) ?: 'Product',
                            'product_category' => (string) ($prod['category'] ?? ''),
                            'quantity'         => $qty,
                            'unit_price'       => round($price, 2),
                            'subtotal'         => round($price * $qty, 2),
                        ];
                    }
                }

                if (!empty($dbOrderItems)) {
                    $orderSubtotal = array_sum(array_column($dbOrderItems, 'subtotal'));
                    $now = date('Y-m-d H:i:s');
                    $db->transStart();
                    $db->table('orders')->insert([
                        'order_number'    => $reference,
                        'user_id'         => $userId,
                        'subtotal'        => round($orderSubtotal, 2),
                        'tax_amount'      => 0.00,
                        'tax_rate'        => 12.00,
                        'discount_amount' => 0.00,
                        'total_amount'    => round($orderSubtotal, 2),
                        'status'          => 'pending',
                        'payment_status'  => 'unpaid',
                        'created_at'      => $now,
                    ]);
                    $orderId = (int) $db->insertID();
                    foreach ($dbOrderItems as $oi) {
                        $db->table('order_items')->insert([
                            'order_id'          => $orderId,
                            'product_id'        => $oi['product_id'],
                            'seller_id'         => $oi['seller_id'],
                            'product_title'     => $oi['product_title'],
                            'product_category'  => $oi['product_category'],
                            'quantity'          => $oi['quantity'],
                            'unit_price'        => $oi['unit_price'],
                            'discount_per_item' => 0,
                            'subtotal'          => $oi['subtotal'],
                            'item_status'       => 'pending',
                            'created_at'        => $now,
                        ]);
                    }
                    $db->transComplete();
                    if ($db->transStatus() === false) {
                        $orderId = 0;
                        log_message('error', 'Failed to create DB order for digital product checkout: ' . $reference);
                    }
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Order creation error (digital product checkout): ' . $e->getMessage());
            $orderId = 0;
        }

        if ($orderId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unable to create order record. Payment was not started. Please try again.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(500);
        }

        $apiCheckout = $this->createDigitalProductCheckoutSessionViaCurl($userId, $reference, $lineItems);
        $localAuthorizeUrl = base_url('payment/digital-product/authorize-test');
        $resolvedCheckoutUrl = $apiCheckout['checkout_url'] ?? $localAuthorizeUrl;
        $resolvedCheckoutSessionId = $apiCheckout['id'] ?? ('cs_sim_' . bin2hex(random_bytes(6)));
        $source = $apiCheckout ? 'paymongo_checkout_session' : 'local_authorize_fallback';

        // Store under the canonical key that Cart::payment_success() reads.
        $pendingSession = [
            'user_id'             => $userId,
            'order_id'            => $orderId,
            'order_number'        => $reference,
            'checkout_session_id' => $resolvedCheckoutSessionId,
            'checkout_url'        => $resolvedCheckoutUrl,
            'amount'              => round($totalAmount, 2),
            'currency'            => 'PHP',
            'status'              => 'pending',
            'source'              => $source,
            'created_at'          => date('Y-m-d H:i:s'),
        ];
        session()->set('pending_product_payment', $pendingSession);
        // Keep legacy key for any code that still reads it.
        session()->set('digital_product_payment_simulation', array_merge($pendingSession, [
            'reference'                    => $reference,
            'line_items'                   => $lineItems,
            'paymongo_checkout_reference'  => self::DIGITAL_PRODUCT_CHECKOUT_REFERENCE_URL,
        ]));

        return $this->response->setJSON([
            'success' => true,
            'message' => $apiCheckout
                ? 'Digital product checkout session created. Redirecting to PayMongo checkout session.'
                : 'PayMongo session unavailable. Redirecting to local authorize test payment page.',
            'data' => [
                'reference' => $reference,
                'checkout_session_id' => $resolvedCheckoutSessionId,
                'checkout_url' => $resolvedCheckoutUrl,
                'amount' => round($totalAmount, 2),
                'currency' => 'PHP',
                'line_items' => $lineItems,
                'source' => $source,
            ],
            'csrfHash' => csrf_hash(),
        ]);
    }

    private function resolveCheckoutImageUrl(string $rawUrl): string
    {
        $rawUrl = trim($rawUrl);
        if ($rawUrl === '') {
            return '';
        }

        $publicAssetBaseUrl = trim((string) (env('PAYMONGO_PUBLIC_ASSET_BASE_URL') ?? ''));

        // Relative path support (e.g. uploads/product-thumbnails/abc.jpg)
        if (!preg_match('/^https?:\/\//i', $rawUrl)) {
            if ($publicAssetBaseUrl === '') {
                $rawUrl = base_url(ltrim($rawUrl, '/'));
            } else {
                $rawUrl = rtrim($publicAssetBaseUrl, '/') . '/' . ltrim($rawUrl, '/');
            }
        }

        if (!preg_match('/^https?:\/\//i', $rawUrl)) {
            return '';
        }

        // Hosted checkout cannot fetch localhost/private network URLs.
        $parsed = parse_url($rawUrl);
        $host = strtolower((string) ($parsed['host'] ?? ''));
        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            if ($publicAssetBaseUrl === '') {
                return '';
            }

            $path = (string) ($parsed['path'] ?? '');
            if ($path === '') {
                return '';
            }

            $rawUrl = rtrim($publicAssetBaseUrl, '/') . '/' . ltrim($path, '/');
        }

        return preg_match('/^https?:\/\//i', $rawUrl) ? $rawUrl : '';
    }

    public function createSellerSubscriptionCheckoutSession()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.',
            ])->setStatusCode(400);
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please log in to continue.',
            ])->setStatusCode(401);
        }

        $checkoutSessionId = 'cs_sim_' . bin2hex(random_bytes(6));
        $apiCheckout = $this->createPayMongoCheckoutSessionViaCurl($userId);
        $resolvedCheckoutSessionId = $apiCheckout['id'] ?? $checkoutSessionId;
        $localAuthorizeUrl = base_url('payment/seller-subscription/authorize-test');
        $resolvedCheckoutUrl = $apiCheckout['checkout_url'] ?? $localAuthorizeUrl;
        $source = $apiCheckout ? 'paymongo_checkout_session' : 'local_authorize_fallback';

        session()->set('seller_subscription_payment_simulation', [
            'user_id' => $userId,
            'checkout_session_id' => $resolvedCheckoutSessionId,
            'checkout_url' => $resolvedCheckoutUrl,
            'payment_method_types' => self::PAYMENT_METHOD_TYPES,
            'amount' => 99.00,
            'currency' => 'PHP',
            'paymongo_checkout_reference' => self::SELLER_SUBSCRIPTION_CHECKOUT_URL,
            'cancel_url' => self::SELLER_SUBSCRIPTION_CANCEL_URL,
            'status' => 'pending',
            'source' => $source,
            'receipt_email_sent' => false,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => $apiCheckout
                ? 'Seller subscription session created. Redirecting to PayMongo checkout session.'
                : 'PayMongo session unavailable. Redirecting to local authorize test payment page.',
            'data' => [
                'checkout_session_id' => $resolvedCheckoutSessionId,
                'cancel_url' => self::SELLER_SUBSCRIPTION_CANCEL_URL,
                'checkout_url' => $resolvedCheckoutUrl,
                'payment_method_types' => self::PAYMENT_METHOD_TYPES,
                'paymongo_checkout_reference' => self::SELLER_SUBSCRIPTION_CHECKOUT_URL,
                'source' => $source,
            ],
        ]);
    }

    private function createPayMongoCheckoutSessionViaCurl(int $userId): ?array
    {
        $secretKey = trim((string) (env('PAYMONGO_SECRET_KEY') ?? env('paymongo.secretKey') ?? ''));
        if ($secretKey === '') {
            log_message('warning', 'PAYMONGO_SECRET_KEY not set. Using fallback checkout URL reference.');
            return null;
        }

        $payload = [
            'data' => [
                'attributes' => [
                    'line_items' => [[
                        'currency' => 'PHP',
                        'amount' => 9900,
                        'name' => 'Byte Market Seller Membership',
                        'quantity' => 1,
                    ]],
                    'payment_method_types' => self::PAYMENT_METHOD_TYPES,
                    'success_url' => base_url('payment/seller-subscription/success'),
                    'cancel_url' => self::SELLER_SUBSCRIPTION_CANCEL_URL,
                    'description' => 'Seller membership checkout session',
                    'metadata' => [
                        'user_id' => (string) $userId,
                        'source' => 'payment_simulation_controller',
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
                log_message('error', 'PayMongo cURL error: ' . $curlError);
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
                log_message('error', 'PayMongo HTTP stream error: ' . ($error['message'] ?? 'Unknown error'));
                return null;
            }

            if (isset($http_response_header[0]) && preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches)) {
                $httpCode = (int) ($matches[1] ?? 0);
            }
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            log_message('error', 'PayMongo API non-success status: ' . $httpCode . ' response: ' . $response);
            return null;
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded) || !isset($decoded['data']['id'])) {
            log_message('error', 'Invalid PayMongo API response shape: ' . $response);
            return null;
        }

        $checkoutUrl = $decoded['data']['attributes']['checkout_url'] ?? null;
        if (!$checkoutUrl) {
            log_message('error', 'PayMongo API response missing checkout_url: ' . $response);
            return null;
        }

        return [
            'id' => (string) $decoded['data']['id'],
            'checkout_url' => (string) $checkoutUrl,
        ];
    }

    private function createDigitalProductCheckoutSessionViaCurl(int $userId, string $reference, array $lineItems): ?array
    {
        $secretKey = trim((string) (env('PAYMONGO_SECRET_KEY') ?? env('paymongo.secretKey') ?? ''));
        if ($secretKey === '') {
            log_message('warning', 'PAYMONGO_SECRET_KEY not set. Using local digital product authorize fallback.');
            return null;
        }

        $payload = [
            'data' => [
                'attributes' => [
                    'line_items' => $lineItems,
                    'payment_method_types' => self::PAYMENT_METHOD_TYPES,
                    'success_url' => base_url('cart/payment-success'),
                    'cancel_url'  => base_url('cart/payment-cancel'),
                    'description' => 'Digital product checkout session',
                    'metadata' => [
                        'user_id' => (string) $userId,
                        'reference' => $reference,
                        'source' => 'payment_simulation_digital_product',
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
                log_message('error', 'Digital product PayMongo cURL error: ' . $curlError);
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
                log_message('error', 'Digital product PayMongo HTTP stream error: ' . ($error['message'] ?? 'Unknown error'));
                return null;
            }

            if (isset($http_response_header[0]) && preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches)) {
                $httpCode = (int) ($matches[1] ?? 0);
            }
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            log_message('error', 'Digital product PayMongo API non-success status: ' . $httpCode . ' response: ' . $response);
            return null;
        }

        $decoded = json_decode((string) $response, true);
        if (!is_array($decoded) || !isset($decoded['data']['id'])) {
            log_message('error', 'Invalid digital product PayMongo response shape: ' . $response);
            return null;
        }

        $checkoutUrl = $decoded['data']['attributes']['checkout_url'] ?? null;
        if (!$checkoutUrl) {
            log_message('error', 'Digital product PayMongo response missing checkout_url: ' . $response);
            return null;
        }

        return [
            'id' => (string) $decoded['data']['id'],
            'checkout_url' => (string) $checkoutUrl,
        ];
    }

    public function authorizeTestPayment()
    {
        $simulation = session()->get('seller_subscription_payment_simulation');

        if (!$simulation) {
            return redirect()->to('subscription')->with('error', 'No simulated checkout session found. Please subscribe again.');
        }

        return view('payment_simulation_authorize', [
            'amount' => '99.00',
            'currency' => 'PHP',
            'cancelUrl' => self::SELLER_SUBSCRIPTION_CANCEL_URL,
            'checkoutReference' => $simulation['paymongo_checkout_reference'] ?? self::SELLER_SUBSCRIPTION_CHECKOUT_URL,
        ]);
    }

    public function sellerSubscriptionStatus()
    {
        $simulation = session()->get('seller_subscription_payment_simulation');

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'status' => $simulation['status'] ?? 'none',
                'checkout_session_id' => $simulation['checkout_session_id'] ?? null,
                'checkout_url' => $simulation['checkout_url'] ?? null,
                'cancel_url' => $simulation['cancel_url'] ?? null,
            ],
        ]);
    }

    public function authorizeDigitalProductTestPayment()
    {
        $simulation = session()->get('digital_product_payment_simulation');

        if (!$simulation) {
            return redirect()->to('cart')->with('error', 'No simulated digital product checkout session found. Please try again.');
        }

        return view('payment_digital_product_authorize', [
            'reference' => (string) ($simulation['reference'] ?? ''),
            'amount' => number_format((float) ($simulation['total_amount'] ?? 0), 2),
            'currency' => (string) ($simulation['currency'] ?? 'PHP'),
            'cancelUrl' => base_url('cart'),
            'checkoutReference' => (string) ($simulation['paymongo_checkout_reference'] ?? self::DIGITAL_PRODUCT_CHECKOUT_REFERENCE_URL),
        ]);
    }

    public function digitalProductStatus()
    {
        $simulation = session()->get('digital_product_payment_simulation');

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'status' => $simulation['status'] ?? 'none',
                'reference' => $simulation['reference'] ?? null,
                'checkout_session_id' => $simulation['checkout_session_id'] ?? null,
                'checkout_url' => $simulation['checkout_url'] ?? null,
                'amount' => $simulation['total_amount'] ?? null,
                'currency' => $simulation['currency'] ?? null,
                'source' => $simulation['source'] ?? null,
            ],
        ]);
    }

    public function markDigitalProductPaid()
    {
        $simulation = session()->get('digital_product_payment_simulation');

        if (!$simulation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No simulated digital product checkout session found.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(404);
        }

        if (($simulation['status'] ?? '') !== 'paid') {
            $simulation['status'] = 'paid';
            $simulation['paid_at'] = date('Y-m-d H:i:s');
            session()->set('digital_product_payment_simulation', $simulation);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Simulated digital product payment marked as paid.',
            'redirect_url' => base_url('cart/payment-success'),
            'data' => $simulation,
            'csrfHash' => csrf_hash(),
        ]);
    }

    public function digitalProductSuccess()
    {
        $simulation = session()->get('digital_product_payment_simulation');

        if (!$simulation) {
            return redirect()->to('cart')->with('error', 'No digital product checkout session found. Please try again.');
        }

        if (($simulation['status'] ?? '') !== 'paid') {
            $simulation['status'] = 'paid';
            $simulation['paid_at'] = date('Y-m-d H:i:s');
            session()->set('digital_product_payment_simulation', $simulation);
        }

        return redirect()->to('cart/payment-success');
    }

    public function markSellerSubscriptionPaid()
    {
        $simulation = session()->get('seller_subscription_payment_simulation');

        if (!$simulation) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No simulated checkout session found.',
            ])->setStatusCode(404);
        }

        $simulation = $this->finalizeSimulatedSellerSubscriptionPayment($simulation);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Simulated seller subscription payment marked as paid.',
            'redirect_url' => base_url('subscription'),
            'data' => $simulation,
        ]);
    }

    public function sellerSubscriptionSuccess()
    {
        $simulation = session()->get('seller_subscription_payment_simulation');

        if (!$simulation) {
            return redirect()->to('subscription')->with('error', 'No checkout session found. Please subscribe again.');
        }

        $this->finalizeSimulatedSellerSubscriptionPayment($simulation);

        return redirect()->to('subscription')->with('success', 'Membership payment simulated via PayMongo checkout session. Your seller membership is now active.');
    }

    private function finalizeSimulatedSellerSubscriptionPayment(array $simulation): array
    {
        if (($simulation['status'] ?? '') !== 'paid') {
            $simulation['status'] = 'paid';
            $simulation['paid_at'] = date('Y-m-d H:i:s');
            session()->set('seller_subscription_payment_simulation', $simulation);
        }

        $userId = (int) ($simulation['user_id'] ?? 0);
        if ($userId <= 0) {
            return $simulation;
        }

        $authModel = new \App\Models\AuthModel();
        $authModel->activateSellerMembership($userId);

        $updatedUser = $authModel->getUserById($userId);
        $sessionEndDate = $updatedUser['subscription_end_date'] ?? date('Y-m-d', strtotime('+1 month'));

        session()->set([
            'role' => 'seller',
            'account_type' => 'seller',
            'subscription_status' => 'active',
            'membership_label' => 'Active',
            'subscription_end_date' => $sessionEndDate,
            'can_access_seller_dashboard' => true,
        ]);

                if (($simulation['receipt_email_sent'] ?? false) !== true) {
                        $this->sendSellerMembershipReceiptEmail($updatedUser ?: [], $simulation, $sessionEndDate);
                        $simulation['receipt_email_sent'] = true;
                        session()->set('seller_subscription_payment_simulation', $simulation);
                }

        return $simulation;
    }

        private function sendSellerMembershipReceiptEmail(array $user, array $simulation, string $membershipEndDate): void
        {
                try {
                        $emailTo = trim((string) ($user['email'] ?? ''));
                        if ($emailTo === '') {
                                return;
                        }

                        $fullName = esc(trim((string) ($user['full_name'] ?? 'Seller')));
                        $receiptNo = 'BMS-' . strtoupper(substr(sha1((string) (($simulation['checkout_session_id'] ?? '') . $emailTo)), 0, 10));
                        $paidAt = esc((string) ($simulation['paid_at'] ?? date('Y-m-d H:i:s')));
                        $amount = number_format((float) ($simulation['amount'] ?? 99), 2);
                        $currency = esc((string) ($simulation['currency'] ?? 'PHP'));
                        $source = esc((string) ($simulation['source'] ?? 'paymongo_checkout_session'));
                        $siteUrl = base_url();
                        $year = date('Y');

                        $email = \Config\Services::email();
                        $email->setFrom('bytemarket730@gmail.com', 'Byte Market');
                        $email->setTo($emailTo);
                        $email->setSubject('ByteMarket Seller Membership Receipt');
                        $email->setMessage(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Seller Membership Receipt</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:36px 0;">
        <tr><td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                <tr><td style="background:#1c2b3a;padding:28px 36px;text-align:center;"><span style="color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;">ByteMarket</span></td></tr>
                <tr><td style="height:4px;background:linear-gradient(90deg,#2f80d0,#22a43a);"></td></tr>
                <tr>
                    <td style="padding:34px 40px 24px;">
                        <p style="margin:0 0 6px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:#2f80d0;">Payment Receipt</p>
                        <h1 style="margin:0 0 14px;font-size:24px;line-height:1.25;color:#1c2b3a;">Seller Membership Activated</h1>
                        <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#444;">Hi {$fullName}, your membership payment was successful and your seller account is now active.</p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:8px;padding:18px 20px;margin-bottom:20px;">
                            <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Receipt No.</strong>{$receiptNo}</td></tr>
                            <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Plan</strong>Seller Membership (Monthly)</td></tr>
                            <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Amount Paid</strong>{$currency} {$amount}</td></tr>
                            <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Gateway Source</strong>{$source}</td></tr>
                            <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Paid At</strong>{$paidAt}</td></tr>
                            <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Valid Until</strong>{$membershipEndDate}</td></tr>
                        </table>
                        <div style="text-align:center;"><a href="{$siteUrl}dashboard" style="display:inline-block;background:#2f80d0;color:#fff;text-decoration:none;padding:13px 34px;border-radius:8px;font-size:14px;font-weight:600;letter-spacing:0.4px;">Open Seller Dashboard</a></div>
                    </td>
                </tr>
                <tr><td style="background:#1c2b3a;padding:22px 36px;text-align:center;"><p style="margin:0;color:#8fa3b1;font-size:12px;">&copy; {$year} ByteMarket. All rights reserved.</p></td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
HTML);
                        $email->send(false);
                } catch (\Throwable $e) {
                        log_message('warning', 'Seller membership receipt email failed: ' . $e->getMessage());
                }
        }
}
