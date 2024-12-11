<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Board;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000|not_regex:/[<>]/',
                'board_identifier' => 'required|string|max:255',
                'post_id' => 'required|exists:posts,id',
                'parent_id' => 'nullable|exists:comments,id',
                'mentioned_user_name' => 'nullable|string|max:255'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());  // 유효성 검사 오류 확인
        }

        $board = Board::where('identifier', $request->board_identifier)->firstOrFail();

        $comment = \DB::transaction(function() use ($validated, $board) {
            return Comment::create([
                'content' => $validated['content'],
                'post_id' => $validated['post_id'],
                'board_id' => $board->id,
                'user_id' => auth()->id(),
                'parent_id' => $validated['parent_id'] ?? null,
                'mentioned_user_name' => $validated['mentioned_user_name'] ?? null
            ]);
        });

        return back()->with('success', '댓글이 작성되었습니다.');
    }
} 