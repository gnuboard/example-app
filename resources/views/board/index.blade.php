@extends('layouts.app')

@section('content')
<div class="container">
    <h1>게시판 목록</h1>
    
    <div class="row">
        @foreach($boards as $board)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $board->title }}</h5>
                        <p class="card-text">{{ $board->description }}</p>
                        <a href="{{ route('boards.list', $board->name) }}" class="btn btn-primary">게시판 보기</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 