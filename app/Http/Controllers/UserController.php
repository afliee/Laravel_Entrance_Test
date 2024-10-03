<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmationMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends ApiController
{

    protected function getService()
    {
        return c(UserService::class);
    }

    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Generate confirmation token
            $user->confirmation_token = Str::random(32);
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

    public function confirmSubscription($token)
    {
        // Find the user by confirmation token
        $user = User::where('confirmation_token', $token)->first();

        if ($user) {
            // Confirm subscription and reset the token
            $user->is_subscribed = true;
            $user->confirmation_token = null;
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
