<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileImageRequest;
use App\Models\User;

final class ProfileImageController extends Controller
{
    public function update(UpdateProfileImageRequest $request, User $user)
    {
        if ($request->hasFile('avatar_path')) {
            $newAvatar = $request->file('avatar_path')->getClientOriginalName();
            $oldAvatar = $user->getFirstMedia('avatar');

            if (! $oldAvatar || $oldAvatar !== $newAvatar) {
                $user->clearMediaCollection('avatar');
                $user
                    ->addMediaFromRequest('avatar_path')
                    ->toMediaCollection('avatar', 'public');
            }
        }

        if ($request->hasFile('cover_path')) {
            $newCover = $request->file('cover_path')->getClientOriginalName();

            $oldCover = $user->getFirstMedia('cover');
            if (! $oldCover || $oldCover->file_name !== $newCover) {
                $user->clearMediaCollection('cover');
                $user->addMediaFromRequest('cover_path')->toMediaCollection('cover', 'public');
            }
        }
    }
}
