<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

final class GroupPolicy
{
    public function update(User $user, Group $group): bool
    {
        return $group->isAdmin($user);
    }

    public function approveJoinRequest(User $user, Group $group): bool
    {
        return $group->isAdmin($user);
    }

    public function changeRole(User $user, Group $group): bool
    {
        return $group->isOwner($user);
    }

    public function invite(User $user, Group $group): bool
    {
        return $group->isAdmin($user);
    }

    public function removeMember(User $user, Group $group, User $member): bool
    {
        if ($group->isAdmin($member)) {
            return false;
        }

        if ($group->isOwner($user)) {
            return true;
        }

        if ($group->isAdmin($user) && ! $group->isAdmin($member)) {
            return true;
        }

        return false;
    }
}
