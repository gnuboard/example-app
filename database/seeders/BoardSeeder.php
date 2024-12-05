<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Board;

class BoardSeeder extends Seeder
{
    public function run(): void
    {
        Board::create([
            'name' => 'free',
            'title' => '자유게시판',
            'description' => '자유롭게 이야기를 나누는 공간입니다.',
        ]);

        Board::create([
            'name' => 'notice',
            'title' => '공지사항',
            'description' => '중요한 공지사항을 확인하세요.',
        ]);
    }
} 