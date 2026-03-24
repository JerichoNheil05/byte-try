<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Byte Market</title>
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
            background: #e9e9e9;
            color: #101010;
        }

        body {
            padding-top: 80px;
            overflow-x: hidden;
        }

        .home-shell {
            min-height: calc(100vh - 80px);
            background: #e9e9e9;
        }

        .top-categories {
            height: 56px;
            background: #000000;
            overflow-x: auto;
            scrollbar-width: none;
            position: sticky;
            top: 80px;
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

        .home-layout {
            display: grid;
            grid-template-columns: 110px minmax(0, 1fr);
            min-height: calc(100vh - 136px);
        }

        .filters-panel {
            background: #21a52f;
            padding: 18px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 44px;
        }

        .filter-toggle {
            border: 1px solid #bff0c4;
            background: transparent;
            color: #ffffff;
            border-radius: 16px;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            width: 98px;
        }

        .filter-list {
            display: flex;
            flex-direction: column;
            gap: 28px;
            width: 100%;
        }

        .filter-link {
            color: #ffffff;
            text-decoration: none;
            text-align: center;
            font-size: 13px;
            line-height: 1.12;
            font-weight: 500;
        }

        .filter-link.active {
            color: #000000;
            font-weight: 700;
        }

        .products-stage {
            position: relative;
            padding: 72px 44px 42px;
            overflow: hidden;
            background: #ececec;
        }

        .show-filters-floating {
            display: none;
            position: relative;
            z-index: 2;
            border: 1px solid #8e8e8e;
            background: #ffffff;
            color: #2c2c2c;
            border-radius: 16px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 18px;
            width: fit-content;
        }

        .shape-circle {
            position: absolute;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: #c4d3e8;
            top: 10px;
            right: 90px;
            z-index: 0;
        }

        .shape-line {
            position: absolute;
            width: 230px;
            height: 26px;
            background: #d3e0cf;
            transform: rotate(-30deg);
            top: 210px;
            right: -40px;
            z-index: 0;
        }

        .shape-triangle-a,
        .shape-triangle-b {
            position: absolute;
            width: 0;
            height: 0;
            border-left: 34px solid transparent;
            border-right: 34px solid transparent;
            border-top: 54px solid #c3c3c3;
            z-index: 0;
        }

        .shape-triangle-a {
            bottom: -12px;
            left: 160px;
            transform: rotate(18deg);
        }

        .shape-triangle-b {
            bottom: 22px;
            right: 26px;
            transform: rotate(-35deg);
        }

        .cards-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px 26px;
            max-width: 1170px;
            transition: opacity 0.18s ease;
        }

        .cards-grid.loading {
            opacity: 0.4;
            pointer-events: none;
        }

        .empty-products {
            grid-column: 1 / -1;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #d6d6d6;
            padding: 24px;
            font-size: 15px;
            color: #4b5563;
            text-align: center;
        }

        .product-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.16);
            border: 1px solid #d6d6d6;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
        }

        .product-thumb {
            width: 100%;
            height: 168px;
            object-fit: cover;
            background: linear-gradient(135deg, #dae3f2, #edf2f9);
        }

        .product-info {
            padding: 8px 10px 9px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .product-title {
            font-size: 14px;
            font-weight: 500;
            color: #0f0f0f;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .seller {
            font-size: 11px;
            color: #6b6b6b;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            min-width: 0;
        }

        .seller i {
            font-size: 10px;
            color: #3e3e3e;
        }

        .rating {
            font-size: 11px;
            color: #6b6b6b;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
        }

        .rating i {
            color: #f0b400;
            font-size: 11px;
        }

        .filters-collapsed .home-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .filters-collapsed .filters-panel {
            display: none;
        }

        .filters-collapsed .show-filters-floating {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .filters-collapsed .cards-grid {
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 1280px) {
            .cards-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .home-layout {
                grid-template-columns: 1fr;
            }

            .filters-panel {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                padding: 12px 14px;
            }

            .filter-list {
                flex-direction: row;
                gap: 16px;
                justify-content: flex-end;
            }

            .filter-link {
                font-size: 14px;
            }

            .products-stage {
                padding: 20px 16px 26px;
            }

            .show-filters-floating {
                margin-bottom: 14px;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .top-categories {
                top: 72px;
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

    $leftGroups = [
        'academic' => 'Academic',
        'creative' => 'Creative & Entertainment',
        'report' => 'Report',
    ];

    $selectedTopCategory = $selectedTopCategory ?? 'presentation-slides';
    $selectedGroup       = $selectedGroup       ?? 'all';
    $availableTops       = $availableTops       ?? [];
    $availableGroups     = $availableGroups     ?? [];
?>
    <div class="home-shell" id="homeShell">
        <nav class="top-categories" aria-label="Main categories">
            <ul class="top-categories-list">
                <?php foreach ($topCategories as $slug => $label): ?>
                    <li>
                        <a
                            href="<?= base_url('home?top=' . urlencode($slug) . '&group=' . urlencode($selectedGroup)) ?>"
                            class="top-cat-link <?= $selectedTopCategory === $slug ? 'active' : '' ?>"
                            <?= $selectedTopCategory === $slug ? 'aria-current="page"' : '' ?>
                            data-top="<?= esc($slug) ?>"
                        >
                            <?= esc($label) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="home-layout">
            <aside class="filters-panel" aria-label="Sidebar filters">
                <button type="button" class="filter-toggle" id="toggleFiltersBtn">Hide Filters</button>

                <div class="filter-list">
                    <?php foreach ($leftGroups as $slug => $label): ?>
                        <a
                            href="<?= base_url('home?top=' . urlencode($selectedTopCategory) . '&group=' . urlencode($slug)) ?>"
                            class="filter-link <?= $selectedGroup === $slug ? 'active' : '' ?>"
                            data-group="<?= esc($slug) ?>"
                        >
                            <?= esc($label) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </aside>

            <main class="products-stage" aria-label="Products area">
                <button type="button" class="show-filters-floating" id="showFiltersBtn">Show Filters</button>

                <span class="shape-circle" aria-hidden="true"></span>
                <span class="shape-line" aria-hidden="true"></span>
                <span class="shape-triangle-a" aria-hidden="true"></span>
                <span class="shape-triangle-b" aria-hidden="true"></span>

                <section class="cards-grid" id="cardsGrid">
                    <?php foreach (($products ?? []) as $product): ?>
                        <a class="product-card" href="<?= (int) ($product['id'] ?? 0) > 0 ? base_url('home/product/' . (int) $product['id']) : '#' ?>">
                            <?php if (!empty($product['thumbnail_url'])): ?>
                                <img
                                    class="product-thumb"
                                    src="<?= esc($product['thumbnail_url']) ?>"
                                    alt="<?= esc($product['title'] ?? 'Product') ?>"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3ETemplate%3C/text%3E%3C/svg%3E';"
                                >
                            <?php else: ?>
                                <img
                                    class="product-thumb"
                                    src="data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3ETemplate%3C/text%3E%3C/svg%3E"
                                    alt="<?= esc($product['title'] ?? 'Product') ?>"
                                >
                            <?php endif; ?>

                            <div class="product-info">
                                <div class="product-title"><?= esc($product['title'] ?? 'Untitled Product') ?></div>
                                <div class="product-meta">
                                    <span class="seller"><span><?= esc($product['seller'] ?? 'MCreateArts') ?></span> <i class="fas fa-check-circle" aria-hidden="true"></i></span>
                                    <span class="rating"><i class="fas fa-star" aria-hidden="true"></i><?= number_format((float) ($product['rating'] ?? 0), 1) ?>(<?= (int) ($product['reviews'] ?? 0) ?>)</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </section>
            </main>
        </div>
    </div>

    <script>
        const homeShell = document.getElementById('homeShell');
        const toggleFiltersBtn = document.getElementById('toggleFiltersBtn');
        const showFiltersBtn = document.getElementById('showFiltersBtn');
        const cardsGrid = document.getElementById('cardsGrid');
        const svgPlaceholder = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 360%22%3E%3Crect width=%22640%22 height=%22360%22 fill=%22%23dae3f2%22/%3E%3Ctext x=%22320%22 y=%22188%22 text-anchor=%22middle%22 fill=%22%236b7280%22 font-size=%2230%22 font-family=%22Arial,sans-serif%22%3ETemplate%3C/text%3E%3C/svg%3E';

        // Track current filter state so partial changes (e.g. only switching group)
        // always know the other parameter's current value.
        let currentTop   = '<?= esc($selectedTopCategory, 'js') ?>';
        let currentGroup = '<?= esc($selectedGroup, 'js') ?>';
        let currentQuery = '<?= esc($searchQuery, 'js') ?>';
        let isLoading    = false;

        // Availability data seeded from the server on initial page load.
        const initialAvailableTops   = <?= json_encode(array_values($availableTops)) ?>;
        const initialAvailableGroups = <?= json_encode(array_values($availableGroups)) ?>;

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderProductCard(product) {
            const id = Number(product.id || 0);
            const title = escapeHtml(product.title || 'Untitled Product');
            const seller = escapeHtml(product.seller || 'MCreateArts');
            const rating = Number(product.rating || 0).toFixed(1);
            const reviews = Number(product.reviews || 0);
            const thumbnailUrl = product.thumbnail_url ? String(product.thumbnail_url) : svgPlaceholder;
            const safeThumb = escapeHtml(thumbnailUrl);
            const href = id > 0 ? `<?= base_url('home/product') ?>/${id}` : '#';

            return `
                <a class="product-card" href="${href}">
                    <img class="product-thumb" src="${safeThumb}" alt="${title}" onerror="this.src='${svgPlaceholder}';">
                    <div class="product-info">
                        <div class="product-title">${title}</div>
                        <div class="product-meta">
                            <span class="seller"><span>${seller}</span> <i class="fas fa-check-circle" aria-hidden="true"></i></span>
                            <span class="rating"><i class="fas fa-star" aria-hidden="true"></i>${rating}(${reviews})</span>
                        </div>
                    </div>
                </a>
            `;
        }

        function renderProducts(products) {
            if (!cardsGrid) return;
            if (!Array.isArray(products) || products.length === 0) {
                cardsGrid.innerHTML = '<div class="empty-products">No matching products found.</div>';
                return;
            }
            cardsGrid.innerHTML = products.map(renderProductCard).join('');
        }

        function updateActiveStates(top, group) {
            document.querySelectorAll('.top-cat-link').forEach((link) => {
                const active = link.dataset.top === top;
                link.classList.toggle('active', active);
                if (active) link.setAttribute('aria-current', 'page');
                else link.removeAttribute('aria-current');
            });
            document.querySelectorAll('.filter-link').forEach((link) => {
                link.classList.toggle('active', link.dataset.group === group);
            });
        }

        /**
         * Availability data: kept for potential future use but currently
         * all tabs appear uniformly styled (not dimmed).
         */
        function updateAvailability(availableTops, availableGroups) {
            // No-op: all filter tabs remain uniformly styled
        }

        /**
         * Core AJAX loader — fetches products for the given top/group/query
         * and updates the grid + browser history without a page reload.
         *
         * @param {string}  top       Top-category slug
         * @param {string}  group     Left-panel group slug
         * @param {string}  query     Search query string
         * @param {boolean} pushState Whether to push a new history entry
         */
        function ajaxLoad(top, group, query, pushState = true) {
            if (isLoading) return;
            isLoading = true;
            if (cardsGrid) cardsGrid.classList.add('loading');

            const params = new URLSearchParams();
            if (top)   params.set('top',   top);
            if (group) params.set('group', group);
            if (query) params.set('q',     query);

            fetch(`<?= base_url('home/search') ?>?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then((r) => r.json())
            .then((payload) => {
                if (!payload?.success || !payload?.data) throw new Error();

                renderProducts(payload.data.products || []);
                updateActiveStates(top, group);
                updateAvailability(payload.data.available_tops || [], payload.data.available_groups || []);

                currentTop   = top;
                currentGroup = group;
                currentQuery = query;

                const url = new URL('<?= base_url('home') ?>', window.location.origin);
                if (top)   url.searchParams.set('top',   top);
                if (group && group !== 'all') url.searchParams.set('group', group);
                if (query) url.searchParams.set('q',     query);

                if (pushState) {
                    history.pushState({ top, group, query }, '', url.toString());
                } else {
                    history.replaceState({ top, group, query }, '', url.toString());
                }
            })
            .catch(() => {
                // Graceful fallback: hard-navigate so the user still gets results.
                const fallback = new URL('<?= base_url('home') ?>', window.location.origin);
                if (top)   fallback.searchParams.set('top',   top);
                if (group) fallback.searchParams.set('group', group);
                if (query) fallback.searchParams.set('q',     query);
                window.location.href = fallback.toString();
            })
            .finally(() => {
                isLoading = false;
                if (cardsGrid) cardsGrid.classList.remove('loading');
            });
        }

        // --- Top-category tab clicks ---
        document.querySelectorAll('.top-cat-link').forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                ajaxLoad(link.dataset.top, currentGroup, currentQuery);
            });
        });

        // --- Left-panel group filter clicks ---
        document.querySelectorAll('.filter-link').forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                ajaxLoad(currentTop, link.dataset.group, currentQuery);
            });
        });

        // --- Browser back / forward ---
        window.addEventListener('popstate', (e) => {
            const s = e.state;
            if (s) {
                ajaxLoad(s.top || '', s.group || 'all', s.query || '', false);
            }
        });

        // Push initial state so the first popstate has something to restore.
        history.replaceState(
            { top: currentTop, group: currentGroup, query: currentQuery },
            '',
            window.location.href
        );

        // Apply server-rendered availability on initial page load.
        updateAvailability(initialAvailableTops, initialAvailableGroups);

        // --- Header search integration ---
        // Called by the header component when the user submits a search.
        window.handleHeaderSearchAjax = function(searchQuery, filters) {
            if (!cardsGrid) return false;
            const top   = (filters?.top)   ? String(filters.top)   : currentTop;
            const group = (filters?.group) ? String(filters.group) : currentGroup;
            ajaxLoad(top, group, searchQuery);
            return true;
        };

        // --- Sidebar show/hide toggle ---
        if (homeShell && toggleFiltersBtn && showFiltersBtn) {
            let hidden = false;

            const syncFilterState = () => {
                homeShell.classList.toggle('filters-collapsed', hidden);
                toggleFiltersBtn.textContent = hidden ? 'Show Filters' : 'Hide Filters';
            };

            toggleFiltersBtn.addEventListener('click', () => { hidden = !hidden; syncFilterState(); });
            showFiltersBtn.addEventListener('click',   () => { hidden = false;   syncFilterState(); });
            syncFilterState();
        }
    </script>
</body>
</html>
