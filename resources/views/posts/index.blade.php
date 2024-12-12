<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $board->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <x-error-message :error="$error ?? null">
                        <div class="mb-6 flex justify-end">
                            <a href="{{ route('posts.create', $board->identifier) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                                새 글 쓰기
                            </a>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">번호</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">제목</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">작성자</th>
                                        <th class="hidden md:table-cell px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">작성일</th>
                                        <th class="hidden md:table-cell px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">조회</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @forelse($posts as $index => $post)
                                        <tr>
                                            <td class="hidden md:table-cell px-4 py-2 whitespace-nowrap text-sm">
                                                {{ $posts->total() - ($posts->firstItem() + $index) + 1 }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                <a href="{{ route('posts.show', [
                                                    'identifier' => $board->identifier, 
                                                    'id' => $post->id,
                                                    'search_type' => request()->get('search_type'),
                                                    'search' => request()->get('search'),
                                                    'page' => request()->get('page')
                                                ]) }}" 
                                                   class="text-blue-500 hover:text-blue-600">
                                                    @if($post->attachment_count > 0)
                                                        <i class="fas fa-paperclip text-gray-500 dark:text-gray-400 mr-1"></i>
                                                    @endif
                                                    {{ $post->title }}
                                                    @if($post->comments_count > 0)
                                                        <span class="ml-1 text-gray-500 dark:text-gray-400">[{{ $post->comments_count }}]</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                <a href="#" class="author-link hover:text-blue-500" data-user-uuid="{{ $post->user->uuid }}">
                                                    {{ $post->author }}
                                                </a>
                                            </td>
                                            <td class="hidden md:table-cell px-4 py-2 whitespace-nowrap text-sm">{{ $post->created_at->format('Y-m-d') }}</td>
                                            <td class="hidden md:table-cell px-4 py-2 whitespace-nowrap text-sm">{{ $post->view_count ?? 0 }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-2 text-center text-sm">
                                                게시물이 없습니다.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end items-center">
                            <form method="GET" action="{{ route('posts.index', $board->identifier) }}" class="flex gap-3 items-center">
                                <select name="search_type" class="w-40 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <option value="title" {{ request('search_type') == 'title' ? 'selected' : '' }}>제목</option>
                                    <option value="content" {{ request('search_type') == 'content' ? 'selected' : '' }}>내용</option>
                                    <option value="comment" {{ request('search_type') == 'comment' ? 'selected' : '' }}>댓글</option>
                                    <option value="author" {{ request('search_type') == 'author' ? 'selected' : '' }}>작성자</option>
                                    <option value="comment_author" {{ request('search_type') == 'comment_author' ? 'selected' : '' }}>댓글작성자</option>
                                </select>
                                <input type="text" name="search" placeholder="검색어를 입력하세요" 
                                       class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg flex-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" value="{{ request('search') }}">
                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md">
                                    검색
                                </button>
                            </form>
                        </div>

                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    </x-error-message>
                </div>
            </div>
        </div>
    </div>

    <!-- 레이어 팝업 -->
    <div id="userActionLayer" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="layer-content absolute bg-white rounded-lg shadow-lg min-w-[200px]">
            <h3 class="px-4 py-3 font-medium border-b border-gray-200">사용자 메뉴</h3>
            <ul class="divide-y divide-gray-200">
                <li>
                    <a href="#" id="viewProfile" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                        회원정보
                    </a>
                </li>
                <li>
                    <a href="#" id="sendMessage" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                        쪽지보내기
                    </a>
                </li>
                <li>
                    <a href="#" id="viewPosts" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                        작성글보기
                    </a>
                </li>
                <li>
                    <a href="#" id="viewComments" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                        작성댓글보기
                    </a>
                </li>
            </ul>
            <button class="close-btn absolute top-2 right-2 p-2 hover:bg-gray-100 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const layer = document.getElementById('userActionLayer');
            const layerContent = layer.querySelector('.layer-content');
            
            // 작성자 링크 클릭 이벤트
            document.querySelectorAll('.author-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userUuid = this.dataset.userUuid;
                    const rect = this.getBoundingClientRect();
                    
                    layerContent.style.left = rect.left + 'px';
                    layerContent.style.top = (rect.bottom + 5) + 'px';
                    
                    // 각 메뉴 항목에 userUuid 설정
                    layer.querySelectorAll('a').forEach(menuItem => {
                        menuItem.dataset.userUuid = userUuid;
                    });
                    
                    layer.classList.remove('hidden');
                });
            });
            
            // 닫기 버튼
            layer.querySelector('.close-btn').addEventListener('click', () => {
                layer.classList.add('hidden');
            });
            
            // 레이어 외부 클릭시 닫기
            layer.addEventListener('click', function(e) {
                if (e.target === layer) {
                    layer.classList.add('hidden');
                }
            });
            
            // 각 메뉴 항목 클릭 이벤트
            document.getElementById('viewProfile').addEventListener('click', function(e) {
                e.preventDefault();
                const userUuid = this.dataset.userUuid;
                window.location.href = `/users/profile/${userUuid}`;
            });
            
            document.getElementById('sendMessage').addEventListener('click', function(e) {
                e.preventDefault();
                const userUuid = this.dataset.userUuid;
                window.location.href = `/messages/create?to=${userUuid}`;
            });
            
            document.getElementById('viewPosts').addEventListener('click', function(e) {
                e.preventDefault();
                const userUuid = this.dataset.userUuid;
                window.location.href = `/users/${userUuid}/posts`;
            });
            
            document.getElementById('viewComments').addEventListener('click', function(e) {
                e.preventDefault();
                const userUuid = this.dataset.userUuid;
                window.location.href = `/users/${userUuid}/comments`;
            });
        });
    </script>
</x-app-layout> 