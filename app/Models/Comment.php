<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Reactable;
use App\Traits\HasReactions;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $comment
 * @property-read int $user_id
 * @property-read int $post_id
 * @property-read int $reaction_count
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class Comment extends Model implements Reactable
{
    use HasReactions;

    protected $fillable = ['post_id', 'user_id', 'comment', 'parent_id', 'depth'];

    protected $appends = ['reactions_count'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
