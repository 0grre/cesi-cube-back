<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RelationRequest extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'status',
        'first_user_id',
        'second_user_id',
    ];

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
