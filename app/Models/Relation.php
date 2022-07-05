<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Relation extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'is_accepted',
        'relation_type_id',
        'first_user_id',
        'second_user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function relation_type(): BelongsTo
    {
        return $this->belongsTo(RelationType::class);
    }

    /**
     * @return BelongsTo
     */
    public function first_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_user_id');
    }

    /**
     * @return BelongsTo
     */
    public function second_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_user_id');
    }
}
