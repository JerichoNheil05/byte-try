# Byte Market Header Component

## Overview
The header component is a reusable, responsive navigation bar for the Byte Market application. It includes a logo, search functionality, navigation icons, and a user profile dropdown menu.

## Files Created

### 1. **app/Views/header.php**
- Main header view file with complete HTML, CSS, and JavaScript
- Features:
  - Byte Market logo with animated icon
  - Centered search bar
  - Shopping cart and home icons
  - User profile button with dropdown modal
  - Responsive design for mobile/tablet/desktop
  - Accessibility features (ARIA labels, keyboard navigation)

### 2. **app/Controllers/Header.php**
- Controller with placeholder methods for all header actions
- Methods:
  - `index()` - Returns header view
  - `account()` - Account editing (placeholder)
  - `dashboard()` - Redirects to marketer dashboard
  - `notifications()` - Notifications management (placeholder)
  - `settings()` - Account settings (placeholder)
  - `faq()` - FAQ page (placeholder)
  - `logout()` - User logout functionality

### 3. **app/Config/Routes.php** (Updated)
- Added routes for all header navigation options:
  ```php
  $routes->get('header', 'Header::index');
  $routes->get('header/account', 'Header::account');
  $routes->get('header/dashboard', 'Header::dashboard');
  $routes->get('header/notifications', 'Header::notifications');
  $routes->get('header/settings', 'Header::settings');
  $routes->get('header/faq', 'Header::faq');
  $routes->get('header/logout', 'Header::logout');
  ```

### 4. **public/assets/images/default-avatar.svg**
- Default user avatar (SVG format)
- Blue background with white user silhouette

### 5. **app/Views/header-demo.php**
- Demo page showing header implementation
- Access at: `http://localhost/bytemarket/public/header-demo`

## Usage

### Including the Header in Other Views
To add the header to any page, simply include this line at the top of your view file:

```php
<?= view('header') ?>
```

### Example Integration
```php
<!-- app/Views/my-page.php -->
<?= view('header') ?>

<main>
    <!-- Your page content here -->
</main>
```

## Design Specifications

### Colors
- **Blue:** `#308BE5` (primary highlights, active states)
- **Green:** `#249E2F` (logo accent)
- **Black:** `#000000` (text, icons)
- **White:** `#FFFFFF` (backgrounds, contrast text)
- **Gray:** `#E8EAED` (search bar background)

### Typography
- **Font Family:** Poppins (Google Fonts)
- **Logo:** 20px, weight 600
- **Search:** 14px, weight 400
- **Dropdown:** 15px, weight 400-600

### Layout
- **Header Height:** ~80px (with padding)
- **Position:** Sticky (fixed at top on scroll)
- **Max Width:** 1600px
- **Responsive Breakpoints:**
  - Desktop: 1024px+
  - Tablet: 768px - 1023px
  - Mobile: < 768px

## Features

### 1. Logo Section
- Animated phone + shopping bag icon
- "Byte Market" text branding
- Links to homepage

### 2. Search Bar
- Centered in header
- Gray background (#E8EAED)
- Search icon on the left
- Placeholder: "Search products, services, or categories..."
- JavaScript hook for search functionality (placeholder)

### 3. Navigation Icons
- **Shopping Cart:** Links to cart (placeholder)
- **Home:** Links to homepage

### 4. Profile Dropdown
- Triggers modal/dropdown menu on click
- Contains:
  - User profile picture
  - User name: "Lalisa Manoban" (placeholder)
  - "Edit Account" link
  - **Marketer Dashboard** (outlined button in blue)
  - Notifications (with bell icon)
  - **Account Settings** (highlighted in blue)
  - FAQ (with question mark icon)
  - Logout (with arrow icon)

### 5. Modal Behavior
- **Toggle:** Click profile button to open/close
- **Close:** Click outside modal or press ESC key
- **Animation:** Smooth fade-in/fade-out
- **Backdrop:** Semi-transparent overlay

### 6. Accessibility
- ARIA labels for all interactive elements
- Keyboard navigation support
- Focus states for all buttons/links
- High contrast text
- Screen reader friendly

## Responsive Design

### Desktop (1024px+)
- Full logo with text
- Full-width search bar
- All icons visible
- Dropdown positioned under profile

### Tablet (768px - 1023px)
- Slightly reduced spacing
- Search bar adapts to available space
- All features remain visible

### Mobile (< 768px)
- Logo text hidden (icon only)
- Search bar full width
- Icons condensed
- Dropdown full width (minus padding)

## Backend Integration (TODO)

### User Data
Replace placeholder user data with dynamic values from database:
```php
<!-- In header.php -->
<h2 class="modal-user-name" id="modalUserName">
    <?= esc($user['name']) ?>
</h2>

<img src="<?= esc($user['profile_picture']) ?>" ...>
```

### Search Functionality
Implement search in JavaScript:
```javascript
document.getElementById('headerSearch').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        const searchQuery = e.target.value;
        window.location.href = '<?= base_url('search') ?>?q=' + encodeURIComponent(searchQuery);
    }
});
```

### Cart Functionality
Update cart icon click handler:
```javascript
document.getElementById('cartIcon').addEventListener('click', () => {
    window.location.href = '<?= base_url('cart') ?>';
});
```

### Notifications Badge
Add notification count indicator:
```php
<button class="modal-option" id="btnNotifications">
    <span class="modal-option-icon">
        <svg>...</svg>
        <?php if ($notification_count > 0): ?>
            <span class="notification-badge"><?= $notification_count ?></span>
        <?php endif; ?>
    </span>
    <span>Notifications</span>
</button>
```

## Testing

### Test URLs
- **Header Demo:** `http://localhost/bytemarket/public/header-demo`
- **Header Only:** `http://localhost/bytemarket/public/header`
- **Account:** `http://localhost/bytemarket/public/header/account`
- **Dashboard:** `http://localhost/bytemarket/public/header/dashboard`
- **Notifications:** `http://localhost/bytemarket/public/header/notifications`
- **Settings:** `http://localhost/bytemarket/public/header/settings`
- **FAQ:** `http://localhost/bytemarket/public/header/faq`
- **Logout:** `http://localhost/bytemarket/public/header/logout`

### Verified Features
✅ Header displays correctly  
✅ Search bar functional (placeholder)  
✅ Profile dropdown toggles on click  
✅ Modal closes on backdrop click  
✅ Modal closes on ESC key  
✅ All routes accessible  
✅ Responsive design working  
✅ Accessibility features implemented  

## Customization

### Changing Active Menu Item
Remove `class="active"` from current item and add to desired item:
```php
<a href="<?= base_url('header/settings') ?>" class="modal-option active">
    Account Settings
</a>
```

### Changing User Name
Update the placeholder text:
```php
<h2 class="modal-user-name" id="modalUserName">Your Name Here</h2>
```

### Changing Profile Picture
Replace the avatar image source:
```php
<img src="<?= base_url('assets/images/your-avatar.jpg') ?>" ...>
```

## Browser Support
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- Mobile browsers: ✅ Full support

## Dependencies
- **Google Fonts:** Poppins (loaded via CDN)
- **CodeIgniter 4:** Framework
- **No external JavaScript libraries required**

## Notes
- Header uses inline styles for self-contained deployment
- JavaScript is embedded in the view file
- No external CSS files required
- SVG icons are inline for better performance
- Modal uses CSS transitions for smooth animations
- Profile picture defaults to SVG avatar if user image unavailable

## Future Enhancements
- [ ] Implement search autocomplete
- [ ] Add shopping cart item count badge
- [ ] Integrate real user profile data
- [ ] Add notification count indicator
- [ ] Implement dark mode toggle
- [ ] Add language selector
- [ ] Mobile hamburger menu for additional navigation
