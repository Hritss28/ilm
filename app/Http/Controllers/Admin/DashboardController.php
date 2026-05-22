<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\News;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Total counts
        $totalNews = News::published()->count();
        $totalUsers = User::where('is_active', true)->count();
        $totalAds = Advertisement::active()->count();
        $totalGalleries = Gallery::active()->count();
        $totalCategories = Category::count();
        $totalVideos = Video::where('is_active', true)->count();

        // 10 most recent news articles (with category, author)
        $recentNews = News::with(['category', 'author'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // 10 most popular news articles in last 7 days (sorted by views, with category, author)
        $popularNews = News::with(['category', 'author'])
            ->published()
            ->where('published_at', '>=', Carbon::now()->subDays(7))
            ->orderByDesc('views')
            ->take(10)
            ->get();

        // Daily view stats for last 30 days (for Chart.js)
        $dailyStats = News::published()
            ->where('published_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(published_at) as date'),
                DB::raw('SUM(views) as total_views'),
                DB::raw('COUNT(*) as total_articles')
            )
            ->groupBy(DB::raw('DATE(published_at)'))
            ->orderBy('date')
            ->get();

        // Fill in missing dates with zero values
        $chartLabels = [];
        $chartViews = [];
        $chartArticles = [];
        $statsMap = $dailyStats->keyBy('date');

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('d M');
            $chartViews[] = isset($statsMap[$date]) ? (int) $statsMap[$date]->total_views : 0;
            $chartArticles[] = isset($statsMap[$date]) ? (int) $statsMap[$date]->total_articles : 0;
        }

        // Category stats for bar chart
        $categoryStats = Category::withCount(['news' => function ($q) {
            $q->published();
        }])->orderByDesc('news_count')->take(10)->get();

        // Total views
        $totalViews = News::published()->sum('views');

        // If redaktur role, show redaktur dashboard
        if ($user->isRedaktur()) {
            return view('admin.dashboard-redaktur', compact(
                'totalNews', 'totalViews', 'totalUsers', 'recentNews', 'popularNews',
                'chartLabels', 'chartViews', 'categoryStats'
            ));
        }

        return view('admin.dashboard', compact(
            'totalNews',
            'totalUsers',
            'totalAds',
            'totalGalleries',
            'totalCategories',
            'totalVideos',
            'recentNews',
            'popularNews',
            'chartLabels',
            'chartViews',
            'chartArticles'
        ));
    }
}
