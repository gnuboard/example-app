<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_memos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('target_user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();

            // 한 사용자가 특정 사용자에 대해 하나의 메모만 작성할 수 있도록
            $table->unique(['user_id', 'target_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_memos');
    }
};
