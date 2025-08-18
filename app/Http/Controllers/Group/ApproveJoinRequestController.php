<?php

declare(strict_types=1);

namespace App\Http\Controllers\Group;

use App\Enums\GroupUserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveJoinRequest;
use App\Models\Group;
use App\Models\User;
use App\Notifications\JoinRequestApproved;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class ApproveJoinRequestController extends Controller
{
    public function __invoke(ApproveJoinRequest $request, Group $group): RedirectResponse
    {
        Gate::authorize('approveJoinRequest', $group);

        $groupUser = $request->getGroupUser();
        $approved = $request->validated()['action'] === 'approve';

        if ($approved) {
            $groupUser->update(['status' => GroupUserStatusEnum::APPROVED]);

            /**
             * @var User $user
             */
            $user = $groupUser->user;
            $user->notify(new JoinRequestApproved($group, $user->id));
        } else {
            $groupUser->delete();
        }

        $verb = $approved ? 'approved' : 'rejected';

        return back()->with('success',
            __("User “:name” has been {$verb}.", [
                'name' => $groupUser->user->name,
            ])
        );
    }
}
