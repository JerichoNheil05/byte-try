<?php

namespace App\Models;

class ProductFeedbackModel extends BaseModel
{
    protected $table = 'product_feedback';
    protected $primaryKey = 'feedback_id';

    protected $allowedFields = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];

    public function isFeedbackTableReady(): bool
    {
        return $this->db->tableExists($this->table);
    }

    public function getFeedbackForProduct(int $productId): array
    {
        if (!$this->isFeedbackTableReady()) {
            return [];
        }

        return $this->db->table('product_feedback pf')
            ->select('pf.feedback_id, pf.rating, pf.comment, pf.created_at, pf.user_id, u.full_name')
            ->join('users u', 'u.id = pf.user_id', 'left')
            ->where('pf.product_id', $productId)
            ->orderBy('pf.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getRatingSummary(int $productId): array
    {
        if (!$this->isFeedbackTableReady()) {
            return ['average' => 0, 'count' => 0];
        }

        $row = $this->db->table('product_feedback')
            ->select('AVG(rating) as avg_rating, COUNT(*) as total_reviews')
            ->where('product_id', $productId)
            ->get()
            ->getRowArray();

        return [
            'average' => round((float) ($row['avg_rating'] ?? 0), 1),
            'count' => (int) ($row['total_reviews'] ?? 0),
        ];
    }

    public function getRatingSummaryMap(array $productIds): array
    {
        if (!$this->isFeedbackTableReady()) {
            return [];
        }

        $productIds = array_values(array_unique(array_filter(array_map('intval', $productIds), static fn(int $id): bool => $id > 0)));
        if (empty($productIds)) {
            return [];
        }

        $rows = $this->db->table('product_feedback')
            ->select('product_id, AVG(rating) as avg_rating, COUNT(*) as total_reviews')
            ->whereIn('product_id', $productIds)
            ->groupBy('product_id')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $productId = (int) ($row['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            $map[$productId] = [
                'average' => round((float) ($row['avg_rating'] ?? 0), 1),
                'count' => (int) ($row['total_reviews'] ?? 0),
            ];
        }

        return $map;
    }

    public function getUserFeedback(int $userId, int $productId): ?array
    {
        if (!$this->isFeedbackTableReady()) {
            return null;
        }

        $row = $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        return is_array($row) ? $row : null;
    }

    public function hasPurchasedProduct(int $userId, int $productId): bool
    {
        if (!$this->db->tableExists('orders') || !$this->db->tableExists('order_items')) {
            return false;
        }

        $row = $this->db->table('order_items oi')
            ->select('oi.order_item_id')
            ->join('orders o', 'o.order_id = oi.order_id', 'inner')
            ->where('oi.product_id', $productId)
            ->where('o.user_id', $userId)
            ->groupStart()
                ->where('o.payment_status', 'completed')
                ->orWhereIn('o.status', ['confirmed', 'processing', 'completed'])
            ->groupEnd()
            ->limit(1)
            ->get()
            ->getRowArray();

        return !empty($row);
    }

    public function saveFeedback(int $userId, int $productId, int $rating, string $comment): bool
    {
        if (!$this->isFeedbackTableReady()) {
            return false;
        }

        $data = [
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => max(1, min(5, $rating)),
            'comment' => trim($comment),
        ];

        $existing = $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!empty($existing)) {
            return (bool) $this->update((int) $existing['feedback_id'], $data);
        }

        return (bool) $this->insert($data);
    }
}
