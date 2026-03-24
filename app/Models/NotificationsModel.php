<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationsModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'message', 'type', 'read', 'related_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get today's notifications for a user
     */
    public function getTodayNotifications($userId)
    {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');

        return $this->where('user_id', $userId)
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get this week's notifications for a user
     */
    public function getWeekNotifications($userId)
    {
        $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $end = date('Y-m-d 23:59:59');

        return $this->where('user_id', $userId)
                    ->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get all unread notifications count for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('read', 0)
                    ->countAllResults();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, ['read' => 1]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)->update(null, ['read' => 1]);
    }

    /**
     * Create a new notification
     */
    public function createNotification($userId, $message, $type = 'general', $relatedId = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
            'read' => 0,
            'related_id' => $relatedId,
        ]);
    }

    /**
     * Delete old notifications (older than 30 days)
     */
    public function deleteOldNotifications()
    {
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        return $this->where('created_at <', $thirtyDaysAgo)->delete();
    }

    /**
     * Get notification activity types
     */
    public function getNotificationTypes()
    {
        return [
            'purchase' => 'Product Purchase',
            'sale' => 'Product Sale',
            'review' => 'Product Review',
            'message' => 'New Message',
            'promotion' => 'Promotion',
            'system' => 'System Update',
            'general' => 'General',
        ];
    }
}
