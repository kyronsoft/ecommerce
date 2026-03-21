<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\EpaycoController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('store.home');
Route::get('/shop', [HomeController::class, 'shop'])->name('store.shop');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('store.product.show');

Route::prefix('wishlist')->name('store.wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add/{product}', [WishlistController::class, 'add'])->name('add');
    Route::delete('/remove/{product}', [WishlistController::class, 'remove'])->name('remove');
});

Route::prefix('cart')->name('store.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('store.checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('store.checkout.store');

Route::get('/payments/epayco/checkout', [EpaycoController::class, 'checkout'])->name('epayco.checkout');
Route::match(['get', 'post'], '/payments/epayco/response', [EpaycoController::class, 'response'])->name('epayco.response');
Route::match(['get', 'post'], '/payments/epayco/confirmation', [EpaycoController::class, 'confirmation'])->name('epayco.confirmation');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'edit', 'update']);
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show']);
});
