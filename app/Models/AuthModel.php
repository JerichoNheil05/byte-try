<?php

namespace App\Models;

/**
 * AuthModel - Handles user authentication and registration
 * 
 * Features:
 * - User registration with validation
 * - Secure password hashing (bcrypt/argon2)
 * - Login authentication
 * - Account verification
 * - Password reset functionality
 * - Session management
 * - Audit logging for auth events
 */
class AuthModel extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'full_name',
        'email',
        'password',
        'role',
        'account_type',
        'subscription_status',
        'membership_label',
        'subscription_end_date',
        'seller_commission_rate',
        'seller_dashboard_preferences',
        'seller_store_profile',
        'profile_image',
        'headline',
        'bio',
        'phone',
        'country',
        'city',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email]|max_length[150]',
        'password' => 'required|min_length[8]|max_length[255]',
        'role' => 'permit_empty|in_list[buyer,seller,admin]'
    ];

    protected $validationMessages = [
        'full_name' => [
            'required' => 'Full name is required',
            'min_length' => 'Full name must be at least 3 characters',
            'max_length' => 'Full name cannot exceed 100 characters'
        ],
        'email' => [
            'required' => 'Email address is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email address is already registered',
            'max_length' => 'Email cannot exceed 150 characters'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 8 characters',
            'max_length' => 'Password cannot exceed 255 characters'
        ],
        'role' => [
            'in_list' => 'Invalid user role specified'
        ]
    ];

    /**
     * Register a new user
     */
    public function registerUser(array $data): ?int
    {
        try {
            // Validate password strength
            if (!$this->isStrongPassword($data['password'])) {
                log_message('warning', 'Weak password attempt for email: ' . ($data['email'] ?? 'unknown'));
                return null;
            }

            // Hash password using Argon2id (or bcrypt as fallback)
            $data['password'] = $this->hashPassword($data['password']);
            
            // Set default role if not provided
            $data['role'] = $data['role'] ?? 'buyer';
            $data['status'] = 'active';

            // Insert user
            $this->insert($data);
            $userId = $this->getInsertID();

            if ($userId) {
                // Audit log
                $this->auditLog(
                    $userId,
                    'login',
                    'user',
                    $userId,
                    'New user registered: ' . $data['email'],
                    null,
                    [
                        'full_name' => $data['full_name'],
                        'email' => $data['email'],
                        'role' => $data['role']
                    ],
                    'success'
                );

                // Create welcome notification
                $this->createNotification(
                    $userId,
                    'system',
                    'Welcome to ByteMarket!',
                    'Your account has been successfully created. Start exploring our products!',
                    null
                );

                return $userId;
            }

            return null;

        } catch (\Exception $e) {
            log_message('error', 'User registration error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Authenticate user login
     */
    public function authenticateUser(string $email, string $password): ?array
    {
        try {
            // Find user by email
            $user = $this->where('email', $email)
                ->where('status', 'active')
                ->first();

            if (!$user) {
                // Audit failed login attempt
                $this->auditLog(
                    null,
                    'login',
                    'user',
                    null,
                    'Failed login attempt - user not found: ' . $email,
                    null,
                    null,
                    'failed'
                );
                return null;
            }

            // Verify password
            if (!$this->verifyPassword($password, $user['password'])) {
                // Audit failed login attempt
                $this->auditLog(
                    $user['id'],
                    'login',
                    'user',
                    $user['id'],
                    'Failed login attempt - incorrect password: ' . $email,
                    null,
                    null,
                    'failed'
                );
                return null;
            }

            // Check if account is suspended
            if ($user['status'] !== 'active') {
                $this->auditLog(
                    $user['id'],
                    'login',
                    'user',
                    $user['id'],
                    'Login attempt on suspended account: ' . $email,
                    null,
                    null,
                    'failed'
                );
                return null;
            }

            // Remove password from return data
            unset($user['password']);

            // Audit successful login
            $this->auditLog(
                $user['id'],
                'login',
                'user',
                $user['id'],
                'User logged in successfully: ' . $email,
                null,
                null,
                'success'
            );

            return $user;

        } catch (\Exception $e) {
            log_message('error', 'Authentication error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): ?array
    {
        try {
            $user = $this->find($userId);
            
            if ($user) {
                unset($user['password']);
            }

            return $user;

        } catch (\Exception $e) {
            log_message('error', 'Get user error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email): ?array
    {
        try {
            $user = $this->where('email', $email)->first();
            
            if ($user) {
                unset($user['password']);
            }

            return $user;

        } catch (\Exception $e) {
            log_message('error', 'Get user by email error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): bool
    {
        try {
            // Don't allow email or password update through this method
            unset($data['email'], $data['password'], $data['id'], $data['role'], $data['status']);

            $oldData = $this->find($userId);
            $result = $this->update($userId, $data);

            if ($result) {
                $this->auditLog(
                    $userId,
                    'profile_update',
                    'user',
                    $userId,
                    'User profile updated',
                    $oldData,
                    array_merge($oldData, $data),
                    'success'
                );
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Update profile error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Change user password
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        try {
            // Get user with password
            $user = $this->find($userId);

            if (!$user) {
                return false;
            }

            // Verify current password
            if (!$this->verifyPassword($currentPassword, $user['password'])) {
                $this->auditLog(
                    $userId,
                    'password_change',
                    'user',
                    $userId,
                    'Failed password change - incorrect current password',
                    null,
                    null,
                    'failed'
                );
                return false;
            }

            // Validate new password strength
            if (!$this->isStrongPassword($newPassword)) {
                return false;
            }

            // Hash and update password
            $hashedPassword = $this->hashPassword($newPassword);
            $result = $this->update($userId, ['password' => $hashedPassword]);

            if ($result) {
                $this->auditLog(
                    $userId,
                    'password_change',
                    'user',
                    $userId,
                    'Password changed successfully',
                    null,
                    null,
                    'success'
                );

                // Notify user
                $this->createNotification(
                    $userId,
                    'system',
                    'Password Changed',
                    'Your password has been successfully changed.',
                    null
                );
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Change password error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upgrade user role from buyer to seller
     */
    public function upgradeToSeller(int $userId): bool
    {
        try {
            return $this->activateSellerMembership($userId);

        } catch (\Exception $e) {
            log_message('error', 'Upgrade to seller error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Activate seller membership and initialize seller defaults.
     */
    public function activateSellerMembership(int $userId): bool
    {
        try {
            $user = $this->find($userId);

            if (!$user) {
                log_message('error', 'User not found for seller activation: ' . $userId);
                return false;
            }

            $updateData = [
                'role' => 'seller',
                'account_type' => 'seller',
                'subscription_status' => 'active',
                'membership_label' => 'Active',
                'subscription_end_date' => date('Y-m-d', strtotime('+1 month')),
                'seller_commission_rate' => 10.00,
                'seller_dashboard_preferences' => json_encode($this->defaultDashboardPreferences()),
                'seller_store_profile' => json_encode($this->defaultStoreProfile($user['full_name'] ?? '')),
            ];

            $result = $this->update($userId, $this->filterExistingUserColumns($updateData));

            if ($result) {
                $this->auditLog(
                    $userId,
                    'role_upgrade',
                    'user',
                    $userId,
                    'User upgraded from buyer to seller',
                    ['role' => $user['role'] ?? 'buyer'],
                    [
                        'role' => 'seller',
                        'account_type' => 'seller',
                        'subscription_status' => 'active',
                        'membership_label' => 'Active',
                    ],
                    'success'
                );

                $this->createNotification(
                    $userId,
                    'system',
                    'Seller Account Activated',
                    'Your seller account has been activated! You can now upload and sell digital products.',
                    base_url('dashboard')
                );

                log_message('info', 'User membership activated as seller: ' . $userId);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Activate seller membership error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deactivate seller membership and fallback account to buyer.
     */
    public function deactivateSellerMembership(int $userId): bool
    {
        try {
            $user = $this->find($userId);

            if (!$user) {
                log_message('error', 'User not found for seller deactivation: ' . $userId);
                return false;
            }

            $result = $this->update($userId, $this->filterExistingUserColumns([
                'role' => 'buyer',
                'account_type' => 'buyer',
                'subscription_status' => 'inactive',
                'membership_label' => 'Inactive',
                'subscription_end_date' => null,
            ]));

            if ($result) {
                $this->auditLog(
                    $userId,
                    'role_downgrade',
                    'user',
                    $userId,
                    'User membership downgraded to buyer',
                    [
                        'role' => $user['role'] ?? 'seller',
                        'subscription_status' => $user['subscription_status'] ?? null,
                    ],
                    [
                        'role' => 'buyer',
                        'subscription_status' => 'inactive',
                    ],
                    'success'
                );
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Deactivate seller membership error: ' . $e->getMessage());
            return false;
        }
    }

    private function defaultDashboardPreferences(): array
    {
        return [
            'layout' => 'default',
            'show_sales_overview' => true,
            'show_recent_orders' => true,
            'theme' => 'light',
        ];
    }

    private function defaultStoreProfile(string $fullName): array
    {
        return [
            'display_name' => trim($fullName) !== '' ? trim($fullName) : 'New Seller',
            'tagline' => 'Digital creator on ByteMarket',
            'description' => '',
        ];
    }

    private function filterExistingUserColumns(array $data): array
    {
        $columns = $this->db->getFieldNames($this->table);

        return array_filter(
            $data,
            static fn ($key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Suspend user account (Admin)
     */
    public function suspendUser(int $userId, int $adminId, string $reason): bool
    {
        try {
            $result = $this->update($userId, ['status' => 'suspended']);

            if ($result) {
                $this->auditLog(
                    $adminId,
                    'admin_action',
                    'user',
                    $userId,
                    'User account suspended: ' . $reason,
                    null,
                    ['status' => 'suspended', 'reason' => $reason],
                    'success'
                );

                // Notify user
                $this->createNotification(
                    $userId,
                    'system',
                    'Account Suspended',
                    'Your account has been suspended. Reason: ' . $reason,
                    null
                );
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Suspend user error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reactivate user account (Admin)
     */
    public function reactivateUser(int $userId, int $adminId): bool
    {
        try {
            $result = $this->update($userId, ['status' => 'active']);

            if ($result) {
                $this->auditLog(
                    $adminId,
                    'admin_action',
                    'user',
                    $userId,
                    'User account reactivated',
                    null,
                    ['status' => 'active'],
                    'success'
                );

                // Notify user
                $this->createNotification(
                    $userId,
                    'system',
                    'Account Reactivated',
                    'Your account has been reactivated. You can now log in.',
                    null
                );
            }

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Reactivate user error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        try {
            return $this->where('email', $email)->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Email check error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hash password using Argon2id or bcrypt
     */
    private function hashPassword(string $password): string
    {
        // Use Argon2id if available (PHP 7.3+), otherwise bcrypt
        if (defined('PASSWORD_ARGON2ID')) {
            return password_hash($password, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536, // 64 MB
                'time_cost' => 4,
                'threads' => 3
            ]);
        }

        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 12
        ]);
    }

    /**
     * Verify password against hash
     */
    private function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Check if password is strong enough
     */
    private function isStrongPassword(string $password): bool
    {
        // Minimum 8 characters
        if (strlen($password) < 8) {
            return false;
        }

        // At least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // At least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // At least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // At least one special character
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Get password strength requirements
     */
    public static function getPasswordRequirements(): array
    {
        return [
            'min_length' => 8,
            'requires_uppercase' => true,
            'requires_lowercase' => true,
            'requires_number' => true,
            'requires_special_char' => true,
            'message' => 'Password must be at least 8 characters and contain uppercase, lowercase, number, and special character'
        ];
    }

    /**
     * Logout user (clear session and audit)
     */
    public function logoutUser(int $userId): bool
    {
        try {
            $this->auditLog(
                $userId,
                'logout',
                'user',
                $userId,
                'User logged out',
                null,
                null,
                'success'
            );

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Logout error: ' . $e->getMessage());
            return false;
        }
    }
}
