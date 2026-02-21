<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            \Illuminate\Support\Facades\Log::info('Google User retrieved', ['id' => $googleUser->id, 'email' => $googleUser->email]);

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                \Illuminate\Support\Facades\Log::info('User found by google_id', ['id' => $user->id]);
                Auth::login($user);
                return $user->role === 'admin' ? redirect()->intended('dashboard') : redirect('/');
            } else {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    \Illuminate\Support\Facades\Log::info('User found by email', ['id' => $user->id]);
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                    Auth::login($user);
                    return $user->role === 'admin' ? redirect()->intended('dashboard') : redirect('/');
                }

                \Illuminate\Support\Facades\Log::info('Creating new user');
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'user', // Default role
                    'password' => null,
                    'email_verified_at' => now(),
                ]);

                Auth::login($newUser);
                return redirect('/'); // New users are always 'user'
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong with Google Login. Please try again.');
        }
    }
}
