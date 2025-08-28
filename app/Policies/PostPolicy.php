<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;

final class PostPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->id === $post->user_id) {
            return true;
        }

        if ($post->group) {
            $group = $post->group;
            $author = $post->user;

            if ($group->user_id === $user->id) {
                return true;
            }

            if ($group->isAdmin($user) && ! $group->isAdmin($author)) {
                return true;
            }
        }

        return false;
    }

    public function pintToGroup(?User $user, Post $post): bool
    {
        if ($user === null) {
            return false;
        }

        /**
         * @var Group $group
         */
        $group = $post->group;
        if (! $group) {
            return false;
        }

        return $group->isAdmin($user);
    }

    public function pin(?User $user): bool
    {
        return $user !== null;
    }
}
