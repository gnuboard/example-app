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
        $validated = $request->validate([
            'content' => 'required|string|max:1000|not_regex:/[<>]/',
            'board_identifier' => 'required|string|max:255',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
            'mentioned_author' => 'nullable|string|max:255'
        ]);
    
        try {
            $board = Board::where('identifier', $request->board_identifier)->firstOrFail();
    
            $comment = \DB::transaction(function() use ($request, $board) {
                $post = Post::lockForUpdate()->find($request->post_id);
                
                // parent_id 결정
                $actualParentId = null;
                if ($request->parent_id) {
                    // 답글을 다는 대상 댓글 찾기
                    $replyToComment = Comment::find($request->parent_id);
                    // 만약 답글을 다는 대상이 이미 답글이라면, 그 답글의 parent_id를 사용
                    $actualParentId = $replyToComment->parent_id ?? $replyToComment->id;
                }
                
                if ($actualParentId) {
                    // 부모 댓글이 있는 경우
                    $parentComment = Comment::lockForUpdate()->find($actualParentId);
                    
                    // 부모 댓글과 연관된 모든 답글들 중 가장 큰 sort_order 찾기
                    $lastReplyOrder = Comment::where('post_id', $request->post_id)
                        ->where(function($query) use ($parentComment) {
                            $query->where('parent_id', $parentComment->id)
                                ->orWhere('id', $parentComment->id);
                        })
                        ->max('sort_order');
                    
                    // 해당 sort_order 이후의 모든 댓글들의 순서를 1씩 증가
                    Comment::where('post_id', $request->post_id)
                        ->where('sort_order', '>', $lastReplyOrder)
                        ->increment('sort_order');
                    
                    // 새 답글의 sort_order는 마지막 연관 답글의 다음 순서
                    $sort_order = $lastReplyOrder + 1;
                } else {
                    // 최상위 댓글인 경우 가장 큰 sort_order + 1
                    $maxOrder = Comment::where('post_id', $request->post_id)
                        ->max('sort_order');
                    $sort_order = ($maxOrder ?? 0) + 1;
                }
    
                return Comment::create([
                    'content' => $request->content,
                    'post_id' => $request->post_id,
                    'board_id' => $board->id,
                    'user_id' => auth()->id(),
                    'author' => auth()->user()->name,
                    'parent_id' => $actualParentId,
                    'mentioned_author' => $request->mentioned_author,
                    'sort_order' => $sort_order
                ]);
            });
    
            Post::where('id', $request->post_id)->increment('comments_count');
    
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

        try {
            // 답글이 있는지 확인
            if ($comment->hasReplies()) {
                // 답글이 있는 경우 내용만 변경하고 소프트 삭제
                $comment->update([
                    'content' => ''
                ]);
                $comment->delete(); // 소프트 삭제
                return back()->with('success', '댓글이 삭제 처리되었습니다.');
            }

            // 답글이 없는 경우
            if ($comment->parent_id) {
                // 답글인 경우 완전 삭제
                $comment->forceDelete();
            } else {
                // 부모 댓글인 경우 완전 삭제
                $comment->forceDelete();
            }

            return back()->with('success', '댓글이 삭제되었습니다.');

        } catch (\Exception $e) {
            \Log::error('댓글 삭제 오류: ' . $e->getMessage());
            return back()->withErrors(['error' => '댓글 삭제 중 오류가 발생했습니다.']);
        }
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
                'content' => $validated['content'],
                'author' => auth()->user()->name
            ]);

            return back()->with('success', '댓글이 수정되었습니다.');
        } catch (\Exception $e) {
            \Log::error('댓글 수정 오류: ' . $e->getMessage());
            return back()->withErrors(['error' => '댓글 수정 중 오류가 발생했습니다.']);
        }
    }
} 