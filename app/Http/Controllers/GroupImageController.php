<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGroupImageRequest;
use App\Models\Group;
use App\Services\MediaUploadService;

final class GroupImageController extends Controller
{
    public function __invoke(UpdateGroupImageRequest $request, Group $group, MediaUploadService $uploader): void
    {
        if ($file = $request->file('avatar_path')) {
            $uploader->upload($group, 'avatar', $file);
        }

        if ($file = $request->file('cover_path')) {
            $uploader->upload($group, 'cover', $file);
        }
    }
}
