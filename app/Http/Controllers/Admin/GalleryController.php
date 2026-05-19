<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Display a listing of galleries.
     */
    public function index(): View
    {
        $galleries = Gallery::with(['creator', 'images'])
            ->latest()
            ->paginate(12);

        return view('admin.galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery.
     */
    public function create(): View
    {
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created gallery with bulk image upload.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'images' => 'required|array|min:1|max:20',
            'images.*' => 'image|mimes:jpeg,png,webp|max:5120',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:500',
        ], [
            'images.required' => 'Minimal upload 1 gambar.',
            'images.max' => 'Maksimal 20 gambar per gallery.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $gallery = Gallery::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->id(),
        ]);

        // Upload images
        $captions = $request->input('captions', []);
        foreach ($request->file('images', []) as $index => $file) {
            $imagePath = $this->imageService->uploadGalleryImage($file, $gallery->id);

            GalleryImage::create([
                'gallery_id' => $gallery->id,
                'image_url' => $imagePath,
                'caption' => $captions[$index] ?? null,
                'order' => $index,
            ]);

            // Set first image as cover
            if ($index === 0) {
                $gallery->update(['cover_image' => $imagePath]);
            }
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery berhasil dibuat.');
    }

    /**
     * Display the specified gallery (redirects to edit).
     */
    public function show(Gallery $gallery): RedirectResponse
    {
        return redirect()->route('admin.galleries.edit', $gallery);
    }

    /**
     * Show the form for editing the specified gallery.
     */
    public function edit(Gallery $gallery): View
    {
        $gallery->load('images');

        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery.
     */
    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,webp|max:5120',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:500',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'integer|exists:gallery_images,id',
            'image_order' => 'nullable|string',
        ], [
            'images.max' => 'Maksimal 20 gambar per gallery.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Handle image reordering
        if ($request->filled('image_order')) {
            $order = json_decode($request->input('image_order'), true);
            if (is_array($order)) {
                foreach ($order as $position => $imageId) {
                    GalleryImage::where('id', $imageId)
                        ->where('gallery_id', $gallery->id)
                        ->update(['order' => $position]);
                }
            }
        }

        // Remove deleted images
        $existingImageIds = $request->input('existing_images', []);
        $imagesToDelete = $gallery->images()->whereNotIn('id', $existingImageIds)->get();
        foreach ($imagesToDelete as $image) {
            $this->imageService->delete($image->image_url);
            $image->delete();
        }

        // Upload new images
        if ($request->hasFile('images')) {
            $currentMaxOrder = $gallery->images()->max('order') ?? -1;
            $captions = $request->input('captions', []);

            foreach ($request->file('images') as $index => $file) {
                $imagePath = $this->imageService->uploadGalleryImage($file, $gallery->id);

                GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image_url' => $imagePath,
                    'caption' => $captions[$index] ?? null,
                    'order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        // Update cover image to first image by order
        $firstImage = $gallery->images()->orderBy('order')->first();
        $gallery->update(['cover_image' => $firstImage?->image_url]);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery berhasil diperbarui.');
    }

    /**
     * Remove the specified gallery and all its images.
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        // Delete all gallery images from storage
        foreach ($gallery->images as $image) {
            $this->imageService->delete($image->image_url);
        }

        // Delete cover image if different from gallery images
        if ($gallery->cover_image) {
            $this->imageService->delete($gallery->cover_image);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery berhasil dihapus.');
    }
}
