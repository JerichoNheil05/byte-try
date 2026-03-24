<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\NotificationModel;

/**
 * Auth Controller - Handles user authentication
 * 
 * Routes:
 * - /auth/signup (POST) - User registration
 * - /auth/login (POST) - User login
 * - /auth/logout (POST) - User logout
 * - /auth/profile (GET/POST) - View/update profile
 * - /auth/change-password (POST) - Change password
 */
class Auth extends BaseController
{
    protected $authModel;
    protected $session;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    /**
     * Test endpoint - returns JSON
     */
    public function test()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'API is working',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Display signup form
     */
    public function signupForm()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/signup', [
            'title' => 'Sign Up - ByteMarket',
            'passwordRequirements' => AuthModel::getPasswordRequirements()
        ]);
    }

    /**
     * Handle user registration
     */
    public function signup()
    {
        // Immediate error logging for debugging
        log_message('debug', '=== SIGNUP REQUEST START ===');
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

        // Get POST data
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role') ?? 'buyer'
        ];

        // Validate password confirmation
        $passwordConfirm = $this->request->getPost('password_confirm');
        if ($data['password'] !== $passwordConfirm) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Passwords do not match.',
                'field' => 'password_confirm'
            ])->setStatusCode(400);
        }

        // Validate data
        if (!$this->authModel->validate($data)) {
            $errors = $this->authModel->errors();
            log_message('warning', 'Validation failed: ' . json_encode($errors));
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_values($errors)[0] ?? []),
                'errors' => $errors
            ])->setStatusCode(400);
        }

        // Register user
        try {
            $userId = $this->authModel->registerUser($data);

            if (!$userId) {
                log_message('error', 'User registration returned null for email: ' . $data['email']);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Registration failed. Please check your password meets all requirements and the email is valid.'
                ])->setStatusCode(500);
            }

            // Auto-login after registration
            $user = $this->authModel->getUserById($userId);
            if (!$user) {
                log_message('error', 'Could not retrieve user after registration, userId: ' . $userId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Registration successful but could not log you in. Please log in manually.'
                ])->setStatusCode(500);
            }

            $this->createUserSession($user);

            // Send welcome email after successful registration (non-blocking for signup result)
            $this->sendWelcomeEmail($user);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration successful! Welcome to ByteMarket.',
                'redirect' => base_url('/dashboard')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Registration exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
            ])->setStatusCode(500);
        }
    }

        /**
         * Send a ByteMarket welcome email to a newly registered user.
         * This should not interrupt signup if email delivery fails.
         */
        private function sendWelcomeEmail(array $user): void
        {
                try {
                        $recipientEmail = (string) ($user['email'] ?? '');
                        if ($recipientEmail === '') {
                                return;
                        }

                        $recipientName = esc((string) ($user['full_name'] ?? 'ByteMarket User'));
                        $siteUrl       = base_url();
                        $year          = date('Y');

                        $emailService = \Config\Services::email();
                        $emailService->setFrom('bytemarket730@gmail.com', 'Byte Market');
                        $emailService->setTo($recipientEmail);
                        $emailService->setSubject('Welcome to ByteMarket');
                        $emailService->setMessage($this->buildWelcomeEmailTemplate($recipientName, $siteUrl, $year));

                        if (! $emailService->send()) {
                                log_message('error', 'Welcome email failed for ' . $recipientEmail . ': ' . $emailService->printDebugger(['headers']));
                        }
                } catch (\Throwable $e) {
                        log_message('error', 'Welcome email exception: ' . $e->getMessage());
                }
        }

        /**
         * Build the HTML email used for welcome messages.
         */
        private function buildWelcomeEmailTemplate(string $recipientName, string $siteUrl, string $year): string
        {
                return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Welcome to ByteMarket</title>
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
                        <td style="padding:36px 40px 24px;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:#2f80d0;">Welcome</p>
                            <h1 style="margin:0 0 14px;font-size:26px;line-height:1.25;color:#1c2b3a;">Hello {$recipientName},</h1>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#444;">
                                Your ByteMarket account is now active. We're excited to have you on board.
                            </p>

                            <div style="background:#f0f7ff;border-left:4px solid #2f80d0;border-radius:0 8px 8px 0;padding:16px 18px;margin-bottom:24px;color:#333;font-size:14px;line-height:1.7;">
                                Start exploring products, manage your profile, and enjoy a secure marketplace experience tailored for digital trading.
                            </div>

                            <div style="text-align:center;">
                                <a href="{$siteUrl}dashboard" style="display:inline-block;background:#2f80d0;color:#fff;text-decoration:none;padding:13px 34px;border-radius:8px;font-size:14px;font-weight:600;letter-spacing:0.4px;">Go to Dashboard</a>
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

    /**
     * Display login form
     */
    public function loginForm()
    {
        // If already logged in, redirect based on role
        if ($this->session->get('isLoggedIn')) {
            $role = (string) ($this->session->get('role') ?? 'buyer');
            return redirect()->to($this->getRedirectUrl($role));
        }

        return view('auth/login', [
            'title' => 'Login - ByteMarket'
        ]);
    }

    /**
     * Handle user login
     */
    public function login()
    {
        // Get credentials
        $email    = trim((string) ($this->request->getPost('email') ?? ''));
        $password = $this->request->getPost('password');
        $rememberMe = $this->request->getPost('remember_me') ? true : false;

        // Validate input
        if (empty($email) || empty($password)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email and password are required.'
            ])->setStatusCode(400);
        }

        // --- Account lockout: block after 5 failed attempts for 15 minutes ---
        $lockKey  = 'login_fail_' . md5(strtolower($email));
        $cache    = \Config\Services::cache();
        $attempts = (int) ($cache->get($lockKey) ?? 0);
        if ($attempts >= 5) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Too many failed login attempts. Please try again in 15 minutes.'
            ])->setStatusCode(429);
        }

        // Authenticate user
        $user = $this->authModel->authenticateUser($email, $password);

        if ($user) {
            // Reset lockout counter on success
            $cache->delete($lockKey);

            // Create session
            $this->createUserSession($user, $rememberMe);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Login successful! Redirecting...',
                'redirect' => $this->getRedirectUrl($user['role'])
            ]);
        }

        // Increment failure counter
        $cache->save($lockKey, $attempts + 1, 900); // 15-minute window

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid email or password. Please try again.'
        ])->setStatusCode(401);
    }

    /**
     * Handle user logout
     */
    public function logout()
    {
        try {
            $sessionConfig = config('Session');
            $cookieConfig = config('Cookie');
            $sessionCookieNames = array_unique(array_filter([
                $sessionConfig->cookieName ?? null,
                session_name(),
                'ci_session',
                'PHPSESSID',
            ]));

            // Destroy session
            $this->session->remove([
                'userId',
                'fullName',
                'email',
                'role',
                'account_type',
                'subscription_status',
                'membership_label',
                'subscription_end_date',
                'can_access_seller_dashboard',
                'profileImage',
                'isLoggedIn',
            ]);
            $this->session->destroy();

            foreach ($sessionCookieNames as $sessionCookieName) {
                if ($sessionCookieName !== '' && isset($_COOKIE[$sessionCookieName])) {
                    setcookie($sessionCookieName, '', [
                        'expires' => time() - 3600,
                        'path' => $cookieConfig->path ?: '/',
                        'domain' => $cookieConfig->domain ?: '',
                        'secure' => (bool) $cookieConfig->secure,
                        'httponly' => (bool) $cookieConfig->httponly,
                        'samesite' => $cookieConfig->samesite ?: 'Lax',
                    ]);
                    unset($_COOKIE[$sessionCookieName]);
                }
            }

            // Clear remember me cookie
            if (isset($_COOKIE['remember_token'])) {
                setcookie('remember_token', '', [
                    'expires' => time() - 3600,
                    'path' => $cookieConfig->path ?: '/',
                    'domain' => $cookieConfig->domain ?: '',
                    'secure' => (bool) $cookieConfig->secure,
                    'httponly' => (bool) $cookieConfig->httponly,
                    'samesite' => $cookieConfig->samesite ?: 'Lax',
                ]);
                unset($_COOKIE['remember_token']);
            }

            return redirect()->to('/')->with('success', 'You have been logged out successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Logout error: ' . $e->getMessage());
            return redirect()->to('/');
        }
    }

    /**
     * Display user profile
     */
    public function profile()
    {
        // Check if logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = $this->session->get('userId');
        $user = $this->authModel->getUserById($userId);

        if (!$user) {
            return redirect()->to('/auth/login');
        }

        return view('auth/profile', [
            'title' => 'My Profile - ByteMarket',
            'user' => $user
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile()
    {
        // Check if logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(401);
        }

        $userId = (int) ($this->session->get('userId') ?? 0);
        if ($userId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid user session.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(401);
        }

        $data = [];
        $profileFields = ['full_name', 'bio', 'headline', 'country', 'city', 'phone'];
        foreach ($profileFields as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null) {
                $value = trim((string) $value);
                if ($value !== '') {
                    $data[$field] = $value;
                }
            }
        }

        if (array_key_exists('phone', $data) && !preg_match('/^\d{11}$/', $data['phone'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Phone number must contain exactly 11 digits.',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(400);
        }

        // Handle profile image upload
        $file = $this->request->getFile('profile_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/profiles/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $data['profile_image'] = 'uploads/profiles/' . $newName;
        }

        if (empty($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'No changes detected.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        if ($this->authModel->updateProfile($userId, $data)) {
            $notificationModel = new NotificationModel();
            $notificationModel->createProfileUpdateNotification($userId);

            // Update session data
            $user = $this->authModel->getUserById($userId);
            $this->session->set([
                'fullName' => $user['full_name'] ?? $this->session->get('fullName'),
                'profileImage' => $user['profile_image'] ?? $this->session->get('profileImage')
            ]);

            $profileImageRaw = trim((string) ($user['profile_image'] ?? ''));
            $profileImageUrl = '';
            if ($profileImageRaw !== '') {
                if (preg_match('/^https?:\/\//i', $profileImageRaw)) {
                    $profileImageUrl = $profileImageRaw;
                } elseif (strpos($profileImageRaw, 'uploads/') === 0) {
                    $profileImageUrl = base_url(ltrim($profileImageRaw, '/'));
                } else {
                    $profileImageUrl = base_url('uploads/profiles/' . ltrim($profileImageRaw, '/'));
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => $user,
                'profileImageUrl' => $profileImageUrl,
                'csrfHash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update profile',
            'csrfHash' => csrf_hash(),
        ])->setStatusCode(500);
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        // Check if logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(401);
        }

        $userId = $this->session->get('userId');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validate passwords match
        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'New passwords do not match',
                'csrfHash' => csrf_hash(),
            ])->setStatusCode(400);
        }

        // Change password
        if ($this->authModel->changePassword($userId, $currentPassword, $newPassword)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password changed successfully!',
                'csrfHash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Current password is incorrect or new password does not meet requirements',
            'csrfHash' => csrf_hash(),
        ])->setStatusCode(400);
    }

    /**
     * Check if email is available
     */
    public function checkEmail()
    {
        $email = $this->request->getGet('email');

        if (empty($email)) {
            return $this->response->setJSON([
                'available' => false,
                'message' => 'Email is required'
            ]);
        }

        $exists = $this->authModel->emailExists($email);

        return $this->response->setJSON([
            'available' => !$exists,
            'message' => $exists ? 'Email already registered' : 'Email available'
        ]);
    }

    /**
     * Create user session
     */
    private function createUserSession(array $user, bool $rememberMe = false)
    {
        // Regenerate the session ID at privilege change to prevent fixation.
        $this->session->regenerate(true);

        $resolvedAccountType = $user['account_type'] ?? ($user['role'] ?? 'buyer');
        $resolvedSubscriptionStatus = $user['subscription_status'] ?? (($resolvedAccountType === 'seller') ? 'active' : 'inactive');
        $resolvedMembershipLabel = $user['membership_label'] ?? (($resolvedSubscriptionStatus === 'active') ? 'Active' : 'Inactive');

        $sessionData = [
            'userId' => $user['id'],
            'fullName' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'account_type' => $resolvedAccountType,
            'subscription_status' => $resolvedSubscriptionStatus,
            'membership_label' => $resolvedMembershipLabel,
            'subscription_end_date' => $user['subscription_end_date'] ?? null,
            'can_access_seller_dashboard' => $resolvedAccountType === 'seller' && $resolvedSubscriptionStatus === 'active',
            'profileImage' => $user['profile_image'] ?? null,
            'isLoggedIn' => true
        ];

        $this->session->set($sessionData);

        // Set remember me cookie (30 days)
        if ($rememberMe) {
            // Remember-me requires server-side token storage (not yet implemented).
            // Cookie creation is intentionally skipped to avoid false security.
        }
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl(string $role): string
    {
        switch ($role) {
            case 'admin':
                return base_url('/dashboard');
            case 'seller':
                return base_url('/dashboard');
            case 'buyer':
            default:
                return base_url('/home');
        }
    }
}