<?php

return [
    'user_levels' => [
        'guest' => 0,      // 방문객
        'unverified' => 1,   // 인증 전 회원 (이메일 회원가입후 이메일 인증전)
        'verified' => 2,     // 인증된 회원 (이메일 인증후, 소셜로그인 회원)
    ],
];