<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;


 Route::resource('posts', PostController::class)->middleware(['auth', 'verified']);

 Route::post('posts/{post}/comments', [CommentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('comments.store');

Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('comments.destroy');
