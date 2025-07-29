<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Reactable
{
    public function reactions(): MorphMany;

    public function toggleReactionForUser(int $userId, string $type): bool;

    public function hasReactionFromUser(int $userId): bool;

    public function getReactionCountAttribute(): int;
}
