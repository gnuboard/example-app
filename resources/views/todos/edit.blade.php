<!DOCTYPE html>
<html>
<head>
    <title>할일 수정</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>할일 수정</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('todos.update', $todo->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div>
            <label for="title">제목:</label>
            <input type="text" id="title" name="title" value="{{ old('title', $todo->title) }}" required>
        </div>

        <div>
            <label for="is_done">상태:</label>
            <input type="checkbox" id="is_done" name="is_done" {{ $todo->is_done ? 'checked' : '' }}>
            <span>완료</span>
        </div>

        <div class="actions">
            <button type="submit">수정하기</button>
            <a href="{{ route('todos.show', $todo->id) }}">취소</a>
        </div>
    </form>
</body>
</html> 