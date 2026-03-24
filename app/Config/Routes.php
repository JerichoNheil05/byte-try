<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Landing page (default homepage)
$routes->get('/', 'Landing::index');
$routes->get('landing', 'Landing::index');
$routes->get('landing/join', 'Landing::join');
$routes->get('landing/buy', 'Landing::buy');

// Home page with product marketplace
$routes->get('home', 'Home::index');
$routes->get('home/search', 'Home::search');
$routes->get('home/category/(:any)', 'Home::category/$1');
$routes->get('home/product/(:any)', 'Home::product/$1');
$routes->post('home/product/(:num)/feedback', 'Home::submitFeedback/$1');

// Analytics routes (seller only)
$routes->get('analytics', 'Analytics::index', ['filter' => 'auth:seller']);
$routes->get('analytics/sales', 'Analytics::sales', ['filter' => 'auth:seller']);
$routes->get('analytics/orders', 'Analytics::orders', ['filter' => 'auth:seller']);
$routes->get('analytics/products', 'Analytics::products', ['filter' => 'auth:seller']);

// Orders page (seller only)
$routes->get('orders', 'Orders::index', ['filter' => 'auth:seller']);

// My Orders page (buyer - purchases)
$routes->get('my-orders', 'Orders::myOrders', ['filter' => 'auth']);
$routes->get('my-orders/download/(:num)', 'Orders::downloadProduct/$1', ['filter' => 'auth']);
$routes->post('my-orders/refund', 'Orders::requestRefund', ['filter' => 'auth']);
$routes->get('my-orders/refund/results', 'Orders::pollRefundResults', ['filter' => 'auth']);

// Wallet routes (seller only)
$routes->get('wallet', 'Wallet::index', ['filter' => 'auth:seller']);
$routes->post('wallet/cashout', 'Wallet::cashout', ['filter' => 'auth:seller']);
$routes->get('wallet/transactions', 'Wallet::transactions', ['filter' => 'auth:seller']);
$routes->get('wallet/balance', 'Wallet::balance', ['filter' => 'auth:seller']);
$routes->get('wallet/preview/(:any)', 'Wallet::preview/$1', ['filter' => 'auth:seller']);

// Products routes (seller only)
$routes->get('products', 'Products::index', ['filter' => 'auth:seller']);
$routes->get('products/details/(:num)', 'Products::details/$1', ['filter' => 'auth:seller']);
$routes->get('products/add', 'Products::add', ['filter' => 'auth:seller']);
$routes->post('products/save', 'Products::save', ['filter' => 'auth:seller']);
$routes->get('products/edit/(:num)', 'Products::edit/$1', ['filter' => 'auth:seller']);
$routes->post('products/update/(:num)', 'Products::update/$1', ['filter' => 'auth:seller']);
$routes->get('products/delete/(:num)', 'Products::delete/$1', ['filter' => 'auth:seller']);

// Dashboard
$routes->get('dashboard', 'Home::dashboard', ['filter' => 'auth:seller']);

// Header navigation routes
$routes->get('header', 'Header::index');
$routes->get('header/account', 'Header::account', ['filter' => 'auth']);
$routes->get('header/dashboard', 'Header::dashboard', ['filter' => 'auth:seller']);
$routes->get('header/notifications', 'Header::notifications', ['filter' => 'auth']);
$routes->get('header/settings', 'Header::settings', ['filter' => 'auth']);
$routes->get('header/faq', 'Header::faq');
$routes->get('header/logout', 'Header::logout', ['filter' => 'auth']);

// Header demo page
$routes->get('header-demo', function() {
    return view('header-demo');
});

// Authentication routes
$routes->group('auth', function($routes) {
    // Signup routes
    $routes->get('signup', 'Auth::signupForm', ['filter' => 'guest']);
    $routes->post('signup', 'Auth::signup'); // No filter for POST testing
    
    // Login routes
    $routes->get('login', 'Auth::loginForm', ['filter' => 'guest']);
    $routes->post('login', 'Auth::login'); // No filter for POST testing
});

// Protected auth routes (require authentication)
$routes->group('auth', ['filter' => 'auth'], function($routes) {
    $routes->get('logout', 'Auth::logout');
    $routes->get('profile', 'Auth::profile');
    $routes->post('profile/update', 'Auth::updateProfile');
    $routes->post('password/change', 'Auth::changePassword');
});

// Email availability check (AJAX)
$routes->get('auth/check-email', 'Auth::checkEmail');

// Test endpoint
$routes->get('auth/test', 'Auth::test');

// Legacy route redirects for backward compatibility
$routes->get('registration', 'Auth::signupForm');
$routes->post('registration', 'Auth::signup');
$routes->get('login', 'Auth::loginForm');
$routes->post('login', 'Auth::login');
$routes->get('signin', 'Auth::loginForm');

// Sign in, terms, privacy, and contact routes
$routes->get('terms', function() {
    return view('terms');
});
$routes->get('privacy', function() {
    return view('privacy');
});
$routes->get('header/contact', 'Header::contact');
$routes->post('header/contact', 'Header::contactSend');

// About Us page
$routes->get('about', 'About::index');

// Products routes (seller product management)
$routes->get('products/add', 'Products::add', ['filter' => 'auth:seller']);
$routes->post('products/save', 'Products::save', ['filter' => 'auth:seller']);
$routes->get('products/list', 'Products::list', ['filter' => 'auth:seller']);
$routes->get('products/edit/(:num)', 'Products::edit/$1', ['filter' => 'auth:seller']);
$routes->post('products/delete/(:num)', 'Products::delete/$1', ['filter' => 'auth:seller']);

// ByteFolio routes (seller portfolio)
$routes->get('bytefolio', 'Bytefolio::index', ['filter' => 'auth:seller']);
$routes->post('bytefolio/update_profile', 'Bytefolio::update_profile', ['filter' => 'auth:seller']);
$routes->post('bytefolio/upload_picture', 'Bytefolio::upload_picture', ['filter' => 'auth:seller']);
$routes->post('bytefolio/update_about', 'Bytefolio::update_about', ['filter' => 'auth:seller']);
$routes->get('bytefolio/products', 'Bytefolio::products', ['filter' => 'auth:seller']);
$routes->get('bytefolio/stats', 'Bytefolio::get_stats', ['filter' => 'auth:seller']);
// Subscription routes (seller subscription management)
$routes->get('subscription', 'Subscription::index', ['filter' => 'auth']);
$routes->post('subscription/update_email', 'Subscription::update_email', ['filter' => 'auth']);
$routes->post('subscription/update_password', 'Subscription::update_password', ['filter' => 'auth']);
$routes->post('subscription/update_cashout', 'Subscription::update_cashout_method', ['filter' => 'auth']);
$routes->post('subscription/activate', 'Subscription::activate', ['filter' => 'auth']);
$routes->post('subscription/cancel', 'Subscription::cancel', ['filter' => 'auth']);
$routes->post('subscription/terms_agree', 'Subscription::terms_agree', ['filter' => 'auth']);
$routes->post('subscription/terms_decline', 'Subscription::terms_decline', ['filter' => 'auth']);

// Payment simulation routes (seller subscription)
$routes->post('payment/seller-subscription/checkout-session', 'PaymentSimulation::createSellerSubscriptionCheckoutSession', ['filter' => 'auth']);
$routes->get('payment/seller-subscription/authorize-test', 'PaymentSimulation::authorizeTestPayment', ['filter' => 'auth']);
$routes->get('payment/seller-subscription/success', 'PaymentSimulation::sellerSubscriptionSuccess', ['filter' => 'auth']);
$routes->get('payment/seller-subscription/status', 'PaymentSimulation::sellerSubscriptionStatus', ['filter' => 'auth']);
$routes->post('payment/seller-subscription/mark-paid', 'PaymentSimulation::markSellerSubscriptionPaid', ['filter' => 'auth']);

// Payment simulation routes (digital product)
$routes->post('payment/digital-product/checkout-session', 'PaymentSimulation::createDigitalProductCheckoutSession', ['filter' => 'auth']);
$routes->get('payment/digital-product/authorize-test', 'PaymentSimulation::authorizeDigitalProductTestPayment', ['filter' => 'auth']);
$routes->get('payment/digital-product/success', 'PaymentSimulation::digitalProductSuccess', ['filter' => 'auth']);
$routes->get('payment/digital-product/status', 'PaymentSimulation::digitalProductStatus', ['filter' => 'auth']);
$routes->post('payment/digital-product/mark-paid', 'PaymentSimulation::markDigitalProductPaid', ['filter' => 'auth']);

// Cart routes (shopping cart management)
$routes->get('cart', 'Cart::index', ['filter' => 'auth']);
$routes->post('cart/add', 'Cart::add', ['filter' => 'auth']);
$routes->post('cart/add/(:any)', 'Cart::add/$1', ['filter' => 'auth']);
$routes->post('cart/remove', 'Cart::remove', ['filter' => 'auth']);
$routes->post('cart/update_selection', 'Cart::update_selection', ['filter' => 'auth']);
$routes->get('cart/count', 'Cart::get_count', ['filter' => 'auth']);
$routes->post('cart/clear', 'Cart::clear', ['filter' => 'auth']);
$routes->post('cart/checkout', 'Cart::checkout', ['filter' => 'auth']);
$routes->get('cart/payment-success', 'Cart::payment_success', ['filter' => 'auth']);
$routes->get('cart/payment-cancel', 'Cart::payment_cancel', ['filter' => 'auth']);
$routes->get('cart/payment-authorize-test', 'Cart::payment_authorize_test', ['filter' => 'auth']);
$routes->post('cart/payment-mark-paid', 'Cart::mark_product_payment_paid', ['filter' => 'auth']);

// Notifications routes (user notifications management)
$routes->get('notifications', 'Notifications::index', ['filter' => 'auth']);
$routes->get('notifications/read/(:any)', 'Notifications::mark_as_read/$1', ['filter' => 'auth']);
$routes->post('notifications/read/(:any)', 'Notifications::mark_as_read/$1', ['filter' => 'auth']);
$routes->get('notifications/fetch_today', 'Notifications::fetch_today', ['filter' => 'auth']);
$routes->get('notifications/fetch_week', 'Notifications::fetch_week', ['filter' => 'auth']);
$routes->post('notifications/notify_seller/(:num)', 'Notifications::notify_seller_refund/$1', ['filter' => 'auth']);

// Seller refund process routes
$routes->get('orders/refund/process/(:num)', 'Orders::showRefundProcess/$1', ['filter' => 'auth:seller']);
$routes->post('orders/refund/process/(:num)', 'Orders::submitRefundProcess/$1', ['filter' => 'auth:seller']);