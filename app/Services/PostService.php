<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class PostService
{
    public function createPostWithAttachments(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $post = Post::create($data);

            $this->handleAttachments($post, $data['attachments'] ?? []);

            return $post;
        });
    }

    public function updatePostWithAttachments(Post $post, array $data)
    {
        return DB::transaction(function () use ($post, $data) {
            if (! empty($data['deleted_attachment_ids'])) {
                foreach ($data['deleted_attachment_ids'] as $mediaId) {
                    $media = $post->attachments()->find($mediaId);
                    if ($media) {
                        $media->delete();
                    }
                }
            }

            $this->handleAttachments($post, $data['attachments'] ?? []);

            $post->update(
                Arr::except($data, ['attachments', 'deleted_attachments_ids'])
            );

            return $post;
        });
    }

    private function handleAttachments(Post $post, array $files): void
    {
        foreach ($files as $file) {
            $post->addMedia($file)->toMediaCollection('attachments');
        }
    }
}
