<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMemo extends Model
{
    protected $fillable = ['user_id', 'target_user_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
} 