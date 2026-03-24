<?= view('header') ?>
<?php $assetModeOld = strtolower((string) old('asset_delivery_type', 'file')); ?>
<?php if (!in_array($assetModeOld, ['file', 'url'], true)) { $assetModeOld = 'file'; } ?>
<?php $addProductBackUrl = previous_url() ?: base_url('products'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products - Byte Market</title>
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
            padding-top: 80px;
        }

        /* === MAIN CONTAINER === */
        .add-products-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
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
            color: #249E2F;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* === FORM SECTION === */
        .form-section {
            margin-bottom: 32px;
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
            display: block;
        }

        .section-description {
            font-size: 13px;
            color: #666666;
            margin-bottom: 16px;
            display: block;
        }

        /* === FORM INPUTS === */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 12px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group input[type="url"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: #000000;
            background: #F5F5F5;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="number"]:focus,
        .form-group input[type="url"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            background: #FFFFFF;
            border-color: #249E2F;
            box-shadow: 0 0 0 3px rgba(36, 158, 47, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 140px;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .form-group textarea::placeholder {
            color: #999999;
        }

        .form-group input::placeholder {
            color: #999999;
        }

        /* === THUMBNAIL SECTION === */
        .thumbnail-section {
            margin-bottom: 32px;
        }

        .thumbnail-label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
        }

        .thumbnail-hint {
            font-size: 13px;
            color: #666666;
            margin-bottom: 16px;
            display: block;
        }

        .upload-box {
            border: 2px dashed #E0E0E0;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #FAFAFA;
        }

        .upload-box:hover {
            border-color: #249E2F;
            background: #F5FFF6;
        }

        .upload-box.dragover {
            border-color: #249E2F;
            background: #F0FFF4;
        }

        .upload-icon {
            font-size: 40px;
            color: #249E2F;
            margin-bottom: 12px;
        }

        .upload-text {
            font-size: 14px;
            color: #249E2F;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .upload-input {
            display: none;
        }

        .image-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .file-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .file-preview-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            background: #FFFFFF;
        }

        .file-preview-thumb {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #F5F5F5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .file-preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-preview-meta {
            min-width: 0;
            flex: 1;
        }

        .file-preview-name {
            font-size: 13px;
            font-weight: 600;
            color: #000000;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-preview-size {
            font-size: 12px;
            color: #666666;
            margin-top: 2px;
        }

        .image-preview {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            background: #F5F5F5;
            border: 1px solid #E0E0E0;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            background: rgba(0, 0, 0, 0.6);
            color: #FFFFFF;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .image-remove:hover {
            background: rgba(0, 0, 0, 0.85);
        }

        /* === ASSETS SECTION === */
        .assets-section {
            margin-bottom: 32px;
        }

        .asset-option {
            margin-bottom: 24px;
            padding: 16px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            background: #FAFAFA;
        }

        .asset-option.active {
            background: #F0FFF4;
            border-color: #249E2F;
        }

        .asset-header {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .toggle-switch {
            position: relative;
            width: 50px;
            height: 28px;
            background: #E0E0E0;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 2px;
            display: flex;
            align-items: center;
        }

        .toggle-switch.active {
            background: #249E2F;
        }

        .toggle-switch-slider {
            width: 24px;
            height: 24px;
            background: #FFFFFF;
            border-radius: 50%;
            transition: all 0.3s ease;
            position: absolute;
            left: 2px;
        }

        .toggle-switch.active .toggle-switch-slider {
            left: 24px;
        }

        .asset-label {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin: 0;
        }

        .asset-input-group {
            margin-top: 16px;
            display: none;
        }

        .asset-option.active .asset-input-group {
            display: block;
        }

        .asset-input-group input,
        .asset-input-group .upload-box {
            margin-top: 12px;
        }

        /* === PRICE SECTION === */
        .price-section {
            margin-bottom: 32px;
        }

        .price-label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 12px;
        }

        .price-input-wrapper {
            position: relative;
            width: 180px;
        }

        .price-currency {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            pointer-events: none;
        }

        .price-input-wrapper input {
            padding-left: 36px;
        }

        /* === BUTTONS SECTION === */
        .button-group {
            display: flex;
            gap: 16px;
            margin-top: 40px;
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
            border: 2px solid #000000;
        }

        .btn-secondary:hover {
            background: #F5F5F5;
            border-color: #000000;
        }

        .btn-secondary:focus-visible {
            outline: 3px solid #000000;
            outline-offset: 2px;
        }

        a.btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        /* === VALIDATION MODAL === */
        .validation-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.48);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 2100;
        }

        .validation-modal.active {
            display: flex;
        }

        .validation-modal-card {
            width: min(560px, 100%);
            background: #FFFFFF;
            border-radius: 14px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.22);
            border: 1px solid #EAEAEA;
            overflow: hidden;
        }

        .validation-modal-head {
            padding: 16px 20px;
            background: #1e293b;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .validation-modal-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .validation-modal-close {
            border: none;
            background: transparent;
            color: #FFFFFF;
            font-size: 20px;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            cursor: pointer;
        }

        .validation-modal-close:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .validation-modal-body {
            padding: 18px 20px 8px;
        }

        .validation-modal-desc {
            margin: 0 0 12px;
            font-size: 14px;
            color: #374151;
        }

        .validation-issues {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 280px;
            overflow: auto;
        }

        .validation-issues button {
            width: 100%;
            text-align: left;
            border: 1px solid #E3E3E3;
            border-radius: 8px;
            background: #F8FAFC;
            color: #1F2937;
            font-size: 14px;
            padding: 10px 12px;
            cursor: pointer;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .validation-issues button:hover {
            border-color: #249E2F;
            background: #F2FFF4;
        }

        .validation-modal-actions {
            padding: 14px 20px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .field-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
            background: #fff7f7 !important;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 768px) {
            .add-products-wrapper {
                padding: 24px 16px;
            }

            .page-title {
                font-size: 24px;
                margin-bottom: 32px;
            }

            .form-group input,
            .form-group textarea {
                font-size: 16px;
            }

            .image-preview-container {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 12px;
            }

            .button-group {
                flex-direction: column;
                gap: 12px;
            }

            .btn {
                width: 100%;
                padding: 14px 24px;
            }

            .price-input-wrapper {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .add-products-wrapper {
                padding: 20px 12px;
            }

            .page-title {
                font-size: 20px;
                margin-bottom: 24px;
            }

            .form-section-title {
                font-size: 14px;
            }

            .image-preview-container {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }

            .upload-box {
                padding: 30px 12px;
            }

            .upload-icon {
                font-size: 32px;
            }

            .upload-text {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="add-products-wrapper">
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

        <?php if (session()->getFlashdata('errors')): ?>
            <div style="margin-bottom: 16px; padding: 12px 14px; border: 1px solid #f5c6cb; border-radius: 8px; background: #f8d7da; color: #721c24;">
                <ul style="margin: 0; padding-left: 18px;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <a href="<?= esc($addProductBackUrl, 'attr') ?>" class="back-btn" aria-label="Go back">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
            Back
        </a>

        <!-- Page Title -->
        <h1 class="page-title">
            <i class="fas fa-cube"></i> Add Products
        </h1>
        <p style="margin: 8px 0 20px; color: #5f6b7a; font-size: 14px;">
            Category is automatically classified from your product content when you publish.
        </p>

        <!-- Add Products Form -->
        <form id="addProductsForm" method="POST" action="<?= base_url('products/save') ?>" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            
            <!-- Product Name -->
            <div class="form-section">
                <div class="form-group">
                    <label for="productName">Product Name</label>
                    <input 
                        type="text" 
                        id="productName" 
                        name="product_name" 
                        placeholder="Enter product name"
                        value="<?= esc(old('product_name')) ?>"
                        required
                        maxlength="200">
                </div>
            </div>

            <!-- Product Description -->
            <div class="form-section">
                <div class="form-group">
                    <label for="productDescription">Product Description</label>
                    <span class="section-description">Provide a full description of product you are selling</span>
                    <textarea 
                        id="productDescription" 
                        name="product_description" 
                        placeholder="Describe your product in detail. Include features, use cases, and any important details buyers should know..."
                        required
                        maxlength="5000"><?= esc(old('product_description')) ?></textarea>
                </div>
            </div>

            <!-- Product Feature -->
            <div class="form-section">
                <div class="form-group">
                    <label for="productFeature">Product Feature</label>
                    <span class="section-description">List key features, highlights, and what makes this product valuable.</span>
                    <textarea
                        id="productFeature"
                        name="product_feature"
                        placeholder="Example: 10 ready-to-use slides, fully editable elements, modern layouts, and free icon packs..."
                        required
                        maxlength="5000"><?= esc(old('product_feature')) ?></textarea>
                </div>
            </div>

            <!-- How It Works -->
            <div class="form-section">
                <div class="form-group">
                    <label for="howItWorks">How it works</label>
                    <span class="section-description">Explain what buyers receive and how they can access/use the product after purchase.</span>
                    <textarea
                        id="howItWorks"
                        name="how_it_works"
                        placeholder="Example: After successful payment, buyers can download the uploaded assets and access the redirect link from their purchase page."
                        required
                        maxlength="5000"><?= esc(old('how_it_works')) ?></textarea>
                </div>
            </div>

            <!-- Thumbnail Section -->
            <div class="thumbnail-section">
                <label class="thumbnail-label">Thumbnail</label>
                <span class="thumbnail-hint">Maximum of 10. Used as representation for your selling product.</span>
                
                <div class="upload-box" id="uploadBox">
                    <div class="upload-icon">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="upload-text">
                        <i class="fas fa-plus"></i> Add images
                    </div>
                    <input 
                        type="file" 
                        id="thumbnailInput" 
                        name="thumbnails[]" 
                        class="upload-input" 
                        accept="image/*" 
                        multiple
                            required
                        aria-label="Upload product thumbnail images">
                </div>

                <div class="image-preview-container" id="imagePreviewContainer"></div>
            </div>

            <!-- Assets Section -->
            <div class="assets-section">
                <div class="form-section-title">Assets</div>
                <span class="section-description">Choose how buyers will access your product after payment: URL or file upload.</span>
                <input type="hidden" name="asset_delivery_type" id="assetDeliveryType" value="<?= esc($assetModeOld, 'attr') ?>">

                <!-- Redirect to URL Option -->
                <div class="asset-option" id="redirectOption">
                    <div class="asset-header">
                        <button type="button" class="toggle-switch" id="redirectToggle" aria-label="Toggle redirect to URL">
                            <span class="toggle-switch-slider"></span>
                        </button>
                        <label class="asset-label">Redirect to URL</label>
                    </div>
                    <div class="asset-input-group">
                        <input 
                            type="url" 
                            name="redirect_url" 
                            placeholder="Add URL where buyer can access other purchase"
                            value="<?= esc(old('redirect_url')) ?>"
                            aria-label="Enter redirect URL">
                    </div>
                </div>

                <!-- Upload File Option -->
                <div class="asset-option" id="uploadOption">
                    <div class="asset-header">
                        <button type="button" class="toggle-switch" id="uploadToggle" aria-label="Toggle file upload">
                            <span class="toggle-switch-slider"></span>
                        </button>
                        <label class="asset-label">Upload File</label>
                    </div>
                    <div class="asset-input-group">
                        <div class="upload-box" id="fileUploadBox">
                            <div class="upload-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="upload-text">
                                <i class="fas fa-plus"></i> Upload File
                            </div>
                            <input 
                                type="file" 
                                name="product_files[]" 
                                class="upload-input" 
                                id="fileInput"
                                multiple
                                aria-label="Upload product files">
                        </div>
                        <div class="file-preview-container" id="filePreviewContainer"></div>
                    </div>
                </div>
            </div>

            <!-- Price Section -->
            <div class="price-section">
                <label class="price-label">Price</label>
                <div class="price-input-wrapper">
                    <span class="price-currency">P</span>
                    <input 
                        type="number" 
                        name="price" 
                        placeholder="0.00" 
                        value="<?= esc(old('price')) ?>"
                        step="0.01" 
                        min="0"
                        required
                        aria-label="Product price in Philippine Pesos">
                </div>
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <a href="<?= base_url('products') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Previous
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save and Publish
                </button>
                <button type="reset" class="btn btn-secondary">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <div class="validation-modal" id="validationModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="validationModalTitle">
        <div class="validation-modal-card">
            <div class="validation-modal-head">
                <h2 class="validation-modal-title" id="validationModalTitle">Please complete required fields</h2>
                <button type="button" class="validation-modal-close" id="validationModalClose" aria-label="Close validation list">×</button>
            </div>
            <div class="validation-modal-body">
                <p class="validation-modal-desc">Click any item below to jump directly to that field.</p>
                <ul class="validation-issues" id="validationIssueList"></ul>
            </div>
            <div class="validation-modal-actions">
                <button type="button" class="btn btn-primary" id="validationModalOk">Got it</button>
            </div>
        </div>
    </div>

    <script>
        // ==== THUMBNAIL UPLOAD HANDLING ====
        const uploadBox = document.getElementById('uploadBox');
        const thumbnailInput = document.getElementById('thumbnailInput');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const files = [];

        function syncThumbnailInputFiles() {
            const dataTransfer = new DataTransfer();
            files.forEach((file) => dataTransfer.items.add(file));
            thumbnailInput.files = dataTransfer.files;
        }

        // Click to upload
        uploadBox.addEventListener('click', () => thumbnailInput.click());

        // Handle file selection
        thumbnailInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        // Drag and drop
        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.classList.add('dragover');
        });

        uploadBox.addEventListener('dragleave', () => {
            uploadBox.classList.remove('dragover');
        });

        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        function handleFiles(fileList) {
            // Limit to 10 files total
            if (files.length >= 10) {
                alert('Maximum 10 images allowed');
                return;
            }

            for (let file of fileList) {
                if (!file.type.startsWith('image/')) {
                    continue;
                }

                const alreadyIncluded = files.some(
                    (existing) =>
                        existing.name === file.name &&
                        existing.size === file.size &&
                        existing.lastModified === file.lastModified
                );

                if (alreadyIncluded) {
                    continue;
                }

                if (files.length < 10) {
                    files.push(file);
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const preview = document.createElement('div');
                        preview.className = 'image-preview';
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Product thumbnail preview">
                            <button type="button" class="image-remove" aria-label="Remove image">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        imagePreviewContainer.appendChild(preview);

                        // Remove image
                        preview.querySelector('.image-remove').addEventListener('click', (e) => {
                            e.preventDefault();
                            preview.remove();
                            files.splice(files.indexOf(file), 1);
                            syncThumbnailInputFiles();
                        });
                    };
                    reader.readAsDataURL(file);
                }
            }

            syncThumbnailInputFiles();
        }

        // ==== ASSET TOGGLES ====
        const redirectToggle = document.getElementById('redirectToggle');
        const uploadToggle = document.getElementById('uploadToggle');
        const redirectOption = document.getElementById('redirectOption');
        const uploadOption = document.getElementById('uploadOption');
        const fileUploadBox = document.getElementById('fileUploadBox');
        const fileInput = document.getElementById('fileInput');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        const redirectUrlInput = document.querySelector('input[name="redirect_url"]');
        const assetDeliveryTypeInput = document.getElementById('assetDeliveryType');

        function formatFileSize(bytes) {
            if (bytes < 1024) return `${bytes} B`;
            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
            if (bytes < 1024 * 1024 * 1024) return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
            return `${(bytes / (1024 * 1024 * 1024)).toFixed(1)} GB`;
        }

        function renderFilePreviews(fileList) {
            filePreviewContainer.innerHTML = '';

            Array.from(fileList).forEach((file) => {
                const card = document.createElement('div');
                card.className = 'file-preview-card';

                const thumb = document.createElement('div');
                thumb.className = 'file-preview-thumb';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.alt = file.name;
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                    thumb.appendChild(img);
                } else {
                    thumb.innerHTML = '<i class="fas fa-file" style="font-size: 20px; color: #249E2F;"></i>';
                }

                const meta = document.createElement('div');
                meta.className = 'file-preview-meta';
                meta.innerHTML = `
                    <div class="file-preview-name" title="${file.name}">${file.name}</div>
                    <div class="file-preview-size">${formatFileSize(file.size)}</div>
                `;

                card.appendChild(thumb);
                card.appendChild(meta);
                filePreviewContainer.appendChild(card);
            });
        }

        function setAssetMode(mode, focusInput = true) {
            const selectedMode = mode === 'url' ? 'url' : 'file';
            assetDeliveryTypeInput.value = selectedMode;

            const urlActive = selectedMode === 'url';
            redirectToggle.classList.toggle('active', urlActive);
            redirectOption.classList.toggle('active', urlActive);
            redirectToggle.setAttribute('aria-pressed', urlActive ? 'true' : 'false');

            uploadToggle.classList.toggle('active', !urlActive);
            uploadOption.classList.toggle('active', !urlActive);
            uploadToggle.setAttribute('aria-pressed', !urlActive ? 'true' : 'false');

            redirectUrlInput.required = urlActive;
            fileInput.required = !urlActive;

            if (focusInput) {
                if (urlActive) {
                    redirectUrlInput.focus();
                } else {
                    fileInput.focus();
                }
            }
        }

        redirectToggle.addEventListener('click', (e) => {
            e.preventDefault();
            setAssetMode('url');
        });

        uploadToggle.addEventListener('click', (e) => {
            e.preventDefault();
            setAssetMode('file');
        });

        [redirectToggle, uploadToggle].forEach((toggle) => {
            toggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggle.click();
                }
            });
        });

        setAssetMode(assetDeliveryTypeInput.value || 'file', false);

        // File upload box click
        fileUploadBox.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', (e) => {
            renderFilePreviews(e.target.files);
        });

        // File drag and drop
        fileUploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadBox.classList.add('dragover');
        });

        fileUploadBox.addEventListener('dragleave', () => {
            fileUploadBox.classList.remove('dragover');
        });

        fileUploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadBox.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            renderFilePreviews(e.dataTransfer.files);
        });

        // ==== FORM VALIDATION ====
        const form = document.getElementById('addProductsForm');
        const validationModal = document.getElementById('validationModal');
        const validationIssueList = document.getElementById('validationIssueList');
        const validationModalClose = document.getElementById('validationModalClose');
        const validationModalOk = document.getElementById('validationModalOk');

        function clearInvalidFieldMarkers() {
            document.querySelectorAll('.field-invalid').forEach((el) => {
                el.classList.remove('field-invalid');
            });
        }

        function closeValidationModal() {
            validationModal.classList.remove('active');
            validationModal.setAttribute('aria-hidden', 'true');
        }

        function focusField(target) {
            if (!target) {
                return;
            }

            if (typeof target.scrollIntoView === 'function') {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            if (typeof target.focus === 'function') {
                target.focus({ preventScroll: true });
            }
        }

        function showValidationModal(issues) {
            validationIssueList.innerHTML = '';

            issues.forEach((issue) => {
                const listItem = document.createElement('li');
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = issue.message;
                button.addEventListener('click', () => {
                    closeValidationModal();
                    if (issue.field) {
                        issue.field.classList.add('field-invalid');
                    }
                    focusField(issue.field);
                });

                listItem.appendChild(button);
                validationIssueList.appendChild(listItem);
            });

            validationModal.classList.add('active');
            validationModal.setAttribute('aria-hidden', 'false');
            validationModalOk.focus();
        }

        validationModalClose.addEventListener('click', closeValidationModal);
        validationModalOk.addEventListener('click', closeValidationModal);
        validationModal.addEventListener('click', (e) => {
            if (e.target === validationModal) {
                closeValidationModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && validationModal.classList.contains('active')) {
                closeValidationModal();
            }
        });

        form.addEventListener('submit', (e) => {
            clearInvalidFieldMarkers();

            const productNameField = document.getElementById('productName');
            const productDescriptionField = document.getElementById('productDescription');
            const productFeatureField = document.getElementById('productFeature');
            const howItWorksField = document.getElementById('howItWorks');
            const priceField = document.querySelector('input[name="price"]');
            const redirectUrlField = redirectUrlInput;

            const name = productNameField.value.trim();
            const description = productDescriptionField.value.trim();
            const productFeature = productFeatureField.value.trim();
            const howItWorks = howItWorksField.value.trim();
            const price = priceField.value;
            const redirectUrl = redirectUrlField.value.trim();
            const hasThumbnail = files.length > 0 || thumbnailInput.files.length > 0;
            const hasProductFile = fileInput.files.length > 0;
            const selectedAssetMode = assetDeliveryTypeInput.value === 'url' ? 'url' : 'file';

            const issues = [];

            if (!name) {
                issues.push({ message: 'Product Name is required.', field: productNameField });
            }

            if (!description) {
                issues.push({ message: 'Product Description is required.', field: productDescriptionField });
            }

            if (!productFeature) {
                issues.push({ message: 'Product Feature is required.', field: productFeatureField });
            }

            if (!howItWorks) {
                issues.push({ message: 'How it works is required.', field: howItWorksField });
            }

            if (!price || Number(price) <= 0) {
                issues.push({ message: 'Price must be greater than 0.', field: priceField });
            }

            if (selectedAssetMode === 'url') {
                if (!redirectUrl) {
                    issues.push({ message: 'Redirect URL is required when URL mode is selected.', field: redirectUrlField });
                } else {
                    let parsedUrl;
                    try {
                        parsedUrl = new URL(redirectUrl);
                        if (!['http:', 'https:'].includes(parsedUrl.protocol)) {
                            issues.push({ message: 'Redirect URL must start with http:// or https://.', field: redirectUrlField });
                        }
                    } catch (error) {
                        issues.push({ message: 'Redirect URL format is invalid.', field: redirectUrlField });
                    }
                }
            }

            if (!hasThumbnail) {
                issues.push({ message: 'At least one thumbnail image is required.', field: uploadBox });
            }

            if (selectedAssetMode === 'file' && !hasProductFile) {
                issues.push({ message: 'At least one product file is required when File mode is selected.', field: fileUploadBox });
            }

            if (issues.length > 0) {
                e.preventDefault();
                issues.forEach((issue) => {
                    if (issue.field) {
                        issue.field.classList.add('field-invalid');
                    }
                });
                showValidationModal(issues);
                return;
            }

            // TODO: Integrate with backend to handle file uploads
            console.log('Form data ready for submission');
        });

        // Cancel button reset
        document.querySelector('button[type="reset"]').addEventListener('click', (e) => {
            if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                imagePreviewContainer.innerHTML = '';
                filePreviewContainer.innerHTML = '';
                files.length = 0;
                setAssetMode('file');
            } else {
                e.preventDefault();
            }
        });
    </script>

    <?= view('footer') ?>
</body>
</html>
