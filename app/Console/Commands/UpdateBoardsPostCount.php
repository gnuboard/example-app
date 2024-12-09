<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Board;

class UpdateBoardsPostCount extends Command
{
    protected $signature = 'boards:update-posts-count';
    protected $description = 'Update posts count for all boards';

    public function handle()
    {
        Board::chunk(100, function ($boards) {
            foreach ($boards as $board) {
                $board->posts_count = $board->posts()->count();
                $board->save();
            }
        });

        $this->info('모든 게시판의 게시글 수가 업데이트되었습니다.');
    }
} 