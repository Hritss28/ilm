<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    /**
     * Display a single news article.
     * View counter is handled by IncrementViewCount middleware.
     */
    public function show(string $slug): View
    {
        $article = News::query()
            ->published()
            ->where('slug', $slug)
            ->with(['category', 'author'])
            ->firstOrFail();

        // Related news: same category, exclude current, limit 4
        $relatedNews = News::query()
            ->published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        $seo = $this->seoService->generateForNews($article);

        return view('public.news.show', compact('article', 'relatedNews', 'seo'));
    }

    /**
     * Display paginated news list filtered by category slug.
     */
    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $articles = News::query()
            ->published()
            ->where('category_id', $category->id)
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->paginate(config('news_portal.pagination.news_per_page', 15));

        $seo = $this->seoService->generateForCategory($category);

        return view('public.news.category', compact('category', 'articles', 'seo'));
    }

    /**
     * Search news by title and content with pagination.
     */
    public function search(Request $request): View
    {
        $query = $request->input('q', '');

        $newsQuery = News::query()
            ->published()
            ->whereDoesntHave('category', fn($q) => $q->where('slug', 'lalu-lintas'))
            ->with(['category', 'author']);

        // Text search filter
        if (strlen(trim($query)) >= 2) {
            $newsQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            });
        }

        // Date filter
        if ($request->filled('year')) {
            $newsQuery->whereYear('published_at', $request->input('year'));
        }
        if ($request->filled('month')) {
            $newsQuery->whereMonth('published_at', $request->input('month'));
        }
        if ($request->filled('day')) {
            $newsQuery->whereDay('published_at', $request->input('day'));
        }

        $articles = $newsQuery->orderByDesc('published_at')
            ->paginate(config('news_portal.pagination.news_per_page', 15))
            ->appends($request->query());

        $seo = $this->seoService->generateForPage(
            $query ? "Pencarian: {$query}" : 'Semua Berita',
            $query ? "Hasil pencarian untuk '{$query}'" : 'Daftar semua berita terbaru'
        );

        return view('public.search', compact('articles', 'query', 'seo'));
    }

    /**
     * Display Info Lalin page with timeline layout.
     */
    public function infoLalin(): View
    {
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        // Get lalu lintas news grouped by date
        $todayNews = \App\Models\InfoLalin::query()
            ->published()
            ->whereDate('incident_date', $today)
            ->with('author')
            ->orderByDesc('start_time')
            ->get();

        $yesterdayNews = \App\Models\InfoLalin::query()
            ->published()
            ->whereDate('incident_date', $yesterday)
            ->with('author')
            ->orderByDesc('start_time')
            ->get();

        $weekNews = \App\Models\InfoLalin::query()
            ->published()
            ->where('incident_date', '>=', now()->subDays(7)->format('Y-m-d'))
            ->with('author')
            ->orderByDesc('incident_date')
            ->orderByDesc('start_time')
            ->get();

        $seo = $this->seoService->generateForPage('Info Lalin', 'Informasi lalu lintas terkini wilayah Mojokerto');

        return view('public.infolalin', compact('todayNews', 'yesterdayNews', 'weekNews', 'seo'));
    }
}
