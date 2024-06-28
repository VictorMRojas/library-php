<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [BookController::class, 'availableBooks'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/available-books', [BookController::class, 'availableBooks'])->name('available-books');    
Route::get('/api/books', [BookController::class, 'getBooks']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('reservation', ReservationController::class);
});

Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');

require __DIR__.'/auth.php';