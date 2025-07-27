<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $comment
 * @property-read int $user_id
 * @property-read int $post_id
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'comment'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
