<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Byte Market</title>
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
        .product-details-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* === PRODUCT PREVIEW SECTION === */
        .product-preview-section {
            margin-bottom: 48px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        .product-carousel {
            position: relative;
            background: #F5F5F5;
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 1;
        }

        .carousel-images {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .carousel-image.active {
            display: block;
        }

        .carousel-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: #308BE5;
        }

        .carousel-dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* === PRODUCT INFO SECTION === */
        .product-info-section {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .product-price {
            font-size: 32px;
            font-weight: 700;
            color: #308BE5;
        }

        .product-title {
            font-size: 28px;
            font-weight: 700;
            color: #000000;
            line-height: 1.3;
        }

        .product-seller {
            font-size: 16px;
            color: #249E2F;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .seller-link {
            color: #249E2F;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .seller-link:hover {
            text-decoration: underline;
        }

        /* === ACTION BUTTONS === */
        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #308BE5;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: #2568c2;
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.3);
        }

        .btn-primary:focus-visible {
            outline: 3px solid #308BE5;
            outline-offset: 2px;
        }

        .btn-danger {
            background: #C32C2C;
            color: #FFFFFF;
        }

        .btn-danger:hover {
            background: #a02121;
            box-shadow: 0 4px 12px rgba(195, 44, 44, 0.3);
        }

        .btn-danger:focus-visible {
            outline: 3px solid #C32C2C;
            outline-offset: 2px;
        }

        .btn-secondary {
            background: #F0F0F0;
            color: #000000;
            border: 1px solid #DDD;
        }

        .btn-secondary:hover {
            background: #E0E0E0;
        }

        .btn-secondary:focus-visible {
            outline: 3px solid #000000;
            outline-offset: 2px;
        }

        /* === SECTION CONTAINER === */
        .section {
            margin-bottom: 40px;
            padding: 32px;
            background: #FAFAFA;
            border-radius: 12px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title-icon {
            font-size: 24px;
        }

        /* === DESCRIPTION SECTION === */
        .description-text {
            font-size: 16px;
            color: #666666;
            line-height: 1.8;
        }

        /* === FEATURES SECTION === */
        .features-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feature-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .feature-icon {
            font-size: 24px;
            flex-shrink: 0;
            width: 32px;
            text-align: center;
        }

        .feature-text {
            font-size: 16px;
            color: #333333;
            line-height: 1.6;
        }

        /* === HOW IT WORKS SECTION === */
        .how-it-works-content {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .how-it-works-step {
            font-size: 16px;
            color: #666666;
            line-height: 1.8;
        }

        .important-note {
            background: #FFF3CD;
            border-left: 4px solid #FFC107;
            padding: 16px;
            border-radius: 6px;
            margin-top: 16px;
        }

        .important-note-title {
            font-weight: 600;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .important-note-text {
            font-size: 14px;
            color: #856404;
            line-height: 1.6;
        }

        /* === CONTACT SECTION === */
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .contact-icon {
            font-size: 24px;
            color: #308BE5;
            width: 32px;
            text-align: center;
            flex-shrink: 0;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
        }

        .contact-label {
            font-size: 12px;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .contact-value {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 968px) {
            .product-preview-section {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .product-price {
                font-size: 28px;
            }

            .product-title {
                font-size: 24px;
            }
        }

        @media (max-width: 768px) {
            .product-details-wrapper {
                padding: 24px 16px;
            }

            .section {
                padding: 24px 16px;
            }

            .product-carousel {
                aspect-ratio: auto;
                height: 300px;
            }

            .product-price {
                font-size: 24px;
            }

            .product-title {
                font-size: 20px;
            }

            .section-title {
                font-size: 18px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .contact-info {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .product-details-wrapper {
                padding: 16px 12px;
            }

            .section {
                padding: 16px 12px;
                margin-bottom: 24px;
            }

            .product-price {
                font-size: 22px;
            }

            .product-title {
                font-size: 18px;
            }

            .section-title {
                font-size: 16px;
            }

            .feature-icon {
                font-size: 20px;
            }

            .feature-text {
                font-size: 14px;
            }

            .carousel-dots {
                bottom: 12px;
            }

            .carousel-dot {
                width: 8px;
                height: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="product-details-wrapper">
        <!-- Product Preview Section -->
        <div class="product-preview-section">
            <!-- Carousel -->
            <div class="product-carousel">
                <div class="carousel-images">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 400'%3E%3Cdefs%3E%3ClinearGradient id='grad1' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%232a1a4e;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%231a3a3a;stop-opacity:1' /%3E%3C/linearGradient%3E%3ClinearGradient id='grad2' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%232d5a4d;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%231a4d3d;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='400' height='400' fill='url(%23grad1)'/%3E%3Crect x='150' y='100' width='100' height='150' fill='%23ffd700' opacity='0.3'/%3E%3Ccircle cx='100' cy='100' r='40' fill='%23ff69b4' opacity='0.2'/%3E%3Ctext x='200' y='350' font-family='Poppins' font-size='24' fill='%23fff' text-anchor='middle'%3EEncanto Presentation%3C/text%3E%3C/svg%3E" alt="Product preview image 1 - Encanto themed PowerPoint template" class="carousel-image active">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 400'%3E%3Cdefs%3E%3ClinearGradient id='grad3' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23ffd700;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23ff8c00;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='400' height='400' fill='url(%23grad3)'/%3E%3Ctext x='200' y='200' font-family='Poppins' font-size='48' font-weight='bold' fill='%23000' text-anchor='middle' dominant-baseline='middle'%3E✨%3C/text%3E%3Ctext x='200' y='350' font-family='Poppins' font-size='24' fill='%23000' text-anchor='middle'%3ESpecial Effects Preview%3C/text%3E%3C/svg%3E" alt="Product preview image 2 - Special effects showcase" class="carousel-image">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 400'%3E%3Cdefs%3E%3ClinearGradient id='grad4' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%2300b4d8;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23006ba6;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='400' height='400' fill='url(%23grad4)'/%3E%3Crect x='80' y='80' width='240' height='240' fill='%23fff' opacity='0.2' rx='10'/%3E%3Ctext x='200' y='350' font-family='Poppins' font-size='24' fill='%23fff' text-anchor='middle'%3ETemplate Details%3C/text%3E%3C/svg%3E" alt="Product preview image 3 - Template details and layout" class="carousel-image">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 400'%3E%3Cdefs%3E%3ClinearGradient id='grad5' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23228b22;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23006400;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='400' height='400' fill='url(%23grad5)'/%3E%3Ccircle cx='200' cy='150' r='60' fill='%23ffd700' opacity='0.3'/%3E%3Ctext x='200' y='350' font-family='Poppins' font-size='24' fill='%23fff' text-anchor='middle'%3EColor Palette%3C/text%3E%3C/svg%3E" alt="Product preview image 4 - Color palette options" class="carousel-image">
                </div>
                <div class="carousel-dots">
                    <button type="button" class="carousel-dot active" data-slide="0" aria-label="Go to slide 1"></button>
                    <button type="button" class="carousel-dot" data-slide="1" aria-label="Go to slide 2"></button>
                    <button type="button" class="carousel-dot" data-slide="2" aria-label="Go to slide 3"></button>
                    <button type="button" class="carousel-dot" data-slide="3" aria-label="Go to slide 4"></button>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info-section">
                <div class="product-price">₱<?= isset($product) ? number_format($product['price'] ?? 200, 2) : '200.00' ?></div>
                <h1 class="product-title"><?= isset($product) ? $product['title'] ?? 'Encanto themed PowerPoint Template' : 'Encanto themed PowerPoint Template' ?></h1>
                <p class="product-seller">
                    by <a href="<?= base_url('bytefolio') ?>" class="seller-link"><?= isset($product) ? $product['seller_name'] ?? 'MCreateArts' : 'MCreateArts' ?></a>
                </p>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('products/edit/' . ($product['id'] ?? '1')) ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger" id="deleteBtn">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="section">
            <h2 class="section-title">
                <span class="section-title-icon">📝</span>
                Description
            </h2>
            <p class="description-text">
                <?= isset($product) ? $product['description'] ?? '' : 'Want to add some magic to your presentations? Our Encanto-themed PowerPoint template will transport your audience straight to the heart of the magical world of the Madrigal family! Whether you\'re working on a school project, business pitch, or creative presentation, this beginner-friendly template has everything you need to wow your audience.' ?>
            </p>
        </div>

        <!-- Special Features Section -->
        <div class="section">
            <h2 class="section-title">
                <span class="section-title-icon">✨</span>
                Special Features
            </h2>
            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-icon">✨</div>
                    <div class="feature-text">Vibrant Encanto Design</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🎞️</div>
                    <div class="feature-text">Customizable Slides</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔄</div>
                    <div class="feature-text">Interactive Transitions</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">Pre-made Layouts for Text, Images, and Graphs</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🎵</div>
                    <div class="feature-text">Musical Elements & Sound Effects</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🌈</div>
                    <div class="feature-text">Colorful Backgrounds inspired by the Encanto World</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🖼️</div>
                    <div class="feature-text">Beautiful Image Placeholders</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🛠️</div>
                    <div class="feature-text">Editable Icons and Illustrations</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📋</div>
                    <div class="feature-text">Simple, Easy-to-Use Template</div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="section">
            <h2 class="section-title">
                <span class="section-title-icon">ℹ️</span>
                How it Works
            </h2>
            <div class="how-it-works-content">
                <p class="how-it-works-step">
                    Once the payment is complete, Byte Market will send you an email with the link to download the Encanto template file. You'll have instant access to the file and can start customizing it right away!
                </p>

                <div class="important-note">
                    <div class="important-note-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        PLEASE NOTE
                    </div>
                    <div class="important-note-text">
                        Due to the nature of digital products, all purchases are final and non-refundable. We appreciate your understanding in this matter. This is a digital product / digital download. No physical product will be shipped to the customer. If you have any questions or concerns about your purchase, feel free to reach out!
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Support Section -->
        <div class="section">
            <h2 class="section-title">
                <span class="section-title-icon">💬</span>
                Support & Contact
            </h2>
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Email</div>
                        <div class="contact-value">support@bytemarket.com</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Phone</div>
                        <div class="contact-value">+63 (2) 1234-5678</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Response Time</div>
                        <div class="contact-value">24 hours</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // === CAROUSEL FUNCTIONALITY ===
        const carouselImages = document.querySelectorAll('.carousel-image');
        const carouselDots = document.querySelectorAll('.carousel-dot');
        let currentSlide = 0;

        function showSlide(index) {
            // Wrap around
            if (index >= carouselImages.length) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = carouselImages.length - 1;
            } else {
                currentSlide = index;
            }

            // Update images
            carouselImages.forEach(img => img.classList.remove('active'));
            carouselImages[currentSlide].classList.add('active');

            // Update dots
            carouselDots.forEach(dot => dot.classList.remove('active'));
            carouselDots[currentSlide].classList.add('active');
        }

        // Dot click handlers
        carouselDots.forEach(dot => {
            dot.addEventListener('click', function() {
                showSlide(parseInt(this.getAttribute('data-slide')));
            });

            // Keyboard support
            dot.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    dot.click();
                }
            });
        });

        // Auto-rotate carousel every 5 seconds (optional)
        setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);

        // === DELETE BUTTON HANDLER ===
        const deleteBtn = document.getElementById('deleteBtn');
        deleteBtn.addEventListener('click', function() {
            const productTitle = document.querySelector('.product-title').textContent;
            if (confirm(`Are you sure you want to delete "${productTitle}"? This action cannot be undone.`)) {
                // TODO: Send delete request to backend
                console.log('Deleting product...');
                // window.location.href = '/products/delete/' + productId;
                alert('Deletion feature coming soon!');
            }
        });

        // === KEYBOARD NAVIGATION FOR ACTION BUTTONS ===
        const actionButtons = document.querySelectorAll('.action-buttons .btn');
        actionButtons.forEach(btn => {
            btn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btn.click();
                }
            });
        });
    </script>
</body>
</html>
