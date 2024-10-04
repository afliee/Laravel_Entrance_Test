<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WeatherService extends ApiService
{
    public function show(Request $request)
    {
        $validate = $this->validate($request, [
            'q' => 'required|string',
            'days' => 'nullable|integer'
        ]);

        if (isset($validate)) {
            return $validate;
        }
        // Define the cache key and set duration for 1 day (1440 minutes)
        $cacheKey = 'weather_' . $request->get('q');
        $cacheDuration = 60 * 60 * 24; // 1 day

        // Check if the weather data is already cached
        $weatherData = Cache::get($cacheKey);

        if ($weatherData) {
            return [
                'status' => true,
                'cached' => true,
                'forecast' => $weatherData
            ];
        }

        $responses = Http::get(env('WEATHER_API_URL') . 'forecast.json',[
            'key' => env('WEATHER_API_KEY'),
            'q' => $request->get('q'),
            'days' => $request->get('days') ?? 4,
            'aqi' => 'no',
        ]);

        if ($responses->failed()) {
            return [
                'status' => false,
                'message' => 'Failed to get weather data'
            ];
        }
        Cache::put($cacheKey, $responses->json(), $cacheDuration);
        return [
            'status' => true,
            'forecast' => $responses->json()
        ];
    }

    public function store(Request $request, array $options = [])
    {
        // TODO: Implement store() method.
    }

    public function update(Request $request, array $options = [])
    {
        // TODO: Implement update() method.
    }

    public function delete(Request $request)
    {
        // TODO: Implement delete() method.
    }
}
