<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\GroupUserRoleEnum;
use App\Enums\GroupUserStatusEnum;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class GroupService
{
    public function createGroupWithAdmin(array $data, int $userId): Group
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['user_id'] = $userId;
            $group = Group::create($data);

            $groupUser = GroupUser::create([
                'status' => GroupUserStatusEnum::APPROVED,
                'role' => GroupUserRoleEnum::ADMIN,
                'user_id' => $userId,
                'group_id' => $group->id,
                'owner_id' => $group->user_id,
                'created_by' => Auth::id()
            ]);

            $group->setRelation('pivot', $groupUser);

            return $group;
        });
    }
}
