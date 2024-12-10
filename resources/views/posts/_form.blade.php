<div class="space-y-4">
    <div>
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

    <div>
        <label for="content" class="block text-sm font-medium text-gray-700">내용</label>
        <textarea name="content" 
                  id="content" 
                  rows="10" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $post->content ?? '') }}</textarea>
        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="attachments" class="block text-sm font-medium text-gray-700">첨부파일</label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
             id="dropzone"
             ondrop="handleDrop(event)"
             ondragover="handleDragOver(event)"
             ondragleave="handleDragLeave(event)">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex flex-col items-center space-y-2">
                    <label for="attachments" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md cursor-pointer hover:bg-indigo-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <span>파일 선택하기</span>
                        <input type="file" 
                               name="attachments[]" 
                               id="attachments" 
                               multiple 
                               class="sr-only"
                               onchange="handleFileSelect(this)">
                    </label>
                    <p class="text-sm text-gray-500">또는 여기로 파일을 끌어다 놓으세요</p>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF, PDF 최대 10MB</p>
                </div>
            </div>
        </div>
        <ul id="fileList" class="mt-2 space-y-2"></ul>
        @error('attachments.*')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @if(isset($post) && $post->attachments->count() > 0)
        <div>
            <h3 class="text-sm font-medium text-gray-700">현재 첨부된 파일</h3>
            <ul class="mt-2 space-y-2">
                @foreach($post->attachments as $attachment)
                    <li class="flex items-center space-x-2">
                        <input type="checkbox" 
                               name="delete_attachments[]" 
                               value="{{ $attachment->id }}" 
                               id="delete_attachment_{{ $attachment->id }}"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <label for="delete_attachment_{{ $attachment->id }}" class="text-sm text-gray-600">
                            {{ $attachment->original_filename }}
                            ({{ number_format($attachment->file_size / 1024, 2) }} KB)
                        </label>
                    </li>
                @endforeach
            </ul>
            <p class="mt-1 text-sm text-gray-500">삭제할 파일을 선택하세요</p>
        </div>
    @endif

    <div class="flex justify-end">
        <button type="submit" 
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            {{ $submitButtonText }}
        </button>
    </div>
</div> 

<script>
function handleDragOver(event) {
    event.preventDefault();
    event.currentTarget.classList.add('border-indigo-500', 'bg-indigo-50');
}

function handleDragLeave(event) {
    event.preventDefault();
    event.currentTarget.classList.remove('border-indigo-500', 'bg-indigo-50');
}

// 전역 변수로 파일 목록 관리
let globalFiles = new DataTransfer();

function handleFileSelect(input) {
    const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
    const newFiles = input.files;
    
    // 파일 크기 체크
    for (let file of newFiles) {
        if (file.size > maxFileSize) {
            alert('파일 크기는 10MB를 초과할 수 없습니다.');
            return;
        }
    }
    
    const existingFiles = new Set(Array.from(globalFiles.files).map(f => f.name));
    
    // 새로 선택된 파일들을 중복 체크 후 추가
    Array.from(newFiles).forEach(file => {
        if (!existingFiles.has(file.name)) {
            globalFiles.items.add(file);
        }
    });
    
    // 최종 파일 목록을 input에 설정
    input.files = globalFiles.files;
    updateFileList();
}

function handleDrop(event) {
    event.preventDefault();
    event.currentTarget.classList.remove('border-indigo-500', 'bg-indigo-50');
    
    const files = event.dataTransfer.files;
    const input = document.getElementById('attachments');
    
    const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
    
    // 파일 크기 체크
    for (let file of files) {
        if (file.size > maxFileSize) {
            alert('파일 크기는 10MB를 초과할 수 없습니다.');
            return;
        }
    }
    
    const existingFiles = new Set(Array.from(globalFiles.files).map(f => f.name));
    
    // 새 파일들을 중복 체크 후 추가
    Array.from(files).forEach(file => {
        if (!existingFiles.has(file.name)) {
            globalFiles.items.add(file);
        }
    });
    
    input.files = globalFiles.files;
    updateFileList();
}

function updateFileList() {
    const fileList = document.getElementById('fileList');
    const input = document.getElementById('attachments');
    
    fileList.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const li = document.createElement('li');
        li.className = 'flex items-center space-x-2 text-sm text-gray-600';
        li.innerHTML = `
            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd" />
            </svg>
            <span>${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
        `;
        fileList.appendChild(li);
    });
}
</script> 