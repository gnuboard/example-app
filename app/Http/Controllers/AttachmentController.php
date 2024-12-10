<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Post $post)
    {
        \Log::info('Download method called for post ID: ' . $post->id);
        
        if (!$post->attachment) {
            \Log::warning('No attachment found for post ID: ' . $post->id);
            abort(404);
        }

        $path = $post->attachment;
        \Log::info('Attempting to download file at path: ' . $path);
        
        if (!Storage::disk('public')->exists($path)) {
            \Log::error('File does not exist at path: ' . $path);
            abort(404);
        }

        \Log::info('File found, initiating download for path: ' . $path);
        return Storage::disk('public')->download($path);
    }

    public function show($postId)
    {
        \Log::info('Attachment show method called for post ID: ' . $postId);
        
        $post = Post::findOrFail($postId);
        
        if (!$post->attachment) {
            \Log::warning('No attachment found for post ID: ' . $postId);
            abort(404);
        }

        $path = storage_path('app/public/' . $post->attachment);
        \Log::info('Attempting to access file at path: ' . $path);
        
        if (!file_exists($path)) {
            \Log::error('File does not exist at path: ' . $path);
            abort(404);
        }

        \Log::info('File found, returning response for path: ' . $path);
        return response()->file($path);
    }
} 