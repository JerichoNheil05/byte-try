<!-- BUYER/SELLER HEADER COMPONENT -->
<header class="navbar-wrapper">
    <!-- BUYER HEADER (for non-sellers) -->
    <?php if (session()->get('role') !== 'seller'): ?>
        <nav class="navbar" role="navigation" aria-label="Buyer Navigation">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= base_url('/') ?>" aria-label="Home">Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('products/list') ?>" aria-label="Browse Products">Browse</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('cart') ?>" aria-label="Shopping Cart">Cart</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('subscription') ?>" class="active" aria-label="Subscription">Seller Membership</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('auth/profile') ?>" aria-label="My Account">Account</a>
                </li>
            </ul>
        </nav>
    <?php else: ?>
        <!-- SELLER HEADER (for active sellers) -->
        <nav class="navbar" role="navigation" aria-label="Seller Dashboard">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" aria-label="Marketer Dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('products') ?>" aria-label="Products">Products</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('orders') ?>" aria-label="Orders">Orders</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('analytics') ?>" aria-label="Analytics">Analytics</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('wallet') ?>" aria-label="Wallet">Wallet</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('subscription') ?>" class="active" aria-label="Subscription">Subscription</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</header>
