<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

final class CommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($user->id === $comment->user_id) {
            return true;
        }

        if ($comment->post->group) {
            $group = $comment->post->group;
            $author = $comment->user;

            if ($group->owner_id === $user->id) {
                return true;
            }

            if ($group->isAdmin($user) && ! $group->isAdmin($author)) {
                return true;
            }
        }

        return false;
    }
}
