<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Post;

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

            // parent_id가 request에 없을 경우를 체크
            if (!$request->has('parent_id')) {
                $validated['parent_id'] = null;
            }

            $board = Board::where('identifier', $request->board_identifier)->firstOrFail();

            $comment = \DB::transaction(function() use ($validated, $board) {
                // 락을 걸고 댓글 정보 조회
                $post = Post::lockForUpdate()->find($validated['post_id']);
                
                if ($validated['parent_id']) {
                    // 부모 댓글이 있는 경우
                    $parentComment = Comment::lockForUpdate()->find($validated['parent_id']);
                    
                    // 부모 댓글의 마지막 대댓글의 sort_order 찾기
                    $lastReplyOrder = Comment::where('post_id', $validated['post_id'])
                        ->where('parent_id', $validated['parent_id'])
                        ->max('sort_order') ?? $parentComment->sort_order;
                    
                    // 마지막 대댓글 이후의 모든 댓글의 sort_order를 1씩 증가
                    Comment::where('post_id', $validated['post_id'])
                        ->where('sort_order', '>', $lastReplyOrder)
                        ->increment('sort_order');
                        
                    // 새 댓글의 sort_order는 마지막 대댓글의 다음 순서
                    $sort_order = $lastReplyOrder + 1;
                } else {
                    // 최상위 댓글인 경우 가장 큰 sort_order + 1
                    $maxOrder = Comment::where('post_id', $validated['post_id'])
                        ->max('sort_order');
                    $sort_order = ($maxOrder ?? 0) + 1;
                }

                return Comment::create([
                    'content' => $validated['content'],
                    'post_id' => $validated['post_id'],
                    'board_id' => $board->id,
                    'user_id' => auth()->id(),
                    'parent_id' => $validated['parent_id'] ?? null,
                    'mentioned_user_name' => $validated['mentioned_user_name'] ?? null,
                    'sort_order' => $sort_order
                ]);
            });

            // 댓글 수 증가
            Post::where('id', $request->post_id)->increment('comments_count');

            // 직접 URL 구성 방식으로 변경
            $redirectUrl = route('posts.show', [
                'identifier' => $request->board_identifier,
                'id' => $request->post_id
            ]) . '#comment-' . $comment->id;

            return redirect($redirectUrl)->with('success', '댓글이 작성되었습니다.');
            
        } catch (\Exception $e) {
            \Log::error('댓글 작성 오류: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => '댓글 작성 중 오류가 발생했습니다.']);
        }
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return back()->with('error', '권한이 없습니다.');
        }

        // 댓글 수 감소
        Post::where('id', $comment->post_id)->decrement('comments_count');
        
        $comment->delete();

        return back()->with('success', '댓글이 삭제되었습니다.');
    }

    public function update(Request $request, Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return back()->with('error', '권한이 없습니다.');
        }

        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000|not_regex:/[<>]/'
            ]);

            $comment->update([
                'content' => $validated['content']
            ]);

            return back()->with('success', '댓글이 수정되었습니다.');
        } catch (\Exception $e) {
            \Log::error('댓글 수정 오류: ' . $e->getMessage());
            return back()->withErrors(['error' => '댓글 수정 중 오류가 발생했습니다.']);
        }
    }
} 