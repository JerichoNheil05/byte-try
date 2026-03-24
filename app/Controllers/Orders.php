<?php

namespace App\Controllers;

class Orders extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $role = strtolower(trim((string) (session()->get('role') ?? 'buyer')));
        if ($role !== 'seller') {
            return redirect()->to('/home')->with('error', 'Access denied. Seller account required.');
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $filter = strtolower(trim((string) ($this->request->getGet('status') ?? 'all')));
        $allowedFilters = ['all', 'pending', 'completed', 'refunded'];
        if (!in_array($filter, $allowedFilters, true)) {
            $filter = 'all';
        }

        $db = \Config\Database::connect();
        $rows = $db->table('order_items oi')
            ->select('oi.order_item_id, oi.product_title, oi.item_status, oi.seller_id, o.order_id, o.order_number, o.user_id as buyer_id, o.status as order_status, o.payment_status, o.created_at, buyer.full_name as buyer_name, seller.full_name as seller_name')
            ->join('orders o', 'o.order_id = oi.order_id', 'inner')
            ->join('users buyer', 'buyer.id = o.user_id', 'left')
            ->join('users seller', 'seller.id = oi.seller_id', 'left')
            ->where('oi.seller_id', $userId)
            ->orderBy('o.created_at', 'DESC')
            ->orderBy('oi.order_item_id', 'DESC')
            ->get()
            ->getResultArray();

        $orders = [];
        foreach ($rows as $row) {
            $itemStatus = strtolower(trim((string) ($row['item_status'] ?? '')));
            $orderStatus = strtolower(trim((string) ($row['order_status'] ?? '')));
            $paymentStatus = strtolower(trim((string) ($row['payment_status'] ?? '')));

            $completedStates = ['completed', 'paid', 'pail', 'success', 'succeeded'];
            $pendingStates = ['pending', 'unpaid', 'processing'];
            $refundedStates = ['refunded', 'refund'];

            $normalizedStates = [$itemStatus, $orderStatus, $paymentStatus];
            $rawStatus = 'pending';
            foreach ($normalizedStates as $state) {
                if (in_array($state, $refundedStates, true)) {
                    $rawStatus = 'refunded';
                    break;
                }

                if (in_array($state, $completedStates, true)) {
                    $rawStatus = 'completed';
                    break;
                }

                if (in_array($state, $pendingStates, true)) {
                    $rawStatus = 'pending';
                }
            }

            if ($filter !== 'all' && $filter !== $rawStatus) {
                continue;
            }

            $buyerName = trim((string) ($row['buyer_name'] ?? 'Buyer'));
            $sellerName = trim((string) ($row['seller_name'] ?? session()->get('fullName') ?? 'Seller'));
            $productTitle = trim((string) ($row['product_title'] ?? 'Product'));
            $createdAt = trim((string) ($row['created_at'] ?? ''));

            $orders[] = [
                'buyer' => $buyerName !== '' ? $buyerName : 'Buyer',
                'product' => $productTitle !== '' ? $productTitle : ('Order #' . (string) ($row['order_number'] ?? $row['order_id'] ?? '')),
                'seller' => $sellerName !== '' ? $sellerName : 'Seller',
                'order_date' => $createdAt !== '' ? date('M j, Y g:i a', strtotime($createdAt)) : '-',
                'status' => ucfirst($rawStatus),
            ];
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'orders' => $orders,
                    'currentFilter' => $filter,
                    'count' => count($orders),
                ],
            ]);
        }

        return view('orders', [
            'orders' => $orders,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * Show orders for the current buyer (purchased items)
     */
    public function myOrders(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        try {
            $authModel = new \App\Models\AuthModel();
            $orderModel = new \App\Models\OrderModel();
            $orderItemModel = new \App\Models\OrderItemModel();
            $productModel = new \App\Models\ProductModel();
            $db = \Config\Database::connect();

            // Verify DB connection
            if (!$db->connID) {
                $db->initialize();
            }

            // Get current buyer info
            $buyerUser = $authModel->getUserById($userId);
            $buyerFullName = trim((string) ($buyerUser['full_name'] ?? session()->get('fullName') ?? 'Buyer'));
            $buyerNameParts = preg_split('/\s+/', $buyerFullName);
            $buyerDisplayName = trim((string) ($buyerNameParts[0] ?? $buyerFullName ?: 'Buyer'));

            // Show successfully paid/completed orders for buyers.
            // Some flows may set payment_status first before status is synchronized.
            $orderResult = $orderModel
                ->where('user_id', $userId)
                ->groupStart()
                    ->where('status', 'completed')
                    ->orWhereIn('payment_status', ['completed', 'paid', 'success', 'succeeded'])
                ->groupEnd()
                ->orderBy('created_at', 'DESC')
                ->findAll();

            $sellerCache = [];
            $orders = [];

            foreach ($orderResult as $order) {
                $orderId = (int) ($order['order_id'] ?? 0);
                if ($orderId <= 0) {
                    continue;
                }

                $items = $orderItemModel->where('order_id', $orderId)
                    ->orderBy('order_item_id', 'ASC')
                    ->findAll();

                // Hide successfully refunded items from buyer order history cards.
                $items = array_values(array_filter($items, static function (array $item): bool {
                    $status = strtolower(trim((string) ($item['item_status'] ?? '')));
                    return $status !== 'refunded';
                }));

                $orderStatus = strtolower(trim((string) ($order['status'] ?? 'pending')));
                $orderStatusDisplay = ucfirst($orderStatus);
                $orderDateRaw = (string) ($order['created_at'] ?? '');
                $orderDate = $orderDateRaw !== '' ? date('M j, Y g:i a', strtotime($orderDateRaw)) : '-';
                $orderNumber = (string) ($order['order_number'] ?? 'ORD-' . $orderId);
                $totalAmount = (float) ($order['total_amount'] ?? 0);

                if (empty($items)) {
                    continue;
                }

                foreach ($items as $item) {
                    $sellerId  = (int) ($item['seller_id'] ?? 0);
                    $productId = (int) ($item['product_id'] ?? 0);

                    if ($sellerId > 0 && !array_key_exists($sellerId, $sellerCache)) {
                        $sellerUser = $authModel->getUserById($sellerId);
                        $sellerCache[$sellerId] = trim((string) ($sellerUser['full_name'] ?? 'Seller'));
                    }

                    // Fetch product image, file presence, and redirect URL from products
                    $productImage       = '';
                    $productRedirectUrl = '';
                    $productHasFile     = false;
                    if ($productId > 0) {
                        $product = $productModel->find($productId);
                        if ($product) {
                            // preview_path may be a JSON array of paths; use the last (newest) thumbnail
                            if (!empty($product['preview_path'])) {
                                $raw = (string) $product['preview_path'];
                                $decoded = json_decode($raw, true);
                                if (is_array($decoded) && !empty($decoded)) {
                                    // Use last element (newest thumbnail)
                                    $raw = (string) end($decoded);
                                }
                                // Strip JSON escape artifacts
                                $raw = str_replace('\\/', '/', $raw);
                                $productImage = base_url(ltrim($raw, '/'));
                            }

                            // redirect_url for link-type products
                            $productRedirectUrl = trim((string) ($product['redirect_url'] ?? ''));

                            // file_path may be a JSON array; use the last (newest) file
                            $rawFile    = (string) ($product['file_path'] ?? '');
                            $decodedFile = json_decode($rawFile, true);
                            $productHasFile = is_array($decodedFile)
                                ? !empty($decodedFile)
                                : ($rawFile !== '');
                        }
                    }

                    $orders[] = [
                        'order_id'      => $orderId,
                        'order_number'  => $orderNumber,
                        'product_name'  => (string) ($item['product_title'] ?? 'Product'),
                        'product_id'    => $productId,
                        'seller_name'   => $sellerId > 0 ? (string) ($sellerCache[$sellerId] ?? 'Seller') : 'Seller',
                        'seller_id'     => $sellerId,
                        'order_date'    => $orderDate,
                        'status'        => $orderStatusDisplay,
                        'amount'        => (float) ($item['unit_price'] ?? 0),
                        'quantity'      => (int) ($item['quantity'] ?? 1),
                        'product_image' => $productImage,
                        'redirect_url'  => $productRedirectUrl,
                        'has_file'      => $productHasFile,
                    ];
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'myOrders error: ' . $e->getMessage());
            $orders = [];
            $buyerDisplayName = session()->get('fullName') ?? 'Buyer';
        }

        return view('my_orders', [
            'orders'           => $orders,
            'buyerDisplayName' => $buyerDisplayName,
        ]);
    }

    /**
     * Securely serve a purchased product file for download.
     * Only buyers who have a completed order for the product may download it.
     */
    public function downloadProduct(int $productId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        // Verify the buyer has a completed order containing this product
        $db = \Config\Database::connect();
        $owned = $db->table('orders o')
            ->join('order_items oi', 'oi.order_id = o.order_id')
            ->where('o.user_id', $userId)
            ->where('oi.product_id', $productId)
            ->groupStart()
                ->where('o.status', 'completed')
                ->orWhereIn('o.payment_status', ['completed', 'paid', 'success', 'succeeded'])
            ->groupEnd()
            ->limit(1)
            ->get()
            ->getRowArray();

        if (!$owned) {
            return $this->response->setStatusCode(403)
                ->setBody('Access denied: you have not purchased this product.');
        }

        $productModel = new \App\Models\ProductModel();
        $product = $productModel->find($productId);

        if (!$product || empty($product['file_path'])) {
            return $this->response->setStatusCode(404)->setBody('File not found.');
        }

        // file_path may be a JSON array — use the last entry (newest file)
        $rawPath = (string) $product['file_path'];
        $decoded = json_decode($rawPath, true);
        if (is_array($decoded) && !empty($decoded)) {
            $rawPath = (string) end($decoded);
        }
        // Strip JSON escape artifacts
        $rawPath = str_replace('\\/', '/', $rawPath);

        $absPath = FCPATH . ltrim($rawPath, '/');
        if (!is_file($absPath)) {
            return $this->response->setStatusCode(404)->setBody('File not available on server.');
        }

        $ext          = strtolower(pathinfo($absPath, PATHINFO_EXTENSION));
        $safeTitle    = preg_replace('/[^a-zA-Z0-9_\-]/', '_', (string) ($product['title'] ?? 'product'));
        $downloadName = $safeTitle . '.' . $ext;

        // Determine the correct MIME type for the file extension
        $mimeType = 'application/octet-stream'; // fallback
        if ($ext !== '') {
            $mimes = \Config\Mimes::$mimes;
            if (isset($mimes[$ext])) {
                $mimeTypes = $mimes[$ext];
                // If it's an array, use the first (most common); otherwise use the string directly
                $mimeType = is_array($mimeTypes) ? $mimeTypes[0] : $mimeTypes;
            }
        }

        // Stream the file with proper headers
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Content-Length: ' . filesize($absPath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        readfile($absPath);
        exit;
    }

    /**
     * Submit a refund request for a purchased product item.
     */
    public function requestRefund(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $orderId = (int) ($this->request->getPost('order_id') ?? 0);
        $productId = (int) ($this->request->getPost('product_id') ?? 0);

        if ($orderId <= 0 || $productId <= 0) {
            return redirect()->to('/my-orders')->with('error', 'Invalid refund request.');
        }

        $db = \Config\Database::connect();

        $ownedItem = $db->table('orders o')
            ->select('o.order_id, o.user_id, o.status as order_status, o.created_at, oi.product_id, oi.order_item_id, oi.product_title, oi.seller_id, oi.subtotal')
            ->join('order_items oi', 'oi.order_id = o.order_id', 'inner')
            ->where('o.user_id', $userId)
            ->where('o.order_id', $orderId)
            ->where('oi.product_id', $productId)
            ->where('o.status', 'completed')
            ->limit(1)
            ->get()
            ->getRowArray();

        if (!$ownedItem) {
            return redirect()->to('/my-orders')->with('error', 'Refund denied: order item not found in your purchases.');
        }

        $orderCreatedAt = (string) ($ownedItem['created_at'] ?? '');
        $requestDays = 9999;
        if ($orderCreatedAt !== '') {
            try {
                $requestDays = (int) ((new \DateTime($orderCreatedAt))->diff(new \DateTime('now'))->days ?? 9999);
            } catch (\Throwable $e) {
                $requestDays = 9999;
            }
        }

        // Simple UI mode: one dropdown maps to full internal rule context.
        $refundCase = strtolower(trim((string) ($this->request->getPost('refund_case') ?? 'other')));

        $orderStatusClaim = 'delivered';
        $issueType = 'none';
        $productStatus = 'normal';
        $replacementStatus = 'not_requested';
        $productReceivedMismatch = false;
        $proofOfIssue = (int) ($this->request->getPost('proof_of_issue') ?? 0) === 1;
        $reason = 'other';
        $issueSource = 'unknown';

        switch ($refundCase) {
            case 'not_delivered_technical_error_verified':
                $orderStatusClaim = 'not_delivered';
                $issueType = 'technical_error_verified';
                $reason = 'technical_error_verified';
                break;

            case 'corrupted_or_inaccessible_replacement_unavailable':
                $productStatus = 'corrupted';
                $replacementStatus = 'unavailable';
                $reason = 'technical_error_verified';
                break;

            case 'product_mismatch_description':
                $productReceivedMismatch = true;
                $reason = 'other';
                break;

            case 'change_of_mind':
            case 'accidental_purchase':
            case 'lack_of_technical_knowledge':
            case 'did_not_read_description':
                $reason = $refundCase;
                break;

            case 'user_side_issue':
                $issueSource = 'user_device';
                $reason = 'other';
                break;

            default:
                $reason = 'other';
                break;
        }

        // Backward compatibility: if advanced fields are provided, let them override.
        $postedOrderStatus = strtolower(trim((string) ($this->request->getPost('order_status_claim') ?? '')));
        $postedIssueType = strtolower(trim((string) ($this->request->getPost('issue_type') ?? '')));
        $postedProductStatus = strtolower(trim((string) ($this->request->getPost('product_status') ?? '')));
        $postedReplacementStatus = strtolower(trim((string) ($this->request->getPost('replacement_status') ?? '')));
        $postedReason = strtolower(trim((string) ($this->request->getPost('reason') ?? '')));
        $postedIssueSource = strtolower(trim((string) ($this->request->getPost('issue_source') ?? '')));
        if ($postedOrderStatus !== '') {
            $orderStatusClaim = $postedOrderStatus;
        }
        if ($postedIssueType !== '') {
            $issueType = $postedIssueType;
        }
        if ($postedProductStatus !== '') {
            $productStatus = $postedProductStatus;
        }
        if ($postedReplacementStatus !== '') {
            $replacementStatus = $postedReplacementStatus;
        }
        if ((string) ($this->request->getPost('product_received_mismatch') ?? '') !== '') {
            $productReceivedMismatch = (int) ($this->request->getPost('product_received_mismatch') ?? 0) === 1;
        }
        if ($postedReason !== '') {
            $reason = $postedReason;
        }
        if ($postedIssueSource !== '') {
            $issueSource = $postedIssueSource;
        }
        $buyerNotes = trim((string) ($this->request->getPost('buyer_notes') ?? ''));

        $decision = $this->evaluateRefundDecision([
            'order_status' => $orderStatusClaim,
            'issue_type' => $issueType,
            'product_status' => $productStatus,
            'replacement_status' => $replacementStatus,
            'product_received_mismatch' => $productReceivedMismatch,
            'request_days' => $requestDays,
            'proof_of_issue' => $proofOfIssue,
            'reason' => $reason,
            'issue_source' => $issueSource,
        ]);

        $this->ensureRefundRequestsTable($db);

        $persistedStatus = $this->normalizeRefundStatusForTable($db, 'refund_requests', (string) ($decision['status'] ?? 'REJECTED'));

        $insertData = [
            'order_id' => $orderId,
            'order_item_id' => (int) ($ownedItem['order_item_id'] ?? 0),
            'product_id' => $productId,
            'buyer_id' => $userId,
            'reason' => $reason,
            'issue_type' => $issueType,
            'issue_source' => $issueSource,
            'product_status' => $productStatus,
            'replacement_status' => $replacementStatus,
            'product_received_mismatch' => $productReceivedMismatch ? 1 : 0,
            'proof_of_issue' => $proofOfIssue ? 1 : 0,
            'request_days' => $requestDays,
            'refund_decision' => $decision['refund_decision'],
            'status' => $persistedStatus,
            'admin_review_required' => $decision['admin_review_required'] ? 1 : 0,
            'decision_rule' => $decision['decision_rule'],
            'buyer_notes' => $buyerNotes,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $insertData = $this->filterInsertDataByTableColumns($db, 'refund_requests', $insertData);

        if (empty($insertData)) {
            return redirect()->to('/my-orders')->with('error', 'Refund request failed: refund table schema is invalid.');
        }

        try {
            $db->table('refund_requests')->insert($insertData);
        } catch (\Throwable $e) {
            log_message('error', 'Refund request insert failed: ' . $e->getMessage());
            return redirect()->to('/my-orders')->with('error', 'Refund request failed. Please try again.');
        }

        $productTitleForMessage = trim((string) ($ownedItem['product_title'] ?? 'Product'));
        $sellerIdForNotification = (int) ($ownedItem['seller_id'] ?? 0);
        $refundId = (int) $db->insertID();

        // Auto-deduct from seller wallet for automatically-approved refunds.
        if ($decision['refund_decision'] === 'APPROVED' && !$decision['admin_review_required'] && $sellerIdForNotification > 0 && $refundId > 0) {
            $itemAmount = (float) ($ownedItem['subtotal'] ?? 0);
            $this->processWalletRefundDeduction(
                $refundId,
                $sellerIdForNotification,
                $userId,
                $itemAmount,
                $orderId,
                $productTitleForMessage
            );
        }

        // Notification #1: request was submitted.
        $this->createNotificationSafe(
            $userId,
            'refund_request',
            'Your refund request for ' . $productTitleForMessage . ' has been submitted.',
            $orderId
        );

        // Result notification is intentionally delayed and published by poll endpoint after ~7 seconds.

        // Notify seller that buyer opened a refund request.
        if ($sellerIdForNotification > 0) {
            $this->createNotificationSafe(
                $sellerIdForNotification,
                'refund_request',
                'A buyer submitted a refund request for ' . $productTitleForMessage . ' (Order #' . $orderId . ').',
                $orderId
            );
        }

        return redirect()->to('/my-orders')->with('success', 'Refund request submitted. Result notification will appear shortly.');
    }

    /**
     * Poll delayed refund result notifications for buyer-side toast popups.
     */
    public function pollRefundResults(): 
        \CodeIgniter\HTTP\ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ])->setStatusCode(401);
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ])->setStatusCode(401);
        }

        $this->publishDueRefundResultNotifications($userId);

        $db = \Config\Database::connect();
        $rows = $db->table('notifications')
            ->select('id, message, created_at, related_id')
            ->where('user_id', $userId)
            ->where('type', 'refund_result')
            ->where('read', 0)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        $results = array_map(static function (array $row): array {
            return [
                'id' => (int) ($row['id'] ?? 0),
                'message' => (string) ($row['message'] ?? ''),
                'created_at' => (string) ($row['created_at'] ?? ''),
                'related_id' => (int) ($row['related_id'] ?? 0),
            ];
        }, $rows);

        return $this->response->setJSON([
            'success' => true,
            'results' => $results,
        ]);
    }

    /**
     * Evaluate refund and cancellation rules.
     */
    private function evaluateRefundDecision(array $context): array
    {
        $orderStatus = strtolower(trim((string) ($context['order_status'] ?? '')));
        $issueType = strtolower(trim((string) ($context['issue_type'] ?? '')));
        $productStatus = strtolower(trim((string) ($context['product_status'] ?? '')));
        $replacementStatus = strtolower(trim((string) ($context['replacement_status'] ?? '')));
        $productReceivedMismatch = (bool) ($context['product_received_mismatch'] ?? false);
        $requestDays = (int) ($context['request_days'] ?? 9999);
        $proofOfIssue = (bool) ($context['proof_of_issue'] ?? false);
        $reason = strtolower(trim((string) ($context['reason'] ?? '')));
        $issueSource = strtolower(trim((string) ($context['issue_source'] ?? '')));

        $refundDecision = 'DENIED';
        $decisionRule = 'NO_MATCH';
        $message = 'Refund request rejected: rule conditions were not met.';

        // Rule 4: hard denials first
        if ($requestDays > 7) {
            $refundDecision = 'DENIED';
            $decisionRule = 'RULE_4_REQUEST_DAYS_GT_7';
            $message = 'Refund denied: request is beyond 7 days from purchase.';
        } elseif (!$proofOfIssue) {
            $refundDecision = 'DENIED';
            $decisionRule = 'RULE_4_PROOF_REQUIRED';
            $message = 'Refund denied: proof of issue is required.';
        } elseif (in_array($reason, ['change_of_mind', 'accidental_purchase', 'lack_of_technical_knowledge', 'did_not_read_description'], true)) {
            // Rule 5: non-refundable reasons
            $refundDecision = 'DENIED';
            $decisionRule = 'RULE_5_NON_REFUNDABLE_REASON';
            $message = 'Refund denied: non-refundable reason.';
        } elseif (in_array($issueSource, ['user_device', 'internet_connection', 'user_software'], true)) {
            // Rule 5: issue source non-refundable
            $refundDecision = 'DENIED';
            $decisionRule = 'RULE_5_ISSUE_SOURCE_USER_SIDE';
            $message = 'Refund denied: issue source is user-side environment.';
        } elseif ($orderStatus === 'not_delivered' && $issueType === 'technical_error_verified') {
            // Rule 4: condition 1
            $refundDecision = 'APPROVED';
            $decisionRule = 'RULE_4_NOT_DELIVERED_TECHNICAL_ERROR_VERIFIED';
            $message = 'Refund approved under delivery/technical verification rule.';
        } elseif (in_array($productStatus, ['corrupted', 'inaccessible'], true) && $replacementStatus === 'unavailable') {
            // Rule 4: condition 2
            $refundDecision = 'APPROVED';
            $decisionRule = 'RULE_4_CORRUPTED_OR_INACCESSIBLE_REPLACEMENT_UNAVAILABLE';
            $message = 'Refund approved due to inaccessible/corrupted product with unavailable replacement.';
        } elseif ($productReceivedMismatch) {
            // Rule 4: condition 3
            $refundDecision = 'APPROVED';
            $decisionRule = 'RULE_4_PRODUCT_MISMATCH_DESCRIPTION';
            $message = 'Refund approved due to product mismatch against description.';
        }

        // Global override rule
        $adminReviewRequired = false;
        if ($refundDecision === 'APPROVED') {
            // Require manual review for subjective/content-based cases.
            $adminReviewRequired = in_array($decisionRule, [
                'RULE_4_CORRUPTED_OR_INACCESSIBLE_REPLACEMENT_UNAVAILABLE',
                'RULE_4_PRODUCT_MISMATCH_DESCRIPTION',
            ], true);

            $status = $adminReviewRequired ? 'PENDING_MANUAL_REVIEW' : 'AUTO_APPROVED';
        } else {
            $status = 'REJECTED';
        }

        return [
            'refund_decision' => $refundDecision,
            'status' => $status,
            'admin_review_required' => $adminReviewRequired,
            'decision_rule' => $decisionRule,
            'message' => $message,
        ];
    }

    /**
     * Creates refund_requests table if missing.
     */
    private function ensureRefundRequestsTable(\CodeIgniter\Database\BaseConnection $db): void
    {
        if (!$db->tableExists('refund_requests')) {
            $db->query(
                'CREATE TABLE IF NOT EXISTS refund_requests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                order_item_id INT NOT NULL,
                product_id INT NOT NULL,
                buyer_id INT NOT NULL,
                reason VARCHAR(100) NOT NULL,
                issue_type VARCHAR(100) NOT NULL,
                issue_source VARCHAR(100) NOT NULL,
                product_status VARCHAR(100) NOT NULL,
                replacement_status VARCHAR(100) NOT NULL,
                product_received_mismatch TINYINT(1) NOT NULL DEFAULT 0,
                proof_of_issue TINYINT(1) NOT NULL DEFAULT 0,
                request_days INT NOT NULL DEFAULT 0,
                refund_decision VARCHAR(20) NOT NULL,
                status VARCHAR(50) NOT NULL,
                admin_review_required TINYINT(1) NOT NULL DEFAULT 0,
                decision_rule VARCHAR(120) NOT NULL,
                buyer_notes TEXT NULL,
                result_notified_at DATETIME NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                INDEX idx_refund_buyer (buyer_id),
                INDEX idx_refund_order (order_id),
                INDEX idx_refund_product (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
            );
        }

        // Align existing tables created from older versions to avoid insert-time DB exceptions.
        $columnDefinitions = [
            'order_id' => 'INT NOT NULL DEFAULT 0',
            'order_item_id' => 'INT NOT NULL DEFAULT 0',
            'product_id' => 'INT NOT NULL DEFAULT 0',
            'buyer_id' => 'INT NOT NULL DEFAULT 0',
            'reason' => "VARCHAR(100) NOT NULL DEFAULT 'other'",
            'issue_type' => "VARCHAR(100) NOT NULL DEFAULT 'none'",
            'issue_source' => "VARCHAR(100) NOT NULL DEFAULT 'unknown'",
            'product_status' => "VARCHAR(100) NOT NULL DEFAULT 'normal'",
            'replacement_status' => "VARCHAR(100) NOT NULL DEFAULT 'not_requested'",
            'product_received_mismatch' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'proof_of_issue' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'request_days' => 'INT NOT NULL DEFAULT 0',
            'refund_decision' => "VARCHAR(20) NOT NULL DEFAULT 'DENIED'",
            'status' => "VARCHAR(50) NOT NULL DEFAULT 'REJECTED'",
            'admin_review_required' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'decision_rule' => "VARCHAR(120) NOT NULL DEFAULT 'NO_MATCH'",
            'buyer_notes' => 'TEXT NULL',
            'result_notified_at' => 'DATETIME NULL',
            'created_at' => 'DATETIME NULL',
            'updated_at' => 'DATETIME NULL',
        ];

        foreach ($columnDefinitions as $column => $definition) {
            if (!$db->fieldExists($column, 'refund_requests')) {
                $db->query('ALTER TABLE refund_requests ADD COLUMN ' . $column . ' ' . $definition);
            }
        }
    }

    /**
     * Keeps insert payload compatible with the actual database table schema.
     */
    private function filterInsertDataByTableColumns(\CodeIgniter\Database\BaseConnection $db, string $table, array $data): array
    {
        $filtered = [];
        foreach ($data as $column => $value) {
            if ($db->fieldExists($column, $table)) {
                $filtered[$column] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Maps modern refund statuses to legacy enum values if needed.
     */
    private function normalizeRefundStatusForTable(\CodeIgniter\Database\BaseConnection $db, string $table, string $status): string
    {
        $status = strtoupper(trim($status));

        try {
            $row = $db->query(
                'SELECT COLUMN_TYPE
                 FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = ?
                   AND COLUMN_NAME = ?
                 LIMIT 1',
                [$table, 'status']
            )->getRowArray();

            $columnType = strtolower((string) ($row['COLUMN_TYPE'] ?? ''));
            if (str_contains($columnType, "enum('pending','approved','denied')")) {
                return match ($status) {
                    'AUTO_APPROVED' => 'approved',
                    'PENDING_MANUAL_REVIEW' => 'pending',
                    default => 'denied',
                };
            }
        } catch (\Throwable $e) {
            // Fallback to provided status on metadata read issues.
        }

        return $status;
    }

    /**
     * Best-effort notification insert without breaking the main refund flow.
     */
    private function createNotificationSafe(int $userId, string $type, string $message, ?int $relatedId = null): void
    {
        if ($userId <= 0 || trim($message) === '') {
            return;
        }

        try {
            $db = \Config\Database::connect();

            if (!$db->tableExists('notifications')) {
                return;
            }

            $insert = [
                'user_id' => $userId,
                'message' => $message,
                'type' => trim($type) !== '' ? $type : 'general',
                'read' => 0,
                'related_id' => $relatedId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $insert = $this->filterInsertDataByTableColumns($db, 'notifications', $insert);
            if (!empty($insert)) {
                $db->table('notifications')->insert($insert);
            }
        } catch (\Throwable $e) {
            log_message('warning', 'Notification insert skipped: ' . $e->getMessage());
        }
    }

    /**
    * Publishes delayed refund result notifications after the 7-second hold period.
     */
    private function publishDueRefundResultNotifications(int $buyerId): void
    {
        if ($buyerId <= 0) {
            return;
        }

        try {
            $db = \Config\Database::connect();
            $this->ensureRefundRequestsTable($db);

            $rows = $db->table('refund_requests rr')
                ->select('rr.id, rr.order_id, rr.status, rr.refund_decision, rr.order_item_id, rr.product_id, oi.product_title')
                ->join('order_items oi', 'oi.order_item_id = rr.order_item_id', 'left')
                ->where('rr.buyer_id', $buyerId)
                ->where('rr.result_notified_at IS NULL', null, false)
                ->where('rr.created_at <= DATE_SUB(NOW(), INTERVAL 7 SECOND)', null, false)
                ->orderBy('rr.id', 'ASC')
                ->limit(50)
                ->get()
                ->getResultArray();

            if (empty($rows)) {
                return;
            }

            $now = date('Y-m-d H:i:s');
            foreach ($rows as $row) {
                $title = trim((string) ($row['product_title'] ?? 'Product'));
                $status = strtoupper(trim((string) ($row['status'] ?? 'REJECTED')));
                $decision = strtoupper(trim((string) ($row['refund_decision'] ?? 'DENIED')));
                $orderId = (int) ($row['order_id'] ?? 0);

                $message = 'Refund result for ' . ($title !== '' ? $title : 'Product') . ': ' . $status . ' (' . $decision . ').';

                $this->createNotificationSafe(
                    $buyerId,
                    'refund_result',
                    $message,
                    $orderId > 0 ? $orderId : null
                );

                $db->table('refund_requests')
                    ->where('id', (int) ($row['id'] ?? 0))
                    ->update([
                        'result_notified_at' => $now,
                        'updated_at' => $now,
                    ]);
            }
        } catch (\Throwable $e) {
            log_message('warning', 'Publish due refund result notifications skipped: ' . $e->getMessage());
        }
    }

    /**
     * Display the seller's refund process page for a specific refund.
     * GET orders/refund/process/{refundId}
     */
    public function showRefundProcess(int $refundId = 0): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $role = strtolower(trim((string) (session()->get('role') ?? '')));
        if ($role !== 'seller') {
            return redirect()->to('/home')->with('error', 'Access denied. Seller account required.');
        }

        $sellerId = (int) (session()->get('userId') ?? 0);
        if ($sellerId <= 0 || $refundId <= 0) {
            return redirect()->to('/orders')->with('error', 'Invalid refund request.');
        }

        try {
            $db = \Config\Database::connect();

            $row = $db->table('refund_requests rr')
                ->select('rr.id, rr.order_id, rr.buyer_id, rr.reason, rr.buyer_notes, rr.refund_decision, rr.status, rr.seller_refund_processed_at, rr.created_at, oi.product_title, oi.seller_id, o.order_number, o.total_amount, buyer.full_name as buyer_name, buyer.email as buyer_email, p.preview_path')
                ->join('order_items oi', 'oi.order_item_id = rr.order_item_id', 'left')
                ->join('orders o', 'o.order_id = rr.order_id', 'left')
                ->join('users buyer', 'buyer.id = rr.buyer_id', 'left')
                ->join('products p', 'p.id = rr.product_id', 'left')
                ->where('rr.id', $refundId)
                ->where('oi.seller_id', $sellerId)
                ->limit(1)
                ->get()
                ->getRowArray();

            if (empty($row)) {
                return redirect()->to('/orders')->with('error', 'Refund request not found or access denied.');
            }

            $decision = strtoupper(trim((string) ($row['refund_decision'] ?? '')));
            if ($decision !== 'APPROVED') {
                return redirect()->to('/orders')->with('error', 'This refund has not been approved.');
            }

            // Resolve thumbnail.
            $raw = trim((string) ($row['preview_path'] ?? ''));
            if ($raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded) && !empty($decoded)) {
                    $last = end($decoded);
                    $raw = is_string($last) ? $last : '';
                }
                $raw = str_replace('\\/', '/', $raw);
            }
            $thumbnailUrl = ($raw !== '' && !preg_match('/^https?:\/\//i', $raw))
                ? base_url(ltrim($raw, '/'))
                : $raw;

            return view('seller_refund_process', [
                'refund'       => $row,
                'refundId'     => $refundId,
                'thumbnailUrl' => $thumbnailUrl,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'showRefundProcess error: ' . $e->getMessage());
            return redirect()->to('/orders')->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Handle seller submission of the reverse payment confirmation.
     * POST orders/refund/process/{refundId}
     */
    public function submitRefundProcess(int $refundId = 0): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $role = strtolower(trim((string) (session()->get('role') ?? '')));
        if ($role !== 'seller') {
            return redirect()->to('/home')->with('error', 'Access denied. Seller account required.');
        }

        $sellerId = (int) (session()->get('userId') ?? 0);
        if ($sellerId <= 0 || $refundId <= 0) {
            return redirect()->to('/orders')->with('error', 'Invalid refund request.');
        }

        try {
            $db = \Config\Database::connect();

            $row = $db->table('refund_requests rr')
                ->select('rr.id, rr.order_id, rr.buyer_id, rr.refund_decision, rr.seller_refund_processed_at, oi.product_title, oi.seller_id, o.order_number')
                ->join('order_items oi', 'oi.order_item_id = rr.order_item_id', 'left')
                ->join('orders o', 'o.order_id = rr.order_id', 'left')
                ->where('rr.id', $refundId)
                ->where('oi.seller_id', $sellerId)
                ->limit(1)
                ->get()
                ->getRowArray();

            if (empty($row)) {
                return redirect()->to('/orders')->with('error', 'Refund request not found or access denied.');
            }

            $decision = strtoupper(trim((string) ($row['refund_decision'] ?? '')));
            if ($decision !== 'APPROVED') {
                return redirect()->to('/orders')->with('error', 'This refund has not been approved.');
            }

            if (!empty($row['seller_refund_processed_at'])) {
                return redirect()->to('/orders/refund/process/' . $refundId)->with('info', 'Refund has already been marked as processed.');
            }

            $now       = date('Y-m-d H:i:s');
            $title     = trim((string) ($row['product_title'] ?? 'Product'));
            $orderNum  = trim((string) ($row['order_number'] ?? (string) ($row['order_id'] ?? '')));
            $buyerId   = (int) ($row['buyer_id'] ?? 0);
            $sellerId  = (int) ($row['seller_id'] ?? 0);
            $orderId   = (int) ($row['order_id'] ?? 0);

            // Deduct from seller wallet (fetch item subtotal first).
            $itemSubtotal = 0.0;
            $itemRow = $db->table('refund_requests rr')
                ->select('oi.subtotal')
                ->join('order_items oi', 'oi.order_item_id = rr.order_item_id', 'left')
                ->where('rr.id', $refundId)
                ->limit(1)
                ->get()
                ->getRowArray();
            if (!empty($itemRow)) {
                $itemSubtotal = (float) ($itemRow['subtotal'] ?? 0);
            }

            $this->processWalletRefundDeduction($refundId, $sellerId, $buyerId, $itemSubtotal, $orderId, $title);

            return redirect()->to('/orders')->with('success', 'Refund processed. Wallet deduction applied and buyer notified.');
        } catch (\Throwable $e) {
            log_message('error', 'submitRefundProcess error: ' . $e->getMessage());
            return redirect()->to('/orders/refund/process/' . $refundId)->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Automatically deduct refund amount from seller wallet and stamp the refund as processed.
     * Called both on auto-approval and on manual seller confirmation.
     */
    private function processWalletRefundDeduction(
        int $refundId,
        int $sellerId,
        int $buyerId,
        float $amount,
        int $orderId,
        string $productTitle
    ): void {
        if ($sellerId <= 0 || $refundId <= 0) {
            return;
        }

        try {
            $db = \Config\Database::connect();
            $this->ensureWalletTransactionsTable($db);

            // Prevent double-processing (also fetch order_item_id for status updates).
            $existing = $db->table('refund_requests')
                ->select('seller_refund_processed_at, order_item_id')
                ->where('id', $refundId)
                ->limit(1)
                ->get()
                ->getRowArray();

            if (!empty($existing['seller_refund_processed_at'])) {
                return;
            }

            $now           = date('Y-m-d H:i:s');
            $deductAmount  = $amount > 0 ? -abs(round($amount, 2)) : 0.0;
            $refLabel      = 'WAL-REF-' . $refundId . '-' . time();
            $title         = $productTitle !== '' ? $productTitle : 'Product';

            // Calculate current seller balance.
            $balRow = $db->query(
                'SELECT COALESCE(SUM(amount), 0) AS total FROM wallet_transactions WHERE seller_id = ? AND status = \'completed\'',
                [$sellerId]
            )->getRowArray();
            $balanceBefore = (float) ($balRow['total'] ?? 0);
            $balanceAfter  = round($balanceBefore + $deductAmount, 2);

            // Insert refund deduction transaction.
            $walletInsert = [
                'seller_id'             => $sellerId,
                'order_id'              => $orderId > 0 ? $orderId : null,
                'transaction_type'      => 'refund',
                'amount'                => $deductAmount,
                'transaction_reference' => $refLabel,
                'description'           => 'Automatic refund deduction for "' . $title . '" (Refund #' . $refundId . ')',
                'status'                => 'completed',
                'balance_before'        => round($balanceBefore, 2),
                'balance_after'         => $balanceAfter,
                'metadata'              => json_encode(['refund_id' => $refundId, 'buyer_id' => $buyerId]),
                'created_at'            => $now,
                'updated_at'            => $now,
            ];
            $walletInsert = $this->filterInsertDataByTableColumns($db, 'wallet_transactions', $walletInsert);
            if (!empty($walletInsert)) {
                $db->table('wallet_transactions')->insert($walletInsert);
            }

            // Stamp the refund as processed and mark it approved.
            $db->table('refund_requests')
                ->where('id', $refundId)
                ->update(['seller_refund_processed_at' => $now, 'status' => 'approved', 'updated_at' => $now]);

            // Mark the specific order item as refunded.
            $orderItemId = (int) ($existing['order_item_id'] ?? 0);
            if ($orderItemId > 0) {
                $this->ensureOrderItemRefundedStatus($db);
                $db->table('order_items')
                    ->where('order_item_id', $orderItemId)
                    ->update(['item_status' => 'refunded', 'updated_at' => $now]);
            }

            // Mark the parent order as refunded only when no un-refunded items remain.
            if ($orderId > 0) {
                $pendingCount = $db->table('order_items')
                    ->where('order_id', $orderId)
                    ->whereNotIn('item_status', ['cancelled', 'returned', 'refunded'])
                    ->where($orderItemId > 0 ? 'order_item_id !=' : '1', $orderItemId > 0 ? $orderItemId : 1)
                    ->countAllResults();

                if ($pendingCount === 0) {
                    $db->table('orders')
                        ->where('order_id', $orderId)
                        ->update(['status' => 'refunded', 'payment_status' => 'refunded', 'updated_at' => $now]);
                }
            }

            // Notify seller about wallet deduction.
            $sellerMsg = '₱' . number_format(abs($deductAmount), 2) . ' was automatically deducted from your wallet for the approved refund on "' . $title . '".';
            $this->createNotificationSafe($sellerId, 'wallet_updated', $sellerMsg, $orderId > 0 ? $orderId : null);

            // Notify buyer that refund has been processed.
            if ($buyerId > 0) {
                $buyerMsg = 'Your refund of ₱' . number_format(abs($deductAmount), 2) . ' for "' . $title . '" has been automatically processed.';
                $this->createNotificationSafe($buyerId, 'refund_processed', $buyerMsg, $orderId > 0 ? $orderId : null);
            }
        } catch (\Throwable $e) {
            log_message('error', 'processWalletRefundDeduction error: ' . $e->getMessage());
        }
    }

    /**
     * Adds 'refunded' to order_items.item_status enum if not already present.
     */
    private function ensureOrderItemRefundedStatus(\CodeIgniter\Database\BaseConnection $db): void
    {
        $colRow = $db->query(
            "SELECT COLUMN_TYPE FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'order_items' AND COLUMN_NAME = 'item_status'"
        )->getRowArray();

        if (!empty($colRow['COLUMN_TYPE']) && str_contains($colRow['COLUMN_TYPE'], "'refunded'")) {
            return;
        }

        // Add 'refunded' to the enum.
        $db->query(
            "ALTER TABLE order_items MODIFY COLUMN item_status
             ENUM('pending','confirmed','shipped','delivered','cancelled','returned','refunded')
             NOT NULL DEFAULT 'pending'"
        );
    }

    /**
     * Creates wallet_transactions table if it does not already exist.
     */
    private function ensureWalletTransactionsTable(\CodeIgniter\Database\BaseConnection $db): void
    {
        if ($db->tableExists('wallet_transactions')) {
            return;
        }

        $db->query(
            'CREATE TABLE IF NOT EXISTS wallet_transactions (
                wallet_transaction_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                seller_id             INT UNSIGNED NOT NULL,
                order_id              INT UNSIGNED NULL,
                transaction_type      VARCHAR(50)  NOT NULL,
                amount                DECIMAL(12,2) NOT NULL,
                transaction_reference VARCHAR(100) NOT NULL,
                description           TEXT NULL,
                status                VARCHAR(30)  NOT NULL DEFAULT \'completed\',
                balance_before        DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                balance_after         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                metadata              TEXT NULL,
                created_at            DATETIME NULL,
                updated_at            DATETIME NULL,
                INDEX idx_wt_seller (seller_id),
                INDEX idx_wt_order  (order_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }
}
