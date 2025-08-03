<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GroupUserRoleEnum;
use App\Enums\GroupUserStatusEnum;
use App\Http\Requests\JoinGroupRequest;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            $status = GroupUserStatusEnum::APPROVED;
            $message = __('You have joined “:name”', ['name' => $group->name]);
        } else {
            $status = GroupUserStatusEnum::PENDING;
            $message = __('Your request to join “:name” is pending approval', [
                'name' => $group->name,
            ]);
        }

        GroupUser::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'status' => $status,
            'role' => GroupUserRoleEnum::USER,
            'owner_id' => $group->user_id,
        ]);

        return back()->with('success', $message);
    }
}
