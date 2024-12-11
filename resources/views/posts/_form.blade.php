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
        <label for="attachments" class="block text-sm font-medium text-gray-700">첨���파일</label>
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

    <div class="flex justify-end space-x-3">
        <a href="{{ route('posts.index', ['identifier' => $board->identifier]) }}" 
           class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            취소
        </a>
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
    
    fileList.className = 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6';
    fileList.innerHTML = '';
    
    Array.from(input.files).forEach((file, index) => {
        const li = document.createElement('li');
        li.className = 'group relative bg-white rounded-xl p-2 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1';
        
        const extension = file.name.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(extension);
        
        let previewHtml = '';
        if (isImage) {
            const imageUrl = URL.createObjectURL(file);
            previewHtml = `
                <div class="aspect-w-1 aspect-h-1 w-full mb-3 overflow-hidden rounded-lg">
                    <img src="${imageUrl}" 
                         alt="${file.name}" 
                         class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-300"
                         onload="URL.revokeObjectURL(this.src)">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
            `;
        } else {
            let iconColor = 'text-gray-400';
            let bgColor = 'bg-gray-50';
            if (['pdf'].includes(extension)) {
                iconColor = 'text-rose-500';
                bgColor = 'bg-rose-50';
            } else if (['doc', 'docx'].includes(extension)) {
                iconColor = 'text-blue-500';
                bgColor = 'bg-blue-50';
            } else if (['xls', 'xlsx'].includes(extension)) {
                iconColor = 'text-emerald-500';
                bgColor = 'bg-emerald-50';
            }
            
            previewHtml = `
                <div class="aspect-w-1 aspect-h-1 w-full mb-3 ${bgColor} rounded-lg overflow-hidden">
                    <div class="flex justify-center items-center p-4 group-hover:scale-105 transition-transform duration-300">
                        <svg class="h-16 w-16 ${iconColor}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 21h10a3 3 0 003-3V8a1 1 0 00-.293-.707l-4-4A1 1 0 0015 3H7a3 3 0 00-3 3v12a3 3 0 003 3zm0-2a1 1 0 01-1-1V6a1 1 0 011-1h7v4a1 1 0 001 1h4v8a1 1 0 01-1 1H7z"/>
                        </svg>
                    </div>
                </div>
            `;
        }
        
        li.innerHTML = `
            ${previewHtml}
            <div class="space-y-1 px-1">
                <p class="text-sm font-medium text-gray-900 truncate group-hover:text-indigo-600 transition-colors duration-200">
                    ${file.name}
                </p>
                <p class="text-xs text-gray-500 font-medium">
                    ${(file.size / 1024).toFixed(2)} KB
                </p>
            </div>
            <button type="button" 
                    onclick="removeFile(${index})" 
                    class="absolute top-3 right-3 p-1.5 rounded-full bg-white/90 backdrop-blur-sm shadow-sm opacity-0 group-hover:opacity-100 hover:bg-red-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <svg class="h-4 w-4 text-gray-500 hover:text-red-600 transition-colors duration-200" 
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        fileList.appendChild(li);
    });
}

function removeFile(index) {
    const newDataTransfer = new DataTransfer();
    const files = Array.from(globalFiles.files);
    
    // 선택된 인덱스를 제외한 모든 파일을 새 DataTransfer에 추가
    files.forEach((file, i) => {
        if (i !== index) {
            newDataTransfer.items.add(file);
        }
    });
    
    // globalFiles 업데이트
    globalFiles = newDataTransfer;
    
    // input 파일 목록 업데이트
    const input = document.getElementById('attachments');
    input.files = globalFiles.files;
    
    // 파일 목록 UI 업데이트
    updateFileList();
}
</script> 