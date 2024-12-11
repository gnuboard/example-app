<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between mb-6">
                    @if($previousPost)
                        <a href="{{ route('posts.show', [$board->identifier, $previousPost->id, 
                            'search_type' => request('search_type'),
                            'search' => request('search')
                        ]) }}" 
                           class="flex items-center bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full"
                           title="이전글: {{ $previousPost->title }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

                <h1 class="text-2xl font-bold mb-4">{{ $post->title }}</h1>
                
                <div class="mb-4 text-sm text-gray-600">
                    <span>작성자: {{ $post->user->name }}</span>
                    <span class="mx-2">|</span>
                    <span>작성일: {{ $post->created_at->format('Y-m-d H:i') }}</span>
                    <span class="mx-2">|</span>
                    <span>조회수: {{ number_format($post->view_count) }}</span>
                </div>

                <div class="prose max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>

                @if($post->attachments->count() > 0)
                    <div class="attachments mt-6">
                        <h4 class="font-bold mb-2">첨부파일</h4>
                        <ul class="space-y-2">
                            @foreach($post->attachments as $attachment)
                                <li>
                                    @if(Str::startsWith($attachment->mime_type, 'image/'))
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($attachment->file_path) }}" 
                                                 alt="{{ $attachment->original_filename }}"
                                                 class="max-w-lg rounded shadow-sm">
                                        </div>
                                    @endif
                                    <a href="{{ route('attachments.download', $attachment) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        {{ $attachment->original_filename }}
                                        ({{ number_format($attachment->file_size / 1024, 2) }} KB)
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-6">
                    <div class="flex justify-between">
                        <a href="{{ route('posts.index', [
                            'identifier' => $board->identifier,
                            'search_type' => request('search_type'),
                            'search' => request('search'),
                            'page' => request('page')
                        ]) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            목록으로
                        </a>
                        
                        @if(auth()->id() === $post->user_id)
                            <div class="space-x-2">
                                <a href="{{ route('posts.edit', [$board->identifier, $post->id]) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                    수정
                                </a>
                                <form action="{{ route('posts.destroy', [$board->identifier, $post->id]) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
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
    </div>
</x-app-layout> 