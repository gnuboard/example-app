<form method="POST" action="{{ route('board.store', $board_name) }}">
    @csrf
    <div class="mb-4">
        <label>제목</label>
        <input type="text" name="title" value="{{ old('title') }}" required>
    </div>
    
    @guest
        <div class="mb-4">
            <label>작성자명</label>
            <input type="text" name="writer_name" value="{{ old('writer_name') }}" required>
        </div>
        <div class="mb-4">
            <label>비밀번호</label>
            <input type="password" name="password" required>
            <p class="text-sm text-gray-600">글 수정/삭제시 필요합니다</p>
        </div>
    @endguest
    
    <div class="mb-4">
        <label>내용</label>
        <textarea name="content" required>{{ old('content') }}</textarea>
    </div>
    
    <button type="submit">작성하기</button>
</form> 