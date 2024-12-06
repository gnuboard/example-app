<!-- resources/views/main.blade.php -->
@extends('layouts.guest')

@section('title', '환영합니다')

@section('content')
<div class="bg-white dark:bg-gray-900">
    <!-- Hero Section -->
    <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
        </div>
        
        <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                    더 나은 협업을 위한 플랫폼
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    팀과 함께 성장하세요. 실시간 협업, 프로젝트 관리, 그리고 더 많은 기능을 경험해보세요.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        대시보드
                    </a>
                    <a href="{{ route('boards.index') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        게시판
                    </a>
                    <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">
                        로그인 <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gradient Background -->
        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
            <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"></div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600 dark:text-indigo-400">빠른 성장</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    모든 것이 더 쉬워집니다
                </p>
                <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    직관적인 인터페이스와 강력한 기능으로 팀의 생산성을 높여보세요.
                </p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                            <svg class="h-5 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            실시간 협업
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-300">
                            <p class="flex-auto">팀원들과 실시간으로 소통하고 협업하세요. 언제 어디서나 함께할 수 있습니다.</p>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                            <svg class="h-5 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                            </svg>
                            자동화된 워크플로우
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-300">
                            <p class="flex-auto">반복적인 작업을 자동화하여 더 중요한 일에 집중하세요.</p>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900 dark:text-white">
                            <svg class="h-5 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4.632 3.533A2 2 0 016.577 2h6.846a2 2 0 011.945 1.533l1.976 8.234A3.489 3.489 0 0016 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234z" />
                                <path fill-rule="evenodd" d="M4 13a2 2 0 100 4h12a2 2 0 100-4H4zm11.24 2a.75.75 0 01.75-.75H16a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75h-.01a.75.75 0 01-.75-.75V15zm-2.25-.75a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75h-.01z" clip-rule="evenodd" />
                            </svg>
                            강력한 분석
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-300">
                            <p class="flex-auto">데이터 기반의 의사결정을 위한 상세한 분석과 리포트를 제공합니다.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-white dark:bg-gray-900">
        <div class="mx-auto max-w-7xl py-24 sm:px-6 sm:py-32 lg:px-8">
            <div class="relative isolate overflow-hidden bg-gray-900 px-6 py-24 text-center shadow-2xl sm:rounded-3xl sm:px-16">
                <h2 class="mx-auto max-w-2xl text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    지금 바로 시작하세요
                </h2>
                <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-300">
                    무료로 시작하고, 팀의 규모에 맞춰 확장하세요.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('register') }}" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        무료로 시작하기
                    </a>
                    <a href="#" class="text-sm font-semibold leading-6 text-white">
                        더 알아보기 <span aria-hidden="true">→</span>
                    </a>
                </div>
                <svg viewBox="0 0 1024 1024" class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2 -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]">
                    <circle cx="512" cy="512" r="512" fill="url(#gradient)" fill-opacity="0.7" />
                    <defs>
                        <radialGradient id="gradient">
                            <stop stop-color="#7775D6" />
                            <stop offset="1" stop-color="#E935C1" />
                        </radialGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection