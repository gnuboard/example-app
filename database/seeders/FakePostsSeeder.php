<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Board;
use App\Models\User;
use Faker\Factory as Faker;

class FakePostsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ko_KR');
        
        // 게시판 확인
        $board = Board::first();
        if (!$board) {
            $this->command->error('게시판이 없습니다. 먼저 게시판을 생성해주세요.');
            return;
        }

        // 기존 사용자들 가져오기
        $users = User::all();
        if ($users->count() == 0) {
            $this->command->error('등록된 사용자가 없습니다.');
            return;
        }

        $this->command->info("총 {$users->count()}명의 사용자를 찾았습니다.");
        $this->command->info('가짜 게시물 생성 시작...');
        $bar = $this->command->getOutput()->createProgressBar(1000);

        for ($i = 0; $i < 1000; $i++) {
            // 한글 제목 생성
            $title = $faker->randomElement([
                $faker->realText(20),
                $faker->realText(30)
            ]);

            // 한글 본문 생성
            $paragraphs = [];
            $paragraphCount = rand(3, 7);
            for ($j = 0; $j < $paragraphCount; $j++) {
                $paragraphs[] = $faker->realText(200);
            }
            $content = implode("\n\n", $paragraphs);

            // 기존 사용자 중에서 랜덤 선택
            $user = $users->random();
            
            Post::create([
                'board_id' => $board->id,
                'user_id' => $user->id,
                'author' => $user->name,
                'title' => $title,
                'content' => $content,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'view_count' => $faker->numberBetween(0, 1000)
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->command->info("\n1000개의 가짜 게시물이 생성되었습니다.");
    }
} 