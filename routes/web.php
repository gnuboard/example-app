<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('main');
});

Route::get('/admin', function () {
    $boards = \App\Models\Board::all();
    return view('admin.index', compact('boards'));
})->middleware(['auth', 'verified'])->name('admin');

Route::get('/start', function () {
    return view('start');
})->name('start');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'password'])->name('password.update');
    
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '인증 링크를 보냈습니다.');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');// Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
// Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

Route::resource('boards', BoardController::class);
// Route::put('/boards/{id}', [BoardController::class, 'update'])->name('boards.update');

// Route::get('/boards/{id}', function($id) {
//     dd('라우트 테스트', $id);
// })->name('boards.show');

// Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
// Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create');
// Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');
// Route::get('/boards/{board}/edit', [BoardController::class, 'edit'])->name('boards.edit');
// Route::put('/boards/{board}', [BoardController::class, 'update'])->name('boards.update');
// Route::delete('/boards/{board}', [BoardController::class, 'destroy'])->name('boards.destroy');

// Route::get('/{identifier}', [BoardController::class, 'showByIdentifier'])
//     ->name('boards.showByIdentifier')
//     ->where('identifier', '^(?!admin|boards|login|register|profile).*$');

Route::get('/attachments/{post}', [AttachmentController::class, 'show'])->name('attachments.show');
Route::get('/attachments/{post}/download', [AttachmentController::class, 'download'])->name('attachments.download');

// Route::get('/{identifier}/create', [PostController::class, 'create'])->name('posts.create');
// Route::post('/{identifier}', [PostController::class, 'store'])->name('posts.store');
// Route::get('/{identifier}/{id}', [PostController::class, 'show'])->name('posts.show');
// Route::get('/{identifier}/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
// Route::put('/{identifier}/{id}', [PostController::class, 'update'])->name('posts.update');
// Route::delete('/{identifier}/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

// Route::get('/{identifier}', [PostController::class, 'index'])
//     ->name('posts.index')
//     ->where('identifier', '^(?!admin|boards|login|register|profile).*$');

Route::where([
    'identifier' => '^(?!admin|boards|login|register|profile|public).*$',
    'id' => '[0-9]+'
])->group(function () {
    Route::get('/{identifier}/get-page', [PostController::class, 'getPage'])->name('posts.get-page');
    Route::get('/{identifier}/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/{identifier}', [PostController::class, 'store'])->name('posts.store');
    Route::get('/{identifier}/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/{identifier}/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/{identifier}/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/{identifier}/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/{identifier}', [PostController::class, 'index'])->name('posts.index');
});
