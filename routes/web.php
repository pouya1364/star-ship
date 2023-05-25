<?php

use Illuminate\Support\Facades\Route;

Route::get('/starship', [\App\Http\Controllers\StarShipController::class, 'starship']);
