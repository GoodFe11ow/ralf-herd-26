<?php

use App\Http\Controllers\MarkerController;
use Illuminate\Support\Facades\Route;

Route::resource('markers', MarkerController::class)
    ->middleware(['auth', 'verified']);