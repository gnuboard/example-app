@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">게시판 목록</h1>
            @auth
                <a href="{{ route('boards.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    새 게시판 만들기
                </a>
            @endauth
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($boards as $board)
                <li>
                    <a href="/{{ $board->name }}" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-medium text-indigo-600">{{ $board->title }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $board->level >= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            Lv.{{ $board->level }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ $board->description }}</p>
                                </div>
                                <div class="ml-4">
                                    <span class="text-sm text-gray-500">/{{ $board->name }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection 