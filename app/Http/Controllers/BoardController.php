<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::query()
            ->when(auth()->check(), function ($query) {
                $query->where('level', '<=', auth()->user()->level);
            }, function ($query) {
                $query->where('level', 0);
            })
            ->latest()
            ->get();

        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        return view('boards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:boards,name',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Board::create($validated);

        return redirect()->route('boards.index')
            ->with('success', '게시판이 생성되었습니다.');
    }

    public function show($name)
    {
        $board = Board::where('name', $name)->firstOrFail();
        $posts = $board->posts()->with('user')->latest()->paginate(15);
        return view('boards.show', compact('board', 'posts'));
    }

    public function edit(Board $board)
    {
        return view('boards.edit', compact('board'));
    }

    public function update(Request $request, Board $board)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $board->update($validated);

        return redirect()->route('boards.index')
            ->with('success', '게시판이 수정되었습니다.');
    }

    public function destroy(Board $board)
    {
        $board->delete();

        return redirect()->route('boards.index')
            ->with('success', '게시판이 삭제되었습니다.');
    }
} 