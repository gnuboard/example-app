<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardManageController extends Controller
{
    public function index()
    {
        $boards = Board::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.boards.index', compact('boards'));
    }

    public function create()
    {
        return view('admin.boards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:boards,name',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ], [
            'name.unique' => '이미 존재하는 게시판 이름 입니다.'
        ]);

        Board::create($validated);

        return redirect()->route('admin.boards.index')
            ->with('success', '게시판이 생성되었습니다.');
    }

    public function edit(Board $board)
    {
        return view('admin.boards.edit', compact('board'));
    }

    public function update(Request $request, Board $board)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:boards,name,'.$board->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $board->update($validated);

        return redirect()->route('admin.boards.index')
            ->with('success', '게시판이 수정되었습니다.');
    }

    public function destroy(Board $board)
    {
        $board->delete();
        return redirect()->route('admin.boards.index')
            ->with('success', '게시판이 삭제되었습니다.');
    }
}
