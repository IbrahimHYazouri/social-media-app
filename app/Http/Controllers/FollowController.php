<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\FollowerNotification;
use Illuminate\Support\Facades\Auth;

final class FollowController extends Controller
{
    public function store(User $user)
    {
        /**
         * @var User $authUser
         */
        $authUser = Auth::user();

        $authUser->following()->syncWithoutDetaching($user->id);

        $user->notify(new FollowerNotification($authUser));

        return back();
    }

    public function destroy(User $user)
    {
        /**
         * @var User $authUser
         */
        $authUser = Auth::user();

        $authUser->following()->detach($user->id);

        return back();
    }
}
