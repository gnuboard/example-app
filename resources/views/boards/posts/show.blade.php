@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
                <p class="mt-2 text-sm text-gray-500">
                    작성자: {{ $post->user->name }} | 
                    작성일: {{ $post->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="/{{ $board->name }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    목록으로
                </a>
                @if(auth()->check() && auth()->id() === $post->user_id)
                    <a href="/{{ $board->name }}/{{ $post->id }}/edit" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm">
                        수정
                    </a>
                    <form action="/{{ $board->name }}/{{ $post->id }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md shadow-sm"
                                onclick="return confirm('정말 삭제하시겠습니까?')">
                            삭제
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="prose max-w-none mb-8">
                {!! nl2br(e($post->content)) !!}
            </div>
            
            @auth
            <div class="flex justify-center space-x-8 mt-8 pt-8 border-t">
                <form action="/{{ $board->name }}/{{ $post->id }}/vote" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="is_like" value="1">
                    <button type="submit" class="flex flex-col items-center group">
                        <svg class="w-8 h-8 {{ $post->votes()->where('user_id', auth()->id())->where('is_like', true)->exists() ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                        </svg>
                        <span class="text-sm mt-1">추천 {{ $post->votes()->where('is_like', true)->count() }}</span>
                    </button>
                </form>

                <form action="/{{ $board->name }}/{{ $post->id }}/vote" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="is_like" value="0">
                    <button type="submit" class="flex flex-col items-center group">
                        <svg class="w-8 h-8 {{ $post->votes()->where('user_id', auth()->id())->where('is_like', false)->exists() ? 'text-red-600' : 'text-gray-400 group-hover:text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0010.943 2H5.527a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                        </svg>
                        <span class="text-sm mt-1">비추천 {{ $post->votes()->where('is_like', false)->count() }}</span>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection 