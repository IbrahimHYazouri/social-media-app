<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Reaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReactions
{
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable', 'object_type', 'object_id');
    }

    public function toggleReactionForUser(int $userId, string $type): bool
    {
        $existingReaction = $this->reactions()->where('user_id', $userId)->first();

        if ($existingReaction) {
            $existingReaction->delete();

            return false;
        }

        $this->reactions()->create([
            'user_id' => $userId,
            'type' => $type,
        ]);

        return true;
    }

    public function hasReactionFromUser(int $userId): bool
    {
        return $this->reactions()->where('user_id', $userId)->exists();
    }

    public function getReactionCountAttribute(): int
    {
        return $this->reactions()->count();
    }
}
