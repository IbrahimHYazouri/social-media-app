<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
        //
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->postService->createPostWithAttachments($request->validated());

        return redirect()->route('dashboard')->with('success', 'Post created');
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);
        $this->postService->updatePostWithAttachments($post, $request->validated());

        return redirect()->route('dashboard')->with('success', 'Post updated');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);
        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post deleted');
    }
}
