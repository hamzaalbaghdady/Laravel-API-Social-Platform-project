<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::apiResource('profiles', ProfileController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('posts.comments', CommentController::class);
    Route::controller(ReactionController::class)->group(function () {
        Route::post('posts/{post}/reactions', 'react')->name('posts.reactions');
        Route::delete('posts/{post}/reactions/{reaction}', 'unreact')->name('posts.reactions.unreact');
        Route::post('comments/{comment}/reactions', 'react')->name(name: 'comments.reactions');
        Route::delete('comments/{comment}/reactions/{reaction}', 'unreact')
            ->name(name: 'comments.reactions.unreact');
    });
    Route::Post('follow/{id}', [FollowController::class, 'follow'])->name('follow');
    Route::delete('unfollow/{id}', [FollowController::class, 'unfollow'])->name('unfollow');
    Route::apiResource('users', UserController::class)->only([
        'update',
        'destroy',
        'show',
        'index',
    ]);
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
