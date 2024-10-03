<?php
namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class WeatherController extends ApiController
{

    protected function getService()
    {
        return c(WeatherService::class);
    }

    public function showCachedWeather($location)
    {
        $cacheKey = 'weather_' . $location;

        // Retrieve the cached weather data
        $weatherData = Cache::get($cacheKey);

        if ($weatherData) {
            return response()->json($weatherData);
        } else {
            return response()->json(['message' => 'No cached weather data available'], 404);
        }
    }

}
