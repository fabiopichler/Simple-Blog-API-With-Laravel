<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('signin', [AuthController::class, 'login'])->name('auth.login');
    Route::post('signout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('signup', [AuthController::class, 'signup'])->name('auth.signup');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
    Route::get('user', [AuthController::class, 'user'])->name('auth.user');
});

Route::group(['prefix' => 'users'], function ($router) {
    Route::get('{username}', [UserController::class, 'show'])->name('users.show')->where(['username' => '[a-zA-Z0-9-_]+']);
});

Route::group(['prefix' => 'posts'], function ($router) {
    Route::get('', [PostController::class, 'index'])->name('posts.index');
    Route::get('search', [PostController::class, 'search'])->name('posts.search');
    Route::get('last', [PostController::class, 'lastPosts'])->name('posts.last');
    Route::get('{postname}', [PostController::class, 'show'])->name('posts.show')->where(['postname' => '[a-z0-9-]+']);
    Route::post('', [PostController::class, 'store'])->name('posts.store');
});

Route::group(['prefix' => 'comments'], function ($router) {
    Route::post('', [CommentController::class, 'store'])->name('comments.store');
});
