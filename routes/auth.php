<?php

use App\Http\Controllers\Auth\SignUpController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/signup', [SignUpController::class, 'create'])->name('signup');
    Route::post('/signup', [SignUpController::class, 'store']);
    
    Route::get('/login')->name('login');
});