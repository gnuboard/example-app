<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $board->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-error-message :error="$error ?? null">
                        <div class="mb-4 flex justify-end">
                            <a href="{{ route('posts.create', $board->identifier) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                                새 글 쓰기
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="hidden md:table-cell px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">번호</th>
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
                                                <a href="{{ route('posts.show', ['identifier' => $board->identifier, 'id' => $post->id]) }}" 
                                                   class="text-blue-500 hover:text-blue-600">
                                                    {{ $post->title }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ optional($post->user)->name }}</td>
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

                        <div class="mt-4 flex justify-end items-center">
                            <form method="GET" action="{{ route('posts.index', $board->identifier) }}" class="flex gap-2 items-center">
                                <select name="search_type" class="px-3 py-1.5 border rounded-md text-sm">
                                    <option value="title" {{ request('search_type') == 'title' ? 'selected' : '' }}>제목</option>
                                    <option value="content" {{ request('search_type') == 'content' ? 'selected' : '' }}>내용</option>
                                    <option value="title_content" {{ request('search_type') == 'title_content' ? 'selected' : '' }}>제목+내용</option>
                                    <option value="comment" {{ request('search_type') == 'comment' ? 'selected' : '' }}>댓글</option>
                                    <option value="writer" {{ request('search_type') == 'writer' ? 'selected' : '' }}>작성자</option>
                                    <option value="comment_writer" {{ request('search_type') == 'comment_writer' ? 'selected' : '' }}>댓글작성자</option>
                                </select>
                                <input type="text" name="search" placeholder="검색어를 입력하세요" 
                                       class="px-3 py-1.5 border rounded-md flex-1 text-sm" value="{{ request('search') }}">
                                <button type="submit" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
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
</x-app-layout> 