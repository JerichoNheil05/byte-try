<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byte Market - Your Marketplace for Digital Goods</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: #000000;
            --blue: #308BE5;
            --blue-dark: #2670B8;
            --green: #249E2F;
            --white: #FFFFFF;
            --gray-bg: #F5F7FA;
            --gray-dark: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--gray-bg);
            color: var(--black);
            line-height: 1.6;
        }

        /* === MAIN CONTAINER === */
        .landing-container {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin-top: 0;
        }

        .landing-content {
            max-width: 1400px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        /* === LEFT COLUMN === */
        .left-section {
            padding: 20px;
        }

        .main-heading {
            font-size: 40px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 24px;
            color: var(--black);
        }

        .description {
            font-size: 18px;
            font-weight: 400;
            line-height: 1.7;
            margin-bottom: 32px;
            color: #444;
        }

        .btn {
            display: inline-block;
            padding: 16px 40px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            outline: none;
            letter-spacing: 0.5px;
        }

        .btn-join {
            background: var(--black);
            color: var(--white);
        }

        .btn-join:hover,
        .btn-join:focus {
            background: var(--gray-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-buy {
            background: var(--blue);
            color: var(--white);
        }

        .btn-buy:hover,
        .btn-buy:focus {
            background: var(--blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(48, 139, 229, 0.4);
        }

        /* === RIGHT COLUMN === */
        .right-section {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-showcase {
            position: relative;
            background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
            border-radius: 32px;
            padding: 60px 40px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Decorative shapes */
        .showcase-bg-shape {
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            top: -50px;
            left: -50px;
            z-index: 0;
        }

        /* Phone graphic */
        .phone-graphic {
            position: relative;
            width: 200px;
            height: 360px;
            background: linear-gradient(145deg, #f0f0f0, #ffffff);
            border-radius: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            margin-bottom: 32px;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 8px solid #2a2a2a;
        }

        .phone-notch {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 20px;
            background: #2a2a2a;
            border-radius: 10px;
        }

        .phone-button {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 4px;
            background: #ccc;
            border-radius: 2px;
        }

        /* Shopping bag icon */
        .shopping-icon {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .bag-body {
            width: 100px;
            height: 80px;
            background: linear-gradient(to bottom, var(--blue) 0%, var(--blue) 60%, var(--green) 60%, var(--green) 100%);
            border-radius: 12px;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .bag-handle {
            width: 60px;
            height: 40px;
            border: 8px solid var(--green);
            border-bottom: none;
            border-radius: 30px 30px 0 0;
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .download-arrow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
            border-top: 24px solid var(--white);
            z-index: 2;
        }

        .download-arrow::before {
            content: '';
            position: absolute;
            width: 6px;
            height: 30px;
            background: var(--white);
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Text content */
        .showcase-content {
            z-index: 1;
        }

        .showcase-title {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .showcase-title .digital {
            color: var(--blue);
        }

        .showcase-title .products,
        .showcase-title .real {
            color: var(--white);
        }

        .showcase-title .results {
            color: var(--green);
        }

        .showcase-description {
            font-size: 15px;
            font-weight: 400;
            line-height: 1.6;
            color: #d0d0d0;
            margin-bottom: 24px;
        }

        /* Carousel indicators */
        .carousel-indicators {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 24px;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: var(--white);
            width: 16px;
            height: 16px;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 1024px) {
            .landing-content {
                gap: 40px;
            }

            .main-heading {
                font-size: 36px;
            }

            .showcase-title {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .landing-content {
                grid-template-columns: 1fr;
                gap: 60px;
            }

            .left-section,
            .right-section {
                text-align: center;
            }

            .main-heading {
                font-size: 32px;
            }

            .description {
                font-size: 16px;
            }

            .product-showcase {
                max-width: 100%;
            }

            .showcase-title {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .landing-container {
                padding: 20px 16px;
            }

            .main-heading {
                font-size: 28px;
            }

            .description {
                font-size: 15px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .phone-graphic {
                width: 160px;
                height: 300px;
            }

            .shopping-icon {
                width: 100px;
                height: 100px;
            }

            .bag-body {
                width: 80px;
                height: 64px;
            }

            .bag-handle {
                width: 48px;
                height: 32px;
                border-width: 6px;
            }

            .showcase-title {
                font-size: 20px;
            }

            .showcase-description {
                font-size: 14px;
            }
        }

        /* === ACCESSIBILITY === */
        .btn:focus-visible,
        .indicator:focus-visible {
            outline: 3px solid var(--blue);
            outline-offset: 4px;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?= view('header') ?>

    <main class="landing-container">
        <div class="landing-content">
            <!-- LEFT SECTION -->
            <section class="left-section">
                <h1 class="main-heading">Your marketplace for digital goods.</h1>
                <p class="description">
                    Explore a growing collection of digital products designed for convenience and speed. 
                    No shipping, just instant access with Byte Market.
                </p>
                <a href="<?= base_url('landing/join') ?>" class="btn btn-join" role="button" aria-label="Join Byte Market now">
                    Join Now
                </a>
            </section>

            <!-- RIGHT SECTION -->
            <section class="right-section">
                <div class="product-showcase">
                    <div class="showcase-bg-shape" aria-hidden="true"></div>
                    
                    <!-- Phone with shopping bag icon -->
                    <div class="phone-graphic" role="img" aria-label="Smartphone displaying digital marketplace">
                        <div class="phone-notch" aria-hidden="true"></div>
                        <div class="shopping-icon">
                            <div class="bag-handle" aria-hidden="true"></div>
                            <div class="bag-body" aria-hidden="true"></div>
                            <div class="download-arrow" aria-hidden="true"></div>
                        </div>
                        <div class="phone-button" aria-hidden="true"></div>
                    </div>

                    <!-- Content -->
                    <div class="showcase-content">
                        <h2 class="showcase-title">
                            <span class="digital">Digital</span> 
                            <span class="products">Products</span><br>
                            <span class="real">Real</span> 
                            <span class="results">Results</span>
                        </h2>
                        <p class="showcase-description">
                            Discover, purchase, and access digital products anytime. 
                            Byte Market keeps everything fast, secure, and simple.
                        </p>
                        <a href="<?= base_url('landing/buy') ?>" class="btn btn-buy" role="button" aria-label="Buy and download digital products">
                            Buy & Download
                        </a>
                    </div>

                    <!-- Carousel indicators -->
                    <div class="carousel-indicators" role="group" aria-label="Slide indicators">
                        <span class="indicator" role="button" tabindex="0" aria-label="Go to slide 1"></span>
                        <span class="indicator active" role="button" tabindex="0" aria-label="Go to slide 2" aria-current="true"></span>
                        <span class="indicator" role="button" tabindex="0" aria-label="Go to slide 3"></span>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
