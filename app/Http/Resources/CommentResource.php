<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

final class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var Comment $comment
         */
        $comment = $this->resource;

        return [
            'id' => $comment->id,
            'comment' => $comment->comment,
            'post_id' => $comment->post_id,
            'user_id' => $comment->user_id,
            'depth' => $comment->depth,
            'num_of_reactions' => $comment->reaction_count,
            'user_has_reacted' => $comment->hasReactionFromUser(Auth::id()),
            'updated_at' => $comment->updated_at->format('Y-m-d H:i:s'),
            'replies' => self::collection($this->whenLoaded('replies')),
            'num_of_replies' => $this->replies->count(),
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'username' => $comment->user->username,
                'avatar_url' => $comment->user->getAvatarThumbUrlAttribute(),
            ],
            'can' => [
                'update' => Gate::allows('update', $comment),
                'delete' => Gate::allows('delete', $comment),
            ],
        ];
    }
}
