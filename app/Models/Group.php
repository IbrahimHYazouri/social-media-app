<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GroupUserRoleEnum;
use App\Enums\GroupUserStatusEnum;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read bool $auto_approval
 * @property-read string $about
 * @property-read int $user_id
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class Group extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'name', 'user_id', 'auto_approval', 'about',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this
            ->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Get avatar thumbnail URL
     */
    public function getAvatarThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('avatar');
    }

    /**
     * Get cover URL
     */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('cover');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function authUserMembership(): HasOne
    {
        return $this->hasOne(GroupUser::class)->where('user_id', Auth::id());
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('status', GroupUserStatusEnum::APPROVED->value)
            ->withPivot(['status', 'role', 'group_id']);
    }

    public function pendingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivotNull('token')
            ->wherePivot('status', GroupUserStatusEnum::PENDING->value);
    }

    public function adminUsers()
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('role', GroupUserRoleEnum::ADMIN->value)
            ->withPivot(['status', 'role', 'group_id', 'owner_id']);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function roleFor(User $user)
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->value('role');
    }

    public function isOwner(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function isAdmin(User $user): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }

        return $this->roleFor($user) === GroupUserRoleEnum::ADMIN->value;
    }
}
