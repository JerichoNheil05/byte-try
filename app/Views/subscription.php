<?= view('header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Subscription - ByteMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #308BE5;
            --green: #249E2F;
            --black: #000000;
            --white: #FFFFFF;
            --gray: #F5F5F5;
            --text-gray: #666666;
            --border-gray: #CCCCCC;
            --nav-hover: #CCCCCC;
            --bg-light: #fafbfc;
            --button-radius: 8px;
            --button-gap: 24px;
            --grid-padding: 32px;
            --shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 24px rgba(48, 139, 229, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            background: var(--white);
            color: #222;
            line-height: 1.4;
        }

        /* === NAVIGATION HEADER WRAPPER === */
        .navbar-wrapper {
            width: 100%;
            display: block;
            position: relative;
            z-index: 1000;
        }

        /* === NAVIGATION BAR === */
        .navbar {
            width: 100%;
            background: var(--black);
            display: flex !important;
            justify-content: center;
            align-items: center;
            padding: 0;
            min-height: 56px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            visibility: visible;
            overflow: visible;
        }

        .nav-list {
            display: flex !important;
            gap: 32px;
            list-style: none;
            margin: 0;
            padding: 0 32px;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }

        .nav-item {
            margin: 0;
            padding: 0;
            display: block;
        }

        .nav-item a {
            display: inline-flex;
            align-items: center;
            height: 56px;
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.01em;
            transition: color 0.2s ease;
            padding: 0 8px;
            outline: none;
            white-space: nowrap;
        }

        .nav-item a:focus,
        .nav-item a:hover {
            color: var(--nav-hover);
        }

        .nav-item a.active {
            color: var(--blue);
            border-bottom: 2px solid var(--blue);
        }

        /* === MAIN CONTAINER === */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: calc(56px + 48px) var(--grid-padding) 48px var(--grid-padding);
            min-height: 100vh;
        }

        .page-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            color: var(--black);
        }

        .page-title .title-green {
            color: var(--green);
        }

        .page-title .title-blue {
            color: var(--blue);
        }

        .page-subtitle {
            font-size: 16px;
            font-weight: 400;
            color: var(--text-gray);
            margin-top: 8px;
        }

        /* === FORM SECTIONS === */
        .form-section {
            background: var(--white);
            border-radius: var(--button-radius);
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: var(--shadow);
        }

        .form-section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--black);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .form-section-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: var(--text-gray);
            margin-bottom: 24px;
        }

        .membership-ui-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            align-items: start;
        }

        .membership-left h2,
        .membership-right h2 {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 14px;
            line-height: 1.5;
        }

        .membership-left h3 {
            font-size: 18px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 12px;
        }

        .how-it-works-list {
            list-style: disc;
            padding-left: 20px;
            color: #000000;
        }

        .how-it-works-list li {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .membership-right-subtext {
            font-size: 14px;
            color: #000000;
            margin-bottom: 18px;
        }

        .membership-price-wrap {
            margin-bottom: 18px;
        }

        .membership-price-value {
            font-size: 34px;
            font-weight: 700;
            color: #FFCC00;
            line-height: 1.2;
        }

        .membership-price-period {
            font-size: 16px;
            color: #000000;
            font-weight: 600;
        }

        .subscribe-btn-wrap {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .subscribe-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 280px;
            padding: 14px 20px;
            border: none;
            border-radius: 0;
            background: #000000;
            color: #FFFFFF;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            cursor: pointer;
        }

        .membership-checklist {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .membership-checklist li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #000000;
        }

        .check-icon {
            font-size: 16px;
            line-height: 1;
        }

        /* === FORM ELEMENTS === */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-row.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--black);
            margin-bottom: 8px;
            display: block;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }

        .form-input,
        .form-select {
            padding: 12px 16px;
            border: 1px solid var(--border-gray);
            border-radius: 6px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: var(--black);
            background: var(--gray);
            transition: border-color 0.2s ease, background 0.2s ease;
            outline: none;
        }

        .form-input::placeholder,
        .form-select::placeholder {
            color: #999;
        }

        .form-input:focus,
        .form-select:focus {
            background: var(--white);
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(48, 139, 229, 0.1);
        }

        /* === BUTTONS === */
        .btn {
            padding: 12px 32px;
            border: none;
            border-radius: var(--button-radius);
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .btn-primary {
            background: var(--blue);
            color: var(--white);
        }

        .btn-primary:hover {
            background: #2575d1;
            box-shadow: var(--shadow-hover);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-danger {
            background: #e74c3c;
            color: var(--white);
        }

        .btn-danger:hover {
            background: #c0392b;
            box-shadow: 0 8px 24px rgba(231, 76, 60, 0.18);
            transform: translateY(-1px);
        }

        .btn-danger:active {
            transform: translateY(0);
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        /* === PAYMENT METHOD GRID === */
        .payment-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 16px;
        }

        .payment-option {
            padding: 20px;
            border: 2px solid var(--border-gray);
            border-radius: var(--button-radius);
            background: var(--white);
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            position: relative;
        }

        .payment-option:hover {
            border-color: var(--blue);
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.15);
        }

        .payment-option.active {
            border-color: var(--green);
            background: rgba(36, 158, 47, 0.05);
            box-shadow: 0 4px 12px rgba(36, 158, 47, 0.15);
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .payment-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            pointer-events: none;
        }

        .payment-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .payment-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--black);
        }

        .payment-default {
            font-size: 12px;
            color: var(--text-gray);
            font-weight: 400;
        }

        .payment-add {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--blue);
            font-weight: 300;
        }

        /* === MEMBERSHIP STATUS SECTION === */
        .membership-status {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .membership-status-badge {
            padding: 6px 12px;
            background: var(--green);
            color: var(--white);
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .membership-status-info {
            flex: 1;
        }

        .membership-status-label {
            font-size: 14px;
            color: var(--text-gray);
            font-weight: 400;
        }

        .membership-status-date {
            font-size: 14px;
            color: var(--black);
            font-weight: 600;
        }

        .membership-card {
            background: var(--black);
            color: var(--white);
            border-radius: var(--button-radius);
            padding: 32px;
            margin: 24px 0;
        }

        .membership-card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .membership-card-subtitle {
            font-size: 12px;
            font-weight: 400;
            color: #999;
            margin-bottom: 24px;
        }

        .membership-price {
            font-size: 32px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 8px;
        }

        .membership-period {
            font-size: 14px;
            font-weight: 400;
            color: #999;
            margin-bottom: 24px;
        }

        .membership-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .membership-features li {
            padding: 10px 0;
            font-size: 14px;
            font-weight: 400;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .membership-features li::before {
            content: "\2611";
            color: var(--green);
            font-weight: 700;
            font-size: 16px;
        }

        .membership-actions {
            margin-top: 32px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 48px 32px;
            background: var(--white);
            border-radius: var(--button-radius);
            margin-bottom: 32px;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--black);
            margin-bottom: 8px;
        }

        .empty-state-text {
            font-size: 14px;
            color: var(--text-gray);
            margin-bottom: 24px;
        }

        .empty-state .btn {
            margin: 0 auto;
        }

        /* === ALERT MESSAGES === */
        .alert {
            padding: 16px;
            border-radius: var(--button-radius);
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(36, 158, 47, 0.1);
            border: 1px solid var(--green);
            color: var(--green);
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        .alert-info {
            background: rgba(48, 139, 229, 0.1);
            border: 1px solid var(--blue);
            color: var(--blue);
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 768px) {
            .container {
                padding: calc(56px + 24px) 16px 24px 16px;
            }

            .membership-ui-grid {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .form-section {
                padding: 24px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .payment-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 2rem;
            }

            .membership-card {
                padding: 24px;
            }

            .membership-price {
                font-size: 24px;
            }

            .membership-actions {
                flex-direction: column;
            }

            .membership-actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                min-height: auto;
            }

            .nav-list {
                gap: 16px;
                padding: 12px 16px;
            }

            .nav-item a {
                font-size: 12px;
                height: auto;
                padding: 8px 4px;
            }

            .container {
                padding: calc(56px + 16px) 12px 16px 12px;
            }

            .form-section {
                padding: 16px;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .form-section-title {
                font-size: 18px;
            }

            .payment-grid {
                grid-template-columns: 1fr;
            }

            .payment-option {
                padding: 16px;
            }

            .page-header {
                margin-bottom: 32px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                width: 100%;
            }

            .modal-content {
                padding: 32px 24px;
                max-width: 90%;
            }

            .modal-title {
                font-size: 20px;
            }

            .modal-message {
                font-size: 14px;
            }

            .modal-actions {
                gap: 10px;
            }
        }

        /* === CANCEL MEMBERSHIP MODAL === */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 200;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--button-radius);
            padding: 48px 32px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: slideUp 0.3s ease;
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: transparent;
            border: none;
            font-size: 28px;
            color: var(--text-gray);
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
            outline: none;
        }

        .modal-close:hover,
        .modal-close:focus {
            color: var(--black);
        }

        .modal-close:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--black);
            margin-bottom: 16px;
            text-align: center;
            line-height: 1.3;
        }

        .modal-message {
            font-size: 16px;
            font-weight: 400;
            color: var(--text-gray);
            text-align: center;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .modal-message strong {
            color: var(--black);
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 32px;
        }

        .modal-btn-confirm {
            padding: 14px 32px;
            background: #E53935;
            color: var(--white);
            border: none;
            border-radius: var(--button-radius);
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
        }

        .modal-btn-confirm:hover {
            background: #c62828;
            box-shadow: 0 8px 24px rgba(229, 57, 53, 0.3);
            transform: translateY(-2px);
        }

        .modal-btn-confirm:active {
            transform: translateY(0);
        }

        .modal-btn-confirm:focus-visible {
            outline: 2px solid #E53935;
            outline-offset: 2px;
        }

        .modal-btn-cancel {
            padding: 14px 32px;
            background: var(--gray);
            color: var(--text-gray);
            border: none;
            border-radius: var(--button-radius);
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
        }

        .modal-btn-cancel:hover {
            background: #e0e0e0;
            transform: translateY(-1px);
        }

        .modal-btn-cancel:active {
            transform: translateY(0);
        }

        .modal-btn-cancel:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .modal-loading {
            display: none;
            text-align: center;
        }

        .modal-loading.active {
            display: block;
        }

        .modal-loading::after {
            content: "";
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 3px solid var(--gray);
            border-top-color: #E53935;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* === ACCESSIBILITY === */
        .form-input:focus-visible,
        .form-select:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .payment-option:focus-within {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .skiplink {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--black);
            color: var(--white);
            padding: 8px;
            text-decoration: none;
            z-index: 100;
        }

        .skiplink:focus {
            top: 0;
        }

        /* === TERMS AND CONDITIONS MODAL === */
        .terms-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            overflow-y: auto;
            padding: 20px;
        }

        .terms-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .terms-modal-container {
            background: var(--white);
            width: 100%;
            max-width: 700px;
            max-height: 85vh;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            position: relative;
            transform: translateY(20px);
            transition: transform 0.3s ease;
            margin: auto;
        }

        .terms-modal-overlay.active .terms-modal-container {
            transform: translateY(0);
        }

        .terms-modal-header {
            background: linear-gradient(135deg, #308BE5 0%, #2568c2 100%);
            color: var(--white);
            padding: 24px 28px;
            border-radius: 16px 16px 0 0;
            text-align: center;
            position: relative;
        }

        .terms-modal-header h2 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .terms-modal-body {
            padding: 32px 28px;
            overflow-y: auto;
            flex: 1;
            max-height: calc(85vh - 200px);
            position: relative;
        }

        .terms-modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .terms-modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .terms-modal-body::-webkit-scrollbar-thumb {
            background: var(--blue);
            border-radius: 4px;
        }

        .terms-modal-body::-webkit-scrollbar-thumb:hover {
            background: #2568c2;
        }

        .terms-content {
            font-size: 14px;
            line-height: 1.8;
            color: #333;
        }

        .terms-content p {
            margin-bottom: 18px;
        }

        .terms-content h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--black);
            margin: 24px 0 14px 0;
            padding-top: 8px;
        }

        .terms-content h3:first-child {
            margin-top: 0;
        }

        .terms-content ul {
            margin: 10px 0 18px 20px;
            padding-left: 0;
        }

        .terms-content li {
            margin-bottom: 10px;
            padding-left: 4px;
        }

        .terms-content strong {
            color: var(--black);
            font-weight: 600;
        }

        .terms-scroll-indicator {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to top, rgba(255,255,255,0.95), transparent);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 12px;
            pointer-events: none;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .terms-scroll-indicator.hidden {
            opacity: 0;
        }

        .scroll-arrow {
            animation: bounce 1.5s infinite;
            color: var(--blue);
            font-size: 24px;
            font-weight: bold;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        .terms-modal-footer {
            padding: 20px 28px 24px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            background: #fafafa;
            border-radius: 0 0 16px 16px;
        }

        .terms-btn {
            padding: 12px 32px;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
            min-width: 120px;
        }

        .terms-btn-decline {
            background: #E53935;
            color: var(--white);
        }

        .terms-btn-decline:hover {
            background: #c62828;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(229, 57, 53, 0.3);
        }

        .terms-btn-agree {
            background: #249E2F;
            color: var(--white);
        }

        .terms-btn-agree:hover:not(:disabled) {
            background: #1e7e24;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(36, 158, 47, 0.3);
        }

        .terms-btn-agree:disabled {
            background: #cccccc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .terms-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .terms-modal-container {
                max-width: 95%;
                max-height: 90vh;
            }

            .terms-modal-header {
                padding: 20px;
            }

            .terms-modal-header h2 {
                font-size: 20px;
            }

            .terms-modal-body {
                padding: 24px 20px;
                max-height: calc(90vh - 180px);
            }

            .terms-modal-footer {
                padding: 16px 20px;
                flex-direction: column-reverse;
            }

            .terms-btn {
                width: 100%;
                padding: 14px;
            }

            .terms-content {
                font-size: 13px;
            }

            .terms-content h3 {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .terms-modal-overlay {
                padding: 10px;
            }

            .terms-modal-header {
                padding: 16px;
            }

            .terms-modal-header h2 {
                font-size: 18px;
            }

            .terms-modal-body {
                padding: 20px 16px;
            }

            .terms-content {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- SKIP TO CONTENT LINK -->
    <a href="#main-content" class="skiplink">Skip to main content</a>

    <!-- MAIN CONTENT -->
    <main class="container" id="main-content">
        <!-- PAGE HEADER -->
        <div class="page-header">
            <?php if (($accountType ?? 'buyer') !== 'seller' || ($subscriptionStatus ?? 'inactive') !== 'active'): ?>
                <h1 class="page-title">
                    <span class="title-green">Become</span>
                    <span class="title-blue">a Seller</span>
                </h1>
                <p class="page-subtitle">Upgrade to seller membership and start selling digital products</p>
            <?php else: ?>
                <h1 class="page-title">
                    <span class="title-green">Seller</span>
                    <span class="title-blue">Subscription</span>
                </h1>
                <p class="page-subtitle">Manage your seller membership and marketplace access</p>
            <?php endif; ?>
        </div>

        <!-- SUCCESS/ERROR ALERTS -->
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success" role="status">
                <?= htmlspecialchars(session('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('info')): ?>
            <div class="alert alert-info" role="status">
                <?= htmlspecialchars(session('info')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars(session('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger" role="alert">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>






        <!-- SECTION 5: MEMBERSHIP STATUS -->
        <section class="form-section" id="seller-membership-section" aria-labelledby="seller-membership-heading">
            <div class="membership-ui-grid" id="membership-ui-grid">
                <div class="membership-left" id="membership-left-column" aria-labelledby="membership-left-heading">
                    <h2 id="membership-left-heading">Sell your digital products on Byte Market with a renewable seller membership designed for independent creators and small sellers.</h2>
                    <h3 id="membership-how-it-works">How It Works</h3>
                    <ul class="how-it-works-list" id="membership-how-it-works-list" aria-labelledby="membership-how-it-works">
                        <li>Apply for Seller Membership</li>
                        <li>Complete the membership payment</li>
                        <li>Wait for admin approval</li>
                        <li>Membership becomes Active</li>
                        <li>Renew membership monthly to continue selling</li>
                    </ul>
                </div>

                <div class="membership-right" id="membership-right-column" aria-labelledby="seller-membership-heading">
                    <h2 id="seller-membership-heading">Byte Market Seller Membership</h2>
                    <p class="membership-right-subtext" id="membership-right-subtext">Membership must remain active to continue selling products.</p>

                    <div class="membership-price-wrap" id="membership-price-display" aria-label="Membership price">
                        <div class="membership-price-value">₱ 99.00</div>
                        <div class="membership-price-period">/ Per Month</div>
                    </div>

                    <div class="subscribe-btn-wrap" id="membership-subscribe-action">
                        <form method="POST" action="<?= base_url('subscription/activate') ?>" id="activationForm" style="width:100%;display:flex;justify-content:center;">
                            <?= csrf_field() ?>
                            <button
                                type="button"
                                class="subscribe-btn"
                                id="activateMembershipBtn"
                                aria-label="Subscribe to seller membership"
                            >
                                SUBSCRIBE
                            </button>
                        </form>
                    </div>

                    <ul class="membership-checklist" id="membership-checklist" aria-label="Seller membership benefits checklist">
                        <li><span class="check-icon" role="img" aria-label="Tick icon">☑</span> Upload and manage digital products</li>
                        <li><span class="check-icon" role="img" aria-label="Tick icon">☑</span> Set product prices and categories</li>
                        <li><span class="check-icon" role="img" aria-label="Tick icon">☑</span> View orders and sales history</li>
                        <li><span class="check-icon" role="img" aria-label="Tick icon">☑</span> Access to seller dashboard tools</li>
                        <li><span class="check-icon" role="img" aria-label="Tick icon">☑</span> Verified Badge</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>

    <!-- CANCEL MEMBERSHIP MODAL -->
    <div class="modal-overlay" id="cancelMembershipModal" role="dialog" aria-labelledby="modal-title" aria-modal="true">
        <div class="modal-content">
            <button class="modal-close" id="modalCloseBtn" aria-label="Close modal" type="button">×</button>
            
            <h2 class="modal-title" id="modal-title">Are you sure you want to cancel your membership?</h2>
            
            <div class="modal-message">
                <strong>There's no refund for your payment after you already paid and accepted.</strong>
                Take note all of your products will be removed from the product list of buyers
            </div>

            <div class="modal-actions">
                <button 
                    type="button" 
                    class="modal-btn-confirm" 
                    id="modalConfirmBtn"
                    aria-label="Confirm cancel membership"
                >
                    Confirm
                </button>
                <button 
                    type="button" 
                    class="modal-btn-cancel" 
                    id="modalCancelBtn"
                    aria-label="Cancel this action"
                >
                    Cancel
                </button>
            </div>

            <div class="modal-loading" id="modalLoading" role="status" aria-live="polite">
                Processing...
            </div>
        </div>
    </div>

    <!-- TERMS AND CONDITIONS MODAL -->
    <div class="terms-modal-overlay" id="termsModal" role="dialog" aria-labelledby="termsModalTitle" aria-modal="true">
        <div class="terms-modal-container">
            <div class="terms-modal-header">
                <h2 id="termsModalTitle">Terms and Conditions</h2>
            </div>
            <div class="terms-modal-body" id="termsModalBody">
                <div class="terms-content">
                    <p>By activating your Seller Subscription on Byte Market, you agree to comply with these Terms and Conditions. The platform provides an online environment where digital products may be sold and purchased electronically. All users agree to provide accurate information, maintain the confidentiality of their accounts, and use the system only for lawful purposes. Electronic transactions, confirmations, and agreements made through the platform are considered valid and binding.</p>

                    <h3>Seller Terms</h3>
                    <p>A Seller who uploads and offers digital products on the platform confirms that the products are original or properly licensed and do not violate intellectual property laws. Sellers agree to provide accurate descriptions, pricing, and complete product information, and to ensure that digital products function as described upon purchase. Sellers are responsible for maintaining the quality and legality of their content and for responding reasonably to buyer concerns or disputes. The platform reserves the right to remove products or suspend seller accounts that engage in misleading, fraudulent, or unlawful activities.</p>

                    <h3>Policy – Seller Obligations and Protection</h3>
                    <ul>
                        <li><strong>Content Policy:</strong> All uploaded products must be original or properly licensed and comply with intellectual property laws.</li>
                        <li><strong>Payment Policy:</strong> Seller payouts follow the platform schedule and may be adjusted for refunds issued to buyers.</li>
                        <li><strong>Dispute Policy:</strong> Sellers must cooperate in resolving buyer disputes. Failure to comply may result in suspension or account termination.</li>
                        <li><strong>Quality Standards Policy:</strong> Products must function as described. Misleading descriptions or non-functional products may result in removal or penalties.</li>
                        <li><strong>Prohibited Content Policy:</strong> Sellers may not upload illegal, harmful, offensive, or copyrighted content without proper authorization.</li>
                    </ul>

                    <h3>Buyer Terms</h3>
                    <p>Buyers agree to purchase digital products in good faith and acknowledge that all sales are final unless otherwise stated. Buyers must report any issues with product functionality or misrepresentation within the specified dispute window. Refunds are subject to platform policy and seller cooperation.</p>

                    <h3>Payment and Refund Policy</h3>
                    <p>All payments are processed securely through the platform. Buyers will receive access to purchased products immediately upon successful payment. Refunds may be issued in cases of product defects, misrepresentation, or other valid disputes as determined by the platform.</p>

                    <h3>User Responsibilities</h3>
                    <ul>
                        <li>Provide accurate and up-to-date account information.</li>
                        <li>Maintain the confidentiality of login credentials.</li>
                        <li>Use the platform in compliance with all applicable laws.</li>
                        <li>Report any suspected fraudulent activity or policy violations.</li>
                    </ul>

                    <h3>Platform Rights and Limitations</h3>
                    <p>Byte Market reserves the right to modify, suspend, or terminate services at any time. The platform is not liable for any indirect, incidental, or consequential damages arising from the use of the service. Users acknowledge that the platform acts as an intermediary and is not responsible for the quality, legality, or accuracy of user-generated content.</p>

                    <h3>Intellectual Property</h3>
                    <p>All content on the platform, including logos, trademarks, and design elements, are the property of Byte Market or its licensors. Users may not reproduce, distribute, or use platform content without express written permission.</p>

                    <h3>Termination</h3>
                    <p>Byte Market reserves the right to suspend or terminate user accounts that violate these Terms and Conditions. Users may also terminate their accounts at any time by contacting support.</p>

                    <h3>Changes to Terms</h3>
                    <p>Byte Market may update these Terms and Conditions from time to time. Users will be notified of significant changes and continued use of the platform constitutes acceptance of the updated terms.</p>
                </div>
                <div class="terms-scroll-indicator" id="termsScrollIndicator">
                    <span class="scroll-arrow">↓</span>
                </div>
            </div>
            <div class="terms-modal-footer">
                <button type="button" class="terms-btn terms-btn-decline" id="termsDeclineBtn" aria-label="Decline Terms and Conditions">
                    Decline
                </button>
                <button type="button" class="terms-btn terms-btn-agree" id="termsAgreeBtn" disabled aria-label="Agree to Terms and Conditions">
                    Agree
                </button>
            </div>
        </div>
    </div>

    <script>
        // Radio button active state update
        document.querySelectorAll('input[type="radio"][name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('active');
                });
                this.closest('.payment-option').classList.add('active');
            });
        });

        document.querySelectorAll('input[type="radio"][name="cashout_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('active');
                });
                this.closest('.payment-option').classList.add('active');
            });
        });

        // Form validation helper (optional)
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePassword(password) {
            // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special char
            const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return re.test(password);
        }

        // Optional: Add client-side validation on form submission
        document.getElementById('email-form')?.addEventListener('submit', function(e) {
            const newEmail = document.getElementById('new_email').value;
            if (!validateEmail(newEmail)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                document.getElementById('new_email').focus();
            }
        });

        document.getElementById('password-form')?.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!validatePassword(newPassword)) {
                e.preventDefault();
                alert('Password must be at least 8 characters long and contain uppercase, lowercase, numbers, and special characters.');
                document.getElementById('new_password').focus();
                return;
            }

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
                document.getElementById('confirm_password').focus();
            }
        });

        // ===== CANCEL MEMBERSHIP MODAL FUNCTIONALITY =====
        const modal = document.getElementById('cancelMembershipModal');
        const cancelMembershipBtn = document.getElementById('cancelMembershipBtn');
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        const modalConfirmBtn = document.getElementById('modalConfirmBtn');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const modalLoading = document.getElementById('modalLoading');

        // Open modal when Cancel Membership button is clicked
        cancelMembershipBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.add('active');
            modalCloseBtn.focus();
        });

        // Close modal function
        function closeModal() {
            modal.classList.remove('active');
            cancelMembershipBtn?.focus();
        }

        // Close button
        modalCloseBtn?.addEventListener('click', closeModal);

        // Cancel button
        modalCancelBtn?.addEventListener('click', closeModal);

        // Close modal when clicking outside (on overlay)
        modal?.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // Confirm button - submit cancellation
        modalConfirmBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loading state
            modalLoading.classList.add('active');
            modalConfirmBtn.disabled = true;
            modalCancelBtn.disabled = true;
            modalCloseBtn.disabled = true;

            // Prepare form data with CSRF token
            const formData = new FormData();
            
            // Get CSRF token from the page (if using CodeIgniter CSRF)
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value;
            if (csrfToken) {
                formData.append('<?= csrf_token() ?>', csrfToken);
            }

            // Send AJAX request to cancel subscription
            fetch('<?= base_url('subscription/cancel') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Success - redirect or show message
                if (data.success) {
                    // Show success message
                    alert(data.message || 'Membership cancelled successfully.');
                    
                    // Reload page to reflect changes
                    window.location.reload();
                } else {
                    // Error from server
                    alert(data.message || 'An error occurred. Please try again.');
                    resetModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while cancelling membership. Please try again.');
                resetModal();
            });
        });

        // Reset modal state
        function resetModal() {
            modalLoading.classList.remove('active');
            modalConfirmBtn.disabled = false;
            modalCancelBtn.disabled = false;
            modalCloseBtn.disabled = false;
        }

        // Trap focus within modal when open
        document.addEventListener('keydown', function(e) {
            if (!modal || !modal.classList.contains('active')) return;
            
            if (e.key !== 'Tab') return;

            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        });

        // === TERMS AND CONDITIONS MODAL FUNCTIONALITY ===
        document.addEventListener('DOMContentLoaded', function() {
            const termsModal = document.getElementById('termsModal');
            const termsModalBody = document.getElementById('termsModalBody');
            const termsAgreeBtn = document.getElementById('termsAgreeBtn');
            const termsDeclineBtn = document.getElementById('termsDeclineBtn');
            const termsScrollIndicator = document.getElementById('termsScrollIndicator');
            const activateMembershipBtn = document.getElementById('activateMembershipBtn');
            const activationForm = document.getElementById('activationForm');
            let isActivatingMembership = false;

            // Activate Membership button click handler
            if (activateMembershipBtn) {
                activateMembershipBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Show terms modal when activating membership
                    isActivatingMembership = true;
                    termsModal.classList.add('active');
                    // Reset scroll position and button state
                    termsModalBody.scrollTop = 0;
                    termsAgreeBtn.disabled = true;
                    termsScrollIndicator.classList.remove('hidden');
                    setTimeout(() => {
                        termsAgreeBtn.focus();
                    }, 300);
                });
            }

            // Simulate backend check: Replace this with actual server-side logic
            // Check if this is the first subscription activation
            const showTermsModal = <?= isset($show_terms) && $show_terms ? 'true' : 'false' ?>;

            if (showTermsModal) {
                // Show terms modal on page load
                setTimeout(() => {
                    termsModal.classList.add('active');
                    termsAgreeBtn.focus();
                }, 300);
            }

            // Scroll detection to enable Agree button
            termsModalBody.addEventListener('scroll', function() {
                const scrollTop = termsModalBody.scrollTop;
                const scrollHeight = termsModalBody.scrollHeight;
                const clientHeight = termsModalBody.clientHeight;

                // Check if scrolled to bottom (with 10px threshold)
                if (scrollTop + clientHeight >= scrollHeight - 10) {
                    termsAgreeBtn.disabled = false;
                    termsScrollIndicator.classList.add('hidden');
                } else {
                    // Optional: Re-disable if scrolling back up
                    // termsAgreeBtn.disabled = true;
                    // termsScrollIndicator.classList.remove('hidden');
                }
            });

            // Agree button handler
            termsAgreeBtn.addEventListener('click', function() {
                // Send agreement to backend
                fetch('<?= base_url('subscription/terms_agree') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({
                        agreed: true,
                        timestamp: new Date().toISOString()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal and allow subscription activation
                        termsModal.classList.remove('active');
                        
                        // If user clicked Activate Membership button, submit activation form directly
                        if (isActivatingMembership && activationForm) {
                            // Show loading state
                            activateMembershipBtn.disabled = true;
                            activateMembershipBtn.textContent = 'Processing...';

                            fetch('<?= base_url('payment/seller-subscription/checkout-session') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    membership: 'seller',
                                    amount: 99.00,
                                    currency: 'PHP'
                                })
                            })
                            .then(response => response.json())
                            .then(checkoutData => {
                                if (checkoutData.success && checkoutData.data?.checkout_url) {
                                    window.location.href = checkoutData.data.checkout_url;
                                    return;
                                }

                                activateMembershipBtn.disabled = false;
                                activateMembershipBtn.textContent = 'SUBSCRIBE';
                                alert(checkoutData.message || 'Unable to create checkout session. Please try again.');
                            })
                            .catch(error => {
                                console.error('Checkout session error:', error);
                                activateMembershipBtn.disabled = false;
                                activateMembershipBtn.textContent = 'SUBSCRIBE';
                                alert('An error occurred while creating checkout session. Please try again.');
                            });
                        } else {
                            alert('Thank you for agreeing to the Terms and Conditions. You can now proceed with your subscription.');
                            // Optionally reload page or enable subscription features
                            // window.location.reload();
                        }
                    } else {
                        alert('Error saving your agreement. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });

            // Decline button handler
            termsDeclineBtn.addEventListener('click', function() {
                const confirmDecline = confirm('Are you sure you want to decline? You will not be able to activate your subscription without agreeing to the Terms and Conditions.');
                
                if (confirmDecline) {
                    // If declining during membership activation, just close modal
                    if (isActivatingMembership) {
                        termsModal.classList.remove('active');
                        isActivatingMembership = false;
                        return;
                    }
                    
                    // Send decline to backend
                    fetch('<?= base_url('subscription/terms_decline') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify({
                            agreed: false,
                            timestamp: new Date().toISOString()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to dashboard or home
                            window.location.href = '<?= base_url('dashboard') ?>';
                        } else {
                            alert('Error processing your response. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                }
            });

            // Prevent closing modal by clicking outside or pressing ESC (force decision)
            termsModal.addEventListener('click', function(e) {
                if (e.target === termsModal) {
                    // Do nothing - prevent closing
                    e.stopPropagation();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && termsModal.classList.contains('active')) {
                    // Prevent ESC from closing modal
                    e.preventDefault();
                }
            });

        });
    </script>

    <?= view('footer') ?>
</body>
</html>
