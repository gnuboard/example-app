<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class FakeUsersSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ko_KR');
        
        $this->command->info('가짜 사용자 생성 시작...');
        $bar = $this->command->getOutput()->createProgressBar(1000);

        // 소셜 로그인 타입 배열
        $socialTypes = ['google', 'github', 'kakao', 'naver'];
        
        // 사용자 레벨 설정
        $userLevels = [
            config('constants.user_levels.unverified'),
            config('constants.user_levels.verified')
        ];

        // 1000명의 가짜 사용자 생성
        for ($i = 0; $i < 1000; $i++) {
            $isSocialUser = $faker->boolean(30); // 30% 확률로 소셜 로그인 사용자
            $isVerified = $faker->boolean(80);   // 80% 확률로 이메일 인증된 사용자
            
            $userData = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => $isVerified ? now() : null,
                'password' => bcrypt('password'), // 모든 사용자의 기본 비밀번호는 'password'
                'remember_token' => Str::random(10),
                'level' => $isVerified ? config('constants.user_levels.verified') : config('constants.user_levels.unverified'),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ];

            // 소셜 로그인 사용자인 경우 추가 정보 설정
            if ($isSocialUser) {
                $socialType = $faker->randomElement($socialTypes);
                $userData = array_merge($userData, [
                    'social_id' => $faker->uuid,
                    'social_type' => $socialType,
                    'social_token' => Str::random(40),
                    'social_refresh_token' => Str::random(40),
                    'avatar' => $faker->imageUrl(200, 200, 'people'),
                    'email_verified_at' => now(), // 소셜 로그인 사용자는 항상 인증됨
                    'level' => config('constants.user_levels.verified')
                ]);
            }

            User::create($userData);
            $bar->advance();
        }

        $bar->finish();
        $this->command->info("\n1000명의 가짜 사용자가 생성되었습니다.");
    }
} 