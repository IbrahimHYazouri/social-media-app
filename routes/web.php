<?php

declare(strict_types=1);

use App\Http\Controllers\ApproveJoinRequestController;
use App\Http\Controllers\ChangeUserRoleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteUserController;
use App\Http\Controllers\JoinGroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostAttachmentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\RemoveUserFromGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/images/{user}', ProfileImageController::class)->name('profile.images.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/attachments/{media}/download', [PostAttachmentController::class, 'download'])->name('attachments.download');

    Route::resource('posts', PostController::class);
    Route::resource('posts.comments', CommentController::class)
        ->only(['store'])
        ->shallow();
    Route::resource('comments', CommentController::class)
        ->only(['update', 'destroy']);

    Route::post('posts/{post}/reactions', ReactionController::class)->name('posts.reactions');
    Route::post('comments/{comment}/reactions', ReactionController::class)->name('comments.reactions');

    Route::resource('groups', GroupController::class);
    Route::patch('/groups/images/{group}', GroupImageController::class)->name('groups.images.update');

    Route::prefix('groups/{group}')
        ->name('groups.')
        ->group(function () {
            Route::post('join', JoinGroupController::class)->name('join');
            Route::post('invite', InviteUserController::class)->name('invite');
            Route::post('approve', ApproveJoinRequestController::class)->name('approve');
            Route::patch('change-role', ChangeUserRoleController::class)->name('change-role');
            Route::delete('users', RemoveUserFromGroupController::class)->name('users.remove');
        });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
});

require __DIR__.'/auth.php';
