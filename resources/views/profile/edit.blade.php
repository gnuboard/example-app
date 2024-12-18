@extends('layouts.app')

@section('title', '프로필 정보')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    프로필 정보 수정
                </h2>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">이름</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">이메일</label>
                        <p class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 p-2">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" style="background-color: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: bold;">
                            수정하기
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
