<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'post_id',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
} 