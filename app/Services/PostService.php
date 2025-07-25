<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function createPostWithAttachments(array $data): Post {
        return DB::transaction(function () use ($data) {
           $post = Post::create($data);

           $this->handleAttachments($post, $data['attachments'] ?? []);

           return $post;
        });
    }

    protected function handleAttachments(Post $post, array $files): void
    {
        foreach ($files as $file) {
            $post->addMedia($file)->toMediaCollection('attachments');
        }
    }
}
