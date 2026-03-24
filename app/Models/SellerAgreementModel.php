<?php

namespace App\Models;

/**
 * SellerAgreementModel - Manages seller terms and agreements
 * 
 * Handles:
 * - Accept seller terms
 * - Decline seller terms
 * - Track agreement versions
 * - Retrieve agreement history
 * - Audit seller compliance
 */
class SellerAgreementModel extends BaseModel
{
    protected $table = 'seller_agreements';
    protected $primaryKey = 'seller_agreement_id';

    protected $allowedFields = [
        'seller_id',
        'agreement_version',
        'terms_accepted',
        'terms_accepted_at',
        'terms_accepted_ip',
        'terms_accepted_user_agent',
        'status',
        'agreement_data',
        'decline_reason',
        'created_at',
        'updated_at'
    ];

    // Agreement version for new sellers
    const CURRENT_VERSION = '1.0';

    // Valid statuses
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_REVOKED = 'revoked';

    /**
     * Check if seller has accepted terms
     */
    public function hasAcceptedTerms(int $sellerId): bool
    {
        try {
            $agreement = $this->where('seller_id', $sellerId)
                ->where('status', self::STATUS_ACCEPTED)
                ->limit(1)
                ->findAll();

            return !empty($agreement);

        } catch (\Exception $e) {
            log_message('error', 'Check accepted terms error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Accept seller agreement
     */
    public function acceptTerms(int $sellerId, string $agreementData): bool
    {
        try {
            // Check if agreement exists
            $existing = $this->where('seller_id', $sellerId)->limit(1)->findAll();

            $data = [
                'seller_id' => $sellerId,
                'agreement_version' => self::CURRENT_VERSION,
                'terms_accepted' => true,
                'terms_accepted_at' => date('Y-m-d H:i:s'),
                'terms_accepted_ip' => $this->getClientIp(),
                'terms_accepted_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'status' => self::STATUS_ACCEPTED,
                'agreement_data' => $agreementData,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if (!empty($existing)) {
                // Update existing
                $this->update($existing[0]['seller_agreement_id'], $data);
                $agreementId = $existing[0]['seller_agreement_id'];
            } else {
                // Create new
                $this->insert($data);
                $agreementId = $this->getInsertID();
            }

            // Audit log
            $this->auditLog(
                $sellerId,
                'terms_accepted',
                'seller_agreement',
                $agreementId,
                'Seller accepted terms and conditions v' . self::CURRENT_VERSION
            );

            // Create notification
            $this->createNotification(
                $sellerId,
                'system',
                'Terms Accepted',
                'You have successfully accepted the seller agreement.',
                null
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Accept terms error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Decline seller agreement
     */
    public function declineTerms(int $sellerId, string $declineReason): bool
    {
        try {
            $data = [
                'seller_id' => $sellerId,
                'agreement_version' => self::CURRENT_VERSION,
                'terms_accepted' => false,
                'status' => self::STATUS_DECLINED,
                'decline_reason' => $declineReason,
                'terms_accepted_ip' => $this->getClientIp(),
                'terms_accepted_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Check if agreement exists
            $existing = $this->where('seller_id', $sellerId)->limit(1)->findAll();

            if (!empty($existing)) {
                $this->update($existing[0]['seller_agreement_id'], $data);
                $agreementId = $existing[0]['seller_agreement_id'];
            } else {
                $this->insert($data);
                $agreementId = $this->getInsertID();
            }

            // Audit log
            $this->auditLog(
                $sellerId,
                'terms_declined',
                'seller_agreement',
                $agreementId,
                'Seller declined terms: ' . $declineReason
            );

            // Create notification
            $this->createNotification(
                $sellerId,
                'system',
                'Terms Declined',
                'You have declined the seller agreement. Your account access may be limited.',
                null
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Decline terms error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get seller's current agreement
     */
    public function getSellerAgreement(int $sellerId): ?array
    {
        try {
            return $this->where('seller_id', $sellerId)
                ->orderBy('created_at', 'DESC')
                ->limit(1)
                ->findAll()[0] ?? null;

        } catch (\Exception $e) {
            log_message('error', 'Get seller agreement error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get seller's agreement status
     */
    public function getAgreementStatus(int $sellerId): ?string
    {
        try {
            $agreement = $this->where('seller_id', $sellerId)
                ->select('status')
                ->orderBy('created_at', 'DESC')
                ->limit(1)
                ->findAll();

            return $agreement[0]['status'] ?? null;

        } catch (\Exception $e) {
            log_message('error', 'Get agreement status error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get agreement history for seller
     */
    public function getAgreementHistory(int $sellerId): array
    {
        try {
            return $this->where('seller_id', $sellerId)
                ->orderBy('created_at', 'DESC')
                ->findAll();

        } catch (\Exception $e) {
            log_message('error', 'Get agreement history error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Revoke seller agreement (Admin action)
     */
    public function revokeAgreement(int $sellerId, string $reason, int $adminId): bool
    {
        try {
            $agreement = $this->where('seller_id', $sellerId)
                ->limit(1)
                ->findAll();

            if (empty($agreement)) {
                return false;
            }

            $this->update($agreement[0]['seller_agreement_id'], [
                'status' => self::STATUS_REVOKED,
                'updated_at' => date('Y-m-d H:i:s'),
                'decline_reason' => $reason
            ]);

            // Audit log
            $this->auditLog(
                $adminId,
                'admin_action',
                'seller_agreement',
                $agreement[0]['seller_agreement_id'],
                'Agreement revoked: ' . $reason
            );

            // Create notification
            $this->createNotification(
                $sellerId,
                'system',
                'Agreement Revoked',
                'Your seller agreement has been revoked. Reason: ' . $reason,
                null
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Revoke agreement error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if agreement needs renewal
     */
    public function needsRenewal(int $sellerId, int $renewalMonths = 12): bool
    {
        try {
            $agreement = $this->getSellerAgreement($sellerId);

            if (!$agreement || $agreement['status'] !== self::STATUS_ACCEPTED) {
                return true;
            }

            // Check if older than renewal period
            $acceptedDate = strtotime($agreement['terms_accepted_at']);
            $renewalDate = strtotime("+{$renewalMonths} months", $acceptedDate);

            return $renewalDate < time();

        } catch (\Exception $e) {
            log_message('error', 'Check renewal error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get pending agreements (awaiting acceptance)
     */
    public function getPendingAgreements(int $limit = 20, int $offset = 0): array
    {
        try {
            $total = $this->where('status', self::STATUS_PENDING)
                ->countAllResults(false);

            $pending = $this->where('status', self::STATUS_PENDING)
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->offset($offset)
                ->findAll();

            return [
                'agreements' => $pending,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get pending agreements error: ' . $e->getMessage());
            return ['agreements' => [], 'total' => 0, 'limit' => $limit, 'offset' => $offset];
        }
    }

    /**
     * Get statistics on sellers by agreement status
     */
    public function getAgreementStatistics(): array
    {
        try {
            return [
                'accepted' => $this->where('status', self::STATUS_ACCEPTED)->countAllResults(),
                'pending' => $this->where('status', self::STATUS_PENDING)->countAllResults(),
                'declined' => $this->where('status', self::STATUS_DECLINED)->countAllResults(),
                'revoked' => $this->where('status', self::STATUS_REVOKED)->countAllResults(),
            ];

        } catch (\Exception $e) {
            log_message('error', 'Get agreement statistics error: ' . $e->getMessage());
            return [
                'accepted' => 0,
                'pending' => 0,
                'declined' => 0,
                'revoked' => 0
            ];
        }
    }

    /**
     * Get template agreement text
     */
    public static function getAgreementTemplate(): string
    {
        return <<<'EOT'
SELLER AGREEMENT - ByteMarket Platform

Version: 1.0
Last Updated: 2026-02-19

This Seller Agreement ("Agreement") is entered into between the Seller ("You") and ByteMarket Platform ("Platform").

1. ACCEPTANCE OF TERMS
By accepting this agreement, you agree to comply with all terms and conditions outlined herein. Your acceptance
indicates that you have read, understood, and agree to be bound by this Agreement.

2. SELLER OBLIGATIONS
2.1 You agree to:
    - Provide accurate product information and descriptions
    - Maintain accurate pricing and inventory management
    - Fulfill orders within the specified timeframe
    - Respond to customer inquiries professionally
    - Comply with all applicable laws and regulations

2.2 Product Standards:
    - All products must be genuine and accurately described
    - Product images must accurately represent the items
    - Product prices must not be deceptively inflated
    - Product availability must match inventory records

3. COMMISSION AND FEES
3.1 Platform Commission: 5% of each confirmed order value
3.2 Platform Fee Structure:
    - Transaction processing fees as stated in current fee schedule
    - Optional premium listing fees (if applicable)
3.3 Fees are deducted automatically from seller wallet balance

4. PAYMENT TERMS
4.1 Seller earnings are calculated after commission deduction
4.2 Withdrawal requests are processed within 5-7 business days
4.3 Minimum withdrawal amount: ₱500
4.4 Funds are transferred to verified payment methods only

5. INTELLECTUAL PROPERTY
5.1 You retain ownership of your product descriptions and images
5.2 You grant Platform a license to display your products
5.3 You warrant that all content is original or properly licensed

6. PROHIBITED ACTIVITIES
6.1 You agree NOT to:
    - Engage in fraudulent activity or misrepresentation
    - Sell counterfeit or illegal products
    - Violate customer privacy or security
    - Engage in unfair competition or sabotage
    - Bypass Platform payment processing

7. DISPUTE RESOLUTION
7.1 Customer disputes are handled through Platform's support system
7.2 Platform reserves the right to investigate claims
7.3 Both parties agree to cooperate in dispute resolution process

8. ACCOUNT SUSPENSION AND TERMINATION
8.1 Platform may suspend or terminate accounts for:
    - Violation of this Agreement
    - Fraudulent activity
    - Consistent policy violations
    - Non-compliance with local regulations
8.2 Suspension may result in forfeiture of pending commissions

9. LIMITATION OF LIABILITY
9.1 Platform acts as an intermediary between buyers and sellers
9.2 Platform is not liable for product defects or issues
9.3 Each party is responsible for their own business operations

10. AMENDMENT
10.1 Platform may update this Agreement with 30 days notice
10.1 Continued use constitutes acceptance of amendments

By accepting this Agreement, you acknowledge that you have read, understood, and agree to all terms outlined above.
EOT;
    }
}
