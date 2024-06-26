<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReservationController;

Route::apiResource('users', UserController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('reservations', ReservationController::class);