<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #308BE5;
            --black: #000000;
            --white: #FFFFFF;
            --gray-light: #E8EAED;
            --gray-border: #D1D5DB;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #F9FAFB;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        * {
            scrollbar-width: none;
        }

        *::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        /* === HEADER STYLES === */
        .header-container {
            background: var(--white);
            padding: 16px 32px;
            box-shadow: 0 2px 8px var(--shadow);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1100;
            display: flex;
            justify-content: center;
        }

        .header-content {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
        }

        /* === LOGO SECTION === */
        .logo-section {
            display: flex;
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-image {
            height: 48px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--blue) 0%, #249E2F 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.3);
        }

        .notification-dot {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #21a52f;
            box-shadow: 0 0 0 2px #ffffff;
        }

        .menu-notification-dot {
            top: -2px;
            right: -3px;
        }

        .logo-phone {
            width: 28px;
            height: 36px;
            background: var(--white);
            border-radius: 6px;
            position: relative;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .logo-phone::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 2px;
            background: #ccc;
            border-radius: 1px;
        }

        .logo-phone::after {
            content: '';
            position: absolute;
            bottom: 3px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 8px;
            border: 2px solid var(--blue);
            border-radius: 50%;
        }

        .logo-cart {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: #249E2F;
            border-radius: 4px;
            border: 2px solid var(--white);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 0.9;
        }

        .logo-text .byte {
            font-size: 15px;
            font-weight: 600;
            color: var(--blue);
        }

        .logo-text .market {
            font-size: 15px;
            font-weight: 600;
            color: #249E2F;
        }

        /* === SEARCH BAR === */
        .search-section {
            flex: 1;
            max-width: none;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 48px;
            border: none;
            border-radius: 24px;
            background: var(--gray-light);
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: var(--black);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
            background: var(--white);
        }

        .search-input::placeholder {
            color: #9CA3AF;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            pointer-events: none;
        }

        .search-icon svg {
            width: 100%;
            height: 100%;
            fill: none;
            stroke: #6B7280;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .search-results {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            box-shadow: 0 12px 28px rgba(16, 24, 40, 0.12);
            max-height: 340px;
            overflow-y: auto;
            z-index: 1200;
            display: none;
        }

        .search-results.active {
            display: block;
        }

        .search-result-item {
            display: block;
            padding: 12px 14px;
            text-decoration: none;
            border-bottom: 1px solid #F3F4F6;
            transition: background 0.2s ease;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background: #F9FAFB;
        }

        .search-result-title {
            color: #111827;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 2px;
        }

        .search-result-meta {
            color: #6B7280;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
        }

        .search-results-empty {
            padding: 14px;
            color: #6B7280;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 13px;
            text-align: center;
        }

        /* === NAVIGATION ICONS === */
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        /* === ABOUT US LINK === */
        .about-us-link {
            color: var(--blue);
            text-decoration: none;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .about-us-link:hover {
            color: var(--blue);
            background: var(--gray-light);
        }

        .about-us-link:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .nav-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
        }

        .nav-icon:hover {
            background: var(--gray-light);
        }

        .nav-icon:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .nav-icon svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: var(--black);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* === PROFILE BUTTON === */
        .profile-button {
            display: flex;
            align-items: center;
            gap: 0;
            background: var(--blue);
            border-radius: 32px;
            padding: 4px 4px 4px 12px;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .profile-button:hover {
            background: #2670B8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.3);
        }

        .profile-button:focus-visible {
            outline: 3px solid var(--blue);
            outline-offset: 3px;
        }

        .hamburger-icon {
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-right: 8px;
        }

        .hamburger-line {
            width: 18px;
            height: 2px;
            background: var(--white);
            border-radius: 2px;
        }

        .profile-picture {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--white);
            box-shadow: 0 2px 8px var(--shadow);
        }

        .profile-avatar,
        .modal-profile-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .profile-picture-fallback,
        .modal-profile-pic-fallback {
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .profile-picture-fallback {
            width: 48px;
            height: 48px;
            background: #ffffff;
            border: 3px solid var(--white);
            box-shadow: 0 2px 8px var(--shadow);
        }

        .profile-picture-fallback svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: #6B7280;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .is-hidden {
            display: none;
        }

        /* === PROFILE MODAL === */
        .profile-modal {
            position: absolute;
            top: calc(100% + 16px);
            right: 32px;
            width: 320px;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            padding: 24px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .profile-modal.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* === MODAL HEADER === */
        .modal-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-border);
            margin-bottom: 16px;
        }

        .modal-profile-pic {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--blue);
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.2);
        }

        .modal-profile-pic-fallback {
            width: 64px;
            height: 64px;
            background: #EFF5FC;
            border: 3px solid var(--blue);
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.2);
        }

        .modal-profile-pic-fallback svg {
            width: 30px;
            height: 30px;
            fill: none;
            stroke: var(--blue);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .modal-user-info {
            flex: 1;
        }

        .modal-user-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--blue);
            margin-bottom: 4px;
        }

        .modal-edit-link {
            font-size: 13px;
            color: #6B7280;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .modal-edit-link:hover {
            color: var(--blue);
        }

        /* === MODAL OPTIONS === */
        .modal-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .modal-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--black);
            font-size: 15px;
            font-weight: 400;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .modal-option:hover {
            background: var(--gray-light);
        }

        .modal-option:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        .modal-option.active {
            background: var(--blue);
            color: var(--white);
        }

        /* === BECOME A SELLER BUTTON === */
        .become-seller-btn {
            display: block;
            width: 100%;
            padding: 14px 24px;
            background: transparent;
            border: 2px solid var(--blue);
            border-radius: 50px;
            color: var(--blue);
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .become-seller-btn:hover {
            background: var(--blue);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(48, 139, 229, 0.3);
        }

        .become-seller-btn:focus-visible {
            outline: 3px solid var(--blue);
            outline-offset: 3px;
        }

        .modal-divider {
            height: 1px;
            background: #D1D5DB;
            margin: 8px 4px;
        }

        .modal-option-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .modal-option-icon svg {
            width: 100%;
            height: 100%;
            fill: currentColor;
        }

        /* === CREATE ACCOUNT BUTTON === */
        .create-account-btn {
            background: var(--black);
            color: var(--white);
            padding: 10px 14px;
            border: none;
            border-radius: 2px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-left: 6px;
        }

        .create-account-btn:hover {
            background: #1F2937;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .create-account-btn:focus-visible {
            outline: 3px solid var(--black);
            outline-offset: 2px;
        }

        /* === LOG IN BUTTON === */
        .login-btn {
            background: transparent;
            color: var(--black);
            padding: 8px 10px;
            border: none;
            border-radius: 0;
            font-family: 'Poppins', Arial, sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            margin-left: 2px;
        }

        .login-btn:hover {
            color: #111827;
            transform: translateY(-1px);
        }

        .login-btn:focus-visible {
            outline: 3px solid var(--black);
            outline-offset: 2px;
        }

        .faq-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #111827;
            background: transparent;
            color: #111827;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 20px;
            font-weight: 700;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 2px;
        }

        .faq-btn:hover {
            background: var(--gray-light);
        }

        .faq-btn:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 2px;
        }

        /* === REGISTRATION MODAL === */
        .registration-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 2000;
        }

        .registration-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .registration-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            background: var(--white);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 2001;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .registration-modal::-webkit-scrollbar {
            display: none;
        }

        .registration-modal.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        /* === REGISTRATION FORM === */
        .registration-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .registration-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: var(--black);
            margin-bottom: 8px;
        }

        .registration-header p {
            font-size: 14px;
            color: #6B7280;
            font-weight: 400;
        }

        .registration-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--black);
            margin-bottom: 8px;
        }

        .form-input {
            padding: 12px 16px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: var(--black);
            transition: all 0.3s ease;
            background: #F9FAFB;
        }

        .form-input:focus {
            outline: none;
            border-color: #249E2F;
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(36, 158, 47, 0.1);
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        /* Password Requirements */
        .password-requirements {
            background: #F9FAFB;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 16px;
            font-size: 12px;
            color: #6B7280;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            transition: color 0.3s ease;
        }

        .requirement-item:last-child {
            margin-bottom: 0;
        }

        .requirement-item.met {
            color: #249E2F;
        }

        .requirement-indicator {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .requirement-item.met .requirement-indicator {
            background: #249E2F;
            border-color: #249E2F;
            color: var(--white);
        }

        /* Register Button */
        .register-submit-btn {
            background: #249E2F;
            color: var(--white);
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        .register-submit-btn:hover:not(:disabled) {
            background: #1e7a27;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(36, 158, 47, 0.3);
        }

        .register-submit-btn:focus-visible {
            outline: 3px solid #249E2F;
            outline-offset: 2px;
        }

        .register-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Form Footer */
        .registration-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6B7280;
            line-height: 1.6;
        }

        .registration-footer a {
            color: #249E2F;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .registration-footer a:hover {
            color: #1e7a27;
            text-decoration: underline;
        }

        /* Sign In Link */
        .signin-link {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #6B7280;
        }

        .signin-link a {
            color: #249E2F;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signin-link a:hover {
            color: #1e7a27;
        }

        /* Divider */
        .registration-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--gray-border);
        }

        .divider-text {
            font-size: 12px;
            color: #6B7280;
            font-weight: 500;
        }

        /* Google OAuth Button */
        .google-oauth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px 20px;
            border: 2px solid var(--gray-border);
            border-radius: 8px;
            background: var(--white);
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #EA4335;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 12px;
        }

        .google-oauth-btn:hover {
            background: #F9FAFB;
            border-color: #EA4335;
            box-shadow: 0 4px 12px rgba(234, 67, 53, 0.2);
        }

        .google-oauth-btn:focus-visible {
            outline: 3px solid #EA4335;
            outline-offset: 2px;
        }

        .google-logo {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* === LOGIN MODAL === */
        .login-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 2000;
        }

        .login-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .login-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            width: 90%;
            max-width: 450px;
            max-height: 90vh;
            background: var(--white);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 2001;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .login-modal::-webkit-scrollbar {
            display: none;
        }

        .login-modal.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        /* === LOGIN FORM === */
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: var(--black);
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 14px;
            color: #6B7280;
            font-weight: 400;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .login-form .form-group {
            display: flex;
            flex-direction: column;
        }

        .login-form .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--black);
            margin-bottom: 8px;
        }

        .login-form .form-input {
            padding: 12px 16px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px;
            color: var(--black);
            transition: all 0.3s ease;
            background: #F9FAFB;
        }

        .login-form .form-input:focus {
            outline: none;
            border-color: var(--blue);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(48, 139, 229, 0.1);
        }

        .login-form .form-input::placeholder {
            color: #9CA3AF;
        }

        /* Login Submit Button */
        .login-submit-btn {
            background: var(--blue);
            color: var(--white);
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        .login-submit-btn:hover:not(:disabled) {
            background: #2670B8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(48, 139, 229, 0.3);
        }

        .login-submit-btn:focus-visible {
            outline: 3px solid var(--blue);
            outline-offset: 2px;
        }

        .login-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Login Footer */
        .login-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #6B7280;
        }

        .login-footer a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #2670B8;
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 768px) {
            .header-content {
                gap: 12px;
                padding: 12px 16px;
                flex-wrap: wrap;
            }

            .logo-text {
                display: none;
            }

            .search-section {
                flex: 1;
                min-width: 200px;
            }

            .about-us-link {
                display: none;
            }

            .create-account-btn {
                padding: 10px 14px;
                font-size: 12px;
                display: none;
            }

            .login-btn {
                padding: 10px 14px;
                font-size: 12px;
                display: none;
            }

            .faq-btn {
                display: none;
            }

            .nav-icons {
                gap: 8px;
            }

            .profile-modal {
                right: 16px;
                width: calc(100vw - 32px);
                max-width: 320px;
            }

            .registration-modal {
                width: 95%;
                padding: 32px 20px;
                max-height: 95vh;
            }

            .registration-header h2 {
                font-size: 24px;
            }

            .login-modal {
                width: 95%;
                padding: 32px 20px;
                max-height: 95vh;
            }

            .login-header h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .nav-icon {
                width: 36px;
                height: 36px;
            }

            .nav-icon svg {
                width: 20px;
                height: 20px;
            }

            .profile-button {
                padding: 3px 3px 3px 8px;
            }

            .profile-picture {
                width: 40px;
                height: 40px;
            }

            .profile-picture-fallback {
                width: 40px;
                height: 40px;
            }

            .profile-picture-fallback svg {
                width: 20px;
                height: 20px;
            }

            .hamburger-line {
                width: 14px;
            }

            .create-account-btn {
                display: none;
            }

            .registration-modal {
                width: 100%;
                padding: 24px 16px;
                max-height: 100vh;
                border-radius: 16px 16px 0 0;
                top: auto;
                bottom: 0;
                left: 0;
                right: 0;
                transform: translateY(100%);
            }

            .registration-modal.active {
                transform: translateY(0);
            }

            .registration-header h2 {
                font-size: 22px;
            }

            .form-input {
                font-size: 16px;
            }

            .password-requirements {
                font-size: 11px;
            }

            .login-btn {
                display: none;
            }

            .login-modal {
                width: 100%;
                padding: 24px 16px;
                max-height: 100vh;
                border-radius: 16px 16px 0 0;
                top: auto;
                bottom: 0;
                left: 0;
                right: 0;
                transform: translateY(100%);
            }

            .login-modal.active {
                transform: translateY(0);
            }

            .login-header h2 {
                font-size: 22px;
            }

            .login-form .form-input {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Modal Backdrop -->
    <div class="modal-backdrop" id="modalBackdrop"></div>

    <?php
    $currentUri = uri_string();
    $isLoggedIn = session()->get('isLoggedIn');
    $userId = (int) (session()->get('userId') ?? 0);
    $unreadNotificationCount = 0;
    $hideLandingHomeButton = (bool) ($hideLandingHomeButton ?? false);
    $isLandingPage = empty($currentUri) || $currentUri === 'landing' || strpos($currentUri, 'landing') === 0 || ($currentUri === 'about' && !$isLoggedIn);
    $useGuestHeader = !$isLoggedIn || $isLandingPage;
    $profileImage = trim((string) (session()->get('profileImage') ?? ''));

    $sessionRole = strtolower(trim((string) (session()->get('role') ?? 'buyer')));
    $sessionAccountType = strtolower(trim((string) (session()->get('account_type') ?? $sessionRole)));
    $sessionSubscriptionStatus = strtolower(trim((string) (session()->get('subscription_status') ?? 'inactive')));
    $sessionMembershipLabel = strtolower(trim((string) (session()->get('membership_label') ?? 'inactive')));

    $isSellerAccount = (
        $sessionRole === 'seller' ||
        $sessionAccountType === 'seller' ||
        $sessionSubscriptionStatus === 'active' ||
        $sessionMembershipLabel === 'active'
    );

    if ($isLoggedIn && !$isSellerAccount) {
        $userId = (int) (session()->get('userId') ?? 0);
        if ($userId > 0) {
            try {
                $authModel = new \App\Models\AuthModel();
                $headerUser = $authModel->getUserById($userId);

                if (!empty($headerUser)) {
                    $dbRole = strtolower(trim((string) ($headerUser['role'] ?? 'buyer')));
                    $dbAccountType = strtolower(trim((string) ($headerUser['account_type'] ?? $dbRole)));
                    $dbSubscriptionStatus = strtolower(trim((string) ($headerUser['subscription_status'] ?? (($dbAccountType === 'seller') ? 'active' : 'inactive'))));
                    $dbMembershipLabel = trim((string) ($headerUser['membership_label'] ?? (($dbSubscriptionStatus === 'active') ? 'Active' : 'Inactive')));

                    $isSellerAccount = (
                        $dbRole === 'seller' ||
                        $dbAccountType === 'seller' ||
                        $dbSubscriptionStatus === 'active' ||
                        strtolower(trim($dbMembershipLabel)) === 'active'
                    );

                    session()->set([
                        'role' => $dbRole,
                        'account_type' => $dbAccountType,
                        'subscription_status' => $dbSubscriptionStatus,
                        'membership_label' => $dbMembershipLabel,
                        'subscription_end_date' => $headerUser['subscription_end_date'] ?? session()->get('subscription_end_date'),
                        'profileImage' => $headerUser['profile_image'] ?? session()->get('profileImage'),
                    ]);

                    $dbProfileImage = trim((string) ($headerUser['profile_image'] ?? ''));
                    if ($dbProfileImage !== '') {
                        $profileImage = $dbProfileImage;
                    }
                }
            } catch (\Throwable $e) {
                log_message('warning', 'Header seller-state sync failed: ' . $e->getMessage());
            }
        }
    }

    if ($isLoggedIn && $userId > 0) {
        try {
            $notificationModel = new \App\Models\NotificationModel();
            $unreadNotificationCount = max(0, (int) $notificationModel->getUnreadCount($userId));
        } catch (\Throwable $e) {
            log_message('warning', 'Header unread notification count failed: ' . $e->getMessage());
        }
    }

    $headerSearchValue = trim((string) (service('request')->getGet('q') ?? ''));

    $hasProfileImage = $profileImage !== '';
    $profileImageUrl = null;
    if ($hasProfileImage) {
        if (preg_match('/^https?:\/\//i', $profileImage)) {
            $profileImageUrl = $profileImage;
        } elseif (strpos($profileImage, 'uploads/') === 0) {
            $profileImageUrl = base_url(ltrim($profileImage, '/'));
        } else {
            $profileImageUrl = base_url('uploads/profiles/' . ltrim($profileImage, '/'));
        }
    }
    ?>

    <!-- Header Container -->
    <header class="header-container">
        <div class="header-content">
            <!-- Logo Section -->
            <a href="<?= base_url('home') ?>" class="logo-section" aria-label="Byte Market Home">
                <img src="<?= base_url('assets/images/LOGO (1).png') ?>" alt="ByteMarket Logo" class="logo-image">
                <div class="logo-text">
                    <span class="byte">Byte</span>
                    <span class="market">Market</span>
                </div>
            </a>

            <!-- Search Bar -->
            <div class="search-section">
                <div class="search-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                </div>
                <input 
                    type="search" 
                    class="search-input" 
                    placeholder="Search products, services, or categories..."
                    aria-label="Search"
                    id="headerSearch"
                    value="<?= esc($headerSearchValue, 'attr') ?>"
                >
                <div class="search-results" id="headerSearchResults" role="listbox" aria-label="Search results"></div>
            </div>

            <?php if ($useGuestHeader): ?>

            <!-- About Us Link -->
            <a href="<?= base_url('about') ?>" class="about-us-link">About Us</a>

            <!-- Action Buttons (Landing Page Only) -->
            <button class="create-account-btn" id="createAccountBtn" aria-label="Create Account">
                Create Account
            </button>

            <button class="login-btn" id="loginBtn" aria-label="Log In">
                Sign In
            </button>

            <button class="faq-btn" id="faqBtn" aria-label="FAQs">?</button>
            <?php else: ?>
            <div class="nav-icons" aria-label="Primary actions">
                <a href="<?= base_url('cart') ?>" class="nav-icon" aria-label="Cart" id="cartIcon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="9" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                        <path d="M3 4h2l2.2 10.4a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L21 8H7"></path>
                    </svg>
                </a>

                <a href="<?= base_url('home') ?>" class="nav-icon" aria-label="Home" id="homeIconLogged">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </a>

                <!-- Profile Button -->
                <button class="profile-button" id="profileToggle" aria-label="User Profile Menu" aria-expanded="false" aria-controls="profileModal">
                    <div class="hamburger-icon" aria-hidden="true">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </div>
                    <div class="profile-avatar">
                        <?php if ($hasProfileImage): ?>
                        <img
                            src="<?= esc((string) $profileImageUrl) ?>"
                            alt="User Profile Picture"
                            class="profile-picture"
                            id="profilePic"
                            onerror="this.style.display='none';this.nextElementSibling.classList.remove('is-hidden');"
                        >
                        <?php endif; ?>
                        <span class="profile-picture-fallback<?= $hasProfileImage ? ' is-hidden' : '' ?>" id="profilePicFallback" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                <circle cx="12" cy="8" r="4"></circle>
                            </svg>
                        </span>
                    </div>
                </button>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!$useGuestHeader): ?>
        <!-- Profile Modal -->
        <div class="profile-modal" id="profileModal" role="dialog" aria-labelledby="modalUserName">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-profile-avatar">
                    <?php if ($hasProfileImage): ?>
                    <img
                        src="<?= esc((string) $profileImageUrl) ?>"
                        alt="User Profile Picture"
                        class="modal-profile-pic"
                        id="modalProfilePic"
                        onerror="this.style.display='none';this.nextElementSibling.classList.remove('is-hidden');"
                    >
                    <?php endif; ?>
                    <span class="modal-profile-pic-fallback<?= $hasProfileImage ? ' is-hidden' : '' ?>" id="modalProfilePicFallback" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                            <circle cx="12" cy="8" r="4"></circle>
                        </svg>
                    </span>
                </div>
                <div class="modal-user-info">
                    <?php
                        $sessionFirstName = trim((string) session()->get('first_name'));
                        $sessionLastName = trim((string) session()->get('last_name'));
                        $sessionFullName = trim((string) (session()->get('fullName') ?? session()->get('full_name')));
                        $displayName = trim($sessionFirstName . ' ' . $sessionLastName);
                        if ($displayName === '') {
                            $displayName = $sessionFullName !== '' ? $sessionFullName : 'User';
                        }
                    ?>
                    <h2 class="modal-user-name" id="modalUserName"><?= esc($displayName) ?></h2>
                    <a href="<?= base_url('auth/profile') ?>" class="modal-edit-link">Edit Account</a>
                </div>
            </div>

            <!-- Seller/Buyer Primary Action -->
            <?php if ($isSellerAccount): ?>
            <a href="<?= base_url('dashboard') ?>" class="become-seller-btn" id="btnMarketerDashboard">
                Marketer Dashboard
            </a>
            <?php elseif ($sessionRole !== 'admin'): ?>
            <a href="<?= base_url('subscription') ?>" class="become-seller-btn" id="btnBecomeSeller">
                Become a seller
            </a>
            <?php endif; ?>

            <!-- Modal Options -->
            <nav class="modal-options">
                <a href="<?= base_url('my-orders') ?>" class="modal-option" id="btnMyOrders">
                    <span class="modal-option-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z" fill="currentColor"></path>
                            <path d="M19 2h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z" fill="currentColor"></path>
                            <path d="M9 12H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Z" fill="currentColor"></path>
                            <path d="M19 12h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <span>My Orders</span>
                </a>

                <a href="<?= base_url('notifications') ?>" class="modal-option" id="btnNotifications">
                    <span class="modal-option-icon">
                        <?php if ($unreadNotificationCount > 0): ?>
                        <span class="notification-dot menu-notification-dot" aria-hidden="true"></span>
                        <?php endif; ?>
                        <svg viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" fill="currentColor"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" fill="currentColor"></path>
                        </svg>
                    </span>
                    <span>Notifications</span>
                </a>

                <a href="<?= base_url('header/faq') ?>" class="modal-option" id="btnFaq">
                    <span class="modal-option-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" fill="currentColor"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" stroke="white" stroke-width="2" fill="none"></path>
                            <circle cx="12" cy="17" r="0.5" fill="white"></circle>
                        </svg>
                    </span>
                    <span>FAQ</span>
                </a>

                <div class="modal-divider" aria-hidden="true"></div>

                <a href="<?= base_url('auth/logout') ?>" class="modal-option" id="btnLogout">
                    <span class="modal-option-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" fill="none"></path>
                            <polyline points="16 17 21 12 16 7" stroke="currentColor" stroke-width="2" fill="none"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2"></line>
                        </svg>
                    </span>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
        <?php endif; ?>
    </header>

    <?php 
    // Only render auth modals on landing page
    if ($useGuestHeader): 
    ?>
    <!-- Registration Modal Backdrop -->
    <div class="registration-backdrop" id="registrationBackdrop"></div>

    <!-- Registration Modal -->
    <div class="registration-modal" id="registrationModal" role="dialog" aria-labelledby="registrationTitle">
        <div class="registration-header">
            <h2 id="registrationTitle">Create Your Account</h2>
            <p>Registration is easy!</p>
        </div>

        <form class="registration-form" id="registrationForm" action="<?= base_url('auth/signup') ?>" method="POST">
            <?= csrf_field() ?>
            
            <!-- Success/Error Message -->
            <div id="regAlertMessage" style="display: none; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px;"></div>
            
            <!-- Full Name -->
            <div class="form-group">
                <label for="regFullName" class="form-label">Full Name</label>
                <input 
                    type="text" 
                    id="regFullName" 
                    class="form-input" 
                    name="full_name" 
                    placeholder="John Doe"
                    required
                    autocomplete="name"
                >
            </div>

            <!-- Email Address -->
            <div class="form-group">
                <label for="regEmail" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="regEmail" 
                    class="form-input" 
                    name="email" 
                    placeholder="your@email.com"
                    required
                    autocomplete="email"
                >
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="regPassword" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="regPassword" 
                    class="form-input" 
                    name="password" 
                    placeholder="Enter a secure password"
                    required
                    autocomplete="new-password"
                >

                <!-- Password Requirements -->
                <div class="password-requirements">
                    <div class="requirement-item" id="req-length">
                        <span class="requirement-indicator">✓</span>
                        <span>At least 8 characters long</span>
                    </div>
                    <div class="requirement-item" id="req-uppercase">
                        <span class="requirement-indicator">✓</span>
                        <span>One uppercase letter (A-Z)</span>
                    </div>
                    <div class="requirement-item" id="req-lowercase">
                        <span class="requirement-indicator">✓</span>
                        <span>One lowercase letter (a-z)</span>
                    </div>
                    <div class="requirement-item" id="req-number">
                        <span class="requirement-indicator">✓</span>
                        <span>One number (0-9)</span>
                    </div>
                    <div class="requirement-item" id="req-special">
                        <span class="requirement-indicator">✓</span>
                        <span>One special character (!@#$%^&*)</span>
                    </div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="regPasswordConfirm" class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    id="regPasswordConfirm" 
                    class="form-input" 
                    name="password_confirm" 
                    placeholder="Re-enter your password"
                    required
                    autocomplete="new-password"
                >
            </div>
            
            <!-- Hidden Role Field (default to buyer) -->
            <input type="hidden" name="role" value="buyer">

            <!-- Register Button -->
            <button type="submit" class="register-submit-btn" id="regSubmitBtn" disabled>
                REGISTER
            </button>

            <!-- Terms & Privacy -->
            <div class="registration-footer">
                By clicking Register you agree to Byte Market's 
                <a href="<?= base_url('terms') ?>">Terms of Use</a> and 
                <a href="<?= base_url('privacy') ?>">Privacy Policy</a>.
            </div>

            <!-- Sign In Link -->
            <div class="signin-link">
                Already Have Account? <a href="<?= base_url('signin') ?>">Sign In</a>
            </div>
        </form>
    </div>

    <!-- Login Modal Backdrop -->
    <div class="login-backdrop" id="loginBackdrop"></div>

    <!-- Login Modal -->
    <div class="login-modal" id="loginModal" role="dialog" aria-labelledby="loginTitle">
        <div class="login-header">
            <h2 id="loginTitle">Welcome Back!</h2>
            <p>See what's Your Missing.</p>
        </div>

        <form class="login-form" id="loginForm" action="<?= base_url('auth/login') ?>" method="POST">
            <?= csrf_field() ?>
            
            <!-- Success/Error Message -->
            <div id="loginAlertMessage" style="display: none; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px;"></div>
            
            <!-- Email Address -->
            <div class="form-group">
                <label for="loginEmail" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="loginEmail" 
                    class="form-input" 
                    name="email" 
                    placeholder="your@email.com"
                    required
                    autocomplete="email"
                >
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="loginPassword" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="loginPassword" 
                    class="form-input" 
                    name="password" 
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >
            </div>
            
            <!-- Remember Me -->
            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" id="rememberMe" name="remember_me" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                <label for="rememberMe" style="cursor: pointer; margin: 0; font-size: 14px;">Remember me</label>
            </div>

            <!-- Log In Button -->
            <button type="submit" class="login-submit-btn" id="loginSubmitBtn">
                LOG IN
            </button>

            <!-- Sign Up Link -->
            <div class="login-footer">
                Don't Have Account Yet? <a href="#" onclick="closeLoginModal(); openRegistrationModal(); return false;">Sign Up</a>
            </div>
        </form>
    </div>
    <?php endif; // End guest header auth modals ?>

    <script>
        <?php if (!$useGuestHeader): ?>
        // === PROFILE MODAL FUNCTIONS ===
        const profileToggle = document.getElementById('profileToggle');
        const profileModal = document.getElementById('profileModal');
        const modalBackdrop = document.getElementById('modalBackdrop');
        const profilePic = document.getElementById('profilePic');
        const profilePicFallback = document.getElementById('profilePicFallback');
        const modalProfilePic = document.getElementById('modalProfilePic');
        const modalProfilePicFallback = document.getElementById('modalProfilePicFallback');
        const modalUserName = document.getElementById('modalUserName');

        function ensureAvatarImage(containerSelector, imageId, imageClass) {
            const container = document.querySelector(containerSelector);
            if (!container) {
                return null;
            }

            let img = document.getElementById(imageId);
            if (img) {
                return img;
            }

            img = document.createElement('img');
            img.id = imageId;
            img.className = imageClass;
            img.alt = 'User Profile Picture';
            img.onerror = function() {
                this.style.display = 'none';
                const fallback = this.nextElementSibling;
                if (fallback) {
                    fallback.classList.remove('is-hidden');
                }
            };

            const fallback = container.querySelector('span');
            if (fallback) {
                container.insertBefore(img, fallback);
            } else {
                container.appendChild(img);
            }

            return img;
        }

        function setAvatarImage(imgEl, fallbackEl, imageUrl, containerSelector, imageId, imageClass) {
            if (!fallbackEl && !containerSelector) {
                return;
            }

            let targetImg = imgEl;
            if (!targetImg && containerSelector && imageId && imageClass) {
                targetImg = ensureAvatarImage(containerSelector, imageId, imageClass);
            }

            const trimmedUrl = typeof imageUrl === 'string' ? imageUrl.trim() : '';
            if (trimmedUrl === '') {
                if (targetImg) {
                    targetImg.removeAttribute('src');
                    targetImg.classList.add('is-hidden');
                }
                if (fallbackEl) {
                    fallbackEl.classList.remove('is-hidden');
                }
                return;
            }

            if (targetImg) {
                targetImg.style.display = '';
                targetImg.src = trimmedUrl;
                targetImg.classList.remove('is-hidden');
            }
            if (fallbackEl) {
                fallbackEl.classList.add('is-hidden');
            }
        }

        window.addEventListener('profile:updated', (event) => {
            const detail = event && event.detail ? event.detail : {};
            const imageUrl = detail.profileImageUrl || '';
            const fullName = (detail.fullName || '').trim();

            setAvatarImage(profilePic, profilePicFallback, imageUrl, '.profile-avatar', 'profilePic', 'profile-picture');
            setAvatarImage(modalProfilePic, modalProfilePicFallback, imageUrl, '.modal-profile-avatar', 'modalProfilePic', 'modal-profile-pic');

            if (fullName !== '' && modalUserName) {
                modalUserName.textContent = fullName;
            }
        });

        function toggleModal() {
            const isActive = profileModal.classList.toggle('active');
            modalBackdrop.classList.toggle('active');
            profileToggle.setAttribute('aria-expanded', isActive);
        }

        function closeModal() {
            profileModal.classList.remove('active');
            modalBackdrop.classList.remove('active');
            profileToggle.setAttribute('aria-expanded', 'false');
        }

        // Toggle modal on button click
        profileToggle.addEventListener('click', toggleModal);

        // Close modal when clicking backdrop
        modalBackdrop.addEventListener('click', closeModal);

        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && profileModal.classList.contains('active')) {
                closeModal();
            }
        });
        <?php endif; ?>

        <?php if ($useGuestHeader): ?>
        // === REGISTRATION MODAL FUNCTIONS ===
        const createAccountBtn = document.getElementById('createAccountBtn');
        const registrationModal = document.getElementById('registrationModal');
        const registrationBackdrop = document.getElementById('registrationBackdrop');
        const registrationForm = document.getElementById('registrationForm');
        const passwordInput = document.getElementById('regPassword');
        const regSubmitBtn = document.getElementById('regSubmitBtn');

        // Password validation requirements
        const passwordRequirements = {
            length: document.getElementById('req-length'),
            uppercase: document.getElementById('req-uppercase'),
            lowercase: document.getElementById('req-lowercase'),
            number: document.getElementById('req-number'),
            special: document.getElementById('req-special')
        };

        function openRegistrationModal() {
            registrationModal.classList.add('active');
            registrationBackdrop.classList.add('active');
            createAccountBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeRegistrationModal() {
            registrationModal.classList.remove('active');
            registrationBackdrop.classList.remove('active');
            createAccountBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        // Open registration modal on button click
        createAccountBtn.addEventListener('click', openRegistrationModal);

        // Close registration modal when clicking backdrop
        registrationBackdrop.addEventListener('click', closeRegistrationModal);

        // Close registration modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && registrationModal.classList.contains('active')) {
                closeRegistrationModal();
            }
        });

        // Password validation function
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*]/.test(password)
            };

            return requirements;
        }

        function updatePasswordRequirements(password) {
            const requirements = validatePassword(password);

            // Update visual indicators
            Object.keys(requirements).forEach(key => {
                const element = passwordRequirements[key];
                if (requirements[key]) {
                    element.classList.add('met');
                } else {
                    element.classList.remove('met');
                }
            });

            // Enable/disable submit button
            const allMet = Object.values(requirements).every(v => v);
            regSubmitBtn.disabled = !allMet;

            return allMet;
        }

        // Real-time password validation
        passwordInput.addEventListener('input', (e) => {
            updatePasswordRequirements(e.target.value);
        });
        
        // Show alert message function
        function showRegAlert(type, message) {
            const alertDiv = document.getElementById('regAlertMessage');
            alertDiv.style.display = 'block';
            alertDiv.className = '';
            
            if (type === 'success') {
                alertDiv.style.background = '#e8f5e9';
                alertDiv.style.color = '#2e7d32';
                alertDiv.style.borderLeft = '4px solid #4caf50';
            } else {
                alertDiv.style.background = '#ffebee';
                alertDiv.style.color = '#c62828';
                alertDiv.style.borderLeft = '4px solid #e53935';
            }
            
            alertDiv.textContent = message;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 5000);
        }

        // Form submission with AJAX
        registrationForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const password = passwordInput.value;
            const passwordConfirm = document.getElementById('regPasswordConfirm').value;
            
            // Check if passwords match
            if (password !== passwordConfirm) {
                showRegAlert('error', 'Passwords do not match!');
                return;
            }
            
            // Validate password requirements
            const requirements = validatePassword(password);
            if (!Object.values(requirements).every(v => v)) {
                showRegAlert('error', 'Please meet all password requirements');
                return;
            }

            // Disable submit button
            regSubmitBtn.disabled = true;
            regSubmitBtn.textContent = 'REGISTERING...';

            try {
                const formData = new FormData(registrationForm);
                
                // Get CSRF token from the form
                const csrfTokenElement = document.querySelector('[name="csrf_test_name"]');
                const csrfToken = formData.get('csrf_test_name') || (csrfTokenElement ? csrfTokenElement.value : '');
                console.log('CSRF Token being sent:', csrfToken ? 'Present' : 'MISSING');
                
                const response = await fetch(registrationForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                });
                
                const text = await response.text();
                console.log('=== REGISTRATION RESPONSE ===');
                console.log('Status:', response.status);
                console.log('Headers:', response.headers);
                console.log('Response text:', text);
                console.log('=== END RESPONSE ===');
                
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response:', text);
                    console.error('Parse error:', e.message);
                    showRegAlert('error', 'Server error: Invalid response from server. Check console for details.');
                    regSubmitBtn.disabled = false;
                    regSubmitBtn.textContent = 'REGISTER';
                    return;
                }
                
                if (data.success) {
                    showRegAlert('success', data.message || 'Registration successful! Redirecting...');
                    
                    // Reset form
                    registrationForm.reset();
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect || '<?= base_url("dashboard") ?>';
                    }, 2000);
                } else {
                    const errorMsg = data.message || 'Registration failed. Please try again.';
                    console.error('Registration error:', data.errors || errorMsg);
                    showRegAlert('error', errorMsg);
                    
                    // Show specific field errors if available
                    if (data.errors) {
                        console.table(data.errors);
                    }
                    
                    // Re-enable button
                    regSubmitBtn.disabled = false;
                    regSubmitBtn.textContent = 'REGISTER';
                }
            } catch (error) {
                console.error('Registration fetch error:', error);
                showRegAlert('error', 'Network error: ' + error.message);
                
                // Re-enable button
                regSubmitBtn.disabled = false;
                regSubmitBtn.textContent = 'REGISTER';
            }
        });

        // === LOGIN MODAL FUNCTIONS ===
        const loginBtn = document.getElementById('loginBtn');
        const loginModal = document.getElementById('loginModal');
        const loginBackdrop = document.getElementById('loginBackdrop');
        const loginForm = document.getElementById('loginForm');
        const loginEmailInput = document.getElementById('loginEmail');
        const loginPasswordInput = document.getElementById('loginPassword');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');

        function openLoginModal() {
            loginModal.classList.add('active');
            loginBackdrop.classList.add('active');
            loginBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            loginEmailInput.focus();
        }

        function closeLoginModal() {
            loginModal.classList.remove('active');
            loginBackdrop.classList.remove('active');
            loginBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        // Open login modal on button click
        loginBtn.addEventListener('click', openLoginModal);

        // Close login modal when clicking backdrop
        loginBackdrop.addEventListener('click', closeLoginModal);

        // Close login modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && loginModal.classList.contains('active')) {
                closeLoginModal();
            }
        });
        
        // Show login alert message function
        function showLoginAlert(type, message) {
            const alertDiv = document.getElementById('loginAlertMessage');
            alertDiv.style.display = 'block';
            alertDiv.className = '';
            
            if (type === 'success') {
                alertDiv.style.background = '#e8f5e9';
                alertDiv.style.color = '#2e7d32';
                alertDiv.style.borderLeft = '4px solid #4caf50';
            } else {
                alertDiv.style.background = '#ffebee';
                alertDiv.style.color = '#c62828';
                alertDiv.style.borderLeft = '4px solid #e53935';
            }
            
            alertDiv.textContent = message;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 5000);
        }

        // Form submission with AJAX
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = loginEmailInput.value.trim();
            const password = loginPasswordInput.value;

            // Basic validation
            if (!email) {
                showLoginAlert('error', 'Please enter your email address');
                loginEmailInput.focus();
                return;
            }

            if (!password) {
                showLoginAlert('error', 'Please enter your password');
                loginPasswordInput.focus();
                return;
            }

            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showLoginAlert('error', 'Please enter a valid email address');
                loginEmailInput.focus();
                return;
            }

            // Disable submit button
            loginSubmitBtn.disabled = true;
            loginSubmitBtn.textContent = 'LOGGING IN...';

            try {
                const formData = new FormData(loginForm);
                // Get CSRF token from the form
                const csrfTokenElement = document.querySelector('[name="csrf_test_name"]');
                const csrfToken = formData.get('csrf_test_name') || (csrfTokenElement ? csrfTokenElement.value : '');
                console.log('CSRF Token being sent (login):', csrfToken ? 'Present' : 'MISSING');

                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                });

                const data = await response.json();
                console.log('Login response:', data);
                console.log('Redirect URL:', data.redirect);

                if (data.success) {
                    showLoginAlert('success', data.message || 'Login successful! Redirecting...');

                    // Reset form
                    loginForm.reset();

                    // Redirect after 1 second
                    const redirectUrl = data.redirect || '<?= base_url('/home') ?>';
                    console.log('Final redirect to:', redirectUrl);
                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 1000);
                } else {
                    showLoginAlert('error', data.message || 'Login failed. Please check your credentials.');

                    // Re-enable button
                    loginSubmitBtn.disabled = false;
                    loginSubmitBtn.textContent = 'LOG IN';
                }
            } catch (error) {
                console.error('Login error:', error);
                showLoginAlert('error', 'An error occurred. Please try again.');

                // Re-enable button
                loginSubmitBtn.disabled = false;
                loginSubmitBtn.textContent = 'LOG IN';
            }
        });
        <?php endif; // End landing page auth JavaScript ?>

        // === HEADER ICON FUNCTIONS ===
        // Home Icon Click
        const homeIcon = document.getElementById('homeIcon');
        if (homeIcon) {
            homeIcon.addEventListener('click', () => {
                window.location.href = '<?= base_url('/') ?>';
            });
        }

        // FAQ Icon Click
        const faqBtn = document.getElementById('faqBtn');
        if (faqBtn) {
            faqBtn.addEventListener('click', () => {
                window.location.href = '<?= base_url('header/faq') ?>';
            });
        }

        // Header search -> backend home search query
        const headerSearch = document.getElementById('headerSearch');
        const headerSearchResults = document.getElementById('headerSearchResults');
        if (headerSearch) {
            const isUserLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
            let searchDebounceTimer = null;
            let activeSearchController = null;

            const escapeHtml = (value) => {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const getFilterState = () => {
                if (!isUserLoggedIn) {
                    return {
                        top: '',
                        group: '',
                    };
                }

                const currentUrl = new URL(window.location.href);
                return {
                    top: currentUrl.searchParams.get('top') || '',
                    group: currentUrl.searchParams.get('group') || '',
                };
            };

            const buildDestinationUrl = (query) => {
                const destination = new URL('<?= base_url('home') ?>', window.location.origin);
                const filterState = getFilterState();

                if (filterState.top) {
                    destination.searchParams.set('top', filterState.top);
                }
                if (filterState.group) {
                    destination.searchParams.set('group', filterState.group);
                }
                if (query !== '') {
                    destination.searchParams.set('q', query);
                }

                return destination;
            };

            const hideResults = () => {
                if (!headerSearchResults) {
                    return;
                }
                headerSearchResults.classList.remove('active');
                headerSearchResults.innerHTML = '';
            };

            const showEmptyState = (message) => {
                if (!headerSearchResults) {
                    return;
                }

                headerSearchResults.innerHTML = `<div class="search-results-empty">${escapeHtml(message)}</div>`;
                headerSearchResults.classList.add('active');
            };

            const renderSearchResults = (products) => {
                if (!headerSearchResults) {
                    return;
                }

                if (!Array.isArray(products) || products.length === 0) {
                    showEmptyState('No matching products found.');
                    return;
                }

                const limited = products.slice(0, 6);
                headerSearchResults.innerHTML = limited.map((product) => {
                    const productId = Number(product.id || 0);
                    const title = escapeHtml(product.title || 'Untitled Product');
                    const seller = escapeHtml(product.seller || 'ByteMarket Seller');
                    const href = productId > 0 ? `<?= base_url('home/product') ?>/${productId}` : '#';

                    return `
                        <a class="search-result-item" role="option" href="${href}">
                            <div class="search-result-title">${title}</div>
                            <div class="search-result-meta">by ${seller}</div>
                        </a>
                    `;
                }).join('');

                headerSearchResults.classList.add('active');
            };

            const fetchLiveSearchResults = (query) => {
                const trimmed = (query || '').trim();
                if (trimmed.length < 2) {
                    hideResults();
                    return;
                }

                if (activeSearchController) {
                    activeSearchController.abort();
                }

                activeSearchController = new AbortController();

                const filterState = getFilterState();
                const params = new URLSearchParams();
                params.set('q', trimmed);
                if (filterState.top) {
                    params.set('top', filterState.top);
                }
                if (filterState.group) {
                    params.set('group', filterState.group);
                }

                fetch(`<?= base_url('home/search') ?>?${params.toString()}`, {
                    signal: activeSearchController.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then((response) => response.json())
                .then((payload) => {
                    if (!payload || payload.success !== true || !payload.data) {
                        showEmptyState('Unable to load search results.');
                        return;
                    }

                    renderSearchResults(payload.data.products || []);
                })
                .catch((error) => {
                    if (error && error.name === 'AbortError') {
                        return;
                    }

                    showEmptyState('Unable to load search results.');
                });
            };

            const executeHeaderSearch = () => {
                const searchQuery = (headerSearch.value || '').trim();
                const destination = buildDestinationUrl(searchQuery);

                if (typeof window.handleHeaderSearchAjax === 'function') {
                    const filterState = getFilterState();
                    const handled = window.handleHeaderSearchAjax(searchQuery, {
                        top: filterState.top,
                        group: filterState.group,
                    });

                    if (handled === true) {
                        hideResults();
                        return;
                    }
                }

                hideResults();
                window.location.href = destination.toString();
            };

            headerSearch.addEventListener('input', () => {
                const searchValue = (headerSearch.value || '').trim();
                if (searchDebounceTimer) {
                    window.clearTimeout(searchDebounceTimer);
                }

                searchDebounceTimer = window.setTimeout(() => {
                    fetchLiveSearchResults(searchValue);
                }, 250);
            });

            headerSearch.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    executeHeaderSearch();
                }
            });

            headerSearch.addEventListener('focus', () => {
                const query = (headerSearch.value || '').trim();
                if (query.length >= 2 && headerSearchResults && headerSearchResults.innerHTML !== '') {
                    headerSearchResults.classList.add('active');
                }
            });

            document.addEventListener('click', (event) => {
                const searchSection = headerSearch.closest('.search-section');
                if (!searchSection) {
                    return;
                }

                if (!searchSection.contains(event.target)) {
                    hideResults();
                }
            });
        }
    </script>
</body>
</html>
