<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class FacebookController extends Controller
{
    /**
     * Redirect to Facebook for authentication.
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback.
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // Check if the user exists in the database
            $user = User::where('email', $facebookUser->email)->first();

            //print_r($user);exit;

            if (!$user) {
                // Create a new user if not found
                $user = User::create([
                    'user_role' => 'general',
                    'username' => rand(100000, 999999),
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'friends' => json_encode(array()),
                    'followers' => json_encode(array()),
                    'password' => bcrypt('dummy_password'),
                    'status' => 0,
                    'email_verified_at' => Carbon::now(),
                    'lastActive' => Carbon::now(),
                    'created_at' => time()
                ]);

                
            }

             event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            //return redirect('/login')->withErrors('Something went wrong!');
        }
    }
}

