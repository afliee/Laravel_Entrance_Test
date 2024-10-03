<?php

namespace App\Jobs;

use App\Mail\DailyWeatherForecastMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyWeatherEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        try {
            $subscribers = User::where('is_subscribed', true)->get();

            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)->send(new DailyWeatherForecastMail($subscriber));
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Email not sent: ' . $e->getMessage());
        }
    }
}
