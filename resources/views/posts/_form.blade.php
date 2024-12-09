<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700">제목</label>
    <input type="text" 
           name="title" 
           id="title" 
           value="{{ old('title', $post->title ?? '') }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('title')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="content" class="block text-sm font-medium text-gray-700">내용</label>
    <textarea name="content" 
              id="content" 
              rows="10" 
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $post->content ?? '') }}</textarea>
    @error('content')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex justify-end space-x-2">
    <a href="{{ route('posts.index', $board->identifier) }}" 
       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
        취소
    </a>
    <button type="submit" 
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        {{ $submitButtonText }}
    </button>
</div> 