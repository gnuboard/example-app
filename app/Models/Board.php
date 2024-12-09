<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'identifier',
        'category',
        'list_level',
        'read_level',
        'write_level',
        'comment_level'
    ];

    protected $casts = [
        'list_level' => 'integer',
        'read_level' => 'integer',
        'write_level' => 'integer',
        'comment_level' => 'integer',
    ];
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}