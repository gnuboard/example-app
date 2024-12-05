<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boards_like', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['board_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boards_like');
    }
}; 