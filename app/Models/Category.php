<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'order',
    ];

    /**
     * Get the news articles in this category.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    /**
     * Get the count of published news in this category.
     */
    public function getNewsCountAttribute(): int
    {
        return $this->news()->where('status', 'published')->count();
    }

    /**
     * Scope to order categories by their display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
