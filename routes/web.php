<?php

declare(strict_types=1);

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\ReactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/images/{user}', [ProfileImageController::class, 'update'])->name('profile.images.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('posts', PostController::class);
    Route::resource('posts.comments', CommentController::class)
        ->only(['store'])
        ->shallow();
    Route::resource('comments', CommentController::class)
        ->only(['update', 'destroy']);

    Route::post('posts/{post}/reactions', ReactionController::class)->name('posts.reactions');
    Route::post('comments/{comment}/reactions', ReactionController::class)->name('comments.reactions');
});

require __DIR__.'/auth.php';
