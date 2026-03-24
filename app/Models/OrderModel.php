<?php

namespace App\Models;

/**
 * OrderModel - Manages buyer orders and order lifecycle
 * 
 * Handles:
 * - Order creation from cart
 * - Order status tracking
 * - Payment status management
 * - Order details retrieval
 * - Order modification and cancellation
 */
class OrderModel extends BaseModel
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $allowedFields = [
        'order_number',
        'user_id',
        'subtotal',
        'tax_amount',
        'tax_rate',
        'discount_amount',
        'total_amount',
        'status',
        'payment_status',
        'payment_method_id',
        'tracking_number',
        'delivery_address',
        'buyer_notes',
        'admin_notes',
        'completed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Create order from cart items
     */
    public function createOrderFromCart(
        int $userId,
        array $cartItems,
        float $subtotal,
        float $taxAmount,
        float $discountAmount,
        ?int $paymentMethodId = null,
        ?string $buyerNotes = null,
        ?string $deliveryAddress = null
    ): ?int {
        try {
            $this->beginTransaction();

            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            // Create order
            $orderData = [
                'order_number' => $this->generateReference('ORD'),
                'user_id' => $userId,
                'subtotal' => round($subtotal, 2),
                'tax_amount' => round($taxAmount, 2),
                'tax_rate' => 12.00,
                'discount_amount' => round($discountAmount, 2),
                'total_amount' => round($totalAmount, 2),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method_id' => $paymentMethodId,
                'delivery_address' => $deliveryAddress,
                'buyer_notes' => $buyerNotes,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->insert($orderData);
            $orderId = $this->getInsertID();

            // Create order items from cart items
            $orderItemModel = new OrderItemModel();
            foreach ($cartItems as $item) {
                $itemData = [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'seller_id' => $item['seller_id'],
                    'product_title' => $item['product_title'] ?? 'Unknown Product',
                    'product_category' => $item['category_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'discount_per_item' => 0,
                    'subtotal' => round($item['price'] * $item['quantity'], 2),
                    'item_status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $orderItemModel->insert($itemData);

                // Notify seller
                $this->createNotification(
                    $item['seller_id'],
                    'order_confirmed',
                    'New Order Received',
                    "You have received a new order #{$orderData['order_number']}",
                    'fas fa-box',
                    '#249E2F',
                    $orderId,
                    base_url("seller/orders/{$orderId}")
                );
            }

            // Audit log
            $this->auditLog(
                $userId,
                'order_created',
                'order',
                $orderId,
                "Created order {$orderData['order_number']} with " . count($cartItems) . " items",
                null,
                ['total_items' => count($cartItems), 'total_amount' => $totalAmount]
            );

            if ($this->transactionFailed()) {
                throw new \Exception('Transaction failed');
            }

            return $orderId;

        } catch (\Exception $e) {
            log_message('error', 'Create order error: ' . $e->getMessage());
            $this->rollbackTransaction();
            return null;
        }
    }

    /**
     * Get order details with items
     */
    public function getOrderDetails(int $orderId, int $userId): ?array
    {
        try {
            $order = $this->select('o.*, pm.account_name, pm.account_number_masked, pm.payment_type')
                ->from('orders as o')
                ->join('payment_methods as pm', 'pm.payment_method_id = o.payment_method_id', 'left')
                ->where('o.order_id', $orderId)
                ->where('o.user_id', $userId)
                ->first();

            if (!$order) {
                return null;
            }

            // Get order items
            $orderItemModel = new OrderItemModel();
            $order['items'] = $orderItemModel->where('order_id', $orderId)->findAll();

            return $order;

        } catch (\Exception $e) {
            log_message('error', 'Get order details error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user's orders with pagination
     */
    public function getUserOrders(int $userId, string $status = '', int $limit = 10, int $offset = 0): array
    {
        try {
            $query = $this->where('user_id', $userId)
                ->orderBy('created_at', 'DESC');

            if (!empty($status)) {
                $query->where('status', $status);
            }

            $total = $query->countAllResults(false);
            $orders = $query->limit($limit)->offset($offset)->findAll();

            return [
                'orders' => $orders,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get user orders error: ' . $e->getMessage());
            return ['orders' => [], 'total' => 0, 'limit' => $limit, 'offset' => $offset];
        }
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $orderId, int $userId, string $newStatus, ?string $adminNotes = null): bool
    {
        try {
            $validStatuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled', 'refunded'];

            if (!in_array($newStatus, $validStatuses)) {
                return false;
            }

            $order = $this->select('status')->find($orderId);

            if (!$order) {
                return false;
            }

            $oldStatus = $order['status'];
            $updateData = [
                'status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($newStatus === 'completed') {
                $updateData['completed_at'] = date('Y-m-d H:i:s');
            }

            if ($adminNotes) {
                $updateData['admin_notes'] = $adminNotes;
            }

            $this->update($orderId, $updateData);

            // Notify buyer
            $this->createNotification(
                $userId,
                'order_status_changed',
                'Order Status Updated',
                "Your order status changed from {$oldStatus} to {$newStatus}",
                'fas fa-info-circle',
                '#308BE5',
                $orderId
            );

            // Audit log
            $this->auditLog(
                $userId,
                'order_status_updated',
                'order',
                $orderId,
                "Updated order status from {$oldStatus} to {$newStatus}",
                ['status' => $oldStatus],
                ['status' => $newStatus]
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Update order status error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $orderId, string $newPaymentStatus): bool
    {
        try {
            $validStatuses = ['unpaid', 'processing', 'completed', 'failed', 'refunded'];

            if (!in_array($newPaymentStatus, $validStatuses)) {
                return false;
            }

            $this->update($orderId, [
                'payment_status' => $newPaymentStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Update payment status error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder(int $orderId, int $userId, ?string $reason = null): bool
    {
        try {
            $order = $this->find($orderId);

            if (!$order || $order['user_id'] != $userId) {
                return false;
            }

            if (in_array($order['status'], ['completed', 'cancelled', 'refunded'])) {
                return false; // Cannot cancel completed or cancelled orders
            }

            $this->update($orderId, [
                'status' => 'cancelled',
                'admin_notes' => $reason,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Notify sellers
            $orderItemModel = new OrderItemModel();
            $items = $orderItemModel->where('order_id', $orderId)->findAll();

            foreach ($items as $item) {
                $this->createNotification(
                    $item['seller_id'],
                    'order_cancelled',
                    'Order Cancelled',
                    "Order #{$order['order_number']} has been cancelled by buyer",
                    'fas fa-times-circle',
                    '#E53935',
                    $orderId
                );
            }

            // Audit log
            $this->auditLog(
                $userId,
                'order_cancelled',
                'order',
                $orderId,
                "Cancelled order #{$order['order_number']}. Reason: {$reason}"
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Cancel order error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order count by status for dashboard
     */
    public function getOrderCountByStatus(int $userId): array
    {
        try {
            $statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
            $counts = [];

            foreach ($statuses as $status) {
                $counts[$status] = $this->where('user_id', $userId)
                    ->where('status', $status)
                    ->countAllResults();
            }

            return $counts;

        } catch (\Exception $e) {
            log_message('error', 'Get order count error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Search orders
     */
    public function searchOrders(int $userId, string $searchTerm, int $limit = 10): array
    {
        try {
            return $this->where('user_id', $userId)
                ->like('order_number', $searchTerm)
                ->orLike('buyer_notes', $searchTerm)
                ->limit($limit)
                ->orderBy('created_at', 'DESC')
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Search orders error: ' . $e->getMessage());
            return [];
        }
    }
}
