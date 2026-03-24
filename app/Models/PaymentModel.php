<?php

namespace App\Models;

/**
 * PaymentModel
 * 
 * Handles payment transaction database operations
 */
class PaymentModel extends BaseModel
{
    protected $table = 'payment_transactions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'paymongo_source_id',
        'paymongo_payment_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'redirect_url',
        'webhook_data',
        'metadata',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Cast JSON fields to appropriate types
     * @var array<string, string>
     */
    protected array $casts = [
        'webhook_data' => 'json',
        'metadata' => 'json',
    ];

    /**
     * Create a new payment transaction
     * 
     * @param int $userId
     * @param float $amount
     * @param string $paymentMethod
     * @param string $sourceId
     * @param string $redirectUrl
     * @param array $metadata
     * @return int|null Transaction ID
     */
    public function createTransaction(
        int $userId,
        float $amount,
        string $paymentMethod,
        string $sourceId,
        string $redirectUrl,
        array $metadata = []
    ): ?int {
        try {
            $data = [
                'user_id' => $userId,
                'amount' => $amount,
                'currency' => 'PHP',
                'payment_method' => $paymentMethod,
                'paymongo_source_id' => $sourceId,
                'redirect_url' => $redirectUrl,
                'status' => 'pending',
                'webhook_data' => [],
                'metadata' => $metadata,
            ];

            $this->insert($data);
            $transactionId = $this->getInsertID();

            if ($transactionId) {
                log_message('info', "Payment transaction created: ID {$transactionId} for user {$userId}");
                return $transactionId;
            }

            return null;

        } catch (\Exception $e) {
            log_message('error', 'Create transaction error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update transaction status
     * 
     * @param string $sourceId PayMongo source ID
     * @param string $status New status
     * @param string|null $paymentId PayMongo payment ID
     * @param array|null $webhookData Webhook payload data
     * @return bool
     */
    public function updateTransactionStatus(
        string $sourceId,
        string $status,
        ?string $paymentId = null,
        ?array $webhookData = null
    ): bool {
        try {
            $transaction = $this->where('paymongo_source_id', $sourceId)->first();

            if (!$transaction) {
                log_message('error', "Transaction not found for source ID: {$sourceId}");
                return false;
            }

            $updateData = [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($paymentId) {
                $updateData['paymongo_payment_id'] = $paymentId;
            }

            if ($webhookData) {
                $updateData['webhook_data'] = $webhookData;
            }

            // Use query builder update to avoid unexpected model casting of nullable JSON fields
            $result = $this->db->table($this->table)
                ->where('id', $transaction['id'])
                ->update($updateData);

            if ($result) {
                log_message('info', "Transaction {$transaction['id']} updated to status: {$status}");
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Update transaction status error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get transaction by source ID
     * 
     * @param string $sourceId
     * @return array|null
     */
    public function getTransactionBySourceId(string $sourceId): ?array
    {
        try {
            return $this->where('paymongo_source_id', $sourceId)->first();
        } catch (\Exception $e) {
            log_message('error', 'Get transaction by source ID error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transaction by payment ID
     * 
     * @param string $paymentId
     * @return array|null
     */
    public function getTransactionByPaymentId(string $paymentId): ?array
    {
        try {
            return $this->where('paymongo_payment_id', $paymentId)->first();
        } catch (\Exception $e) {
            log_message('error', 'Get transaction by payment ID error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all transactions for a user
     * 
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserTransactions(int $userId, int $limit = 50): array
    {
        try {
            return $this->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Get user transactions error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get successful payments for a user
     * 
     * @param int $userId
     * @return array
     */
    public function getSuccessfulPayments(int $userId): array
    {
        try {
            return $this->where('user_id', $userId)
                ->where('status', 'paid')
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Get successful payments error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mark expired pending transactions
     * 
     * Transactions older than 1 hour and still pending are marked as expired
     * 
     * @return int Number of transactions marked as expired
     */
    public function markExpiredTransactions(): int
    {
        try {
            $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));

            $result = $this->where('status', 'pending')
                ->where('created_at <', $oneHourAgo)
                ->set(['status' => 'expired'])
                ->update();

            if ($result) {
                log_message('info', "Marked {$result} transactions as expired");
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Mark expired transactions error: ' . $e->getMessage());
            return 0;
        }
    }
}
