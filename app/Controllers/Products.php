<?php

namespace App\Controllers;

class Products extends BaseController
{
    private const MAX_THUMBNAIL_SIZE_BYTES = 5_242_880; // 5 MB

    private const ALLOWED_THUMBNAIL_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp',
    ];

    private const ADULT_CONTENT_KEYWORDS = [
        'porn',
        'porno',
        'pornography',
        'xxx',
        'nsfw',
        'nude',
        'nudity',
        'naked',
        'erotic',
        'sex',
        'sexual',
        'hentai',
        'onlyfans',
        'camgirl',
        'cam girl',
        'escort',
        'boob',
        'boobs',
        'breast',
        'nipple',
        'vagina',
        'penis',
        'anal',
        'fetish',
        'strip',
        'lingerie',
    ];

    private function ensureSellerAccess()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $role = strtolower(trim((string) ($this->session->get('role') ?? 'buyer')));
        if ($role !== 'seller') {
            return redirect()->to('/home')->with('error', 'Access denied. Seller account required.');
        }

        return null;
    }

    /**
     * Display the product listing page with all seller's products.
     *
     * @return string
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $sellerId = (int) ($this->session->get('userId') ?? 0);
        $productModel = new \App\Models\ProductModel();
        $productRows = $productModel->getSellerProducts($sellerId);

        $selectedCategory = strtolower(trim((string) ($this->request->getGet('category') ?? 'all')));
        if ($selectedCategory !== '' && $selectedCategory !== 'all') {
            $productRows = array_values(array_filter(
                $productRows,
                static fn(array $row): bool => strtolower((string) ($row['category'] ?? '')) === $selectedCategory
            ));
        }

        $products = array_map(function (array $product): array {
            $createdAt = $product['created_at'] ?? null;
            $category = trim((string) ($product['category'] ?? ''));
            $thumbnailPaths = $this->parsePathCollection((string) ($product['preview_path'] ?? ''));
            $primaryThumbnail = !empty($thumbnailPaths) ? $thumbnailPaths[count($thumbnailPaths) - 1] : null;

            return [
                'id' => $product['id'] ?? null,
                'product_name' => $product['title'] ?? 'Untitled Product',
                'description' => $product['description'] ?? '',
                'category' => $category !== '' ? $category : 'General',
                'published_date' => $createdAt,
                'published_display' => !empty($createdAt) ? date('n/j/y', strtotime((string) $createdAt)) : 'N/A',
                'price' => $product['price'] ?? 0,
                'thumbnail_path' => $primaryThumbnail,
                'thumbnail_paths' => $thumbnailPaths,
            ];
        }, $productRows);

        return view('product_listing', [
            'products' => $products,
            'selected_category' => $selectedCategory,
        ]);
    }

    /**
     * Display the add products form.
     *
     * @return string
     */
    public function add(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        return view('add_products');
    }

    /**
     * Handle product form submission and save product data.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function save()
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $assetDeliveryType = strtolower(trim((string) ($this->request->getPost('asset_delivery_type') ?? 'file')));
        if (!in_array($assetDeliveryType, ['url', 'file'], true)) {
            $assetDeliveryType = 'file';
        }

        // Validate form data
        $rules = [
            'product_name'        => 'required|string|max_length[200]',
            'product_description' => 'required|string|max_length[5000]',
            'product_feature'     => 'required|string|max_length[5000]',
            'how_it_works'        => 'required|string|max_length[5000]',
            'price'               => 'required|numeric|greater_than[0]',
            'asset_delivery_type' => 'required|in_list[url,file]',
        ];

        if ($assetDeliveryType === 'url') {
            $rules['redirect_url'] = 'required|valid_url|max_length[2000]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                            ->withInput()
                            ->with('errors', $this->validator->getErrors());
        }

        $productName = trim((string) $this->request->getPost('product_name'));
        $productDescription = trim((string) $this->request->getPost('product_description'));
        $productFeature = trim((string) $this->request->getPost('product_feature'));
        $howItWorks = trim((string) $this->request->getPost('how_it_works'));
        $redirectUrl = $assetDeliveryType === 'url'
            ? (trim((string) ($this->request->getPost('redirect_url') ?? '')) ?: null)
            : null;

        $contentMatches = $this->findAdultContentMatches([
            $productName,
            $productDescription,
            $productFeature,
            $howItWorks,
            (string) ($redirectUrl ?? ''),
        ]);

        if (!empty($contentMatches)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Product contains restricted adult content terms and cannot be published.');
        }

        $sellerId = (int) ($this->session->get('userId') ?? 0);
        $thumbnailPaths = [];
        $productFilePaths = [];

        $thumbnailFiles = $this->request->getFileMultiple('thumbnails');
        $hasValidThumbnail = false;
        if (!empty($thumbnailFiles)) {
            foreach ($thumbnailFiles as $thumbnail) {
                if ($thumbnail && $thumbnail->isValid() && $thumbnail->getError() !== UPLOAD_ERR_NO_FILE) {
                    $hasValidThumbnail = true;
                    break;
                }
            }
        }

        if (!$hasValidThumbnail) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Thumbnail is required. Please upload at least one image.');
        }

        $thumbnailNameMatches = $this->findAdultContentMatches($this->extractUploadFileNames($thumbnailFiles));
        if (!empty($thumbnailNameMatches)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Thumbnail filename contains restricted content terms. Please rename and try again.');
        }

        if (!empty($thumbnailFiles)) {
            $thumbnailDir = FCPATH . 'uploads/product-thumbnails/';
            if (!is_dir($thumbnailDir)) {
                @mkdir($thumbnailDir, 0755, true);
            }

            foreach ($thumbnailFiles as $thumbnail) {
                if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
                    $thumbnailError = $this->validateUploadedThumbnail($thumbnail);
                    if ($thumbnailError !== null) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', $thumbnailError);
                    }

                    $newName = $thumbnail->getRandomName();
                    $thumbnail->move($thumbnailDir, $newName);
                    $thumbnailPaths[] = 'uploads/product-thumbnails/' . $newName;
                }
            }
        }

        if ($assetDeliveryType === 'file') {
            $productFiles = $this->request->getFileMultiple('product_files');
            $hasValidProductFile = false;
            if (!empty($productFiles)) {
                foreach ($productFiles as $productFile) {
                    if ($productFile && $productFile->isValid() && $productFile->getError() !== UPLOAD_ERR_NO_FILE) {
                        $hasValidProductFile = true;
                        break;
                    }
                }
            }

            if (!$hasValidProductFile) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Product file is required when File mode is selected.');
            }

            $productFileNameMatches = $this->findAdultContentMatches($this->extractUploadFileNames($productFiles));
            if (!empty($productFileNameMatches)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'One of the product file names contains restricted content terms.');
            }

            if (!empty($productFiles)) {
                $productFileDir = FCPATH . 'uploads/product-files/';
                if (!is_dir($productFileDir)) {
                    @mkdir($productFileDir, 0755, true);
                }

                foreach ($productFiles as $productFile) {
                    if ($productFile && $productFile->getError() === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }

                    if ($productFile && !$productFile->isValid()) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'One of the product files could not be uploaded.');
                    }

                    if ($productFile && !$productFile->hasMoved()) {
                        $newName = $productFile->getRandomName();
                        $productFile->move($productFileDir, $newName);
                        $productFilePaths[] = 'uploads/product-files/' . $newName;
                    }
                }
            }

            $productFilePaths = array_values(array_unique(array_filter($productFilePaths)));
        }

        $categoryClassifier = new \App\Models\ProductCategoryClassifierModel();
        $autoCategory = $categoryClassifier->classify([
            'title' => $productName,
            'description' => $productDescription,
            'product_feature' => $productFeature,
            'how_it_works' => $howItWorks,
            'redirect_url' => (string) ($redirectUrl ?? ''),
            'file_paths' => $productFilePaths,
        ]);

        $data = [
            'seller_id' => $sellerId,
            'title' => $productName,
            'description' => $productDescription,
            'product_feature' => $productFeature,
            'how_it_works' => $howItWorks,
            'price' => (float) $this->request->getPost('price'),
            'category' => $autoCategory,
            'preview_path' => !empty($thumbnailPaths)
                ? (count($thumbnailPaths) === 1
                    ? $thumbnailPaths[0]
                    : json_encode($thumbnailPaths, JSON_UNESCAPED_SLASHES))
                : null,
            'file_path' => !empty($productFilePaths)
                ? (count($productFilePaths) === 1
                    ? $productFilePaths[0]
                    : json_encode($productFilePaths, JSON_UNESCAPED_SLASHES))
                : null,
            'redirect_url' => $redirectUrl,
            'status' => 'active',
        ];

        try {
            $db = \Config\Database::connect();
            $data = array_filter(
                $data,
                static fn($value, $column) => $db->fieldExists($column, 'products'),
                ARRAY_FILTER_USE_BOTH
            );

            $productModel = new \App\Models\ProductModel();
            if ($productModel->insert($data) === false) {
                throw new \RuntimeException(implode(' ', $productModel->errors()));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Product save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save product. Please try again.');
        }

        return redirect()->to('/products')
                        ->with('message', 'Product published successfully!');
    }

    /**
     * Display seller's products (placeholder for list products page).
     *
     * @return string
     */
    public function list(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        // TODO: Fetch seller's products from database
        // $products = $this->Product_model->getBySellerID($seller_id);
        return redirect()->to('/dashboard')->with('message', 'Products list page coming soon!');
    }

    /**
     * Edit an existing product.
     *
     * @param string $productId The product ID to edit
     * @return string
     */
    public function edit(string $productId = ''): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $sellerId = (int) ($this->session->get('userId') ?? 0);
        $id = (int) $productId;

        if ($id <= 0) {
            return redirect()->to('/products')->with('error', 'Invalid product.');
        }

        $productModel = new \App\Models\ProductModel();
        $row = $productModel->where('id', $id)->where('seller_id', $sellerId)->first();

        if (empty($row)) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $thumbnailPaths = $this->parsePathCollection((string) ($row['preview_path'] ?? ''));
        $assetFilePaths = $this->parsePathCollection((string) ($row['file_path'] ?? ''));

        $product = [
            'id' => $row['id'] ?? $id,
            'product_name' => $row['title'] ?? '',
            'product_description' => $row['description'] ?? '',
            'product_feature' => $row['product_feature'] ?? '',
            'how_it_works' => $row['how_it_works'] ?? '',
            'price' => (float) ($row['price'] ?? 0),
            'asset_redirect_url' => $row['redirect_url'] ?? '',
            'thumbnails' => array_map(
                static fn(string $path): array => ['url' => base_url(ltrim($path, '/'))],
                $thumbnailPaths
            ),
            'asset_files' => array_map(
                static fn(string $path): array => [
                    'name' => basename($path),
                    'url' => base_url(ltrim($path, '/')),
                    'path' => $path,
                ],
                $assetFilePaths
            ),
        ];

        return view('edit_product', ['product' => $product]);
    }

    /**
     * Display product details page (seller view).
     *
     * @param string $productId The product ID to display
     * @return string
     */
    public function details(string $productId = ''): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        // TODO: Fetch product details from database by ID
        // $product = $this->Product_model->getById($productId);
        // Verify product belongs to current seller
        // if (!$product || $product['seller_id'] != session()->get('user_id')) {
        //     return redirect()->to('/dashboard')->with('error', 'Product not found');
        // }
        
        // For now, return sample product data
        $product = [
            'id' => $productId,
            'title' => 'Encanto themed PowerPoint Template',
            'price' => 200.00,
            'seller_name' => 'MCreateArts',
            'description' => 'Want to add some magic to your presentations? Our Encanto-themed PowerPoint template will transport your audience straight to the heart of the magical world of the Madrigal family! Whether you\'re working on a school project, business pitch, or creative presentation, this beginner-friendly template has everything you need to wow your audience.',
        ];
        
        return view('product_details', ['product' => $product]);
    }

    /**
     * Handle product update form submission.
     *
     * @param string $productId The product ID to update
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(string $productId = ''): \CodeIgniter\HTTP\RedirectResponse
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $sellerId = (int) ($this->session->get('userId') ?? 0);
        $id = (int) $productId;

        if ($id <= 0) {
            return redirect()->to('/products')->with('error', 'Invalid product.');
        }

        $productModel = new \App\Models\ProductModel();
        $existingProduct = $productModel->where('id', $id)->where('seller_id', $sellerId)->first();

        if (empty($existingProduct)) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $assetRedirectUrl = trim((string) ($this->request->getPost('asset_redirect_url') ?? ''));

        // Validate form data
        $rules = [
            'product_name'        => 'required|string|max_length[200]',
            'product_description' => 'required|string|max_length[5000]',
            'product_feature'     => 'required|string|max_length[5000]',
            'how_it_works'        => 'required|string|max_length[5000]',
            'price'               => 'required|numeric|greater_than[0]',
        ];

        if ($assetRedirectUrl !== '') {
            $rules['asset_redirect_url'] = 'valid_url|max_length[2000]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                            ->withInput()
                            ->with('errors', $this->validator->getErrors());
        }

        $productName = trim((string) $this->request->getPost('product_name'));
        $productDescription = trim((string) $this->request->getPost('product_description'));
        $productFeature = trim((string) $this->request->getPost('product_feature'));
        $howItWorks = trim((string) $this->request->getPost('how_it_works'));

        $contentMatches = $this->findAdultContentMatches([
            $productName,
            $productDescription,
            $productFeature,
            $howItWorks,
            $assetRedirectUrl,
        ]);

        if (!empty($contentMatches)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Product contains restricted adult content terms and cannot be published.');
        }

        $thumbnailPaths = $this->parsePathCollection((string) ($existingProduct['preview_path'] ?? ''));
        $assetFilePaths = $this->parsePathCollection((string) ($existingProduct['file_path'] ?? ''));

        $removedAssetPathsRaw = $this->request->getPost('removed_asset_paths');
        $removedAssetPaths = [];
        if (is_array($removedAssetPathsRaw)) {
            $removedAssetPaths = $removedAssetPathsRaw;
        } elseif (is_string($removedAssetPathsRaw) && trim($removedAssetPathsRaw) !== '') {
            $removedAssetPaths = [$removedAssetPathsRaw];
        }

        $removedAssetPaths = array_values(array_unique(array_filter(array_map(
            static fn($value): string => str_replace('\\/', '/', trim((string) $value)),
            $removedAssetPaths
        ))));

        if (!empty($removedAssetPaths)) {
            $assetFilePaths = array_values(array_filter(
                $assetFilePaths,
                static fn(string $path): bool => !in_array($path, $removedAssetPaths, true)
            ));

            $uploadsDir = realpath(FCPATH . 'uploads/product-files/');
            if ($uploadsDir !== false) {
                foreach ($removedAssetPaths as $removedPath) {
                    // Restrict deletions to product-files uploads only.
                    if (!str_starts_with($removedPath, 'uploads/product-files/')) {
                        continue;
                    }

                    $candidatePath = realpath(FCPATH . ltrim($removedPath, '/'));
                    if ($candidatePath === false) {
                        continue;
                    }

                    if (!str_starts_with($candidatePath, $uploadsDir . DIRECTORY_SEPARATOR) && $candidatePath !== $uploadsDir) {
                        continue;
                    }

                    if (is_file($candidatePath)) {
                        @unlink($candidatePath);
                    }
                }
            }
        }

        $thumbnailFiles = $this->request->getFileMultiple('thumbnails');
        if (!empty($thumbnailFiles)) {
            $thumbnailNameMatches = $this->findAdultContentMatches($this->extractUploadFileNames($thumbnailFiles));
            if (!empty($thumbnailNameMatches)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Thumbnail filename contains restricted content terms. Please rename and try again.');
            }

            $thumbnailDir = FCPATH . 'uploads/product-thumbnails/';
            if (!is_dir($thumbnailDir)) {
                @mkdir($thumbnailDir, 0755, true);
            }

            foreach ($thumbnailFiles as $thumbnail) {
                if (!$thumbnail || $thumbnail->getError() === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                if (!$thumbnail->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'One of the thumbnails could not be uploaded.');
                }

                $thumbnailError = $this->validateUploadedThumbnail($thumbnail);
                if ($thumbnailError !== null) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', $thumbnailError);
                }

                $newName = $thumbnail->getRandomName();
                $thumbnail->move($thumbnailDir, $newName);
                $thumbnailPaths[] = 'uploads/product-thumbnails/' . $newName;
            }
        }

        $thumbnailPaths = array_values(array_unique(array_filter($thumbnailPaths)));
        if (empty($thumbnailPaths)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'At least one thumbnail is required.');
        }

        $assetFiles = $this->request->getFileMultiple('asset_files');
        if (!empty($assetFiles)) {
            $assetFileNameMatches = $this->findAdultContentMatches($this->extractUploadFileNames($assetFiles));
            if (!empty($assetFileNameMatches)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'One of the asset file names contains restricted content terms.');
            }

            $assetFileDir = FCPATH . 'uploads/product-files/';
            if (!is_dir($assetFileDir)) {
                @mkdir($assetFileDir, 0755, true);
            }

            foreach ($assetFiles as $assetFile) {
                if (!$assetFile || $assetFile->getError() === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                if (!$assetFile->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'One of the asset files could not be uploaded.');
                }

                $newName = $assetFile->getRandomName();
                $assetFile->move($assetFileDir, $newName);
                $assetFilePaths[] = 'uploads/product-files/' . $newName;
            }
        }

        $assetFilePaths = array_values(array_unique(array_filter($assetFilePaths)));
        if (empty($assetFilePaths) && $assetRedirectUrl === '') {
            return redirect()->back()
                ->withInput()
            ->with('error', 'Please provide at least one asset file or a redirect URL.');
        }

        $data = [
            'title' => $productName,
            'description' => $productDescription,
            'product_feature' => $productFeature,
            'how_it_works' => $howItWorks,
            'price' => (float) $this->request->getPost('price'),
            'redirect_url' => $assetRedirectUrl !== '' ? $assetRedirectUrl : null,
            'preview_path' => count($thumbnailPaths) === 1
                ? $thumbnailPaths[0]
                : json_encode($thumbnailPaths, JSON_UNESCAPED_SLASHES),
            'file_path' => empty($assetFilePaths)
                ? null
                : (count($assetFilePaths) === 1
                    ? $assetFilePaths[0]
                    : json_encode($assetFilePaths, JSON_UNESCAPED_SLASHES)),
        ];

        try {
            $db = \Config\Database::connect();
            $data = array_filter(
                $data,
                static fn($value, $column) => $db->fieldExists($column, 'products'),
                ARRAY_FILTER_USE_BOTH
            );

            if ($productModel->update($id, $data) === false) {
                throw new \RuntimeException(implode(' ', $productModel->errors()));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Product update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }

        return redirect()->to('/products')
                        ->with('message', 'Product updated successfully!');
    }

    private function parsePathCollection(string $rawValue): array
    {
        $rawValue = trim($rawValue);
        if ($rawValue === '') {
            return [];
        }

        $candidates = [];
        $decoded = json_decode($rawValue, true);
        if (is_array($decoded)) {
            $candidates = array_map(static fn($value): string => trim((string) $value), $decoded);
        } elseif (is_string($decoded)) {
            $candidates = [trim($decoded)];
        } else {
            $candidates = [trim($rawValue)];
        }

        $normalized = [];
        foreach ($candidates as $candidate) {
            $candidate = str_replace('\\/', '/', trim($candidate, " \t\n\r\0\x0B\"'"));
            if ($candidate === '') {
                continue;
            }

            if (str_starts_with($candidate, 'http://') || str_starts_with($candidate, 'https://') || str_starts_with($candidate, 'uploads/')) {
                $normalized[] = $candidate;
                continue;
            }

            $uploadPos = strpos($candidate, 'uploads/');
            if ($uploadPos !== false) {
                $normalized[] = substr($candidate, $uploadPos);
            }
        }

        if ($normalized === [] && preg_match_all('/(?:https?:\/\/[^\s"\']+|uploads\/[A-Za-z0-9_\-\.\/]+)/i', $rawValue, $matches)) {
            foreach ($matches[0] as $match) {
                $match = str_replace('\\/', '/', trim((string) $match));
                if ($match !== '') {
                    $normalized[] = $match;
                }
            }
        }

        return array_values(array_unique(array_filter($normalized)));
    }

    private function extractUploadFileNames(array $uploadedFiles): array
    {
        $names = [];
        foreach ($uploadedFiles as $file) {
            if (!$file || !$file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $name = trim((string) $file->getClientName());
            if ($name !== '') {
                $names[] = $name;
            }
        }

        return $names;
    }

    private function findAdultContentMatches(array $inputs): array
    {
        $matches = [];

        foreach ($inputs as $input) {
            $text = strtolower(trim((string) $input));
            if ($text === '') {
                continue;
            }

            foreach (self::ADULT_CONTENT_KEYWORDS as $keyword) {
                if (str_contains($text, $keyword)) {
                    $matches[$keyword] = true;
                }
            }
        }

        return array_keys($matches);
    }

    private function validateUploadedThumbnail($thumbnail): ?string
    {
        if (!$thumbnail || !$thumbnail->isValid()) {
            return 'One of the thumbnails could not be uploaded.';
        }

        if ($thumbnail->getSize() > self::MAX_THUMBNAIL_SIZE_BYTES) {
            return 'Thumbnail must be 5MB or less.';
        }

        $mimeType = strtolower((string) $thumbnail->getMimeType());
        if (!in_array($mimeType, self::ALLOWED_THUMBNAIL_MIME_TYPES, true)) {
            return 'Thumbnail type is not allowed. Use JPEG, PNG, GIF, WEBP, or BMP.';
        }

        $imageInfo = @getimagesize($thumbnail->getTempName());
        if ($imageInfo === false) {
            return 'Thumbnail file does not appear to be a valid image.';
        }

        // Verify by magic bytes — MIME type alone can be spoofed.
        $handle = @fopen($thumbnail->getTempName(), 'rb');
        $magic  = $handle ? strtolower(bin2hex((string) fread($handle, 12))) : '';
        if ($handle) {
            fclose($handle);
        }

        $validPrefixes = [
            'ffd8ff',                       // JPEG
            '89504e47',                     // PNG
            '47494638',                     // GIF
            '424d',                         // BMP
            '52494646' . '????????' . '57454250', // WEBP (RIFF....WEBP)
        ];

        $isValidImage = false;
        foreach ($validPrefixes as $prefix) {
            if ($prefix === '52494646????????57454250') {
                if (str_starts_with($magic, '52494646') && str_contains($magic, '57454250')) {
                    $isValidImage = true;
                    break;
                }
                continue;
            }

            if (str_starts_with($magic, $prefix)) {
                $isValidImage = true;
                break;
            }
        }

        if (!$isValidImage) {
            return 'Thumbnail file signature is invalid.';
        }

        return null;
    }

    /**
     * Delete a product.
     *
     * @param string $productId The product ID to delete
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(string $productId = ''): \CodeIgniter\HTTP\RedirectResponse
    {
        $guard = $this->ensureSellerAccess();
        if ($guard !== null) {
            return $guard;
        }

        $sellerId = (int) ($this->session->get('userId') ?? 0);
        $id = (int) $productId;

        if ($id <= 0) {
            return redirect()->to('/products')->with('error', 'Invalid product.');
        }

        $productModel = new \App\Models\ProductModel();
        $product = $productModel->where('id', $id)->where('seller_id', $sellerId)->first();

        if (empty($product)) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        try {
            foreach (['preview_path', 'file_path'] as $pathField) {
                $rawValue = trim((string) ($product[$pathField] ?? ''));
                if ($rawValue === '') {
                    continue;
                }

                $candidatePaths = [];
                $decoded = json_decode($rawValue, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $decodedPath) {
                        $decodedPath = ltrim((string) $decodedPath, '/\\');
                        if ($decodedPath !== '') {
                            $candidatePaths[] = $decodedPath;
                        }
                    }
                } else {
                    $candidatePaths[] = ltrim($rawValue, '/\\');
                }

                foreach ($candidatePaths as $relativePath) {
                    if ($relativePath !== '' && str_starts_with($relativePath, 'uploads/')) {
                        $absolutePath = FCPATH . $relativePath;
                        if (is_file($absolutePath)) {
                            @unlink($absolutePath);
                        }
                    }
                }
            }

            $productModel->delete($id);
        } catch (\Throwable $e) {
            log_message('error', 'Product delete error: ' . $e->getMessage());
            return redirect()->to('/products')->with('error', 'Failed to delete product. Please try again.');
        }

        return redirect()->to('/products')->with('message', 'Product deleted successfully.');
    }
}
