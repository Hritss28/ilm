<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\CacheService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected CacheService $cacheService
    ) {}

    /**
     * Display the homepage with featured news, breaking news,
     * news by category, sidebar data, and active ads.
     */
    public function index(): View
    {
        $headlineNews = $this->cacheService->getHeadlineNews();
        $featuredNews = $this->cacheService->getFeaturedNews();
        $breakingNews = $this->cacheService->getBreakingNews();

        // Ads
        $topAds = $this->cacheService->getActiveAds('top');
        $contentAds = $this->cacheService->getActiveAds('content');

        // News by category for homepage grid
        $categorySlugs = ['regional', 'nasional', 'politik', 'ekonomi', 'olahraga'];
        $categoryNews = [];

        foreach ($categorySlugs as $slug) {
            $news = News::query()
                ->published()
                ->byCategory($slug)
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(4)
                ->get();

            $categoryName = $news->first()?->category?->name ?? ucfirst(str_replace('-', ' ', $slug));

            $categoryNews[$slug] = [
                'name' => $categoryName,
                'news' => $news,
            ];
        }

        $seo = [
            'title' => config('news_portal.site.name', 'Info Lantas Mojokerto'),
            'description' => config('news_portal.site.description', ''),
            'keywords' => config('news_portal.seo.default_keywords', ''),
            'og_type' => 'website',
            'canonical' => route('home'),
        ];

        return view('public.home', compact(
            'headlineNews',
            'featuredNews',
            'breakingNews',
            'topAds',
            'contentAds',
            'categoryNews',
            'seo'
        ));
    }
}
