<?php

use App\Jobs\SendDailyWeatherEmails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: '/api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // verifyCsrfToken by class
        $middleware->validateCsrfTokens(except: [
            '/subscribe',
            '/unsubscribe',
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new SendDailyWeatherEmails)->daily()->at('08:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
