<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');

Route::get('/shop', [StorefrontController::class, 'shop'])->name('products.index');
Route::get('/collection', [StorefrontController::class, 'collection'])->name('collection.index');
Route::get('/categories', [StorefrontController::class, 'categories'])->name('categories.index');
Route::get('/categories/{slug}', [StorefrontController::class, 'category'])->name('categories.show');
Route::view('/about', 'pages.catalog.about')->name('about');
Route::view('/contact', 'pages.catalog.contact')->name('contact');
Route::post('/contact', [StorefrontController::class, 'submitContact'])->name('contact.submit');
Route::get('/shop/{slug}', [StorefrontController::class, 'product'])->name('products.show');
Route::get('/reviews', [StorefrontController::class, 'reviews'])->name('reviews.index');
Route::get('/chat', [StorefrontController::class, 'chat'])->name('chat.index');
Route::post('/chat', [StorefrontController::class, 'submitChat'])->name('chat.submit');

Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart.index');
Route::post('/cart/items', [StorefrontController::class, 'addToCart'])->name('cart.items.store');
Route::patch('/cart/items/{slug}', [StorefrontController::class, 'updateCart'])->name('cart.items.update');
Route::delete('/cart/items/{slug}', [StorefrontController::class, 'removeFromCart'])->name('cart.items.destroy');
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [StorefrontController::class, 'placeOrder'])->name('checkout.store');
Route::get('/wishlist', [StorefrontController::class, 'wishlist'])->name('wishlist.index');
Route::post('/wishlist/toggle', [StorefrontController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::get('/profile', [StorefrontController::class, 'profile'])->name('profile.index');
Route::get('/orders', [StorefrontController::class, 'orders'])->name('orders.index');
Route::get('/orders/{number}', [StorefrontController::class, 'order'])->name('orders.show');
Route::get('/orders/{number}/payment', [StorefrontController::class, 'payment'])->name('payments.show');
Route::get('/addresses', [StorefrontController::class, 'addresses'])->name('addresses.index');
Route::post('/addresses', [StorefrontController::class, 'storeAddress'])->name('addresses.store');
Route::view('/help', 'pages.customer.help')->name('help.index');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
