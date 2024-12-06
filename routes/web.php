<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\BoardManageController;

Route::get('/', function () {
    return view('main');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

// 게시판 목록 (비회원 접근 가능)
Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');

// 전체 게시물 목록 (비회원 접근 가능)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '인증 링크가 발송되었습니다.');
    })->name('verification.send');
    Route::put('/password', [ProfileController::class, 'password'])->name('password.update');
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '인증 링크를 보냈습니다.');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // 게시판 및 게시물 라우트
    Route::prefix('')->group(function () {
        // 비회원 접근 가능한 라우트
        Route::get('/{name}', [BoardController::class, 'show'])
            ->name('boards.show')
            ->where('name', '[a-zA-Z0-9-_]+');
        Route::get('/{name}/{id}', [PostController::class, 'show'])
            ->name('boards.posts.show');

        // 로그인 필요한 라우트
        Route::middleware('auth')->group(function () {
            Route::get('/{name}/write', [PostController::class, 'create'])->name('boards.posts.create');
            Route::post('/{name}', [PostController::class, 'store'])->name('boards.posts.store');
            Route::get('/{name}/{id}/edit', [PostController::class, 'edit'])->name('boards.posts.edit');
            Route::put('/{name}/{id}', [PostController::class, 'update'])->name('boards.posts.update');
            Route::delete('/{name}/{id}', [PostController::class, 'destroy'])->name('boards.posts.destroy');
            Route::post('/{name}/{id}/vote', [PostController::class, 'vote'])->name('boards.posts.vote');
        });
    });

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{id}', [PostController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    });

    Route::post('/{name}/{id}/vote', [PostController::class, 'vote'])
        ->name('boards.posts.vote')
        ->middleware('auth');
});

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('social.callback');
// Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
// Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('boards', BoardManageController::class);
});

// Admin Routes
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', \App\Http\Middleware\AdminMiddleware::class]
], function () {
    Route::resource('boards', BoardManageController::class);
});

Route::resource('boards', BoardController::class);
Route::resource('boards.posts', PostController::class)->parameters([
    'boards' => 'name'
])->except(['index']);