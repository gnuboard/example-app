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
        <div class="comment-container">
            {{-- 원 댓글 --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium">{{ $comment->user->name }}</span>
                        <span class="text-sm text-gray-500">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if(Auth::id() === $comment->user_id)
                        <div class="flex space-x-2">
                            <button class="text-sm text-blue-500 hover:text-blue-600">수정</button>
                            <button class="text-sm text-red-500 hover:text-red-600">삭제</button>
                        </div>
                    @endif
                </div>
                <div class="mt-2">
                    <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                </div>
                <div class="mt-2">
                    <button class="text-sm text-blue-500 hover:text-blue-600" onclick="toggleReplyForm('{{ $comment->id }}')">
                        답글쓰기
                    </button>
                </div>
            </div>

            {{-- 대댓글 입력 폼 (기본적으로 숨김) --}}
            <div id="replyForm-{{ $comment->id }}" class="hidden ml-8 mt-2">
                <form action="{{ route('comments.store') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="hidden" name="board_identifier" value="{{ $post->board->identifier }}">
                    <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <input type="hidden" name="mentioned_user_name" value="{{ $comment->user->name }}">
                    <textarea name="content" rows="2" 
                            class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ $comment->user->name }}님에게 답글 작성"></textarea>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                        답글 작성
                    </button>
                </form>
            </div>

            {{-- 대댓글 목록 --}}
            @if($comment->replies->count() > 0)
                <div class="ml-8 mt-2 space-y-2">
                    @foreach($comment->replies as $reply)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-600">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium">{{ $reply->user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $reply->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                @if(Auth::id() === $reply->user_id)
                                    <div class="flex space-x-2">
                                        <button class="text-sm text-blue-500 hover:text-blue-600">수정</button>
                                        <button class="text-sm text-red-500 hover:text-red-600">삭제</button>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2">
                                @if($reply->mentioned_user_name)
                                    <span class="text-blue-500">{{ $reply->mentioned_user_name }}</span>
                                @endif
                                <p class="text-gray-700 dark:text-gray-300">{{ $reply->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>

<script>
function toggleReplyForm(commentId) {
    const formElement = document.getElementById(`replyForm-${commentId}`);
    formElement.classList.toggle('hidden');
}
</script> 