<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\ProductFeedbackModel;
use App\Models\ProductModel;

class Landing extends BaseController
{
    /**
     * Display the landing page
     * 
     * @return string
     */
    public function index(): string
    {
        return view('landing', [
            'topProducts' => $this->getTopProductsForLanding(),
        ]);
    }

    /**
     * Handle user registration/join action
     * Placeholder for future user registration logic
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function join()
    {
        // TODO: Implement user registration logic
        // - Validate user input
        // - Create user account
        // - Send verification email
        // - Redirect to dashboard or login
        
        // For now, redirect back to landing with a message
        return redirect()->to(base_url('landing'))
                         ->with('message', 'Join functionality coming soon!');
    }

    /**
     * Handle purchase/download action
     * Placeholder for future purchase logic
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function buy()
    {
        // TODO: Implement purchase/download logic
        // - Display product catalog
        // - Handle cart functionality
        // - Process payments
        // - Generate download links
        
        // For now, redirect back to landing with a message
        return redirect()->to(base_url('landing'))
                         ->with('message', 'Buy & Download functionality coming soon!');
    }

    private function getTopProductsForLanding(): array
    {
        $productModel = new ProductModel();
        $authModel = new AuthModel();
        $feedbackModel = new ProductFeedbackModel();

        $rows = $productModel->orderBy('created_at', 'DESC')->findAll(50);
        if (empty($rows)) {
            return [];
        }

        $productIds = array_values(array_filter(array_map(
            static fn(array $row): int => (int) ($row['id'] ?? 0),
            $rows
        ), static fn(int $id): bool => $id > 0));

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
                $sellerCache[$sellerId] = trim((string) ($sellerUser['full_name'] ?? 'ByteMarket Seller'));
            }

            $thumbPath = $this->extractPrimaryPath((string) ($row['preview_path'] ?? ''));
            $thumbUrl = $thumbPath !== '' ? base_url(ltrim($thumbPath, '/')) : null;
            $productId = (int) ($row['id'] ?? 0);

            $products[] = [
                'id' => $productId,
                'title' => trim((string) ($row['title'] ?? 'Untitled Product')),
                'seller' => $sellerCache[$sellerId] !== '' ? $sellerCache[$sellerId] : 'ByteMarket Seller',
                'thumbnail_url' => $thumbUrl,
                'category' => trim((string) ($row['category'] ?? 'Digital Product')),
                'price' => (float) ($row['price'] ?? 0),
                'rating' => (float) ($ratingSummaryMap[$productId]['average'] ?? 0.0),
                'reviews' => (int) ($ratingSummaryMap[$productId]['count'] ?? 0),
            ];
        }

        usort($products, static function (array $left, array $right): int {
            $ratingCompare = ($right['rating'] <=> $left['rating']);
            if ($ratingCompare !== 0) {
                return $ratingCompare;
            }

            $reviewCompare = ($right['reviews'] <=> $left['reviews']);
            if ($reviewCompare !== 0) {
                return $reviewCompare;
            }

            return $right['id'] <=> $left['id'];
        });

        return array_slice($products, 0, 30);
    }

    private function extractPrimaryPath(string $rawPath): string
    {
        $rawPath = trim($rawPath);
        if ($rawPath === '') {
            return '';
        }

        $decoded = json_decode($rawPath, true);
        if (is_array($decoded)) {
            foreach (array_reverse($decoded) as $path) {
                $path = str_replace('\/', '/', trim((string) $path));
                if ($path !== '') {
                    return $path;
                }
            }

            return '';
        }

        return str_replace('\/', '/', $rawPath);
    }
}
