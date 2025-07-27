<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property-read int $object_id
 * @property-read string $object_type
 * @property-read int $user_id
 * @property-read string $type
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class Reaction extends Model
{
    protected $fillable = ['user_id', 'object_id', 'object_type', 'type'];

    public function reactable(): MorphTo
    {
        return $this->morphTo(
            'reactable',
            'object_type',
            'object_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
