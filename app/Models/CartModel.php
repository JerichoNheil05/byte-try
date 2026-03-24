<?php

namespace App\Models;

/**
 * CartModel - Manages buyer shopping cart items
 * 
 * Handles:
 * - Adding/removing items from cart
 * - Quantity management
 * - Selection state (checkout)
 * - Cart totals calculation
 * - Soft deletes for retention
 */
class CartModel extends BaseModel
{
    protected $table = 'cart_items';
    protected $primaryKey = 'cart_item_id';

    protected $allowedFields = [
        'user_id',
        'product_id',
        'seller_id',
        'quantity',
        'price',
        'is_selected',
        'notes',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Add product to cart
     */
    public function addToCart(int $userId, int $productId, int $sellerId, float $price, int $quantity = 1, ?string $notes = null): ?int
    {
        try {
            // Check if item already exists in cart
            $existing = $this->where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('seller_id', $sellerId)
                ->where('deleted_at', null)
                ->first();

            if ($existing) {
                // Update quantity if product already in cart
                $newQuantity = $existing['quantity'] + $quantity;
                $this->update($existing['cart_item_id'], [
                    'quantity' => $newQuantity,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                return $existing['cart_item_id'];
            }

            // Add new item
            $data = [
                'user_id' => $userId,
                'product_id' => $productId,
                'seller_id' => $sellerId,
                'quantity' => $quantity,
                'price' => $price,
                'is_selected' => true,
                'notes' => $notes,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->insert($data);
            
            // Log action
            $this->auditLog(
                $userId,
                'product_added_to_cart',
                'cart_item',
                $this->getInsertID(),
                "Added product ID {$productId} to cart (qty: {$quantity})"
            );

            return $this->getInsertID();

        } catch (\Exception $e) {
            log_message('error', 'Add to cart error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Remove item from cart (soft delete)
     */
    public function removeFromCart(int $cartItemId, int $userId): bool
    {
        try {
            // Verify ownership
            $item = $this->where('cart_item_id', $cartItemId)
                ->where('user_id', $userId)
                ->first();

            if (!$item) {
                return false;
            }

            // Soft delete
            $this->delete($cartItemId);

            // Audit log
            $this->auditLog(
                $userId,
                'product_removed_from_cart',
                'cart_item',
                $cartItemId,
                "Removed product ID {$item['product_id']} from cart"
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Remove from cart error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update item quantity
     */
    public function updateQuantity(int $cartItemId, int $userId, int $quantity): bool
    {
        try {
            if ($quantity <= 0) {
                return $this->removeFromCart($cartItemId, $userId);
            }

            $item = $this->where('cart_item_id', $cartItemId)
                ->where('user_id', $userId)
                ->first();

            if (!$item) {
                return false;
            }

            $oldQuantity = $item['quantity'];

            $this->update($cartItemId, [
                'quantity' => $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Audit log
            $this->auditLog(
                $userId,
                'cart_item_quantity_updated',
                'cart_item',
                $cartItemId,
                "Updated quantity from {$oldQuantity} to {$quantity}",
                ['quantity' => $oldQuantity],
                ['quantity' => $quantity]
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Update quantity error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update selection status for checkout
     */
    public function updateSelection(int $cartItemId, int $userId, bool $isSelected): bool
    {
        try {
            $item = $this->where('cart_item_id', $cartItemId)
                ->where('user_id', $userId)
                ->first();

            if (!$item) {
                return false;
            }

            $this->update($cartItemId, [
                'is_selected' => $isSelected,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Update selection error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user's cart items with product details
     */
    public function getCartItems(int $userId, bool $selectedOnly = false): array
    {
        try {
            $query = $this->db->table('cart_items c')
                ->select('c.*, p.title as product_title, p.category as product_category, p.preview_path, COALESCE(u.full_name, u.email, "Unknown Seller") as seller_name')
                ->join('products p', 'p.id = c.product_id', 'left')
                ->join('users u', 'u.id = c.seller_id', 'left')
                ->where('c.user_id', $userId)
                ->where('c.deleted_at', null);

            if ($selectedOnly) {
                $query->where('c.is_selected', 1);
            }

            return $query->orderBy('c.created_at', 'DESC')->get()->getResultArray();

        } catch (\Exception $e) {
            log_message('error', 'Get cart items error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cart summary with totals
     */
    public function getCartSummary(int $userId, bool $selectedOnly = true): array
    {
        try {
            $items = $this->getCartItems($userId, $selectedOnly);

            $subtotal = 0;
            $totalQuantity = 0;
            $itemCount = count($items);

            foreach ($items as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                $subtotal += $itemTotal;
                $totalQuantity += $item['quantity'];
            }

            $taxRate = 12.00;
            $taxAmount = $this->calculateTax($subtotal, $taxRate);
            $total = $subtotal + $taxAmount;

            return [
                'items' => $items,
                'item_count' => $itemCount,
                'total_quantity' => $totalQuantity,
                'subtotal' => round($subtotal, 2),
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total' => round($total, 2)
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get cart summary error: ' . $e->getMessage());
            return [
                'items' => [],
                'item_count' => 0,
                'total_quantity' => 0,
                'subtotal' => 0,
                'tax_rate' => 12,
                'tax_amount' => 0,
                'total' => 0
            ];
        }
    }

    /**
     * Clear entire cart (soft delete all items)
     */
    public function clearCart(int $userId): bool
    {
        try {
            $items = $this->where('user_id', $userId)
                ->where('deleted_at IS NULL')
                ->findAll();

            $deletedCount = 0;

            foreach ($items as $item) {
                if ($this->delete($item['cart_item_id'])) {
                    $deletedCount++;
                }
            }

            if ($deletedCount > 0) {
                $this->auditLog(
                    $userId,
                    'cart_cleared',
                    'cart',
                    null,
                    "Cleared cart ({$deletedCount} items removed)"
                );
            }

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Clear cart error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cart item count
     */
    public function getItemCount(int $userId): int
    {
        try {
            return $this->where('user_id', $userId)
                ->where('deleted_at IS NULL')
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Get item count error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Validate cart before checkout
     */
    public function validateCartForCheckout(int $userId): array
    {
        try {
            $items = $this->getCartItems($userId, true);
            $errors = [];

            if (empty($items)) {
                $errors[] = 'Cart is empty. Please add items before checkout.';
                return $errors;
            }

            // Validate each item
            foreach ($items as $item) {
                if ($item['quantity'] <= 0) {
                    $errors[] = "Invalid quantity for {$item['product_title']}";
                }
                if ($item['price'] <= 0) {
                    $errors[] = "Invalid price for {$item['product_title']}";
                }
            }

            return $errors;

        } catch (\Exception $e) {
            log_message('error', 'Validate cart error: ' . $e->getMessage());
            return ['An error occurred while validating cart'];
        }
    }

    /**
     * Get grouped items by seller
     */
    public function getItemsGroupedBySeller(int $userId): array
    {
        try {
            $items = $this->getCartItems($userId, true);
            $grouped = [];

            foreach ($items as $item) {
                $sellerId = $item['seller_id'];
                if (!isset($grouped[$sellerId])) {
                    $grouped[$sellerId] = [
                        'seller_id' => $sellerId,
                        'seller_name' => $item['seller_name'],
                        'items' => [],
                        'subtotal' => 0
                    ];
                }

                $itemTotal = $item['price'] * $item['quantity'];
                $grouped[$sellerId]['items'][] = $item;
                $grouped[$sellerId]['subtotal'] += $itemTotal;
            }

            return $grouped;

        } catch (\Exception $e) {
            log_message('error', 'Get items grouped error: ' . $e->getMessage());
            return [];
        }
    }
}
