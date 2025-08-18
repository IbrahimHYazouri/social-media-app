<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

final class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var Post $post
         */
        $post = $this->resource;
        $comments = $this->comments;

        return [
            'id' => $post->id,
            'body' => $post->body,
            'updated_at' => $post->updated_at->format('Y-m-d H:i:s'),
            'user' => UserResource::make($post->user),
            'num_of_reactions' => $post->reaction_count,
            'user_has_reacted' => $post->hasReactionFromUser(Auth::id()),
            'attachments' => PostAttachmentResource::collection($post->attachments()),
            'group' => GroupResource::make($post->group),
            'comments' => CommentResource::collection($comments),
            'num_of_comments' => count($comments),
            'can' => [
                'update' => Gate::allows('update', $post),
                'delete' => Gate::allows('delete', $post),
            ],
        ];
    }
}
