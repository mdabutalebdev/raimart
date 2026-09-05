<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/offers', [OfferController::class, 'index'])->name('offers');
Route::get('/track', [OrderTrackingController::class, 'index'])->name('track');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/page/{page:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots']);
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::post('/products/{product:slug}/reviews', [\App\Http\Controllers\ReviewController::class, 'productStore'])->name('products.reviews.store');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/partial', [CartController::class, 'partial'])->name('partial');
    Route::post('/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
});

Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/{product}', [WishlistController::class, 'toggle'])->name('toggle');
    Route::delete('/{product}', [WishlistController::class, 'remove'])->name('remove');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::post('/coupon', [CheckoutController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon', [CheckoutController::class, 'removeCoupon'])->name('coupon.remove');
    Route::post('/webhook', [CheckoutController::class, 'webhook'])->name('webhook');
    Route::get('/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('confirmation');
    Route::get('/{order}/pay', [CheckoutController::class, 'pay'])->name('pay');
    Route::get('/{order}/callback', [CheckoutController::class, 'callback'])->name('callback');
    Route::get('/{order}/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
});
