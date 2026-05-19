<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Indicates that the model should not use updated_at timestamp.
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
    ];

    /**
     * Get the user who performed this action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
