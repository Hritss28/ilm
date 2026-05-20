<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\SeoService;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    /**
     * Display grid of all active videos.
     */
    public function index(): View
    {
        $videos = Video::query()
            ->active()
            ->orderByDesc('created_at')
            ->paginate(12);

        $seo = $this->seoService->generateForPage('Video', 'Kumpulan video berita terkini');

        return view('public.video.index', compact('videos', 'seo'));
    }

    /**
     * Display a single video with embedded player.
     * Increments view counter (session-based).
     */
    public function show(Video $video): View
    {
        if (!$video->is_active) {
            abort(404);
        }

        // Session-based view increment
        $sessionKey = "viewed_video_{$video->id}";
        if (!session()->has($sessionKey)) {
            $video->increment('views');
            session()->put($sessionKey, true);
        }

        $seo = $this->seoService->generateForPage($video->title, $video->description ?? '');

        $latestVideos = Video::query()
            ->active()
            ->where('id', '!=', $video->id)
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        return view('public.video.show', compact('video', 'seo', 'latestVideos'));
    }
}
