<p>Hello {{ $user->name }},</p>

<p>Here is your daily weather forecast:</p>

<ul>
    <li>Location: {{ $weather['location'] }}</li>
    <li>Temperature: {{ $weather['temperature'] }}</li>
    <li>Condition: {{ $weather['condition'] }}</li>
</ul>

<p>
    <img src="https:{{ $weather['icon'] }}" alt="Weather icon">
</p>

<p>Have a great day!</p>
