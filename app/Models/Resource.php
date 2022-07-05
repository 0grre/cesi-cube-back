<?php

namespace App\Models;

use App\Http\Resources\ClassifyResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Omalizadeh\QueryFilter\Traits\HasFilter;

class Resource extends Model
{
    use HasFactory, HasFilter, Searchable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'views',
        'richTextContent',
        'mediaUrl',
        'mediaLink',
        'status',
        'type_id',
        'category_id',
        'user_id',
        'deleted_at',
    ];

    /**
     * @return string
     */
    public function searchableAs(): string
    {
        return 'resources_index';
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
        'title' => "mixed",
        'richTextContent' => "mixed",
        'createdAt' => "mixed",
        'updatedAt' => "mixed",
        'deletedAt' => "mixed",
    ])]
    #[SearchUsingPrefix(['title', 'richTextContent'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'richTextContent' => $this->richTextContent,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
        ];
    }

    /**
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status == 'accepted' && $this->is_public() && !$this->deleted_at;
    }

    /**
     * @return bool
     */
    public function is_public(): bool
    {
        return empty($this->shared()->first());
    }

    /**
     * @return bool
     */
    public function relation_exist(): bool
    {
        return DB::table('relations')->whereIn('first_user_id', [$this->user_id, Auth::user()->getAuthIdentifier()])
            ->whereIn('second_user_id', [$this->user_id, Auth::user()->getAuthIdentifier()])
            ->exists();
    }

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
    public function shared(): BelongsToMany
    {
        return $this->belongsToMany(RelationType::class, 'shared')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function read_later(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'read_later')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function exploited(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'exploited')->withTimestamps();
    }
}
