<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index($identifier)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        $user = Auth::user();
        
        $listLevel = $board->list_level;
        $userLevel = $user ? $user->level : config('constants.user_levels.visitor');
        
        if ($listLevel > $userLevel) {
            return view('posts.index', [
                'board' => $board,
                'error' => '목록을 볼 권한이 없습니다.',
                'posts' => Post::where('id', 0)->paginate(15)
            ]);
        }
        
        $posts = Post::where('board_id', $board->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('posts.index', compact('board', 'posts'));
    }

    public function create($identifier)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        return view('posts.create', compact('board'));
    }

    public function store(Request $request, $identifier)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ], [
                'title.required' => '제목을 입력해주세요.',
                'content.required' => '내용을 입력해주세요.',
            ]);

            $post = new Post();
            $post->board_id = $board->id;
            $post->user_id = auth()->id();
            $post->title = $validated['title'];
            $post->content = $validated['content'];
            $post->save();

            return redirect()
                ->route('posts.index', $board->identifier)
                ->with('success', '게시물이 성공적으로 작성되었습니다.');
            
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('게시물 작성 오류: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => '게시물 작성 중 오류가 발생했습니다.']);
        }
    }

    public function show($identifier, $id)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        $post = Post::findOrFail($id);
        
        // 게시물이 현재 게시판에 속하는지 확인
        if ($post->board_id !== $board->id) {
            abort(404);
        }

        // 세션에 저장된 조회 기록 확인
        $viewedPosts = session('viewed_posts', []);
        $sessionKey = 'post_' . $post->id;
        
        // 해당 게시물을 처음 조회하는 경우에만 조회수 증가
        if (!in_array($sessionKey, $viewedPosts)) {
            $post->increment('views');
            $viewedPosts[] = $sessionKey;
            session(['viewed_posts' => $viewedPosts]);
        }

        return view('posts.show', compact('board', 'post'));
    }

    public function edit($identifier, $id)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        $post = Post::findOrFail($id);
        
        if ($post->board_id !== $board->id) {
            abort(404);
        }
        
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }
        
        return view('posts.edit', compact('board', 'post'));
    }

    public function update(Request $request, $identifier, $id)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        $post = Post::findOrFail($id);
        
        if ($post->board_id !== $board->id) {
            abort(404);
        }
        
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ], [
                'title.required' => '제목을 입력해주세요.',
                'content.required' => '내용을 입력해주세요.',
            ]);

            $post->update([
                'title' => $validated['title'],
                'content' => $validated['content']
            ]);

            return redirect()
                ->route('posts.show', [$board->identifier, $post->id])
                ->with('success', '게시물이 성공적으로 수정되었습니다.');
            
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('게시물 수정 오류: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => '게시물 수정 중 오류가 발생했습니다.']);
        }
    }

    public function destroy($identifier, $id)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        $post = Post::findOrFail($id);
        
        if ($post->board_id !== $board->id) {
            abort(404);
        }
        
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }
        
        try {
            $post->delete();
            
            return redirect()
                ->route('posts.index', $board->identifier)
                ->with('success', '게시물이 성공적으로 삭제되었습니다.');
            
        } catch (\Exception $e) {
            \Log::error('게시물 삭제 오류: ' . $e->getMessage());
            
            return back()->withErrors(['error' => '게시물 삭제 중 오류가 발생했습니다.']);
        }
    }
}
