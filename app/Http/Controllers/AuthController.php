<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Services\WeatherService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends ApiController
{
    protected function getService()
    {
        return c(UserService::class);
    }
    public function redirectToGoogle() : RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {

            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
            if ($finduser) {

                Auth::login($finduser);
//                return redirect()->intended('home');

            } else {
                $newUser = $this->getService()->store($request, [
                    'user' => $user
                ]);

                if ($newUser['status']) {
                    Auth::login($newUser['user']);
                } else {
                    dd($newUser);
                }
            }

            return [
                'status' => 'success',
                'user' => Auth::user(),
            ];
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


}
