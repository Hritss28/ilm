<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\CacheService;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function __construct(
        protected ImageService $imageService,
        protected CacheService $cacheService
    ) {}

    /**
     * Display a listing of advertisements.
     */
    public function index(Request $request): View
    {
        $query = Advertisement::orderBy('position')->orderByDesc('priority');

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $advertisements = $query->paginate(20)->withQueryString();

        return view('admin.advertisements.index', compact('advertisements'));
    }

    /**
     * Show the form for creating a new advertisement.
     */
    public function create(): View
    {
        return view('admin.advertisements.create');
    }

    /**
     * Store a newly created advertisement in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,webp,gif|max:5120',
            'link_url' => 'required|url|max:500',
            'position' => 'required|in:top,sidebar,content,footer',
            'priority' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $imagePath = $this->imageService->uploadAdvertisement($request->file('image'));

        Advertisement::create([
            'title' => $validated['title'],
            'image_url' => $imagePath,
            'link_url' => $validated['link_url'],
            'position' => $validated['position'],
            'priority' => $validated['priority'],
            'is_active' => $request->boolean('is_active', true),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        $this->cacheService->flushAdsCache($validated['position']);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Iklan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified advertisement.
     */
    public function edit(Advertisement $advertisement): View
    {
        return view('admin.advertisements.edit', compact('advertisement'));
    }

    /**
     * Update the specified advertisement in storage.
     */
    public function update(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,webp,gif|max:5120',
            'link_url' => 'required|url|max:500',
            'position' => 'required|in:top,sidebar,content,footer',
            'priority' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data = [
            'title' => $validated['title'],
            'link_url' => $validated['link_url'],
            'position' => $validated['position'],
            'priority' => $validated['priority'],
            'is_active' => $request->boolean('is_active', true),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ];

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($advertisement->image_url) {
                $this->imageService->delete($advertisement->image_url);
            }
            $data['image_url'] = $this->imageService->uploadAdvertisement($request->file('image'));
        }

        $advertisement->update($data);

        $this->cacheService->flushAdsCache($validated['position']);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Iklan berhasil diperbarui.');
    }

    /**
     * Remove the specified advertisement from storage.
     */
    public function destroy(Advertisement $advertisement): RedirectResponse
    {
        // Delete the image file
        if ($advertisement->image_url) {
            $this->imageService->delete($advertisement->image_url);
        }

        $position = $advertisement->position;
        $advertisement->delete();

        $this->cacheService->flushAdsCache($position);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Iklan berhasil dihapus.');
    }
}
