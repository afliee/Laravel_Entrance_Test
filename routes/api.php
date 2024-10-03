<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
Route::get('/', static function () {
    return [
        'status' => false,
        'message' => 'Not this route... try the below route',
        'data' => [
            'url' => URL::to('/api')
        ],
    ];
});


// prefix weather
Route::group(['prefix' => 'weather'], static function () {
    Route::get('/', [WeatherController::class, 'show']);
    Route::get('/cached/{city}', [WeatherController::class, 'showCachedWeather']);
//    Route::get('/{city}', [WeatherController::class, 'getWeather']);
//    Route::get('/{city}/forecast', [WeatherController::class, 'getForecast']);
//    Route::get('/{city}', [WeatherController::class, 'getWeather']);
//    Route::get('/{city}/forecast', [WeatherController::class, 'getForecast']);
});



