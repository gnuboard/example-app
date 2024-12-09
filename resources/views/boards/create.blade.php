<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('새 게시판 생성하기') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('boards.store') }}" class="space-y-6" autocomplete="off">
                        @csrf
                        @include('boards._form', ['submitButtonText' => '생성하기'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> auto 