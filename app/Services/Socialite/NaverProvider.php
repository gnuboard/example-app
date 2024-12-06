<?php

namespace App\Services\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class NaverProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = [];
    protected $encoded = false;

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://nid.naver.com/oauth2.0/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://nid.naver.com/oauth2.0/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://openapi.naver.com/v1/nid/me', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        $response = $user['response'];
        
        return (new User)->setRaw($user)->map([
            'id' => $response['id'],
            'nickname' => $response['nickname'],
            'name' => $response['name'],
            'email' => $response['email'],
            'avatar' => $response['profile_image'],
        ]);
    }
} 