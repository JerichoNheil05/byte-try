<?php

namespace App\Controllers;

class Subscription extends BaseController
{
    /**
     * Display the subscription management page
     * Backend-ready: Database integration can be added later
     */
    public function index()
    {
        $authModel = new \App\Models\AuthModel();
        $userId = (int) (session()->get('userId') ?? 0);
        $user = $userId > 0 ? $authModel->getUserById($userId) : null;

        $accountType = $user['account_type'] ?? (session()->get('account_type') ?? (session()->get('role') ?? 'buyer'));
        $subscriptionStatus = $user['subscription_status'] ?? (session()->get('subscription_status') ?? (($accountType === 'seller') ? 'active' : 'inactive'));
        $membershipLabel = $user['membership_label'] ?? (session()->get('membership_label') ?? (($subscriptionStatus === 'active') ? 'Active' : 'Inactive'));
        $subscriptionEndDate = $user['subscription_end_date'] ?? (session()->get('subscription_end_date') ?? null);

        session()->set([
            'account_type' => $accountType,
            'subscription_status' => $subscriptionStatus,
            'membership_label' => $membershipLabel,
            'subscription_end_date' => $subscriptionEndDate,
            'can_access_seller_dashboard' => $accountType === 'seller' && $subscriptionStatus === 'active',
        ]);

        $data = [
            'currentEmail' => $user['email'] ?? (session()->get('email') ?? 'user@example.com'),
            'accountType' => $accountType,
            'subscriptionStatus' => $subscriptionStatus,
            'subscriptionEndDate' => $subscriptionEndDate,
            'membershipLabel' => $membershipLabel,
            'paymentMethod' => session()->get('payment_method') ?? 'gcash',
            'cashoutMethod' => session()->get('cashout_method') ?? 'gcash',
        ];

        return view('subscription', $data);
    }

    /**
     * Update user email address
     * Backend-ready: Database integration can be added later
     */
    public function update_email()
    {
        // Validate form input
        if (!$this->validate([
            'new_email' => 'required|valid_email',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $newEmail = $this->request->getPost('new_email');
        
        // Store in session (replace with database call when backend is ready)
        session()->set('email', $newEmail);
        
        return redirect()->to('subscription')->with('success', 'Email address updated successfully.');
    }

    /**
     * Update user password
     * Backend-ready: Database integration can be added later
     */
    public function update_password()
    {
        // Validate form inputs
        if (!$this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // For now, just accept the password (replace with database verification when backend is ready)
        session()->set('password_updated', true);
        
        return redirect()->to('subscription')->with('success', 'Password updated successfully.');
    }

    /**
     * Update payment method preference
     * Backend-ready: Database integration can be added later
     */
    public function update_payment_method()
    {
        // Validate payment method selection
        if (!$this->validate([
            'payment_method' => 'required|in_list[gcash,maya]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $paymentMethod = $this->request->getPost('payment_method');
        
        // Store in session (replace with database call when backend is ready)
        session()->set('payment_method', $paymentMethod);
        
        return redirect()->to('subscription')->with('success', 'Payment method updated successfully.');
    }

    /**
     * Update cashout method preference
     * Backend-ready: Database integration can be added later
     */
    public function update_cashout_method()
    {
        // Validate cashout method selection
        if (!$this->validate([
            'cashout_method' => 'required|in_list[gcash,maya]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $cashoutMethod = $this->request->getPost('cashout_method');
        
        // Store in session (replace with database call when backend is ready)
        session()->set('cashout_method', $cashoutMethod);
        
        return redirect()->to('subscription')->with('success', 'Cashout method updated successfully.');
    }

    /**
     * Activate seller subscription
     * Backend-ready: Database integration can be added later
     */
    public function activate()
    {
        $userId = session()->get('userId');
        
        if (!$userId) {
            return redirect()->to('auth/login')->with('error', 'Please log in to activate seller membership.');
        }

        try {
            if (!$this->finalizeSellerUpgrade((int) $userId)) {
                return redirect()->to('subscription')->with('error', 'Account upgrade failed. Please contact support.');
            }

            return redirect()->to('subscription')->with('success', 'Congratulations! Your seller membership is now active. You can now upload and sell digital products.');
        } catch (\Exception $e) {
            log_message('error', 'Activate membership error: ' . $e->getMessage());
            return redirect()->to('subscription')->with('error', 'An error occurred while activating your membership. Please try again.');
        }
    }

    /**
     * Cancel seller subscription
     * Backend-ready: Database integration can be added later
     * Supports both regular form submission and AJAX requests
     */
    public function cancel()
    {
        try {
            $userId = (int) (session()->get('userId') ?? 0);
            if ($userId <= 0) {
                return $this->request->isAJAX()
                    ? $this->response->setJSON([
                        'success' => false,
                        'message' => 'Please log in first.'
                    ])->setStatusCode(401)
                    : redirect()->to('auth/login')->with('error', 'Please log in first.');
            }

            $authModel = new \App\Models\AuthModel();
            $deactivated = $authModel->deactivateSellerMembership($userId);

            if (!$deactivated) {
                throw new \RuntimeException('Unable to deactivate seller membership.');
            }

            session()->set([
                'role' => 'buyer',
                'account_type' => 'buyer',
                'subscription_status' => 'inactive',
                'membership_label' => 'Inactive',
                'subscription_end_date' => null,
                'can_access_seller_dashboard' => false,
            ]);
            
            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                // Return JSON response for AJAX
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Your seller membership has been cancelled. You can reactivate it anytime.'
                ]);
            } else {
                // Regular form submission - redirect
                return redirect()->to('subscription')->with('success', 'Your seller membership has been cancelled. You can reactivate it anytime.');
            }
        } catch (\Exception $e) {
            // Error handling
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred while cancelling membership. Please try again.'
                ])->setStatusCode(500);
            } else {
                return redirect()->back()->with('error', 'An error occurred while cancelling membership. Please try again.');
            }
        }
    }

    /**
     * Save seller's agreement to Terms and Conditions
     * Backend-ready: Database integration can be added later
     */
    public function terms_agree()
    {
        try {
            // Check if this is an AJAX request
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method.'
                ])->setStatusCode(400);
            }

            // Get JSON data from request
            $json = $this->request->getJSON();
            
            // Validate required fields
            if (!isset($json->agreed) || $json->agreed !== true) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Agreement not confirmed.'
                ])->setStatusCode(400);
            }

            // Store agreement in session (replace with database call when backend is ready)
            // TODO: Update database: UPDATE subscriptions SET terms_agreed = 1, terms_agreed_at = NOW() WHERE user_id = ?
            session()->set('terms_agreed', true);
            session()->set('terms_agreed_at', date('Y-m-d H:i:s'));
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Terms and Conditions agreement saved successfully.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Terms agree error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while saving your agreement. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Handle seller's decline of Terms and Conditions
     * Backend-ready: Database integration can be added later
     */
    public function terms_decline()
    {
        try {
            // Check if this is an AJAX request
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method.'
                ])->setStatusCode(400);
            }

            // Get JSON data from request
            $json = $this->request->getJSON();
            
            // Store decline in session (replace with database logging when backend is ready)
            // TODO: Log decline event for audit purposes
            session()->set('terms_agreed', false);
            session()->set('terms_declined_at', date('Y-m-d H:i:s'));
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Terms declined.',
                'redirect' => base_url('dashboard')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Terms decline error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred. Please try again.'
            ])->setStatusCode(500);
        }
    }

    private function finalizeSellerUpgrade(int $userId): bool
    {
        try {
            $authModel = new \App\Models\AuthModel();
            $upgraded = $authModel->activateSellerMembership($userId);

            if (!$upgraded) {
                log_message('error', 'Seller upgrade failed for user: ' . $userId);
                return false;
            }

            $updatedUser = $authModel->getUserById($userId);
            $sessionEndDate = $updatedUser['subscription_end_date'] ?? date('Y-m-d', strtotime('+1 month'));

            session()->set([
                'role' => 'seller',
                'account_type' => 'seller',
                'subscription_status' => 'active',
                'membership_label' => 'Active',
                'subscription_end_date' => $sessionEndDate,
                'can_access_seller_dashboard' => true,
            ]);

            $this->sendMembershipActivationEmail($updatedUser ?: [
                'email' => session()->get('email'),
                'full_name' => session()->get('fullName'),
            ]);

            log_message('info', 'Seller membership finalized for user ' . $userId);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Finalize seller upgrade error: ' . $e->getMessage());
            return false;
        }
    }

    private function sendMembershipActivationEmail(array $user): void
    {
        try {
            $emailTo = trim((string) ($user['email'] ?? ''));
            if ($emailTo === '') {
                return;
            }

            $email = \Config\Services::email();
                        $email->setFrom('bytemarket730@gmail.com', 'Byte Market');
            $email->setTo($emailTo);
                        $email->setSubject('ByteMarket Seller Membership Receipt');

                        $fullName = esc(trim((string) ($user['full_name'] ?? 'Seller')));
                        $membershipEndDate = esc((string) ($user['subscription_end_date'] ?? date('Y-m-d', strtotime('+1 month'))));
                        $paymentMethod = esc((string) (session()->get('payment_method') ?? 'gcash'));
                        $receiptNumber = 'BMS-' . strtoupper(substr(sha1((string) ($emailTo . microtime(true))), 0, 10));
                        $paidAt = date('Y-m-d H:i:s');
                        $siteUrl = base_url();
                        $year = date('Y');

                        $email->setMessage($this->buildMembershipReceiptEmailTemplate(
                                $fullName,
                                $receiptNumber,
                                $paymentMethod,
                                $membershipEndDate,
                                $paidAt,
                                $siteUrl,
                                $year
                        ));
            $email->send(false);
        } catch (\Exception $e) {
            log_message('warning', 'Membership activation email failed: ' . $e->getMessage());
        }
    }

        private function buildMembershipReceiptEmailTemplate(
                string $fullName,
                string $receiptNumber,
                string $paymentMethod,
                string $membershipEndDate,
                string $paidAt,
                string $siteUrl,
                string $year
        ): string {
                return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Seller Membership Receipt</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:36px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    <tr>
                        <td style="background:#1c2b3a;padding:28px 36px;text-align:center;">
                            <span style="color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;">ByteMarket</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:4px;background:linear-gradient(90deg,#2f80d0,#22a43a);"></td>
                    </tr>

                    <tr>
                        <td style="padding:34px 40px 24px;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:#2f80d0;">Payment Receipt</p>
                            <h1 style="margin:0 0 14px;font-size:24px;line-height:1.25;color:#1c2b3a;">Seller Membership Activated</h1>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#444;">Hi {$fullName}, your membership payment was successful and your seller account is now active.</p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:8px;padding:18px 20px;margin-bottom:20px;">
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Receipt No.</strong>{$receiptNumber}</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Plan</strong>Seller Membership (Monthly)</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Amount Paid</strong>PHP 99.00</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Payment Method</strong>{$paymentMethod}</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Paid At</strong>{$paidAt}</td></tr>
                                <tr><td style="padding:5px 0;font-size:14px;color:#555;"><strong style="color:#222;width:150px;display:inline-block;">Valid Until</strong>{$membershipEndDate}</td></tr>
                            </table>

                            <div style="background:#f0f7ff;border-left:4px solid #2f80d0;border-radius:0 8px 8px 0;padding:16px 18px;margin-bottom:24px;color:#333;font-size:14px;line-height:1.7;">
                                You can now upload products, manage your storefront, and track sales from your seller dashboard.
                            </div>

                            <div style="text-align:center;">
                                <a href="{$siteUrl}dashboard" style="display:inline-block;background:#2f80d0;color:#fff;text-decoration:none;padding:13px 34px;border-radius:8px;font-size:14px;font-weight:600;letter-spacing:0.4px;">Open Seller Dashboard</a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#1c2b3a;padding:22px 36px;text-align:center;">
                            <p style="margin:0 0 6px;color:#8fa3b1;font-size:12px;">&copy; {$year} ByteMarket. All rights reserved.</p>
                            <p style="margin:0;font-size:12px;">
                                <a href="{$siteUrl}" style="color:#4a90d9;text-decoration:none;">Visit our website</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{$siteUrl}privacy" style="color:#4a90d9;text-decoration:none;">Privacy Policy</a>
                                &nbsp;&bull;&nbsp;
                                <a href="{$siteUrl}terms" style="color:#4a90d9;text-decoration:none;">Terms of Service</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
HTML;
        }

}
