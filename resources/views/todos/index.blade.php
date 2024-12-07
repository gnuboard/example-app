<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Todo List</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div>
        <a href="{{ route('todos.create') }}" class="btn">할 일 추가하기</a>
    </div>

    <ul>
        @foreach ($todos as $todo)
            <li>
                <input type="checkbox" class="todo-checkbox" 
                    data-id="{{ $todo->id }}"
                    {{ $todo->is_done ? 'checked' : '' }}>
                <a href="{{ route('todos.show', $todo->id) }}" class="todo-title" id="todo-{{ $todo->id }}" 
                    style="{{ $todo->is_done ? 'text-decoration: line-through' : '' }}">
                    {{ $todo->title }}
                </a>
                <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</button>
                </form>
            </li>
        @endforeach
    </ul>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('change', '.todo-checkbox', function() {
                const todoId = $(this).data('id');
                const isDone = $(this).is(':checked');
                const todoTitle = $(`#todo-${todoId}`);
                const checkbox = $(this);

                $.ajax({
                    url: "{{ url('todos') }}/" + todoId + "/complete",
                    type: 'PUT',
                    data: {
                        is_done: isDone ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            if (isDone) {
                                todoTitle.css('text-decoration', 'line-through');
                            } else {
                                todoTitle.css('text-decoration', 'none');
                            }
                        } else {
                            checkbox.prop('checked', !isDone);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax 에러:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        checkbox.prop('checked', !isDone);
                    }
                });
            });
        });
    </script>
</body>
</html>
