<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Byte Market</title>
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
            color: #121212;
        }

        body {
            padding-top: 84px;
        }

        .privacy-page {
            border-top: 1px solid #d8d8d8;
            border-bottom: 1px solid #d8d8d8;
            background: #ececec;
        }

        .privacy-content {
            width: 100%;
            max-width: 1220px;
            margin: 0 auto;
            padding: 46px 80px 42px;
        }

        .privacy-title {
            color: #4a86c5;
            font-size: 48px;
            line-height: 1.1;
            font-weight: 700;
            letter-spacing: 0.01em;
            margin-bottom: 34px;
        }

        .privacy-copy {
            font-size: 15px;
            line-height: 1.65;
            color: #202020;
            margin-bottom: 20px;
            max-width: 980px;
        }

        .privacy-copy:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 992px) {
            .privacy-content {
                padding: 34px 34px 36px;
            }

            .privacy-title {
                font-size: 38px;
                margin-bottom: 28px;
            }

            .privacy-copy {
                font-size: 14px;
                line-height: 1.6;
                margin-bottom: 16px;
            }
        }

        @media (max-width: 640px) {
            body {
                padding-top: 76px;
            }

            .privacy-content {
                padding: 24px 16px 26px;
            }

            .privacy-title {
                font-size: 30px;
                margin-bottom: 20px;
            }

            .privacy-copy {
                font-size: 13px;
                line-height: 1.55;
                margin-bottom: 14px;
            }
        }
    </style>
</head>
<body>
    <main class="privacy-page" aria-labelledby="privacyHeading">
        <section class="privacy-content">
            <h1 class="privacy-title" id="privacyHeading">Privacy Policy</h1>

            <p class="privacy-copy">
                Byte Market values privacy and is committed to protecting personal information. This Privacy Policy explains how information is collected, used, and safeguarded when using the platform.
            </p>

            <p class="privacy-copy">
                When an account is registered, personal information such as name, email address, and contact details is collected. Transaction-related information, including purchase history and payment details, as well as usage data such as activity on the platform and digital product downloads, may also be collected. This information is used solely for account management, transaction processing, customer support, and improvement of Byte Market's features and services.
            </p>

            <p class="privacy-copy">
                Personal information is not sold, traded, or shared with unauthorized third parties. Sharing of data is limited to authorized service providers, such as payment processors or legal authorities, when required for compliance with applicable laws. Reasonable measures, including encryption and monitoring, are applied to protect information from unauthorized access or breaches. Account security is the responsibility of each user, and login credentials should be safeguarded. Optional security features, such as two-factor authentication, may be enabled by users for added protection.
            </p>

            <p class="privacy-copy">
                Personal data is retained only for the period necessary to provide services, comply with legal obligations, resolve disputes, and enforce agreements, in accordance with Philippine laws. Users have the right to access, correct, or request deletion of personal information, and may opt out of marketing communications where applicable.
            </p>
        </section>
    </main>

    <?= view('footer') ?>
</body>
</html>
