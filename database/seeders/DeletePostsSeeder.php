<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

class DeletePostsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("Deleting all posts...");
        
        // 방법 1: 트렁케이트 (더 빠름)
        DB::table('posts')->truncate();
        
        // 또는 방법 2: 모델을 통한 삭제
        // Post::query()->delete();
        
        $this->command->info("All posts have been deleted successfully!");
    }
} 