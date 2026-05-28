<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\News;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
     * Get headline news from cache.
     * TTL: 5 minutes.
     */
    public function getHeadlineNews(): Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.headline_news', 5);

        $result = Cache::remember('headline_news', now()->addMinutes($ttl), function () {
            return News::query()
                ->published()
                ->headline()
                ->with(['category', 'author'])
                ->orderBy('headline_order', 'asc')
                ->limit(3)
                ->get();
        });

        if (!$result instanceof Collection) {
            Cache::forget('headline_news');
            return News::query()
                ->published()
                ->headline()
                ->with(['category', 'author'])
                ->orderBy('headline_order', 'asc')
                ->limit(3)
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
     * Get popular content from all sources (News + Video + Gallery) sorted by views.
     * Returns a unified collection with: type, title, url, views, thumbnail.
     * TTL: 30 minutes.
     */
    public function getPopularAll(int $limit = 10): \Illuminate\Support\Collection
    {
        $ttl = (int) config('news_portal.cache_ttl.popular_news', 30);
        $cacheKey = "popular_all_{$limit}";

        $result = Cache::remember($cacheKey, now()->addMinutes($ttl), function () use ($limit) {
            return $this->buildPopularAll($limit);
        });

        if (!$result instanceof \Illuminate\Support\Collection) {
            Cache::forget($cacheKey);
            return $this->buildPopularAll($limit);
        }

        return $result;
    }

    /**
     * Build the unified popular content collection (News + Video + Gallery).
     */
    private function buildPopularAll(int $limit): \Illuminate\Support\Collection
    {
        // Top news (published, non-lalin)
        $news = News::query()
            ->published()
            ->whereNull('lalin_category')
            ->with(['category'])
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'type'         => 'news',
                    'title'        => $item->title,
                    'url'          => route('news.show', $item->slug),
                    'views'        => $item->views ?? 0,
                    'thumbnail'    => $item->thumbnail ? \Illuminate\Support\Facades\Storage::url($item->thumbnail) : null,
                    'published_at' => $item->published_at,
                    'label'        => $item->category?->name ?? 'Berita',
                ];
            });

        // Top videos (active)
        $videos = \App\Models\Video::query()
            ->active()
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'type'         => 'video',
                    'title'        => $item->title,
                    'url'          => route('video.show', $item->id),
                    'views'        => $item->views ?? 0,
                    'thumbnail'    => $item->display_thumbnail,
                    'published_at' => $item->created_at,
                    'label'        => 'Video',
                ];
            });

        // Top galleries (active) — Potret Kelana Kota
        $galleries = \App\Models\Gallery::query()
            ->active()
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'type'         => 'gallery',
                    'title'        => $item->title,
                    'url'          => route('gallery.show', $item->slug),
                    'views'        => $item->views ?? 0,
                    'thumbnail'    => $item->cover_image ? \Illuminate\Support\Facades\Storage::url($item->cover_image) : null,
                    'published_at' => $item->created_at,
                    'label'        => 'Potret',
                ];
            });

        return $news->concat($videos)->concat($galleries)
            ->sortByDesc('views')
            ->values()
            ->take($limit);
    }

    /**
     * Flush all news-related caches.
     */
    public function flushNewsCache(): void
    {
        Cache::forget('breaking_news');
        Cache::forget('headline_news');
        Cache::forget('featured_news');
        Cache::forget('popular_news');
        Cache::forget('recent_news');
        Cache::forget('popular_all_5');
        Cache::forget('popular_all_10');
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

    /**
     * Get weather data from Open-Meteo API by coordinate.
     * TTL: 30 minutes. Cache key is unique per coordinate.
     *
     * @return array{label: string, temperature: float|null, condition: string, windspeed: float|null, humidity: int|null}
     */
    public function getWeatherByCoordinate(float $lat, float $lng, string $label): array
    {
        $cacheKey = 'weather_' . str_replace(['.', '-'], '_', "{$lat}_{$lng}");

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($lat, $lng, $label) {
            try {
                $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude'           => $lat,
                    'longitude'          => $lng,
                    'current'            => 'temperature_2m,weathercode,windspeed_10m,relativehumidity_2m',
                    'timezone'           => 'Asia/Jakarta',
                    'forecast_days'      => 1,
                ]);

                if (!$response->successful()) {
                    return $this->weatherFallback($label);
                }

                $current = $response->json('current') ?? [];
                $code    = (int) ($current['weathercode'] ?? -1);

                return [
                    'label'       => $label,
                    'temperature' => $current['temperature_2m'] ?? null,
                    'condition'   => $this->mapWeatherCode($code),
                    'windspeed'   => $current['windspeed_10m'] ?? null,
                    'humidity'    => $current['relativehumidity_2m'] ?? null,
                    'code'        => $code,
                ];
            } catch (\Throwable) {
                return $this->weatherFallback($label);
            }
        });
    }

    /**
     * Map Open-Meteo WMO weather code to Indonesian condition label.
     */
    private function mapWeatherCode(int $code): string
    {
        return match (true) {
            $code === 0              => 'Cerah',
            $code >= 1  && $code <= 3  => 'Berawan',
            $code >= 45 && $code <= 48 => 'Berkabut',
            $code >= 51 && $code <= 55 => 'Gerimis',
            $code >= 56 && $code <= 57 => 'Gerimis Beku',
            $code >= 61 && $code <= 65 => 'Hujan Ringan',
            $code >= 66 && $code <= 67 => 'Hujan Lebat',
            $code >= 71 && $code <= 77 => 'Bersalju',
            $code >= 80 && $code <= 82 => 'Hujan Deras',
            $code >= 85 && $code <= 86 => 'Hujan Salju',
            $code >= 95             => 'Badai Petir',
            default                 => 'Tidak Diketahui',
        };
    }

    /**
     * Return a safe fallback when weather API is unavailable.
     */
    private function weatherFallback(string $label): array
    {
        return [
            'label'       => $label,
            'temperature' => null,
            'condition'   => 'Tidak Tersedia',
            'windspeed'   => null,
            'humidity'    => null,
            'code'        => -1,
        ];
    }
}
