<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMemo;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $posts = $user->posts()->latest()->take(5)->get();
        $comments = $user->comments()->latest()->take(5)->get();
        $memo = null;
        
        if (auth()->check()) {
            $memo = UserMemo::where('user_id', auth()->id())
                           ->where('target_user_id', $user->id)
                           ->first();
        }
        
        return view('users.profile.show', compact('user', 'posts', 'comments', 'memo'));
    }

    public function saveMemo(Request $request, $uuid)
    {
        $targetUser = User::where('uuid', $uuid)->firstOrFail();
        
        $memo = UserMemo::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'target_user_id' => $targetUser->id
            ],
            ['content' => $request->content]
        );

        return response()->json([
            'content' => $memo->content
        ]);
    }
} 