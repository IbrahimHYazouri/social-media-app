<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GroupUserRoleEnum;
use App\Enums\GroupUserStatusEnum;
use App\Http\Requests\InviteUserRequest;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class InviteUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(InviteUserRequest $request, Group $group): RedirectResponse
    {
        $invitee = $request->getInvitee();
        optional($request->getExistingPivot())->delete();

        $hours = 24;
        $token = Str::random(255);

        GroupUser::create([
            'group_id' => $group->id,
            'user_id' => $invitee->id,
            'status' => GroupUserStatusEnum::PENDING,
            'role' => GroupUserRoleEnum::USER,
            'token' => $token,
            'token_expires_at' => now()->addHours($hours),
            'owner_id' => Auth::id(),
        ]);

        // TODO handle notifying the invited user

        return back()->with('success',
            __('Invitation send to :email', [
                'email' => $invitee->email,
            ]),
        );
    }
}
