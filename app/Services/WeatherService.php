<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WeatherService extends ApiService
{
    public string $cacheTag = 'weather';
    public function show(Request $request)
    {
        $data = $request->all();
        $rules = [
            'days' => 'nullable|integer',
        ];
//        check if in data consist of q then not require lat and lon and if not then require lat and lon
        if (isset($data['q'])) {
            $rules['q'] = 'required|string';
        } else {
            $rules['lat'] = 'required|numeric';
            $rules['lon'] = 'required|numeric';
        }
        $validate = $this->validate($request, $rules);

        if (isset($validate)) {
            return $validate;
        }
        // Define the cache key and set duration for 1 day (1440 minutes)
        $cacheKey = 'weather_' . $request->get('q');
        $cacheDuration = 60 * 60 * 24; // 1 day
//
//        // Check if the weather data is already cached
//        $weatherData = Cache::get($cacheKey);
//
//        if ($weatherData) {
//            return [
//                'status' => true,
//                'cached' => true,
//                'forecast' => $weatherData
//            ];
//        }

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
//        online cache location is q in request
        $this->storeCacheWithTags(
            $cacheKey,
            $request->get('q'),
            [$this->cacheTag],
            $cacheDuration
        );
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

    public function storeCacheWithTags($key, $value, $tags = [], $ttl = 60)
    {
        // Store the actual cache value
        Cache::put($key, $value, $ttl);

        // Loop through each tag and store the key in a separate cache list for that tag
        foreach ($tags as $tag) {
            // Create a unique key for each tag
            $taggedKeys = Cache::get("tag_{$tag}_keys", []);
            if (!in_array($key, $taggedKeys)) {
                $taggedKeys[] = $key;
                Cache::put("tag_{$tag}_keys", $taggedKeys, $ttl);
            }
        }
    }

    public function getCacheByTag($tag)
    {
        // Retrieve the list of keys associated with the tag
        $taggedKeys = Cache::get("tag_{$tag}_keys", []);

        $cachedValues = [];

        // Loop through each key and retrieve the cached value
        foreach ($taggedKeys as $key) {
            $value = Cache::get($key);
            if ($value !== null) {
                $cachedValues[$key] = $value;
            }
        }

        return $cachedValues; // Return all cached values associated with the tag
    }


}
