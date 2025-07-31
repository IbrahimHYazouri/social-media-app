<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Resources\GroupResource;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;

final class GroupController extends Controller
{
    public function __construct(protected GroupService $groupService)
    {
        //
    }

    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();

        $group = $this->groupService->createGroupWithAdmin($data, $userId);

        return response(GroupResource::make($group), 201);
    }
}
