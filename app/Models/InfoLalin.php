<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfoLalin extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'incident_date',
        'start_time',
        'estimated_end_time',
        'status',
        'title',
        'description',
        'alternative_route',
        'location',
        'source',
        'author_id',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'start_time' => 'datetime:H:i',
        'estimated_end_time' => 'datetime:H:i',
    ];

    /**
     * Get the author of the info lalin.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope a query to only include active info lalin.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Masih aktif');
    }

    /**
     * Get published info lalin (excluding drafts).
     */
    public function scopePublished($query)
    {
        return $query->whereIn('status', ['Masih aktif', 'Sudah selesai']);
    }
}
