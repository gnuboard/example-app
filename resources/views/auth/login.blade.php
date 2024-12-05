@extends('layouts.guest')

@section('title', '로그인')

@section('content')
<div class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-white">로그인</h2>
            
            <form method="POST" action="{{ route('login') }}" autocomplete="off" id="loginForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                        이메일
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" 
                           id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="password">
                        비밀번호
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" 
                           id="password" type="password" name="password" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input id="remember" type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">로그인 상태 유지</span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                            type="submit">
                        로그인
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('register') }}">
                        회원가입
                    </a>
                </div>
            </form>

            <div class="mt-4 flex flex-col space-y-4">
                <a href="{{ route('social.redirect', 'google') }}" 
                   class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded text-center">
                    Google로 로그인
                </a>
                
                <a href="{{ route('social.redirect', 'github') }}"
                   class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-center">
                    GitHub로 로그인
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 페이지 로드 시 저장된 이메일 값이 있으면 복원
    const savedEmail = localStorage.getItem('lastEmail');
    if (savedEmail) {
        document.getElementById('email').value = savedEmail;
    }

    // 폼 제출 시 이메일 값 저장
    document.getElementById('loginForm').addEventListener('submit', function() {
        const emailInput = document.getElementById('email');
        localStorage.setItem('lastEmail', emailInput.value);
    });
});
</script>
@endsection
