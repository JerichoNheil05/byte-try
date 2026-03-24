<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    private NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Display the notifications page.
     */
    public function index()
    {
        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('auth/login')->with('error', 'Please login first.');
        }

        $todayRows = $this->notificationModel->getTodayNotifications($userId, 50);
        $weekRows = $this->notificationModel->getWeekNotifications($userId, 100);

        $todayRows = $this->sortNotificationRows($todayRows);
        $weekRows = $this->sortNotificationRows($weekRows);

        $allRows = array_merge($todayRows, $weekRows);
        $refundDetailsByOrder = $this->getRefundDetailsByOrderId($userId, $allRows);
        $orderDetailsByOrder = $this->getOrderDetailsByOrderId($userId, $allRows);

        // Avoid duplicating today's rows under "This Week".
        $displayTz = $this->getDisplayTimezone();
        $todayDate = (new \DateTimeImmutable('now', $displayTz))->format('Y-m-d');
        $weekRows = array_values(array_filter($weekRows, function (array $row) use ($todayDate, $displayTz): bool {
            $created = (string) ($row['created_at'] ?? '');
            if ($created === '') {
                return true;
            }

            try {
                $createdLocal = (new \DateTimeImmutable($created, new \DateTimeZone('UTC')))
                    ->setTimezone($displayTz)
                    ->format('Y-m-d');
            } catch (\Throwable $e) {
                $createdLocal = substr($created, 0, 10);
            }

            return $createdLocal !== $todayDate;
        }));

        return view('notifications', [
            'todayNotifications' => $this->formatNotifications($todayRows, true, $refundDetailsByOrder, $orderDetailsByOrder),
            'weekNotifications' => $this->formatNotifications($weekRows, false, $refundDetailsByOrder, $orderDetailsByOrder),
            'todayCount' => count($todayRows),
            'showPlaceholder' => false,
            'openNotificationId' => (int) ($this->request->getGet('open') ?? 0),
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function mark_as_read($id = null)
    {
        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('auth/login')->with('error', 'Please login first.');
        }

        $notificationId = (int) $id;
        if ($notificationId <= 0) {
            return redirect()->back()->with('error', 'Invalid notification ID.');
        }

        try {
            $isMarked = $this->notificationModel->markAsRead($notificationId, $userId);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => $isMarked,
                    'message' => $isMarked ? 'Notification marked as read.' : 'Notification not found.',
                ]);
            }

            if ($isMarked) {
                return redirect()->back()->with('success', 'Notification marked as read.');
            }

            return redirect()->back()->with('error', 'Notification not found.');
        } catch (\Throwable $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error marking notification as read.',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Error marking notification as read.');
        }
    }

    /**
     * Fetch today's notifications.
     */
    public function fetch_today()
    {
        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ])->setStatusCode(401);
        }

        try {
            $rows = $this->notificationModel->getTodayNotifications($userId, 50);
            $formatted = $this->formatNotifications(
                $rows,
                true,
                $this->getRefundDetailsByOrderId($userId, $rows),
                $this->getOrderDetailsByOrderId($userId, $rows)
            );

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $formatted,
                    'count' => count($formatted),
                ]);
            }

            return redirect()->to('notifications');
        } catch (\Throwable $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error fetching notifications.',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Error fetching notifications.');
        }
    }

    /**
     * Fetch this week's notifications.
     */
    public function fetch_week()
    {
        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
            ])->setStatusCode(401);
        }

        try {
            $rows = $this->notificationModel->getWeekNotifications($userId, 100);
            $formatted = $this->formatNotifications(
                $rows,
                false,
                $this->getRefundDetailsByOrderId($userId, $rows),
                $this->getOrderDetailsByOrderId($userId, $rows)
            );

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $formatted,
                    'count' => count($formatted),
                ]);
            }

            return redirect()->to('notifications');
        } catch (\Throwable $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error fetching notifications.',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Error fetching notifications.');
        }
    }

    private function formatNotifications(array $rows, bool $forToday, array $refundDetailsByOrder = [], array $orderDetailsByOrder = []): array
    {
        return array_map(function (array $row) use ($forToday, $refundDetailsByOrder, $orderDetailsByOrder): array {
            $createdAtRaw = (string) ($row['created_at'] ?? date('Y-m-d H:i:s'));
            $createdAt = $this->convertUtcToDisplayTime($createdAtRaw);
            $relatedId = (int) ($row['related_id'] ?? 0);
            $type = (string) ($row['type'] ?? '');
            $refundDetails = $relatedId > 0 ? ($refundDetailsByOrder[$relatedId] ?? null) : null;
            $orderDetails = $relatedId > 0 ? ($orderDetailsByOrder[$relatedId] ?? null) : null;

            return [
                'id' => (int) ($row['id'] ?? 0),
                'message' => (string) ($row['message'] ?? ''),
                'created_at' => $createdAt,
                'read' => (bool) ((int) ($row['read'] ?? 0)),
                'type' => $type,
                'related_id' => $relatedId,
                'refund_details' => $refundDetails,
                'order_details' => $orderDetails,
                'time_ago' => $forToday ? $this->formatTimeAgo($createdAt) : null,
                'date_formatted' => $forToday ? null : date('F j', strtotime($createdAt)),
            ];
        }, $rows);
    }

    private function getOrderDetailsByOrderId(int $userId, array $rows): array
    {
        $orderIds = [];
        foreach ($rows as $row) {
            $type = (string) ($row['type'] ?? '');
            $relatedId = (int) ($row['related_id'] ?? 0);
            if (in_array($type, ['refund_request', 'refund_result', 'refund_reverse_payment'], true) || $relatedId <= 0) {
                continue;
            }

            $orderIds[] = $relatedId;
        }

        $orderIds = array_values(array_unique($orderIds));
        if ($userId <= 0 || empty($orderIds)) {
            return [];
        }

        try {
            $db = \Config\Database::connect();
            if (!$db->tableExists('orders') || !$db->tableExists('order_items')) {
                return [];
            }

            $rows = $db->table('orders o')
                ->select('o.order_id, o.order_number, o.created_at as order_created_at, oi.product_title, oi.seller_id, p.preview_path')
                ->join('order_items oi', 'oi.order_id = o.order_id', 'left')
                ->join('products p', 'p.id = oi.product_id', 'left')
                ->whereIn('o.order_id', $orderIds)
                ->groupStart()
                    ->where('o.user_id', $userId)
                    ->orWhere('oi.seller_id', $userId)
                ->groupEnd()
                ->orderBy('o.order_id', 'DESC')
                ->orderBy('oi.order_item_id', 'ASC')
                ->get()
                ->getResultArray();

            $detailsByOrder = [];
            foreach ($rows as $row) {
                $orderId = (int) ($row['order_id'] ?? 0);
                if ($orderId <= 0 || isset($detailsByOrder[$orderId])) {
                    continue;
                }

                $detailsByOrder[$orderId] = [
                    'order_id' => $orderId,
                    'order_number' => (string) ($row['order_number'] ?? ''),
                    'product_title' => trim((string) ($row['product_title'] ?? 'Product')) ?: 'Product',
                    'product_thumbnail_url' => $this->resolveThumbnailUrl((string) ($row['preview_path'] ?? '')),
                    'bought_at' => $this->convertUtcToDisplayTime((string) ($row['order_created_at'] ?? '')),
                ];
            }

            return $detailsByOrder;
        } catch (\Throwable $e) {
            log_message('warning', 'Order details lookup skipped: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Collect the latest refund request details for related notification order IDs.
     */
    private function getRefundDetailsByOrderId(int $userId, array $rows): array
    {
        $orderIds = [];
        foreach ($rows as $row) {
            $type = (string) ($row['type'] ?? '');
            $relatedId = (int) ($row['related_id'] ?? 0);
            if (!in_array($type, ['refund_request', 'refund_result', 'refund_reverse_payment'], true) || $relatedId <= 0) {
                continue;
            }

            $orderIds[] = $relatedId;
        }

        $orderIds = array_values(array_unique($orderIds));
        if ($userId <= 0 || empty($orderIds)) {
            return [];
        }

        try {
            $db = \Config\Database::connect();
            if (!$db->tableExists('refund_requests')) {
                return [];
            }

            $resultRows = $db->table('refund_requests rr')
                ->select('rr.id, rr.order_id, rr.product_id, rr.reason, rr.issue_type, rr.issue_source, rr.product_status, rr.replacement_status, rr.product_received_mismatch, rr.proof_of_issue, rr.request_days, rr.refund_decision, rr.status, rr.admin_review_required, rr.decision_rule, rr.buyer_notes, rr.created_at, rr.updated_at, rr.seller_reverse_notified_at, rr.seller_refund_processed_at, oi.product_title, oi.seller_id, oi.item_status, o.order_number, o.status as order_status, o.payment_status, p.preview_path')
                ->join('order_items oi', 'oi.order_item_id = rr.order_item_id', 'left')
                                ->join('orders o', 'o.order_id = rr.order_id', 'left')
                ->join('products p', 'p.id = rr.product_id', 'left')
                ->whereIn('rr.order_id', $orderIds)
                ->groupStart()
                    ->where('rr.buyer_id', $userId)
                    ->orWhere('oi.seller_id', $userId)
                ->groupEnd()
                ->orderBy('rr.created_at', 'DESC')
                ->orderBy('rr.id', 'DESC')
                ->get()
                ->getResultArray();

            $detailsByOrder = [];
            foreach ($resultRows as $resultRow) {
                $orderId = (int) ($resultRow['order_id'] ?? 0);
                if ($orderId <= 0 || isset($detailsByOrder[$orderId])) {
                    continue;
                }

                $detailsByOrder[$orderId] = [
                    'refund_id' => (int) ($resultRow['id'] ?? 0),
                    'order_id' => $orderId,
                    'order_number' => (string) ($resultRow['order_number'] ?? ''),
                    'payment_id' => (string) ($resultRow['order_number'] ?? ''),
                    'product_title' => (string) ($resultRow['product_title'] ?? 'Product'),
                    'product_thumbnail_url' => $this->resolveThumbnailUrl((string) ($resultRow['preview_path'] ?? '')),
                    'reason' => (string) ($resultRow['reason'] ?? 'other'),
                    'issue_type' => (string) ($resultRow['issue_type'] ?? 'none'),
                    'issue_source' => (string) ($resultRow['issue_source'] ?? 'unknown'),
                    'product_status' => (string) ($resultRow['product_status'] ?? 'normal'),
                    'replacement_status' => (string) ($resultRow['replacement_status'] ?? 'not_requested'),
                    'product_received_mismatch' => (bool) ((int) ($resultRow['product_received_mismatch'] ?? 0)),
                    'proof_of_issue' => (bool) ((int) ($resultRow['proof_of_issue'] ?? 0)),
                    'request_days' => (int) ($resultRow['request_days'] ?? 0),
                    'refund_decision' => (string) ($resultRow['refund_decision'] ?? 'DENIED'),
                    'status' => (string) ($resultRow['status'] ?? 'REJECTED'),
                    'admin_review_required' => (bool) ((int) ($resultRow['admin_review_required'] ?? 0)),
                    'decision_rule' => (string) ($resultRow['decision_rule'] ?? 'NO_MATCH'),
                    'buyer_notes' => (string) ($resultRow['buyer_notes'] ?? ''),
                    'seller_id' => (int) ($resultRow['seller_id'] ?? 0),
                    'seller_reverse_notified_at' => (string) ($resultRow['seller_reverse_notified_at'] ?? ''),
                    'seller_refund_processed_at' => (string) ($resultRow['seller_refund_processed_at'] ?? ''),
                    'item_status' => (string) ($resultRow['item_status'] ?? ''),
                    'order_status' => (string) ($resultRow['order_status'] ?? ''),
                    'payment_status' => (string) ($resultRow['payment_status'] ?? ''),
                    'is_seller_notification' => ((int) ($resultRow['seller_id'] ?? 0)) === $userId,
                    'created_at' => (string) ($resultRow['created_at'] ?? ''),
                    'updated_at' => (string) ($resultRow['updated_at'] ?? ''),
                ];
            }

            return $detailsByOrder;
        } catch (\Throwable $e) {
            log_message('warning', 'Refund details lookup skipped: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Enforce deterministic ordering for same-time notifications.
     * Keeps latest first, and ensures refund result is displayed above refund request.
     */
    private function sortNotificationRows(array $rows): array
    {
        usort($rows, static function (array $a, array $b): int {
            $timeA = strtotime((string) ($a['created_at'] ?? '')) ?: 0;
            $timeB = strtotime((string) ($b['created_at'] ?? '')) ?: 0;

            if ($timeA !== $timeB) {
                return $timeB <=> $timeA;
            }

            $relatedA = (int) ($a['related_id'] ?? 0);
            $relatedB = (int) ($b['related_id'] ?? 0);
            $typeA = (string) ($a['type'] ?? '');
            $typeB = (string) ($b['type'] ?? '');

            if ($relatedA > 0 && $relatedA === $relatedB && $typeA !== $typeB) {
                if ($typeA === 'refund_result' && $typeB === 'refund_request') {
                    return -1;
                }
                if ($typeA === 'refund_request' && $typeB === 'refund_result') {
                    return 1;
                }
            }

            return ((int) ($b['id'] ?? 0)) <=> ((int) ($a['id'] ?? 0));
        });

        return $rows;
    }

    private function resolveThumbnailUrl(string $previewPath): string
    {
        $raw = trim($previewPath);
        if ($raw === '') {
            return '';
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded) && !empty($decoded)) {
            $last = end($decoded);
            $raw = is_string($last) ? $last : '';
        }

        $raw = str_replace('\\/', '/', trim($raw));
        if ($raw === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $raw)) {
            return $raw;
        }

        return base_url(ltrim($raw, '/'));
    }

    private function formatTimeAgo(string $datetime): string
    {
        try {
            $displayTz = $this->getDisplayTimezone();
            $time = (new \DateTimeImmutable($datetime, $displayTz))->getTimestamp();
            $now = (new \DateTimeImmutable('now', $displayTz))->getTimestamp();
        } catch (\Throwable $e) {
            $time = strtotime($datetime);
            $now = time();
        }

        if (!$time) {
            return 'Just now';
        }

        $delta = max(0, $now - $time);
        if ($delta < 60) {
            return 'Just now';
        }

        $minutes = (int) floor($delta / 60);
        if ($minutes < 60) {
            return $minutes . ' minute' . ($minutes === 1 ? '' : 's') . ' ago';
        }

        $hours = (int) floor($minutes / 60);
        if ($hours < 24) {
            return $hours . ' hour' . ($hours === 1 ? '' : 's') . ' ago';
        }

        $days = (int) floor($hours / 24);
        return $days . ' day' . ($days === 1 ? '' : 's') . ' ago';
    }

    /**
     * Converts UTC datetime (stored in DB) to local display time.
     */
    private function convertUtcToDisplayTime(string $datetime): string
    {
        $raw = trim($datetime);
        if ($raw === '') {
            return date('Y-m-d H:i:s');
        }

        try {
            return (new \DateTimeImmutable($raw, new \DateTimeZone('UTC')))
                ->setTimezone($this->getDisplayTimezone())
                ->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return $raw;
        }
    }

    /**
     * Resolve local display timezone. If app timezone is still UTC,
     * fallback to Asia/Manila for user-facing notification timestamps.
     */
    private function getDisplayTimezone(): \DateTimeZone
    {
        $appConfig = config('App');
        $tzName = (string) ($appConfig->appTimezone ?? 'UTC');
        if ($tzName === '' || strtoupper($tzName) === 'UTC') {
            $tzName = 'Asia/Manila';
        }

        try {
            return new \DateTimeZone($tzName);
        } catch (\Throwable $e) {
            return new \DateTimeZone('Asia/Manila');
        }
    }

    /**
     * Notify the seller to process the reverse payment for an approved refund.
     * POST notifications/notify_seller/(:num)
     */
    public function notify_seller_refund(int $refundId = 0): \CodeIgniter\HTTP\ResponseInterface
    {
        $buyerId = (int) (session()->get('userId') ?? 0);
        if ($buyerId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized.'])->setStatusCode(401);
        }

        if ($refundId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid refund ID.'])->setStatusCode(400);
        }

        try {
            $db = \Config\Database::connect();

            $row = $db->table('refund_requests rr')
                ->select('rr.id, rr.order_id, rr.buyer_id, rr.refund_decision, rr.seller_reverse_notified_at, oi.product_title, oi.seller_id, o.order_number')
                ->join('order_items oi', 'oi.order_item_id = rr.order_item_id', 'left')
                ->join('orders o', 'o.order_id = rr.order_id', 'left')
                ->where('rr.id', $refundId)
                ->where('rr.buyer_id', $buyerId)
                ->limit(1)
                ->get()
                ->getRowArray();

            if (empty($row)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Refund request not found.'])->setStatusCode(404);
            }

            $decision = strtoupper(trim((string) ($row['refund_decision'] ?? '')));
            if ($decision !== 'APPROVED') {
                return $this->response->setJSON(['success' => false, 'message' => 'Refund is not approved.'])->setStatusCode(422);
            }

            if (!empty($row['seller_reverse_notified_at'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Seller has already been notified.'])->setStatusCode(409);
            }

            $sellerId  = (int) ($row['seller_id'] ?? 0);
            $orderId   = (int) ($row['order_id'] ?? 0);
            $title     = trim((string) ($row['product_title'] ?? 'Product'));
            $orderNum  = trim((string) ($row['order_number'] ?? (string) $orderId));

            if ($sellerId <= 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'Seller not found for this order.'])->setStatusCode(422);
            }

            // Insert notification for seller.
            $now     = date('Y-m-d H:i:s');
            $message = 'Please process the reverse payment for the approved refund on "'
                . ($title !== '' ? $title : 'Product')
                . '" (Order #' . $orderNum . ').';

            $insert = [
                'user_id'    => $sellerId,
                'message'    => $message,
                'type'       => 'refund_reverse_payment',
                'read'       => 0,
                'related_id' => $orderId > 0 ? $orderId : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Filter to only columns that exist in the notifications table.
            $columns = array_column($db->getFieldData('notifications'), 'name');
            $insert  = array_intersect_key($insert, array_flip($columns));

            if (!empty($insert)) {
                $db->table('notifications')->insert($insert);
            }

            // Mark seller as notified to prevent duplicates.
            $db->table('refund_requests')
                ->where('id', $refundId)
                ->update(['seller_reverse_notified_at' => $now, 'updated_at' => $now]);

            return $this->response->setJSON(['success' => true, 'message' => 'Seller has been notified to process the reverse payment.']);
        } catch (\Throwable $e) {
            log_message('error', 'notify_seller_refund error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred. Please try again.'])->setStatusCode(500);
        }
    }
}
