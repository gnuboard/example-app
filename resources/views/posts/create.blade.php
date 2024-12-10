<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-4">새 게시물 작성</h1>

                <form action="{{ route('posts.store', $board->identifier) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @include('posts._form', ['submitButtonText' => '작성하기'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 