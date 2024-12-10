<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            ->paginate(10);

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
                'attachments.*' => 'nullable|file|max:10240' // 다중 파일 업로드 검증
            ], [
                'title.required' => '제목을 입력해주세요.',
                'content.required' => '내용을 입력해주세요.',
                'attachments.*.max' => '각 파일의 크기는 10MB를 초과할 수 없습니다.'
            ]);

            $post = new Post();
            $post->board_id = $board->id;
            $post->user_id = auth()->id();
            $post->title = $validated['title'];
            $post->content = $validated['content'];
            $post->save();

            // 다중 파일 처리
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    // 랜덤 해시값으로 파일명 생성 (32자)
                    $hashedName = Str::random(32);
                    
                    // 원본 파일의 확장자는 저장하지 않음
                    $path = $file->storeAs('attachments', $hashedName, 'public');
                    
                    $post->attachments()->create([
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ]);
                }
            }

            return redirect()
                ->route('posts.index', $board->identifier)
                ->with('success', '게시물이 성공적으로 작성되었습니다.');
                
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('게시물 작성 오류: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => '게시물 작성 중 오류가 발생했습니다.']);
        }
    }

    public function show($identifier, $id)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        $post = Post::with('attachments')->findOrFail($id);
        
        // 게시물이 현재 게시판에 속하는지 확인
        if ($post->board_id !== $board->id) {
            abort(404);
        }

        // 세션에 저장된 조회 기록 확인
        $viewedPosts = session('viewed_posts', []);
        $sessionKey = 'post_' . $post->id;
        
        // 해당 게시물을 처음 조회하는 경우에만 조회수 증가
        if (!in_array($sessionKey, $viewedPosts)) {
            $post->increment('view_count');
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
                'attachments.*' => 'nullable|file|max:10240',
                'delete_attachments.*' => 'nullable|integer|exists:attachments,id'
            ], [
                'attachments.*.max' => '파일 크기는 10MB를 초과할 수 없습니다.'
            ]);

            // 삭제할 첨부파일 처리
            if ($request->has('delete_attachments')) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = $post->attachments()->find($attachmentId);
                    if ($attachment) {
                        \Storage::disk('public')->delete($attachment->file_path);
                        $attachment->delete();
                    }
                }
            }

            // 새로운 첨부파일 추가
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    // 랜덤 해시값으로 파일명 생성 (32자)
                    $hashedName = Str::random(32);
                    
                    // 원본 파일의 확장자는 저장하지 않음
                    $path = $file->storeAs('attachments', $hashedName, 'public');
                    
                    $post->attachments()->create([
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ]);
                }
            }

            $post->update([
                'title' => $validated['title'],
                'content' => $validated['content']
            ]);

            return redirect()
                ->route('posts.show', [$board->identifier, $post->id])
                ->with('success', '게시물이 성공적으로 수정되었습니다.');
                
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('게시물 수정 오류: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => '게시물 수정 중 오류가 발생했습니다.']);
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
            // 첨부파일 삭제
            foreach ($post->attachments as $attachment) {
                \Storage::disk('public')->delete($attachment->file_path);
            }
            
            $post->delete(); // cascade 삭제로 attachments도 함께 삭제됨
            
            return redirect()
                ->route('posts.index', $board->identifier)
                ->with('success', '게시물이 성공적으로 삭제되었습니다.');
                
        } catch (\Exception $e) {
            \Log::error('게시물 삭제 오류: ' . $e->getMessage());
            return back()->withErrors(['error' => '게시물 삭제 중 오류가 발생했습니다.']);
        }
    }
}
