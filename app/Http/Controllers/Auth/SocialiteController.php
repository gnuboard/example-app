<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

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
            
            // 기존 사용자 검색
            $existingUser = User::where('social_id', $socialUser->getId())
                ->where('social_type', $provider)
                ->first();

            if ($existingUser) {
                // 기존 사용자의 경우 이름을 제외한 나머지 정보만 업데이트
                $existingUser->update([
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                    'social_token' => $socialUser->token,
                    'social_refresh_token' => $socialUser->refreshToken,
                    'email_verified_at' => now(),
                ]);
                $user = $existingUser;
            } else {
                // 새로운 사용자 생성
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                    'social_id' => $socialUser->getId(),
                    'social_type' => $provider,
                    'social_token' => $socialUser->token,
                    'social_refresh_token' => $socialUser->refreshToken,
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user, true);
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('소셜 로그인 에러', [
                'provider' => $provider,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/login')->with('error', '소셜 로그인 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }
} 