<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class News extends Model
{
    use HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'thumbnail',
        'category_id',
        'author_id',
        'status',
        'is_featured',
        'is_breaking_news',
        'breaking_news_until',
        'views',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'firebase_id',
        'lalin_category',
        'lalin_status',
        'lalin_estimated_end',
        'lalin_alternative_route',
        'lalin_location',
        'lalin_source',
        'lalin_contact',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_breaking_news' => 'boolean',
            'breaking_news_until' => 'datetime',
            'published_at' => 'datetime',
            'lalin_estimated_end' => 'datetime',
            'views' => 'integer',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the category that owns the news article.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the author of the news article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope a query to only include published news.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include featured news.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include active breaking news.
     */
    public function scopeBreakingNews(Builder $query): Builder
    {
        return $query->where('is_breaking_news', true)
            ->where(function ($q) {
                $q->whereNull('breaking_news_until')
                    ->orWhere('breaking_news_until', '>=', now());
            });
    }

    /**
     * Scope a query to filter news by category slug.
     */
    public function scopeByCategory(Builder $query, string $slug): Builder
    {
        return $query->whereHas('category', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    /**
     * Get the full URL for the thumbnail image.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        return Storage::url($this->thumbnail);
    }

    /**
     * Get the excerpt, auto-generating from content if null.
     */
    public function getExcerptAttribute(?string $value): string
    {
        if ($value) {
            return $value;
        }

        $plainText = strip_tags($this->attributes['content'] ?? '');

        return \Illuminate\Support\Str::limit($plainText, 200);
    }
}
