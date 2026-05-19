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
        $articles = collect();

        if (strlen(trim($query)) >= 2) {
            $articles = News::query()
                ->published()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%");
                })
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->paginate(config('news_portal.pagination.news_per_page', 15))
                ->appends(['q' => $query]);
        }

        $seo = $this->seoService->generateForPage("Pencarian: {$query}", "Hasil pencarian untuk '{$query}'");

        return view('public.search', compact('articles', 'query', 'seo'));
    }

    /**
     * Display Info Lalin page with timeline layout.
     */
    public function infoLalin(): View
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        // Get lalu lintas news grouped by date
        $todayNews = News::query()
            ->published()
            ->byCategory('lalu-lintas')
            ->whereDate('published_at', $today)
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->get();

        $yesterdayNews = News::query()
            ->published()
            ->byCategory('lalu-lintas')
            ->whereDate('published_at', $yesterday)
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->get();

        $weekNews = News::query()
            ->published()
            ->byCategory('lalu-lintas')
            ->where('published_at', '>=', now()->subDays(7))
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->get();

        $seo = $this->seoService->generateForPage('Info Lalin', 'Informasi lalu lintas terkini wilayah Mojokerto');

        return view('public.infolalin', compact('todayNews', 'yesterdayNews', 'weekNews', 'seo'));
    }
}
