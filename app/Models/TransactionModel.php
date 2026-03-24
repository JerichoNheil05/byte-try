<?php

namespace App\Models;

/**
 * TransactionModel - Manages all payment transactions
 * 
 * Handles:
 * - Payment processing records
 * - Transaction status tracking
 * - Payment gateway integration
 * - Refund processing
 * - Chargeback handling
 */
class TransactionModel extends BaseModel
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';

    protected $allowedFields = [
        'transaction_reference',
        'order_id',
        'user_id',
        'payment_method_id',
        'amount',
        'currency',
        'transaction_type',
        'status',
        'gateway_reference',
        'gateway_response',
        'failure_reason',
        'processed_at',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at'
    ];

    /**
     * Create transaction record
     */
    public function createTransaction(
        int $orderId,
        int $userId,
        int $paymentMethodId,
        float $amount,
        string $transactionType = 'payment',
        string $currency = 'PHP'
    ): ?int {
        try {
            $transactionData = [
                'transaction_reference' => $this->generateReference('TXN'),
                'order_id' => $orderId,
                'user_id' => $userId,
                'payment_method_id' => $paymentMethodId,
                'amount' => round($amount, 2),
                'currency' => $currency,
                'transaction_type' => $transactionType,
                'status' => 'pending',
                'ip_address' => $this->getClientIp(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->insert($transactionData);
            
            // Audit log
            $this->auditLog(
                $userId,
                'transaction_created',
                'transaction',
                $this->getInsertID(),
                "Created {$transactionType} transaction for order ID {$orderId}",
                null,
                ['amount' => $amount, 'status' => 'pending']
            );

            return $this->getInsertID();

        } catch (\Exception $e) {
            log_message('error', 'Create transaction error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process payment transaction
     */
    public function processPayment(
        int $transactionId,
        int $userId,
        bool $success = true,
        ?string $gatewayReference = null,
        ?array $gatewayResponse = null,
        ?string $failureReason = null
    ): bool {
        try {
            $transaction = $this->find($transactionId);

            if (!$transaction || $transaction['user_id'] != $userId) {
                return false;
            }

            $newStatus = $success ? 'completed' : 'failed';

            $updateData = [
                'status' => $newStatus,
                'processed_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($gatewayReference) {
                $updateData['gateway_reference'] = $gatewayReference;
            }

            if ($gatewayResponse) {
                $updateData['gateway_response'] = json_encode($gatewayResponse);
            }

            if ($failureReason && !$success) {
                $updateData['failure_reason'] = $failureReason;
            }

            $this->update($transactionId, $updateData);

            // Update order payment status
            $orderModel = new OrderModel();
            $orderPaymentStatus = $success ? 'completed' : 'failed';
            $orderModel->updatePaymentStatus($transaction['order_id'], $orderPaymentStatus);

            if ($success) {
                // Create wallet entry for seller
                $this->createSellerWalletEntry($transaction['order_id'], $transaction['amount']);

                // Notify buyer
                $this->createNotification(
                    $userId,
                    'payment_received',
                    'Payment Confirmed',
                    "We've received your payment of " . $this->formatCurrency($transaction['amount']),
                    'fas fa-check-circle',
                    '#249E2F',
                    $transaction['order_id']
                );
            } else {
                // Notify buyer of failed payment
                $this->createNotification(
                    $userId,
                    'payment_failed',
                    'Payment Failed',
                    'Your payment could not be processed. Please try again.',
                    'fas fa-exclamation-circle',
                    '#E53935',
                    $transaction['order_id']
                );
            }

            // Audit log
            $this->auditLog(
                $userId,
                'payment_processed',
                'transaction',
                $transactionId,
                "Payment {$newStatus}: " . ($failureReason ?? 'Success'),
                ['status' => 'pending'],
                ['status' => $newStatus, 'gateway_ref' => $gatewayReference]
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Process payment error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create wallet entry for seller (distribute order amount to sellers)
     */
    private function createSellerWalletEntry(int $orderId, float $amount): void
    {
        try {
            $orderItemModel = new OrderItemModel();
            $items = $orderItemModel->where('order_id', $orderId)->findAll();
            
            $walletModel = new WalletModel();

            foreach ($items as $item) {
                // Calculate seller's share (amount - commission)
                $commissionRate = 5.00; // 5% platform commission
                $commission = $item['subtotal'] * ($commissionRate / 100);
                $sellerAmount = $item['subtotal'] - $commission;

                $walletModel->addTransaction(
                    $item['seller_id'],
                    'sale',
                    $sellerAmount,
                    "Order #{$orderId} - Product sale",
                    $orderId,
                    ['product_id' => $item['product_id'], 'quantity' => $item['quantity']]
                );
            }

        } catch (\Exception $e) {
            log_message('error', 'Create seller wallet entry error: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction details
     */
    public function getTransactionDetails(int $transactionId, int $userId): ?array
    {
        try {
            return $this->where('transaction_id', $transactionId)
                ->where('user_id', $userId)
                ->first();

        } catch (\Exception $e) {
            log_message('error', 'Get transaction details error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user's transactions with pagination
     */
    public function getUserTransactions(int $userId, int $limit = 10, int $offset = 0): array
    {
        try {
            $query = $this->where('user_id', $userId)
                ->orderBy('created_at', 'DESC');

            $total = $query->countAllResults(false);
            $transactions = $query->limit($limit)->offset($offset)->findAll();

            return [
                'transactions' => $transactions,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get user transactions error: ' . $e->getMessage());
            return ['transactions' => [], 'total' => 0, 'limit' => $limit, 'offset' => $offset];
        }
    }

    /**
     * Process refund
     */
    public function processRefund(int $transactionId, int $userId, ?string $reason = null): bool
    {
        try {
            $transaction = $this->find($transactionId);

            if (!$transaction || $transaction['user_id'] != $userId) {
                return false;
            }

            if ($transaction['status'] !== 'completed') {
                return false; // Can only refund completed transactions
            }

            // Create refund transaction
            $this->insert([
                'transaction_reference' => $this->generateReference('RFD'),
                'order_id' => $transaction['order_id'],
                'user_id' => $userId,
                'payment_method_id' => $transaction['payment_method_id'],
                'amount' => $transaction['amount'],
                'currency' => $transaction['currency'],
                'transaction_type' => 'refund',
                'status' => 'completed',
                'failure_reason' => $reason,
                'processed_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Update original transaction
            $this->update($transactionId, [
                'status' => 'refunded',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Update order status
            $orderModel = new OrderModel();
            $orderModel->updatePaymentStatus($transaction['order_id'], 'refunded');

            // Notify buyer
            $this->createNotification(
                $userId,
                'refund_processed',
                'Refund Processed',
                'Your refund of ' . $this->formatCurrency($transaction['amount']) . ' has been processed',
                'fas fa-undo',
                '#249E2F',
                $transaction['order_id']
            );

            // Audit log
            $this->auditLog(
                $userId,
                'refund_processed',
                'transaction',
                $transactionId,
                "Processed refund for transaction. Reason: {$reason}"
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Process refund error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get transaction statistics by status
     */
    public function getTransactionStats(int $userId, string $startDate = null, string $endDate = null): array
    {
        try {
            $query = $this->where('user_id', $userId);

            if ($startDate) {
                $query->where('DATE(created_at) >=', $startDate);
            }

            if ($endDate) {
                $query->where('DATE(created_at) <=', $endDate);
            }

            $completed = $query->where('status', 'completed')->countAllResults(false);
            $pending = $query->where('status', 'pending')->countAllResults(false);
            $failed = $query->where('status', 'failed')->countAllResults(false);

            $totalAmount = $this->db->table('transactions')
                ->selectSum('amount', 'total')
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->get()
                ->getRow();

            return [
                'completed' => $completed,
                'pending' => $pending,
                'failed' => $failed,
                'total_amount' => (float)($totalAmount->total ?? 0)
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get transaction stats error: ' . $e->getMessage());
            return ['completed' => 0, 'pending' => 0, 'failed' => 0, 'total_amount' => 0];
        }
    }
}
