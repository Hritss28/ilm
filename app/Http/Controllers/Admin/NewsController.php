<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Models\Category;
use App\Models\News;
use App\Services\CacheService;
use App\Services\ContentSanitizer;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(
        protected ImageService $imageService,
        protected CacheService $cacheService,
        protected ContentSanitizer $contentSanitizer,
    ) {}

    /**
     * Display a listing of news articles with filters.
     */
    public function index(Request $request): View
    {
        $query = News::with(['category', 'author']);

        // Filter by category (by id or slug)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Special sorting for breaking news and featured views
        if ($request->filled('breaking')) {
            $query->orderByDesc('is_breaking_news');
        } elseif ($request->filled('featured')) {
            $query->orderByDesc('is_featured');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by owner (mine)
        if ($request->filled('mine')) {
            $query->where('author_id', $request->user()->id);
        }

        $news = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::ordered()->get();

        return view('admin.news.index', compact('news', 'categories'));
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create(): View
    {
        $categories = Category::ordered()->get();

        return view('admin.news.create', compact('categories'));
    }

    /**
     * Store a newly created news article.
     */
    public function store(StoreNewsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Sanitize rich text content
        $data['content'] = $this->contentSanitizer->sanitize($data['content']);

        // Handle lalin category custom input
        if (isset($data['lalin_category']) && $data['lalin_category'] === 'Lainnya' && $request->filled('lalin_category_custom')) {
            $data['lalin_category'] = $request->input('lalin_category_custom');
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->imageService->uploadThumbnail(
                $request->file('thumbnail'),
                'thumbnails'
            );
        }

        // Set author
        $data['author_id'] = $request->user()->id;

        // Set published_at: use tanggal_kejadian + waktu_kejadian if provided (info lalin), otherwise now()
        if ($data['status'] === 'published') {
            if ($request->filled('tanggal_kejadian') && $request->filled('waktu_kejadian')) {
                $data['published_at'] = \Carbon\Carbon::parse($request->input('tanggal_kejadian') . ' ' . $request->input('waktu_kejadian'));
            } else {
                $data['published_at'] = now();
            }
        }

        // Handle boolean fields
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_breaking_news'] = $request->boolean('is_breaking_news');

        // If breaking news, set expiry
        if ($data['is_breaking_news']) {
            $data['breaking_news_until'] = now()->addHours(24);
        }

        News::create($data);

        $this->cacheService->flushNewsCache();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit(News $news): View
    {
        Gate::authorize('update', $news);

        $categories = Category::ordered()->get();

        return view('admin.news.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified news article.
     */
    public function update(StoreNewsRequest $request, News $news): RedirectResponse
    {
        Gate::authorize('update', $news);

        $data = $request->validated();

        // Sanitize rich text content
        $data['content'] = $this->contentSanitizer->sanitize($data['content']);

        // Handle lalin category custom input
        if (isset($data['lalin_category']) && $data['lalin_category'] === 'Lainnya' && $request->filled('lalin_category_custom')) {
            $data['lalin_category'] = $request->input('lalin_category_custom');
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($news->thumbnail) {
                $this->imageService->delete($news->thumbnail);
            }
            $data['thumbnail'] = $this->imageService->uploadThumbnail(
                $request->file('thumbnail'),
                'thumbnails'
            );
        }

        // Set published_at if status changed to published, or use tanggal/waktu kejadian
        if ($request->filled('tanggal_kejadian') && $request->filled('waktu_kejadian')) {
            $data['published_at'] = \Carbon\Carbon::parse($request->input('tanggal_kejadian') . ' ' . $request->input('waktu_kejadian'));
        } elseif ($data['status'] === 'published' && $news->status !== 'published') {
            $data['published_at'] = now();
        }

        // Handle boolean fields
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_breaking_news'] = $request->boolean('is_breaking_news');

        $news->update($data);

        $this->cacheService->flushNewsCache();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified news article.
     */
    public function destroy(News $news): RedirectResponse
    {
        Gate::authorize('delete', $news);

        // Delete thumbnail if exists
        if ($news->thumbnail) {
            $this->imageService->delete($news->thumbnail);
        }

        $news->delete();

        $this->cacheService->flushNewsCache();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Toggle breaking news status.
     */
    public function toggleBreakingNews(News $news): RedirectResponse
    {
        Gate::authorize('toggleBreaking', News::class);

        if ($news->is_breaking_news) {
            $news->update([
                'is_breaking_news' => false,
                'breaking_news_until' => null,
            ]);
        } else {
            if (News::where('is_breaking_news', true)->count() >= 5) {
                return redirect()->back()->with('error', 'Maksimal 5 berita dapat dijadikan Breaking News. Silakan hapus status breaking news pada berita lain terlebih dahulu.');
            }
            $news->update([
                'is_breaking_news' => true,
                'breaking_news_until' => now()->addHours(24),
            ]);
        }

        $this->cacheService->flushNewsCache();

        return redirect()->back()
            ->with('success', 'Status breaking news berhasil diubah.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(News $news): RedirectResponse
    {
        Gate::authorize('toggleFeatured', News::class);

        if (!$news->is_featured && News::where('is_featured', true)->count() >= 5) {
            return redirect()->back()->with('error', 'Maksimal 5 berita dapat dijadikan Berita Pilihan. Silakan hapus status berita pilihan pada berita lain terlebih dahulu.');
        }

        $news->update([
            'is_featured' => !$news->is_featured,
        ]);

        $this->cacheService->flushNewsCache();

        return redirect()->back()
            ->with('success', 'Status featured berhasil diubah.');
    }
}
