<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'board_id',
        'user_id',
        'title',
        'content',
        'attachment',
        'comments_count',
        'attachment_count',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(PostVote::class);
    }

    public function voters()
    {
        return $this->belongsToMany(User::class, 'post_votes')->withTimestamps()->withPivot('is_like');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function rootComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            $post->board()->increment('posts_count');
        });

        static::deleted(function ($post) {
            $post->board()->decrement('posts_count');
        });
    }
} 