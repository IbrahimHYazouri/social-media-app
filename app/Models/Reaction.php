<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Reaction extends Model
{
    protected $fillable = ['user_id', 'post_id', 'type'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
