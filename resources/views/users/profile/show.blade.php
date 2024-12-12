<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- 프로필 정보 -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-4">회원정보</h2>
                    <div class="space-y-4">
                        <div>
                            <span class="font-medium">이름:</span>
                            <span>{{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium">레벨:</span>
                            <span>{{ $user->level }} 레벨</span>
                        </div>
                        <div>
                            <span class="font-medium">가입일:</span>
                            <span>{{ $user->created_at->format('Y-m-d') }}</span>
                        </div>
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
</x-app-layout> 