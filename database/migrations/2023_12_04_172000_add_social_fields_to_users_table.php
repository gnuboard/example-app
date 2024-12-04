<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('social_id')->nullable()->after('id');
            $table->string('social_type')->nullable()->after('social_id');
            $table->string('avatar')->nullable()->after('email');
            $table->string('social_token')->nullable()->after('avatar');
            $table->string('social_refresh_token')->nullable()->after('social_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'social_id',
                'social_type',
                'avatar',
                'social_token',
                'social_refresh_token'
            ]);
        });
    }
}; 