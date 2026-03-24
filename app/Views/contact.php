<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Byte Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #fff;
            color: #121212;
        }

        body { padding-top: 72px; }

        .contact-page {
            min-height: calc(100vh - 72px);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 60px 80px;
        }

        /* Logo watermark — absolute, left side */
        .deco-phone {
            position: absolute;
            left: -40px;
            top: 50%;
            transform: translateY(-46%);
            width: 700px;
            opacity: 0.10;
            pointer-events: none;
            user-select: none;
        }

        /* Decorative accents */
        .deco-triangle {
            position: absolute;
            top: 55px;
            left: 38%;
            width: 0; height: 0;
            border-left: 18px solid transparent;
            border-right: 18px solid transparent;
            border-bottom: 30px solid #aaa;
            opacity: 0.5;
            pointer-events: none;
        }

        .deco-circle {
            position: absolute;
            top: 70px;
            right: 50px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: #c2d9ef;
            opacity: 0.70;
            pointer-events: none;
        }

        .deco-rect {
            position: absolute;
            bottom: 68px;
            left: 38%;
            width: 210px; height: 18px;
            background: #c5e8c5;
            opacity: 0.85;
            pointer-events: none;
        }

        /* Two-column layout */
        .contact-inner {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 40px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        /* Left: text over the phone watermark */
        .contact-info { padding-right: 10px; }

        .contact-title {
            font-size: 52px;
            font-weight: 700;
            line-height: 1.1;
            color: #111;
            margin-bottom: 18px;
        }

        .contact-desc {
            font-size: 14px;
            line-height: 1.75;
            color: #333;
            margin-bottom: 26px;
            max-width: 340px;
        }

        .contact-email-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #111;
        }

        .contact-email-row svg { flex-shrink: 0; color: #2f80d0; }

        /* Right: form */
        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 13px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            background: #e9e9e9;
            border: none;
            outline: none;
            border-radius: 8px;
            padding: 14px 18px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: #222;
            resize: none;
            transition: background 0.15s;
        }

        .contact-form input:focus,
        .contact-form textarea:focus { background: #e2e2e2; }

        .contact-form input::placeholder,
        .contact-form textarea::placeholder { color: #999; }

        .contact-form textarea { height: 130px; }

        .contact-form .btn-submit {
            align-self: flex-end;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px 48px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.18s;
        }

        .contact-form .btn-submit:hover { background: #2f80d0; }

        /* Alerts */
        .contact-alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .contact-alert-success { background: #d4edda; color: #196032; border: 1px solid #b0dfc0; }
        .contact-alert-error   { background: #fde8e8; color: #9b1c1c; border: 1px solid #f5c2c2; }

        .contact-errors {
            list-style: none;
            padding: 10px 14px;
            background: #fde8e8;
            border: 1px solid #f5c2c2;
            border-radius: 8px;
            color: #9b1c1c;
            font-size: 13px;
            line-height: 1.7;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .contact-page { padding: 40px 30px; }
            .contact-inner { grid-template-columns: 1fr; gap: 36px; }
            .contact-title { font-size: 38px; }
            .deco-phone { width: 180px; opacity: 0.18; }
        }

        @media (max-width: 560px) {
            body { padding-top: 60px; }
            .contact-page { padding: 30px 18px; }
            .contact-title { font-size: 30px; }
            .contact-form .btn-submit { align-self: stretch; text-align: center; }
        }
    </style>
</head>
<body>

<div class="contact-page">

    <!-- Logo watermark -->
    <img class="deco-phone" src="<?= base_url('assets/images/LOGO (1).png') ?>" alt="">

    <!-- Decorative shapes -->
    <div class="deco-triangle"></div>
    <div class="deco-circle"></div>
    <div class="deco-rect"></div>

    <div class="contact-inner">

        <!-- Left: title, desc, email -->
        <div class="contact-info">
            <h1 class="contact-title">Contact us</h1>
            <p class="contact-desc">We'd love to hear from you! Whether you have a question, need support, or have feedback, feel free to reach out. Our team is here to help.</p>
            <div class="contact-email-row">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                bytemarket730@gmail.com
            </div>
        </div>

        <!-- Right: form -->
        <div>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="contact-alert contact-alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="contact-alert contact-alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php $formErrors = session()->getFlashdata('errors') ?? []; ?>

            <form class="contact-form" action="<?= base_url('header/contact') ?>" method="post">
                <?= csrf_field() ?>
                <input type="text"  name="full_name"      placeholder="Full Name"          value="<?= esc(old('full_name')) ?>"      required>
                <input type="email" name="email"          placeholder="example@gmail.com"  value="<?= esc(old('email')) ?>"          required>
                <input type="tel"   name="contact_number" placeholder="Contact Number:"    value="<?= esc(old('contact_number')) ?>">
                <input type="text"  name="subject"        placeholder="Subject"            value="<?= esc(old('subject')) ?>">
                <textarea name="message" placeholder="Your Message:" required><?= esc(old('message')) ?></textarea>
                <?php if (!empty($formErrors)): ?>
                    <ul class="contact-errors">
                        <?php foreach ($formErrors as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <button type="submit" class="btn-submit">Submit</button>
            </form>
        </div>

    </div>
</div>

<?= view('footer') ?>

</body>
</html>
