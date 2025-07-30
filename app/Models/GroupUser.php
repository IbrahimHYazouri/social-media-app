<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GroupUserRoleEnum;
use App\Enums\GroupUserStatusEnum;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $status
 * @property-read string $role
 * @property-read string $token
 * @property-read DateTimeInterface $token_expires_at
 * @property-read DateTimeInterface $token_used
 * @property-read int $owner_id
 * @property-read int $user_id
 * @property-read int $group_id
 * @property-read DateTimeInterface $created_at
 */
final class GroupUser extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'status', 'role', 'owner_id', 'user_id', 'group_id', 'token', 'token_expires_at',
    ];

    protected $casts = [
        'status' => GroupUserStatusEnum::class,
        'role' => GroupUserRoleEnum::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
