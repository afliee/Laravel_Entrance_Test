<p>Hello {{ $user->name }},</p>

<p>Thank you for subscribing to our daily weather forecast. To confirm your subscription, please click the link below:</p>

<a href="{{ url('/confirm-subscription/' . $user->confirmation_token) }}">Confirm Subscription</a>

<p>If you did not request this subscription, you can ignore this email.</p>

<p>Thank you!</p>
