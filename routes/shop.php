<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/shop', [ProductController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('shop.index');

Route::get('/cart', [CartController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('cart.index');

Route::post('/cart', [CartController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('cart.store');
    
Route::patch('/cart/{productId}', [CartController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('cart.update');

Route::delete('/cart/{productId}', [CartController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.store');

Route::get('/checkout/{order}/pay', [CheckoutController::class, 'pay'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.pay');

Route::get('/checkout/{order}/success', [CheckoutController::class, 'success'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.success');

Route::get('/checkout/{order}/cancel', [CheckoutController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('checkout.cancel');

