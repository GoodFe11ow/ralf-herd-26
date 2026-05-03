<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/shop', [ProductController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('shop.index');