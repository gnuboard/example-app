@extends('layouts.guest')

@section('title', '시작하기')

@section('content')
<div class="flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-4xl">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                    시작하기
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            주요 기능
                        </h2>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-400">
                            <li>손쉬운 프로젝트 관리</li>
                            <li>실시간 협업 기능</li>
                            <li>강력한 분석 도구</li>
                            <li>커스터마이징 가능한 대시보드</li>
                        </ul>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            시작하는 방법
                        </h2>
                        <ol class="list-decimal list-inside space-y-2 text-gray-600 dark:text-gray-400">
                            <li>회원가입을 완료하세요</li>
                            <li>프로젝트를 생성하세요</li>
                            <li>팀원을 초대하세요</li>
                            <li>작업을 시작하세요</li>
                        </ol>
                    </div>
                </div>

                <div class="mt-8 flex justify-center space-x-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        회원가입
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        로그인
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 