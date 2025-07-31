<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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

        return [
            'id' => $group->id,
            'name' => $group->name,
            'slug' => $group->slug,
            'auto_approval' => $group->auto_approval,
            'about' => $group->about,
            'user_id' => $group->user_id,
            'role' => $group->pivot?->role,
            'status' => $group->pivot?->status,
            'created_at' => $group->created_at,
            'updated_at' => $group->updated_at,
        ];
    }
}
