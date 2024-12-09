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
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">게시판 정보</h3>
                        <div class="mt-2">
                            <p>아이디: {{ $board->identifier }}</p>
                            <p>제목: {{ $board->title }}</p>
                            <p>카테고리: {{ $board->category }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">권한 설정</h3>
                        <div class="mt-2">
                            <p>목록 보기 권한: {{ $board->list_level }}</p>
                            <p>읽기 권한: {{ $board->read_level }}</p>
                            <p>쓰기 권한: {{ $board->write_level }}</p>
                            <p>댓글 권한: {{ $board->comment_level }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center space-x-4">
                        <a href="{{ route('admin') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            목록으로
                        </a>
                        
                        <a href="{{ route('boards.edit', $board->id) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            수정
                        </a>
                        
                        <form action="{{ route('boards.destroy', $board->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('정말 삭제하시겠습니까?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                삭제
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 