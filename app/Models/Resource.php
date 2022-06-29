<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'views',
        'richTextContent',
        'mediaUrl',
        'status',
        'scope',
        'type_id',
        'category_id',
        'user_id',
        'deleted_at',
    ];

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return BelongsToMany
     */
    public function read_later(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'read_later')
            ->using(ReadLater::class)
            ->as('read_later')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->using(ReadLater::class)
            ->as('read_later')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function exploited(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'exploited')
            ->using(ReadLater::class)
            ->as('read_later')->withTimestamps();
    }
}
