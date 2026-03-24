<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet - Byte Market Seller Dashboard</title>
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
            background: #ECECEC;
        }

        /* === NAVIGATION BAR === */
        .category-nav { display: none; }

        .category-nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            gap: 32px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .category-nav-item {
            padding: 14px 0;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 500;
            color: #CCCCCC;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-nav-item:hover {
            color: #FFFFFF;
        }

        .category-nav-item.active {
            color: #FFFFFF;
        }

        /* === MAIN CONTAINER === */
        .wallet-wrapper {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 52px 24px 40px;
            overflow: hidden;
        }

        .wallet-bg-circle {
            position: absolute;
            right: 190px;
            top: 38px;
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: #d8dee6;
            pointer-events: none;
            z-index: 0;
        }

        .wallet-bg-line-right,
        .wallet-bg-line-left {
            position: absolute;
            height: 24px;
            background: #d9e4dc;
            opacity: 0.72;
            pointer-events: none;
            z-index: 0;
            transform: rotate(-30deg);
        }

        .wallet-bg-line-right {
            right: 18px;
            top: 238px;
            width: 250px;
        }

        .wallet-bg-line-left {
            left: -18px;
            top: 322px;
            width: 230px;
        }

        /* === WALLET SECTION === */
        .wallet-section {
            position: relative;
            z-index: 1;
            background: transparent;
            padding: 26px 8px 24px;
            border-radius: 0;
            margin-bottom: 40px;
            box-shadow: none;
        }

        .wallet-hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
        }

        .wallet-main {
            min-width: 0;
        }

        .wallet-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .wallet-icon {
            font-size: 56px;
            color: #000000;
        }

        .wallet-title {
            font-size: 52px;
            font-weight: 700;
            color: #000000;
            line-height: 1;
        }

        .wallet-balance {
            font-size: 72px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 8px;
            line-height: 1;
        }

        .wallet-label {
            font-size: 34px;
            color: #2d2d2d;
            font-weight: 500;
            margin-bottom: 0;
            line-height: 1.1;
        }

        .wallet-actions {
            padding-top: 18px;
            flex-shrink: 0;
        }

        /* === CASH OUT BUTTON === */
        .cashout-btn {
            background: #3589d8;
            color: #FFFFFF;
            border: none;
            padding: 12px 42px;
            border-radius: 10px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 28px;
            font-weight: 600;
            text-transform: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0;
            min-width: 370px;
            min-height: 92px;
            justify-content: center;
        }

        .cashout-btn:hover {
            background: #2f79c0;
            box-shadow: 0 6px 16px rgba(47, 121, 192, 0.32);
        }

        .cashout-btn:focus-visible {
            outline: 3px solid #249E2F;
            outline-offset: 2px;
        }

        .cashout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* === DIVIDER === */
        .section-divider {
            height: 0;
            margin: 10px 0 26px;
        }

        /* === ORDERS SECTION === */
        .orders-section {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .orders-title {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 32px;
            text-align: center;
        }

        /* === TABLE CONTAINER === */
        .table-container {
            overflow-x: auto;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table thead {
            background: #F5F5F5;
            border-bottom: 2px solid #EEEEEE;
        }

        .transactions-table th {
            padding: 16px 20px;
            text-align: left;
            font-size: 16px;
            font-weight: 500;
            color: #000000;
            text-transform: capitalize;
        }

        .transactions-table tbody tr {
            border-bottom: 1px solid #EEEEEE;
            transition: all 0.3s ease;
        }

        .transactions-table tbody tr:hover {
            background: #F9F9F9;
        }

        .transactions-table td {
            padding: 16px 20px;
            font-size: 14px;
            color: #000000;
        }

        .buyer-name {
            font-weight: 600;
            color: #000000;
        }

        .product-name {
            color: #666666;
            max-width: 250px;
            word-wrap: break-word;
        }

        .transaction-date {
            color: #999999;
        }

        .transaction-amount {
            font-weight: 600;
            color: #249E2F;
        }

        /* === ACTION LINK === */
        .preview-link {
            color: #308BE5;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .preview-link:hover {
            color: #2568c2;
            background: rgba(48, 139, 229, 0.1);
        }

        .preview-link:focus-visible {
            outline: 2px solid #308BE5;
            outline-offset: 2px;
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 64px;
            color: #CCCCCC;
            margin-bottom: 16px;
        }

        .empty-state-text {
            font-size: 16px;
            color: #666666;
        }

        /* === MODALS === */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(6px);
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999999;
            transition: all 0.3s ease;
            padding: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: #000000;
        }

        /* === PREVIEW MODAL === */
        .preview-modal .modal-content {
            max-width: 560px;
            padding: 32px 32px 28px;
            border-radius: 18px;
        }

        .receipt-list {
            display: grid;
            gap: 12px;
            margin-bottom: 20px;
        }

        .receipt-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 12px;
            align-items: start;
            font-size: 14px;
        }

        .receipt-label {
            color: #000000;
            font-weight: 600;
        }

        .receipt-value {
            color: #000000;
            font-weight: 500;
            word-break: break-word;
        }

        .receipt-product {
            margin: 8px 0 16px;
        }

        .receipt-product-title {
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 10px;
        }

        .receipt-thumbnail {
            width: 220px;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #EEEEEE;
            background: #FFFFFF;
            display: block;
        }

        .receipt-footer {
            display: grid;
            gap: 10px;
            margin-top: 8px;
        }

        .receipt-amount {
            color: #249E2F;
            font-weight: 600;
        }

        /* === FORM FIELDS === */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 8px;
        }

        .form-input,
        .form-select {
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

        .form-input:focus,
        .form-select:focus {
            outline: none;
            background: #FFFFFF;
            border-color: #308BE5;
            box-shadow: 0 0 0 3px rgba(48, 139, 229, 0.1);
        }

        /* === MODAL BUTTONS === */
        .modal-buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn-cancel {
            background: #F5F5F5;
            color: #000000;
            border: 1px solid #EEEEEE;
            padding: 12px 24px;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-cancel:hover {
            background: #EEEEEE;
        }

        .btn-confirm {
            background: #308BE5;
            color: #FFFFFF;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-confirm:hover {
            background: #2568c2;
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.3);
        }

        /* === METHOD SELECTION MODAL === */
        .method-selection-modal .modal-content {
            max-width: 420px;
            padding: 32px 28px;
            border-radius: 16px;
        }

        .method-selection-modal .modal-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 24px;
            text-align: center;
        }

        .method-options {
            display: grid;
            gap: 12px;
            margin-bottom: 24px;
        }

        .method-option {
            display: flex;
            align-items: center;
            padding: 16px 18px;
            border: 2px solid #E8E8E8;
            border-radius: 10px;
            background: #FFFFFF;
            cursor: pointer;
            transition: all 0.3s ease;
            gap: 12px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 15px;
            font-weight: 500;
            color: #000000;
        }

        .method-option:hover {
            border-color: #CCCCCC;
            background: #FAFAFA;
        }

        .method-option input[type="radio"] {
            cursor: pointer;
            width: 20px;
            height: 20px;
            accent-color: #308BE5;
        }

        .method-option.selected {
            border-color: #308BE5;
            border-width: 2px;
            background: #F0F7FF;
        }

        .method-option .method-label {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .method-option .method-label i {
            font-size: 18px;
            color: #308BE5;
        }

        .method-option.selected .method-label i {
            color: #308BE5;
        }

        .method-selection-buttons {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .method-selection-buttons .btn-cancel {
            background: #F5F5F5;
            color: #000000;
            border: 1px solid #EEEEEE;
            padding: 14px 24px;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .method-selection-buttons .btn-cancel:hover {
            background: #EEEEEE;
        }

        .method-selection-buttons .btn-confirm {
            background: #249E2F;
            color: #FFFFFF;
            border: none;
            padding: 14px 24px;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
            flex: 1;
        }

        .method-selection-buttons .btn-confirm:hover {
            background: #1d7a24;
            box-shadow: 0 4px 12px rgba(36, 158, 47, 0.3);
        }

        .method-selection-buttons .btn-confirm:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* === CASH OUT METHOD SETUP MODAL === */
        .cashout-setup-modal .modal-content {
            max-width: 580px;
            padding: 36px 32px;
            border-radius: 16px;
        }

        .cashout-setup-modal .modal-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #000000;
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            padding-top: 8px;
        }

        .security-text {
            font-size: 14px;
            color: #666666;
            font-weight: 400;
        }

        .ssl-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #308BE5;
            font-weight: 600;
            font-size: 13px;
        }

        .ssl-badge i {
            font-size: 20px;
        }

        .form-field {
            margin-bottom: 24px;
        }

        .form-field label {
            display: block;
            font-size: 15px;
            font-weight: 500;
            color: #000000;
            margin-bottom: 10px;
        }

        .form-field input {
            width: 100%;
            padding: 14px 16px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 15px;
            color: #000000;
            background: #F5F5F5;
            border: 2px solid #E8E8E8;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-field input:focus {
            outline: none;
            background: #FFFFFF;
            border-color: #308BE5;
            box-shadow: 0 0 0 3px rgba(48, 139, 229, 0.1);
        }

        .form-field input.error {
            border-color: #E53935;
        }

        .form-field .error-message {
            color: #E53935;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .form-field .error-message.show {
            display: block;
        }

        .cashout-setup-button {
            width: 100%;
            background: #249E2F;
            color: #FFFFFF;
            border: none;
            padding: 16px 24px;
            border-radius: 50px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
            margin-top: 12px;
        }

        .cashout-setup-button:hover:not(:disabled) {
            background: #1d7a24;
            box-shadow: 0 6px 16px rgba(36, 158, 47, 0.3);
        }

        .cashout-setup-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 1024px) {
            .wallet-wrapper {
                padding: 24px 16px;
            }

            .wallet-title {
                font-size: 42px;
            }

            .wallet-balance {
                font-size: 60px;
            }

            .wallet-label {
                font-size: 26px;
            }

            .cashout-btn {
                min-width: 290px;
                min-height: 78px;
                font-size: 22px;
            }

            .wallet-section,
            .orders-section {
                padding: 24px 20px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }

            .wallet-wrapper {
                padding: 20px 12px;
            }

            .wallet-bg-circle,
            .wallet-bg-line-right,
            .wallet-bg-line-left {
                display: none;
            }

            .wallet-hero {
                flex-direction: column;
            }

            .wallet-actions {
                width: 100%;
                padding-top: 4px;
            }

            .wallet-title {
                font-size: 34px;
            }

            .wallet-balance {
                font-size: 52px;
            }

            .wallet-label {
                font-size: 24px;
            }

            .wallet-section,
            .orders-section {
                padding: 20px 16px;
            }

            .cashout-btn {
                width: 100%;
                min-width: 0;
                min-height: 64px;
                font-size: 20px;
            }

            .section-divider {
                margin: 24px 0;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 12px;
                font-size: 13px;
            }

            .modal-content {
                width: 95%;
                padding: 24px;
            }

            .preview-modal .modal-content {
                padding: 24px 20px;
            }

            .method-selection-modal .modal-content {
                max-width: 90%;
                padding: 24px 20px;
            }

            .method-option {
                padding: 14px 16px;
                font-size: 14px;
            }

            .method-selection-modal .modal-title {
                font-size: 18px;
                margin-bottom: 20px;
            }

            .receipt-row {
                grid-template-columns: 120px 1fr;
            }
        }

        @media (max-width: 480px) {
            body {
                padding-top: 60px;
            }

            .wallet-wrapper {
                padding: 16px 8px;
            }

            .wallet-section,
            .orders-section {
                padding: 16px 12px;
            }

            .wallet-header {
                gap: 12px;
            }

            .wallet-icon {
                font-size: 40px;
            }

            .wallet-title {
                font-size: 28px;
            }

            .wallet-balance {
                font-size: 42px;
            }

            .wallet-label {
                font-size: 19px;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 10px 8px;
                font-size: 12px;
            }

            .orders-title {
                font-size: 18px;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }

            .preview-modal .modal-content {
                padding: 20px 16px;
            }

            .modal-title {
                font-size: 20px;
            }

            .receipt-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .modal-buttons {
                flex-direction: column;
            }

            .empty-state-icon {
                font-size: 48px;
            }

            .empty-state-text {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Category Navigation -->
    <nav class="category-nav" aria-label="Product categories">
        <div class="category-nav-container">
            <a href="<?= base_url('wallet?category=templates') ?>" class="category-nav-item active">Templates</a>
            <a href="<?= base_url('wallet?category=study') ?>" class="category-nav-item">Study & Productivity</a>
            <a href="<?= base_url('wallet?category=design') ?>" class="category-nav-item">Design Assets</a>
            <a href="<?= base_url('wallet?category=ebooks') ?>" class="category-nav-item">E-books</a>
            <a href="<?= base_url('wallet?category=printables') ?>" class="category-nav-item">Printables</a>
            <a href="<?= base_url('wallet?category=presentation') ?>" class="category-nav-item">Presentation Slides</a>
            <a href="<?= base_url('wallet?category=marketing') ?>" class="category-nav-item">Marketing Materials</a>
            <a href="<?= base_url('wallet?category=business') ?>" class="category-nav-item">Business & Finance Tools</a>
            <a href="<?= base_url('wallet?category=creative') ?>" class="category-nav-item">Creative Packs</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="wallet-wrapper">
        <span class="wallet-bg-circle" aria-hidden="true"></span>
        <span class="wallet-bg-line-right" aria-hidden="true"></span>
        <span class="wallet-bg-line-left" aria-hidden="true"></span>

        <!-- Wallet Section -->
        <div class="wallet-section">
            <div class="wallet-hero">
                <div class="wallet-main">
                    <div class="wallet-header">
                        <span class="wallet-icon">
                            <i class="fas fa-wallet"></i>
                        </span>
                        <h1 class="wallet-title">My Wallet</h1>
                    </div>

                    <div class="wallet-balance">₱<?= number_format($wallet['balance'] ?? 0.00, 2) ?></div>
                    <div class="wallet-label">Balance</div>
                </div>

                <div class="wallet-actions">
                    <button type="button" class="cashout-btn" id="cashoutBtn" aria-label="Cash out from wallet">
                        Cash Out
                    </button>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="section-divider"></div>

        <!-- Orders Section -->
        <div class="orders-section">
            <h2 class="orders-title">Wallet Transactions</h2>

            <?php if(!empty($transactions)): ?>
                <div class="table-container">
                    <table class="transactions-table" role="table" aria-label="Wallet transactions">
                        <thead>
                            <tr role="row">
                                <th role="columnheader">Date</th>
                                <th role="columnheader">Description</th>
                                <th role="columnheader">Money Received</th>
                                <th role="columnheader">Withdrawal</th>
                                <th role="columnheader">Running Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($transactions as $transaction): ?>
                                <?php $isSale = ($transaction['type'] ?? 'money_received') === 'money_received'; ?>
                                <tr role="row">
                                    <td class="transaction-date"><?= !empty($transaction['transaction_date']) ? date('n/j/y', strtotime($transaction['transaction_date'])) : 'N/A' ?></td>
                                    <td><?php if ($isSale): ?>
                                        <span style="font-weight:500;"><?= htmlspecialchars($transaction['buyer_name'] ?? '') ?></span><br>
                                        <small style="color:#999;"><?= htmlspecialchars($transaction['product_name'] ?? '') ?></small>
                                    <?php else: ?>
                                        <span style="font-weight:500;"><?= htmlspecialchars($transaction['description'] ?? 'Cash Out') ?></span>
                                        <?php if (($transaction['status'] ?? '') === 'pending'): ?>
                                            <span style="margin-left:6px;font-size:.75rem;padding:2px 6px;border-radius:4px;background:#fff3cd;color:#856404;">Pending</span>
                                        <?php endif; ?>
                                    <?php endif; ?></td>
                                    <td class="transaction-amount" style="color:#38a169;font-weight:600;">
                                        <?= $isSale ? '+₱' . number_format($transaction['amount'] ?? 0, 2) : '—' ?>
                                    </td>
                                    <td style="color:#e53e3e;font-weight:600;">
                                        <?= !$isSale ? '-₱' . number_format($transaction['amount'] ?? 0, 2) : '—' ?>
                                    </td>
                                    <td class="running-balance" style="font-weight:600;">₱<?= number_format($transaction['running_balance'] ?? 0, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <p class="empty-state-text">No transactions yet. Your orders will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cash Out Method Setup Modal -->
    <div id="cashoutSetupModal" class="modal cashout-setup-modal" role="dialog" aria-labelledby="cashoutSetupTitle" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="cashoutSetupTitle">Cash Out Method: Ewallet</h2>
                <button type="button" class="modal-close" id="cashoutSetupClose" aria-label="Close dialog">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="security-badge">
                <p class="security-text">All payment methods are secure and encrypted</p>
                <div class="ssl-badge">
                    <i class="fas fa-lock"></i>
                    SSL SECURED
                </div>
            </div>

            <form id="cashoutSetupForm">
                <div class="form-field">
                    <label for="accountName">Account Name</label>
                    <input 
                        type="text" 
                        id="accountName" 
                        name="account_name" 
                        placeholder="Enter your account name"
                        required
                        aria-required="true"
                    >
                    <div class="error-message" id="accountNameError">Account name is required</div>
                </div>

                <div class="form-field">
                    <label for="cardNumber">Card Number</label>
                    <input 
                        type="text" 
                        id="cardNumber" 
                        name="card_number" 
                        placeholder="Enter your card/account number"
                        required
                        aria-required="true"
                        maxlength="20"
                    >
                    <div class="error-message" id="cardNumberError">Valid card number is required (10-20 digits)</div>
                </div>

                <button type="submit" class="cashout-setup-button" id="addCashoutMethodBtn" disabled>
                    Add Cash Out Method
                </button>
            </form>
        </div>
    </div>

    <!-- Method Selection Modal -->
    <div id="methodSelectionModal" class="modal method-selection-modal" role="dialog" aria-labelledby="methodSelectionTitle" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="methodSelectionTitle">Save Cash Out Method</h2>
                <button type="button" class="modal-close" id="methodSelectionClose" aria-label="Close dialog">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="method-options" role="group" aria-labelledby="methodSelectionTitle">
                <label class="method-option" role="radio" aria-checked="false" tabindex="0">
                    <input type="radio" name="payment_method" value="gcash" aria-label="GCash">
                    <div class="method-label">
                        <i class="fas fa-wallet"></i>
                        GCash
                    </div>
                </label>

                <label class="method-option" role="radio" aria-checked="false" tabindex="0">
                    <input type="radio" name="payment_method" value="paymaya" aria-label="Maya (formerly PayMaya)">
                    <div class="method-label">
                        <i class="fas fa-credit-card"></i>
                        Maya
                    </div>
                </label>

                <label class="method-option" role="radio" aria-checked="false" tabindex="0">
                    <input type="radio" name="payment_method" value="paypal" aria-label="PayPal">
                    <div class="method-label">
                        <i class="fab fa-paypal"></i>
                        PayPal
                    </div>
                </label>

                <label class="method-option" role="radio" aria-checked="false" tabindex="0">
                    <input type="radio" name="payment_method" value="bank_transfer" aria-label="Bank Transfer">
                    <div class="method-label">
                        <i class="fas fa-university"></i>
                        Bank Transfer
                    </div>
                </label>
            </div>

            <div class="method-selection-buttons">
                <button type="button" class="btn-cancel" id="methodSelectionCancel" aria-label="Cancel method selection">
                    Cancel
                </button>
                <button type="button" class="btn-confirm" id="methodSelectionConfirm" aria-label="Confirm selected method" disabled>
                    <i class="fas fa-check"></i> Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="modal preview-modal" role="dialog" aria-labelledby="previewModalTitle" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="previewModalTitle">Transaction Details</h2>
                <button type="button" class="modal-close" id="previewClose" aria-label="Close dialog">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="receipt-list">
                <div class="receipt-row">
                    <span class="receipt-label">Name:</span>
                    <span class="receipt-value" id="previewBuyer">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Date:</span>
                    <span class="receipt-value" id="previewDate">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Reference:</span>
                    <span class="receipt-value" id="previewReference">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Mode of payment:</span>
                    <span class="receipt-value" id="previewPayment">-</span>
                </div>
            </div>

            <div class="receipt-product">
                <div class="receipt-product-title">Product Purchased</div>
                <img id="previewThumbnail" class="receipt-thumbnail" src="" alt="Product thumbnail preview">
            </div>

            <div class="receipt-footer">
                <div class="receipt-row">
                    <span class="receipt-label">Product Name:</span>
                    <span class="receipt-value" id="previewProduct">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Amount:</span>
                    <span class="receipt-value receipt-amount" id="previewAmount">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Out Modal -->
    <div id="cashoutModal" class="modal" role="dialog" aria-labelledby="cashoutModalTitle" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="cashoutModalTitle">Cash Out</h2>
                <button type="button" class="modal-close" id="closeModal" aria-label="Close dialog">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="cashoutForm" method="POST" action="<?= base_url('wallet/cashout') ?>">
                <?= csrf_field() ?>

                <!-- Amount -->
                <div class="form-group">
                    <label for="cashoutAmount" class="form-label">Amount to Cash Out</label>
                    <input 
                        type="number" 
                        id="cashoutAmount" 
                        name="amount" 
                        class="form-input" 
                        placeholder="Enter amount"
                        step="0.01"
                        min="0.01"
                        max="<?= $wallet['balance'] ?? 0.00 ?>"
                        required
                        aria-required="true"
                    >
                    <small style="color: #999999;">Available balance: ₱<?= number_format($wallet['balance'] ?? 0.00, 2) ?></small>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label for="paymentMethod" class="form-label">Payment Method</label>
                    <select id="paymentMethod" name="payment_method" class="form-select" required aria-required="true">
                        <option value="">Select a payment method</option>
                        <option value="gcash">GCash</option>
                        <option value="paymaya">PayMaya</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <!-- Bank Transfer Fields (conditional) -->
                <div id="bankFields" style="display: none;">
                    <div class="form-group">
                        <label for="bankName" class="form-label">Bank Name</label>
                        <input 
                            type="text" 
                            id="bankName" 
                            name="bank_name" 
                            class="form-input" 
                            placeholder="e.g., BDO, BPI, Metrobank"
                        >
                    </div>

                    <div class="form-group">
                        <label for="accountNumber" class="form-label">Account Number</label>
                        <input 
                            type="text" 
                            id="accountNumber" 
                            name="account_number" 
                            class="form-input" 
                            placeholder="Your bank account number"
                        >
                    </div>

                    <div class="form-group">
                        <label for="accountHolder" class="form-label">Account Holder Name</label>
                        <input 
                            type="text" 
                            id="accountHolder" 
                            name="account_holder" 
                            class="form-input" 
                            placeholder="Name as it appears in the bank"
                        >
                    </div>
                </div>

                <!-- Wallet Details -->
                <div id="walletFields" style="display: none;">
                    <div class="form-group">
                        <label for="walletNumber" class="form-label">Phone Number</label>
                        <input 
                            type="tel" 
                            id="walletNumber" 
                            name="wallet_number" 
                            class="form-input" 
                            placeholder="09XXXXXXXXX"
                        >
                    </div>
                </div>

                <!-- Buttons -->
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn-confirm">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Wait for DOM to be fully loaded before accessing elements
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing wallet scripts');
            
            // === MODAL FUNCTIONALITY ===
            // Note: cashoutBtn event listener moved to METHOD SELECTION MODAL section below
            const cashoutModal = document.getElementById('cashoutModal');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const cashoutForm = document.getElementById('cashoutForm');

        // Close modal
        function closeModalFunc() {
            cashoutModal.classList.remove('active');
            cashoutModal.setAttribute('aria-hidden', 'true');
            cashoutForm.reset();
        }

        closeModal.addEventListener('click', closeModalFunc);
        cancelBtn.addEventListener('click', closeModalFunc);

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === cashoutModal) {
                closeModalFunc();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && cashoutModal.classList.contains('active')) {
                closeModalFunc();
            }
        });

            // === PREVIEW MODAL FUNCTIONALITY ===
            const previewModal = document.getElementById('previewModal');
            const previewClose = document.getElementById('previewClose');
            const previewBuyer = document.getElementById('previewBuyer');
            const previewDate = document.getElementById('previewDate');
            const previewReference = document.getElementById('previewReference');
            const previewPayment = document.getElementById('previewPayment');
            const previewProduct = document.getElementById('previewProduct');
            const previewAmount = document.getElementById('previewAmount');
            const previewThumbnail = document.getElementById('previewThumbnail');

            /**
             * Populate and display the preview modal with transaction data
             * @param {Object} data - Transaction data object
             */
            function openPreviewModal(data) {
                previewBuyer.textContent = data.buyer || '- ';
                previewDate.textContent = data.date || '- ';
                previewReference.textContent = data.reference || '- ';
                previewPayment.textContent = data.payment || '- ';
                previewProduct.textContent = data.product || '- ';
                previewAmount.textContent = data.amount || '- ';
                
                // Handle thumbnail image
                if (data.thumbnail) {
                    previewThumbnail.src = data.thumbnail;
                    previewThumbnail.alt = `Product thumbnail for ${data.product || 'product'}`;
                } else {
                    previewThumbnail.src = '';
                    previewThumbnail.alt = 'Product thumbnail';
                }

                previewModal.classList.add('active');
                previewModal.setAttribute('aria-hidden', 'false');
                previewClose.focus();
            }

            /**
             * Close the preview modal and return focus
             */
            function closePreviewModal() {
                previewModal.classList.remove('active');
                previewModal.setAttribute('aria-hidden', 'true');
            }

            /**
             * Load transaction details via AJAX from the backend
             * @param {string|number} transactionId - The transaction ID
             * @returns {Promise}
             */
            function loadTransactionViaAjax(transactionId) {
                return fetch(`<?= base_url('wallet/preview') ?>/${transactionId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(json => {
                    if (json.success === false) {
                        console.warn('Failed to load transaction details:', json.message);
                        return null;
                    }
                    // Map API response to data object expected by openPreviewModal
                    return {
                        buyer: json.data?.buyer_name || json.data?.buyer || '- ',
                        date: json.data?.transaction_date ? new Date(json.data.transaction_date).toLocaleDateString() : json.data?.date || '- ',
                        reference: json.data?.reference || '- ',
                        payment: json.data?.payment_method || json.data?.payment || '- ',
                        product: json.data?.product_name || json.data?.product || '- ',
                        amount: json.data?.amount ? `₱${parseFloat(json.data.amount).toFixed(2)}` : json.data?.amount || '- ',
                        thumbnail: json.data?.product_thumbnail || ''
                    };
                })
                .catch(err => {
                    console.error('Error loading transaction:', err);
                    return null;
                });
            }

            previewClose.addEventListener('click', closePreviewModal);

            window.addEventListener('click', function(e) {
                if (e.target === previewModal) {
                    closePreviewModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && previewModal.classList.contains('active')) {
                    closePreviewModal();
                }
            });

            const previewLinks = document.querySelectorAll('.preview-link');
            previewLinks.forEach(link => {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    
                    // Check if transaction ID is available for AJAX loading
                    const transactionId = link.getAttribute('data-transaction-id');
                    
                    if (transactionId) {
                        // Load data via AJAX from backend
                        const ajaxData = await loadTransactionViaAjax(transactionId);
                        if (ajaxData) {
                            openPreviewModal(ajaxData);
                            return;
                        }
                        // Fall back to data attributes if AJAX fails
                    }
                    
                    // Use data attributes from table row (fallback)
                    const data = {
                        buyer: link.getAttribute('data-buyer') || '- ',
                        date: link.getAttribute('data-date') || '- ',
                        reference: link.getAttribute('data-reference') || '- ',
                        payment: link.getAttribute('data-payment') || '- ',
                        product: link.getAttribute('data-product') || '- ',
                        amount: link.getAttribute('data-amount') || '- ',
                        thumbnail: link.getAttribute('data-thumbnail') || ''
                    };
                    openPreviewModal(data);
                });

                link.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        link.click();
                    }
                });
            });

        // === PAYMENT METHOD CONDITIONAL FIELDS ===
        const paymentMethod = document.getElementById('paymentMethod');
        const bankFields = document.getElementById('bankFields');
        const walletFields = document.getElementById('walletFields');

        paymentMethod.addEventListener('change', function() {
            if (this.value === 'bank_transfer') {
                bankFields.style.display = 'block';
                walletFields.style.display = 'none';
                document.getElementById('bankName').required = true;
                document.getElementById('accountNumber').required = true;
                document.getElementById('accountHolder').required = true;
                document.getElementById('walletNumber').required = false;
            } else if (this.value === 'gcash' || this.value === 'paymaya' || this.value === 'paypal') {
                bankFields.style.display = 'none';
                walletFields.style.display = 'block';
                document.getElementById('walletNumber').required = true;
                document.getElementById('walletNumber').placeholder = this.value === 'paypal' ? 'PayPal email or phone' : '09XXXXXXXXX';
                document.getElementById('bankName').required = false;
                document.getElementById('accountNumber').required = false;
                document.getElementById('accountHolder').required = false;
            } else {
                bankFields.style.display = 'none';
                walletFields.style.display = 'none';
                document.getElementById('bankName').required = false;
                document.getElementById('accountNumber').required = false;
                document.getElementById('accountHolder').required = false;
                document.getElementById('walletNumber').required = false;
            }
        });

        // === METHOD SELECTION MODAL FUNCTIONALITY ===
        const methodSelectionModal = document.getElementById('methodSelectionModal');
        const methodSelectionClose = document.getElementById('methodSelectionClose');
        const methodSelectionCancel = document.getElementById('methodSelectionCancel');
        const methodSelectionConfirm = document.getElementById('methodSelectionConfirm');
        const cashoutBtn = document.getElementById('cashoutBtn');
        const methodOptions = document.querySelectorAll('.method-option');
        const methodRadios = document.querySelectorAll('input[name="payment_method"]');
        let selectedMethod = null;

        // Debug: Check if elements exist
        console.log('Method Selection Modal Elements:');
        console.log('methodSelectionModal:', methodSelectionModal);
        console.log('cashoutBtn:', cashoutBtn);
        console.log('methodOptions.length:', methodOptions.length);
        console.log('methodRadios.length:', methodRadios.length);

        /**
         * Open method selection modal
         */
        function openMethodSelectionModal() {
            methodSelectionModal.classList.add('active');
            methodSelectionModal.setAttribute('aria-hidden', 'false');
            // Focus on first radio button or selected one
            const selectedRadio = document.querySelector('input[name="payment_method"]:checked');
            if (selectedRadio) {
                selectedRadio.parentElement.focus();
                selectedRadio.focus();
            } else {
                methodRadios[0].parentElement.focus();
            }
        }

        /**
         * Close method selection modal
         */
        function closeMethodSelectionModal() {
            methodSelectionModal.classList.remove('active');
            methodSelectionModal.setAttribute('aria-hidden', 'true');
            cashoutBtn.focus();
        }

        /**
         * Handle method option selection
         */
        function handleMethodSelection(radio) {
            // Uncheck all radios
            methodRadios.forEach(r => r.checked = false);
            
            // Check selected radio
            radio.checked = true;
            selectedMethod = radio.value;
            
            // Update visual selection
            methodOptions.forEach(option => {
                const input = option.querySelector('input[type="radio"]');
                if (input === radio) {
                    option.classList.add('selected');
                    option.setAttribute('aria-checked', 'true');
                } else {
                    option.classList.remove('selected');
                    option.setAttribute('aria-checked', 'false');
                }
            });
            
            // Enable confirm button
            methodSelectionConfirm.disabled = false;
        }

        /**
         * Handle confirm button click
         */
        methodSelectionConfirm.addEventListener('click', function() {
            if (selectedMethod) {
                // Store selected method
                document.getElementById('paymentMethod').value = selectedMethod;
                
                // Close method selection modal
                closeMethodSelectionModal();
            }
        });

        // Method option click handlers
        methodOptions.forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            
            option.addEventListener('click', (e) => {
                handleMethodSelection(radio);
            });
            
            option.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    handleMethodSelection(radio);
                }
            });

            radio.addEventListener('change', () => {
                handleMethodSelection(radio);
            });
        });

        // Arrow key navigation
        document.addEventListener('keydown', function(e) {
            if (methodSelectionModal.classList.contains('active')) {
                const focusedElement = document.activeElement;
                const isFocusedInMethodOptions = Array.from(methodOptions).includes(focusedElement);
                
                if (isFocusedInMethodOptions) {
                    const currentIndex = Array.from(methodOptions).indexOf(focusedElement);
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextIndex = (currentIndex + 1) % methodOptions.length;
                        methodOptions[nextIndex].focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevIndex = (currentIndex - 1 + methodOptions.length) % methodOptions.length;
                        methodOptions[prevIndex].focus();
                    }
                }
            }
        });

        // Modal close handlers
        methodSelectionClose.addEventListener('click', closeMethodSelectionModal);
        methodSelectionCancel.addEventListener('click', closeMethodSelectionModal);

        window.addEventListener('click', function(e) {
            if (e.target === methodSelectionModal) {
                closeMethodSelectionModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && methodSelectionModal.classList.contains('active')) {
                closeMethodSelectionModal();
            }
        });

        // Set GCash as default selection on load
        const gcashOption = methodRadios[0];
        if (gcashOption) {
            handleMethodSelection(gcashOption);
        }

        // === CASH OUT METHOD SETUP MODAL FUNCTIONALITY ===
        const cashoutSetupModal = document.getElementById('cashoutSetupModal');
        const cashoutSetupClose = document.getElementById('cashoutSetupClose');
        const cashoutSetupForm = document.getElementById('cashoutSetupForm');
        const accountNameInput = document.getElementById('accountName');
        const cardNumberInput = document.getElementById('cardNumber');
        const addCashoutMethodBtn = document.getElementById('addCashoutMethodBtn');
        const accountNameError = document.getElementById('accountNameError');
        const cardNumberError = document.getElementById('cardNumberError');

        /**
         * Open cash out setup modal
         */
        function openCashoutSetupModal() {
            cashoutSetupModal.classList.add('active');
            cashoutSetupModal.setAttribute('aria-hidden', 'false');
            accountNameInput.focus();
        }

        /**
         * Close cash out setup modal
         */
        function closeCashoutSetupModal() {
            cashoutSetupModal.classList.remove('active');
            cashoutSetupModal.setAttribute('aria-hidden', 'true');
            cashoutSetupForm.reset();
            accountNameInput.classList.remove('error');
            cardNumberInput.classList.remove('error');
            accountNameError.classList.remove('show');
            cardNumberError.classList.remove('show');
            addCashoutMethodBtn.disabled = true;
        }

        /**
         * Validate account name
         */
        function validateAccountName() {
            const value = accountNameInput.value.trim();
            if (value.length === 0) {
                accountNameInput.classList.add('error');
                accountNameError.classList.add('show');
                return false;
            }
            accountNameInput.classList.remove('error');
            accountNameError.classList.remove('show');
            return true;
        }

        /**
         * Validate card number (10-20 digits)
         */
        function validateCardNumber() {
            const value = cardNumberInput.value.trim();
            const isNumeric = /^[0-9]+$/.test(value);
            const validLength = value.length >= 10 && value.length <= 20;
            
            if (!isNumeric || !validLength) {
                cardNumberInput.classList.add('error');
                cardNumberError.classList.add('show');
                return false;
            }
            cardNumberInput.classList.remove('error');
            cardNumberError.classList.remove('show');
            return true;
        }

        /**
         * Validate entire form and enable/disable submit button
         */
        function validateCashoutSetupForm() {
            const accountNameValid = accountNameInput.value.trim().length > 0;
            const cardNumberValue = cardNumberInput.value.trim();
            const cardNumberValid = /^[0-9]{10,20}$/.test(cardNumberValue);
            
            addCashoutMethodBtn.disabled = !(accountNameValid && cardNumberValid);
        }

        // Input event listeners for real-time validation
        accountNameInput.addEventListener('input', function() {
            validateCashoutSetupForm();
            if (accountNameInput.value.trim().length > 0) {
                accountNameInput.classList.remove('error');
                accountNameError.classList.remove('show');
            }
        });

        accountNameInput.addEventListener('blur', validateAccountName);

        cardNumberInput.addEventListener('input', function() {
            // Only allow numeric input
            this.value = this.value.replace(/[^0-9]/g, '');
            validateCashoutSetupForm();
            if (this.value.length >= 10) {
                cardNumberInput.classList.remove('error');
                cardNumberError.classList.remove('show');
            }
        });

        cardNumberInput.addEventListener('blur', validateCardNumber);

        // Form submission
        cashoutSetupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const accountNameValid = validateAccountName();
            const cardNumberValid = validateCardNumber();
            
            if (accountNameValid && cardNumberValid) {
                const formData = {
                    account_name: accountNameInput.value.trim(),
                    card_number: cardNumberInput.value.trim(),
                    method_type: 'ewallet'
                };
                
                // TODO: Send to backend via AJAX
                fetch('<?= base_url('wallet/add_cashout_method') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cash out method added successfully!');
                        closeCashoutSetupModal();
                        // Optionally reload or update UI
                        // location.reload();
                    } else {
                        alert(data.message || 'Failed to add cash out method');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });

        // Modal close handlers
        cashoutSetupClose.addEventListener('click', closeCashoutSetupModal);

        window.addEventListener('click', function(e) {
            if (e.target === cashoutSetupModal) {
                closeCashoutSetupModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && cashoutSetupModal.classList.contains('active')) {
                closeCashoutSetupModal();
            }
        });

        // Expose function to open modal from method selection
        window.openCashoutSetupModal = openCashoutSetupModal;

        // Cashout button click handler - Opens the cashout form modal
        cashoutBtn.addEventListener('click', function() {
            cashoutModal.classList.add('active');
            cashoutModal.setAttribute('aria-hidden', 'false');
            document.getElementById('cashoutAmount').focus();
        });

        // === CASHOUT FORM — AJAX SUBMISSION ===
        cashoutForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const amount = parseFloat(document.getElementById('cashoutAmount').value);
            const maxBalance = <?= $wallet['balance'] ?? 0.00 ?>;
            const method = document.getElementById('paymentMethod').value;

            if (!amount || amount <= 0) {
                showCashoutFeedback('Please enter a valid amount.', 'error');
                return;
            }
            if (amount > maxBalance) {
                showCashoutFeedback('Amount exceeds your available balance.', 'error');
                return;
            }
            if (!method) {
                showCashoutFeedback('Please select a payment method.', 'error');
                return;
            }

            const submitBtn = cashoutForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            const formData = new FormData(cashoutForm);

            fetch('<?= base_url('wallet/cashout') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeModalFunc();
                    // Brief delay so user sees the modal close before reload
                    setTimeout(() => location.reload(), 300);
                } else {
                    showCashoutFeedback(data.message || 'Request failed. Please try again.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Confirm';
                }
            })
            .catch(() => {
                showCashoutFeedback('Network error. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirm';
            });
        });

        function showCashoutFeedback(msg, type) {
            let fb = document.getElementById('cashoutFeedback');
            if (!fb) {
                fb = document.createElement('p');
                fb.id = 'cashoutFeedback';
                fb.style.cssText = 'margin:8px 0 0;font-size:.875rem;';
                cashoutForm.prepend(fb);
            }
            fb.textContent = msg;
            fb.style.color = type === 'error' ? '#e53e3e' : '#38a169';
        }

        // === TABLE ROW HOVER ===
        const tableRows = document.querySelectorAll('.transactions-table tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const link = row.querySelector('.preview-link');
                    if (link) {
                        link.click();
                    }
                }
            });
        });

        // === CATEGORY NAVIGATION ACTIVE STATE ===
        const categoryLinks = document.querySelectorAll('.category-nav-item');
        const currentUrl = window.location.href;

        categoryLinks.forEach(link => {
            if (currentUrl.includes(link.getAttribute('href'))) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
        }); // Close main DOMContentLoaded event listener
    </script>
</body>
</html>
