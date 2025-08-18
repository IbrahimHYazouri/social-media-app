<?php

declare(strict_types=1);

namespace App\Http\Controllers\Group;

use App\Enums\GroupUserRoleEnum;
use App\Enums\GroupUserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteUserRequest;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Notifications\InvitationAccepted;
use App\Notifications\InvitationToGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;

final class GroupInvitationController extends Controller
{
    public function show(string $token)
    {
        /**
         * @var GroupUser $groupUser
         */
        $groupUser = GroupUser::query()
            ->where('token', $token)
            ->first();

        if (! $groupUser) {
            return response()->json([
                'message' => 'This invitation token is invalid.',
            ], 404);
        }

        return Inertia::render('Group/GroupInvitationAccept', [
            'group' => [
                'name' => $groupUser->group->name,
                'id' => $groupUser->group->id,
            ],
            'invitee' => [
                'id' => $groupUser->creator->id,
                'name' => $groupUser->creator->name,
                'username' => $groupUser->creator->username,
            ],
            'token' => $groupUser->token,
        ]);
    }

    public function invite(InviteUserRequest $request, Group $group): RedirectResponse
    {
        Gate::authorize('invite', $group);

        $invitee = $request->getInvitee();
        optional($request->getExistingPivot())->delete();

        $hours = 24;
        $token = Str::random(255);

        $groupUser = GroupUser::create([
            'group_id' => $group->id,
            'user_id' => $invitee->id,
            'status' => GroupUserStatusEnum::PENDING,
            'role' => GroupUserRoleEnum::USER,
            'token' => $token,
            'token_expires_at' => now()->addHours($hours),
            'owner_id' => $group->user_id,
            'created_by' => Auth::id(),
        ]);

        $invitee->notify(new InvitationToGroup($group, $groupUser, $hours));

        return back()->with('success',
            __('Invitation send to :email', [
                'email' => $invitee->email,
            ]),
        );
    }

    public function accept(string $token)
    {
        /**
         * @var GroupUser $groupUser
         */
        $groupUser = GroupUser::where('token', $token)->first();

        if (! $groupUser) {
            return response()->json([
                'message' => 'The invitation link is not valid.',
            ], 404);
        }

        if ($groupUser->token_used || $groupUser->status === GroupUserStatusEnum::APPROVED->value) {
            return response()->json([
                'message' => 'The invitation link has already been used.',
            ], 410);
        }

        if ($groupUser->token_expires_at < now()) {
            return response()->json([
                'message' => 'The invitation link has expired.',
            ], 410);
        }

        $groupUser->update([
            'status' => GroupUserStatusEnum::APPROVED,
            'token_used' => now(),
        ]);

        /**
         * @var User $invitee
         */
        $invitee = $groupUser->creator;
        $invitee?->notify(new InvitationAccepted($groupUser));

        return response()->json([
            'message' => 'You have successfully joined the group.',
            'redirect' => route('groups.show', $groupUser->group),
        ]);
    }
}
