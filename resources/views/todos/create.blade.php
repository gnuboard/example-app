<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
</head>
<body>
    <h1>Todo Create</h1>
    <form method="POST" action="{{ route('todos.store') }}">
    @csrf
    
    <div>
        <input type="text" name="title" value="{{ old('title') }}" required>
        @error('title')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">저장하기</button>
    <a href="{{ route('todos.index') }}">취소</a>
</form>
</body>
</html>