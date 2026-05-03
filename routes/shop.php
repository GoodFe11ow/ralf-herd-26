<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

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
