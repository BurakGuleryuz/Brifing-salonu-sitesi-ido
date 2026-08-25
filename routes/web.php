<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\SignageController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [LoginController::class, 'verifyForgotPassword'])->name('password.forgot.submit');
Route::get('/reset-password', [LoginController::class, 'showResetPasswordForm'])->name('password.reset-form');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.reset');

Route::middleware('auth.simple')->group(function () {
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/{room}/mark-faulty', [RoomController::class, 'markFaulty'])->name('rooms.mark-faulty');
    Route::post('/rooms/{room}/clear-faulty', [RoomController::class, 'clearFaulty'])->name('rooms.clear-faulty');
    Route::resource('meetings', MeetingController::class);
});

// Signage ekranı bilinçli olarak korumasız - TV'de şifresiz açık kalması gerekiyor
Route::get('/signage/{room}', [SignageController::class, 'show'])->name('signage.show');

Route::get('/', function () {
    return redirect()->route('rooms.index');
});