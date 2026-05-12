<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('games.index');
