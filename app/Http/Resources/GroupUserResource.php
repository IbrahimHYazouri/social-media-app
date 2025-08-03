<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class GroupUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->pivot->role,
            'status' => $this->pivot->status,
            'group_id' => $this->pivot->group_id,
            'username' => $this->username,
            'avatar_url' => $this->avatar_path,
        ];
    }
}
