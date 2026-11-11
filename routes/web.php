<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{category}', [ProductController::class, 'category'])->name('category.show');
Route::get('/about', [ProductController::class, 'about'])->name('about');
Route::get('/privacy-policy', [ProductController::class, 'privacyPolicy'])->name('privacy');
Route::get('/how-to-buy', [ProductController::class, 'howToBuy'])->name('how.to.buy');
Route::get('/payment', [ProductController::class, 'payment'])->name('payment');
Route::get('/returns-exchange', [ProductController::class, 'returnsExchange'])->name('returns.exchange');
Route::get('/delivery', [ProductController::class, 'delivery'])->name('delivery');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/order/confirm', [CartController::class, 'confirmOrder'])->name('order.confirm');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'open'])->name('notifications.open');
    Route::get('/orders/{order}', [NotificationController::class, 'order'])->name('orders.show');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/checkout/store', [CartController::class, 'confirmOrder'])->middleware('auth')->name('checkout.store');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/products', [\App\Http\Controllers\AdminCatalogController::class, 'index'])->name('products.index');
    Route::post('/products', [\App\Http\Controllers\AdminCatalogController::class, 'save'])->name('products.store');
    Route::put('/products/{id}', [\App\Http\Controllers\AdminCatalogController::class, 'save'])->name('products.update');
    Route::patch('/products/{id}/stock', [AdminController::class, 'updateStock'])->name('products.stock');
    Route::delete('/products/{id}', [\App\Http\Controllers\AdminCatalogController::class, 'destroy'])->name('products.destroy');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::patch('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    Route::get('/promotions', [AdminController::class, 'promotions'])->name('promotions.index');
    Route::post('/promotions', [AdminController::class, 'storePromotion'])->name('promotions.store');
    Route::put('/promotions/{promotion}', [AdminController::class, 'updatePromotion'])->name('promotions.update');
    Route::delete('/promotions/{promotion}', [AdminController::class, 'destroyPromotion'])->name('promotions.destroy');

    Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
});

Route::get('/shade-finder', fn() => redirect()->route('home')->with('success', 'Choose a product, then open its camera check below the shades.'))->name('shade.finder');
Route::post('/shade-finder', [\App\Http\Controllers\ShadeFinderController::class,'analyze'])->middleware('throttle:30,1')->name('shade.analyze');
Route::post('/shade-feedback', [\App\Http\Controllers\ShadeFinderController::class,'feedback'])->middleware('auth')->name('shade.feedback');
Route::delete('/shade-profile', [\App\Http\Controllers\ShadeFinderController::class,'clearProfile'])->middleware('auth')->name('shade.profile.delete');

Route::get('/orders/{order}/payment-proof', [NotificationController::class,'paymentProof'])->middleware('auth')->name('orders.payment-proof');


Route::get('/shade-check/{shade}', fn(\App\Models\Lipstick $shade) => redirect()->route('product.show', $shade->id));
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [
        \App\Http\Controllers\PasswordResetController::class,
        'requestForm',
    ])->name('password.request');

    Route::post('/forgot-password', [
        \App\Http\Controllers\PasswordResetController::class,
        'sendLink',
    ])->middleware('throttle:3,1')->name('password.email');

    Route::get('/reset-password/{token}', [
        \App\Http\Controllers\PasswordResetController::class,
        'resetForm',
    ])->name('password.reset');

    Route::post('/reset-password', [
        \App\Http\Controllers\PasswordResetController::class,
        'reset',
    ])->middleware('throttle:5,1')->name('password.update');
});