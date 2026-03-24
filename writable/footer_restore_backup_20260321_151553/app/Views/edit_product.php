<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Byte Market Seller Dashboard</title>
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
            background: #F9F9F9;
        }

        /* === MAIN CONTAINER === */
        .edit-product-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* === HEADER === */
        .page-header {
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .back-button {
            background: none;
            border: none;
            color: #000000;
            font-size: 28px;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-radius: 8px;
            text-decoration: none;
        }

        .back-button:hover {
            background: #F0F0F0;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #249E2F;
        }

        /* === FORM CONTAINER === */
        .form-container {
            background: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* === FORM GROUP === */
        .form-group {
            margin-bottom: 32px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #C32C2C;
        }

        .form-helper-text {
            font-size: 12px;
            color: #999999;
            margin-top: 4px;
        }

        /* === INPUT FIELDS === */
        .form-input {
            width: 100%;
            padding: 12px 16px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: #000000;
            background: #F5F5F5;
            border: 1px solid #EEEEEE;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            background: #FFFFFF;
            border-color: #249E2F;
            box-shadow: 0 0 0 3px rgba(36, 158, 47, 0.1);
        }

        .form-input::placeholder {
            color: #CCCCCC;
        }

        /* === TEXTAREA === */
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: #000000;
            background: #F5F5F5;
            border: 1px solid #EEEEEE;
            border-radius: 8px;
            resize: vertical;
            min-height: 120px;
            transition: all 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            background: #FFFFFF;
            border-color: #249E2F;
            box-shadow: 0 0 0 3px rgba(36, 158, 47, 0.1);
        }

        /* === THUMBNAIL SECTION === */
        .thumbnail-section {
            margin-bottom: 32px;
        }

        .thumbnail-label {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
            display: block;
        }

        .thumbnail-helper-text {
            font-size: 12px;
            color: #999999;
            margin-bottom: 16px;
        }

        .thumbnail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .thumbnail-item {
            position: relative;
            aspect-ratio: 1;
            background: #F5F5F5;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .thumbnail-item:hover {
            border-color: #249E2F;
            box-shadow: 0 4px 12px rgba(36, 158, 47, 0.2);
        }

        .thumbnail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-remove {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(195, 44, 44, 0.9);
            color: #FFFFFF;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .thumbnail-item:hover .thumbnail-remove {
            opacity: 1;
        }

        .thumbnail-remove:hover {
            background: rgba(195, 44, 44, 1);
        }

        .thumbnail-add {
            aspect-ratio: 1;
            background: #F5F5F5;
            border: 2px dashed #CCCCCC;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-direction: column;
            gap: 8px;
            color: #249E2F;
        }

        .thumbnail-add:hover {
            background: #F9F9F9;
            border-color: #249E2F;
        }

        .thumbnail-add-icon {
            font-size: 32px;
        }

        .thumbnail-add-text {
            font-size: 14px;
            font-weight: 600;
        }

        .thumbnail-input {
            display: none;
        }

        /* === ASSETS SECTION === */
        .assets-section {
            margin-bottom: 32px;
        }

        .assets-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 16px;
            display: block;
        }

        .assets-helper-text {
            font-size: 12px;
            color: #999999;
            margin-bottom: 16px;
        }

        .asset-option {
            margin-bottom: 24px;
            padding: 16px;
            background: #F5F5F5;
            border-radius: 8px;
            border: 1px solid #EEEEEE;
            transition: all 0.3s ease;
        }

        .asset-option.active {
            background: #F0F7F3;
            border-color: #249E2F;
        }

        .asset-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
        }

        .toggle-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #249E2F;
        }

        .asset-toggle-label {
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            cursor: pointer;
            flex: 1;
        }

        .asset-content {
            margin-top: 16px;
            display: none;
        }

        .asset-content.visible {
            display: block;
        }

        .asset-input {
            width: 100%;
            padding: 12px 16px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: #000000;
            background: #FFFFFF;
            border: 1px solid #EEEEEE;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .asset-input:focus {
            outline: none;
            border-color: #249E2F;
            box-shadow: 0 0 0 3px rgba(36, 158, 47, 0.1);
        }

        .asset-file-input {
            width: 100%;
            padding: 12px 16px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
        }

        .file-list {
            margin-top: 16px;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #FFFFFF;
            border: 1px solid #EEEEEE;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .file-item-name {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            font-size: 14px;
            min-width: 0;
        }

        .file-item-details {
            display: flex;
            flex-direction: column;
            min-width: 0;
            gap: 2px;
        }

        .file-item-link {
            font-size: 12px;
            color: #249E2F;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .file-item-link:hover {
            text-decoration: underline;
        }

        .stored-link-preview {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #249E2F;
            font-size: 13px;
            text-decoration: none;
            word-break: break-all;
        }

        .stored-link-preview:hover {
            text-decoration: underline;
        }

        .file-item-remove {
            background: none;
            border: none;
            color: #C32C2C;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
            transition: all 0.3s ease;
        }

        .file-item-remove:hover {
            color: #a02121;
        }

        /* === PRICE SECTION === */
        .price-section {
            margin-bottom: 32px;
        }

        .price-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .currency-symbol {
            position: absolute;
            left: 16px;
            font-size: 16px;
            font-weight: 600;
            color: #666666;
        }

        .price-input {
            padding-left: 40px !important;
        }

        /* === FORM ACTIONS === */
        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 40px;
            padding-top: 32px;
            border-top: 1px solid #EEEEEE;
        }

        .btn {
            min-height: 48px;
            padding: 0 28px;
            border-radius: 999px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-transform: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            letter-spacing: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.08);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2bc53a 0%, #1f9f2d 100%);
            color: #FFFFFF;
            flex: 1;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(36, 158, 47, 0.28);
        }

        .btn-primary:focus-visible {
            outline: 3px solid #249E2F;
            outline-offset: 2px;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.95);
            color: #1f2937;
            border-color: #d9dee7;
            flex: 1;
        }

        .btn-secondary:hover:not(:disabled) {
            transform: translateY(-1px);
            background: #f8fafc;
            border-color: #c5ceda;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        }

        .btn-secondary:focus-visible {
            outline: 3px solid #000000;
            outline-offset: 2px;
        }

        /* === ERROR MESSAGES === */
        .error-message {
            color: #C32C2C;
            font-size: 12px;
            margin-top: 4px;
        }

        .form-input.error,
        .form-textarea.error {
            border-color: #C32C2C;
            background: #FFF5F5;
        }

        /* === SUCCESS BANNER === */
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #E7F5E9;
            color: #1d7a24;
            border-left: 4px solid #249E2F;
        }

        .alert-error {
            background: #FFE7E9;
            color: #a02121;
            border-left: 4px solid #C32C2C;
        }

        .alert-icon {
            font-size: 18px;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 768px) {
            .edit-product-wrapper {
                padding: 24px 16px;
            }

            .form-container {
                padding: 24px 16px;
            }

            .page-title {
                font-size: 28px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .thumbnail-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .edit-product-wrapper {
                padding: 16px 12px;
            }

            .form-container {
                padding: 16px 12px;
            }

            .page-title {
                font-size: 24px;
            }

            .page-header {
                gap: 12px;
            }

            .form-group {
                margin-bottom: 24px;
            }

            .form-label {
                font-size: 14px;
            }

            .thumbnail-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 12px;
            }

            .btn {
                font-size: 13px;
                padding: 10px 16px;
            }
        }
    </style>
</head>
<body>
    <?php
        $hasExistingRedirectUrl = !empty($product['asset_redirect_url']);
        $hasExistingAssetFiles = !empty($product['asset_files']) && is_array($product['asset_files']);
    ?>
    <div class="edit-product-wrapper">
        <!-- Header -->
        <div class="page-header">
            <a href="<?= base_url('products') ?>" class="back-button js-history-back" data-fallback-url="<?= base_url('products') ?>" aria-label="Go back to previous page">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Edit Product</h1>
        </div>

        <!-- Alert Messages -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle alert-icon"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle alert-icon"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form class="form-container" id="editProductForm" method="POST" action="<?= base_url('products/update/' . ($product['id'] ?? '1')) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div id="removedAssetPathsContainer"></div>

            <!-- Product Name -->
            <div class="form-group">
                <label for="productName" class="form-label">
                    Product Name <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="productName" 
                    name="product_name" 
                    class="form-input" 
                    value="<?= isset($product) ? htmlspecialchars($product['product_name'] ?? 'Encanto themed PowerPoint Template') : '' ?>"
                    placeholder="Enter product name"
                    required
                    aria-required="true"
                >
                <div id="productNameError" class="error-message"></div>
            </div>

            <!-- Product Description -->
            <div class="form-group">
                <label for="productDescription" class="form-label">
                    Product Description <span class="required">*</span>
                </label>
                <textarea 
                    id="productDescription" 
                    name="product_description" 
                    class="form-textarea"
                    placeholder="Provide detailed description of product you are selling"
                    required
                    aria-required="true"
                ><?= isset($product) ? htmlspecialchars($product['product_description'] ?? '') : '' ?></textarea>
                <div id="descriptionError" class="error-message"></div>
            </div>

            <!-- Product Feature -->
            <div class="form-group">
                <label for="productFeature" class="form-label">
                    Product Feature <span class="required">*</span>
                </label>
                <textarea
                    id="productFeature"
                    name="product_feature"
                    class="form-textarea"
                    placeholder="List key product features and highlights"
                    required
                    aria-required="true"
                ><?= isset($product) ? htmlspecialchars($product['product_feature'] ?? '') : '' ?></textarea>
                <div id="productFeatureError" class="error-message"></div>
            </div>

            <!-- How It Works -->
            <div class="form-group">
                <label for="howItWorks" class="form-label">
                    How it works <span class="required">*</span>
                </label>
                <textarea
                    id="howItWorks"
                    name="how_it_works"
                    class="form-textarea"
                    placeholder="Explain how buyers can access and use the product after purchase"
                    required
                    aria-required="true"
                ><?= isset($product) ? htmlspecialchars($product['how_it_works'] ?? '') : '' ?></textarea>
                <div id="howItWorksError" class="error-message"></div>
            </div>

            <!-- Thumbnail Section -->
            <div class="thumbnail-section">
                <label class="thumbnail-label">Thumbnail</label>
                <p class="thumbnail-helper-text">Maximum of 10. Used as representation for your selling product.</p>
                
                <div class="thumbnail-grid" id="thumbnailGrid">
                    <?php if(isset($product['thumbnails']) && is_array($product['thumbnails'])): ?>
                        <?php foreach($product['thumbnails'] as $index => $thumbnail): ?>
                            <div class="thumbnail-item">
                                <img src="<?= htmlspecialchars($thumbnail['url']) ?>" alt="Product thumbnail <?= $index + 1 ?>" class="thumbnail-image">
                                <button type="button" class="thumbnail-remove" onclick="removeThumbnail(<?= $index ?>)" aria-label="Remove thumbnail">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <!-- Add Thumbnail Button -->
                    <label class="thumbnail-add" onclick="document.getElementById('thumbnailInput').click()" role="button" tabindex="0" aria-label="Add product thumbnails">
                        <div class="thumbnail-add-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="thumbnail-add-text">Add Images</div>
                    </label>
                </div>

                <input 
                    type="file" 
                    id="thumbnailInput" 
                    name="thumbnails[]" 
                    class="thumbnail-input" 
                    accept="image/*"
                    multiple
                    onchange="handleThumbnailUpload(event)"
                >
                <div id="thumbnailError" class="error-message"></div>
            </div>

            <!-- Assets Section -->
            <div class="assets-section">
                <label class="assets-section-title">Assets</label>
                <p class="assets-helper-text">Files that buyers can get access once payment is completed.</p>

                <!-- Redirect to URL -->
                <div class="asset-option <?= $hasExistingRedirectUrl ? 'active' : '' ?>">
                    <div class="asset-toggle">
                        <input 
                            type="checkbox" 
                            id="redirectUrlToggle" 
                            class="toggle-checkbox"
                            <?= $hasExistingRedirectUrl ? 'checked' : '' ?>
                            onchange="toggleAssetOption('redirectUrl')"
                        >
                        <label for="redirectUrlToggle" class="asset-toggle-label">
                            <i class="fas fa-link" style="margin-right: 8px; color: #249E2F;"></i>
                            Redirect to URL
                        </label>
                    </div>
                    <div id="redirectUrlContent" class="asset-content <?= $hasExistingRedirectUrl ? 'visible' : '' ?>">
                        <input 
                            type="url" 
                            name="asset_redirect_url" 
                            class="asset-input"
                            placeholder="https://drive.google.com/drive/folders/1a1a1a1a1a1a1a1a1a1a1a1a"
                            value="<?= isset($product['asset_redirect_url']) ? htmlspecialchars($product['asset_redirect_url']) : '' ?>"
                        >
                        <?php if ($hasExistingRedirectUrl): ?>
                            <a href="<?= esc($product['asset_redirect_url']) ?>" target="_blank" rel="noopener noreferrer" class="stored-link-preview">
                                <i class="fas fa-external-link-alt"></i>
                                <span><?= esc($product['asset_redirect_url']) ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upload File -->
                <div class="asset-option <?= $hasExistingAssetFiles ? 'active' : '' ?>">
                    <div class="asset-toggle">
                        <input 
                            type="checkbox" 
                            id="uploadFileToggle" 
                            class="toggle-checkbox"
                            <?= $hasExistingAssetFiles ? 'checked' : '' ?>
                            onchange="toggleAssetOption('uploadFile')"
                        >
                        <label for="uploadFileToggle" class="asset-toggle-label">
                            <i class="fas fa-upload" style="margin-right: 8px; color: #249E2F;"></i>
                            Upload File
                        </label>
                    </div>
                    <div id="uploadFileContent" class="asset-content <?= $hasExistingAssetFiles ? 'visible' : '' ?>">
                        <input 
                            type="file" 
                            name="asset_files[]" 
                            class="asset-file-input"
                            multiple
                            onchange="handleAssetFileUpload(event)"
                        >
                        <div class="file-list" id="fileList">
                            <?php if(isset($product['asset_files']) && is_array($product['asset_files'])): ?>
                                <?php foreach($product['asset_files'] as $index => $file): ?>
                                    <div class="file-item" data-existing-file-path="<?= esc($file['path'] ?? '', 'attr') ?>">
                                        <div class="file-item-name">
                                            <i class="fas fa-file"></i>
                                            <div class="file-item-details">
                                                <span><?= htmlspecialchars($file['name']) ?></span>
                                                <?php if (!empty($file['url'])): ?>
                                                    <a href="<?= htmlspecialchars($file['url']) ?>" target="_blank" rel="noopener noreferrer" class="file-item-link">View current file</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <button type="button" class="file-item-remove" onclick="removeFile(this)" aria-label="Remove file">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div id="assetError" class="error-message"></div>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="productPrice" class="form-label">
                    Price <span class="required">*</span>
                </label>
                <div class="price-input-wrapper">
                    <span class="currency-symbol">₱</span>
                    <input 
                        type="number" 
                        id="productPrice" 
                        name="price" 
                        class="form-input price-input" 
                        value="<?= isset($product) ? number_format($product['price'] ?? 200, 2) : '' ?>"
                        placeholder="0.00"
                        step="0.01"
                        min="0"
                        required
                        aria-required="true"
                    >
                </div>
                <div id="priceError" class="error-message"></div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="updateBtn">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="<?= base_url('products') ?>" class="btn btn-secondary js-history-back" data-fallback-url="<?= base_url('products') ?>">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // === ASSET TOGGLE FUNCTIONALITY ===
        function toggleAssetOption(option) {
            const contentId = option + 'Content';
            const contentElement = document.getElementById(contentId);
            contentElement.classList.toggle('visible');

            // Update parent styling
            contentElement.parentElement.classList.toggle('active');
        }

        // === THUMBNAIL HANDLING ===
        function handleThumbnailUpload(event) {
            const files = Array.from(event.target.files);
            const thumbnailGrid = document.getElementById('thumbnailGrid');
            const currentThumbnails = thumbnailGrid.querySelectorAll('.thumbnail-item').length;

            if (currentThumbnails + files.length > 10) {
                document.getElementById('thumbnailError').textContent = 'Maximum 10 thumbnails allowed';
                return;
            }

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const thumbnailItem = document.createElement('div');
                    thumbnailItem.className = 'thumbnail-item';
                    thumbnailItem.innerHTML = `
                        <img src="${e.target.result}" alt="New thumbnail" class="thumbnail-image">
                        <button type="button" class="thumbnail-remove" onclick="this.parentElement.remove()" aria-label="Remove thumbnail">
                            <i class="fas fa-times"></i>
                        </button>
                    `;

                    // Insert before the "Add Images" tile inside the thumbnail grid.
                    const addButton = thumbnailGrid.querySelector('.thumbnail-add');
                    thumbnailGrid.insertBefore(thumbnailItem, addButton);
                };
                reader.readAsDataURL(file);
            });

            document.getElementById('thumbnailError').textContent = '';
        }

        function removeThumbnail(index) {
            // Implementation for removing existing thumbnails
            console.log('Removing thumbnail:', index);
        }

        // === FILE HANDLING ===
        function handleAssetFileUpload(event) {
            const files = Array.from(event.target.files);
            const fileList = document.getElementById('fileList');

            files.forEach((file) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <div class="file-item-name">
                        <i class="fas fa-file"></i>
                        <div class="file-item-details">
                            <span>${file.name}</span>
                            <span class="file-item-link">New file selected</span>
                        </div>
                    </div>
                    <button type="button" class="file-item-remove" onclick="this.parentElement.remove()" aria-label="Remove file">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                fileList.appendChild(fileItem);
            });
        }

        function removeFile(button) {
            const fileList = document.getElementById('fileList');
            const removedAssetPathsContainer = document.getElementById('removedAssetPathsContainer');
            if (!fileList || !button) {
                return;
            }

            const targetItem = button.closest('.file-item');
            if (!targetItem) {
                return;
            }

            const existingPath = targetItem.getAttribute('data-existing-file-path') || '';
            if (existingPath && removedAssetPathsContainer) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'removed_asset_paths[]';
                hiddenInput.value = existingPath;
                removedAssetPathsContainer.appendChild(hiddenInput);
            }

            targetItem.remove();
        }

        // === FORM VALIDATION ===
        const form = document.getElementById('editProductForm');
        
        form.addEventListener('submit', function(e) {
            // Reset error messages
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-input.error, .form-textarea.error').forEach(el => el.classList.remove('error'));

            let isValid = true;

            // Validate product name
            const productName = document.getElementById('productName');
            if (!productName.value.trim()) {
                document.getElementById('productNameError').textContent = 'Product name is required';
                productName.classList.add('error');
                isValid = false;
            }

            // Validate description
            const description = document.getElementById('productDescription');
            if (!description.value.trim()) {
                document.getElementById('descriptionError').textContent = 'Product description is required';
                description.classList.add('error');
                isValid = false;
            }

            // Validate price
            const price = document.getElementById('productPrice');
            if (!price.value || parseFloat(price.value) <= 0) {
                document.getElementById('priceError').textContent = 'Price must be greater than 0';
                price.classList.add('error');
                isValid = false;
            }

            // Validate at least one asset
            const hasRedirectUrl = document.getElementById('redirectUrlToggle').checked && document.querySelector('input[name="asset_redirect_url"]').value.trim();
            const hasUploadFile = document.getElementById('uploadFileToggle').checked && document.querySelectorAll('.file-item').length > 0;

            if (!hasRedirectUrl && !hasUploadFile) {
                document.getElementById('assetError').textContent = 'Please provide at least one asset (URL or file)';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // === KEYBOARD NAVIGATION ===
        document.querySelectorAll('.asset-toggle-label').forEach(label => {
            label.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    label.querySelector('input[type="checkbox"]').click();
                }
            });
        });

        // === INPUT FOCUS STYLING ===
        document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.remove('error');
            });
        });

        document.querySelectorAll('.js-history-back').forEach((backLink) => {
            backLink.addEventListener('click', (event) => {
                event.preventDefault();

                if (window.history.length > 1) {
                    window.history.back();
                    return;
                }

                const fallbackUrl = backLink.getAttribute('data-fallback-url') || '<?= base_url('products') ?>';
                window.location.href = fallbackUrl;
            });
        });
    </script>
</body>
</html>
