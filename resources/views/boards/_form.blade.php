<div>
    <x-input-label for="identifier" value="게시판아이디" />
    <input 
        id="identifier" 
        name="identifier" 
        type="text" 
        class="mt-1 block w-full max-w-2xl bg-gray-100 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
        value="{{ old('identifier', $board->identifier ?? '') }}"
        required 
        pattern="[a-zA-Z0-9-]+"
        title="영문자, 숫자, 하이픈(-)만 사용할 수 있습니다"
        {{ isset($board->identifier) ? 'readonly' : '' }}
    >
</div>

<div class="mt-1">
    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ isset($board->identifier) ? '게시판아이디는 수정할 수 없습니다.' : '영문자와 숫자, 하이픈(-)만 사용하여 입력해주세요.' }}
    </p>
</div>

@error('identifier')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror

<div>
    <x-input-label for="title" value="게시판 제목" />
    <x-text-input 
        id="title" 
        name="title" 
        type="text" 
        class="mt-1 block w-full max-w-2xl" 
        :value="old('title', $board->title ?? '')"
        required 
    />
    @error('title')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="list_level" value="목록 보기 권한" />
        <x-text-input 
            id="list_level" 
            name="list_level" 
            type="number" 
            min="0" 
            max="100" 
            class="mt-1 block w-24" 
            :value="old('list_level', $board->list_level ?? 0)" 
            required 
        />
        @error('list_level')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-input-label for="read_level" value="읽기 권한" />
        <x-text-input 
            id="read_level" 
            name="read_level" 
            type="number" 
            min="0" 
            max="100" 
            class="mt-1 block w-24" 
            :value="old('read_level', $board->read_level ?? 0)" 
            required 
        />
        @error('read_level')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-input-label for="write_level" value="쓰기 권한" />
        <x-text-input 
            id="write_level" 
            name="write_level" 
            type="number" 
            min="0" 
            max="100" 
            class="mt-1 block w-24" 
            :value="old('write_level', $board->write_level ?? 0)" 
            required 
        />
        @error('write_level')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-input-label for="comment_level" value="댓글 쓰기 권한" />
        <x-text-input 
            id="comment_level" 
            name="comment_level" 
            type="number" 
            min="0" 
            max="100" 
            class="mt-1 block w-24" 
            :value="old('comment_level', $board->comment_level ?? 0)" 
            required 
        />
        <x-input-error :messages="$errors->get('comment_level')" class="mt-2" />
    </div>

    <div class="col-span-2">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            모든 권한 레벨은 0부터 100 사이의 숫자만 입력 가능합니다.
        </p>
    </div>
</div>

<div class="flex items-center justify-end mt-4">
    <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
        취소
    </x-secondary-button>
    <x-primary-button>
        {{ $submitButtonText }}
    </x-primary-button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // identifier 입력 필드 찾기
        const identifierInput = document.getElementById('identifier');
        // 자동 포커스 설정
        if (identifierInput) {
            identifierInput.focus();
        }
    });
</script> 