@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-white mb-4">이메일 인증</h2>
        
        @if (session('message'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('message') }}
            </div>
        @endif
        
        <p class="text-white mb-4">
            계정을 사용하기 전에 이메일 주소를 인증해 주세요.
            인증 이메일을 받지 못하셨다면 아래 버튼을 클릭하여 재발송 할 수 있습니다.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" style="background-color: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: bold; width: 100%; display: block;">
                인증 이메일 재발송
            </button>
        </form>
    </div>
</div>
@endsection
