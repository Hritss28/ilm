<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Display a listing of videos.
     */
    public function index(Request $request): View
    {
        $videos = Video::with('creator')
            ->latest()
            ->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create(): View
    {
        return view('admin.videos.create');
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'video_url' => [
                'required',
                'url',
            ],
            'thumbnail' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageService->uploadThumbnail(
                $request->file('thumbnail'),
                'videos'
            );
        }

        Video::create($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video berhasil ditambahkan.');
    }

    /**
     * Display the specified video (redirects to edit).
     */
    public function show(Video $video): RedirectResponse
    {
        return redirect()->route('admin.videos.edit', $video);
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit(Video $video): View
    {
        return view('admin.videos.edit', compact('video'));
    }

    /**
     * Update the specified video.
     */
    public function update(Request $request, Video $video): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'video_url' => [
                'required',
                'url',
            ],
            'thumbnail' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($video->thumbnail) {
                $this->imageService->delete($video->thumbnail);
            }
            $validated['thumbnail'] = $this->imageService->uploadThumbnail(
                $request->file('thumbnail'),
                'videos'
            );
        } elseif ($request->boolean('remove_thumbnail')) {
            if ($video->thumbnail) {
                $this->imageService->delete($video->thumbnail);
            }
            $validated['thumbnail'] = null;
        }

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video berhasil diperbarui.');
    }

    /**
     * Remove the specified video.
     */
    public function destroy(Video $video): RedirectResponse
    {
        if ($video->thumbnail) {
            $this->imageService->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video berhasil dihapus.');
    }
}
