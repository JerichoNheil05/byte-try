<!-- Include Header -->
<?= view('header') ?>

<!-- Main Content -->
<main style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <section style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 32px; color: #000000; margin-bottom: 20px;">
            Header Component Demo
        </h1>
        
        <p style="font-family: 'Poppins', sans-serif; font-size: 16px; color: #444; line-height: 1.8; margin-bottom: 24px;">
            This page demonstrates the Byte Market header component. The header includes:
        </p>
        
        <ul style="font-family: 'Poppins', sans-serif; font-size: 15px; color: #444; line-height: 2; margin-left: 20px; margin-bottom: 24px;">
            <li><strong>Logo:</strong> Byte Market branding with phone and shopping bag icon</li>
            <li><strong>Search Bar:</strong> Centered search functionality (placeholder)</li>
            <li><strong>Shopping Cart Icon:</strong> Quick access to cart (placeholder)</li>
            <li><strong>Home Icon:</strong> Navigate back to homepage</li>
            <li><strong>Profile Menu:</strong> Click the profile button to open dropdown with options:
                <ul style="margin-left: 20px; margin-top: 8px;">
                    <li>Edit Account</li>
                    <li>Marketer Dashboard (outlined button)</li>
                    <li>Notifications</li>
                    <li>Account Settings (highlighted in blue)</li>
                    <li>FAQ</li>
                    <li>Logout</li>
                </ul>
            </li>
        </ul>
        
        <div style="background: #F9FAFB; border-left: 4px solid #308BE5; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
            <h3 style="font-family: 'Poppins', sans-serif; font-size: 18px; color: #308BE5; margin-bottom: 12px;">
                💡 Usage Instructions
            </h3>
            <p style="font-family: 'Poppins', sans-serif; font-size: 14px; color: #444; line-height: 1.6;">
                To include this header in any view, simply add: <code style="background: white; padding: 4px 8px; border-radius: 4px; font-family: monospace;">&lt;?= view('header') ?&gt;</code> at the top of your PHP view file.
            </p>
        </div>
        
        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
            <a href="<?= base_url('/') ?>" 
               style="display: inline-block; padding: 12px 32px; background: #308BE5; color: white; text-decoration: none; border-radius: 8px; font-family: 'Poppins', sans-serif; font-weight: 600; transition: all 0.3s ease;">
                Go to Landing Page
            </a>
            <a href="<?= base_url('dashboard') ?>" 
               style="display: inline-block; padding: 12px 32px; background: #000000; color: white; text-decoration: none; border-radius: 8px; font-family: 'Poppins', sans-serif; font-weight: 600; transition: all 0.3s ease;">
                Go to Dashboard
            </a>
        </div>
    </section>
    
    <!-- Spacer for demonstration -->
    <div style="height: 800px;"></div>
    
    <section style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="font-family: 'Poppins', sans-serif; font-size: 24px; color: #000000; margin-bottom: 16px;">
            Scroll Test
        </h2>
        <p style="font-family: 'Poppins', sans-serif; font-size: 15px; color: #444; line-height: 1.8;">
            Notice that the header remains fixed at the top as you scroll down the page. This ensures consistent navigation access throughout your browsing experience.
        </p>
    </section>
</main>
