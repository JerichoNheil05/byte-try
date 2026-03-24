<?php

namespace App\Controllers;

class Bytefolio extends BaseController
{
    private function ensureSellerAccess()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $role = strtolower(trim((string) (session()->get('role') ?? 'buyer')));
        if ($role !== 'seller') {
            return redirect()->to('/home')->with('error', 'Access denied. Seller account required.');
        }

        return null;
    }

    /**
     * Display the ByteFolio page.
     *
     * @return string
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $userId = (int) (session()->get('userId') ?? 0);
        $userData = [
            'full_name' => 'User',
            'bio' => 'Digital Creator',
            'headline' => 'Digital Creator',
            'profile_image' => base_url('assets/images/default-avatar.svg'),
            'country' => 'Philippines',
            'city' => 'Manila',
            'phone' => '+63 (0) 9876554241',
        ];

        $products = [];

        if ($userId > 0) {
            try {
                $authModel = new \App\Models\AuthModel();
                $user = $authModel->getUserById($userId);
                $productModel = new \App\Models\ProductModel();
                $feedbackModel = new \App\Models\ProductFeedbackModel();
                $productRows = $productModel->getSellerProducts($userId);
                $productIds = array_values(array_filter(array_map(static fn(array $row): int => (int) ($row['id'] ?? 0), $productRows), static fn(int $id): bool => $id > 0));
                $ratingSummaryMap = $feedbackModel->getRatingSummaryMap($productIds);

                if (!empty($user)) {
                    $role = strtolower(trim((string) ($user['role'] ?? 'buyer')));
                    $accountType = strtolower(trim((string) ($user['account_type'] ?? $role)));
                    $subscriptionStatus = strtolower(trim((string) ($user['subscription_status'] ?? (($accountType === 'seller') ? 'active' : 'inactive'))));
                    $membershipLabel = trim((string) ($user['membership_label'] ?? (($subscriptionStatus === 'active') ? 'Active' : 'Inactive')));

                    session()->set([
                        'role' => $role,
                        'account_type' => $accountType,
                        'subscription_status' => $subscriptionStatus,
                        'membership_label' => $membershipLabel,
                        'subscription_end_date' => $user['subscription_end_date'] ?? session()->get('subscription_end_date'),
                        'can_access_seller_dashboard' => $accountType === 'seller' && $subscriptionStatus === 'active',
                    ]);

                    $userData = [
                        'full_name' => trim((string) ($user['full_name'] ?? 'User')),
                        'bio' => trim((string) ($user['bio'] ?? 'Digital Creator')),
                        'headline' => trim((string) ($user['headline'] ?? 'Digital Creator')),
                        'profile_image' => !empty($user['profile_image']) ? base_url($user['profile_image']) : base_url('assets/images/default-avatar.svg'),
                        'country' => trim((string) ($user['country'] ?? 'Philippines')),
                        'city' => trim((string) ($user['city'] ?? 'Manila')),
                        'phone' => trim((string) ($user['phone'] ?? '+63 (0) 9876554241')),
                    ];
                }

                $products = array_map(function (array $row) use ($userData): array {
                    $thumbnailPaths = $this->parsePathCollection((string) ($row['preview_path'] ?? ''));
                    $thumbnailPath = $thumbnailPaths[0] ?? '';

                    return [
                        'id' => (int) ($row['id'] ?? 0),
                        'title' => trim((string) ($row['title'] ?? 'Untitled Product')),
                        'seller' => trim((string) ($userData['full_name'] ?? 'You')),
                        'thumbnail_url' => $thumbnailPath !== '' ? base_url(ltrim($thumbnailPath, '/')) : null,
                        'rating' => (float) ($ratingSummaryMap[(int) ($row['id'] ?? 0)]['average'] ?? 0.0),
                        'reviews' => (int) ($ratingSummaryMap[(int) ($row['id'] ?? 0)]['count'] ?? 0),
                        'detail_url' => base_url('home/product/' . (int) ($row['id'] ?? 0)),
                    ];
                }, $productRows);
            } catch (\Throwable $e) {
                log_message('warning', 'ByteFolio header seller-state sync failed: ' . $e->getMessage());
            }
        }

        return view('bytefolio', [
            'user' => $userData,
            'products' => $products,
        ]);
    }

    private function parsePathCollection(string $rawValue): array
    {
        $rawValue = trim($rawValue);
        if ($rawValue === '') {
            return [];
        }

        $decoded = json_decode($rawValue, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map(
                static fn($value): string => trim((string) $value),
                $decoded
            )));
        }

        return [trim($rawValue)];
    }

    /**
     * Handle profile updates (country, city, phone, headline).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update_profile()
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $rules = [
            'country'  => 'required|string|max_length[100]',
            'city'     => 'required|string|max_length[100]',
            'phone'    => 'required|string|max_length[20]',
            'headline' => 'required|string|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userId = (int) (session()->get('userId') ?? 0);
        $data = [
            'country' => trim((string) $this->request->getPost('country')),
            'city'    => trim((string) $this->request->getPost('city')),
            'phone'   => trim((string) $this->request->getPost('phone')),
            'headline' => trim((string) $this->request->getPost('headline')),
        ];

        $db = \Config\Database::connect();
        $data = array_filter(
            $data,
            static fn($value, $column) => $db->fieldExists($column, 'users'),
            ARRAY_FILTER_USE_BOTH
        );

        if (empty($data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No compatible profile fields found in database.');
        }

        try {
            $authModel = new \App\Models\AuthModel();
            if ($authModel->updateProfile($userId, $data)) {
                return redirect()->to('/bytefolio')
                    ->with('message', 'Profile updated successfully!');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Profile update error: ' . $e->getMessage());
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update profile. Please try again.');
    }

    /**
     * Handle profile picture upload and crop.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function upload_picture()
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $file = $this->request->getFile('profile_picture');

        if (!$file || !$file->isValid()) {
            return redirect()->back()
                ->with('error', 'Invalid file upload');
        }

        $validated = $this->validate([
            'profile_picture' => 'uploaded[profile_picture]|max_size[profile_picture,5120]|is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
        ]);

        if (!$validated) {
            return redirect()->back()
                ->with('error', 'Invalid image file. Please upload JPG, PNG, GIF, or WebP (max 5MB).');
        }

        $userId = (int) (session()->get('userId') ?? 0);
        $uploadDir = FCPATH . 'uploads/profiles/';

        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $newName = 'user_' . $userId . '_' . time() . '.' . $file->getClientExtension();

        try {
            $file->move($uploadDir, $newName);

            $profileImagePath = 'uploads/profiles/' . $newName;
            $authModel = new \App\Models\AuthModel();

            if ($authModel->updateProfile($userId, ['profile_image' => $profileImagePath])) {
                session()->set('profileImage', $profileImagePath);
                return redirect()->to('/bytefolio')
                    ->with('message', 'Profile picture updated successfully!');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Profile picture upload error: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('error', 'Failed to upload profile picture. Please try again.');
    }

    /**
     * Handle About Me section updates.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update_about()
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $rules = [
            'about_me' => 'required|string|max_length[5000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userId = (int) (session()->get('userId') ?? 0);
        $data = [
            'bio' => trim((string) $this->request->getPost('about_me')),
        ];

        try {
            $authModel = new \App\Models\AuthModel();
            if ($authModel->updateProfile($userId, $data)) {
                return redirect()->to('/bytefolio')
                    ->with('message', 'About me updated successfully!');
            }
        } catch (\Throwable $e) {
            log_message('error', 'About me update error: ' . $e->getMessage());
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update about me. Please try again.');
    }

    /**
     * Fetch seller's products (used for Most Sold Products section).
     *
     * @return string|array
     */
    public function products()
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        return redirect()->to('/bytefolio')->with('message', 'Products fetch coming soon!');
    }

    /**
     * Get seller statistics (for future dashboard stats).
     *
     * @return array
     */
    public function get_stats()
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        return [
            'total_sales' => 0,
            'average_rating' => 0,
            'total_revenue' => 0,
            'customers' => 0,
        ];
    }
}
