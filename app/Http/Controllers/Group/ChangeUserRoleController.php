<?php

declare(strict_types=1);

namespace App\Http\Controllers\Group;

use App\Enums\GroupUserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Notifications\UserRoleChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class ChangeUserRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Group $group)
    {
        Gate::authorize('changeRole', $group);

        $data = $request->validate([
            'user_id' => ['required'],
            'role' => ['required', Rule::enum(GroupUserRoleEnum::class)],
        ]);

        $groupUser = GroupUser::where([
            'user_id' => $data['user_id'],
            'group_id' => $group->id,
        ])->first();

        if ($groupUser) {
            $groupUser->update(['role' => $data['role']]);

            /**
             * @var User $user
             */
            $user = $groupUser->user;
            $user->notify(new UserRoleChanged($group, $user->id, $data['role']));
        }

        return back();
    }
}
