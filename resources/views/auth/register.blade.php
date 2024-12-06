@extends('layouts.guest')

@section('content')
<div class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-white">회원가입</h2>
            
            <form method="POST" action="{{ route('register') }}" autocomplete="off" id="registerForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">
                        이름
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                           id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="off">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                        이메일
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" 
                           id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="off">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="password">
                        비밀번호
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" 
                           id="password" type="password" name="password" required autocomplete="off">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="password_confirmation">
                        비밀번호 확인
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="password_confirmation" type="password" name="password_confirmation" required autocomplete="off">
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                            type="submit" id="registerButton">
                        회원가입
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('login') }}">
                        로그인으로 돌아가기
                    </a>
                </div>
            </form>

            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <p class="text-center text-gray-600 dark:text-gray-400 mb-4">또는 소셜 계정으로 회원가입</p>
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('social.redirect', 'google') }}" 
                       class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded text-center">
                        Google로 회원가입
                    </a>
                    
                    <a href="{{ route('social.redirect', 'github') }}"
                       class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-center">
                        GitHub로 회원가입
                    </a>

                    <a href="{{ route('social.redirect', 'kakao') }}"
                        class="bg-[#FEE500] hover:bg-[#FDD000] text-black font-bold py-2 px-4 rounded text-center">
                            <span>카카오로 회원가입</span>
                    </a>
                </div>
            </div>

            <p class="mt-8 text-sm text-center text-gray-600">
                이미 계정이 있으신가요?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    로그인하기
                </a>
            </p>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    if (this.submitted) {
        e.preventDefault();
        return;
    }
    this.submitted = true;
    const button = document.getElementById('registerButton');
    button.disabled = true;
    button.innerHTML = '처리중...';
    button.style.backgroundColor = '#9CA3AF';
    button.style.cursor = 'not-allowed';
    button.classList.remove('hover:bg-blue-700'); // hover 효과 제거
});
</script>
@endsection
