        <!-- Cropper.js CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
        <!-- Cropper Modal Styles -->
        <style>
            #cropperModal {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0; top: 0; width: 100vw; height: 100vh;
                background: rgba(0,0,0,0.6);
                align-items: center; justify-content: center;
            }
            #cropperModal.active { display: flex; }
            #cropperBox {
                background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 24px rgba(0,0,0,0.18);
                display: flex; flex-direction: column; align-items: center;
            }
            #cropperBox img { max-width: 400px; max-height: 400px; }
            #cropperActions { margin-top: 18px; display: flex; gap: 16px; }
            #cropperActions button { padding: 10px 28px; border-radius: 6px; border: none; font-size: 1rem; font-weight: bold; cursor: pointer; }
            #cropperSave { background: #27ae60; color: #fff; }
            #cropperCancel { background: #eee; color: #222; }
        </style>
        <!-- Cropper Modal HTML -->
        <div id="cropperModal">
            <div id="cropperBox">
                <img id="cropperImage" src="" alt="Cropper Preview">
                <div id="cropperActions">
                    <button id="cropperSave" type="button">Save</button>
                    <button id="cropperCancel" type="button">Cancel</button>
                </div>
            </div>
        </div>
        <!-- Cropper.js JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <style>
        .profile-editing .profile-details-value {
            display: none;
        }
        .profile-editing .profile-details-input {
            display: block !important;
        }
        .profile-details-input {
            display: none;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #bbb;
            font-size: 1.1rem;
            margin-bottom: 1.2em;
            color: #222;
            background: #fff;
        }
        .profile-save {
            display: none;
            background: #27ae60;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 16px;
        }
        .profile-editing .profile-save {
            display: inline-block;
        }
    </style>
<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Get To Know Me - Byte Market</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { background: #fff; color: #222; margin: 0; font-family: 'Poppins', Arial, sans-serif; }
        .profile-main {
            max-width: 1200px;
            margin: 2.5rem auto 0 auto;
            padding: 40px 32px 0 32px;
            transform: scale(0.85);
            transform-origin: top center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .profile-title {
            align-self: flex-start;
            margin-left: 0;
        }
        .profile-title { 
            width: 100%; 
            max-width: 1200px; 
            min-width: 350px; 
            margin-left: auto; 
            margin-right: auto; 
            font-family: 'Poppins', Arial, sans-serif; 
            font-size: 2.8rem; 
            font-weight: 900; 
            color: #2196f3; 
            margin-bottom: 0.5em; 
            margin-top: 0; 
            letter-spacing: 1px; 
            transform: scale(1.05);
            transform-origin: left center;
            padding-left: 2%;
        }
        .profile-title span { color: #27ae60; text-shadow: 1px 2px 0 #b6e7c9; }
        .profile-top {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            margin-bottom: 32px;
            width: 100%;
            max-width: 1200px;
            min-width: 350px;
            margin-left: auto;
            margin-right: auto;
        }
        .profile-top {
            width: 100%;
            max-width: 1200px;
            min-width: 350px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 32px;
        }

        .profile-section {
            width: 100%; 
            max-width: 1200px; 
            min-width: 350px; 
            margin-left: auto; 
            margin-right: auto; 
            padding-left: 13px;
        }
        .profile-section {
            width: 100%;
            max-width: 1200px;
            min-width: 350px;
            margin-left: auto;
            margin-right: auto;
        }
        .profile-img {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #f4f4f4;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        }
        .profile-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .profile-name {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 2.7rem;
            font-weight: 900;
            margin: 0 0 0.2em 0;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }
        .profile-verified {
            color: #2196f3;
            font-size: 1.6rem;
            margin-left: 10px;
        }
        .profile-role {
            font-style: italic;
            color: #2196f3;
            font-size: 1.1rem;
            margin-bottom: 0.2em;
        }
        .profile-meta {
            color: #444;
            font-size: 1rem;
            margin-bottom: 0.2em;
            font-style: italic;
        }
        .profile-contact {
            color: #222;
            font-size: 1rem;
            margin-bottom: 0.2em;
            font-style: italic;
        }
        .profile-edit {
            margin-left: auto;
            font-size: 1.4rem;
            font-weight: bold;
            color: #111;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .profile-edit:focus { outline: 2px solid #2196f3; }
        .profile-section {
            margin-top: 32px;
        }
        .about-title {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.2em;
        }
        .about-desc {
            font-size: 1.1rem;
            color: #222;
            margin-bottom: 2em;
            max-width: 900px;
        }
        .profile-details {
            display: flex;
            gap: 80px;
            margin-top: 2em;
            margin-bottom: 2em;
        }
        .profile-details-col {
            flex: 1;
        }
        .profile-details-label {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5em;
        }
        .profile-details-value {
            color: #aaa;
            font-size: 1.1rem;
            margin-bottom: 1.2em;
        }
        @media (max-width: 900px) {
            .profile-main { padding: 24px 8px 0 8px; }
            .profile-top, .profile-section { max-width: 100%; min-width: unset; }
            .profile-top { flex-direction: column; align-items: center; gap: 18px; width: 100%; }
            .profile-img { width: 140px; height: 140px; }
            .profile-details { flex-direction: column; gap: 24px; }
        }
    </style>
</head>
<body>
    <?php include APPPATH . 'Views/headerbuyer.php'; ?>
    <div class="profile-main">
        
        <div class="profile-content-scale">
        <div class="profile-top">
            <div style="position:relative;">
                <img src="/public/profile-placeholder.png" alt="Profile" class="profile-img" id="profileImgPreview">
                <label for="profileImgInput" id="profileImgLabel" style="position:absolute;bottom:12px;right:12px;background:#2196f3;color:#fff;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.12);font-size:1.3em;z-index:2;">
                    <span>&#9998;</span>
                </label>
                <input type="file" id="profileImgInput" accept="image/*" style="display:none;">
            </div>
                <script>
                // ...existing script...
                // Profile image upload, crop, and preview
                const profileImgInput = document.getElementById('profileImgInput');
                const profileImgPreview = document.getElementById('profileImgPreview');
                const cropperModal = document.getElementById('cropperModal');
                const cropperImage = document.getElementById('cropperImage');
                const cropperSave = document.getElementById('cropperSave');
                const cropperCancel = document.getElementById('cropperCancel');
                let cropper = null;
                // Removed label onclick to prevent double file dialog
                profileImgInput.onchange = function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            cropperImage.src = evt.target.result;
                            cropperModal.classList.add('active');
                            if (cropper) { cropper.destroy(); }
                            setTimeout(() => {
                                cropper = new Cropper(cropperImage, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    minContainerWidth: 350,
                                    minContainerHeight: 350
                                });
                            }, 100);
                        };
                        reader.readAsDataURL(file);
                    }
                };
                cropperCancel.onclick = function() {
                    cropperModal.classList.remove('active');
                    if (cropper) { cropper.destroy(); cropper = null; }
                    profileImgInput.value = '';
                };
                cropperSave.onclick = function() {
                    if (cropper) {
                        const canvas = cropper.getCroppedCanvas({ width: 220, height: 220 });
                        profileImgPreview.src = canvas.toDataURL('image/png');
                        cropperModal.classList.remove('active');
                        cropper.destroy();
                        cropper = null;
                        profileImgInput.value = '';
                    }
                };
                </script>
            <div class="profile-info">
                <div class="profile-name">[User Name]</div>
                <div class="profile-role">Digital Creator</div>
                <div class="profile-meta">Manila, Philippines</div>
                    <div class="profile-contact">[User Number]</div>
            </div>
            <button class="profile-edit" id="editBtn"><span style="font-size:1.2em;">&#9998;</span> Edit</button>
            <form id="profileEditForm" autocomplete="off">
        </div>
        <div class="profile-section">
            <div class="about-title">About me</div>
            <div class="about-desc" id="aboutValue">
                As a designer, I specialize in creating professionally crafted PowerPoint templates that make your presentations stand out. Whether you're preparing for a business meeting, educational session, or creative pitch, my templates are designed to be visually appealing, easy to use, and fully customizable. Explore my collection and elevate your presentations with stylish, high-quality templates!
            </div>
                <textarea class="profile-details-input" id="aboutInput" name="about" style="display:none; min-height: 90px; width:100%; font-size:1.1rem; margin-bottom:2em;">[User About/Bio]</textarea>
            <div class="profile-details">
                <div class="profile-details-col">
                    <div class="profile-details-label">Country</div>
                    <div class="profile-details-value" id="countryValue">Philippines</div>
                    <input class="profile-details-input" id="countryInput" name="country" value="Philippines">
                        <div class="profile-details-label">Number</div>
                        <div class="profile-details-value" id="numberValue">[User Number]</div>
                        <input class="profile-details-input" id="numberInput" name="number" value="[User Number]">
                </div>
                <div class="profile-details-col">
                    <div class="profile-details-label">City</div>
                    <div class="profile-details-value" id="cityValue">Manila</div>
                    <input class="profile-details-input" id="cityInput" name="city" value="Manila">
                        <div class="profile-details-label">Headline</div>
                        <div class="profile-details-value" id="headlineValue">[User Headline]</div>
                        <input class="profile-details-input" id="headlineInput" name="headline" value="[User Headline]">
                </div>
            </div>
            <button type="submit" class="profile-save" id="saveBtn">Save</button>
        </form>
            <script>
            const editBtn = document.getElementById('editBtn');
            const saveBtn = document.getElementById('saveBtn');
            const form = document.getElementById('profileEditForm');
            const main = document.querySelector('.profile-main');
            const fields = [
                { value: 'countryValue', input: 'countryInput' },
                { value: 'cityValue', input: 'cityInput' },
                { value: 'numberValue', input: 'numberInput' },
                { value: 'headlineValue', input: 'headlineInput' }
            ];
            // Add About Me as editable field
            const aboutValue = document.getElementById('aboutValue');
            const aboutInput = document.getElementById('aboutInput');
            editBtn.onclick = function() {
                main.classList.add('profile-editing');
                fields.forEach(f => {
                    document.getElementById(f.value).style.display = 'none';
                    document.getElementById(f.input).style.display = 'block';
                });
                // Show textarea for About Me
                aboutValue.style.display = 'none';
                aboutInput.style.display = 'block';
                saveBtn.style.display = 'inline-block';
            };
            form.onsubmit = function(e) {
                e.preventDefault();
                fields.forEach(f => {
                    const val = document.getElementById(f.input).value;
                    document.getElementById(f.value).textContent = val;
                    document.getElementById(f.value).style.display = 'block';
                    document.getElementById(f.input).style.display = 'none';
                });
                // Save About Me
                aboutValue.textContent = aboutInput.value;
                aboutValue.style.display = 'block';
                aboutInput.style.display = 'none';
                main.classList.remove('profile-editing');
                saveBtn.style.display = 'none';
            };
            </script>
        </div>
        </div> <!-- end .profile-content-scale -->
    </div>
    <style>
    .profile-content-scale {
        width: 100%; 
        max-width: 1200px; 
        min-width: 350px; 
        margin-left: auto; 
        margin-right: auto; 
        transform: scale(1.05); 
        transform-origin: top center; 
    }
    .profile-content-scale {
        width: 100%;
        max-width: 1200px;
        min-width: 350px;
        margin-left: auto;
        margin-right: auto;
        transform: scale(1.05);
        transform-origin: top center;
    }
    </style>
</body>
</html>
    </script>

    <?= view('footer') ?>
</body>
</html>
