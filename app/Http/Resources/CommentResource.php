<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'updated_at' => $comment->updated_at->format('Y-m-d H:i:s'),
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'username' => $comment->user->username,
                'avatar_url' => $comment->user->getAvatarThumbUrlAttribute()
            ]
        ];
    }
}
