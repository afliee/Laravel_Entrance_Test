<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DailyWeatherForecastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $weather;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->weather = $this->getWeatherData();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
            subject: 'Daily Weather Forecast Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_weather_forecast',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get weather data from Weather API.
     *
     * @return array
     */
    protected function getWeatherData(): array
    {
        $apiKey = env('WEATHER_API_KEY'); // Add your API key to .env
        $location = 'London'; // Replace with a dynamic location if needed

        // Make a GET request to the Weather API
        $response = Http::get(env('WEATHER_API_URL') . 'current.json', [
            'key' => $apiKey,
            'q' => $location,
            'aqi' => 'no', // Optional, set to 'no' to disable air quality info
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            $data = $response->json();

            return [
                'location' => $data['location']['name'],
                'temperature' => $data['current']['temp_c'] . ' °C', // Temperature in Celsius
                'condition' => $data['current']['condition']['text'], // Weather condition
                'icon' => $data['current']['condition']['icon'], // Weather icon URL
            ];
        } else {
            // Handle any errors or fallback values
            return [
                'location' => $location,
                'temperature' => 'N/A',
                'condition' => 'Unable to fetch weather data',
            ];
        }
    }
}
