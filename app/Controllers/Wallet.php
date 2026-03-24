<?php

namespace App\Controllers;

class Wallet extends BaseController
{
    private function currentSellerId(): int
    {
        return (int) (session()->get('userId') ?? session()->get('user_id') ?? 0);
    }

    private function isRefundLikeStatus(string $status): bool
    {
        return in_array($status, ['refunded', 'returned', 'cancelled'], true);
    }

    private function isCompletedLikeStatus(string $status): bool
    {
        return in_array($status, ['completed', 'delivered', 'paid', 'success', 'succeeded'], true);
    }

    private function resolvePreviewThumbnail(string $previewPath): string
    {
        $raw = trim($previewPath);
        if ($raw === '') {
            return '';
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded) && !empty($decoded)) {
            $last = end($decoded);
            $raw = is_string($last) ? $last : '';
        }

        $raw = str_replace('\\/', '/', trim($raw));
        if ($raw === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $raw)) {
            return $raw;
        }

        return base_url(ltrim($raw, '/'));
    }

    private function getSellerSalesData(int $sellerId): array
    {
        if ($sellerId <= 0) {
            return ['balance' => 0.0, 'transactions' => []];
        }

        $db = \Config\Database::connect();

        // --- Sales (money received) ---
        $rows = $db->table('order_items oi')
            ->select('oi.order_item_id, oi.product_title, oi.subtotal, oi.item_status, oi.created_at as item_created_at, o.order_id, o.order_number, o.status as order_status, o.payment_status, o.created_at as order_created_at, o.user_id as buyer_id, buyer.full_name as buyer_name, p.preview_path')
            ->join('orders o', 'o.order_id = oi.order_id', 'inner')
            ->join('users buyer', 'buyer.id = o.user_id', 'left')
            ->join('products p', 'p.id = oi.product_id', 'left')
            ->where('oi.seller_id', $sellerId)
            ->orderBy('o.created_at', 'ASC')
            ->orderBy('oi.order_item_id', 'ASC')
            ->get()
            ->getResultArray();

        $entries = [];

        foreach ($rows as $row) {
            $itemStatus    = strtolower(trim((string) ($row['item_status']    ?? '')));
            $orderStatus   = strtolower(trim((string) ($row['order_status']   ?? '')));
            $paymentStatus = strtolower(trim((string) ($row['payment_status'] ?? '')));

            if (
                $this->isRefundLikeStatus($itemStatus)
                || $this->isRefundLikeStatus($orderStatus)
                || $this->isRefundLikeStatus($paymentStatus)
            ) {
                continue;
            }

            $isCompleted =
                $this->isCompletedLikeStatus($itemStatus)
                || $this->isCompletedLikeStatus($orderStatus)
                || $this->isCompletedLikeStatus($paymentStatus);

            if (!$isCompleted) {
                continue;
            }

            $amount  = (float) ($row['subtotal'] ?? 0);
            $dateRaw = (string) ($row['order_created_at'] ?? $row['item_created_at'] ?? '');
            $orderId = (int)   ($row['order_id'] ?? 0);

            $entries[] = [
                'type'              => 'money_received',
                'sort_date'         => $dateRaw,
                'id'                => (int) ($row['order_item_id'] ?? 0),
                'buyer_name'        => trim((string) ($row['buyer_name']    ?? 'Buyer'))   ?: 'Buyer',
                'product_name'      => trim((string) ($row['product_title'] ?? 'Product')) ?: 'Product',
                'transaction_date'  => $dateRaw,
                'amount'            => round($amount, 2),
                'reference'         => trim((string) ($row['order_number'] ?? '')) ?: ('ORD-' . $orderId),
                'payment_method'    => 'Digital Payment',
                'product_thumbnail' => $this->resolvePreviewThumbnail((string) ($row['preview_path'] ?? '')),
                'description'       => '',
                'status'            => 'completed',
            ];
        }

        // --- Withdrawals ---
        try {
            if ($db->tableExists('wallet_transactions')) {
                $wRows = $db->table('wallet_transactions')
                    ->select('wallet_transaction_id, amount, transaction_reference, description, status, created_at')
                    ->where('seller_id', $sellerId)
                    ->where('transaction_type', 'withdrawal')
                    ->whereIn('status', ['pending', 'completed'])
                    ->orderBy('created_at', 'ASC')
                    ->get()
                    ->getResultArray();

                foreach ($wRows as $w) {
                    $entries[] = [
                        'type'              => 'withdrawal',
                        'sort_date'         => (string) ($w['created_at'] ?? ''),
                        'id'                => (int)    ($w['wallet_transaction_id'] ?? 0),
                        'buyer_name'        => '',
                        'product_name'      => '',
                        'transaction_date'  => (string) ($w['created_at'] ?? ''),
                        'amount'            => round((float) ($w['amount'] ?? 0), 2),
                        'reference'         => (string) ($w['transaction_reference'] ?? ''),
                        'payment_method'    => '',
                        'product_thumbnail' => '',
                        'description'       => trim((string) ($w['description'] ?? '')) ?: 'Cash Out',
                        'status'            => (string) ($w['status'] ?? 'pending'),
                    ];
                }
            }
        } catch (\Throwable $e) {
            log_message('warning', 'Wallet withdrawal fetch skipped: ' . $e->getMessage());
        }

        // Sort oldest→newest for correct running balance accumulation.
        usort($entries, function (array $a, array $b): int {
            return strcmp($a['sort_date'], $b['sort_date']);
        });

        // Compute running balance for each row.
        $balance = 0.0;
        foreach ($entries as &$entry) {
            if ($entry['type'] === 'money_received') {
                $balance += $entry['amount'];
            } else {
                $balance -= $entry['amount'];
            }
            $entry['running_balance'] = round(max(0.0, $balance), 2);
        }
        unset($entry);

        // Newest-first for display.
        $transactions = array_reverse($entries);

        return [
            'balance'      => round(max(0.0, $balance), 2),
            'transactions' => $transactions,
        ];
    }

    /**
     * Display the wallet dashboard with balance and transaction history.
     *
     * @return string
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $sellerId = $this->currentSellerId();
        if ($sellerId <= 0) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $salesData = $this->getSellerSalesData($sellerId);
        $wallet = ['balance' => (float) ($salesData['balance'] ?? 0)];
        $transactions = $salesData['transactions'] ?? [];

        return view('wallet', [
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Handle cash out request from seller.
     */
    public function cashout(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request.',
            ])->setStatusCode(400);
        }

        $sellerId = $this->currentSellerId();
        if ($sellerId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please log in first.',
            ])->setStatusCode(401);
        }

        // Ensure the logged-in user is actually a seller.
        if (strtolower((string) (session()->get('role') ?? '')) !== 'seller') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.',
            ])->setStatusCode(403);
        }

        $amount        = (float) $this->request->getPost('amount');
        $paymentMethod = trim((string) ($this->request->getPost('payment_method') ?? ''));
        $walletNumber  = trim((string) ($this->request->getPost('wallet_number') ?? ''));
        $bankName      = trim((string) ($this->request->getPost('bank_name') ?? ''));
        $accountNumber = trim((string) ($this->request->getPost('account_number') ?? ''));
        $accountHolder = trim((string) ($this->request->getPost('account_holder') ?? ''));

        $allowed = ['gcash', 'paymaya', 'paypal', 'bank_transfer'];
        if ($amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Amount must be greater than zero.', 'csrfHash' => csrf_hash()])->setStatusCode(422);
        }
        if (!in_array($paymentMethod, $allowed, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid payment method.', 'csrfHash' => csrf_hash()])->setStatusCode(422);
        }
        if (in_array($paymentMethod, ['gcash', 'paymaya', 'paypal'], true) && $walletNumber === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Phone / account number is required.', 'csrfHash' => csrf_hash()])->setStatusCode(422);
        }
        if ($paymentMethod === 'bank_transfer' && ($bankName === '' || $accountNumber === '' || $accountHolder === '')) {
            return $this->response->setJSON(['success' => false, 'message' => 'All bank details are required for bank transfer.', 'csrfHash' => csrf_hash()])->setStatusCode(422);
        }

        try {
            $db = \Config\Database::connect();

            // Available balance = sales earnings minus prior withdrawals
            $availableBalance = $this->getSellerSalesData($sellerId)['balance'];

            if ($amount > $availableBalance) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Insufficient balance. Available: ₱' . number_format($availableBalance, 2),
                    'csrfHash' => csrf_hash(),
                ])->setStatusCode(422);
            }

            $reference    = 'WAL-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            $now          = date('Y-m-d H:i:s');
            $balanceBefore = $availableBalance;
            $balanceAfter  = round($availableBalance - $amount, 2);

            $metadata = ['payment_method' => $paymentMethod];
            if (in_array($paymentMethod, ['gcash', 'paymaya', 'paypal'], true)) {
                $metadata['wallet_number'] = $walletNumber;
            } else {
                $metadata['bank_name']      = $bankName;
                $metadata['account_number'] = $accountNumber;
                $metadata['account_holder'] = $accountHolder;
            }

            $db->table('wallet_transactions')->insert([
                'seller_id'             => $sellerId,
                'order_id'              => null,
                'transaction_type'      => 'withdrawal',
                'amount'                => round($amount, 2),
                'transaction_reference' => $reference,
                'description'           => 'Cash out via ' . ucfirst(str_replace('_', ' ', $paymentMethod)),
                'status'                => 'pending',
                'balance_before'        => round($balanceBefore, 2),
                'balance_after'         => $balanceAfter,
                'metadata'              => json_encode($metadata),
                'created_at'            => $now,
            ]);

            return $this->response->setJSON([
                'success'   => true,
                'message'   => 'Cash out request submitted! Reference: ' . $reference . '. It will be processed within 24 hours.',
                'reference' => $reference,
                'new_balance' => $balanceAfter,
                'csrfHash'  => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Wallet cashout error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to process cash out. Please try again.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Preview transaction details via AJAX or direct request
     * Backend-ready: Database integration can be added later
     *
     * @param int|string $transactionId
     * @return \CodeIgniter\HTTP\ResponseInterface|string
     */
    public function preview($transactionId = null)
    {
        if (!$transactionId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid transaction ID.',
                ])->setStatusCode(400);
            }
            return redirect()->back()->with('error', 'Invalid transaction ID.');
        }

        try {
            // TODO: Fetch transaction data from database using $transactionId
            // $userId = session()->get('user_id');
            // $transaction = $this->Wallet_model->getTransactionDetails($userId, $transactionId);

            // Sample transaction data for demonstration
            $transaction = [
                'id' => $transactionId,
                'buyer_name' => 'Roseanne Park',
                'transaction_date' => '2026-02-26',
                'reference' => 'P012026',
                'payment_method' => 'Paymaya',
                'product_name' => 'Habit Tracker Template',
                'amount' => 200.00,
                'product_thumbnail' => 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 320 200\'%3E%3Crect width=\'320\' height=\'200\' fill=\'%23f5f5f5\'/%3E%3Crect x=\'24\' y=\'24\' width=\'272\' height=\'152\' rx=\'10\' fill=\'%23ffffff\' stroke=\'%23e0e0e0\'/%3E%3Ctext x=\'160\' y=\'110\' font-family=\'Poppins\' font-size=\'14\' text-anchor=\'middle\' fill=\'%23999999\'%3EHabit Tracker Template%3C/text%3E%3C/svg%3E',
            ];

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $transaction,
                ]);
            }

            // For non-AJAX requests, return formatted data
            return $this->response->setJSON($transaction);
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error fetching transaction details.',
                ])->setStatusCode(500);
            }
            return redirect()->back()->with('error', 'Error fetching transaction details.');
        }
    }

    /**
     * Get transaction history (API endpoint).
     * TODO: Implement backend logic to fetch transaction data.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function transactions()
    {
        $sellerId = $this->currentSellerId();
        if ($sellerId <= 0) {
            return $this->response->setJSON([])->setStatusCode(401);
        }

        $transactions = $this->getSellerSalesData($sellerId)['transactions'] ?? [];

        return $this->response->setJSON($transactions);
    }

    /**
     * Get wallet balance (API endpoint).
     * TODO: Implement backend logic to fetch wallet balance.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function balance()
    {
        $sellerId = $this->currentSellerId();
        if ($sellerId <= 0) {
            return $this->response->setJSON(['balance' => 0])->setStatusCode(401);
        }

        $wallet = [
            'balance' => (float) ($this->getSellerSalesData($sellerId)['balance'] ?? 0),
        ];

        return $this->response->setJSON($wallet);
    }
}
