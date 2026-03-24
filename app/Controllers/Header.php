<?php

namespace App\Controllers;

class Header extends BaseController
{
    /**
     * Display the header view
     * Note: This is typically included in other views, not called directly
     * 
     * @return string
     */
    public function index(): string
    {
        return view('header');
    }

    /**
     * Display/Edit user account page
     * Placeholder for user account management
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function account()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'));
        }

        return redirect()->to(base_url('auth/profile'));
    }

    /**
     * Display marketer dashboard
     * Redirects to the main dashboard
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function dashboard()
    {
        // Redirect to the main dashboard
        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Display user notifications
     * Placeholder for notifications system
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function notifications()
    {
        return redirect()->to(base_url('notifications'));
    }

    /**
     * Display account settings page
     * Placeholder for settings management
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function settings()
    {
        // TODO: Implement settings functionality
        // - Display settings form (privacy, security, preferences)
        // - Handle settings updates
        // - Password change functionality
        // - Two-factor authentication setup
        
        return redirect()->to(base_url('/'))
                         ->with('message', 'Settings functionality coming soon!');
    }

    /**
     * Display FAQ page
     * FAQ/Help section
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function faq()
    {
      $topCategories = $this->buildHomeLikeTopCategories();
      $selectedTopCategory = strtolower(trim((string) ($this->request->getGet('top') ?? 'all')));

      if (!isset($topCategories[$selectedTopCategory])) {
        $selectedTopCategory = 'all';
      }

      return view('faq', [
        'topCategories' => $topCategories,
        'selectedTopCategory' => $selectedTopCategory,
      ]);
    }

    private function buildHomeLikeTopCategories(): array
    {
      $productModel = new \App\Models\ProductModel();
      $rows = $productModel->orderBy('created_at', 'DESC')->findAll(200);

      $topCategories = ['all' => 'All Products'];

      foreach ($rows as $row) {
        $status = strtolower(trim((string) ($row['status'] ?? 'active')));
        if ($status !== '' && $status !== 'active') {
          continue;
        }

        $title = trim((string) ($row['title'] ?? ''));
        $description = trim((string) ($row['description'] ?? ''));
        $category = trim((string) ($row['category'] ?? ''));

        $slug = $this->resolveTopCategorySlug($title, $description, $category);
        if ($slug === '' || isset($topCategories[$slug])) {
          continue;
        }

        $topCategories[$slug] = $category !== '' ? $category : $this->slugToLabel($slug);
      }

      $dynamic = $topCategories;
      unset($dynamic['all']);
      uasort($dynamic, static fn(string $a, string $b): int => strnatcasecmp($a, $b));

      return ['all' => 'All Products'] + $dynamic;
    }

    private function resolveTopCategorySlug(string $title, string $description, string $category): string
    {
      $categorySlug = $this->slugifyValue($category);
      if ($categorySlug !== '') {
        return $categorySlug;
      }

      $haystack = strtolower($title . ' ' . $description . ' ' . $category);

      if (str_contains($haystack, 'ebook') || str_contains($haystack, 'e-book')) {
        return 'e-books';
      }
      if (str_contains($haystack, 'printable') || str_contains($haystack, 'planner') || str_contains($haystack, 'worksheet')) {
        return 'printables';
      }
      if (str_contains($haystack, 'excel') || str_contains($haystack, 'spreadsheet') || str_contains($haystack, 'finance') || str_contains($haystack, 'business')) {
        return 'business-finance-tools';
      }
      if (str_contains($haystack, 'marketing') || str_contains($haystack, 'social media')) {
        return 'marketing-materials';
      }
      if (str_contains($haystack, 'design') || str_contains($haystack, 'figma') || str_contains($haystack, 'icon') || str_contains($haystack, 'illustration')) {
        return 'design-assets';
      }
      if (str_contains($haystack, 'study') || str_contains($haystack, 'productivity') || str_contains($haystack, 'notion')) {
        return 'study-productivity';
      }
      if (str_contains($haystack, 'creative') || str_contains($haystack, 'bundle') || str_contains($haystack, 'brand kit')) {
        return 'creative-packs';
      }
      if (str_contains($haystack, 'template') || str_contains($haystack, 'theme') || str_contains($haystack, 'layout')) {
        return 'templates';
      }
      if (str_contains($haystack, 'presentation') || str_contains($haystack, 'slides') || str_contains($haystack, 'powerpoint') || str_contains($haystack, 'ppt')) {
        return 'presentation-slides';
      }

      return 'templates';
    }

    private function slugifyValue(string $value): string
    {
      $value = strtolower(trim($value));
      if ($value === '') {
        return '';
      }

      $value = str_replace('&', ' and ', $value);
      $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

      return trim($value, '-');
    }

    private function slugToLabel(string $slug): string
    {
      $normalized = trim(str_replace('-', ' ', strtolower($slug)));
      if ($normalized === '') {
        return 'General';
      }

      return ucwords($normalized);
    }

    /**
     * Display Contact Us page
     */
    public function contact(): string
    {
        return view('contact');
    }

    /**
     * Handle Contact Us form submission
     */
    public function contactSend()
    {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email'     => 'required|valid_email',
            'message'   => 'required|min_length[5]|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $name    = $this->request->getPost('full_name');
        $email   = $this->request->getPost('email');
        $phone   = $this->request->getPost('contact_number');
        $subject = $this->request->getPost('subject') ?: 'Contact Form Message';
        $message = $this->request->getPost('message');

        $emailService = \Config\Services::email();

        $emailService->setFrom($email, $name);
        $emailService->setTo('bytemarket730@gmail.com');
        $emailService->setSubject('[ByteMarket Contact] ' . esc($subject));

        $siteUrl   = base_url();
        $year      = date('Y');

        // Escape all user-supplied values before embedding in HTML email
        $eName     = esc($name);
        $eEmail    = esc($email);
        $eSubject  = esc($subject);
        $eMessage  = nl2br(esc($message));
        $phoneLine = $phone ? '<tr><td style="padding:6px 0;color:#555;font-size:14px;"><strong style="color:#222;width:110px;display:inline-block;">Phone</strong>' . esc($phone) . '</td></tr>' : '';

        $body = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;">

  <!-- Wrapper -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

          <!-- HEADER -->
          <tr>
            <td style="background:#1c2b3a;padding:28px 36px;" align="center">
              <span style="color:#ffffff;font-size:24px;font-weight:700;letter-spacing:0.5px;">ByteMarket</span>
            </td>
          </tr>

          <!-- ACCENT BAR -->
          <tr>
            <td style="height:4px;background:linear-gradient(90deg,#2f80d0,#22a43a);"></td>
          </tr>

          <!-- BODY -->
          <tr>
            <td style="padding:36px 40px 28px;">

              <!-- Title -->
              <p style="margin:0 0 6px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:#2f80d0;">New Message</p>
              <h1 style="margin:0 0 24px;font-size:22px;font-weight:700;color:#1c2b3a;">Contact Form Submission</h1>

              <!-- Info table -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:8px;padding:20px 24px;margin-bottom:24px;">
                <tr><td style="padding:6px 0;color:#555;font-size:14px;"><strong style="color:#222;width:110px;display:inline-block;">Name</strong>{$eName}</td></tr>
                <tr><td style="padding:6px 0;color:#555;font-size:14px;"><strong style="color:#222;width:110px;display:inline-block;">Email</strong><a href="mailto:{$eEmail}" style="color:#2f80d0;text-decoration:none;">{$eEmail}</a></td></tr>
                {$phoneLine}
                <tr><td style="padding:6px 0;color:#555;font-size:14px;"><strong style="color:#222;width:110px;display:inline-block;">Subject</strong>{$eSubject}</td></tr>
              </table>

              <!-- Message -->
              <p style="margin:0 0 10px;font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#888;">Message</p>
              <div style="background:#f0f7ff;border-left:4px solid #2f80d0;border-radius:0 8px 8px 0;padding:18px 20px;font-size:15px;line-height:1.7;color:#333;">
                {$eMessage}
              </div>

              <!-- CTA -->
              <div style="margin-top:28px;text-align:center;">
                <a href="mailto:{$eEmail}" style="display:inline-block;background:#2f80d0;color:#fff;text-decoration:none;padding:13px 36px;border-radius:8px;font-size:14px;font-weight:600;letter-spacing:0.5px;">Reply to {$eName}</a>
              </div>

            </td>
          </tr>

          <!-- FOOTER -->
          <tr>
            <td style="background:#1c2b3a;padding:22px 36px;text-align:center;">
              <p style="margin:0 0 6px;color:#8fa3b1;font-size:12px;">&copy; {$year} Byte Market. All rights reserved.</p>
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

        $emailService->setMessage($body);

        if ($emailService->send()) {
            return redirect()->to(base_url('header/contact'))
                             ->with('success', 'Your message has been sent. We\'ll get back to you soon!');
        }

        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Sorry, we could not send your message. Please try again later.');
    }

    /**
     * Handle user logout
     * Placeholder for logout functionality
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        // TODO: Implement logout functionality
        // - Destroy user session
        // - Clear authentication cookies
        // - Log logout event
        // - Redirect to landing page
        
        $sessionConfig = config('Session');
        $cookieConfig = config('Cookie');
        $sessionCookieNames = array_unique(array_filter([
          $sessionConfig->cookieName ?? null,
          session_name(),
          'ci_session',
          'PHPSESSID',
        ]));

        // Clear session data
        session()->remove([
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
        session()->destroy();

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
        
        return redirect()->to(base_url('/'))
                         ->with('message', 'You have been logged out successfully.');
    }
}
