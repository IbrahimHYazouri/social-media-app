<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupUserResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class GroupController extends Controller
{
    public function __construct(protected GroupService $groupService)
    {
        //
    }

    public function show(Group $group): Response
    {
        $group->load('authUserMembership');
        $group->load('postAttachments');
        $group->load('posts');

        if ($group->authUserMembership) {
            $group->setRelation('pivot', $group->authUserMembership);
        }

        $pending = $group->pendingUsers()->get();
        $group->load('members');

        return Inertia::render('Group/Show', [
            'group' => GroupResource::make($group),
            'posts' => PostResource::collection($group->posts),
            'users' => GroupUserResource::collection($group->members),
            'pending' => UserResource::collection($pending),
            'allowed_attachment_extensions' => StorePostRequest::$extensions,
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();

        $group = $this->groupService->createGroupWithAdmin($data, $userId);

        return response(GroupResource::make($group), 201);
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        Gate::authorize('update', $group);

        $group->update($request->validated());

        return redirect()->route('groups.show', $group);
    }
}
