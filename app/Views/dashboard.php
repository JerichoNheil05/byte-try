<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer Dashboard - ByteMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <style>
        :root {
            --blue: #3a8ad8;
            --green: #25a433;
            --bg: #ececec;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            min-height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--bg);
            color: #202020;
        }

        body {
            position: relative;
            overflow-x: hidden;
            padding-top: 86px;
        }

        .bg-shape {
            position: fixed;
            pointer-events: none;
            z-index: 0;
            opacity: 0.18;
        }

        .shape-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #d7e1eb;
            top: 130px;
            right: 170px;
        }

        .shape-line-left {
            width: 250px;
            height: 16px;
            background: #d6e4d6;
            transform: rotate(50deg);
            left: 8px;
            top: 430px;
        }

        .shape-line-right {
            width: 220px;
            height: 16px;
            background: #d6e4d6;
            transform: rotate(-28deg);
            right: 32px;
            top: 430px;
        }

        .shape-triangle-small {
            width: 0;
            height: 0;
            border-left: 34px solid transparent;
            border-right: 34px solid transparent;
            border-top: 56px solid #d4dcd4;
            left: 230px;
            bottom: 52px;
            transform: rotate(35deg);
        }

        .shape-triangle-right {
            width: 0;
            height: 0;
            border-left: 36px solid transparent;
            border-right: 36px solid transparent;
            border-top: 62px solid #d4dcd4;
            right: 88px;
            bottom: 66px;
            transform: rotate(38deg);
        }

        .dashboard-shell {
            position: relative;
            z-index: 1;
            max-width: 980px;
            margin: 0 auto;
            padding: 60px 24px 76px;
        }

        .dashboard-title {
            text-align: center;
            font-size: 66px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 68px;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.14);
        }

        .dashboard-title .green {
            color: var(--green);
        }

        .dashboard-title .blue {
            color: var(--blue);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            column-gap: 26px;
            row-gap: 32px;
            justify-items: center;
            align-items: center;
            max-width: 720px;
            margin: 0 auto;
        }

        .dashboard-card {
            width: 212px;
            height: 188px;
            border-radius: 24px;
            color: var(--white);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.16);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .dashboard-card:hover,
        .dashboard-card:focus-visible {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18);
            outline: none;
        }

        .card-blue {
            background: var(--blue);
        }

        .card-green {
            background: var(--green);
        }

        .card-icon {
            width: 58px;
            height: 58px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-icon i {
            font-size: 48px;
            color: #ffffff;
        }

        .card-label {
            font-size: 23px;
            line-height: 1.12;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
        }

        .card-desc {
            font-size: 14px;
            line-height: 1.2;
            font-weight: 400;
            opacity: 0.98;
            max-width: 170px;
        }

        .dashboard-grid .dashboard-card:nth-child(2) .card-desc,
        .dashboard-grid .dashboard-card:nth-child(6) .card-desc {
            max-width: 182px;
        }

        @media (max-width: 860px) {
            .dashboard-title {
                font-size: 52px;
                margin-bottom: 48px;
            }

            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-card {
                width: 220px;
                height: 196px;
            }
        }

        @media (max-width: 600px) {
            .dashboard-shell {
                padding: 36px 16px;
            }

            .dashboard-title {
                font-size: 42px;
                margin-bottom: 34px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                max-width: 260px;
                row-gap: 20px;
            }

            .dashboard-card {
                width: 100%;
                height: 184px;
            }

            .shape-line-left,
            .shape-line-right,
            .shape-triangle-small,
            .shape-triangle-right {
                display: none;
            }
        }
    </style>
</head>
<body>
    <span class="bg-shape shape-circle" aria-hidden="true"></span>
    <span class="bg-shape shape-line-left" aria-hidden="true"></span>
    <span class="bg-shape shape-line-right" aria-hidden="true"></span>
    <span class="bg-shape shape-triangle-small" aria-hidden="true"></span>
    <span class="bg-shape shape-triangle-right" aria-hidden="true"></span>

    <main class="dashboard-shell">
        <h1 class="dashboard-title">
            <span class="green">Marketer</span>
            <span class="blue">Dashboard</span>
        </h1>

        <section class="dashboard-grid" aria-label="Marketer dashboard actions">
            <a href="<?= base_url('bytefolio') ?>" class="dashboard-card card-blue" aria-label="My ByteFolio">
                <span class="card-icon" aria-hidden="true">
                    <i class="fas fa-microchip"></i>
                </span>
                <span class="card-label">My ByteFolio</span>
                <span class="card-desc">Manage your portfolio</span>
            </a>

            <a href="<?= base_url('products/add') ?>" class="dashboard-card card-green" aria-label="Sell Products">
                <span class="card-icon" aria-hidden="true">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <span class="card-label">Sell Products</span>
                <span class="card-desc">List your products and start selling</span>
            </a>

            <a href="<?= base_url('orders') ?>" class="dashboard-card card-blue" aria-label="Orders">
                <span class="card-icon" aria-hidden="true">
                    <i class="fas fa-shopping-cart"></i>
                </span>
                <span class="card-label">Orders</span>
                <span class="card-desc">Keep track of sales and orders</span>
            </a>

            <a href="<?= base_url('analytics') ?>" class="dashboard-card card-green" aria-label="Analytics">
                <span class="card-icon" aria-hidden="true">
                    <i class="fas fa-chart-line"></i>
                </span>
                <span class="card-label">Analytics</span>
                <span class="card-desc">Your Byteship insights</span>
            </a>

            <a href="<?= base_url('wallet') ?>" class="dashboard-card card-blue" aria-label="Wallet">
                <span class="card-icon" aria-hidden="true">
                    <i class="fas fa-wallet"></i>
                </span>
                <span class="card-label">Wallet</span>
                <span class="card-desc">See all transactions and funds</span>
            </a>

            <a href="<?= base_url('products') ?>" class="dashboard-card card-green" aria-label="Product Listing">
                <span class="card-icon" aria-hidden="true">
                    <i class="far fa-credit-card"></i>
                </span>
                <span class="card-label">Product Listing</span>
                <span class="card-desc">View All Product</span>
            </a>
        </section>
    </main>

    <?= view('footer') ?>
</body>
</html>
