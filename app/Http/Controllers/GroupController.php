<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupUserResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
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

        if ($group->authUserMembership) {
            $group->setRelation('pivot', $group->authUserMembership);
        }

        $pending = $group->pendingUsers()->get();

        return Inertia::render('Group/Show', [
            'group' => GroupResource::make($group),
            'pending' => UserResource::collection($pending)
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();

        $group = $this->groupService->createGroupWithAdmin($data, $userId);

        return response(GroupResource::make($group), 201);
    }
}
