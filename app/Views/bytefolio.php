<?= view('header') ?>
<?php $bytefolioBackUrl = previous_url() ?: base_url('dashboard'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByteFolio - Byte Market</title>
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
            background: #FFFFFF;
            color: #000000;
            line-height: 1.6;
        }

        body {
            padding-top: 88px;
        }

        /* === MAIN CONTAINER === */
        .bytefolio-wrapper {
            width: 75%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding: 8px 12px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #d9d9d9;
            color: #111111;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            width: fit-content;
        }

        .back-btn:hover {
            background: #f7f7f7;
            border-color: #c8c8c8;
        }

        .back-btn svg {
            width: 14px;
            height: 14px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* === PAGE TITLE === */
        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
        }

        .page-title .blue {
            color: #308BE5;
        }

        .page-title .green {
            color: #249E2F;
        }

        /* === PROFILE SECTION === */
        .profile-section {
            display: flex;
            gap: 32px;
            align-items: flex-start;
            margin-bottom: 48px;
            flex-wrap: wrap;
        }

        .bytefolio-profile-picture-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .bytefolio-profile-picture {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #F0F0F0;
            background: #F5F5F5;
        }

        .bytefolio-profile-edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #000000;
            color: #FFFFFF;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .bytefolio-profile-edit-btn:hover {
            background: #333333;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .profile-info {
            flex: 1;
            min-width: 300px;
        }

        .profile-name {
            font-size: 28px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .verified-badge {
            color: #308BE5;
            font-size: 18px;
        }

        .profile-title {
            font-size: 16px;
            color: #308BE5;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .profile-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 14px;
            color: #666666;
        }

        /* === FORM SECTION === */
        .form-section {
            margin-bottom: 48px;
            padding: 32px;
            background: #FAFAFA;
            border-radius: 12px;
        }

        .form-section-title {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 24px;
        }

        /* === FORM LAYOUT === */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px 16px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: #000000;
            background: #FFFFFF;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #249E2F;
            box-shadow: 0 0 0 3px rgba(36, 158, 47, 0.1);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999999;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            grid-column: 1 / -1;
        }

        /* === ABOUT ME SECTION === */
        .about-section {
            margin-bottom: 48px;
            padding: 32px;
            background: #FAFAFA;
            border-radius: 12px;
        }

        .about-section-title {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 16px;
        }

        .about-text {
            font-size: 14px;
            color: #666666;
            line-height: 1.8;
            border: 1px solid #E0E0E0;
            padding: 16px;
            border-radius: 8px;
            background: #FFFFFF;
            min-height: 120px;
            max-height: 200px;
            overflow-y: auto;
        }

        .about-text.editable {
            padding: 12px 16px;
        }

        /* === PRODUCTS SECTION === */
        .products-section {
            margin-bottom: 48px;
        }

        .products-title {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 24px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: #F5F5F5;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(48, 139, 229, 0.2);
        }

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, #E0E0E0 0%, #F5F5F5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .product-image-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-info {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .product-title {
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-seller {
            font-size: 12px;
            color: #999999;
            font-weight: 400;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .product-stars {
            display: flex;
            gap: 1px;
        }

        .star {
            color: #FFB800;
            font-size: 12px;
            line-height: 1;
        }

        .star.empty {
            color: rgba(255, 184, 0, 0.35);
        }

        .rating-count {
            font-size: 12px;
            color: #999999;
        }

        .empty-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 48px 20px;
            color: #999999;
        }

        .empty-products-icon {
            font-size: 48px;
            margin-bottom: 16px;
            color: #DDD;
        }

        /* === BUTTONS === */
        .button-group {
            display: flex;
            gap: 16px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 32px;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: #249E2F;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: #1e7a27;
            box-shadow: 0 4px 12px rgba(36, 158, 47, 0.3);
        }

        .btn-primary:focus-visible {
            outline: 3px solid #249E2F;
            outline-offset: 2px;
        }

        .btn-secondary {
            background: #FFFFFF;
            color: #000000;
            border: 1px solid #DDD;
        }

        .btn-secondary:hover {
            border-color: #000000;
            background: #F5F5F5;
        }

        /* === HIDDEN FILE INPUT === */
        .hidden-file-input {
            display: none;
        }

        /* === MODAL === */
        .crop-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .crop-modal.active {
            display: flex;
        }

        .crop-modal-content {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 32px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .crop-modal-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .crop-preview {
            width: 100%;
            height: 300px;
            border: 2px dashed #DDD;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F5F5F5;
        }

        .crop-buttons {
            display: flex;
            gap: 12px;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 768px) {
            .bytefolio-wrapper {
                padding: 24px 16px;
            }

            .page-title {
                font-size: 24px;
                margin-bottom: 32px;
            }

            .profile-section {
                gap: 20px;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-info {
                width: 100%;
            }

            .form-section,
            .about-section {
                padding: 20px 16px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 16px;
            }

            .button-group {
                flex-direction: column;
                justify-content: stretch;
            }

            .btn {
                width: 100%;
                padding: 14px 24px;
            }
        }

        @media (max-width: 480px) {
            .bytefolio-wrapper {
                padding: 16px 12px;
            }

            .page-title {
                font-size: 20px;
            }

            .bytefolio-profile-picture {
                width: 120px;
                height: 120px;
            }

            .bytefolio-profile-edit-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .profile-name {
                font-size: 20px;
            }

            .form-section-title,
            .products-title {
                font-size: 18px;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="bytefolio-wrapper">
        <a href="<?= esc($bytefolioBackUrl, 'attr') ?>" class="back-btn" aria-label="Go back">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
            Back
        </a>

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

        <!-- Profile Section -->
        <div class="profile-section">
            <div class="bytefolio-profile-picture-wrapper">
                <form id="profilePictureForm" method="POST" action="<?= base_url('bytefolio/upload_picture') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                <img 
                    src="<?= esc($user['profile_image'] ?? base_url('assets/images/default-avatar.svg')) ?>" 
                    alt="Profile picture of <?= esc($user['full_name'] ?? 'User') ?>" 
                    class="bytefolio-profile-picture"
                    id="profilePictureDisplay">
                <button type="button" class="bytefolio-profile-edit-btn" id="editProfileBtn" aria-label="Edit profile picture">
                    <i class="fas fa-edit"></i>
                </button>
                <input type="file" id="profilePictureInput" name="profile_picture" class="hidden-file-input" accept="image/*" aria-label="Upload new profile picture">
                </form>
            </div>

            <div class="profile-info">
                <h2 class="profile-name">
                    <span id="displayName"><?= esc($user['full_name'] ?? 'User') ?></span>
                    <i class="fas fa-check-circle verified-badge" title="Verified seller"></i>
                </h2>
                <p class="profile-title" id="displayTitle"><?= esc($user['bio'] ?? 'Digital Creator') ?></p>
                <div class="profile-details">
                    <div><i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i><span id="displayLocation"><?= esc($user['city'] ?? 'Manila') ?>, <?= esc($user['country'] ?? 'Philippines') ?></span></div>
                    <div><i class="fas fa-phone" style="margin-right: 8px;"></i><span id="displayPhone"><?= esc($user['phone'] ?? '+63 (0) 9876554241') ?></span></div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <form id="profileForm" method="POST" action="<?= base_url('bytefolio/update_profile') ?>">
            <?= csrf_field() ?>
            <div class="form-section">
                <h3 class="form-section-title">Edit Profile</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input 
                            type="text" 
                            id="country" 
                            name="country" 
                            placeholder="Philippines"
                            value="<?= esc($user['country'] ?? 'Philippines') ?>">
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input 
                            type="text" 
                            id="city" 
                            name="city" 
                            placeholder="Manila"
                            value="<?= esc($user['city'] ?? 'Manila') ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            placeholder="09876554241"
                            value="<?= esc($user['phone'] ?? '+63 (0) 9876554241') ?>">
                    </div>

                    <div class="form-group">
                        <label for="headline">Headline</label>
                        <input 
                            type="text" 
                            id="headline" 
                            name="headline" 
                            placeholder="e.g. Graphic Designer"
                            value="<?= esc($user['bio'] ?? 'Digital Creator') ?>">
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </form>

        <!-- About Me Section -->
        <div class="about-section">
            <h3 class="about-section-title">About me</h3>
            <form id="aboutForm" method="POST" action="<?= base_url('bytefolio/update_about') ?>">
                <?= csrf_field() ?>
                <div class="form-group">
                    <textarea 
                        id="aboutMe" 
                        name="about_me" 
                        placeholder="Write something about yourself..."
                        class="about-text editable"><?= esc($user['bio'] ?? '') ?></textarea>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>

        <!-- Most Sold Products Section -->
        <div class="products-section">
            <h3 class="products-title">Most Sold Products</h3>
            <div class="products-grid" id="productsGrid">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                            $productRating = (float) ($product['rating'] ?? 0.0);
                            $roundedHalf = round($productRating * 2) / 2;
                            if ($roundedHalf < 0) {
                                $roundedHalf = 0;
                            }
                            if ($roundedHalf > 5) {
                                $roundedHalf = 5;
                            }
                            $filledStars = (int) floor($roundedHalf);
                            $hasHalfStar = ((float) ($roundedHalf - $filledStars) === 0.5);
                            $emptyStars = 5 - $filledStars - ($hasHalfStar ? 1 : 0);
                        ?>
                        <div
                            class="product-card"
                            tabindex="0"
                            role="link"
                            data-url="<?= esc($product['detail_url'] ?? '#') ?>"
                            aria-label="<?= esc($product['title'] ?? 'Product') ?>">
                            <div class="product-image">
                                <?php if (!empty($product['thumbnail_url'])): ?>
                                    <img
                                        src="<?= esc($product['thumbnail_url']) ?>"
                                        alt="Thumbnail for <?= esc($product['title'] ?? 'Product') ?>"
                                        class="product-image-img"
                                        onerror="this.parentElement.textContent='🖼️';">
                                <?php else: ?>
                                    🖼️
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h4 class="product-title"><?= esc($product['title'] ?? 'Untitled Product') ?></h4>
                                <p class="product-seller"><?= esc($product['seller'] ?? 'You') ?></p>
                                <div class="product-rating">
                                    <div class="product-stars">
                                        <?php for ($i = 0; $i < $filledStars; $i++): ?>
                                            <span class="star" aria-hidden="true"><i class="fas fa-star"></i></span>
                                        <?php endfor; ?>
                                        <?php if ($hasHalfStar): ?>
                                            <span class="star" aria-hidden="true"><i class="fas fa-star-half-alt"></i></span>
                                        <?php endif; ?>
                                        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                            <span class="star empty" aria-hidden="true"><i class="far fa-star"></i></span>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-count"><?= number_format((float) ($product['rating'] ?? 0.0), 1) ?> (<?= (int) ($product['reviews'] ?? 0) ?>)</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-products">
                        <div class="empty-products-icon"><i class="fas fa-box-open"></i></div>
                        <p>No products published yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Crop Modal (placeholder - to be implemented) -->
    <div class="crop-modal" id="cropModal">
        <div class="crop-modal-content">
            <h3 class="crop-modal-title">Crop Your Profile Picture</h3>
            <div class="crop-preview" id="cropPreview">
                <img id="cropImage" src="" alt="Image to crop" style="max-width: 100%; max-height: 100%;">
            </div>
            <div class="crop-buttons">
                <button type="button" class="btn btn-secondary" id="cancelCropBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCropBtn">Save Picture</button>
            </div>
        </div>
    </div>

    <script>
        // === PROFILE PICTURE UPLOAD ===
        const editProfileBtn = document.getElementById('editProfileBtn');
        const profilePictureInput = document.getElementById('profilePictureInput');
        const profilePictureDisplay = document.getElementById('profilePictureDisplay');
        const profilePictureForm = document.getElementById('profilePictureForm');
        const cropModal = document.getElementById('cropModal');
        const cropImage = document.getElementById('cropImage');
        const cancelCropBtn = document.getElementById('cancelCropBtn');
        const saveCropBtn = document.getElementById('saveCropBtn');

        editProfileBtn.addEventListener('click', () => {
            profilePictureInput.click();
        });

        profilePictureInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    cropImage.src = event.target.result;
                    cropModal.classList.add('active');
                };
                reader.readAsDataURL(file);
            }
        });

        cancelCropBtn.addEventListener('click', () => {
            cropModal.classList.remove('active');
            profilePictureInput.value = '';
        });

        saveCropBtn.addEventListener('click', () => {
            const selectedFile = profilePictureInput.files[0];
            if (!selectedFile) {
                alert('Please select an image first.');
                return;
            }

            cropModal.classList.remove('active');
            profilePictureForm.submit();
        });

        // === PROFILE FORM SUBMISSION ===
        const profileForm = document.getElementById('profileForm');
        const countryInput = document.getElementById('country');
        const cityInput = document.getElementById('city');
        const phoneInput = document.getElementById('phone');
        const headlineInput = document.getElementById('headline');
        const displayName = document.getElementById('displayName');
        const displayTitle = document.getElementById('displayTitle');
        const displayLocation = document.getElementById('displayLocation');
        const displayPhone = document.getElementById('displayPhone');

        profileForm.addEventListener('submit', (e) => {
            // Update display
            displayTitle.textContent = headlineInput.value || 'Digital Creator';
            displayLocation.textContent = `${cityInput.value || 'Manila'}, ${countryInput.value || 'Philippines'}`;
            displayPhone.textContent = phoneInput.value || '09876554241';
        });

        // === ABOUT ME FORM SUBMISSION ===
        const aboutForm = document.getElementById('aboutForm');
        
        aboutForm.addEventListener('submit', (e) => {
            // Native form submit to backend route
        });

        // === PRODUCT CARD INTERACTIONS ===
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('click', function() {
                const targetUrl = this.getAttribute('data-url');
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            });

            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    card.click();
                }
            });
        });

        // === KEYBOARD NAVIGATION ===
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                cropModal.classList.remove('active');
            }
        });
    </script>

    <?= view('footer') ?>
</body>
</html>
