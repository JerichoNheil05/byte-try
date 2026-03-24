<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sign Up - ByteMarket') ?></title>
    
    <!-- Poppins Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        .signup-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-section h1 {
            color: #667eea;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .logo-section p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label .required {
            color: #e53935;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.error {
            border-color: #e53935;
        }

        .form-control.success {
            border-color: #4caf50;
        }

        .error-message {
            color: #e53935;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .success-message {
            color: #4caf50;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        .password-strength {
            margin-top: 8px;
            display: none;
        }

        .password-strength.show {
            display: block;
        }

        .strength-bar {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-bar-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }

        .strength-bar-fill.weak {
            width: 33%;
            background: #e53935;
        }

        .strength-bar-fill.medium {
            width: 66%;
            background: #ff9800;
        }

        .strength-bar-fill.strong {
            width: 100%;
            background: #4caf50;
        }

        .strength-text {
            font-size: 12px;
            color: #666;
        }

        .password-requirements {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 12px;
            margin-top: 10px;
            font-size: 12px;
            display: none;
        }

        .password-requirements.show {
            display: block;
        }

        .password-requirements h4 {
            font-size: 13px;
            color: #333;
            margin-bottom: 8px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
        }

        .password-requirements li {
            padding: 3px 0;
            color: #666;
        }

        .password-requirements li i {
            width: 16px;
            margin-right: 5px;
        }

        .password-requirements li.valid {
            color: #4caf50;
        }

        .role-selector {
            display: flex;
            gap: 10px;
            margin-top: 8px;
        }

        .role-option {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-option input {
            display: none;
        }

        .role-option:hover {
            border-color: #667eea;
        }

        .role-option input:checked + label {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .role-option label {
            cursor: pointer;
            display: block;
            font-weight: 500;
            color: #333;
        }

        .btn-signup {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-signup:active {
            transform: translateY(0);
        }

        .btn-signup:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #999;
            font-size: 14px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #e53935;
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 576px) {
            .signup-container {
                padding: 30px 20px;
            }

            .logo-section h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="logo-section">
            <h1><i class="fas fa-store"></i> ByteMarket</h1>
            <p>Create your account to get started</p>
        </div>

        <div id="alertMessage"></div>

        <form id="signupForm" method="POST" action="<?= base_url('auth/signup') ?>">
            <?= csrf_field() ?>

            <!-- Full Name -->
            <div class="form-group">
                <label for="full_name">Full Name <span class="required">*</span></label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="full_name" name="full_name" class="form-control" 
                           placeholder="Enter your full name" required>
                </div>
                <div class="error-message" id="fullNameError"></div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="Enter your email" required>
                </div>
                <div class="error-message" id="emailError"></div>
                <div class="success-message" id="emailSuccess"></div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Create a strong password" required>
                </div>
                <div class="error-message" id="passwordError"></div>
                
                <div class="password-strength" id="passwordStrength">
                    <div class="strength-bar">
                        <div class="strength-bar-fill" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText"></div>
                </div>

                <div class="password-requirements" id="passwordRequirements">
                    <h4>Password must contain:</h4>
                    <ul>
                        <li id="req-length"><i class="fas fa-times"></i> At least 8 characters</li>
                        <li id="req-upper"><i class="fas fa-times"></i> One uppercase letter</li>
                        <li id="req-lower"><i class="fas fa-times"></i> One lowercase letter</li>
                        <li id="req-number"><i class="fas fa-times"></i> One number</li>
                        <li id="req-special"><i class="fas fa-times"></i> One special character</li>
                    </ul>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirm">Confirm Password <span class="required">*</span></label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" 
                           placeholder="Confirm your password" required>
                </div>
                <div class="error-message" id="confirmPasswordError"></div>
            </div>

            <!-- Role Selection -->
            <div class="form-group">
                <label>I want to <span class="required">*</span></label>
                <div class="role-selector">
                    <div class="role-option">
                        <input type="radio" id="role-buyer" name="role" value="buyer" checked>
                        <label for="role-buyer">
                            <i class="fas fa-shopping-cart"></i><br>
                            Buy Products
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="role-seller" name="role" value="seller">
                        <label for="role-seller">
                            <i class="fas fa-store"></i><br>
                            Sell Products
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-signup" id="submitBtn">
                Create Account
            </button>
        </form>

        <div class="divider">
            <span>or</span>
        </div>

        <div class="login-link">
            Already have an account? <a href="<?= base_url('auth/login') ?>">Log In</a>
        </div>
    </div>

    <script>
        // Form submission handling
        const form = document.getElementById('signupForm');
        const submitBtn = document.getElementById('submitBtn');
        const alertMessage = document.getElementById('alertMessage');
        
        // Email validation
        const emailInput = document.getElementById('email');
        let emailCheckTimeout;
        
        emailInput.addEventListener('input', function() {
            clearTimeout(emailCheckTimeout);
            emailCheckTimeout = setTimeout(() => {
                checkEmailAvailability(this.value);
            }, 500);
        });
        
        async function checkEmailAvailability(email) {
            if (!email || !validateEmail(email)) return;
            
            try {
                const response = await fetch(`<?= base_url('auth/check-email') ?>?email=${encodeURIComponent(email)}`);
                const data = await response.json();
                
                const emailError = document.getElementById('emailError');
                const emailSuccess = document.getElementById('emailSuccess');
                
                if (data.available) {
                    emailInput.classList.remove('error');
                    emailInput.classList.add('success');
                    emailError.classList.remove('show');
                    emailSuccess.textContent = 'Email available';
                    emailSuccess.classList.add('show');
                } else {
                    emailInput.classList.add('error');
                    emailInput.classList.remove('success');
                    emailSuccess.classList.remove('show');
                    emailError.textContent = 'Email already registered';
                    emailError.classList.add('show');
                }
            } catch (error) {
                console.error('Email check error:', error);
            }
        }
        
        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
        
        // Password strength checking
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordRequirements = document.getElementById('passwordRequirements');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        passwordInput.addEventListener('focus', function() {
            passwordRequirements.classList.add('show');
        });
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            passwordStrength.classList.add('show');
            
            // Check requirements
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };
            
            // Update requirement indicators
            updateRequirement('req-length', requirements.length);
            updateRequirement('req-upper', requirements.upper);
            updateRequirement('req-lower', requirements.lower);
            updateRequirement('req-number', requirements.number);
            updateRequirement('req-special', requirements.special);
            
            // Calculate strength
            const met = Object.values(requirements).filter(Boolean).length;
            let strength = 'weak';
            let strengthClass = 'weak';
            
            if (met === 5) {
                strength = 'Strong';
                strengthClass = 'strong';
            } else if (met >= 3) {
                strength = 'Medium';
                strengthClass = 'medium';
            } else {
                strength = 'Weak';
                strengthClass = 'weak';
            }
            
            strengthBar.className = 'strength-bar-fill ' + strengthClass;
            strengthText.textContent = 'Password strength: ' + strength;
        });
        
        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            const icon = element.querySelector('i');
            
            if (met) {
                element.classList.add('valid');
                icon.className = 'fas fa-check';
            } else {
                element.classList.remove('valid');
                icon.className = 'fas fa-times';
            }
        }
        
        // Confirm password validation
        const confirmPasswordInput = document.getElementById('password_confirm');
        
        confirmPasswordInput.addEventListener('input', function() {
            const confirmError = document.getElementById('confirmPasswordError');
            
            if (this.value && this.value !== passwordInput.value) {
                this.classList.add('error');
                confirmError.textContent = 'Passwords do not match';
                confirmError.classList.add('show');
            } else {
                this.classList.remove('error');
                confirmError.classList.remove('show');
            }
        });
        
        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Creating Account...<span class="loading-spinner"></span>';
            
            try {
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('success', data.message);
                    form.reset();
                    
                    // Redirect after 1 second
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    showAlert('error', data.message);
                    
                    // Show field errors
                    if (data.errors) {
                        for (const [field, message] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(field + 'Error');
                            if (errorElement) {
                                errorElement.textContent = message;
                                errorElement.classList.add('show');
                                document.getElementById(field).classList.add('error');
                            }
                        }
                    }
                    
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create Account';
                }
            } catch (error) {
                showAlert('error', 'An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Account';
            }
        });
        
        function showAlert(type, message) {
            alertMessage.className = 'alert alert-' + type;
            alertMessage.textContent = message;
            alertMessage.style.display = 'block';
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertMessage.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
