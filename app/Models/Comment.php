<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
        'mentioned_user_name',
    ];

    // 댓글 작성자
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 원글
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // // 부모 댓글
    // public function parent()
    // {
    //     return $this->belongsTo(Comment::class, 'parent_id');
    // }

    // // 대댓글들
    // public function replies()
    // {
    //     return $this->hasMany(Comment::class, 'parent_id')->whereNull('deleted_at');
    // }
} 