<?php

namespace App\Models;

/**
 * OrderItemModel - Manages individual items within orders
 */
class OrderItemModel extends BaseModel
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';

    protected $allowedFields = [
        'order_id',
        'product_id',
        'seller_id',
        'product_title',
        'product_category',
        'quantity',
        'unit_price',
        'discount_per_item',
        'subtotal',
        'item_status',
        'seller_notes',
        'created_at',
        'updated_at'
    ];

    /**
     * Get items for an order
     */
    public function getOrderItems(int $orderId): array
    {
        try {
            return $this->where('order_id', $orderId)->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Get order items error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Update item status
     */
    public function updateItemStatus(int $orderItemId, int $sellerId, string $newStatus, ?string $sellerNotes = null): bool
    {
        try {
            $item = $this->find($orderItemId);

            if (!$item || $item['seller_id'] != $sellerId) {
                return false;
            }

            $validStatuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled', 'returned'];

            if (!in_array($newStatus, $validStatuses)) {
                return false;
            }

            $updateData = [
                'item_status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($sellerNotes) {
                $updateData['seller_notes'] = $sellerNotes;
            }

            $this->update($orderItemId, $updateData);

            // Get order for buyer notification
            $order = $this->db->table('orders')->select('user_id, order_number')->find($item['order_id']);

            if ($order) {
                // Notify buyer of item status change
                $this->createNotification(
                    $order['user_id'],
                    'order_item_updated',
                    'Order Item Status Updated',
                    "{$item['product_title']} status changed to {$newStatus}",
                    'fas fa-box',
                    '#249E2F',
                    $item['order_id']
                );
            }

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Update item status error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get seller's pending items
     */
    public function getSellerPendingItems(int $sellerId, int $limit = 10): array
    {
        try {
            return $this->select('oi.*, o.order_number, o.created_at as order_date')
                ->from('order_items as oi')
                ->join('orders as o', 'o.order_id = oi.order_id')
                ->where('oi.seller_id', $sellerId)
                ->where('oi.item_status', 'pending')
                ->orderBy('o.created_at', 'ASC')
                ->limit($limit)
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get seller pending items error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get seller's items for a date range
     */
    public function getSellerItemsByDateRange(int $sellerId, string $startDate, string $endDate): array
    {
        try {
            return $this->select('oi.*, o.order_number')
                ->from('order_items as oi')
                ->join('orders as o', 'o.order_id = oi.order_id')
                ->where('oi.seller_id', $sellerId)
                ->where('DATE(oi.created_at) >=', $startDate)
                ->where('DATE(oi.created_at) <=', $endDate)
                ->orderBy('oi.created_at', 'DESC')
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get seller items by date range error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get item count by status for seller
     */
    public function getSellerItemCountByStatus(int $sellerId): array
    {
        try {
            $statuses = ['pending', 'confirmed', 'shipped', 'delivered'];
            $counts = [];

            foreach ($statuses as $status) {
                $counts[$status] = $this->where('seller_id', $sellerId)
                    ->where('item_status', $status)
                    ->countAllResults();
            }

            return $counts;

        } catch (\Exception $e) {
            log_message('error', 'Get seller item count error: ' . $e->getMessage());
            return [];
        }
    }
}
