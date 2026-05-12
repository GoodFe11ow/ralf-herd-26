<?php

use App\Http\Controllers\GameApiController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameApiController::class, 'index']);