<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GroupUserRoleEnum;
use App\Enums\GroupUserStatusEnum;
use App\Http\Requests\JoinGroupRequest;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Notifications\GroupJoined;
use App\Notifications\RequestToJoinGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class JoinGroupController extends Controller
{
    /**
     * Handle incoming request
     */
    public function __invoke(JoinGroupRequest $request, Group $group): RedirectResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if ($group->auto_approval) {
            GroupUser::create([
                'group_id' => $group->id,
                'user_id' => $user->id,
                'status' => GroupUserStatusEnum::APPROVED->value,
                'role' => GroupUserRoleEnum::USER->value,
                'owner_id' => $group->user_id,
                'created_by' => $user->id,
            ]);

            $user->notify(new GroupJoined($group));
        } else {
            GroupUser::create([
                'group_id' => $group->id,
                'user_id' => $user->id,
                'status' => GroupUserStatusEnum::PENDING->value,
                'role' => GroupUserRoleEnum::USER->value,
                'owner_id' => $group->user_id,
                'created_by' => $user->id,
            ]);

            $group->adminUsers->each->notify(new RequestToJoinGroup($group, $user->name));
        }

        return back();
    }
}
