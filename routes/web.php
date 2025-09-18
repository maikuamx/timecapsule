<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimeCapsuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TimeCapsuleController::class, 'index'])->name('dashboard');

    Route::get('/capsules/create', [TimeCapsuleController::class, 'create'])->name('capsules.create');
    Route::post('/capsules', [TimeCapsuleController::class, 'store'])->name('capsules.store');
    Route::get('/capsules/{timeCapsule}', [TimeCapsuleController::class, 'show'])->name('capsules.show');
    Route::delete('/capsules/{timeCapsule}', [TimeCapsuleController::class, 'destroy'])->name('capsules.destroy');
});
