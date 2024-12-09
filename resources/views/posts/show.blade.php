<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-4">{{ $post->title }}</h1>
                
                <div class="mb-4 text-sm text-gray-600">
                    <span>작성자: {{ $post->user->name }}</span>
                    <span class="mx-2">|</span>
                    <span>작성일: {{ $post->created_at->format('Y-m-d H:i') }}</span>
                    <span class="mx-2">|</span>
                    <span>조회수: {{ number_format($post->views) }}</span>
                </div>

                <div class="prose max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('posts.index', $board->identifier) }}" 
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
</x-app-layout> 