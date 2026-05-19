<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\News;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Get breaking news from cache.
     * TTL: 5 minutes.
     */
    public function getBreakingNews(): Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.breaking_news', 5);

        $result = Cache::remember('breaking_news', now()->addMinutes($ttl), function () {
            return News::query()
                ->published()
                ->breakingNews()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(config('news_portal.breaking_news.max_display', 5))
                ->get();
        });

        if (!$result instanceof Collection) {
            Cache::forget('breaking_news');
            return News::query()
                ->published()
                ->breakingNews()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(config('news_portal.breaking_news.max_display', 5))
                ->get();
        }

        return $result;
    }

    /**
     * Get featured news from cache.
     * TTL: 10 minutes.
     */
    public function getFeaturedNews(): Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.featured_news', 10);

        $result = Cache::remember('featured_news', now()->addMinutes($ttl), function () {
            return News::query()
                ->published()
                ->featured()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(5)
                ->get();
        });

        if (!$result instanceof Collection) {
            Cache::forget('featured_news');
            return News::query()
                ->published()
                ->featured()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(5)
                ->get();
        }

        return $result;
    }

    /**
     * Get popular news from cache (sorted by views, last 7 days).
     * TTL: 30 minutes.
     */
    public function getPopularNews(): Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.popular_news', 30);

        $result = Cache::remember('popular_news', now()->addMinutes($ttl), function () {
            return News::query()
                ->published()
                ->where('published_at', '>=', now()->subDays(7))
                ->with(['category', 'author'])
                ->orderByDesc('views')
                ->limit(10)
                ->get();
        });

        if (!$result instanceof Collection) {
            Cache::forget('popular_news');
            return News::query()
                ->published()
                ->where('published_at', '>=', now()->subDays(7))
                ->with(['category', 'author'])
                ->orderByDesc('views')
                ->limit(10)
                ->get();
        }

        return $result;
    }

    /**
     * Get recent news from cache.
     * TTL: 10 minutes.
     */
    public function getRecentNews(): Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.recent_news', 10);

        $result = Cache::remember('recent_news', now()->addMinutes($ttl), function () {
            return News::query()
                ->published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(10)
                ->get();
        });

        if (!$result instanceof Collection) {
            Cache::forget('recent_news');
            return News::query()
                ->published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(10)
                ->get();
        }

        return $result;
    }

    /**
     * Get active advertisements for a specific position from cache.
     * TTL: 15 minutes.
     */
    public function getActiveAds(string $position): Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.advertisements', 15);

        $result = Cache::remember("ads_{$position}", now()->addMinutes($ttl), function () use ($position) {
            return Advertisement::query()
                ->active()
                ->byPosition($position)
                ->orderByDesc('priority')
                ->get();
        });

        if (!$result instanceof Collection) {
            Cache::forget("ads_{$position}");
            return Advertisement::query()
                ->active()
                ->byPosition($position)
                ->orderByDesc('priority')
                ->get();
        }

        return $result;
    }

    /**
     * Get categories menu from cache.
     * TTL: 60 minutes.
     */
    public function getCategoriesMenu(): Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.categories', 60);

        $result = Cache::remember('categories_menu', now()->addMinutes($ttl), function () {
            return Category::query()
                ->ordered()
                ->get();
        });

        if (!$result instanceof Collection) {
            Cache::forget('categories_menu');
            return Category::query()
                ->ordered()
                ->get();
        }

        return $result;
    }

    /**
     * Flush all news-related caches.
     */
    public function flushNewsCache(): void
    {
        Cache::forget('breaking_news');
        Cache::forget('featured_news');
        Cache::forget('popular_news');
        Cache::forget('recent_news');
    }

    /**
     * Flush advertisement caches.
     * If position is provided, only flush that position's cache.
     * Otherwise flush all known positions.
     */
    public function flushAdsCache(?string $position = null): void
    {
        if ($position) {
            Cache::forget("ads_{$position}");
        } else {
            $positions = ['top', 'sidebar', 'content', 'footer'];
            foreach ($positions as $pos) {
                Cache::forget("ads_{$pos}");
            }
        }
    }

    /**
     * Flush categories menu cache.
     */
    public function flushCategoriesCache(): void
    {
        Cache::forget('categories_menu');
    }
}
