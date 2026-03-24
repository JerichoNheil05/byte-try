<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Display the home page with product listings.
     *
     * @return string
     */
    public function index(): string
    {
        $selectedTopCategory = strtolower(trim((string) ($this->request->getGet('top') ?? 'all')));
        $selectedGroup = strtolower(trim((string) ($this->request->getGet('group') ?? 'all')));
        $searchQuery = trim((string) ($this->request->getGet('q') ?? ''));
        $searchQueryLower = strtolower($searchQuery);

        $rows = $this->fetchActiveProductRows(200);
        $filterOptions = $this->buildFilterOptions($rows);

        if (!isset($filterOptions['top_categories'][$selectedTopCategory])) {
            $selectedTopCategory = 'all';
        }

        if (!isset($filterOptions['group_options'][$selectedGroup])) {
            $selectedGroup = 'all';
        }

        $products = $this->collectProducts($rows, $selectedTopCategory, $selectedGroup, $searchQueryLower);

        if (empty($products) && $searchQueryLower === '') {
            $products = [
                ['id' => 0, 'title' => 'Encanto Theme for PowerPoint', 'seller' => 'MCreateArts', 'thumbnail_url' => null, 'platform' => 'PowerPoint', 'rating' => 0.0, 'reviews' => 0, 'group' => 'creative', 'category_slug' => 'presentation-slides'],
                ['id' => 0, 'title' => 'Simple News Theme for PowerPoint', 'seller' => 'MCreateArts', 'thumbnail_url' => null, 'platform' => 'PowerPoint', 'rating' => 0.0, 'reviews' => 0, 'group' => 'report', 'category_slug' => 'presentation-slides'],
                ['id' => 0, 'title' => 'Education Theme for PowerPoint', 'seller' => 'MCreateArts', 'thumbnail_url' => null, 'platform' => 'PowerPoint', 'rating' => 0.0, 'reviews' => 0, 'group' => 'academic', 'category_slug' => 'presentation-slides'],
            ];
        }

        $availability = $this->computeAvailability($rows, $selectedTopCategory, $selectedGroup, $searchQueryLower);

        return view('home', [
            'products' => $products,
            'selectedTopCategory' => $selectedTopCategory,
            'selectedGroup' => $selectedGroup,
            'searchQuery' => $searchQuery,
            'topCategories' => $filterOptions['top_categories'],
            'groupOptions' => $filterOptions['group_options'],
            'availableTops' => $availability['available_tops'],
            'availableGroups' => $availability['available_groups'],
        ]);
    }

    /**
     * AJAX endpoint for header search on Home.
     */
    public function search(): \CodeIgniter\HTTP\ResponseInterface
    {
        $topParam = $this->request->getGet('top');
        $selectedTopCategory = $topParam === null
            ? 'all'
            : strtolower(trim((string) $topParam));
        $selectedGroup = strtolower(trim((string) ($this->request->getGet('group') ?? 'all')));
        $searchQuery = trim((string) ($this->request->getGet('q') ?? ''));
        $searchQueryLower = strtolower($searchQuery);

        $rows = $this->fetchActiveProductRows(200);
        $filterOptions = $this->buildFilterOptions($rows);

        if (!isset($filterOptions['top_categories'][$selectedTopCategory])) {
            $selectedTopCategory = 'all';
        }

        if (!isset($filterOptions['group_options'][$selectedGroup])) {
            $selectedGroup = 'all';
        }

        $products = $this->collectProducts($rows, $selectedTopCategory, $selectedGroup, $searchQueryLower);

        $availability = $this->computeAvailability($rows, $selectedTopCategory, $selectedGroup, $searchQueryLower);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'products' => $products,
                'count' => count($products),
                'query' => $searchQuery,
                'top' => $selectedTopCategory,
                'group' => $selectedGroup,
                'available_tops' => $availability['available_tops'],
                'available_groups' => $availability['available_groups'],
            ],
        ]);
    }

    /**
     * Display products filtered by category.
     *
     * @param string $categoryId The category ID to filter by
     * @return string
     */
    public function category(string $categoryId = ''): string
    {
        return redirect()->to('/home?top=' . urlencode($categoryId));
    }

    /**
     * Display a single product detail page.
     *
     * @param string $productId The product ID to display
     * @return string
     */
    public function product(string $productId = ''): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $id = (int) $productId;
        if ($id <= 0) {
            return redirect()->to('/home')->with('error', 'Invalid product.');
        }

        $productModel = new \App\Models\ProductModel();
        $authModel = new \App\Models\AuthModel();
        $feedbackModel = new \App\Models\ProductFeedbackModel();

        $row = $productModel->find($id);
        if (empty($row)) {
            return redirect()->to('/home')->with('error', 'Product not found.');
        }

        $status = strtolower(trim((string) ($row['status'] ?? 'active')));
        if ($status !== '' && $status !== 'active') {
            return redirect()->to('/home')->with('error', 'Product is not available.');
        }

        $sellerId = (int) ($row['seller_id'] ?? 0);
        $sellerUser = $sellerId > 0 ? $authModel->getUserById($sellerId) : null;
        $sellerName = trim((string) ($sellerUser['full_name'] ?? 'MCreateArts'));

        $previewPaths = $this->extractPathCollection((string) ($row['preview_path'] ?? ''));
        $previewUrls = array_map(
            static fn(string $path): string => base_url(ltrim($path, '/')),
            $previewPaths
        );

        if (empty($previewUrls)) {
            $previewUrls[] = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3EProduct%20Preview%3C/text%3E%3C/svg%3E';
        }

        $feedbackEntries = [];
        $canLeaveFeedback = false;
        $userFeedback = null;
        $ratingValue = 0.0;
        $reviewCount = 0;

        if ($feedbackModel->isFeedbackTableReady()) {
            $feedbackEntries = $feedbackModel->getFeedbackForProduct($id);
            $summary = $feedbackModel->getRatingSummary($id);
            $ratingValue = (float) ($summary['average'] ?? 0);
            $reviewCount = (int) ($summary['count'] ?? 0);

            $currentUserId = (int) (session()->get('userId') ?? 0);
            if ($currentUserId > 0) {
                $canLeaveFeedback = $feedbackModel->hasPurchasedProduct($currentUserId, $id);
                $userFeedback = $feedbackModel->getUserFeedback($currentUserId, $id);
            }
        }

        if ($reviewCount <= 0) {
            $ratingValue = 0.0;
        }

        $product = [
            'id' => (int) ($row['id'] ?? $id),
            'title' => trim((string) ($row['title'] ?? 'Untitled Product')),
            'description' => trim((string) ($row['description'] ?? 'No description provided.')),
            'product_feature' => trim((string) ($row['product_feature'] ?? '')),
            'how_it_works' => trim((string) ($row['how_it_works'] ?? '')),
            'price' => (float) ($row['price'] ?? 0),
            'seller' => $sellerName !== '' ? $sellerName : 'MCreateArts',
            'rating' => $ratingValue,
            'reviews' => $reviewCount,
            'preview_urls' => $previewUrls,
            'redirect_url' => trim((string) ($row['redirect_url'] ?? '')),
            'category' => trim((string) ($row['category'] ?? 'presentation-slides')),
        ];

        $filterRows = $this->fetchActiveProductRows(200);
        $filterOptions = $this->buildFilterOptions($filterRows);
        $activeCategory = $this->resolveTopCategory(
            $product['title'],
            $product['description'],
            $product['category']
        );

        return view('home_product_detail', [
            'product' => $product,
            'feedbackEntries' => $feedbackEntries,
            'canLeaveFeedback' => $canLeaveFeedback,
            'userFeedback' => $userFeedback,
            'topCategories' => $filterOptions['top_categories'],
            'activeTopCategory' => $activeCategory,
        ]);
    }

    public function submitFeedback(string $productId = ''): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $id = (int) $productId;
        if ($id <= 0) {
            return redirect()->to('/home')->with('error', 'Invalid product.');
        }

        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'comment' => 'required|string|max_length[1500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/home/product/' . $id)
                ->withInput()
                ->with('error', 'Please provide a valid rating and feedback message.');
        }

        $productModel = new \App\Models\ProductModel();
        $feedbackModel = new \App\Models\ProductFeedbackModel();

        $product = $productModel->find($id);
        if (empty($product)) {
            return redirect()->to('/home')->with('error', 'Product not found.');
        }

        if (!$feedbackModel->isFeedbackTableReady()) {
            return redirect()->to('/home/product/' . $id)
                ->withInput()
                ->with('error', 'Feedback is not available yet. Please run database migrations.');
        }

        if (!$feedbackModel->hasPurchasedProduct($userId, $id)) {
            return redirect()->to('/home/product/' . $id)
                ->withInput()
                ->with('error', 'You can only add feedback after purchasing this product.');
        }

        $rating = (int) $this->request->getPost('rating');
        $comment = trim((string) $this->request->getPost('comment'));

        if (!$feedbackModel->saveFeedback($userId, $id, $rating, $comment)) {
            return redirect()->to('/home/product/' . $id)
                ->withInput()
                ->with('error', 'Unable to submit feedback right now. Please try again.');
        }

        return redirect()->to('/home/product/' . $id)
            ->with('message', 'Thank you! Your feedback has been saved.');
    }

    private function extractPrimaryPath(string $rawPath): string
    {
        $rawPath = trim($rawPath);
        if ($rawPath === '') {
            return '';
        }

        $decoded = json_decode($rawPath, true);
        if (is_array($decoded)) {
            // Use the last entry — newly uploaded thumbnails are appended,
            // so the last one is the most recently added.
            foreach (array_reverse($decoded) as $path) {
                $path = str_replace('\/', '/', trim((string) $path));
                if ($path !== '') {
                    return $path;
                }
            }

            return '';
        }

        // Scalar — strip any JSON escape artifacts.
        return str_replace('\/', '/', $rawPath);
    }

    private function collectProducts(array $rows, string $selectedTopCategory, string $selectedGroup, string $searchQueryLower): array
    {
        $authModel = new \App\Models\AuthModel();
        $feedbackModel = new \App\Models\ProductFeedbackModel();

        $productIds = array_values(array_filter(array_map(static fn(array $row): int => (int) ($row['id'] ?? 0), $rows), static fn(int $id): bool => $id > 0));
        $ratingSummaryMap = $feedbackModel->getRatingSummaryMap($productIds);
        $sellerCache = [];

        $products = [];
        foreach ($rows as $row) {
            $status = strtolower(trim((string) ($row['status'] ?? 'active')));
            if ($status !== '' && $status !== 'active') {
                continue;
            }

            $sellerId = (int) ($row['seller_id'] ?? 0);
            if (!array_key_exists($sellerId, $sellerCache)) {
                $sellerUser = $sellerId > 0 ? $authModel->getUserById($sellerId) : null;
                $sellerCache[$sellerId] = trim((string) ($sellerUser['full_name'] ?? 'MCreateArts'));
            }

            $title = trim((string) ($row['title'] ?? 'Untitled Product'));
            $description = trim((string) ($row['description'] ?? ''));
            $categoryLabel = trim((string) ($row['category'] ?? 'Presentation Slides'));
            $categorySlug = strtolower(str_replace(['&', ' '], ['and', '-'], $categoryLabel));
            $sellerName = $sellerCache[$sellerId] ?: 'MCreateArts';

            if ($searchQueryLower !== '') {
                $searchHaystack = strtolower(trim($title . ' ' . $description . ' ' . $categoryLabel . ' ' . $sellerName));
                if (!str_contains($searchHaystack, $searchQueryLower)) {
                    continue;
                }
            }

            $group = $this->resolveProductGroup($title, $description, $categoryLabel);
            if ($selectedGroup !== 'all' && $selectedGroup !== $group) {
                continue;
            }

            $topCategory = $this->resolveTopCategory($title, $description, $categoryLabel);
            if ($selectedTopCategory !== '' && $selectedTopCategory !== 'all' && $topCategory !== $selectedTopCategory) {
                continue;
            }

            $thumbPath = $this->extractPrimaryPath((string) ($row['preview_path'] ?? ''));
            $thumbUrl = $thumbPath !== '' ? base_url(ltrim($thumbPath, '/')) : null;

            $platform = str_contains(strtolower($title . ' ' . $description), 'canva') ? 'Canva' : 'PowerPoint';

            $products[] = [
                'id' => (int) ($row['id'] ?? 0),
                'title' => $title,
                'seller' => $sellerName,
                'thumbnail_url' => $thumbUrl,
                'platform' => $platform,
                'rating' => (float) ($ratingSummaryMap[(int) ($row['id'] ?? 0)]['average'] ?? 0.0),
                'reviews' => (int) ($ratingSummaryMap[(int) ($row['id'] ?? 0)]['count'] ?? 0),
                'group' => $group,
                'category_slug' => $categorySlug,
            ];
        }

        return $products;
    }

    /**
     * Computes which top-category slugs and group slugs currently have
     * at least one active product, so the view can dim empty filter options.
     *
     * - available_tops  : respects group + query filters (ignores top filter)
     * - available_groups: respects top  + query filters (ignores group filter)
     */
    private function computeAvailability(array $rows, string $topFilter, string $groupFilter, string $queryLower): array
    {
        $availableTops   = [];
        $availableGroups = [];

        foreach ($rows as $row) {
            $status = strtolower(trim((string) ($row['status'] ?? 'active')));
            if ($status !== '' && $status !== 'active') {
                continue;
            }

            $title         = trim((string) ($row['title'] ?? ''));
            $description   = trim((string) ($row['description'] ?? ''));
            $categoryLabel = trim((string) ($row['category'] ?? 'Presentation Slides'));

            if ($queryLower !== '') {
                $haystack = strtolower($title . ' ' . $description . ' ' . $categoryLabel);
                if (!str_contains($haystack, $queryLower)) {
                    continue;
                }
            }

            $group       = $this->resolveProductGroup($title, $description, $categoryLabel);
            $topCategory = $this->resolveTopCategory($title, $description, $categoryLabel);

            // Available tops: categories that have products in the current GROUP
            if ($groupFilter === 'all' || $groupFilter === $group) {
                $availableTops[$topCategory] = true;
            }

            // Available groups: groups that have products in the current TOP
            if ($topFilter === '' || $topFilter === 'all' || $topFilter === $topCategory) {
                $availableGroups[$group] = true;
            }
        }

        if (!empty($availableTops)) {
            $availableTops['all'] = true;
        }

        // 'all' is always selectable when any group has products
        if (!empty($availableGroups)) {
            $availableGroups['all'] = true;
        }

        return [
            'available_tops'   => array_values(array_keys($availableTops)),
            'available_groups' => array_values(array_keys($availableGroups)),
        ];
    }

    private function resolveProductGroup(string $title, string $description, string $category): string
    {
        $haystack = strtolower($title . ' ' . $description . ' ' . $category);

        if (str_contains($haystack, 'report') || str_contains($haystack, 'news')) {
            return 'report';
        }

        if (str_contains($haystack, 'academic') || str_contains($haystack, 'education') || str_contains($haystack, 'study')) {
            return 'academic';
        }

        return 'creative';
    }

    private function resolveTopCategory(string $title, string $description, string $category): string
    {
        // Prefer category value stored by the system.
        $categorySlug = $this->slugifyFilterValue($category);
        if ($categorySlug !== '') {
            return $categorySlug;
        }

        // Fallback: keyword matching for legacy or unrecognised category labels.
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

    private function fetchActiveProductRows(int $limit = 200): array
    {
        $productModel = new \App\Models\ProductModel();
        $rows = $productModel->orderBy('created_at', 'DESC')->findAll($limit);

        return array_values(array_filter($rows, static function (array $row): bool {
            $status = strtolower(trim((string) ($row['status'] ?? 'active')));
            return $status === '' || $status === 'active';
        }));
    }

    private function buildFilterOptions(array $rows): array
    {
        $topCategories = ['all' => 'All Products'];
        $groupOptions = ['all' => 'All Types'];

        foreach ($rows as $row) {
            $title = trim((string) ($row['title'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            $category = trim((string) ($row['category'] ?? ''));

            $topSlug = $this->resolveTopCategory($title, $description, $category);
            if ($topSlug !== '' && !isset($topCategories[$topSlug])) {
                $topCategories[$topSlug] = $category !== '' ? $category : $this->slugToLabel($topSlug);
            }

            $groupSlug = $this->resolveProductGroup($title, $description, $category);
            if ($groupSlug !== '' && !isset($groupOptions[$groupSlug])) {
                $groupOptions[$groupSlug] = $this->slugToLabel($groupSlug);
            }
        }

        $dynamicTops = $topCategories;
        unset($dynamicTops['all']);
        uasort($dynamicTops, static fn(string $a, string $b): int => strnatcasecmp($a, $b));
        $topCategories = ['all' => 'All Products'] + $dynamicTops;

        $dynamicGroups = $groupOptions;
        unset($dynamicGroups['all']);
        uasort($dynamicGroups, static fn(string $a, string $b): int => strnatcasecmp($a, $b));
        $groupOptions = ['all' => 'All Types'] + $dynamicGroups;

        return [
            'top_categories' => $topCategories,
            'group_options' => $groupOptions,
        ];
    }

    private function slugifyFilterValue(string $value): string
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

    private function extractPathCollection(string $rawPath): array
    {
        $rawPath = trim($rawPath);
        if ($rawPath === '') {
            return [];
        }

        $decoded = json_decode($rawPath, true);
        if (is_array($decoded)) {
            $paths = [];
            foreach ($decoded as $path) {
                $path = trim((string) $path);
                if ($path !== '') {
                    $paths[] = $path;
                }
            }

            return $paths;
        }

        return [$rawPath];
    }

    /**
     * Display the marketer dashboard.
     *
     * @return string
     */
    public function dashboard(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $accountType = session()->get('account_type') ?? session()->get('role') ?? 'buyer';
        $subscriptionStatus = session()->get('subscription_status') ?? (($accountType === 'seller') ? 'active' : 'inactive');

        if ($accountType !== 'seller' || $subscriptionStatus !== 'active') {
            return redirect()->to('/subscription')->with('error', 'Activate your seller membership to access the seller dashboard.');
        }

        return view('dashboard', [
            'membershipLabel' => session()->get('membership_label') ?? 'Active',
        ]);
    }
}
