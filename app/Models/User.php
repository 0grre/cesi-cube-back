<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Omalizadeh\QueryFilter\Traits\HasFilter;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $email
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Searchable, HasFilter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'avatar',
        'firstname',
        'lastname',
        'address1',
        'address2',
        'zipCode',
        'city',
        'primaryPhone',
        'secondaryPhone',
        'birthDate',
        'disabled_at',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function searchableAs(): string
    {
        return 'users_index';
    }

    /**
     * @return mixed
     */
    public function getScoutKey(): mixed
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getScoutKeyName(): string
    {
        return 'id';
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'id' => "mixed",
        'email' => "string",
        'firstname' => "mixed",
        'lastname' => "mixed",
        'city' => "mixed",
        'createdAt' => "mixed",
        'updatedAt' => "mixed",
        'deletedAt' => "mixed",
    ])]
    #[SearchUsingPrefix(['email', 'firstname', 'lastname', 'city'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'city' => $this->city,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->disabled_at,
        ];
    }

    /**
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return $this->hasRole('citizen') && !$this->disabled_at;
    }

    /**
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return Collection
     */
    public function relations(): Collection
    {
        $relations = collect($this->hasMany(Relation::class, 'first_user_id')->get());
        return $relations->merge(collect($this->hasMany(Relation::class, 'second_user_id')->get()));
    }

    /**
     * @return Collection
     */
    public function relation_requests(): Collection
    {
        $relations = collect($this->hasMany(RelationRequest::class, 'first_user_id')->get());
        return $relations->merge(collect($this->hasMany(Relation::class, 'second_user_id')->get()));
    }

    /**
     * @return BelongsToMany
     */
    public function read_later(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'read_later')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'favorites')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function exploited(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'exploited')->withTimestamps();
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }
}
