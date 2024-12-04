<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::updateOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'name' => $socialUser->getName(),
                'social_id' => $socialUser->getId(),
                'social_type' => $provider,
                'avatar' => $socialUser->getAvatar(),
                'social_token' => $socialUser->token,
                'social_refresh_token' => $socialUser->refreshToken,
                'email_verified_at' => now(),
            ]);

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', '소셜 로그인 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }
} 