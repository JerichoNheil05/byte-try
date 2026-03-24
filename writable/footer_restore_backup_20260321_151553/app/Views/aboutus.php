<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Byte Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-bg: #111111;
            --darker-bg: #0A0A0A;
            --blue: #308BE5;
            --green: #249E2F;
            --white: #FFFFFF;
            --gray: #F5F7FA;
            --gray-text: #B0B0B0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--dark-bg);
            color: var(--white);
            line-height: 1.6;
        }

        /* === MAIN CONTAINER === */
        .aboutus-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* === HERO SECTION === */
        .hero-section {
            padding: 80px 20px;
            text-align: center;
            background: linear-gradient(135deg, #1a1a1a 0%, var(--darker-bg) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before,
        .hero-section::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }

        .hero-section::before {
            background: var(--blue);
            top: -100px;
            left: -100px;
        }

        .hero-section::after {
            background: var(--green);
            bottom: -100px;
            right: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 24px;
            color: var(--white);
        }

        .hero-section h1 .highlight-green {
            color: var(--green);
        }

        .hero-section h1 .highlight-blue {
            color: var(--blue);
        }

        .hero-section p {
            font-size: 16px;
            font-weight: 400;
            color: var(--gray-text);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* === WHAT WE OFFER SECTION === */
        .offer-section {
            padding: 80px 20px;
            background: var(--dark-bg);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .section-header .highlight {
            color: var(--green);
        }

        .section-header p {
            font-size: 16px;
            color: var(--gray-text);
            max-width: 800px;
            margin: 0 auto;
        }

        .offer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 20px;
        }

        .offer-card {
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            min-height: 200px;
            justify-content: center;
        }

        .offer-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .offer-card.blue {
            background: var(--blue);
            color: var(--white);
        }

        .offer-card.green {
            background: var(--green);
            color: var(--white);
        }

        .offer-card h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .offer-card p {
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
        }

        .offer-icon {
            font-size: 48px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* === MISSION SECTION === */
        .mission-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #1a1a1a 0%, var(--darker-bg) 100%);
            text-align: center;
        }

        .mission-section h2 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .mission-section .highlight {
            color: var(--blue);
        }

        .mission-section p {
            font-size: 16px;
            font-weight: 400;
            color: var(--gray-text);
            max-width: 900px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* === WHY CHOOSE SECTION === */
        .why-choose-section {
            padding: 80px 20px;
            background: var(--dark-bg);
        }

        .why-choose-section .section-header h2 {
            color: var(--white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 16px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: rgba(48, 139, 229, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: var(--blue);
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            background: rgba(36, 158, 47, 0.1);
            color: var(--green);
            transform: scale(1.1);
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--white);
        }

        .feature-card p {
            font-size: 14px;
            color: var(--gray-text);
            line-height: 1.6;
        }

        /* === CTA SECTION === */
        .cta-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #1a1a1a 0%, var(--darker-bg) 100%);
            text-align: center;
        }

        .cta-content {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 60px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-text {
            text-align: left;
            flex: 1;
            min-width: 300px;
        }

        .cta-text h2 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .cta-text .highlight {
            color: var(--green);
        }

        .cta-text p {
            font-size: 16px;
            color: var(--gray-text);
            line-height: 1.8;
            margin-bottom: 32px;
        }

        .cta-button {
            display: inline-block;
            background: var(--green);
            color: var(--white);
            padding: 16px 40px;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            text-decoration: none;
        }

        .cta-button:hover {
            background: #1e7a27;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(36, 158, 47, 0.3);
        }

        .cta-button:focus-visible {
            outline: 3px solid var(--green);
            outline-offset: 2px;
        }

        .cta-illustration {
            flex: 1;
            min-width: 300px;
            text-align: center;
        }

        .cta-illustration svg {
            max-width: 100%;
            height: auto;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 1024px) {
            .hero-section h1 {
                font-size: 40px;
            }

            .section-header h2 {
                font-size: 32px;
            }

            .cta-text h2 {
                font-size: 32px;
            }

            .offer-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 20px;
            }

            .hero-section h1 {
                font-size: 32px;
            }

            .hero-section p {
                font-size: 14px;
            }

            .offer-section,
            .mission-section,
            .why-choose-section,
            .cta-section {
                padding: 60px 20px;
            }

            .section-header h2 {
                font-size: 28px;
            }

            .cta-content {
                gap: 40px;
                flex-direction: column;
            }

            .cta-text {
                text-align: center;
            }

            .cta-text h2 {
                font-size: 28px;
            }

            .features-grid {
                gap: 30px;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .feature-card h3 {
                font-size: 18px;
            }

            .feature-card p {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 40px 16px;
            }

            .hero-section h1 {
                font-size: 24px;
            }

            .hero-section p {
                font-size: 13px;
            }

            .offer-section,
            .mission-section,
            .why-choose-section,
            .cta-section {
                padding: 40px 16px;
            }

            .section-header h2 {
                font-size: 24px;
            }

            .offer-card {
                padding: 30px 20px;
            }

            .features-grid {
                gap: 20px;
                grid-template-columns: 1fr;
            }

            .feature-card {
                gap: 12px;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 32px;
            }

            .cta-button {
                padding: 14px 32px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="aboutus-container">
            <div class="hero-content">
                <h1>Your <span class="highlight-green">marketplace</span> for <span class="highlight-blue">digital goods.</span></h1>
                <p>At Byte Market, we're revolutionizing the way you access digital products. Whether you're looking for ebooks, software, tutorials, or digital art, Byte Market offers a fast, secure, and straightforward platform to discover and download a growing collection of digital goods. We're focused on providing a seamless experience, ensuring that you get instant access to high-quality products, anytime and anywhere.</p>
            </div>
        </div>
    </section>

    <!-- What We Offer Section -->
    <section class="offer-section">
        <div class="aboutus-container">
            <div class="section-header">
                <h2>What We <span class="highlight">Offer</span></h2>
                <p><strong>Byte Market</strong> is more than just a digital product store – we're a community dedicated to bringing you the best in digital innovation. Here, you can:</p>
            </div>
            <div class="offer-grid">
                <div class="offer-card blue">
                    <div class="offer-icon">🔍</div>
                    <h3>Browse and discover</h3>
                    <p>Browse and discover a wide variety of digital products</p>
                </div>
                <div class="offer-card green">
                    <div class="offer-icon">⬇️</div>
                    <h3>Download instantly</h3>
                    <p>Download instantly after purchase – no shipping required.</p>
                </div>
                <div class="offer-card blue">
                    <div class="offer-icon">🛒</div>
                    <h3>Purchase with ease</h3>
                    <p>Purchase with our trusted payment system.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="aboutus-container">
            <div class="section-header">
                <h2>Our <span class="highlight">Mission</span></h2>
            </div>
            <p>Our mission is to create the most reliable, fast, and secure platform for digital goods. By working directly with creators and sellers, we ensure that our marketplace is filled with a diverse range of high-quality, ready-to-use products. We're here to serve customers who value convenience, speed, and quality, making your digital purchasing experience simple and enjoyable.</p>
        </div>
    </section>

    <!-- Why Choose Byte Market Section -->
    <section class="why-choose-section">
        <div class="aboutus-container">
            <div class="section-header">
                <h2>Why Choose <span class="highlight">Byte Market?</span></h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure Payments</h3>
                    <p>We prioritize your security with encrypted payment processing on your information is always safe.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Vast Product Range</h3>
                    <p>From software to libraries, tutorials, and more, we've got you covered.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎧</div>
                    <h3>24/7 Support</h3>
                    <p>Got a question? Our team is available to assist you at any time.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Instant Access</h3>
                    <p>Forget waiting for shipping your products are available immediately after purchase.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🖱️</div>
                    <h3>Easy to Use</h3>
                    <p>Our user-friendly interface makes browsing and purchasing digital products in browser</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌟</div>
                    <h3>Community Driven</h3>
                    <p>Join our growing community of creators and customers dedicated to quality digital goods.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="aboutus-container">
            <div class="cta-content">
                <div class="cta-illustration">
                    <svg viewBox="0 0 200 300" width="200" height="300">
                        <!-- Shopping Bag Illustration -->
                        <rect x="50" y="80" width="100" height="120" rx="8" fill="#249E2F" opacity="0.9"/>
                        <path d="M 75 80 Q 75 60 100 60 Q 125 60 125 80" stroke="#249E2F" stroke-width="8" fill="none" stroke-linecap="round"/>
                        <circle cx="75" cy="100" r="8" fill="white" opacity="0.4"/>
                        <circle cx="100" cy="110" r="8" fill="white" opacity="0.4"/>
                        <circle cx="125" cy="100" r="8" fill="white" opacity="0.4"/>
                        <path d="M 100 140 L 100 180" stroke="white" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="cta-text">
                    <h2>Join the <span class="highlight">Byte Market</span> Community</h2>
                    <p>Get started with Byte Market today. Sign up to explore, purchase, and download your favorite digital products in seconds. We're here to simplify the way you shop for digital goods.</p>
                    <a href="<?= base_url('registration') ?>" class="cta-button">START NOW</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (add if needed) -->
</body>
</html>
