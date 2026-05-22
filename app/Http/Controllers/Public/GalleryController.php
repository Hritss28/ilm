<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\SeoService;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    /**
     * Display grid of all active galleries.
     */
    public function index(): View
    {
        $galleries = Gallery::query()
            ->active()
            ->with('images')
            ->orderByDesc('created_at')
            ->paginate(10);

        $seo = $this->seoService->generateForPage('Potret', 'Galeri foto berita dan kegiatan');

        return view('public.gallery.index', compact('galleries', 'seo'));
    }

    /**
     * Display a single gallery with all its images.
     */
    public function show(string $slug): View
    {
        $gallery = Gallery::query()
            ->active()
            ->where('slug', $slug)
            ->with(['images', 'creator'])
            ->firstOrFail();

        $seo = $this->seoService->generateForPage($gallery->title, $gallery->description ?? '');

        $latestGalleries = Gallery::query()
            ->active()
            ->where('id', '!=', $gallery->id)
            ->latest()
            ->take(4)
            ->get();

        return view('public.gallery.show', compact('gallery', 'seo', 'latestGalleries'));
    }
}
