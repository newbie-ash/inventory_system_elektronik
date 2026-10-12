<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (if authenticated) or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Laporan (Admin & Staff)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/realtime-data', [DashboardController::class, 'realtimeData'])->name('dashboard.realtime');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Stock Transactions (Admin & Staff)
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
    Route::get('/stock-movements/{stock_movement}', [StockMovementController::class, 'show'])->name('stock-movements.show');

    // Admin-only Product Modifications & Management
    Route::middleware('role:admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->whereNumber('product')->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->whereNumber('product')->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->whereNumber('product')->name('products.destroy');

        // Master Data CRUD (Admin Only)
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('users', UserController::class);
    });

    // Product Viewing (Admin & Staff)
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->whereNumber('product')->name('products.show');
});
