<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $data = $request->validated();
        $data['post_id'] = $post->id;
        $data['user_id'] = $user->id;

        $depth = 0;
        if (! empty($data['parent_id'])) {
            $parent = Comment::findOrFail($data['parent_id']);
            $depth = $parent->depth + 1;

            if ($depth > 2) {
                return response()->json([
                    'message' => 'Sub-comments cannot be deeper than 2 levels.',
                ], 422);
            }
        }
        $data['depth'] = $depth;

        $comment = Comment::create($data);

        return response(CommentResource::make($comment), 201);
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return CommentResource::make($comment);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->noContent();
    }
}
