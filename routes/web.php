<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Public Storefront Catalog
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'shop'])->name('products.index');
Route::get('/shop/{slug}', [CatalogController::class, 'product'])->name('products.show');
Route::get('/collection', [CatalogController::class, 'collection'])->name('collection.index');
Route::get('/categories', [CatalogController::class, 'categories'])->name('categories.index');
Route::get('/categories/{slug}', [CatalogController::class, 'category'])->name('categories.show');
Route::view('/about', 'pages.catalog.about')->name('about');
Route::view('/contact', 'pages.catalog.contact')->name('contact');
Route::post('/contact', [ContactController::class, 'submitContact'])->name('contact.submit');
Route::get('/reviews', [CatalogController::class, 'reviews'])->name('reviews.index');
Route::get('/chat', [ChatController::class, 'chat'])->name('chat.index');
Route::post('/chat', [ChatController::class, 'submitChat'])->name('chat.submit');
Route::view('/help', 'pages.customer.help')->name('help.index');

// Cart Flow (supporting both /cart and /cart/items paths)
Route::get('/cart', [CartController::class, 'cart'])->name('cart.index');
Route::post('/cart', [CartController::class, 'addToCart']);
Route::post('/cart/items', [CartController::class, 'addToCart'])->name('cart.items.store');
Route::patch('/cart/{slug}', [CartController::class, 'updateCart']);
Route::patch('/cart/items/{slug}', [CartController::class, 'updateCart'])->name('cart.items.update');
Route::delete('/cart/{slug}', [CartController::class, 'removeFromCart']);
Route::delete('/cart/items/{slug}', [CartController::class, 'removeFromCart'])->name('cart.items.destroy');

// Authenticated Customer Flow
Route::middleware('auth')->group(function (): void {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.store');
    Route::post('/checkout/voucher', [CheckoutController::class, 'applyVoucher'])->name('checkout.voucher.apply');
    Route::delete('/checkout/voucher', [CheckoutController::class, 'removeVoucher'])->name('checkout.voucher.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'wishlist'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'toggleWishlist'])->name('wishlist.store');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::post('/wishlist/{slug}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

    // Reviews Submission
    Route::post('/reviews', [CatalogController::class, 'storeReview'])->name('reviews.store');

    // Profile & Addresses
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses.index');
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::post('/addresses/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('addresses.default');

    // Orders & Payments
    Route::get('/orders', [OrderController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{number}', [OrderController::class, 'order'])->name('orders.show');
    Route::get('/orders/{number}/payment', [OrderController::class, 'payment'])->name('payments.show');
    Route::post('/orders/{number}/payment/confirm', [OrderController::class, 'confirmPayment'])->name('payments.confirm');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
