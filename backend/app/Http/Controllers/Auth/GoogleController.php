<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
         Log::info('Redirect URI: ' . config('services.google.redirect'));
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Fetch the user's Google profile
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Log the Google user info for debugging (optional)
            \Log::info('Google User:', (array) $googleUser);

            // Check if the user exists in the database
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Create a new user if not found
                $user = User::create([
                    'user_role' => 'general',
                    'username' => rand(100000, 999999),
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'friends' => json_encode(array()),
                    'followers' => json_encode(array()),
                    'password' => bcrypt('dummy_password'),
                    'status' => 0,
                    'email_verified_at' => Carbon::now(),
                    "lastactive" => Carbon::now(),
                    'created_at' => time()
                ]);

                
            }

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Google Login Error: ' . $e->getMessage());

            // Redirect to login with an error message
            return redirect('/login')->withErrors(['error' => $e->getMessage()]);
        }
    }
}

