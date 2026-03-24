<?php

namespace App\Models;

/**
 * PaymentMethodModel - Manages secure payment method storage
 * 
 * Features:
 * - AES-256 encryption for account numbers
 * - Account number masking for display
 * - Default payment method management
 * - Verification tracking
 */
class PaymentMethodModel extends BaseModel
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'payment_method_id';

    protected $allowedFields = [
        'user_id',
        'payment_type',
        'account_name',
        'account_number_encrypted',
        'account_number_masked',
        'is_default',
        'is_verified',
        'verified_at',
        'metadata',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Fields to encrypt
    protected $encryptedFields = ['account_number_encrypted'];

    /**
     * Add payment method
     */
    public function addPaymentMethod(
        int $userId,
        string $paymentType,
        string $accountName,
        string $accountNumber,
        bool $setAsDefault = false
    ): ?int {
        try {
            // Validate account number
            if (!$this->isValidAccountNumber($accountNumber)) {
                throw new \Exception('Invalid account number format');
            }

            $maskedAccount = $this->maskAccountNumber($accountNumber);

            $data = [
                'user_id' => $userId,
                'payment_type' => strtolower($paymentType),
                'account_name' => $accountName,
                'account_number_encrypted' => $this->encryptData($accountNumber),
                'account_number_masked' => $maskedAccount,
                'is_default' => false,
                'is_verified' => false,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->insert($data);
            $paymentMethodId = $this->getInsertID();

            // Set as default if requested
            if ($setAsDefault) {
                $this->setAsDefault($paymentMethodId, $userId);
            }

            // Audit log
            $this->auditLog(
                $userId,
                'payment_method_add',
                'payment_method',
                $paymentMethodId,
                "Added {$paymentType} payment method ending in " . substr($accountNumber, -4)
            );

            return $paymentMethodId;

        } catch (\Exception $e) {
            log_message('error', 'Add payment method error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user's payment methods (masked, not decrypted for security)
     */
    public function getUserPaymentMethods(int $userId): array
    {
        try {
            return $this->where('user_id', $userId)
                ->where('deleted_at IS NULL')
                ->orderBy('is_default', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get payment methods error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user's default payment method
     */
    public function getDefaultPaymentMethod(int $userId): ?array
    {
        try {
            return $this->where('user_id', $userId)
                ->where('is_default', true)
                ->where('deleted_at IS NULL')
                ->first();

        } catch (\Exception $e) {
            log_message('error', 'Get default payment method error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Set payment method as default
     */
    public function setAsDefault(int $paymentMethodId, int $userId): bool
    {
        try {
            // Verify ownership
            $method = $this->find($paymentMethodId);

            if (!$method || $method['user_id'] != $userId) {
                return false;
            }

            // Remove default from all other methods
            $this->db->table('payment_methods')
                ->where('user_id', $userId)
                ->where('payment_method_id !=', $paymentMethodId)
                ->update(['is_default' => false]);

            // Set this as default
            $this->update($paymentMethodId, [
                'is_default' => true,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Set default payment method error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify payment method (2FA, test charge, etc)
     */
    public function verifyPaymentMethod(int $paymentMethodId, int $userId): bool
    {
        try {
            $method = $this->find($paymentMethodId);

            if (!$method || $method['user_id'] != $userId) {
                return false;
            }

            $this->update($paymentMethodId, [
                'is_verified' => true,
                'verified_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Audit log
            $this->auditLog(
                $userId,
                'payment_method_verified',
                'payment_method',
                $paymentMethodId,
                "Verified {$method['payment_type']} payment method"
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Verify payment method error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete payment method (soft delete)
     */
    public function deletePaymentMethod(int $paymentMethodId, int $userId): bool
    {
        try {
            $method = $this->find($paymentMethodId);

            if (!$method || $method['user_id'] != $userId) {
                return false;
            }

            // Cannot delete if it's the default
            if ($method['is_default']) {
                // Set another as default if available
                $other = $this->where('user_id', $userId)
                    ->where('payment_method_id !=', $paymentMethodId)
                    ->where('deleted_at IS NULL')
                    ->first();

                if ($other) {
                    $this->setAsDefault($other['payment_method_id'], $userId);
                }
            }

            // Soft delete
            $this->delete($paymentMethodId);

            // Audit log
            $this->auditLog(
                $userId,
                'payment_method_delete',
                'payment_method',
                $paymentMethodId,
                "Deleted {$method['payment_type']} payment method"
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Delete payment method error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update payment method details
     */
    public function updatePaymentMethod(
        int $paymentMethodId,
        int $userId,
        string $accountName,
        ?string $accountNumber = null
    ): bool {
        try {
            $method = $this->find($paymentMethodId);

            if (!$method || $method['user_id'] != $userId) {
                return false;
            }

            $updateData = [
                'account_name' => $accountName,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Update account number if provided
            if ($accountNumber) {
                if (!$this->isValidAccountNumber($accountNumber)) {
                    throw new \Exception('Invalid account number format');
                }

                $updateData['account_number_encrypted'] = $this->encryptData($accountNumber);
                $updateData['account_number_masked'] = $this->maskAccountNumber($accountNumber);
            }

            $this->update($paymentMethodId, $updateData);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Update payment method error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get decrypted account number (sensitive - log this action)
     * Only call when absolutely necessary for payment processing
     */
    public function getDecryptedAccountNumber(int $paymentMethodId, int $userId): ?string
    {
        try {
            $method = $this->find($paymentMethodId);

            if (!$method || $method['user_id'] != $userId) {
                return null;
            }

            // Log sensitive data access
            $this->auditLog(
                $userId,
                'data_access',
                'payment_method',
                $paymentMethodId,
                "Accessed encrypted account number for payment processing"
            );

            return $this->decryptData($method['account_number_encrypted'] ?? '');

        } catch (\Exception $e) {
            log_message('error', 'Get decrypted account number error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if payment method exists and is valid
     */
    public function isValidPaymentMethod(int $paymentMethodId, int $userId): bool
    {
        try {
            $method = $this->find($paymentMethodId);

            return $method 
                && $method['user_id'] == $userId 
                && $method['is_verified']
                && empty($method['deleted_at']);

        } catch (\Exception $e) {
            log_message('error', 'Validate payment method error: ' . $e->getMessage());
            return false;
        }
    }
}
