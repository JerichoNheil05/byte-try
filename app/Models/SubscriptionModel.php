<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'status', 'subscription_date', 'end_date', 'payment_method', 'cashout_method', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get subscription status for a user
     */
    public function getSubscriptionStatus($userId)
    {
        try {
            $result = $this->where('user_id', $userId)
                           ->select('status')
                           ->first();

            return $result['status'] ?? 'inactive';
        } catch (\Exception $e) {
            return 'inactive';
        }
    }

    /**
     * Get subscription end date for a user
     */
    public function getSubscriptionEndDate($userId)
    {
        try {
            $result = $this->where('user_id', $userId)
                           ->select('end_date')
                           ->first();

            return $result['end_date'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get payment method for a user
     */
    public function getPaymentMethod($userId)
    {
        try {
            $result = $this->where('user_id', $userId)
                           ->select('payment_method')
                           ->first();

            return $result['payment_method'] ?? 'gcash';
        } catch (\Exception $e) {
            return 'gcash';
        }
    }

    /**
     * Get cashout method for a user
     */
    public function getCashoutMethod($userId)
    {
        try {
            $result = $this->where('user_id', $userId)
                           ->select('cashout_method')
                           ->first();

            return $result['cashout_method'] ?? 'gcash';
        } catch (\Exception $e) {
            return 'gcash';
        }
    }

    /**
     * Update email address
     */
    public function updateEmail($userId, $newEmail)
    {
        $db = \Config\Database::connect();
        $result = $db->table('users')->update(
            ['email' => $newEmail],
            ['id' => $userId]
        );

        return $result;
    }

    /**
     * Update password
     */
    public function updatePassword($userId, $newPassword)
    {
        $db = \Config\Database::connect();
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $result = $db->table('users')->update(
            ['password' => $hashedPassword],
            ['id' => $userId]
        );

        return $result;
    }

    /**
     * Get user password (for verification)
     */
    public function getUserPassword($userId)
    {
        $db = \Config\Database::connect();
        $result = $db->table('users')
                     ->select('password')
                     ->where('id', $userId)
                     ->get()
                     ->getRowArray();

        return $result;
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod($userId, $paymentMethod)
    {
        return $this->update(
            $this->where('user_id', $userId)->first()['id'] ?? null,
            ['payment_method' => $paymentMethod]
        );
    }

    /**
     * Update cashout method
     */
    public function updateCashoutMethod($userId, $cashoutMethod)
    {
        return $this->update(
            $this->where('user_id', $userId)->first()['id'] ?? null,
            ['cashout_method' => $cashoutMethod]
        );
    }

    /**
     * Activate subscription (create new subscription record)
     */
    public function activateSubscription($userId)
    {
        $subscriptionDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime('+1 month'));

        return $this->insert([
            'user_id' => $userId,
            'status' => 'active',
            'subscription_date' => $subscriptionDate,
            'end_date' => $endDate,
            'payment_method' => 'gcash',
            'cashout_method' => 'gcash',
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription($userId)
    {
        $subscription = $this->where('user_id', $userId)->first();

        if (!$subscription) {
            return false;
        }

        return $this->update($subscription['id'], ['status' => 'inactive']);
    }

    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription($userId)
    {
        $result = $this->where('user_id', $userId)
                       ->where('status', 'active')
                       ->where('end_date >=', date('Y-m-d H:i:s'))
                       ->first();

        return !empty($result);
    }

    /**
     * Restrict seller functions if subscription is inactive
     */
    public function canAccessSellerFunctions($userId)
    {
        return $this->hasActiveSubscription($userId);
    }
}
