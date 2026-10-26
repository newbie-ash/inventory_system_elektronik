<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Customer\ActivityController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\PromoController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoBannerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Landing Page (REDMAGIC Gaming Style)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/product/{product}', [LandingController::class, 'previewProduct'])->name('product.preview');

// Midtrans Public Webhook Notification
Route::post('/payments/midtrans-notification', [PaymentController::class, 'notification'])->name('payments.midtrans-notification');

// Guest Authentication
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

    // Forgot & Reset Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Social SSO Login Simulation
    Route::get('/auth/{provider}', [AuthController::class, 'socialLogin'])->name('auth.social');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Real-Time Analytics
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/realtime-data', [DashboardController::class, 'realtimeData'])->name('dashboard.realtime');

    // Account Profile & Settings (All Authenticated Roles)
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('customer.settings');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('customer.settings.update');
    Route::get('/settings/contact', [ProfileController::class, 'contact'])->name('settings.contact');
    Route::put('/settings/contact', [ProfileController::class, 'updateContact'])->name('settings.contact.update');
    Route::get('/settings/security', [ProfileController::class, 'security'])->name('settings.security');
    Route::put('/settings/security', [ProfileController::class, 'updateSecurity'])->name('settings.security.update');
    Route::get('/settings/preferences', [ProfileController::class, 'preferences'])->name('settings.preferences');
    Route::put('/settings/preferences', [ProfileController::class, 'updatePreferences'])->name('settings.preferences.update');

    // --- PEMBELI (CUSTOMER) ROUTES ---
    Route::middleware('role:pembeli')->group(function () {
        Route::get('/catalog', [CatalogController::class, 'index'])->name('customer.catalog');
        Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('customer.catalog.show');
        Route::get('/promo', [PromoController::class, 'index'])->name('customer.promo');

        // Shopping Cart & Promo
        Route::get('/cart', [CartController::class, 'index'])->name('customer.cart');
        Route::post('/cart/add', [CartController::class, 'add'])->name('customer.cart.add');
        Route::patch('/cart/update', [CartController::class, 'update'])->name('customer.cart.update');
        Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('customer.cart.remove');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('customer.cart.clear');
        Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('customer.cart.apply-promo');
        Route::delete('/cart/promo', [CartController::class, 'removePromo'])->name('customer.cart.remove-promo');
        Route::post('/cart/reorder/{sale}', [CartController::class, 'reorder'])->name('customer.cart.reorder');

        // Checkout & Dedicated Payment Page
        Route::post('/cart/checkout', [OrderController::class, 'store'])->name('customer.checkout');
        Route::get('/orders/{sale}/payment', [OrderController::class, 'payment'])->name('customer.orders.payment');
        Route::post('/orders/{sale}/simulate-payment', [OrderController::class, 'simulatePayment'])->name('customer.orders.simulate-payment');
        Route::post('/orders/{sale}/switch-to-cod', [OrderController::class, 'switchToCod'])->name('customer.orders.payment-switch-to-cod');
        Route::post('/orders/{sale}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.payment-cancel');
        Route::post('/orders/{sale}/apply-promo', [OrderController::class, 'applyPromo'])->name('customer.orders.apply-promo');

        // Pesanan Saya (Shopee-Style Status Tabs: Belum Bayar, Dikemas, Dikirim, Selesai)
        Route::get('/my-orders', [OrderController::class, 'index'])->name('customer.orders.index');
        Route::get('/my-orders/{sale}', [OrderController::class, 'show'])->name('customer.orders.show');
        Route::post('/my-orders/{sale}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
        Route::post('/my-orders/{sale}/switch-to-cod', [OrderController::class, 'switchToCod'])->name('customer.orders.switch-to-cod');
        Route::post('/my-orders/{sale}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('customer.orders.confirm-delivery');
        Route::post('/my-orders/{sale}/advance-shipping', [OrderController::class, 'advanceShipping'])->name('customer.orders.advance-shipping');
        Route::post('/my-orders/{sale}/simulate-payment', [OrderController::class, 'simulatePayment'])->name('customer.orders.legacy-simulate-payment');

        // Customer Reviews & Ratings
        Route::post('/reviews', [ReviewController::class, 'store'])->name('customer.reviews.store');

        // Riwayat Aktivitas (Tokopedia-Style Browsing / Viewed History)
        Route::get('/activity', [ActivityController::class, 'index'])->name('customer.activity');
        Route::delete('/activity/{activity}', [ActivityController::class, 'destroy'])->name('customer.activity.destroy');
        Route::delete('/activity', [ActivityController::class, 'clear'])->name('customer.activity.clear');
    });

    // --- STAFF & ADMIN (OPERATIONAL) ROUTES ---
    Route::middleware('role:admin,staff')->group(function () {
        // Purchases (Stock-In)
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

        // Sales (Stock-Out / Kasir & Fulfillment)
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::patch('/sales/{sale}/shipping-status', [SaleController::class, 'updateShippingStatus'])->name('sales.update-shipping-status');

        // Products (Read-Only)
        Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->whereNumber('product')->name('products.show');

        // Stock Movement Audit Log
        Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
        Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
        Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
        Route::get('/stock-movements/{stock_movement}', [StockMovementController::class, 'show'])->name('stock-movements.show');
    });

    // --- ADMIN ONLY ROUTES ---
    Route::middleware('role:admin')->group(function () {
        // Master Data CRUD
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('users', UserController::class);
        Route::resource('banners', PromoBannerController::class);

        // Product CRUD (Create, Edit, Delete)
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->whereNumber('product')->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->whereNumber('product')->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->whereNumber('product')->name('products.destroy');

        // Transaction Cancellations & Inventory Reversals
        Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
        Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
