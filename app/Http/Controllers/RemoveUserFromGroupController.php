<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;

class RemoveUserFromGroupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Group $group)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id']
        ]);

        $groupUser = GroupUser::where('user_id', $data['user_id'])
            ->where('group_id', $group->id)
            ->first();

        if ($groupUser) {
            $groupUser->delete();
        }

        return back();
    }
}
