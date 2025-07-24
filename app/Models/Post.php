<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $body
 * @property-read int $user_id
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class Post extends Model
{
    protected $fillable = [
        'body',
        'user_id',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
