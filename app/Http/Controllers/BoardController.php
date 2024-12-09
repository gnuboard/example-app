<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BoardController extends Controller
{
    public function __construct()
    {
        // dd() 제거하고 필요한 미들웨어만 설정
        // 예: $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = Board::all();
        return view('boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('boards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'identifier' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9-]+$/',
                    'unique:boards,identifier'
                ],
                'list_level' => 'required|integer|min:0|max:100',
                'read_level' => 'required|integer|min:0|max:100',
                'write_level' => 'required|integer|min:0|max:100',
                'comment_level' => 'required|integer|min:0|max:100',
            ], [
                'identifier.unique' => '이미 사용 중인 게시판 식별자입니다.',
                'identifier.regex' => '게시판 식별자는 영문자, 숫자, 하이픈(-)만 사용할 수 있습니다.',
            ]);

            $validated['category'] = 'general';
            $board = Board::create($validated);

            return redirect()
                ->route('boards.show', $board->id)
                ->with('success', '게시판이 성공적으로 생성되었습니다.');
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('게시판 생성 오류: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => '게시판 생성 중 오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // dd('show 메서드 실행됨', $id);
        try {
            $board = Board::findOrFail($id);
            
            // 데이터 확인을 위한 디버깅 추가
            // dd([
            //     'id' => $id,
            //     'board' => $board,
            //     'exists' => $board->exists,
            //     'attributes' => $board->getAttributes()
            // ]);
            
            return view('boards.show', [
                'board' => $board
            ]);
        } catch (\Exception $e) {
            \Log::error('게시판 조회 오류: ' . $e->getMessage());
            abort(404, '게시판을 찾을 수 없습니다.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $board = Board::findOrFail($id);
        
        return view('boards.edit', [
            'board' => $board
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $board = Board::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'identifier' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9-]+$/',
                    'unique:boards,identifier,' . $board->id
                ],
                'list_level' => 'required|integer|min:0|max:100',
                'read_level' => 'required|integer|min:0|max:100',
                'write_level' => 'required|integer|min:0|max:100',
                'comment_level' => 'required|integer|min:0|max:100',
            ], [
                'identifier.unique' => '이미 사용 중인 게시판 식별자입니다.',
                'identifier.regex' => '게시판 식별자는 영문자, 숫자, 하이픈(-)만 사용할 수 있습니다.',
            ]);

            $board->update($validated);
            
            // posts_count 업데이트
            $board->posts_count = $board->posts()->count();
            $board->save();

            return redirect()
                ->route('boards.show', $board->id)
                ->with('success', '게시판이 성공적으로 수정되었습니다.');
        } catch (\Exception $e) {
            \Log::error('게시판 수정 오류: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => '게시판 수정 중 오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $board = Board::findOrFail($id);
            $board->delete();

            return redirect()
                ->route('admin')
                ->with('success', '게시판이 성공적으로 삭제되었습니다.');
        } catch (\Exception $e) {
            \Log::error('게시판 삭제 오류: ' . $e->getMessage());
            
            return back()
                ->withErrors(['error' => '게시판 삭제 중 오류가 발생했습니다.']);
        }
    }

    public function showByIdentifier($identifier)
    {
        $board = Board::where('identifier', $identifier)->firstOrFail();
        return view('boards.show', compact('board'));
    }
}
