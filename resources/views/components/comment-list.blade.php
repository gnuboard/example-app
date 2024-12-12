{{-- 새 댓글 입력 폼 --}}
<div class="mb-4">
    @auth
        <form action="{{ route('comments.store') }}" method="POST" class="space-y-2">
            @csrf
            <input type="hidden" name="board_identifier" value="{{ $post->board->identifier }}">
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea name="content" rows="3" 
                    class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="댓글을 입력하세요"></textarea>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                댓글 작성
            </button>
        </form>
    @else
        <p class="text-gray-500">댓글을 작성하려면 <a href="{{ route('login') }}" class="text-blue-500">로그인</a>이 필요합니다.</p>
    @endauth
</div>

<div class="space-y-4">
    @foreach($comments as $comment)
        <div id="comment-{{ $comment->id }}" class="comment-container {{ $comment->parent_id ? 'ml-8' : '' }}">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                {{-- 댓글 헤더 --}}
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium">{{ $comment->user->name }}</span>
                        <span class="text-xs text-gray-500">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if(!$comment->trashed())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z">
                                </path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            @if(Auth::id() === $comment->user_id)
                                <button type="button" 
                                        onclick="toggleEditForm({{ $comment->id }})" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    수정
                                </button>
                                <form action="{{ route('comments.destroy', $comment) }}" 
                                      method="POST" 
                                      class="block" 
                                      onsubmit="return confirm('정말 이 댓글을 삭제하시겠습니까?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        삭제
                                    </button>
                                </form>
                            @endif
                            <button type="button"
                                    onclick="toggleReplyForm('{{ $comment->id }}')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                답글 쓰기
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 댓글 내용 (일반 보기) --}}
                <div id="comment-content-{{ $comment->id }}" class="mt-2">
                    <p class="text-sm {{ $comment->trashed() ? 'text-gray-400 italic' : 'text-gray-700 dark:text-gray-300' }}">
                        <span class="text-xs text-gray-500 mr-2">[{{ $comment->id }}]</span>
                        @if($comment->parent_id && !$comment->trashed())
                            <span class="text-xs text-blue-500 mr-1">{{ $comment->mentioned_author }}</span>
                        @endif
                        @if($comment->trashed())
                            삭제된 댓글입니다.
                        @else
                            {{ $comment->content }}
                        @endif
                    </p>
                </div>

                {{-- 댓글 수정 폼 --}}
                <div id="edit-form-{{ $comment->id }}" class="mt-2 hidden">
                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <textarea name="content" rows="3" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $comment->content }}</textarea>
                        <div class="flex space-x-2 mt-2">
                            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-xs">수정완료</button>
                            <button type="button" onclick="toggleEditForm({{ $comment->id }})" class="px-3 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-xs">취소</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 답글 폼 --}}
            <div id="replyForm-{{ $comment->id }}" class="hidden ml-8 mt-2">
                <form action="{{ route('comments.store') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="hidden" name="board_identifier" value="{{ $post->board->identifier }}">
                    <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <input type="hidden" name="ment" value="{{ $comment->user->name }}">
                    <textarea name="content" rows="2" 
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ $comment->user->name }}님에게 답글 작성"></textarea>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                        답글 작성
                    </button>
                </form>
            </div>

        </div>
    @endforeach
</div>

<script>
function toggleReplyForm(commentId) {
    const formElement = document.getElementById(`replyForm-${commentId}`);
    formElement.classList.toggle('hidden');
}

function toggleEditForm(commentId) {
    console.log('Toggle edit form for comment:', commentId);
    const contentElement = document.getElementById(`comment-content-${commentId}`);
    const formElement = document.getElementById(`edit-form-${commentId}`);
    
    if (contentElement && formElement) {
        contentElement.classList.toggle('hidden');
        formElement.classList.toggle('hidden');
    }
}
</script>