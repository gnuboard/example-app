<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- 프로필 정보 -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-bold mb-4 dark:text-white">회원정보</h2>
                    <div class="space-y-4">
                        <div>
                            <span class="font-medium dark:text-gray-300">이름:</span>
                            <span class="dark:text-gray-100">{{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium dark:text-gray-300">레벨:</span>
                            <span class="dark:text-gray-100">{{ $user->level }} 레벨</span>
                        </div>
                        <div>
                            <span class="font-medium dark:text-gray-300">가입일:</span>
                            <span class="dark:text-gray-100">{{ $user->created_at->format('Y-m-d') }}</span>
                        </div>

                        @auth
                        @if(auth()->id() !== $user->id)
                        <div class="mt-4">
                            <div class="flex items-center space-x-2">
                                <span class="font-medium dark:text-gray-300">메모:</span>
                                <button id="editMemoBtn" class="text-sm text-blue-500 hover:text-blue-600">
                                    {{ $memo ? '수정' : '작성' }}
                                </button>
                            </div>
                            <div id="memoDisplay" class="mt-2 text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $memo ? $memo->content : '등록된 메모가 없습니다.' }}</div>
                            <div id="memoForm" class="mt-2 hidden">
                                <form id="saveMemoForm" class="space-y-2">
                                    @csrf
                                    <textarea name="content" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="3">{{ $memo ? $memo->content : '' }}</textarea>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" id="cancelMemoBtn" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                                            취소
                                        </button>
                                        <button type="submit" class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
                                            저장
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        @endauth
                    </div>
                </div>

                <!-- 최근 게시글 -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold mb-4">최근 게시글</h3>
                    @if($posts->count() > 0)
                        <ul class="space-y-2">
                            @foreach($posts as $post)
                                <li class="flex justify-between items-center">
                                    <a href="{{ route('posts.show', ['identifier' => $post->board->identifier, 'id' => $post->id]) }}" class="hover:text-blue-500">
                                        {{ Str::limit($post->title, 50) }}
                                    </a>
                                    <span class="text-sm text-gray-500">
                                        {{ $post->created_at->format('Y-m-d') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">작성한 게시글이 없습니다.</p>
                    @endif
                </div>

                <!-- 최근 댓글 -->
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">최근 댓글</h3>
                    @if($comments->count() > 0)
                        <ul class="space-y-2">
                            @foreach($comments as $comment)
                                <li class="flex justify-between items-center">
                                    <a href="{{ route('posts.show', ['identifier' => $comment->post->board->identifier, 'id' => $comment->post->id]) }}" class="hover:text-blue-500">
                                        {{ Str::limit($comment->content, 50) }}
                                    </a>
                                    <span class="text-sm text-gray-500">
                                        {{ $comment->created_at->format('Y-m-d') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">작성한 댓글이 없습니다.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editMemoBtn = document.getElementById('editMemoBtn');
            const memoDisplay = document.getElementById('memoDisplay');
            const memoForm = document.getElementById('memoForm');
            const cancelMemoBtn = document.getElementById('cancelMemoBtn');
            const saveMemoForm = document.getElementById('saveMemoForm');

            if (editMemoBtn) {
                editMemoBtn.addEventListener('click', function() {
                    memoDisplay.classList.add('hidden');
                    memoForm.classList.remove('hidden');
                });
            }

            if (cancelMemoBtn) {
                cancelMemoBtn.addEventListener('click', function() {
                    memoDisplay.classList.remove('hidden');
                    memoForm.classList.add('hidden');
                });
            }

            if (saveMemoForm) {
                saveMemoForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    try {
                        const response = await fetch(`/users/memo/{{ $user->uuid }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            },
                            body: JSON.stringify({
                                content: this.content.value
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            memoDisplay.textContent = data.content;
                            memoDisplay.classList.remove('hidden');
                            memoForm.classList.add('hidden');
                        } else {
                            throw new Error('메모 저장에 실패했습니다.');
                        }
                    } catch (error) {
                        alert(error.message);
                    }
                });
            }
        });
    </script>
</x-app-layout> 