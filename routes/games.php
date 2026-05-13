<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('games.index');

Route::get('/games/movies', [GameController::class, 'movies'])
    ->middleware(['auth', 'verified'])
    ->name('games.movies');

Route::get('/games/create', [GameController::class, 'create'])
->middleware(['auth', 'verified'])
->name('games.create');

Route::post('/games', [GameController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('games.store');

Route::get('/games/{game}/edit', [GameController::class, 'edit'])
->middleware(['auth', 'verified'])
->name('games.edit');

Route::put('/games/{game}', [GameController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('games.update');

Route::delete('/games/{game}', [GameController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('games.destroy');
