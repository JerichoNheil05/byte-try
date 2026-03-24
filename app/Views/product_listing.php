<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing - Byte Market Seller Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f7f7f7;
            color: #111111;
            line-height: 1.5;
        }

        body {
            padding-top: 80px;
        }

        .products-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 36px 20px 40px;
        }

        .products-back {
            margin-bottom: 12px;
        }

        .products-back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 36px;
            padding: 0 14px;
            border-radius: 9px;
            border: 1px solid #dfe6ef;
            background: #ffffff;
            color: #1f2d3d;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .products-back-link:hover {
            background: #f0f6ff;
            border-color: #c4d9f3;
            color: #173f70;
        }

        .products-back-link:focus-visible {
            outline: 2px solid #308be5;
            outline-offset: 2px;
        }

        .products-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .products-title {
            font-size: 34px;
            line-height: 1;
            font-weight: 700;
            color: #121212;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 36px;
            padding: 0 14px;
            border: none;
            border-radius: 8px;
            background: #249e2f;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #efefef;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
            background: #ffffff;
        }

        .products-table thead th {
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #2a2a2a;
            padding: 14px 16px;
            background: #fbfbfb;
            border-bottom: 1px solid #efefef;
        }

        .products-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
            font-size: 14px;
        }

        .products-table tbody tr:last-child td {
            border-bottom: none;
        }

        .product-cell {
            width: 100%;
            border: none;
            background: transparent;
            padding: 0;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            font-family: inherit;
            color: inherit;
        }

        .product-cell:focus-visible {
            outline: 2px solid #308be5;
            outline-offset: 2px;
            border-radius: 8px;
        }

        .product-thumb,
        .product-thumb-fallback {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            background: #f0f0f0;
            border: 1px solid #e8e8e8;
            object-fit: cover;
            flex: 0 0 auto;
        }

        .product-thumb-fallback {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #8a8a8a;
            font-size: 20px;
        }

        .product-meta {
            min-width: 0;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #151515;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 280px;
        }

        .product-sub {
            margin-top: 2px;
            font-size: 12px;
            color: #8a8a8a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 280px;
        }

        .product-category,
        .product-date {
            color: #666666;
            white-space: nowrap;
        }

        .product-price {
            color: #249e2f;
            font-weight: 700;
            white-space: nowrap;
        }

        .action-col {
            width: 88px;
        }

        .action-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .action-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e4e4e4;
            background: #ffffff;
            color: #333333;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .action-icon:hover {
            background: #249e2f;
            border-color: #249e2f;
            color: #ffffff;
        }

        .delete-btn {
            background: #ffffff;
            border-color: #f0d7d7;
            color: #c83535;
        }

        .delete-btn:hover {
            background: #c83535;
            border-color: #c83535;
            color: #ffffff;
        }

        .delete-form {
            display: inline-flex;
            margin: 0;
        }

        .empty-state {
            text-align: center;
            padding: 52px 16px;
            color: #6d6d6d;
            font-size: 15px;
        }

        .bottom-cta {
            margin-top: 22px;
            display: flex;
            justify-content: center;
        }

        .add-product-btn {
            background: #308be5;
            color: #ffffff;
            border: none;
            height: 42px;
            padding: 0 26px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.3px;
        }

        .add-product-btn:hover {
            background: #256ec0;
        }

        .product-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.36);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1200;
        }

        .product-modal.open {
            display: flex;
        }

        .product-modal-card {
            width: min(920px, 100%);
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
            padding: 26px 30px 26px;
            position: relative;
        }

        .product-modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 34px;
            height: 34px;
            border: 1px solid #e6e6e6;
            border-radius: 50%;
            background: #ffffff;
            color: #666666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .product-modal-gallery-wrap {
            position: relative;
            margin-bottom: 8px;
        }

        .product-modal-gallery {
            width: 100%;
            min-height: 190px;
        }

        .product-modal-preview {
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 4px;
            object-fit: cover;
            background: #e9edf2;
            border: 1px solid #ececec;
            display: none;
        }

        .product-modal-preview.is-active {
            display: block;
        }

        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid #d8d8d8;
            background: rgba(255, 255, 255, 0.92);
            color: #333333;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
        }

        .carousel-control:hover {
            background: #ffffff;
            border-color: #bdbdbd;
        }

        .carousel-prev {
            left: 8px;
        }

        .carousel-next {
            right: 8px;
        }

        .carousel-control.hidden {
            display: none;
        }

        .product-modal-dots {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin: 2px 0 12px;
        }

        .product-modal-dots span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #d1d1d1;
        }

        .product-modal-dots span.active {
            background: #4f92d8;
        }

        .product-modal-price {
            color: #3181d2;
            font-size: 20px;
            line-height: 1;
            font-weight: 700;
            margin-top: 8px;
        }

        .product-modal-name {
            margin-top: 4px;
            font-size: 20px;
            line-height: 1.1;
            font-weight: 700;
            color: #111111;
        }

        .product-modal-byline {
            margin-top: 2px;
            font-size: 16px;
            line-height: 1.1;
            color: #2a2a2a;
        }

        .product-modal-byline .seller {
            color: #249e2f;
            font-weight: 700;
        }

        .product-modal-description-title {
            margin-top: 18px;
            font-size: 18px;
            line-height: 1.1;
            font-weight: 700;
            color: #111111;
        }

        .product-modal-description {
            margin-top: 5px;
            font-size: 14px;
            line-height: 1.25;
            color: #2d2d2d;
        }

        .product-modal-text-content {
            width: 70%;
            min-width: 0;
        }

        .product-modal-actions {
            margin-top: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-modal-edit,
        .product-modal-back {
            height: 34px;
            padding: 0 18px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .product-modal-edit {
            background: #249e2f;
            border: 1px solid #249e2f;
            color: #ffffff;
        }

        .product-modal-back {
            background: #ffffff;
            border: 1px solid #249e2f;
            color: #249e2f;
        }

        .confirm-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1300;
        }

        .confirm-modal.open {
            display: flex;
        }

        .confirm-modal-card {
            width: min(460px, 100%);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.2);
            padding: 22px 20px;
        }

        .confirm-modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #121212;
            line-height: 1.1;
        }

        .confirm-modal-text {
            margin-top: 8px;
            font-size: 14px;
            color: #4a4a4a;
            line-height: 1.45;
        }

        .confirm-modal-actions {
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .confirm-modal-btn {
            height: 36px;
            padding: 0 16px;
            border-radius: 8px;
            border: 1px solid transparent;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .confirm-cancel-btn {
            background: #ffffff;
            border-color: #d8d8d8;
            color: #2d2d2d;
        }

        .confirm-delete-btn {
            background: #c83535;
            border-color: #c83535;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 72px;
            }

            .products-wrapper {
                padding: 24px 12px 28px;
            }

            .products-title {
                font-size: 28px;
            }

            .filter-chip {
                height: 34px;
                padding: 0 12px;
                font-size: 13px;
            }

            .product-modal-card {
                border-radius: 18px;
                padding: 22px 16px 20px;
            }

            .product-modal-gallery {
                min-height: 140px;
            }

            .product-modal-price,
            .product-modal-name,
            .product-modal-byline,
            .product-modal-description-title,
            .product-modal-description {
                font-size: 14px;
            }

            .product-modal-text-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php $productListingBackUrl = previous_url() ?: base_url('dashboard'); ?>
    <?php $modalSellerName = trim((string) (session()->get('fullName') ?? session()->get('full_name') ?? 'Seller')); ?>
    <div class="products-wrapper">
        <div class="products-back">
            <a class="products-back-link" href="<?= esc($productListingBackUrl, 'attr') ?>" aria-label="Go back">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <span>Back</span>
            </a>
        </div>

        <?php if (session()->getFlashdata('message')): ?>
            <div style="margin-bottom: 16px; padding: 12px 14px; border: 1px solid #c3e6cb; border-radius: 8px; background: #d4edda; color: #155724;">
                <?= esc(session()->getFlashdata('message')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div style="margin-bottom: 16px; padding: 12px 14px; border: 1px solid #f5c6cb; border-radius: 8px; background: #f8d7da; color: #721c24;">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="products-header">
            <h1 class="products-title">Products</h1>
            <a class="filter-chip" href="<?= base_url('products?category=all') ?>">
                <?= esc(ucfirst($selected_category ?? 'all')) ?>
                <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </a>
        </div>

        <div class="table-wrap">
            <table class="products-table" role="table" aria-label="Product listing">
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>Category</th>
                        <th>Published</th>
                        <th>Price</th>
                        <th class="action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php
                                $modalThumbUrls = [];
                                if (!empty($product['thumbnail_paths']) && is_array($product['thumbnail_paths'])) {
                                    foreach ($product['thumbnail_paths'] as $thumbPath) {
                                        $thumbPath = trim((string) $thumbPath);
                                        if ($thumbPath !== '') {
                                            $modalThumbUrls[] = base_url(ltrim($thumbPath, '/'));
                                        }
                                    }
                                }
                            ?>
                            <tr>
                                <td>
                                    <button
                                        type="button"
                                        class="product-cell js-open-product-modal"
                                        data-id="<?= (int) ($product['id'] ?? 0) ?>"
                                        data-name="<?= esc($product['product_name'] ?? 'Untitled Product', 'attr') ?>"
                                        data-description="<?= esc($product['description'] ?? '', 'attr') ?>"
                                        data-price="<?= number_format((float) ($product['price'] ?? 0), 2, '.', '') ?>"
                                        data-thumb="<?= esc(!empty($product['thumbnail_path']) ? base_url(ltrim((string) $product['thumbnail_path'], '/')) : '', 'attr') ?>"
                                        data-thumbs="<?= esc(json_encode($modalThumbUrls, JSON_UNESCAPED_SLASHES), 'attr') ?>"
                                        data-seller="<?= esc($modalSellerName, 'attr') ?>"
                                    >
                                        <?php if (!empty($product['thumbnail_path'])): ?>
                                            <img
                                                src="<?= base_url(ltrim((string) $product['thumbnail_path'], '/')) ?>"
                                                alt="<?= esc($product['product_name'] ?? 'Product') ?>"
                                                class="product-thumb"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
                                            >
                                            <span class="product-thumb-fallback" style="display:none;"><i class="fas fa-image"></i></span>
                                        <?php else: ?>
                                            <span class="product-thumb-fallback"><i class="fas fa-image"></i></span>
                                        <?php endif; ?>

                                        <div class="product-meta">
                                            <div class="product-name"><?= esc($product['product_name'] ?? 'Untitled Product') ?></div>
                                            <div class="product-sub"><?= esc($product['description'] ?? '') ?></div>
                                        </div>
                                    </button>
                                </td>
                                <td class="product-category"><?= esc($product['category'] ?? 'General') ?></td>
                                <td class="product-date"><?= esc($product['published_display'] ?? 'N/A') ?></td>
                                <td class="product-price">₱<?= number_format((float) ($product['price'] ?? 0), 2) ?></td>
                                <td>
                                    <div class="action-group">
                                        <a href="<?= base_url('products/edit/' . ($product['id'] ?? 0)) ?>" class="action-icon" aria-label="Edit <?= esc($product['product_name'] ?? 'product') ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form method="post" action="<?= base_url('products/delete/' . ($product['id'] ?? 0)) ?>" class="delete-form" data-product-name="<?= esc($product['product_name'] ?? 'this product', 'attr') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="action-icon delete-btn" aria-label="Delete <?= esc($product['product_name'] ?? 'product') ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">No products yet. Create your first product to get started.</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="bottom-cta">
            <a href="<?= base_url('products/add') ?>" class="add-product-btn">ADD PRODUCT</a>
        </div>
    </div>

    <div class="product-modal" id="productSpecModal" aria-hidden="true">
        <div class="product-modal-card" role="dialog" aria-modal="true" aria-label="Product specification">
            <button type="button" class="product-modal-close" id="closeProductSpecModal" aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>

            <div class="product-modal-gallery-wrap">
                <button type="button" class="carousel-control carousel-prev hidden" id="modalPrevSlide" aria-label="Previous image">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div id="modalGallery" class="product-modal-gallery"></div>

                <button type="button" class="carousel-control carousel-next hidden" id="modalNextSlide" aria-label="Next image">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="product-modal-dots" id="modalGalleryDots" aria-hidden="true"></div>

            <div class="product-modal-text-content">
                <div class="product-modal-price" id="modalProductPrice">₱0.00</div>
                <div class="product-modal-name" id="modalProductName">Untitled Product</div>
                <div class="product-modal-byline">by <span class="seller" id="modalProductSeller">Seller</span></div>

                <div class="product-modal-description-title">Description:</div>
                <div class="product-modal-description" id="modalProductDescription">No description provided.</div>

                <div class="product-modal-actions">
                    <a href="#" class="product-modal-edit" id="modalEditProductBtn">Edit Product</a>
                    <button type="button" class="product-modal-back" id="modalBackBtn">Back</button>
                </div>
            </div>
        </div>
    </div>

    <div class="confirm-modal" id="deleteConfirmModal" aria-hidden="true">
        <div class="confirm-modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle" aria-describedby="deleteConfirmText">
            <h2 class="confirm-modal-title" id="deleteConfirmTitle">Delete product?</h2>
            <p class="confirm-modal-text" id="deleteConfirmText">
                Are you sure you want to delete <strong id="deleteConfirmProductName">this product</strong>? This action cannot be undone.
            </p>
            <div class="confirm-modal-actions">
                <button type="button" class="confirm-modal-btn confirm-cancel-btn" id="deleteConfirmCancelBtn">Cancel</button>
                <button type="button" class="confirm-modal-btn confirm-delete-btn" id="deleteConfirmSubmitBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const productModal = document.getElementById('productSpecModal');
        const closeProductSpecModalBtn = document.getElementById('closeProductSpecModal');
        const modalBackBtn = document.getElementById('modalBackBtn');
        const modalProductPrice = document.getElementById('modalProductPrice');
        const modalProductName = document.getElementById('modalProductName');
        const modalProductSeller = document.getElementById('modalProductSeller');
        const modalProductDescription = document.getElementById('modalProductDescription');
        const modalEditProductBtn = document.getElementById('modalEditProductBtn');
        const modalGallery = document.getElementById('modalGallery');
        const modalGalleryDots = document.getElementById('modalGalleryDots');
        const modalPrevSlideBtn = document.getElementById('modalPrevSlide');
        const modalNextSlideBtn = document.getElementById('modalNextSlide');
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const deleteConfirmProductName = document.getElementById('deleteConfirmProductName');
        const deleteConfirmCancelBtn = document.getElementById('deleteConfirmCancelBtn');
        const deleteConfirmSubmitBtn = document.getElementById('deleteConfirmSubmitBtn');
        const defaultPreview = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23e9edf2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%238a8a8a%22 font-size=%2232%22 font-family=%22Arial,sans-serif%22%3EProduct%20Preview%3C/text%3E%3C/svg%3E';
        let modalThumbnails = [];
        let currentSlideIndex = 0;
        let pendingDeleteForm = null;

        const renderModalCarousel = () => {
            modalGallery.innerHTML = '';
            modalGalleryDots.innerHTML = '';

            modalThumbnails.forEach((thumbnail, index) => {
                const image = document.createElement('img');
                image.className = `product-modal-preview ${index === currentSlideIndex ? 'is-active' : ''}`;
                image.src = thumbnail;
                image.alt = `Product preview ${index + 1}`;
                image.onerror = () => {
                    image.src = defaultPreview;
                };
                modalGallery.appendChild(image);

                const dot = document.createElement('span');
                if (index === currentSlideIndex) {
                    dot.classList.add('active');
                }
                dot.style.cursor = 'pointer';
                dot.addEventListener('click', () => {
                    currentSlideIndex = index;
                    renderModalCarousel();
                });
                modalGalleryDots.appendChild(dot);
            });

            const multiSlide = modalThumbnails.length >= 2;
            modalPrevSlideBtn.classList.toggle('hidden', !multiSlide);
            modalNextSlideBtn.classList.toggle('hidden', !multiSlide);
            modalGalleryDots.style.display = multiSlide ? 'flex' : 'none';
        };

        const goToNextSlide = () => {
            if (modalThumbnails.length < 2) {
                return;
            }

            currentSlideIndex = (currentSlideIndex + 1) % modalThumbnails.length;
            renderModalCarousel();
        };

        const goToPreviousSlide = () => {
            if (modalThumbnails.length < 2) {
                return;
            }

            currentSlideIndex = (currentSlideIndex - 1 + modalThumbnails.length) % modalThumbnails.length;
            renderModalCarousel();
        };

        const openProductSpecModal = (trigger) => {
            const productId = trigger.getAttribute('data-id') || '0';
            const productName = trigger.getAttribute('data-name') || 'Untitled Product';
            const productDescription = trigger.getAttribute('data-description') || 'No description provided.';
            const productPrice = trigger.getAttribute('data-price') || '0.00';
            const productThumb = trigger.getAttribute('data-thumb') || defaultPreview;
            const productSeller = trigger.getAttribute('data-seller') || 'Seller';
            const rawThumbs = trigger.getAttribute('data-thumbs') || '[]';

            let thumbnails = [];
            try {
                const parsed = JSON.parse(rawThumbs);
                if (Array.isArray(parsed)) {
                    thumbnails = parsed.filter((value) => typeof value === 'string' && value.trim() !== '');
                } else if (typeof parsed === 'string' && parsed.trim() !== '') {
                    thumbnails = [parsed.trim()];
                }
            } catch (error) {
                const normalized = rawThumbs
                    .replaceAll('&quot;', '"')
                    .replace(/^\[|\]$/g, '')
                    .trim();

                if (normalized !== '') {
                    thumbnails = normalized
                        .split(',')
                        .map((value) => value.trim().replace(/^"|"$/g, ''))
                        .filter((value) => value !== '');
                }
            }

            if (thumbnails.length === 0) {
                thumbnails = [productThumb || defaultPreview];
            }

            modalThumbnails = thumbnails;
            currentSlideIndex = 0;

            modalProductPrice.textContent = `₱${Number(productPrice).toFixed(2)}`;
            modalProductName.textContent = productName;
            modalProductSeller.textContent = productSeller;
            modalProductDescription.textContent = productDescription;
            modalEditProductBtn.href = `<?= base_url('products/edit') ?>/${productId}`;

            renderModalCarousel();

            productModal.classList.add('open');
            productModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        const closeProductSpecModal = () => {
            productModal.classList.remove('open');
            productModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        };

        const openDeleteConfirmModal = (form) => {
            pendingDeleteForm = form;
            const productName = form.getAttribute('data-product-name') || 'this product';
            deleteConfirmProductName.textContent = productName;
            deleteConfirmModal.classList.add('open');
            deleteConfirmModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        const closeDeleteConfirmModal = () => {
            deleteConfirmModal.classList.remove('open');
            deleteConfirmModal.setAttribute('aria-hidden', 'true');
            pendingDeleteForm = null;
            document.body.style.overflow = '';
        };

        document.querySelectorAll('.js-open-product-modal').forEach((button) => {
            button.addEventListener('click', () => openProductSpecModal(button));
        });

        closeProductSpecModalBtn.addEventListener('click', closeProductSpecModal);
        modalBackBtn.addEventListener('click', closeProductSpecModal);
        modalPrevSlideBtn.addEventListener('click', goToPreviousSlide);
        modalNextSlideBtn.addEventListener('click', goToNextSlide);

        document.querySelectorAll('.delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                openDeleteConfirmModal(form);
            });
        });

        deleteConfirmCancelBtn.addEventListener('click', closeDeleteConfirmModal);
        deleteConfirmSubmitBtn.addEventListener('click', () => {
            if (pendingDeleteForm) {
                pendingDeleteForm.submit();
            }
        });

        productModal.addEventListener('click', (event) => {
            if (event.target === productModal) {
                closeProductSpecModal();
            }
        });

        deleteConfirmModal.addEventListener('click', (event) => {
            if (event.target === deleteConfirmModal) {
                closeDeleteConfirmModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowRight' && productModal.classList.contains('open')) {
                goToNextSlide();
            }

            if (event.key === 'ArrowLeft' && productModal.classList.contains('open')) {
                goToPreviousSlide();
            }

            if (event.key === 'Escape' && productModal.classList.contains('open')) {
                closeProductSpecModal();
            }

            if (event.key === 'Escape' && deleteConfirmModal.classList.contains('open')) {
                closeDeleteConfirmModal();
            }
        });
    </script>

    <?= view('footer') ?>
</body>
</html>
