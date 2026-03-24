<?php

namespace App\Models;

/**
 * NotificationModel - Manages user notifications
 * 
 * Handles:
 * - Create notifications
 * - Mark as read
 * - Archive notifications
 * - Retrieve user notifications
 * - Notification filtering and sorting
 */
class NotificationModel extends BaseModel
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'message',
        'type',
        'read',
        'related_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get user's unread notifications
     */
    public function getUnreadNotifications(int $userId, int $limit = 5): array
    {
        try {
            return $this->where('user_id', $userId)
                ->where('read', 0)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->limit($limit)
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get unread notifications error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user's all notifications with pagination
     */
    public function getUserNotifications(
        int $userId,
        int $limit = 10,
        int $offset = 0,
        bool $includeArchived = false
    ): array {
        try {
            $query = $this->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC');

            $total = $query->countAllResults(false);
            $notifications = $query->limit($limit)->offset($offset)->findAll();

            return [
                'notifications' => $notifications,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get user notifications error: ' . $e->getMessage());
            return ['notifications' => [], 'total' => 0, 'limit' => $limit, 'offset' => $offset];
        }
    }

    /**
     * Get notifications by type
     */
    public function getNotificationsByType(int $userId, string $type, int $limit = 10): array
    {
        try {
            return $this->where('user_id', $userId)
                ->where('type', $type)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->limit($limit)
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get notifications by type error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        try {
            $notification = $this->find($notificationId);

            if (!$notification || $notification['user_id'] != $userId) {
                return false;
            }

            $this->update($notificationId, [
                'read' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Mark as read error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(int $userId): bool
    {
        try {
            return $this->db->table('notifications')
                ->where('user_id', $userId)
                ->where('read', 0)
                ->update([
                    'read' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

        } catch (\Exception $e) {
            log_message('error', 'Mark all as read error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Archive notification
     */
    public function archiveNotification(int $notificationId, int $userId): bool
    {
        // Legacy compatibility: table has no archive column, so delete is used.
        return $this->deleteNotification($notificationId, $userId);
    }

    /**
     * Delete notification permanently
     */
    public function deleteNotification(int $notificationId, int $userId): bool
    {
        try {
            $notification = $this->find($notificationId);

            if (!$notification || $notification['user_id'] != $userId) {
                return false;
            }

            $this->delete($notificationId);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Delete notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread count
     */
    public function getUnreadCount(int $userId): int
    {
        try {
            return $this->where('user_id', $userId)
                ->where('read', 0)
                ->countAllResults();

        } catch (\Exception $e) {
            log_message('error', 'Get unread count error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get notifications for today
     */
    public function getTodayNotifications(int $userId, int $limit = 10): array
    {
        try {
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 00:00:00', strtotime('+1 day'));

            return $this->where('user_id', $userId)
                ->where('created_at >=', $start)
                ->where('created_at <', $end)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->limit($limit)
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get today notifications error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get notifications for this week
     */
    public function getWeekNotifications(int $userId, int $limit = 20): array
    {
        try {
            $weekAgo = date('Y-m-d 00:00:00', strtotime('-7 days'));

            return $this->where('user_id', $userId)
                ->where('created_at >=', $weekAgo)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->limit($limit)
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get week notifications error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear archived notifications (older than 30 days)
     */
    public function clearOldArchivedNotifications(int $daysOld = 30): bool
    {
        // Legacy compatibility: table has no archive support.
        return true;
    }

    /**
     * Create buyer purchase notification.
     */
    public function createPurchaseNotification(int $userId, string $message, ?int $relatedOrderId = null): bool
    {
        try {
            return $this->insert([
                'user_id'    => $userId,
                'message'    => $message,
                'type'       => 'purchase',
                'read'       => 0,
                'related_id' => $relatedOrderId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]) !== false;
        } catch (\Exception $e) {
            log_message('error', 'Create purchase notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create buyer profile update notification.
     */
    public function createProfileUpdateNotification(int $userId): bool
    {
        try {
            return $this->insert([
                'user_id'    => $userId,
                'message'    => 'updated your profile information.',
                'type'       => 'profile_update',
                'read'       => 0,
                'related_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]) !== false;
        } catch (\Exception $e) {
            log_message('error', 'Create profile update notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get notification template
     */
    public static function getNotificationTemplate(string $type): ?array
    {
        $templates = [
            'order_confirmed' => [
                'icon' => 'fas fa-check-circle',
                'color' => '#249E2F'
            ],
            'order_shipped' => [
                'icon' => 'fas fa-truck',
                'color' => '#2196F3'
            ],
            'order_delivered' => [
                'icon' => 'fas fa-box-open',
                'color' => '#249E2F'
            ],
            'order_cancelled' => [
                'icon' => 'fas fa-times-circle',
                'color' => '#E53935'
            ],
            'payment_received' => [
                'icon' => 'fas fa-check-circle',
                'color' => '#249E2F'
            ],
            'payment_failed' => [
                'icon' => 'fas fa-exclamation-circle',
                'color' => '#E53935'
            ],
            'refund_processed' => [
                'icon' => 'fas fa-undo',
                'color' => '#249E2F'
            ],
            'seller_review' => [
                'icon' => 'fas fa-star',
                'color' => '#FBC02D'
            ],
            'wallet_updated' => [
                'icon' => 'fas fa-wallet',
                'color' => '#249E2F'
            ]
        ];

        return $templates[$type] ?? null;
    }
}
