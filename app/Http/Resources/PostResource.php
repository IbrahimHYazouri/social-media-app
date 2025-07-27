<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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

        return [
            'id' => $post->id,
            'body' => $post->body,
            'updated_at' => $post->updated_at->format('Y-m-d H:i:s'),
            'user' => UserResource::make($post->user),
            'num_of_reactions' => $this->reactions_count,
            'user_has_reaction' => $this->reactions_count > 0,
            'attachments' => PostAttachmentResource::collection($post->attachments()),
        ];
    }
}
