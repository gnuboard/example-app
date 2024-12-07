<!DOCTYPE html>
<html>
<head>
    <title>할일 상세보기</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>할일 상세보기</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="todo-details">
        <h2>{{ $todo->title }}</h2>
        
        <p>상태: {{ $todo->is_done ? '완료' : '미완료' }}</p>
        
        <p>생성일: {{ $todo->created_at->format('Y-m-d H:i:s') }}</p>
        <p>수정일: {{ $todo->updated_at->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="actions">
        <a href="{{ route('todos.index') }}" class="btn">목록으로 돌아가기</a>
        <a href="{{ route('todos.edit', $todo->id) }}" class="btn">수정하기</a>
        
        <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" onclick="return confirm('정말 삭제하시겠습니까?')">삭제하기</button>
        </form>
    </div>
</body>
</html> 