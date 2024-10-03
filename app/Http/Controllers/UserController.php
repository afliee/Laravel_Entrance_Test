<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmationMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends ApiController
{

    protected function getService()
    {
        return c(UserService::class);
    }

    public function subscribe(Request $request): mixed
    {
        $validate = $this->getService()->validate($request, [
            'email' => 'required|email',
            'subscribe_location' => 'required|string'
        ]);

        if ($validate) {
            return $validate;
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Generate confirmation token
            $token = Str::random(32) . '?subscribe_location=' . $request->get('subscribe_location');
            $user->confirmation_token = $token;
            $user->save();

            // Send confirmation email with the token
            Mail::to($user->email)->send(new SubscriptionConfirmationMail($user));

            return response()->json(['message' => 'Please check your email to confirm your subscription.']);
        }

        return response()->json(['message' => 'User not found.'], 404);
//        return $this->getService()->update($request, [
//            'rules' => [
//                'id' => 'required|integer',
//                'is_subscribed' => 'required|boolean'
//            ]
//        ]);
    }

    public function confirmSubscription(Request $request, $token)
    {
        $location = $request->get('subscribe_location');
        // Find the user by confirmation token
        $user = User::where(
            'confirmation_token', $token . '?subscribe_location=' . $location
        )->first();

        if ($user) {
            // Confirm subscription and reset the token
            $user->is_subscribed = true;
            $user->confirmation_token = null;
            $user->subscribe_location = $location;
            $user->save();

            return response()->json(['message' => 'Subscription confirmed successfully!']);
        }

        return response()->json(['message' => 'Invalid token or already confirmed.'], 404);
    }


    public function unsubscribe(Request $request)
    {
        // Validate that the email is provided
        $validate = $this->getService()->validate($request, [
            'email' => 'required|email'
        ]);

        if ($validate) {
            return response()->json(['message' => 'Email is required.'], 400);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Check if user is already unsubscribed
        if (!$user->is_subscribed) {
            return response()->json(['message' => 'User is already unsubscribed.']);
        }

        // Set is_subscribed to false to unsubscribe the user
        $user->is_subscribed = false;
        $user->save();

        // Optionally send an email confirming the unsubscription
        // Mail::to($user->email)->send(new \App\Mail\UnsubscriptionConfirmationMail());

        return response()->json(['message' => 'You have successfully unsubscribed from the daily weather forecast.']);
    }
}
