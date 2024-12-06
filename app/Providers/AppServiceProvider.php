<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Kakao\KakaoExtendSocialite;
use Laravel\Socialite\Facades\Socialite;
use App\Services\Socialite\NaverProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, [KakaoExtendSocialite::class, 'handle']);

        Socialite::extend('naver', function ($app) {
            $config = $app['config']['services.naver'];
            return new NaverProvider(
                $app['request'], 
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }
}
