<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // 1. uuid 컬럼이 없을 경우에만 추가
        if (!Schema::hasColumn('users', 'uuid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('uuid', 36)->nullable();
            });
        }

        // 2. 기존 사용자들에게 UUID 할당
        DB::table('users')->orderBy('id')->each(function ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });

        // 3. unique 제약조건 추가 (수정된 부분)
        Schema::table('users', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // 인덱스가 존재하는지 확인
            if (Schema::hasIndex('users', 'users_uuid_unique')) {
                $table->dropUnique('users_uuid_unique');
            }
            
            // 컬럼이 존재하는지 확인
            if (Schema::hasColumn('users', 'uuid')) {
                $table->dropColumn('uuid');
            }
        });
    }
};
