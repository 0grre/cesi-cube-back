<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
        return $this->belongsToMany(Resource::class, 'read_later')
            ->using(ReadLater::class)
            ->as('read_later')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'favorites')
            ->using(Favorite::class)
            ->as('favorites')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function exploited(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'exploited')
            ->using(Exploited::class)
            ->as('exploited')->withTimestamps();
    }
}
