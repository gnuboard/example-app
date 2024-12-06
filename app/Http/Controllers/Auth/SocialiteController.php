<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'github', 'kakao'])) {
            return redirect()->route('login')
                ->with('error', '지원하지 않는 소셜 로그인입니다.');
        }

        // if ($provider === 'kakao') {
        //     return Socialite::driver($provider)
        //         ->scopes(['account_email'])  // 이메일 정보 요청
        //         ->redirect();
        // }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            
            // 디버깅을 위한 상세 로그
            \Log::debug('Kakao User Raw Data:', [
                'id' => $user->getId(),           // 카카오 고유 ID
                'nickname' => $user->getNickname(), // 카카오 닉네임
                'name' => $user->getName(),       // 이름 (설정된 경우)
                'email' => $user->getEmail(),     // 이메일
                'avatar' => $user->getAvatar(),   // 프로필 이미지
                'token' => $user->token,          // 액세스 토큰
                'refreshToken' => $user->refreshToken, // 리프레시 토큰
                'expiresIn' => $user->expiresIn,  // 토큰 만료 시간
                'raw' => $user->getRaw(),         // 원본 데이터 전체
            ]);

            $name = $user->getName() ?: $user->getNickname();
            $authUser = $this->findOrCreateUser($user, $provider);
            Auth::login($authUser, true);
            return redirect()->route('dashboard');
        } catch (InvalidStateException $e) {
            return redirect()->route('login')
                ->with('error', '소셜 로그인 처리 중 오류가 발생했습니다.');
        } catch (\Exception $e) {
            \Log::error('Kakao Login Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', '소셜 로그인 처리 중 오류가 발생했습니다.');
        }
    }

    private function findOrCreateUser($socialUser, $provider)
    {
        try {
            // 소셜 ID로 기존 사용자 검색
            $user = User::where('social_id', $socialUser->getId())
                ->where('social_type', $provider)
                ->first();

            // 소셜 ID로 찾지 못한 경우 이메일로 검색
            if (!$user) {
                $user = User::where('email', $socialUser->getEmail())->first();
                
                // 이메일로 찾은 경우 소셜 정보 업데이트
                if ($user) {
                    $user->update([
                        'social_id' => $socialUser->getId(),
                        'social_type' => $provider,
                        'social_token' => $socialUser->token,
                        'social_refresh_token' => $socialUser->refreshToken,
                    ]);
                    return $user;
                }
            }

            if ($user) {
                // 기존 사용자의 경우 이름을 제외한 나머지 정보만 업데이트
                $user->update([
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                    'social_token' => $socialUser->token,
                    'social_refresh_token' => $socialUser->refreshToken,
                    'email_verified_at' => now(),
                ]);
                return $user;
            } else {

                if ($provider === 'kakao') {
                    // 카카오 로그인의 경우 이름이나 이메일이 없을 수 있음
                    $name = $socialUser->getName() ?? $socialUser->getNickname() ?? '카카오사용자_'.time();
                    $email = $socialUser->getEmail() ?? $socialUser->getId() . '@kakao.com';
                } else {
                    // 다른 소셜 로그인의 경우 기본값 사용
                    $name = $socialUser->getName() ?? $socialUser->getNickname() ?? '사용자_'.time();
                    $email = $socialUser->getEmail();
                }

                // 새로운 사용자 생성
                return User::create([
                    'name' => $name,
                    'email' => $email,
                    'avatar' => $socialUser->getAvatar(),
                    'social_id' => $socialUser->getId(),
                    'social_type' => $provider,
                    'social_token' => $socialUser->token,
                    'social_refresh_token' => $socialUser->refreshToken,
                    'email_verified_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
} 