<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'num_of_reactions' => $this->reactions_count,
            'user_has_reacted' => $this->relationLoaded('reactionByCurrentUser') && $this->reactionByCurrentUser !== null,
            'updated_at' => $comment->updated_at->format('Y-m-d H:i:s'),
            'replies' => self::collection($this->whenLoaded('replies')),
            'num_of_replies' => $this->replies->count(),
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'username' => $comment->user->username,
                'avatar_url' => $comment->user->getAvatarThumbUrlAttribute(),
            ],
        ];
    }
}
