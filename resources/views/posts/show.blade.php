<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
            <div class="p-8">
                <div class="flex justify-between mb-8">
                    @if($previousPost)
                        <a href="{{ route('posts.show', [$board->identifier, $previousPost->id, 'search_type' => request('search_type'), 'search' => request('search')]) }}" 
                           class="flex items-center transition-all duration-200 bg-gray-50 hover:bg-gray-100 px-5 py-2.5 rounded-lg group"
                           title="이전글: {{ $previousPost->title }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>이전글</span>
                        </a>
                    @else
                        <button disabled class="flex items-center bg-gray-50 px-4 py-2 rounded-full cursor-not-allowed" title="이전글이 없습니다">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span class="text-gray-400">이전글</span>
                        </button>
                    @endif

                    @if($nextPost)
                        <a href="{{ route('posts.show', [$board->identifier, $nextPost->id,
                            'search_type' => request('search_type'),
                            'search' => request('search')
                        ]) }}" 
                           class="flex items-center bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full"
                           title="다음글: {{ $nextPost->title }}">
                            <span>다음글</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <button disabled class="flex items-center bg-gray-50 px-4 py-2 rounded-full cursor-not-allowed" title="다음글이 없습니다">
                            <span class="text-gray-400">다음글</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @endif
                </div>

                <div class="border-b border-gray-100 pb-6 mb-6">
                    <h1 class="text-xl font-bold mb-4 text-gray-900">{{ $post->title }}</h1>
                    
                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ $post->user->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $post->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>{{ number_format($post->view_count) }}</span>
                        </div>
                    </div>
                </div>

                <div class="prose prose-lg max-w-none mb-8">
                    {!! $post->content !!}
                </div>

                @if($post->attachments->count() > 0)
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">첨부파일</h4>
                        <ul class="space-y-3">
                            @foreach($post->attachments as $attachment)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <a href="{{ route('attachments.download', $attachment) }}" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $attachment->original_filename }}
                                        <span class="text-sm text-gray-500">({{ number_format($attachment->file_size / 1024, 2) }} KB)</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-8">
                    <h3 class="text-base font-medium mb-4">
                        댓글 ({{ $post->comments_count }})
                    </h3>
                    <x-comment-list :post="$post" :comments="$comments" />
                </div>

                <div class="flex justify-between pt-6 border-t border-gray-100">
                    <a href="{{ route('posts.index', ['identifier' => $board->identifier, 'search_type' => request('search_type'), 'search' => request('search'), 'page' => request('page')]) }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        목록으로
                    </a>
                    
                    @if(auth()->id() === $post->user_id)
                        <div class="space-x-3">
                            <a href="{{ route('posts.edit', [$board->identifier, $post->id]) }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-xs font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                수정
                            </a>
                            <form action="{{ route('posts.destroy', [$board->identifier, $post->id]) }}" 
                                  method="POST" 
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-xs font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                        onclick="return confirm('정말 삭제하시겠습니까?')">
                                    삭제
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 