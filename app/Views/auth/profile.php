<?= view('header') ?>
<?php $profileBackUrl = previous_url() ?: base_url('home'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Edit Profile - ByteMarket') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #ececec;
            color: #101010;
        }

        body { padding-top: 88px; }

        .profile-page {
            position: relative;
            min-height: calc(100vh - 88px);
            overflow: hidden;
            padding: 36px 18px 48px;
        }

        .shape { position: absolute; z-index: 0; pointer-events: none; }
        .shape-tri { top: 62px; left: -16px; width: 0; height: 0; border-left: 44px solid transparent; border-right: 44px solid transparent; border-bottom: 68px solid #c3c3c3; transform: rotate(-24deg); }
        .shape-circle { top: 90px; right: 82px; width: 88px; height: 88px; border-radius: 50%; background: #c4d3e8; }
        .shape-line { bottom: 70px; right: -40px; width: 250px; height: 26px; background: #d3e0cf; transform: rotate(-29deg); }

        .profile-shell {
            position: relative;
            z-index: 1;
            max-width: 820px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 18px 18px 28px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            padding: 8px 12px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #d9d9d9;
            color: #111111;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            width: fit-content;
        }

        .back-btn:hover {
            background: #f7f7f7;
            border-color: #c8c8c8;
        }

        .back-btn svg {
            width: 14px;
            height: 14px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .flash {
            margin-bottom: 14px;
            border-radius: 8px;
            padding: 11px 13px;
            font-size: 13px;
            display: none;
        }

        .flash.show { display: block; }
        .flash.success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .flash.error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }

        .profile-head {
            display: flex;
            align-items: center;
            gap: 28px;
            padding: 16px 6px 20px;
            margin-bottom: 18px;
        }

        .avatar-wrap { position: relative; width: 156px; height: 156px; flex-shrink: 0; }
        .avatar-img {
            width: 156px;
            height: 156px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
            background: #f2f2f2;
        }

        .avatar-edit {
            position: absolute;
            left: 10px;
            bottom: 14px;
            border: none;
            background: rgba(16, 16, 16, 0.66);
            color: #fff;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .avatar-edit span { font-size: 14px; }

        .hidden-file { display: none; }

        .profile-name {
            font-size: clamp(24px, 3.8vw, 32px);
            font-weight: 700;
            line-height: 1.05;
            margin-bottom: 4px;
        }

        .profile-meta {
            font-size: 13px;
            color: #181818;
            line-height: 1.35;
        }

        .card-title {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.05;
            margin-bottom: 8px;
        }

        .section {
            margin-top: 16px;
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.1;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group { display: flex; flex-direction: column; }

        .form-group label {
            font-size: 13px;
            color: #2e2e2e;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .form-group input {
            border: 1px solid #6aa5df;
            background: #f6f6f6;
            color: #222;
            border-radius: 7px;
            font-size: 15px;
            font-family: 'Poppins', Arial, sans-serif;
            padding: 10px 12px;
            height: 46px;
        }

        .form-group input::placeholder { color: #8a8a8a; }

        .form-group input:focus {
            outline: none;
            border-color: #308BE5;
            box-shadow: 0 0 0 3px rgba(48, 139, 229, 0.16);
            background: #fff;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        .btn-save {
            border: none;
            background: #308BE5;
            color: #fff;
            border-radius: 12px;
            padding: 10px 34px;
            font-size: 18px;
            font-weight: 700;
            font-family: 'Poppins', Arial, sans-serif;
            cursor: pointer;
            min-width: 160px;
            transition: all 0.2s ease;
        }

        .btn-save:hover:not(:disabled) {
            background: #2670b8;
            transform: translateY(-1px);
        }

        .btn-save:disabled {
            background: #8fbdea;
            cursor: not-allowed;
        }

        @media (max-width: 920px) {
            .profile-name, .section-title, .card-title { font-size: 21px; }
            .form-group input { font-size: 14px; height: 44px; }
            .btn-save { font-size: 16px; padding: 9px 26px; min-width: 140px; }
            .avatar-edit { font-size: 16px; padding: 4px 7px; }
            .avatar-edit span { font-size: 13px; }
        }

        @media (max-width: 760px) {
            .profile-head { flex-direction: column; align-items: flex-start; gap: 14px; }
            .grid-3 { grid-template-columns: 1fr; gap: 12px; }
            .btn-save { width: 100%; min-width: 0; }
        }

        .crop-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2200;
            padding: 16px;
        }

        .crop-modal.show { display: flex; }

        .crop-dialog {
            width: min(680px, 100%);
            max-height: 90vh;
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 14px 36px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .crop-title {
            font-size: 16px;
            font-weight: 600;
        }

        .crop-area {
            width: 100%;
            height: min(58vh, 420px);
            background: #f2f2f2;
            border-radius: 10px;
            overflow: hidden;
        }

        .crop-area img {
            display: block;
            width: 100%;
            max-width: 100%;
        }

        .crop-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-crop {
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-crop-cancel {
            background: #ebebeb;
            color: #333;
        }

        .btn-crop-apply {
            background: #308BE5;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="profile-page">
        <div class="shape shape-tri"></div>
        <div class="shape shape-circle"></div>
        <div class="shape shape-line"></div>

        <div class="profile-shell">
            <a href="<?= esc($profileBackUrl, 'attr') ?>" class="back-btn" aria-label="Go back">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
                Back
            </a>

            <div id="profileAlert" class="flash"></div>

            <?php
                $profileImageRaw = trim((string) ($user['profile_image'] ?? ''));
                if ($profileImageRaw === '') {
                    $profileImageUrl = base_url('assets/images/default-avatar.svg');
                } elseif (preg_match('/^https?:\/\//i', $profileImageRaw)) {
                    $profileImageUrl = $profileImageRaw;
                } elseif (strpos($profileImageRaw, 'uploads/') === 0) {
                    $profileImageUrl = base_url(ltrim($profileImageRaw, '/'));
                } else {
                    $profileImageUrl = base_url('uploads/profiles/' . ltrim($profileImageRaw, '/'));
                }

                $displayName = trim((string) ($user['full_name'] ?? 'User'));
                $displayCity = trim((string) ($user['city'] ?? ''));
                $displayCountry = trim((string) ($user['country'] ?? ''));
                $displayPhone = trim((string) ($user['phone'] ?? ''));
                $locationText = trim($displayCity . ( $displayCity !== '' && $displayCountry !== '' ? ', ' : '') . $displayCountry);
                if ($locationText === '') {
                    $locationText = 'Location not set';
                }
                if ($displayPhone === '') {
                    $displayPhone = 'Phone not set';
                }
            ?>

            <form id="buyerProfileForm" method="POST" action="<?= base_url('auth/profile/update') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="profile-head">
                    <div class="avatar-wrap">
                        <img id="avatarPreview" class="avatar-img" src="<?= esc($profileImageUrl) ?>" alt="Profile picture of <?= esc($displayName) ?>">
                        <button type="button" class="avatar-edit" id="avatarEditBtn" aria-label="Edit profile picture">✎ <span>Edit</span></button>
                        <input type="file" id="profileImageInput" class="hidden-file" name="profile_image" accept="image/*">
                    </div>

                    <div>
                        <h1 class="profile-name" id="displayName"><?= esc($displayName) ?></h1>
                        <p class="profile-meta" id="displayLocation"><?= esc($locationText) ?></p>
                        <p class="profile-meta" id="displayPhone"><?= esc($displayPhone) ?></p>
                    </div>
                </div>

                <section class="section">
                    <h2 class="section-title">Personal Info</h2>
                    <div class="grid-3">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" value="<?= esc($displayName) ?>" maxlength="120">
                        </div>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-title">Address</h2>
                    <div class="grid-3">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" placeholder="Country" value="<?= esc($user['country'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" placeholder="City" value="<?= esc($user['city'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Number</label>
                            <input type="text" id="phone" name="phone" placeholder="09XXXXXXXXX" value="<?= esc($user['phone'] ?? '') ?>" inputmode="numeric" maxlength="11" pattern="\d{11}" title="Phone number must be exactly 11 digits, for example 09XXXXXXXXX">
                        </div>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-title">Change Password</h2>
                    <div class="grid-3">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                        </div>
                    </div>
                </section>

                <div class="actions">
                    <button type="submit" class="btn-save" id="saveProfileBtn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="crop-modal" id="cropModal" aria-hidden="true">
        <div class="crop-dialog" role="dialog" aria-modal="true" aria-labelledby="cropTitle">
            <h3 class="crop-title" id="cropTitle">Crop profile picture</h3>
            <div class="crop-area">
                <img id="cropImage" alt="Crop preview">
            </div>
            <div class="crop-actions">
                <button type="button" class="btn-crop btn-crop-cancel" id="cropCancelBtn">Cancel</button>
                <button type="button" class="btn-crop btn-crop-apply" id="cropApplyBtn">Apply</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
    <script>
        (() => {
            const profileForm = document.getElementById('buyerProfileForm');
            const saveBtn = document.getElementById('saveProfileBtn');
            const profileAlert = document.getElementById('profileAlert');
            const avatarEditBtn = document.getElementById('avatarEditBtn');
            const profileImageInput = document.getElementById('profileImageInput');
            const avatarPreview = document.getElementById('avatarPreview');
            const tokenInput = profileForm.querySelector('input[name="<?= csrf_token() ?>"]');

            const countryInput = document.getElementById('country');
            const cityInput = document.getElementById('city');
            const phoneInput = document.getElementById('phone');
            const fullNameInput = document.getElementById('full_name');
            const displayName = document.getElementById('displayName');
            const currentPasswordInput = document.getElementById('current_password');
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const displayLocation = document.getElementById('displayLocation');
            const displayPhone = document.getElementById('displayPhone');
            const cropModal = document.getElementById('cropModal');
            const cropImage = document.getElementById('cropImage');
            const cropCancelBtn = document.getElementById('cropCancelBtn');
            const cropApplyBtn = document.getElementById('cropApplyBtn');

            let cropper = null;
            let currentObjectUrl = '';
            let croppedImageFile = null;

            function showAlert(type, message) {
                profileAlert.className = 'flash show ' + type;
                profileAlert.textContent = message;
            }

            function updateCsrfHash(hash) {
                if (typeof hash === 'string' && hash !== '' && tokenInput) {
                    tokenInput.value = hash;
                }
            }

            function parseJsonResponse(response) {
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (error) {
                        throw new Error('Unexpected response. Please refresh the page and try again.');
                    }
                });
            }

            function closeCropModal() {
                cropModal.classList.remove('show');
                cropModal.setAttribute('aria-hidden', 'true');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                    currentObjectUrl = '';
                }
            }

            function openCropModal(file) {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                }

                currentObjectUrl = URL.createObjectURL(file);
                cropImage.src = currentObjectUrl;
                cropModal.classList.add('show');
                cropModal.setAttribute('aria-hidden', 'false');

                cropImage.onload = () => {
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1,
                        responsive: true,
                        background: false,
                    });
                };
            }

            avatarEditBtn.addEventListener('click', () => profileImageInput.click());

            phoneInput.addEventListener('input', () => {
                phoneInput.value = phoneInput.value.replace(/\D/g, '').slice(0, 11);
            });

            profileImageInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (!file) {
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    showAlert('error', 'Please select a valid image file.');
                    profileImageInput.value = '';
                    return;
                }

                openCropModal(file);
            });

            cropCancelBtn.addEventListener('click', () => {
                croppedImageFile = null;
                profileImageInput.value = '';
                closeCropModal();
            });

            cropApplyBtn.addEventListener('click', () => {
                if (!cropper) {
                    return;
                }

                const canvas = cropper.getCroppedCanvas({
                    width: 600,
                    height: 600,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                canvas.toBlob((blob) => {
                    if (!blob) {
                        showAlert('error', 'Unable to crop image. Please try another image.');
                        return;
                    }

                    croppedImageFile = new File([blob], 'profile-crop.jpg', { type: 'image/jpeg' });
                    avatarPreview.src = URL.createObjectURL(blob);
                    closeCropModal();
                }, 'image/jpeg', 0.9);
            });

            cropModal.addEventListener('click', (event) => {
                if (event.target === cropModal) {
                    closeCropModal();
                }
            });

            profileForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                showAlert('success', 'Saving profile...');
                saveBtn.disabled = true;

                try {
                    const fullNameValue = fullNameInput.value.trim();
                    if (fullNameValue === '') {
                        throw new Error('Full name is required.');
                    }

                    const phoneValue = phoneInput.value.trim();
                    if (phoneValue !== '' && !/^\d{11}$/.test(phoneValue)) {
                        throw new Error('Phone number must contain exactly 11 digits.');
                    }

                    const profileData = new FormData(profileForm);
                    profileData.delete('current_password');
                    profileData.delete('new_password');
                    profileData.delete('confirm_password');

                    if (croppedImageFile) {
                        profileData.set('profile_image', croppedImageFile, croppedImageFile.name);
                    }

                    const profileResponse = await fetch('<?= base_url('auth/profile/update') ?>', {
                        method: 'POST',
                        body: profileData,
                    });
                    const profileResult = await parseJsonResponse(profileResponse);
                    updateCsrfHash(profileResult.csrfHash || '');

                    if (!profileResult.success) {
                        throw new Error(profileResult.message || 'Failed to update profile.');
                    }

                    const shouldChangePassword = newPasswordInput.value.trim() !== '';

                    if (shouldChangePassword) {
                        if (currentPasswordInput.value.trim() === '') {
                            throw new Error('Please enter your current password to change password.');
                        }

                        if (confirmPasswordInput.value.trim() === '') {
                            throw new Error('Please confirm your new password.');
                        }

                        if (newPasswordInput.value !== confirmPasswordInput.value) {
                            throw new Error('New passwords do not match.');
                        }

                        const passwordData = new URLSearchParams();
                        passwordData.append('<?= csrf_token() ?>', tokenInput ? tokenInput.value : '');
                        passwordData.append('current_password', currentPasswordInput.value);
                        passwordData.append('new_password', newPasswordInput.value);
                        passwordData.append('confirm_password', confirmPasswordInput.value);

                        const passwordResponse = await fetch('<?= base_url('auth/password/change') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            },
                            body: passwordData.toString(),
                        });

                        const passwordResult = await parseJsonResponse(passwordResponse);
                        updateCsrfHash(passwordResult.csrfHash || '');

                        if (!passwordResult.success) {
                            throw new Error(passwordResult.message || 'Failed to change password.');
                        }
                    }

                    const city = cityInput.value.trim();
                    const country = countryInput.value.trim();
                    const phone = phoneInput.value.trim();
                    const fullName = fullNameInput.value.trim();
                    displayName.textContent = fullName || 'User';
                    displayLocation.textContent = city + (city && country ? ', ' : '') + country || 'Location not set';
                    displayPhone.textContent = phone || 'Phone not set';

                    window.dispatchEvent(new CustomEvent('profile:updated', {
                        detail: {
                            fullName: (profileResult.user && profileResult.user.full_name) ? profileResult.user.full_name : '',
                            profileImageUrl: profileResult.profileImageUrl || '',
                        }
                    }));

                    currentPasswordInput.value = '';
                    newPasswordInput.value = '';
                    confirmPasswordInput.value = '';
                    croppedImageFile = null;
                    profileImageInput.value = '';

                    showAlert('success', 'Profile saved successfully.');
                } catch (error) {
                    showAlert('error', error.message || 'Failed to save profile.');
                } finally {
                    saveBtn.disabled = false;
                }
            });
        })();
    </script>

    <?= view('footer') ?>
</body>
</html>
