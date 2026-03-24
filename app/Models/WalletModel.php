<?php

namespace App\Models;

/**
 * WalletModel - Manages seller wallet and earnings
 * 
 * Handles:
 * - Track all earnings (sales, bonuses)
 * - Manage deductions (commissions, adjustments)
 * - Balance calculation
 * - Withdrawal requests
 * - Transaction history
 */
class WalletModel extends BaseModel
{
    protected $table = 'wallet_transactions';
    protected $primaryKey = 'wallet_transaction_id';

    protected $allowedFields = [
        'seller_id',
        'order_id',
        'transaction_type',
        'amount',
        'transaction_reference',
        'description',
        'status',
        'balance_before',
        'balance_after',
        'metadata',
        'created_at',
        'updated_at'
    ];

    /**
     * Add wallet transaction
     */
    public function addTransaction(
        int $sellerId,
        string $transactionType,
        float $amount,
        string $description,
        ?int $orderId = null,
        ?array $metadata = null
    ): ?int {
        try {
            $validTypes = ['sale', 'commission_deduction', 'withdrawal', 'refund', 'bonus', 'adjustment'];

            if (!in_array($transactionType, $validTypes)) {
                throw new \Exception('Invalid transaction type');
            }

            $balanceBefore = $this->getCurrentBalance($sellerId);
            $balanceAfter = $balanceBefore + $amount;

            $data = [
                'seller_id' => $sellerId,
                'order_id' => $orderId,
                'transaction_type' => $transactionType,
                'amount' => round($amount, 2),
                'transaction_reference' => $this->generateReference('WAL'),
                'description' => $description,
                'status' => 'completed',
                'balance_before' => round($balanceBefore, 2),
                'balance_after' => round($balanceAfter, 2),
                'metadata' => $metadata ? json_encode($metadata) : null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->insert($data);

            // Audit log
            $this->auditLog(
                $sellerId,
                'wallet_transaction',
                'wallet',
                $this->getInsertID(),
                "{$transactionType}: {$description}",
                ['balance' => $balanceBefore],
                ['balance' => $balanceAfter]
            );

            return $this->getInsertID();

        } catch (\Exception $e) {
            log_message('error', 'Add wallet transaction error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get current wallet balance
     */
    public function getCurrentBalance(int $sellerId): float
    {
        try {
            $result = $this->selectSum('amount', 'total')
                ->where('seller_id', $sellerId)
                ->where('status', 'completed')
                ->get()
                ->getRow();

            return (float)($result->total ?? 0);

        } catch (\Exception $e) {
            log_message('error', 'Get balance error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get wallet transaction history
     */
    public function getTransactionHistory(int $sellerId, int $limit = 10, int $offset = 0): array
    {
        try {
            $query = $this->where('seller_id', $sellerId)
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
            log_message('error', 'Get transaction history error: ' . $e->getMessage());
            return ['transactions' => [], 'total' => 0, 'limit' => $limit, 'offset' => $offset];
        }
    }

    /**
     * Request cashout/withdrawal
     */
    public function requestWithdrawal(
        int $sellerId,
        float $amount,
        int $paymentMethodId,
        ?string $notes = null
    ): ?int {
        try {
            $currentBalance = $this->getCurrentBalance($sellerId);

            if ($amount > $currentBalance) {
                throw new \Exception('Insufficient balance for withdrawal');
            }

            if ($amount <= 0) {
                throw new \Exception('Withdrawal amount must be greater than 0');
            }

            // Verify payment method belongs to seller
            $paymentMethodModel = new PaymentMethodModel();
            if (!$paymentMethodModel->isValidPaymentMethod($paymentMethodId, $sellerId)) {
                throw new \Exception('Invalid or unverified payment method');
            }

            // Create withdrawal transaction
            $data = [
                'seller_id' => $sellerId,
                'transaction_type' => 'withdrawal',
                'amount' => round(-$amount, 2), // Negative for deduction
                'transaction_reference' => $this->generateReference('WDR'),
                'description' => $notes ?? "Withdrawal request to payment method ID {$paymentMethodId}",
                'status' => 'pending',
                'balance_before' => round($currentBalance, 2),
                'balance_after' => round($currentBalance - $amount, 2),
                'metadata' => json_encode([
                    'payment_method_id' => $paymentMethodId,
                    'withdrawal_status' => 'pending'
                ]),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->insert($data);
            $withdrawalId = $this->getInsertID();

            // Notify seller
            $this->createNotification(
                $sellerId,
                'wallet_updated',
                'Withdrawal Request Submitted',
                'Your withdrawal request of ' . $this->formatCurrency($amount) . ' is pending',
                'fas fa-money-bill',
                '#249E2F'
            );

            // Audit log
            $this->auditLog(
                $sellerId,
                'withdrawal_request',
                'wallet',
                $withdrawalId,
                "Requested withdrawal of {$this->formatCurrency($amount)}",
                ['balance' => $currentBalance],
                ['balance' => $currentBalance - $amount]
            );

            return $withdrawalId;

        } catch (\Exception $e) {
            log_message('error', 'Request withdrawal error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process withdrawal (admin action)
     */
    public function processWithdrawal(int $withdrawalId, bool $approve = true, ?string $adminNotes = null): bool
    {
        try {
            $withdrawal = $this->find($withdrawalId);

            if (!$withdrawal || $withdrawal['transaction_type'] !== 'withdrawal') {
                return false;
            }

            if ($withdrawal['status'] !== 'pending') {
                return false; // Can only process pending withdrawals
            }

            $newStatus = $approve ? 'completed' : 'cancelled';
            $oldStatus = $withdrawal['status'];

            $updateData = [
                'status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // If cancelled, reverse the balance
            if (!$approve) {
                $reversalAmount = abs($withdrawal['amount']);
                $newBalance = $withdrawal['balance_before'] + $reversalAmount;
                $updateData['balance_after'] = $newBalance;
            }

            $this->update($withdrawalId, $updateData);

            // Notify seller
            if ($approve) {
                $this->createNotification(
                    $withdrawal['seller_id'],
                    'wallet_updated',
                    'Withdrawal Processed',
                    'Your withdrawal has been processed and sent to your payment method',
                    'fas fa-check-circle',
                    '#249E2F'
                );
            } else {
                $this->createNotification(
                    $withdrawal['seller_id'],
                    'wallet_updated',
                    'Withdrawal Cancelled',
                    'Your withdrawal request was cancelled. Funds have been restored to your wallet.',
                    'fas fa-exclamation-circle',
                    '#F57C00'
                );
            }

            // Audit log
            $this->auditLog(
                0, // Admin action
                'withdrawal_processed',
                'wallet',
                $withdrawalId,
                "Withdrawal {$newStatus}. " . ($adminNotes ?? 'No notes'),
                ['status' => $oldStatus],
                ['status' => $newStatus],
                $approve ? 'success' : 'warning'
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Process withdrawal error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get wallet summary
     */
    public function getWalletSummary(int $sellerId): array
    {
        try {
            $currentBalance = $this->getCurrentBalance($sellerId);

            // Get monthly earnings
            $monthStart = date('Y-m-01');
            $monthEnd = date('Y-m-t');

            $monthlyResult = $this->selectSum('amount', 'total')
                ->where('seller_id', $sellerId)
                ->where('status', 'completed')
                ->where('DATE(created_at) >=', $monthStart)
                ->where('DATE(created_at) <=', $monthEnd)
                ->get()
                ->getRow();

            $monthlyEarnings = (float)($monthlyResult->total ?? 0);

            // Get pending withdrawals
            $pendingWithdrawals = $this->where('seller_id', $sellerId)
                ->where('transaction_type', 'withdrawal')
                ->where('status', 'pending')
                ->selectSum('amount', 'total')
                ->get()
                ->getRow();

            $pendingAmount = abs((float)($pendingWithdrawals->total ?? 0));

            return [
                'current_balance' => round($currentBalance, 2),
                'monthly_earnings' => round($monthlyEarnings, 2),
                'pending_withdrawal' => round($pendingAmount, 2),
                'available_balance' => round($currentBalance - $pendingAmount, 2)
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get wallet summary error: ' . $e->getMessage());
            return [
                'current_balance' => 0,
                'monthly_earnings' => 0,
                'pending_withdrawal' => 0,
                'available_balance' => 0
            ];
        }
    }

    /**
     * Get wallet statistics for date range
     */
    public function getWalletStats(int $sellerId, string $startDate, string $endDate): array
    {
        try {
            $query = $this->where('seller_id', $sellerId)
                ->where('status', 'completed')
                ->where('DATE(created_at) >=', $startDate)
                ->where('DATE(created_at) <=', $endDate);

            $totalResult = $query->selectSum('amount', 'total')->get()->getRow();
            $total = (float)($totalResult->total ?? 0);

            $salesResult = $query->where('transaction_type', 'sale')
                ->selectSum('amount', 'total')->get()->getRow();
            $sales = (float)($salesResult->total ?? 0);

            $commissionsResult = $query->where('transaction_type', 'commission_deduction')
                ->selectSum('amount', 'total')->get()->getRow();
            $commissions = abs((float)($commissionsResult->total ?? 0));

            return [
                'date_range' => "{$startDate} to {$endDate}",
                'total_earnings' => round($total, 2),
                'gross_sales' => round($sales, 2),
                'commissions_paid' => round($commissions, 2)
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get wallet stats error: ' . $e->getMessage());
            return [
                'date_range' => "",
                'total_earnings' => 0,
                'gross_sales' => 0,
                'commissions_paid' => 0
            ];
        }
    }
}
