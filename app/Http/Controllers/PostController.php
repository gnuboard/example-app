<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use App\Models\PostVote;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create($name)
    {
        try {
            \Log::info('Attempting to show create form', ['name' => $name]);
            
            $board = Board::where('name', $name)->firstOrFail();
            \Log::info('Board found', ['board' => $board->toArray()]);
            
            return view('boards.posts.create', compact('board'));
        } catch (\Exception $e) {
            \Log::error('Create form error: ' . $e->getMessage(), [
                'name' => $name,
                'exception' => $e
            ]);
            abort(404);
        }
    }

    public function store(Request $request, $name)
    {
        $board = Board::where('name', $name)->firstOrFail();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post = $board->posts()->create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content']
        ]);

        return redirect("/{$board->name}/{$post->id}")
            ->with('success', '게시글이 작성되었습니다.');
    }

    public function show($name, $id)
    {
        try {
            \Log::info('Attempting to show post', [
                'name' => $name,
                'id' => $id
            ]);

            $board = Board::where('name', $name)->firstOrFail();
            \Log::info('Board found', ['board' => $board->toArray()]);

            $post = Post::where('board_id', $board->id)
                        ->where('id', $id)
                        ->firstOrFail();
            \Log::info('Post found', ['post' => $post->toArray()]);
                    
            return view('boards.posts.show', compact('board', 'post'));
        } catch (\Exception $e) {
            \Log::error('Post show error: ' . $e->getMessage(), [
                'name' => $name,
                'id' => $id,
                'exception' => $e
            ]);
            abort(404);
        }
    }

    public function edit($name, $id)
    {
        $board = Board::where('name', $name)->firstOrFail();
        $post = Post::where('board_id', $board->id)
                    ->where('id', $id)
                    ->firstOrFail();

        // 작성자만 수정 가능하도록
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        return view('boards.posts.edit', compact('board', 'post'));
    }

    public function update(Request $request, $name, $id)
    {
        $board = Board::where('name', $name)->firstOrFail();
        $post = Post::where('board_id', $board->id)
                    ->where('id', $id)
                    ->firstOrFail();

        // 작성자만 수정 가능하도록
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post->update($validated);

        return redirect("/{$board->name}/{$post->id}")
            ->with('success', '게시글이 수정되었습니다.');
    }

    public function destroy($name, $id)
    {
        try {
            $board = Board::where('name', $name)->firstOrFail();
            $post = Post::where('board_id', $board->id)
                        ->where('id', $id)
                        ->firstOrFail();

            // 작성자만 삭제 가능하도록 체크
            if (auth()->id() !== $post->user_id) {
                abort(403, '삭제 권한이 없습니다.');
            }

            $post->delete();

            return redirect("/{$board->name}")
                ->with('success', '게시글이 삭제되었습니다.');
            
        } catch (\Exception $e) {
            \Log::error('Post deletion error: ' . $e->getMessage());
            return back()->with('error', '게시글 삭제 중 오류가 발생했습니다.');
        }
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'latest'); // 기본값은 최신순

        $posts = Post::with(['user', 'board'])
            ->when($sort === 'latest', function ($query) {
                return $query->latest();
            })
            ->when($sort === 'popular', function ($query) {
                return $query->withCount(['votes' => function($query) {
                    $query->where('is_like', true);
                }])
                ->orderByDesc('votes_count')
                ->orderByDesc('created_at');
            })
            ->paginate(15)
            ->withQueryString(); // 정렬 파라미터 유지

        return view('posts.index', compact('posts', 'sort'));
    }

    public function vote(Request $request, $name, $id)
    {
        $board = Board::where('name', $name)->firstOrFail();
        $post = Post::where('board_id', $board->id)
                    ->where('id', $id)
                    ->firstOrFail();

        $existingVote = PostVote::where('post_id', $post->id)
                               ->where('user_id', auth()->id())
                               ->first();

        // 이미 같은 투표가 있으면 삭제 (취소)
        if ($existingVote && $existingVote->is_like === $request->boolean('is_like')) {
            $existingVote->delete();
            return back();
        }

        // 없거나 다른 투표면 생성/업데이트
        PostVote::updateOrCreate(
            [
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ],
            ['is_like' => $request->boolean('is_like')]
        );

        return back();
    }
}
