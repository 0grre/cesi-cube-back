<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RelationType extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function relations(): HasMany
    {
        return $this->hasMany(Relation::class);
    }

    /**
     * @return BelongsToMany
     */
    public function shared(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'shared')->withTimestamps();
    }
}
