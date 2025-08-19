<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $email
 * @property-read DateTimeInterface|null $email_verified_at
 * @property-read string $username
 * @property-read string $password
 * @property-read string $remember_token
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasSlug, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('username')
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

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_users')
            ->withPivot(['status', 'role', 'owner_id']);
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'follower_id', 'followee_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'followee_id', 'follower_id');
    }

    public function isFollowing(self $user): bool
    {
        return $this->following()->where('followee_id', $user->id)->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
