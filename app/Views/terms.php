<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - Byte Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #ececec;
            color: #141414;
        }

        body {
            padding-top: 84px;
        }

        .terms-page {
            border-top: 1px solid #d8d8d8;
            border-bottom: 1px solid #d8d8d8;
            background: #ececec;
        }

        .terms-content {
            width: 100%;
            max-width: 1220px;
            margin: 0 auto;
            padding: 34px 44px 28px;
        }

        .terms-title {
            color: #4a86c5;
            font-size: 48px;
            line-height: 1.08;
            font-weight: 700;
            letter-spacing: 0.01em;
            margin-bottom: 12px;
        }

        .terms-intro {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 18px;
            color: #171717;
        }

        .terms-rule {
            border: 0;
            border-top: 1px solid #dddddd;
            margin: 0 0 18px;
        }

        .terms-section {
            margin-bottom: 18px;
        }

        .terms-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #111111;
            margin-bottom: 8px;
        }

        .terms-lines {
            list-style: none;
            margin: 0;
            padding: 0 0 0 36px;
        }

        .terms-lines li {
            font-size: 15px;
            line-height: 1.55;
            color: #161616;
        }

        .terms-section + .terms-rule {
            margin-top: 6px;
            margin-bottom: 18px;
        }

        @media (max-width: 992px) {
            .terms-content {
                padding: 28px 24px 24px;
            }

            .terms-title {
                font-size: 38px;
            }

            .terms-intro,
            .terms-section-title,
            .terms-lines li {
                font-size: 14px;
            }

            .terms-lines {
                padding-left: 20px;
            }
        }

        @media (max-width: 640px) {
            body {
                padding-top: 76px;
            }

            .terms-content {
                padding: 20px 14px 20px;
            }

            .terms-title {
                font-size: 31px;
                margin-bottom: 10px;
            }

            .terms-intro,
            .terms-section-title,
            .terms-lines li {
                font-size: 13px;
                line-height: 1.5;
            }

            .terms-lines {
                padding-left: 12px;
            }
        }
    </style>
</head>
<body>
    <main class="terms-page" aria-labelledby="termsHeading">
        <section class="terms-content">
            <h1 class="terms-title" id="termsHeading">Terms of Use</h1>

            <p class="terms-intro">By accessing or using Byte Market, you agree to comply with the following Terms of Use:</p>
            <hr class="terms-rule" aria-hidden="true">

            <section class="terms-section" aria-labelledby="termsUserAccounts">
                <h2 class="terms-section-title" id="termsUserAccounts">User Accounts</h2>
                <ul class="terms-lines">
                    <li>Users must provide accurate information when registering.</li>
                    <li>Account credentials must be kept confidential.</li>
                    <li>Users are responsible for all activity under their account.</li>
                </ul>
            </section>
            <hr class="terms-rule" aria-hidden="true">

            <section class="terms-section" aria-labelledby="termsPlatformUse">
                <h2 class="terms-section-title" id="termsPlatformUse">Platform Use</h2>
                <ul class="terms-lines">
                    <li>The platform may be used only for lawful purposes.</li>
                    <li>Users must not engage in fraudulent, abusive, or illegal activities.</li>
                    <li>Uploading unauthorized, pirated, or harmful content is prohibited.</li>
                </ul>
            </section>
            <hr class="terms-rule" aria-hidden="true">

            <section class="terms-section" aria-labelledby="termsDigitalTransactions">
                <h2 class="terms-section-title" id="termsDigitalTransactions">Digital Product Transactions</h2>
                <ul class="terms-lines">
                    <li>All sales and purchases are electronic and considered valid upon confirmation.</li>
                    <li>Download links for purchased products may have limits or expiration dates.</li>
                    <li>Users must comply with licensing terms for digital products.</li>
                </ul>
            </section>
            <hr class="terms-rule" aria-hidden="true">

            <section class="terms-section" aria-labelledby="termsUserObligations">
                <h2 class="terms-section-title" id="termsUserObligations">User Obligations</h2>
                <ul class="terms-lines">
                    <li>Buyers: Ensure compatibility and safekeeping of downloaded products.</li>
                    <li>Sellers: Provide accurate product information and respond to buyer concerns.</li>
                </ul>
            </section>
            <hr class="terms-rule" aria-hidden="true">

            <section class="terms-section" aria-labelledby="termsPlatformRights">
                <h2 class="terms-section-title" id="termsPlatformRights">Platform Rights</h2>
                <ul class="terms-lines">
                    <li>The platform reserves the right to suspend or terminate accounts violating these Terms.</li>
                    <li>The platform may modify features, prices, or policies at any time.</li>
                </ul>
            </section>
        </section>
    </main>

    <?= view('footer') ?>
</body>
</html>
