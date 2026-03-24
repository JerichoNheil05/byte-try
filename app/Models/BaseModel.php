<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BaseModel - Extends CodeIgniter Model with standardized security and utility methods
 * 
 * Features:
 * - Automatic field encryption/decryption
 * - Audit logging
 * - Soft deletes
 * - Timestamp management
 * - data validation
 */
class BaseModel extends Model
{
    /**
     * Fields that should be encrypted before storage
     */
    protected array $encryptedFields = [];

    /**
     * Default return type
     */
    protected $returnType = 'array';

    /**
     * Allow batch inserts
     */
    protected bool $allowEmptyInserts = false;

    /**
     * Use timestamps automatically
     */
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    /**
     * Soft delete field
     */
    protected $deletedField = 'deleted_at';

    /**
     * Automatically exclude deleted records
     */
    protected $useSoftDeletes = false;

    /**
     * Validation rules
     */
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get database instance
     */
    protected $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Encrypt sensitive data
     * 
     * Uses CodeIgniter's Encryption service with AES-256
     */
    protected function encryptData(string $data): string
    {
        if (empty($data)) {
            return '';
        }

        try {
            return \Config\Services::encrypter()->encrypt($data);
        } catch (\Exception $e) {
            log_message('error', 'Encryption error: ' . $e->getMessage());
            throw new \RuntimeException('Failed to encrypt data');
        }
    }

    /**
     * Decrypt sensitive data
     */
    protected function decryptData(string $encryptedData): string
    {
        if (empty($encryptedData)) {
            return '';
        }

        try {
            return \Config\Services::encrypter()->decrypt($encryptedData);
        } catch (\Exception $e) {
            log_message('error', 'Decryption error: ' . $e->getMessage());
            throw new \RuntimeException('Failed to decrypt data');
        }
    }

    /**
     * Generate masked account number
     * Example: 09551234567 -> 0955-12XX-X567 or XXXX-XXXX-3567
     */
    protected function maskAccountNumber(string $accountNumber, int $showLast = 4): string
    {
        $length = strlen($accountNumber);
        
        if ($length <= $showLast) {
            return $accountNumber;
        }

        $masked = str_repeat('X', $length - $showLast) . substr($accountNumber, -$showLast);
        
        // Format with dashes every 4 characters
        $formatted = chunk_split($masked, 4, '-');
        
        return rtrim($formatted, '-');
    }

    /**
     * Generate unique reference number
     * Format: PREFIX-TIMESTAMP-RANDOM
     * Example: ORD-1708336800-A7K9
     */
    protected function generateReference(string $prefix = 'REF'): string
    {
        $timestamp = time();
        $random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4);
        
        return strtoupper($prefix) . '-' . $timestamp . '-' . $random;
    }

    /**
     * Audit log an action with change tracking
     */
    protected function auditLog(
        int $userId,
        string $actionType,
        string $entityType,
        ?int $entityId,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null,
        string $status = 'success'
    ): bool {
        try {
            $auditData = [
                'user_id' => $userId,
                'action_type' => $actionType,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'description' => $description,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'status' => $status,
                'ip_address' => $this->getClientIp(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            return $this->db->table('audit_logs')->insert($auditData);
        } catch (\Exception $e) {
            log_message('error', 'Audit log error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get client IP address.
     * Only trusts X-Forwarded-For when a trusted proxy is in use;
     * falls back to REMOTE_ADDR to prevent IP spoofing in audit logs.
     */
    protected function getClientIp(): string
    {
        // Only trust X-Forwarded-For on explicitly configured trusted proxies.
        // Leave TRUSTED_PROXIES empty (or define it in Constants.php) to disable.
        $trustedProxies = defined('TRUSTED_PROXIES') ? (array) TRUSTED_PROXIES : [];
        $remoteAddr     = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        if (!empty($trustedProxies) && in_array($remoteAddr, $trustedProxies, true)) {
            // Take the first (client) IP from the forwarded header.
            $forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
            if ($forwarded !== '') {
                $ip = trim(explode(',', $forwarded)[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return filter_var($remoteAddr, FILTER_VALIDATE_IP) ? $remoteAddr : '0.0.0.0';
    }

    /**
     * Create system notification
     */
    protected function createNotification(
        int $userId,
        string $notificationType,
        string $title,
        string $message,
        ?string $icon = null,
        ?string $color = null,
        ?int $orderId = null,
        ?string $actionUrl = null
    ): bool {
        try {
            $notificationData = [
                'user_id' => $userId,
                'notification_type' => $notificationType,
                'title' => $title,
                'message' => $message,
                'icon' => $icon,
                'color' => $color,
                'related_order_id' => $orderId,
                'action_url' => $actionUrl,
                'is_read' => false,
                'created_at' => date('Y-m-d H:i:s')
            ];

            return $this->db->table('notifications')->insert($notificationData);
        } catch (\Exception $e) {
            log_message('error', 'Notification creation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate email format
     */
    protected function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone number (Philippine format)
     */
    protected function isValidPhoneNumber(string $phone): bool
    {
        // Philippine format: +63XXXXXXXXXX or 09XXXXXXXXX
        return preg_match('/^(\+63|0)9\d{9}$/', str_replace(['-', ' ', '(', ')'], '', $phone));
    }

    /**
     * Validate account number (10-20 digits)
     */
    protected function isValidAccountNumber(string $accountNumber): bool
    {
        $digits = preg_replace('/[^0-9]/', '', $accountNumber);
        return strlen($digits) >= 10 && strlen($digits) <= 20;
    }

    /**
     * Format currency amount
     */
    protected function formatCurrency(float $amount, string $currency = 'PHP', int $decimals = 2): string
    {
        return $currency . ' ' . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Calculate tax amount (12% VAT)
     */
    protected function calculateTax(float $subtotal, float $taxRate = 12.00): float
    {
        return round($subtotal * ($taxRate / 100), 2);
    }

    /**
     * Override insert to handle encryption
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data)) {
            $data = $this->encryptSensitiveFields($data);
        }

        return parent::insert($data, $returnID);
    }

    /**
     * Override update to handle encryption
     */
    public function update($id = null, $data = null): bool
    {
        if (is_array($data)) {
            $data = $this->encryptSensitiveFields($data);
        }

        return parent::update($id, $data);
    }

    /**
     * Encrypt specified fields in data array
     */
    protected function encryptSensitiveFields(array $data): array
    {
        foreach ($this->encryptedFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $data[$field] = $this->encryptData($data[$field]);
            }
        }

        return $data;
    }

    /**
     * Get record with decrypted fields
     */
    public function findWithDecryption($id)
    {
        $record = $this->find($id);
        
        if ($record && !empty($this->encryptedFields)) {
            foreach ($this->encryptedFields as $field) {
                if (isset($record[$field]) && !empty($record[$field])) {
                    $record[$field] = $this->decryptData($record[$field]);
                }
            }
        }

        return $record;
    }

    /**
     * Get all records with decrypted fields
     */
    public function findAllWithDecryption(int $limit = 0, int $offset = 0)
    {
        $records = $limit > 0 
            ? $this->limit($limit)->offset($offset)->findAll()
            : $this->findAll();

        if (empty($records)) {
            return $records;
        }

        foreach ($records as &$record) {
            foreach ($this->encryptedFields as $field) {
                if (isset($record[$field]) && !empty($record[$field])) {
                    $record[$field] = $this->decryptData($record[$field]);
                }
            }
        }

        return $records;
    }

    /**
     * Begin database transaction
     */
    protected function beginTransaction(): void
    {
        $this->db->transStart();
    }

    /**
     * Commit database transaction
     */
    protected function commitTransaction(): bool
    {
        return $this->db->transComplete();
    }

    /**
     * Rollback transaction
     */
    protected function rollbackTransaction(): void
    {
        $this->db->transRollback();
    }

    /**
     * Check if transaction failed
     */
    protected function transactionFailed(): bool
    {
        return $this->db->transStatus() === false;
    }

    /**
     * Get raw database instance for complex queries
     */
    protected function rawQuery(string $sql, array $binds = [])
    {
        return $this->db->query($sql, $binds);
    }
}