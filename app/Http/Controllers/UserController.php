<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $posts = $user->posts()->latest()->take(5)->get();
        $comments = $user->comments()->latest()->take(5)->get();
        
        return view('users.profile.show', compact('user', 'posts', 'comments'));
    }
} 