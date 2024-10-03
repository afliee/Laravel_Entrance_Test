<?php

use App\Lib\Helper\MapService;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

if (!function_exists('c')) {
    function c(string $key)
    {
        return App::make($key);
    }
}
