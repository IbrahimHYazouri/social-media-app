<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property-read int $id
 * @property-read string $comment
 * @property-read int $user_id
 * @property-read int $post_id
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'comment', 'parent_id', 'depth'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable', 'object_type', 'object_id');
    }

    public function reactionByCurrentUser(): HasOne
    {
        return $this->hasOne(Reaction::class, 'object_id')
            ->where('object_type', self::class)
            ->where('user_id', Auth::id());
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')->with('user', 'replies');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
