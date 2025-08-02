<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GroupUserStatusEnum;
use App\Http\Requests\ApproveJoinRequest;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;

final class ApproveJoinRequestController extends Controller
{
    public function __invoke(ApproveJoinRequest $request, Group $group): RedirectResponse
    {
        $groupUser = $request->getGroupUser();
        $approved = $request->validated()['action'] === 'approve';

        if ($approved) {
            $groupUser->update(['status' => GroupUserStatusEnum::APPROVED]);
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
