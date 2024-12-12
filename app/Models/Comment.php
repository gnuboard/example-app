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
        'author',
        'content',
        'mentioned_author',
        'sort_order'
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

    // 부모 댓글 관계 정의
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id')->withTrashed();
    }

    // 답글 관계 정의
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->withTrashed();
    }

    // 답글이 있는지 확인하는 메서드
    public function hasReplies()
    {
        return $this->replies()->count() > 0;
    }
} 