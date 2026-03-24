<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - ByteMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --page-blue: #308BE5;
            --card-dark: #222631;
            --card-shadow: rgba(0, 0, 0, 0.24);
            --white: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #5b6473;
            --green: #1fa14d;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--page-blue);
            color: var(--text-dark);
        }

        body {
            padding-top: 88px;
            min-height: 100vh;
        }

        .top-categories {
            height: 56px;
            background: #000000;
            overflow-x: auto;
            scrollbar-width: none;
            position: sticky;
            top: 88px;
            z-index: 900;
        }

        .top-categories::-webkit-scrollbar {
            display: none;
        }

        .top-categories-list {
            list-style: none;
            display: flex;
            align-items: stretch;
            justify-content: center;
            min-width: max-content;
            height: 56px;
        }

        .top-categories-list li {
            border-right: 1px solid #2a2a2a;
        }

        .top-cat-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-width: 98px;
            height: 56px;
            padding: 0 14px;
            color: #ffffff;
            text-decoration: none;
            font-size: 15px;
            line-height: 1.05;
            font-weight: 500;
        }

        .top-cat-link.active {
            background: #21a52f;
            font-weight: 700;
        }

        .faq-wrap {
            max-width: 980px;
            margin: 0 auto;
            padding: 48px 16px 56px;
        }

        .faq-title {
            margin: 0 0 26px;
            text-align: center;
            color: var(--white);
            font-size: clamp(30px, 5vw, 52px);
            font-weight: 800;
            letter-spacing: 0.4px;
            text-shadow: 0 4px 0 rgba(0, 0, 0, 0.2);
        }

        .faq-topics {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 28px;
            max-width: 760px;
            margin-left: auto;
            margin-right: auto;
        }

        .topic-btn {
            border: none;
            border-radius: 10px;
            background: var(--card-dark);
            color: #dbe2ee;
            padding: 16px 10px 14px;
            min-height: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 16px var(--card-shadow);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            text-align: center;
            max-width: 132px;
            width: 100%;
            justify-self: center;
        }

        .topic-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(0, 0, 0, 0.28);
        }

        .topic-btn.active {
            background: #0f131a;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2), 0 14px 24px rgba(0, 0, 0, 0.3);
        }

        .topic-icon {
            width: 44px;
            height: 44px;
            color: #e7edf8;
        }

        .topic-label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 860px;
            margin: 0 auto;
        }

        .faq-item {
            border-radius: 8px;
            background: #f3f4f6;
            box-shadow: 0 8px 14px rgba(8, 47, 73, 0.2);
            overflow: hidden;
        }

        .faq-item.open {
            background: #f7f8fa;
        }

        .faq-q {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
            padding: 16px 18px;
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        .faq-item.open .faq-q {
            color: var(--green);
        }

        .faq-chevron {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            color: #374151;
            transition: transform 0.2s ease;
        }

        .faq-item.open .faq-chevron {
            transform: rotate(180deg);
            color: var(--green);
        }

        .faq-a {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease;
            padding: 0 18px;
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.65;
        }

        .faq-item.open .faq-a {
            max-height: 220px;
            padding-bottom: 16px;
        }

        @media (max-width: 920px) {
            .faq-topics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .faq-q {
                font-size: 14px;
                padding: 14px 14px;
            }

            .faq-a {
                font-size: 13px;
                padding-left: 14px;
                padding-right: 14px;
            }
        }

        @media (max-width: 520px) {
            .faq-wrap {
                padding-top: 30px;
                padding-bottom: 36px;
            }

            .faq-topics {
                grid-template-columns: 1fr;
            }

            .topic-btn {
                min-height: 96px;
            }

            .top-categories {
                top: 80px;
            }

            .top-cat-link {
                min-width: 84px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<?php
    $topCategories = [
        'templates' => 'Templates',
        'study-productivity' => 'Study & Productivity',
        'design-assets' => 'Design Assets',
        'e-books' => 'E-books',
        'printables' => 'Printables',
        'presentation-slides' => 'Presentation Slides',
        'marketing-materials' => 'Marketing Materials',
        'business-finance-tools' => 'Business & Finance Tools',
        'creative-packs' => 'Creative Packs',
    ];

    $selectedTopCategory = trim((string) ($selectedTopCategory ?? service('request')->getGet('top') ?? 'presentation-slides'));
    if (!array_key_exists($selectedTopCategory, $topCategories)) {
        $selectedTopCategory = 'presentation-slides';
    }
?>
    <nav class="top-categories" aria-label="Main categories">
        <ul class="top-categories-list">
            <?php foreach ($topCategories as $slug => $label): ?>
                <li>
                    <a
                        href="<?= base_url('home?top=' . urlencode($slug) . '&group=all') ?>"
                        class="top-cat-link <?= $selectedTopCategory === $slug ? 'active' : '' ?>"
                        <?= $selectedTopCategory === $slug ? 'aria-current="page"' : '' ?>
                    >
                        <?= esc($label) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <main class="faq-wrap">
        <h1 class="faq-title">Frequently Asked Questions</h1>

        <div class="faq-topics" role="tablist" aria-label="FAQ Topics">
            <button class="topic-btn active" type="button" aria-label="General Questions">
                <svg class="topic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="4"></circle><path d="M2 21c0-3.5 3-6 7-6"></path><circle cx="17" cy="8" r="3"></circle><path d="M14 21c.3-2.3 2.2-4 4.6-4 1.4 0 2.7.5 3.4 1.4"></path><path d="M11 13h2m-1-1v2"></path></svg>
                <span class="topic-label">General Questions</span>
            </button>
            <button class="topic-btn" type="button" aria-label="Payment and Security">
                <svg class="topic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 5-3.5 8-7 9-3.5-1-7-4-7-9V6l7-3z"></path><path d="M9 12l2 2 4-4"></path></svg>
                <span class="topic-label">Payment &amp; Security</span>
            </button>
            <button class="topic-btn" type="button" aria-label="Downloads">
                <svg class="topic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 16.5A4.5 4.5 0 0 0 17 8h-1A6 6 0 0 0 4 9.5"></path><path d="M12 12v8"></path><path d="m8.5 16.5 3.5 3.5 3.5-3.5"></path></svg>
                <span class="topic-label">Downloads</span>
            </button>
            <button class="topic-btn" type="button" aria-label="Account">
                <svg class="topic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 20a8 8 0 0 1 16 0"></path><path d="m16.7 15.8 1.6 1.6 3-3"></path></svg>
                <span class="topic-label">Account</span>
            </button>
        </div>

        <section class="faq-list" aria-label="FAQ Accordion">
            <article class="faq-item">
                <button class="faq-q" type="button" aria-expanded="false">HOW THIS APP WORKS?
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.167l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <div class="faq-a">
                    ByteMarket lets buyers discover digital products, complete checkout, and instantly access files or links from My Orders after successful payment.
                </div>
            </article>

            <article class="faq-item">
                <button class="faq-q" type="button" aria-expanded="false">WHAT KIND OF PRODUCTS ARE AVAILABLE ON BYTE MARKET?
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.167l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <div class="faq-a">
                    You can find templates, printables, study tools, design assets, and downloadable resources for personal, school, and business use.
                </div>
            </article>

            <article class="faq-item open">
                <button class="faq-q" type="button" aria-expanded="true">DO I NEED AN ACCOUNT TO BROWSE PRODUCTS?
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.167l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <div class="faq-a">
                    You can browse categories without an account, but creating an account is required to purchase products, view notifications, and access downloads.
                </div>
            </article>

            <article class="faq-item">
                <button class="faq-q" type="button" aria-expanded="false">IS THIS APP FOR FREE?
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.167l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <div class="faq-a">
                    Creating an account is free. Product pricing depends on each seller listing; some resources may be free while premium products are paid.
                </div>
            </article>

            <article class="faq-item">
                <button class="faq-q" type="button" aria-expanded="false">CAN I GET ASSISTANT?
                    <svg class="faq-chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.167l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <div class="faq-a">
                    Yes. If you need help with your account, payment, or downloads, contact support through the platform's help channels.
                </div>
            </article>
        </section>
    </main>

    <script>
        document.querySelectorAll('.faq-q').forEach((button) => {
            button.addEventListener('click', () => {
                const item = button.closest('.faq-item');
                if (!item) return;

                const isOpen = item.classList.contains('open');
                document.querySelectorAll('.faq-item').forEach((el) => {
                    el.classList.remove('open');
                    const btn = el.querySelector('.faq-q');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                });

                if (!isOpen) {
                    item.classList.add('open');
                    button.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.querySelectorAll('.topic-btn').forEach((button) => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.topic-btn').forEach((el) => el.classList.remove('active'));
                button.classList.add('active');
            });
        });
    </script>
</body>
</html>
