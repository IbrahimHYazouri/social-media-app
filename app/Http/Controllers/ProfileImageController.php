<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileImageRequest;
use App\Models\User;
use App\Services\MediaUploadService;

final class ProfileImageController extends Controller
{
    public function __invoke(UpdateProfileImageRequest $request, User $user, MediaUploadService $uploader): void
    {
        if ($file = $request->file('avatar_path')) {
            $uploader->upload($user, 'avatar', $file);
        }

        if ($file = $request->file('cover_path')) {
            $uploader->upload($user, 'cover', $file);
        }
    }
}
