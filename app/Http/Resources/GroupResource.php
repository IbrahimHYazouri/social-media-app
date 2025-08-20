<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\GroupUserStatusEnum;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

final class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var Group $group
         */
        $group = $this->resource;
        $role = $group->pivot?->role;
        $status = $group->pivot?->status;
        $isOwner = $group->user_id === Auth::id();
        $isMember = ! is_null($role) && $status === GroupUserStatusEnum::APPROVED->value;

        return [
            'id' => $group->id,
            'name' => $group->name,
            'slug' => $group->slug,
            'auto_approval' => $group->auto_approval,
            'about' => $group->about,
            'user_id' => $group->user_id,
            'role' => $group->pivot?->role,
            'status' => $group->pivot?->status,
            'attachments' => PostAttachmentResource::collection(
                $this->whenLoaded('postAttachments'),
            ),
            'cover_url' => $group->getCoverUrlAttribute(),
            'avatar_url' => $group->getAvatarThumbUrlAttribute(),
            'created_at' => $group->created_at,
            'updated_at' => $group->updated_at,
            'can' => [
                'manage' => $isOwner,
                'participate' => $isMember,
                'join' => Auth::check() && ! $isMember,
            ],
        ];
    }
}
