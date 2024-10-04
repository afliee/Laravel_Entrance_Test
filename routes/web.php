<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use App\Mail\DailyWeatherForecastMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'status' => false,
        'message' => 'Not this route...',
    ];
});

Route::get('/weather', [WeatherController::class, 'show']);
Route::controller(AuthController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::post('/subscribe', [UserController::class, 'subscribe']);
Route::post('/unsubscribe', [UserController::class, 'unsubscribe']);
Route::get('/confirm-subscription/{token}', [UserController::class, 'confirmSubscription']);
Route::get('/error', function () {
    return [
        'status' => false,
        'message' => 'An error occurred...',
    ];
});
