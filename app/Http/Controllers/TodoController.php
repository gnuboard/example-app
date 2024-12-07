<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::all();
        return view('todos.index', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|min:3'
        ], [
            'title.min' => '제목은 최소 3글자 이상이어야 합니다.',
            'title.required' => '제목은 필수 입력 항목입니다.',
            'title.max' => '제목은 255글자를 초과할 수 없습니다.'
        ]);

        Todo::create($validated);
        return redirect()->route('todos.index')->with('success', '할 일이 추가되었습니다.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::findOrFail($id);
        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $todo = Todo::findOrFail($id);
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the title of the specified resource.
     */
    public function updateTitle(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|min:3'
        ], [
            'title.min' => '제목은 최소 3글자 이상이어야 합니다.',
            'title.required' => '제목은 필수 입력 항목입니다.',
            'title.max' => '제목은 255글자를 초과할 수 없습니다.'
        ]);

        $todo = Todo::findOrFail($id);
        $todo->title = $validated['title'];
        $todo->save();

        return redirect()->route('todos.index')->with('success', '할 일이 정되었습니다.');
    }

    /**
     * Update the completion status of the specified resource.
     */
    public function updateComplete(Request $request, $id)
    {
        try {
            $todo = Todo::findOrFail($id);
            $todo->is_done = $request->is_done;
            $todo->save();

            return response()->json([
                'success' => true,
                'message' => '할일 상태가 업데이트되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '업데이트 중 오류가 발생했습니다.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect()
            ->route('todos.index')
            ->with('success', '할일이 삭제되었습니다.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255|min:3',
        ], [
            'title.min' => '제목은 최소 3글자 이상이어야 합니다.',
            'title.required' => '제목은 필수 입력 항목입니다.',
            'title.max' => '제목은 255글자를 초과할 수 없습니다.'
        ]);

        $todo = Todo::findOrFail($id);
        $todo->title = $validated['title'];
        $todo->is_done = $request->has('is_done');
        $todo->save();

        return redirect()
            ->route('todos.show', $todo->id)
            ->with('success', '할일이 수정되었습니다.');
    }
}
